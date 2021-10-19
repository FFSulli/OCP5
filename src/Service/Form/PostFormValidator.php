<?php

declare(strict_types=1);

namespace App\Service\Form;

use App\Service\Http\Session\Session;

class PostFormValidator extends FormValidatorService implements FormInterface
{

    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function isValid(array $form): bool
    {
        $isValid = true;

        if ($this->isEmpty($form['title']) && $this->isEmpty($form['excerpt']) && $this->isEmpty($form['content'])) {
            $this->session->addFlashes('errorFormIsNull', 'Le formulaire est vide, merci de remplir tous les champs.');
            $isValid = false;
        }

        if ($this->isEmpty($form['title'])) {
            $this->session->addFlashes('errorTitleIsNull', 'Le champ titre ne peut pas être vide');
            $isValid = false;
        }

        if ($this->isEmpty($form['excerpt'])) {
            $this->session->addFlashes('errorExcerptIsNull', 'Le champ extrait ne peut pas être vide');
            $isValid = false;
        }

        if ($this->isEmpty($form['content'])) {
            $this->session->addFlashes('errorContentIsNull', 'Le champ corps de l\'article ne peut pas être vide');
            $isValid = false;
        }

        return $isValid;
    }
}
