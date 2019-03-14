<?php
namespace App\Controller;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
class ArticleAdminController extends AbstractController
{
    /**
     * @Route("/admin/article/new")
     */
    public function new(EntityManagerInterface $em)
    {
        $article = new Article();
        $article->setTitle('¿Qué creeis que hay después de la muerte?')
            ->setNotice('que_hay_despues_de_la_muerte'.rand(100, 999))
            ->setContent(<<<EOF
La verdad que solo eso namas que queria saber que hay después de la muerte segun vosotros :CCC
fugiat.
EOF
            );
        // publish most articles
        if (rand(1, 10) > 2) {
            $article->setPublishedAt(new \DateTime(sprintf('-%d days', rand(1, 100))));
        }
        $em->persist($article);
        $em->flush();
        return new Response(sprintf(
            'Hiya! New Article id: #%d slug: %s',
            $article->getId(),
            $article->getNotice()
        ));
    }
    /**
     * @Route("/admin/article/post", name ="upload_post", methods={"POST"})
     */
    public function post(Request $data, EntityManagerInterface $em){
        $article = new Article();
        $title = $data->get('title');
        $content = $data->get('content');

        $post = [$title,$content];

        $article->setTitle($post[0])
            ->setNotice($post[0].rand(100, 999))
            ->setContent($content);
        $em->persist($article);
        $em->flush();

    }
    /**
     * @Route("/admin/article/get", name ="get_post", methods={"GET"})
     */
    public function getArticles(){

        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render('article/list.html.twig', array('articles' => $articles));

    }
}