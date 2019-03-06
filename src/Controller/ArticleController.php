<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 5/03/19
 * Time: 17:38
 */

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function homepage(){
        $html = "BOMBA";
        return $this->render("article/homepage.html.twig");
    }

    /**
     * @Route("/news/{notice}", name ="new")
     */
    public function show($notice)
    {
        $comments = ['Que sería de internet sin LoremIpsum',
            'El Lorem este no vale pana socioo',
            'Podrian aprovechas y poner un texto que aporte valores...'];
        return $this->render('article/show.html.twig', [
            'title' => ucwords(str_replace('-',' ', $notice)),
            'comments' => $comments,
        ]);
    }
}