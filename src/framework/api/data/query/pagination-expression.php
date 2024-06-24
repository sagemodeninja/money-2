<?php
namespace Framework\Api\Data\Query;

use PDO;
use Exception;

class PaginationExpression implements Expression
{
    private static bool $hasOrExpression = false;
    
    private string $type;
    private int $value;

    public function __construct(string $type, int $value)
    {
        if ($type == 'OFFSET' && !PaginationExpression::$hasOrExpression) {
            throw new Exception("Cannot use 'OFFSET' before 'LIMIT'.");
        }

        $this->type = $type;
        $this->value = $value;

        # Unlocks usage of 'OFFSET'.
        PaginationExpression::$hasOrExpression = $type == 'LIMIT';
    }

    public function build(): mixed
    {
        $token = $this->type;
        $param = ':_p' . strtolower($this->type);

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