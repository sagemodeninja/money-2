<?php
namespace Framework\Api\Data\Query;

use ReflectionClass;
use ReflectionProperty;

class InsertQuery
{
    private object $model;

    public function __construct(object $model)
    {
        $this->model = $model;
    }

    public function build(string $table)
    {
        $model = $this->model;
        $reflection = new ReflectionClass($model);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);

        $fields = [];
        $paramNames = [];
        $params = [];

        foreach ($properties as $property)
        {
            $name = $property->getName();

            if (!isset($model->$name)) {
                continue;
            }

            $value = $property->getValue($model);

            $field = str_replace(' ', '', ucwords(str_replace(['-','_'], ' ', $name)));
            $param = self::getFieldParam($name);
            
            $fields[] = "`$field`";
            $paramNames[] = $param;
            $params += [$param => $value];
        }

        $query = "INSERT INTO $table (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $paramNames) . ")";

        return [
            'query' => $query,
            'params' => $params
        ];
    }

    private static function getFieldParam(string $field)
    {
        # Transform PascalCase, camelCase, and kebab-case into snake_case.
        $param = ':f_' . strtolower(preg_replace(['/(?<!^)([A-Z])/', '/-/'], ['_$1', '_'], $field));
        return $param;
    }
}
?>