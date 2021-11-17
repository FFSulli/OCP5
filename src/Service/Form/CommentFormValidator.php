<?php

declare(strict_types=1);

namespace App\Service\Form;

use App\Service\Http\Session\Session;

class CommentFormValidator extends FormValidatorService implements FormInterface
{

    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function isValid(array $form): bool
    {
        if ($this->isEmpty($form['content'])) {
            $this->session->addFlashes('errorFormIsNull', 'Le commentaire est vide, merci de remplir le champ');
            return false;
        }

        return true;
    }
}
