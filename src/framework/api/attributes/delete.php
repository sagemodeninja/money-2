<?php
namespace Framework\Api\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Delete extends HttpMethodAttribute {}
?>