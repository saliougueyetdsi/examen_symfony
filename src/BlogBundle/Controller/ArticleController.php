<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BlogBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use BlogBundle\Form\ArticleType;
use BlogBundle\Entity\Article;
use BlogBundle\Entity\User;
use BlogBundle\Entity\commentaire;
use BlogBundle\Entity\Categorie;
use BlogBundle\Form\CategorieType;
use BlogBundle\Form\commentaireType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArticleController extends Controller
{
     /**
     * @Route("/admin")
     */
    public function adminAction()
    {
        return new Response('<html><body>Admin page!</body></html>');
    }

   
     /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)

    {
       //sswordEncoder = $this->get('security.password_encoder'); 
        // 1) build the form

        $userauth = new Userauth();
        $form = $this->createForm(UserType::class, $userauth);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($userauth, $userauth->getPlainPassword());
            $user->setPassword($password);

            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userauth);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('home');
        }

        return $this->render(
            '@Blog/Article/add_user.html.twig/register.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/index")
     */
    public function addUserAction(Request $request)

    {
    	$user = new User();
    	$form =$this->createForm(UserType::class, $user);
    	$form->handleRequest($request);
    	if ($form->isSubmitted() && $form->isValid())
        {
    		    $user = $form->getData();
    		    $entityManager = $this->getDoctrine()->getManager();
         $entityManager->persist($user);
        $entityManager->flush();
       return $this->redirectToRoute('home');
    	}

        return $this->render('@Blog/Article/add_user.html.twig', array( 'form' => $form->createView()
            // ...
        ));
    }
    /**
     * @Route("/home" ,name="home")
     */
    public function homeAction()
    {
    

 return $this->render('@Blog/Article/index.html.twig', array());

    }
     /**
     * @Route("/home/createarticle/view/{id}" ,name="article_view")
     */
    public function viewAction(Article $article ,Request $request )
    {
    // $em = $this->getDoctrine()->getManager();

    // Pour récupérer une seule annonce, on utilise la méthode find($id)
     //$article = $em->getRepository('BlogBundle:Article')->find($id);

    
    // ou null si l'id $id n'existe pas, d'où ce if :
   // if (null === $article) {
      // throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    //}

    // Récupération de la liste des candidatures de l'annonce
   // $listuser = $em
     // ->getRepository('BlogBundle:User')
      //->findBy(array('article' => $article))
   // ;



    //return $this->render('@Blog/Article/view.html.twig', array(
      
   // ));
        $commentaires = new commentaire();
        $form =$this->createForm(commentaireType::class, $commentaires);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
                $commentaires->setCreatedAt(new \DateTime());
                $commentaires->setArticle($article);
                $entityManager = $this->getDoctrine()->getManager();
         $entityManager->persist($commentaires);
         $entityManager->persist($article);

        $entityManager->flush();
       
        }

 return $this->render('@Blog/Article/view.html.twig', array('article'=>$article ,'form'=>$form->createView()));

    }
    /**
     * @Route("/home/createarticle", name="create_article_view")
     */
    public function addArticleAction(Request $request)

    {
    	$article = new Article();
        $categorie = new Categorie();
    	$form =$this->createForm(ArticleType::class, $article);
    	$form->handleRequest($request);
    	if ($form->isSubmitted() && $form->isValid())
        {
            /**
             * UploadFile $file
             */
           // $file =$article->getImage();
//$filename=md5(uniqid()).'.'.$file->guessExtension();
           // $file->RemoveUpload($this->getParameter('image_directory') );
           //$article->setImage();
    		    $article = $form->getData();
    		    $entityManager = $this->getDoctrine()->getManager();

                
                $entityManager->persist($article);

         $entityManager->persist($article->getImage());
          
         
         
        $entityManager->flush();
       return $this->redirectToRoute('list_article_enregistre');
    	}

        return $this->render('@Blog/Article/add_article.html.twig', array( 'form' => $form->createView()
            // ...
        ));
    }
    /**
     * @Route("/home/createcategorie")
     */
      public function addcategorieAction(Request $request)

    {
        $categorie = new Categorie();
        $form=$this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form1->isSubmitted() && $form1->isValid())
        {
                $categorie = $form->getData();
                $entityManager = $this->getDoctrine()->getManager();
         $entityManager->persist($categorie);
        $entityManager->flush();
       return $this->redirectToRoute('create_article_view');
        }

        return $this->render('@Blog/Article/categorie.html.twig', array( 'form' => $form->createView()
            // ...
        ));
    }
    /**
     * @Route("/home/article/list",name="list_article_view" )
     */
      public function listAction()

    {
        $articles = new Article();
        $Repository = $this->getDoctrine()->getRepository('BlogBundle:Article');
        $query = $Repository->createQueryBuilder('a')
    ->where('a.etat !=0')
    ->orderBy('a.date', 'desc')
    ->getQuery();
 
        $articles=$query->getResult();
        
        return $this->render('@Blog/Article/list.html.twig', array( 'articles'=>$articles));
    }
     /**
     * @Route("/home/article/enregistre",name="list_article_enregistre" )
     */
      public function listarticletAction()

    {
        $articles = $this->getDoctrine()->getRepository('BlogBundle:Article');
        
        return $this->render('@Blog/Article/list_article_enregistre.html.twig', array( 'articles'=>$articles));
    }
     /**
     * @Route("/home/article/list/education",name="list_article_education" )
     */
      public function listeducationAction(Categorie $categorie)

    {

       $articles = $this->getDoctrine()->getRepository('BlogBundle:Article')-> getAdvertWithCategories($categorie);
        return $this->render('@Blog/Article/list.html.twig', array( 'articles'=>$articles));
    }
     /**
     * @Route("/home/article/list/education",name="list_article_politique" )
     */
      public function listepolitiqueAction()

    {
        $articles = new Article();
        $Repository = $this->getDoctrine()->getRepository('BlogBundle:Article');
        $query = $Repository->createQueryBuilder('a')
    ->where('a.etat !=0')
    ->orderBy('a.date', 'desc')
    ->getQuery();
 
        $articles=$query->getResult();
        
        return $this->render('@Blog/Article/list.html.twig', array( 'articles'=>$articles));
    }
     /**
     * @Route("/home/article/list/sport",name="list_article_sport" )
     */
      public function listesportAction()

    {
        $articles = new Article();
        $Repository = $this->getDoctrine()->getRepository('BlogBundle:Article');
        $query = $Repository->createQueryBuilder('a')
    ->where('a.etat !=0')
    ->orderBy('a.date', 'desc')
    ->getQuery();
 
        $articles=$query->getResult();
        
        return $this->render('@Blog/Article/list.html.twig', array( 'articles'=>$articles));
    }
     /**
     * @Route("/home/article/list/musique",name="list_article_musique" )
     */
      public function listemusiqueAction()

    {
        $articles = new Article();
        $Repository = $this->getDoctrine()->getRepository('BlogBundle:Article');
        $query = $Repository->createQueryBuilder('a')
    ->where('a.etat !=0')
    ->orderBy('a.date', 'desc')
    ->getQuery();
 
        $articles=$query->getResult();
        
        return $this->render('@Blog/Article/list.html.twig', array( 'articles'=>$articles));
    }
    /**
     * @Route("/home/article/list/autre",name="list_article_autre" )
     */
      public function listeautreAction()

    {
        $articles = new Article();
        $Repository = $this->getDoctrine()->getRepository('BlogBundle:Article');
        $query = $Repository->createQueryBuilder('a')
    ->where('a.etat !=0')
    ->orderBy('a.date', 'desc')
    ->getQuery();
 
        $articles=$query->getResult();
        
        return $this->render('@Blog/Article/list.html.twig', array( 'articles'=>$articles));
    }
     /**
     * @Route("/home/articles/commentaire/{id}" )
     */
        public function listcommenttAction(Article $article)
        {
            $commentaires =new commentaire();
            $commentaires->setContenu();
             $commentaires->setCreatedAt(new \DateTime());
              $commentaires->setArticle($article);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($commentaires);
         $entityManager->persist($article);
        $entityManager->flush();
        return $this->redirectToRoute('article_view',array('id'=>$article->getId()));


        }

}
