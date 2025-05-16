<?php
namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/article/{id}', name: 'app_article_show', methods: ['GET', 'POST'])]
    public function show(Article $article, EntityManagerInterface $entityManager, Request $request): Response
    {
        $comment = new Comment();
        $commentForm = $this->createForm(CommentFormType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setPost($article);
            $comment->setCreateAt(new \DateTimeImmutable());
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('app_article_show', ['id' => $article->getId()]);
        }

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'commentForm' => $commentForm,
        ]);
    }
} 