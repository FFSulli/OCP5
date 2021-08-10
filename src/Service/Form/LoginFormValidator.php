<?php

declare(strict_types=1);

namespace App\Service\Form;


use App\Model\Repository\UserRepository;

class LoginFormValidator extends FormValidatorService implements FormInterface
{

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {

        $this->userRepository = $userRepository;
    }

    public function isValid(array $form): bool
    {
        $user = $this->userRepository->findOneBy(['email' => $form['email']]);

        if ($this->isNull($form)) {
            return false;
        }

        if ($this->isEmpty($form['email']) || $this->isEmpty($form['password'])) {
            return false;
        }

        if (! $this->isEmail($form['email'])) {
            return false;
        }

        if (! password_verify($form['password'], $user->getPassword())) {
            return false;
        }

        return true;
    }
}
