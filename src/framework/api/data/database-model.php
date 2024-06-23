<?php
    namespace Framework\Api\Data;

    use PDO;

    class DatabaseModel
    {
        private readonly string $class;
        private readonly PDO $connection;

        public function __construct(string $class, PDO &$connection)
        {
            $this->class = $class;
            $this->connection = $connection;
        }

        public function fetchAll(string $query, ?array $params = null)
        {
            $statement = self::execute($query, $params);
            $result = $statement->fetchAll();
            return self::mapRowsToModel($this->class, $result);
        }

        public function execute(string $query, ?array $params = null) {
            $connection = &$this->connection;

            $statement = $connection->prepare($query);
            $statement->execute($params);

            return $statement;
        }

        private static function mapRowsToModel($model, $rows)
        {
            $entities = [];

            foreach ($rows as $row)
            {
                $entities[] = self::mapRowToModel($model, $row);
            }

            return $entities;
        }

        private static function mapRowToModel($model, $row)
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