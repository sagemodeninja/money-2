<?php
namespace Framework\Api\Data\Query;

class OrderExpression implements Expression
{
    private string $field;
    private RowOrder $order;

    public function __construct(string $field, RowOrder $order)
    {
        $this->field = $field;
        $this->order = $order;
    }

    public function build(): mixed
    {
        $field = $this->field;
        $order = $this->order->value;

        return [
            'clause' => "`$field` $order"
        ];
    }
}
?>