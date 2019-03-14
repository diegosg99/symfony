<?php
namespace App\Controller;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ArticleAdminController extends AbstractController
{
    /**
     * @Route("/admin/article/new")
     */
    public function new(EntityManagerInterface $em)
    {
        $article = new Article();
        $article->setTitle('¿Qué significa en plan?')
            ->setNotice('en_plan'.rand(100, 999))
            ->setContent(<<<EOF
Hay tanto tonto suelto que ya no si ni a lo que se refieren en el vocabulario de estos niñatos, ¿hay alguien que sea tan subnormal de usar esta expresión, que me pueda explicar cuando y por qué la usa?.
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
     * @Route("/admin/article/post", name ="upload_post")
     */
    public function post(Request $request, EntityManagerInterface $em){

        $defaultData = ['contenido' => 'tikitiki'];
        $form = $this->createFormBuilder($defaultData)
            ->add('title', TextType::class)
            ->add('content', TextType::class)
            ->add('send', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        $data = 'bomba';

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
        }

        $article = new Article();
        $title = $request->request->get('title');
        $content = $request->request->get('content');

        $article->setTitle($title)
            ->setNotice($title.rand(100, 999))
            ->setContent($content);
        $em->persist($article);
        $em->flush();
        return $this->redirect($this->generateUrl('get_post'));
    }

    /**
     * @Route("/register", name="user_registration")
     */
    public function register(Request $request)
    {
        // 1) build the form
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {



            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('get_post');
        }
    }


    /**
     * @Route("/admin/article/get", name ="get_post", methods={"GET"})
     */
    public function getArticles(){

        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render('article/list.html.twig', array('articles' => $articles));

    }
}