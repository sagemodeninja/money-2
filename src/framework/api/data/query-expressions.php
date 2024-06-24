<?php
namespace Framework\Api\Data;

class QueryExpressions
{
    private array $expressions = [];

    public function add(string $type, Expression $expression)
    {
        $this->expressions[] = [
            'type' => $type,
            'expression' => $expression
        ];
    }

    public function build(string $table)
    {
        # Where
        $results = [
            'where' => [],
            'pagination' => [],
            'params' => []
        ];

        foreach ($this->expressions as $expression)
        {
            $result = $expression['expression']->build();
            $results[$expression['type']][] = $result['clause'];
            $results['params'][] = $result['param'];
        }

        return [
            'query' => "SELECT * FROM " . $table . ' WHERE ' . implode(' AND ', $results['where']) . ' ' . implode(' ', $results['pagination']),
            'params' => $results['params']
        ];
    }
}
?>