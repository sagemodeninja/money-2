<?php
    namespace Framework\Api;
    
    use PDO;

    class DatabaseContextBuilder
    {
        private array $models;

        public function addModel(string $model, string $class)
        {
            $this->models[] = [
                'model' => $model,
                'class' => $class
            ];
        }

        public function build(object &$context, PDO &$connection)
        {
            foreach ($this->models as $config)
            {
                $model = $config['model'];
                $class = $config['class'];

                $context->$model = new DatabaseModel($class, $connection);
            }
        }
    }
?>