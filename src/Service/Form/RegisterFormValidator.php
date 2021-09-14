<?php

declare(strict_types=1);

namespace App\Service\Form;

use App\Model\Repository\UserRepository;
use App\Service\Http\Session\Session;

class RegisterFormValidator extends FormValidatorService implements FormInterface
{

    private Session $session;
    private UserRepository $userRepository;

    public function __construct(Session $session, UserRepository $userRepository)
    {
        $this->session = $session;
        $this->userRepository = $userRepository;
    }

    public function isValid(array $form): bool
    {

        $isValid = true;

        if ($this->isEmpty($form['firstName']) && $this->isEmpty($form['lastName']) && $this->isEmpty($form['email']) && $this->isEmpty($form['password'])) {
            $this->session->addFlashes('errorFormIsNull', 'Le formulaire est vide, merci de remplir tous les champs');
            $isValid = false;
        }

        if ($this->isEmpty($form['firstName'])) {
            $this->session->addFlashes('errorFirstNameIsNull', 'Le champ prénom ne peut pas être vide');
            $isValid = false;
        }

        if ($this->isEmpty($form['lastName'])) {
            $this->session->addFlashes('errorLastNameIsNull', 'Le champ nom ne peut pas être vide');
            $isValid = false;
        }

        if (! $this->isEmpty($form['firstName']) && ! $this->isAplhabeticalOnly($form['firstName'])) {
            $this->session->addFlashes('errorFirstNameIsNotAlphabetical', 'Le champ prénom ne peut contenir que des lettres');
            $isValid = false;
        }

        if (! $this->isEmpty($form['lastName']) && ! $this->isAplhabeticalOnly($form['lastName'])) {
            $this->session->addFlashes('errorLastNameIsNotAlphabetical', 'Le champ nom ne peut contenir que des lettres');
            $isValid = false;
        }

        if ($this->isEmpty($form['email'])) {
            $this->session->addFlashes('errorEmailIsNull', 'Le champ email ne peut pas être vide');
            $isValid = false;
        }

        if (! $this->isEmpty($form['email']) && ! $this->isEmail($form['email'])) {
            $this->session->addFlashes('errorEmailIsNotValid', "Le champ email n'est pas valide");
            $isValid = false;
        }

        if (! $this->isEmpty($form['email']) && $this->userRepository->findOneBy(['email' => $form['email']])) {
            $this->session->addFlashes('emailAlreadyExists', 'Cette adresse email est déjà utilisée');
            $isValid = false;
        }

        if ($this->isEmpty($form['password'])) {
            $this->session->addFlashes('errorPasswordIsNull', 'Le champ mot de passe ne peut pas être vide');
            $isValid = false;
        }

        if (! $this->isEmpty($form['password']) && strlen($form['password']) < 8) {
            $this->session->addFlashes('errorPasswordIsTooShort', 'Le champ mot de passe doit faire plus de 8 caractères');
            $isValid = false;
        }

        if (! $this->isEmpty($form['password']) && ! preg_match("#[0-9]+#", $form['password']) || !preg_match("#[a-zA-Z]+#", $form['password'])) {
            $this->session->addFlashes('errorPasswordNeedsSpecialCharacters', 'Le champ mot de passe doit contenir au moins une lettre et un chiffre');
            $isValid = false;
        }

        if ($this->isEmpty($form['confirmPassword'])) {
            $this->session->addFlashes('errorConfirmPasswordIsNull', 'Le champ confirmation du mot de passe ne peut pas être vide');
            $isValid = false;
        }

        if (! $this->isEmpty($form['confirmPassword']) && ! $this->isMatching($form['password'], $form['confirmPassword'])) {
            $this->session->addFlashes('errorPasswordIsNotIdentical', "Les mots de passe saisis ne sont pas identiques");
            $isValid = false;
        }

        return $isValid;
    }
}
