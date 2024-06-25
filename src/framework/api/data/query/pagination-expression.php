<?php
namespace Framework\Api\Data\Query;

use PDO;

class PaginationExpression implements Expression
{
    private string $type;
    private int $value;

    public function __construct(string $type, int $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    public function build(): mixed
    {
        $token = $this->type;
        $param = ':p_' . strtolower($this->type);

        return [
            'clause' => "$token $param",
            'param' => [
                'name' => $param,
                'value' => $this->value,
                'type' => PDO::PARAM_INT
            ]
        ];
    }
}
?>