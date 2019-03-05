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

class ArticleController
{
    /**
     * @Route("/")
     */
    public function homepage(){
        $html = ``;
        return new Response($html);
    }

    /**
     * @Route("/news/{notice}")
     */
    public function show($notice)
    {
        return new Response(sprintf('El articulo es: %s', $notice));
    }
}