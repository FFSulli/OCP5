<?php

declare(strict_types=1);

namespace  App\Controller\Frontoffice;

use App\Model\Entity\Comment;
use App\Model\Repository\UserRepository;
use App\Service\Form\CommentFormValidator;
use App\Service\Http\Request;
use App\Service\Http\Session\Session;
use App\Service\Pagination\PaginationService;
use App\View\View;
use App\Service\Http\Response;
use App\Model\Repository\PostRepository;
use App\Model\Repository\CommentRepository;

final class PostController
{
    private PostRepository $postRepository;
    private UserRepository $userRepository;
    private View $view;
    private Session $session;

    public function __construct(PostRepository $postRepository, UserRepository $userRepository, View $view, Session $session)
    {
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
        $this->view = $view;
        $this->session = $session;
    }

    public function displayOneAction(Request $request, int $postId, CommentRepository $commentRepository, CommentFormValidator $commentFormValidator): Response
    {
        $post = $this->postRepository->find($postId);
        $comments = $commentRepository->findBy([
            "post_fk" => $postId,
            "verified" => 1
        ]);
        $user = $this->userRepository->findOneBy(['email' => $this->session->get('user')]);

        $data = $request->request()->all();

        if ($request->getMethod() === 'POST') {
            if ($commentFormValidator->isValid($data)) {
                $comment = new Comment();
                $comment->setContent($data['content']);
                $comment->setUserFk($user->getId());
                $comment->setPostFk($postId);

                $commentRepository->create($comment);
                $this->session->addFlashes('success', 'Votre commentaire a bien été ajouté');

                return new Response($this->view->render([
                    'template' => 'post',
                    'data' => [
                        'post' => $post,
                        'comments' => $comments
                    ],
                ]));
            }
        }

        $response = new Response('<h1>faire une redirection vers la page d\'erreur, ce post n\'existe pas</h1><a href="index.php?action=posts">Liste des posts</a><br>', 404);

        if ($post !== null) {
            $response = new Response($this->view->render(
                [
                'template' => 'post',
                'data' => [
                    'post' => $post,
                    'comments' => $comments,
                    'connected' => $this->session->get('user')
                ],
                ],
            ));
        }

        return $response;
    }

    public function displayAllPostsAction(PaginationService $paginationService): Response
    {
        $posts = $paginationService->paginatePosts();
        $pages = $paginationService->displayPages();

        return new Response($this->view->render([
            'template' => 'posts',
            'data' => [
                'posts' => $posts,
                'pages' => $pages,
                'connected' => $this->session->get('user')
            ],
        ]));
    }
}
