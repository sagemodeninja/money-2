<?php
namespace Framework\Api\Data;

use PDO;

class DatabaseContextBuilder
{
    private array $models;

    public function addModel(string $class, string $model, string $table = null)
    {
        $this->models[] = [
            'class' => $class,
            'model' => $model,
            'table' => $table ?? $model
        ];
    }

    public function build(object &$context, PDO &$connection)
    {
        foreach ($this->models as $config)
        {
            $class = $config['class'];
            $model = $config['model'];
            $table = $config['table'];

            $context->$model = new DatabaseModel($connection, $class, $table);
        }
    }
}
?>