<?php
namespace Framework\Api\Data\Query;

interface IQueryBuilder
{
    public function build(string $table);
}
?>