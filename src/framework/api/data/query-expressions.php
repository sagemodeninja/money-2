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
            'params' => [],
            'typedParams' => []
        ];

        foreach ($this->expressions as $expression)
        {
            $result = $expression['expression']->build();
            
            $type = $expression['type'];
            $results[$type][] = $result['clause'];

            if (array_key_exists('param', $result)) {
                $results['params'] += $result['param'];
            }

            if (array_key_exists('typedParam', $result)) {
                $results['typedParams'][] = $result['typedParam'];
            }
        }

        return [
            'query' => "SELECT * FROM " . $table . ' WHERE ' . implode(' AND ', $results['where']) . ' ' . implode(' ', $results['pagination']),
            'params' => $results['params'],
            'typedParams' => $results['typedParams'],
        ];
    }
}
?>