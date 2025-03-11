<?php

namespace App\API\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class HttpDelete
{
    public string $uri;
    public function __construct(string $uri)
    {
        $this->uri = $uri;
    }
}