<?php
# TODO: This script is getting big, might want to start refactoring.

namespace Framework\Api\Data;

use Exception;
use PDO;
use ReflectionClass;
use Framework\Api\Data\Query\{InsertQueryBuilder,UpdateQueryBuilder,SelectQueryBuilder};
use Framework\Api\Data\Query\{IQueryBuilder,OrderExpression,WhereExpression,PaginationExpression,RowOrder};
use ReflectionProperty;

class DatabaseModel
{
    private PDO $connection;
    private string $class;
    private string $table;

    private SelectQueryBuilder $queries;
    private array $modelsForInsertion = [];
    private array $trackedModels = [];

    public function __construct(PDO &$connection, string $class, string $table)
    {
        $this->connection = $connection;
        $this->class = $class;
        $this->table = $table;
        $this->queries = new SelectQueryBuilder();
    }

    public function where(string|array $field, mixed $operatorOrValue = null, mixed $value = null)
    {
        return $this->_where($field, $operatorOrValue, $value, 'AND');
    }

    public function orWhere(string|array $field, mixed $operatorOrValue = null, mixed $value = null)
    {
        return $this->_where($field, $operatorOrValue, $value, 'OR');
    }

    public function top(int $top)
    {
        $expression = new PaginationExpression('LIMIT', $top);
        $this->queries->add('pagination', $expression);
        return $this;
    }

    public function skip(int $skip)
    {
        $expression = new PaginationExpression('OFFSET', $skip);
        $this->queries->add('pagination', $expression);
        return $this;
    }

    public function orderBy(string|array $field, ?RowOrder $order = null) {
        $blocks = is_string($field)
            ? [[$field, $order]]
            : $field;

        foreach ($blocks as $block)
        {
            $expression = new OrderExpression($block[0], $block[1] ?? RowOrder::Ascending);
            $this->queries->add('order', $expression);
        }

        return $this;
    }

    public function add(object $model)
    {
        if (($class = get_class($model)) != $this->class) {
            throw new Exception("Cannot add an instance of '$class' to table '" . $this->table . "'.");
        }

        $this->modelsForInsertion[] = $model;

        return $this;
    }

    # Terminators - Executes the query builders.
    public function all()
    {
        $statement = $this->executeQuery($this->queries);
        $result = $statement->fetchAll();
        return self::rowsToModel($this->class, $result);
    }

    public function first()
    {
        $this->top(1);

        $statement = $this->executeQuery($this->queries);
        $result = $statement->fetch();
        $model = self::rowToModel($this->class, $result);

        # Track model
        $this->trackedModels[] = [
            'original' => clone $model,
            'tracked' => $model,
            'persisted' => true
        ];

        return $model;
    }

    public function save()
    {
        foreach ($this->modelsForInsertion as $model)
        {
            $query = new InsertQueryBuilder($model);
            $this->executeQuery($query);
        }

        # Tracked models
        foreach ($this->trackedModels as $model)
        {
            $original = $model['original'];
            $tracked = $model['tracked'];

            if (count($changes =  self::getModelChanges($original, $tracked)) > 0) {
                $query = new UpdateQueryBuilder($original->id, $changes);
                self::executeQuery($query);
            }
        }
    }

    private function executeQuery(IQueryBuilder $builder)
    {
        $connection = &$this->connection;
        
        $query = $builder->build($this->table);
        $statement = $connection->prepare($query['statement']);

        foreach ($query['params'] as $param) {
            if (isset($param['type'])) {
                $statement->bindValue(
                    $param['name'],
                    $param['value'],
                    $param['type']
                );
            } else {
                $statement->bindValue(
                    $param['name'],
                    $param['value']
                );
            }
        }

        $statement->execute();
        return $statement;
    }

    private function _where(string|array $field, mixed $operatorOrValue, mixed $value, string $logic)
    {
        $blocks = is_string($field)
            ? [[$field, $operatorOrValue, $value]]
            : $field;

        foreach ($blocks as $block)
        {
            $operator = isset($block[2]) ? $block[1] : '=';
            $expression = new WhereExpression($block[0], $operator, $block[2] ?? $block[1], $logic);
            $this->queries->add('where', $expression);
        }

        return $this;
    }

    private static function getModelChanges($old, $new)
    {
        $changes = [];

        $reflection = new ReflectionClass($old);
        $properies = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($properies as $property)
        {
            if ($property->getValue($old) !== ($nval = $property->getValue($new))) {
                $changes[$property->getName()] = $nval;
            }
        }

        return $changes;
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