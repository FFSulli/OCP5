<?php

declare(strict_types=1);

namespace App\Service\Form;

use App\Model\Repository\UserRepository;
use App\Service\Http\Session\Session;

class LoginFormValidator extends FormValidatorService implements FormInterface
{

    private UserRepository $userRepository;
    private Session $session;

    public function __construct(UserRepository $userRepository, Session $session)
    {

        $this->userRepository = $userRepository;
        $this->session = $session;
    }

    public function isValid(array $form): bool
    {
        $user = $this->userRepository->findOneBy(['email' => $form['email']]);

        if ($this->isEmpty($form['email']) && $this->isEmpty($form['password'])) {
            $this->session->addFlashes('errorFormIsNull', 'Le formulaire est vide, merci de remplir tous les champs');
            return false;
        }

        if ($this->isEmpty($form['email'])) {
            $this->session->addFlashes('errorEmailIsNull', 'Le champ email ne peut pas être vide');
            return false;
        }

        if (! $this->isEmail($form['email'])) {
            $this->session->addFlashes('errorEmailIsNotValid', "Le champ email n'est pas valide");
            return false;
        }

        if ($user === null) {
            $this->session->addFlashes('errorEmailDoesntExist', 'Aucun compte associé à cette adresse e-mail');
            return false;
        }

        if ($this->isEmpty($form['password'])) {
            $this->session->addFlashes('errorPasswordIsNull', 'Le champ mot de passe ne peut pas être vide');
            return false;
        }

        if (! password_verify($form['password'], $user->getPassword())) {
            $this->session->addFlashes('errorPasswordIsNotIdentical', "Mot de passe erroné");
            return false;
        }

        return true;
    }
}
