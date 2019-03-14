<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 5/03/19
 * Time: 17:38
 */

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function homepage(){

        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        $head = $articles[0];
        $trending = [$articles[0]->ge];
        return $this->render('article/homepage.html.twig', array('articles' => $articles));
    }

    /**
     * @Route("/news/{notice}", name ="new")
     */
    public function show($notice)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->findBy(array('notice'=>$notice));
        if (!$article){
            throw $this->createNotFoundException(
              'No se ha encontrado el articulo'
            );
        }
        $title = $article[0]->getTitle();
        $content = $article[0]->getContent();
        $comments = ['Que sería de internet sin LoremIpsum',
            'El Lorem este no vale pana socioo',
            'Podrian aprovechas y poner un texto que aporte valores...',
            'el loremipsum ese loko yo lo conocí era to wena ente hermano'];
        return $this->render('article/show.html.twig', [
            'title' => $title,
            'comments' => $comments,
            'notice' => $notice,
            'content' => $content
        ]);
    }
    /**
     * @Route("/news/{notice}/heart", name ="article_toggle_heart", methods={"POST"})
     */
    public function toggleArticleHeart($notice)
    {
        return new JsonResponse(['hearts' => rand(5, 100)]);
    }
}