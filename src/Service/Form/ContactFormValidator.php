<?php

declare(strict_types=1);

namespace App\Service\Form;

use App\Service\Http\Session\Session;

class ContactFormValidator extends FormValidatorService implements FormInterface
{

    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function isValid(array $form): bool
    {
        if ($this->isEmpty($form['firstName']) && $this->isEmpty($form['lastName']) && $this->isEmpty($form['email']) && $this->isEmpty($form['message'])) {
            $this->session->addFlashes('errorFormIsNull', 'Le formulaire est vide, merci de remplir tous les champs.');
            return false;
        }

        if ($this->isEmpty($form['lastName'])) {
            $this->session->addFlashes('errorLastNameIsNull', 'Le champ nom ne peut pas être vide');
            return false;
        }

        if ($this->isEmpty($form['firstName'])) {
            $this->session->addFlashes('errorFirstNameIsNull', 'Le champ prénom ne peut pas être vide');
            return false;
        }

        if ($this->isEmpty($form['email'])) {
            $this->session->addFlashes('errorEmailIsNull', 'Le champ email ne peut pas être vide');
            return false;
        }

        if ($this->isEmpty($form['message'])) {
            $this->session->addFlashes('errorMessageIsNull', 'Le champ message ne peut pas être vide');
            return false;
        }

        if (! $this->isAplhabeticalOnly($form['firstName'])) {
            $this->session->addFlashes('errorFirstNameIsNotAlphabetical', 'Le champ prénom ne doit contenir que des lettres');
            return false;
        }

        if (! $this->isAplhabeticalOnly($form['lastName'])) {
            $this->session->addFlashes('errorLastNameIsNotAlphabetical', 'Le champ nom ne doit contenir que des lettres');
            return false;
        }

        if (! $this->isEmail($form['email'])) {
            $this->session->addFlashes('errorEmailIsNotValid', "Le champ email n'est pas valide");
            return false;
        }

        return true;
    }
}
