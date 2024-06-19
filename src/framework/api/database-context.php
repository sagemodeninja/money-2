<?php
    namespace Framework\Api;

    use PDO;

    abstract class DatabaseContext
    {
        public function __construct()
        {
            $connection = self::connect();
            $builder = new DatabaseContextBuilder();

            $this->configure($builder);
            $builder->build($this, $connection);
        }

        public static function connect()
        {
            $dbhost = getenv('MYSQL_HOST');
            $dbuser = getenv('MYSQL_USER');
            $dbpassword = getenv('MYSQL_PASSWORD');
            $dbname = getenv('MYSQL_DATABASE');

            $dsn = "mysql:host=$dbhost;dbname=$dbname;charset=utf8mb4";
            $connection = new PDO($dsn, $dbuser, $dbpassword);

            return $connection;
        }

        abstract protected function configure(DatabaseContextBuilder $builder);
    }
?>