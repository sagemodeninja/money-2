<?php
namespace Framework\Api\Data\Query;

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
        $query = "SELECT * FROM $table";
        $expressions = self::buildExpressions($this->expressions);

        if (count($clauses = $expressions['where']) > 0) {
            $query .= ' WHERE ' . implode(' ', $clauses);
        }

        if (count($clauses = $expressions['pagination']) > 0) {
            $query .= ' ' . implode(' ', $clauses);
        }

        if (count($clauses = $expressions['order']) > 0) {
            $query .= ' ORDER BY ' . implode(', ', $clauses);
        }

        return [
            'query' => $query,
            'params' => $expressions['params'],
            'typedParams' => $expressions['typedParams'],
        ];
    }

    private static function buildExpressions(array $expressions)
    {
        $results = [
            'where' => [],
            'pagination' => [],
            'params' => [],
            'typedParams' => []
        ];

        foreach ($expressions as $expression)
        {
            $result = $expression['expression']->build();
            
            $type = $expression['type'];
            $results[$type][] = $result['clause'];

            if (isset($result['param'])) {
                $results['params'] += $result['param'];
            }

            if (isset($result['typedParam'])) {
                $results['typedParams'][] = $result['typedParam'];
            }
        }

        return $results;
    }
}
?>