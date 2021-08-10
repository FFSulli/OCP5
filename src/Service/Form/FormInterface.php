<?php

declare(strict_types=1);

namespace App\Service\Form;

interface FormInterface
{
    public function isValid(array $form): bool;
}
