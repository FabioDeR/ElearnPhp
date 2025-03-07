<?php
namespace App\Core\Exceptions;

interface ValidatorInterface
{
    public function validate(array $data): array;
}