<?php

namespace App\Controller\Backoffice;

use App\Model\Repository\CommentRepository;
use App\Service\Authentication\Authentication;
use App\Service\CSRF\Csrf;
use App\Service\Http\RedirectResponse;
use App\Service\Http\Request;
use App\Service\Http\Response;
use App\Service\Http\Session\Session;
use App\View\View;

class AdminCommentController
{
    private Request $request;
    private View $view;
    private Session $session;
    private Authentication $authentication;
    private CommentRepository $commentRepository;
    private Csrf $csrf;

    public function __construct(Request $request, View $view, Session $session, Authentication $authentication, CommentRepository $commentRepository, Csrf $csrf)
    {
        $this->request = $request;
        $this->view = $view;
        $this->session = $session;
        $this->authentication = $authentication;
        $this->commentRepository = $commentRepository;
        $this->csrf = $csrf;
    }

    public function displayAdminCommentAction(): Response
    {
        if (!$this->authentication->isAdmin()) {
            return new RedirectResponse('index.php', 403);
        }

        $this->csrf->generateToken();

        $comments = $this->commentRepository->findAll();

        $data = $this->request->request()->all();

        if ($this->request->getMethod() === 'POST') {

            if ($this->authentication->isAdmin()) {
                if ($this->commentRepository->find($data['allowComment']) !== null) {
                    if ($data['allowComment'] && $this->csrf->checkToken($data['csrfToken'])) {
                        $comment = $this->commentRepository->find((int) $data['allowComment']);
                        $this->commentRepository->allowComment($comment);
                        $this->csrf->deleteToken();

                        $this->session->addFlashes('success', 'Commentaire validé.');
                        return new RedirectResponse('index.php?action=admin_comments', 302);
                    }
                }
            }
        }

        return new Response($this->view->render([
            'template' => '../backoffice/comments',
            'data' => [
                'comments' => $comments,
                'csrfToken' => $this->session->get('csrfToken')
            ]
        ]));
    }
}
