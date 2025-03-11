<?php

namespace App\API\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class HttpPut
{
    public string $uri;
    public function __construct(string $uri)
    {
        $this->uri = $uri;
    }
}