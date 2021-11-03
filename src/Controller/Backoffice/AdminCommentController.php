<?php

namespace App\Controller\Backoffice;

use App\Model\Entity\Comment;
use App\Model\Repository\CommentRepository;
use App\Model\Repository\UserRepository;
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
    private UserRepository $userRepository;

    public function __construct(Request $request, View $view, Session $session, Authentication $authentication, CommentRepository $commentRepository, Csrf $csrf, UserRepository $userRepository)
    {
        $this->request = $request;
        $this->view = $view;
        $this->session = $session;
        $this->authentication = $authentication;
        $this->commentRepository = $commentRepository;
        $this->csrf = $csrf;
        $this->userRepository = $userRepository;
    }

    public function displayAdminCommentAction(): Response
    {
        if (!$this->authentication->isAdmin()) {
            return new RedirectResponse('index.php', 403);
        }

        $comments = $this->commentRepository->findAll();

        foreach ($comments as $comment) {
            /** @var Comment $comment */
            $commentor = $this->userRepository->find($comment->getUserFk());
        }

        $data = $this->request->request()->all();

        if ($this->request->getMethod() === 'POST') {

            if ($this->authentication->isAdmin()) {
                if ($data['allowComment']) {
                    if ($this->commentRepository->find($data['allowComment']) !== null) {
                        $comment = $this->commentRepository->find((int) $data['allowComment']);
                        if ($this->csrf->checkToken($data['csrfToken'])) {
                            $this->csrf->deleteToken();
                        }
                        $this->commentRepository->allowComment($comment);

                        $this->session->addFlashes('success', 'Commentaire validé.');
                        return new RedirectResponse('index.php?action=admin_comments', 302);
                    }
                }
                if ($data['deleteComment']) {
                    if ($this->commentRepository->find($data['deleteComment']) !== null) {

                        $comment = $this->commentRepository->find((int) $data['deleteComment']);

                        if ($this->csrf->checkToken($data['csrfToken'])) {
                            $this->csrf->deleteToken();
                        }
                        $this->commentRepository->delete($comment);

                        $this->session->addFlashes('success', 'Commentaire supprimé.');
                        return new RedirectResponse('index.php?action=admin_comments', 302);
                    }
                }
            }
        }

        return new Response($this->view->render([
            'template' => '../backoffice/comments',
            'data' => [
                'comments' => $comments,
                'commentor' => $commentor,
                'csrfToken' => $this->session->get('csrfToken')
            ]
        ]));
    }
}
