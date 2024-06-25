<?php
namespace Framework\Api\Data\Query;

class UpdateQuery
{
    private array $changes;

    public function __construct(array $changes)
    {
        $this->changes = $changes;
    }

    /* This method breaks the pattern for now by accepting a 2nd parameter until I figure
     out how to handle the WHERE clause of updates. */
    public function build(string $table, int $id)
    {
        $expressions = [];
        $params = [':u_primary_key' => $id];

        foreach ($this->changes as $name => $value)
        {
            $field = str_replace(' ', '', ucwords(str_replace(['-','_'], ' ', $name)));
            $param = self::getFieldParam($name);
            
            $expressions[] = "`$field` = $param";
            $params += [$param => $value];
        }

        # Id is assumed to be the primary key for now.
        $query = "UPDATE $table SET " . implode(', ', $expressions) . " WHERE `Id` = :u_primary_key";

        return [
            'query' => $query,
            'params' => $params
        ];
    }

    private static function getFieldParam(string $field)
    {
        # Transform PascalCase, camelCase, and kebab-case into snake_case.
        $param = ':u_' . strtolower(preg_replace(['/(?<!^)([A-Z])/', '/-/'], ['_$1', '_'], $field));
        return $param;
    }
}
?>