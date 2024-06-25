<?php
namespace Framework\Api\Data\Query;

class UpdateQueryBuilder implements IQueryBuilder
{
    private int $id;
    private array $changes;

    public function __construct(int $id, array $changes)
    {
        $this->id = $id;
        $this->changes = $changes;
    }

    /* This method breaks the pattern for now by accepting a 2nd parameter until I figure
     out how to handle the WHERE clause of updates. */
    public function build(string $table)
    {
        $expressions = [];
        $params = [':u_primary_key' => $this->id];

        foreach ($this->changes as $name => $value)
        {
            $field = str_replace(' ', '', ucwords(str_replace(['-','_'], ' ', $name)));
            $param = self::getFieldParam($name);
            
            $expressions[] = "`$field` = $param";
            $params[] = [
                'name' => $param,
                'value' => $value
            ];
        }

        # Id is assumed to be the primary key for now.
        $query = "UPDATE $table SET " . implode(', ', $expressions) . " WHERE `Id` = :u_primary_key";

        return [
            'statement' => $query,
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