<?php


namespace App\Controller\Backoffice;

use App\Service\Http\Response;
use App\View\View;

class AdminCommentController
{
    /**
     * @var View
     */
    private View $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    public function displayAdminCommentAction(): Response
    {
        return new Response($this->view->render([
            'template' => '../backoffice/comments',
        ]));
    }

}
