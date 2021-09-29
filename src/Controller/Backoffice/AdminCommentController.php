<?php

namespace App\Controller\Backoffice;

use App\Model\Repository\CommentRepository;
use App\Service\Http\Response;
use App\View\View;

class AdminCommentController
{
    /**
     * @var View
     */
    private View $view;
    private CommentRepository $commentRepository;

    public function __construct(View $view, CommentRepository $commentRepository)
    {
        $this->view = $view;
        $this->commentRepository = $commentRepository;
    }

    public function displayAdminCommentAction(): Response
    {
        $comments = $this->commentRepository->findAll();

        return new Response($this->view->render([
            'template' => '../backoffice/comments',
            'data' => [
                'comments' => $comments
            ]
        ]));
    }
}
