<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(PostRepository $post): Response
    {
        return $this->render('page/home.html.twig', [
            'posts' => $post->findLatest(),
        ]);
    }

    #[Route('/blog/{slug}', name: 'app_post')]
    public function post($slug, PostRepository $postRepository): Response
    {
        return $this->render('page/post.html.twig', [
            'post' => $postRepository->findOneBySlug($slug),
            'form' => $this->createForm(CommentType::class),
        ]);
    }

    #[Route('/nuevo-comentario/{slug}', name: 'app_comment_new')]
    public function comment(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment; //Creamos al comentario.
        $comment->setUser($this->getUser()); //Se crea un comentario tomando el usuario logueado.
        $comment->setPost($post); //Este comentario pertene a un post, toma el de la consulta.


        $form = $this->createForm(CommentType::class, $comment); //Creamos e formulario y le pasamos el comentario.
        $form->handleRequest($request); //Manejamos los datos.

        if ($form->isSubmitted() && $form->isValid()) { //Si el formulario has sido enviado y es valido.
            $entityManager->persist($comment);
            $entityManager->flush(); //Efectua los cambios.

            return $this->redirectToRoute('app_post', ['slug' => $post->getSlug()]); //Una vez realizado lo anterior redireccionamos a la ruta y pasamos dicha informacion.
        }

        return $this->render('page/post.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }
}
