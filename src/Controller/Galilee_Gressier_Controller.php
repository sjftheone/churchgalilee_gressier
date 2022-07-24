<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use App\Repository\PersonneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


use App\Entity\Comment;
use App\Entity\Contact;
use App\Entity\PersonneSearch;
use App\Entity\Post;
use App\Event\CommentCreatedEvent;
use App\Form\CommentType;
use App\Form\ContactType;
use App\Form\PersonneSearchType;
use App\Repository\PostRepository;
//use App\Repository\TagRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;

//use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Routing\Annotation\Route;

class Galilee_Gressier_Controller extends AbstractController
{

    public function __construct(PostRepository $repository, EntityManagerInterface $em)
    {
        $this->em=$em;
        $this->repository=$repository;
    }


    /**
      * @Route("/", name="Galilee_index")
      */
    public function index(PostRepository $posts,PaginatorInterface $paginator,Request $request,MailerInterface $mailer): Response
    {
      
        $posts=$paginator->paginate($this->repository->findBy([],['publishedAt' => 'DESC']),$request->query->getInt('page',1),5);

       // $contact=new Contact();
        $form = $this->createForm(ContactType::class); 
        $contact=$form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
          $email=(new TemplatedEmail())
         ->from($contact->get('email')->getData())
          ->to('sjftheone@gmail.com')
          // ->to($contact->getUsers->getEmail())
          ->subject('Contact concernant leglise')
          ->htmlTemplate('emails\contact.html.twig')
          ->context([
            'mail'=>$contact->get('email')->getData(),
            'message'=>$contact->get('message')->getData(),
            'firstname'=>$contact->get('firstname')->getData(),
            'lastname'=>$contact->get('lastname')->getData(),
            'phone'=>$contact->get('phone')->getData()
          ]);
          $mailer->send($email);
          $this->addFlash('Messagess', 'Mail envoye');
          return $this->redirectToRoute('Galilee_index');
         
        }
        return $this->render('base.html.twig',
        ['posts' => $posts,
            'current_menu'=>'posts',
            'form'=>$form->CreateView()
            

            //'Sunrise'=>$Sunrise,
         //   'Sunset'=>$Sunset,
            // 'form'=>$form->createView()
        ]);

    }
	
	  /**
      * @Route("/Qui_Sommes_Nous", name="Galilee_qui_sommes_nous")
      */
    public function qui_sommes_nous_show(): Response
    {
		return $this->render('Qui_Sommes_Nous.html.twig');
    }
	
	 /**
      * @Route("/nos_origines", name="Galilee_origines")
      */
	 public function nos_origines_show(): Response
    {
		return $this->render('nos_origines.html.twig');
    }
	
	
	 /**
      * @Route("/nos_croyances", name="Galilee_Croyances")
      */
	 public function nos_croyances_show(): Response
    {
		return $this->render('nos_croyances.html.twig');
    }
	
	
	/**
      * @Route("/ancienat", name="ancienat")
      */
	 public function ancienat_infos(): Response
    {
		return $this->render('departements/ancienat.html.twig');
    }
	
	/**
      * @Route("/ministere_personnel", name="ministere_personnel")
      */
	 public function ministere_personnel_infos(): Response
    {
		return $this->render('departements/ministere_personnel.html.twig');
    }
	
	/**
      * @Route("/diaconat", name="diaconat")
      */
	 public function diaconat_infos(): Response
    {
		return $this->render('departements/diaconat.html.twig');
    }
	
	
	/**
      * @Route("/ministere_sante", name="ministere_sante")
      */
	 public function ministere_sante_infos(): Response
    {
		return $this->render('departements/ministere_sante.html.twig');
    }
	
	/**
      * @Route("/ministere_enfant", name="ministere_enfant")
      */
	 public function ministere_enfant_infos(): Response
    {
		return $this->render('departements/ministere_enfant.html.twig');
    }
	
	/**
      * @Route("/communication", name="communication")
      */
	 public function communication_infos(): Response
    {
		return $this->render('departements/communication.html.twig');
    }
	
	/**
      * @Route("/education", name="education")
      */
	 public function education_infos(): Response
    {
		return $this->render('departements/education.html.twig');
    }
	
	
	/**
      * @Route("/publication", name="publication")
      */
	 public function ministere_publication_infos(): Response
    {
		return $this->render('departements/publication.html.twig');
    }
	
	/**
      * @Route("/ministere_femme", name="ministere_femme")
      */
	 public function ministere_femme_infos(): Response
    {
		return $this->render('departements/ministere_femme.html.twig');
    }
	
	
	/**
      * @Route("/ecole_sabbat", name="ecole_sabbat")
      */
	 public function ecole_sabbat_infos(): Response
    {
		return $this->render('departements/ecole_sabbat.html.twig');
    }
	
	
	/**
      * @Route("/gestion_chretienne", name="gestion_chretienne")
      */
	 public function gestion_chretienne_infos(): Response
    {
		return $this->render('departements/gestion_chretienne.html.twig');
    }
	
		/**
      * @Route("/jeunesse_adventiste", name="jeunesse_adventiste")
      */
	 public function jeunesse_adventiste_infos(): Response
    {
		return $this->render('departements/jeunesse_adventiste.html.twig');
    }
	
	   /**
      * @Route("/Contact", name="Contact")
      */
      public function contact(Request $request,MailerInterface $mailer): Response
      {
        
         // $contact=new Contact();
          $form = $this->createForm(ContactType::class);
          $contact=$form->handleRequest($request);
  
          
  
          if ($form->isSubmitted() && $form->isValid())
          {
            $email=(new TemplatedEmail())
           ->from($contact->get('email')->getData())
            ->to('xxxx@hhh.com')
            // ->to($contact->getUsers->getEmail())
            ->subject('Contact concernant leglise')
            ->htmlTemplate('emails\contact.html.twig')
            ->context([
              'mail'=>$contact->get('email')->getData(),
              'message'=>$contact->get('message')->getData(),
              'firstname'=>$contact->get('firstname')->getData(),
              'lastname'=>$contact->get('lastname')->getData(),
              'phone'=>$contact->get('phone')->getData()
            ]);
            $mailer->send($email);
            $this->addFlash('Message', 'Mail envoye');
            return $this->redirectToRoute('Galilee_index');
           
          }
          return $this->render('contactindex.html.twig',
          [
              'form'=>$form->CreateView()
              
              //'Sunrise'=>$Sunrise,
           //   'Sunset'=>$Sunset,
              // 'form'=>$form->createView()
          ]);
  
      }
    

	
	
}