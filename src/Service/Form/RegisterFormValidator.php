<?php

declare(strict_types=1);

namespace App\Service\Form;

class RegisterFormValidator extends FormValidatorService implements FormInterface
{
    public function isValid(?array $form): bool
    {
        if ($this->isNull($form)) {
            return false;
        }

        if ($this->isEmpty($form['firstName']) || $this->isEmpty($form['lastName']) || $this->isEmpty($form['email']) || $this->isEmpty($form['password'])) {
            return false;
        }

        if (! $this->isAplhabeticalOnly($form['firstName']) || ! $this->isAplhabeticalOnly($form['lastName'])) {
            return false;
        }

        if (! $this->isEmail($form['email'])) {
            return false;
        }

        if (! $this->isMatching($form['password'], $form['confirmPassword'])) {
            return false;
        }

        return true;
    }
}
