<?php
namespace Framework\Api\Data\Query;

use Exception;

class WhereExpression implements Expression
{
    # Used to keep track of known params.
    private static array $knownParams = [];
    private static bool $hasInstance = false;

    private string $field;
    private string $operator;
    private mixed $value;
    private string $logic;
    private bool $first;

    public function __construct(string $field, string $operator, mixed $value, string $logic)
    {
        if ($logic == 'OR' && !WhereExpression::$hasInstance) {
            throw new Exception("The logic 'OR' cannot be used on the first where expression.");
        }

        $this->field = $field;
        $this->operator = $operator;
        $this->value = $value;
        $this->logic = $logic;
        $this->first = !WhereExpression::$hasInstance;

        # Subsequent instances will not be first.
        WhereExpression::$hasInstance = true;
    }

    public function build(): mixed
    {
        $field = $this->field;
        $param = self::getFieldParam($field);
        $logic = !$this->first ? $this->logic : null;

        return [
            'clause' => implode(' ', [ $logic, "`$field`", $this->operator, $param]),
            'param' => [$param => $this->value]
        ];
    }

    private static function getFieldParam(string $field)
    {
        # Transform PascalCase, camelCase, and kebab-case into snake_case.
        $param = ':w_' . strtolower(preg_replace(['/(?<!^)([A-Z])/', '/-/'], ['_$1', '_'], $field));
        
        # Ensure that param names are always unique.
        if (isset(self::$knownParams[$param])) {
            $param .= '_' . ++self::$knownParams[$param];
        } else {
            self::$knownParams[$param] = 0;
        }

        return $param;
    }
}
?>