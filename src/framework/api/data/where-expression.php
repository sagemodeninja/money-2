<?php
namespace Framework\Api\Data;

use PDO;

class WhereExpression implements Expression
{
    public string $field;
    public string $operator;
    public mixed $value;

    public function __construct(string $field, string $operator, mixed $value)
    {
        $this->field = $field;
        $this->operator = $operator;
        $this->value = $value;
    }

    public function build(): mixed
    {
        $field = $this->field;

        # Normalize $field into a param.
        $param = strtolower(preg_replace('/(?<!^)([A-Z])/', '_$1', $field)); # Transform PascalCase or camelCase into snake_case.
        $param = str_replace('-', '_', $param); # Transform kebabCase into snake_case.
        $param = ':' . $param; # Append ':'

        return [
            'clause' => "`$field` " . $this->operator . " $param",
            'param' => [
                'name' => $param,
                'value' => $this->value,
                'type' => self::getValueType($this->value)
            ]
        ];
    }

    private static function getValueType(mixed $value)
    {
        if (is_int($value)) {
            return PDO::PARAM_INT;
        } elseif (is_bool($value)) {
            return PDO::PARAM_BOOL;
        } elseif (is_null($value)) {
            return PDO::PARAM_NULL;
        } elseif (is_resource($value)) {
            return PDO::PARAM_LOB;
        } else {
            return PDO::PARAM_STR;
        }
    }
}
?>