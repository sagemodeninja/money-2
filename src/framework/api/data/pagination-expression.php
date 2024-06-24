<?php
namespace Framework\Api\Data;

use PDO;

class PaginationExpression implements Expression
{
    public string $type;
    public int $value;

    public function __construct(string $type, int $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    public function build(): mixed
    {
        $token = $this->type;
        $param = ':' . strtolower($this->type);

        return [
            'clause' => "$token $param",
            'typedParam' => [
                'name' => $param,
                'value' => $this->value,
                'type' => PDO::PARAM_INT
            ]
        ];
    }
}
?>