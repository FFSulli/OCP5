<?php

declare(strict_types=1);

namespace App\Service\Form;

class FormValidatorService
{
    public function isEmail(string $email): bool
    {
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

    public function isEmpty(string $field): bool
    {
        if (! empty($field)) {
            return false;
        }
        return true;
    }

    public function isAplhabeticalOnly(string $field): bool
    {
        if (! preg_match("/^[a-zA-Z -]+$/", $field)) {
            return false;
        }
        return true;
    }

    public function isNull(?array $form): bool
    {
        if ($form === null) {
            return true;
        }
        return false;
    }
}
