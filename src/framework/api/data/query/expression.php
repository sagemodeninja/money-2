<?php
namespace Framework\Api\Data\Query;

interface Expression
{
    public function build(): mixed;
}
?>