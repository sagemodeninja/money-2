<?php
namespace Framework\Api\Data;

use Exception;
use PDO;
use Framework\Api\Data\{QueryExpressions,WhereExpression,PaginationExpression};

class DatabaseModel
{
    private PDO $connection;
    private string $class;
    private string $table;

    private QueryExpressions $expressions;
    private bool $hasTopExpression = false;

    public function __construct(PDO &$connection, string $class, string $table)
    {
        $this->connection = $connection;
        $this->class = $class;
        $this->table = $table;

        $this->expressions = new QueryExpressions();
    }

    public function where(string $field, mixed $value)
    {
        $expression = new WhereExpression($field, '=', $value);
        $this->expressions->add('where', $expression);
        return $this;
    }

    public function top(int $top)
    {
        $expression = new PaginationExpression('LIMIT', $top);
        $this->expressions->add('pagination', $expression);
        $this->hasTopExpression = true;
        return $this;
    }

    public function skip(int $skip)
    {
        if (!$this->hasTopExpression) {
            throw new Exception('Method skip() can only be used after a top().');
        }

        $expression = new PaginationExpression('OFFSET', $skip);
        $this->expressions->add('pagination', $expression);
        return $this;
    }

    public function all()
    {
        $statement = $this->exececuteExpression();
        $result = $statement->fetchAll();
        return self::rowsToModel($this->class, $result);
    }

    public function first()
    {
        $this->top(1);
        $statement = $this->exececuteExpression();
        $result = $statement->fetch();
        return self::rowToModel($this->class, $result);
    }

    public function exececuteExpression()
    {
        $query = $this->expressions->build($this->table);
        return self::execute($query['query'], [], $query['params']);
    }

    public function execute(string $query, ?array $params = null, ?array $typedParams = null) {
        $connection = &$this->connection;
        $statement = $connection->prepare($query);

        # TODO: Merge with typed params
        foreach ($params as $name => $value) {
            $statement->bindValue($name, $value);
        }

        foreach ($typedParams ?? [] as $param) {
            $statement->bindValue(
                $param['name'],
                $param['value'],
                $param['type']
            );
        }

        $statement->execute();
        return $statement;
    }

    private static function rowsToModel($model, $rows)
    {
        return array_map(
            fn ($row) => self::rowToModel($model, $row),
            $rows
        );
    }

    private static function rowToModel($model, $row)
    {
        $entity = new $model();

        foreach ($row as $key => $value)
        {
            $key = lcfirst($key);

            if (property_exists($entity, $key))
            {
                $entity->$key = $value;
            }
        }

        return $entity;
    }
}
?>