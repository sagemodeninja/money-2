<?php
namespace Framework\Api\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Patch extends HttpMethodAttribute {}
?>