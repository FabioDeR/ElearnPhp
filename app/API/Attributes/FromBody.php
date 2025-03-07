<?php

namespace App\API\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
class FromBody
{
    // Vous pouvez ajouter des options ici si besoin (par exemple, définir des règles de désérialisation)
}