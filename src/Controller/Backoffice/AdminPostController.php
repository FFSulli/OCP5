<?php

namespace App\Controller\Backoffice;

use App\Model\Entity\Post;
use App\Model\Repository\PostRepository;
use App\Model\Repository\UserRepository;
use App\Service\Authentication\Authentication;
use App\Service\CSRF\Csrf;
use App\Service\Form\PostFormValidator;
use App\Service\Http\RedirectResponse;
use App\Service\Http\Request;
use App\Service\Http\Response;
use App\Service\Http\Session\Session;
use App\View\View;

class AdminPostController
{

    private Request $request;
    private View $view;
    private Session $session;
    private PostRepository $postRepository;
    private UserRepository $userRepository;
    private PostFormValidator $postFormValidator;
    private Authentication $authentication;
    private Csrf $csrf;

    public function __construct(Request $request,
        View $view,
        Session $session,
        PostRepository $postRepository,
        UserRepository $userRepository,
        PostFormValidator $postFormValidator,
        Authentication $authentication,
        Csrf $csrf
    )
    {
        $this->request = $request;
        $this->view = $view;
        $this->session = $session;
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
        $this->postFormValidator = $postFormValidator;
        $this->authentication = $authentication;
        $this->csrf = $csrf;
    }

    public function displayAdminPostAction(): Response
    {

        if ($this->authentication->isAdmin() || $this->authentication->isEditor()) {

            $posts = $this->postRepository->findAll();

            foreach ($posts as $post) {
                /** @var Post $post */
                $author = $this->userRepository->find($post->getUserFk());
            }

            $data = $this->request->request()->all();


            if ($this->request->getMethod() === 'POST' && $this->csrf->checkToken($data['csrfToken'])) {

                if ($this->authentication->isAdmin()) {
                    if ($this->postRepository->find($data['deletePost']) !== null) {
                        $post = $this->postRepository->find($data['deletePost']);
                        $this->postRepository->delete($post);

                        $this->session->addFlashes('success', 'Article supprimé.');
                        return new RedirectResponse('index.php?action=admin_posts', 302);
                    }
                }
            }

            $this->csrf->generateToken();

            return new Response($this->view->render([
                'template' => '../backoffice/posts',
                'data' => [
                    'posts' => $posts,
                    'author' => $author,
                    'csrfToken' => $this->session->get('csrfToken')
                ]
            ]));
        }

        return new RedirectResponse('index.php', 403);

    }

    public function addPostAction()
    {
        if ($this->authentication->isAdmin() || $this->authentication->isEditor()) {
            $this->csrf->generateToken();

            $user = $this->authentication->getAuthenticatedUser();

            $data = $this->request->request()->all();

            if ($this->request->getMethod() === 'POST' && $this->csrf->checkToken($data['csrfToken'])) {

                if ($this->postFormValidator->isValid($data)) {

                    $post = new Post();

                    $post
                        ->setTitle($data['title'])
                        ->setExcerpt($data['excerpt'])
                        ->setContent($data['content'])
                        ->setUserFk($user->getId());

                    $this->postRepository->create($post);
                    $this->csrf->deleteToken();

                    $this->session->addFlashes('success', 'Article enregistré.');
                    return new RedirectResponse('index.php?action=admin_posts', 302);

                } else {
                    $oldRequest = $this->request->request()->all();
                    $this->session->addFlashes('error', "Le formulaire n'est pas valide, merci de vérifier les informations renseignées.");
                }
            }

            return new Response($this->view->render([
                'template' => '../backoffice/add_post',
                'data' => [
                    'request' => $oldRequest,
                    'csrfToken' => $this->session->get('csrfToken')
                ]
            ]));
        }

        return new RedirectResponse('index.php', 403);
    }

    public function editPostAction(int $id)
    {
        if ($this->authentication->isAdmin() || $this->authentication->isEditor()) {
            $this->csrf->generateToken();

            $user = $this->authentication->getAuthenticatedUser();
            $post = $this->postRepository->find($id);
            $editors = $this->userRepository->findBy(['role_fk' => 2]);

            $data = $this->request->request()->all();

            if ($this->request->getMethod() === 'POST' && $this->csrf->checkToken($data['csrfToken'])) {

                if ($this->postFormValidator->isValid($data)) {

                    if ($user->getId() !== $data['author']) {
                        $user = $this->userRepository->find($data['author']);
                    }

                    $post
                        ->setId($post->getId())
                        ->setTitle($data['title'])
                        ->setExcerpt($data['excerpt'])
                        ->setContent($data['content'])
                        ->setUserFk($user->getId());

                    $this->postRepository->update($post);
                    $this->csrf->deleteToken();

                    $this->session->addFlashes('success', 'Article mis à jour.');
                    return new RedirectResponse('index.php?action=admin_posts', 302);

                } else {
                    $oldRequest = $this->request->request()->all();
                    $this->session->addFlashes('error', "Le formulaire n'est pas valide, merci de vérifier les informations renseignées.");
                }
            }

            return new Response($this->view->render([
                'template' => '../backoffice/edit_post',
                'data' => [
                    'request' => $oldRequest,
                    'post' => $post,
                    'editors' => $editors,
                    'csrfToken' => $this->session->get('csrfToken')
                ]
            ]));
        }

        return new RedirectResponse('index.php', 403);
    }

}
