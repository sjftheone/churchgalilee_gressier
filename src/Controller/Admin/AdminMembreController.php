<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Admin;

use App\Entity\Evenement;
use App\Controller\Admin\Export_data_membre;
use App\Entity\Integration;
use App\Form\BaptemeType;
use App\Entity\Bapteme;
use App\Form\IntegrationType;
use App\Form\EvenementType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\PersonneType;
use App\Form\PersonneSearchType;
use App\Entity\Personne;
use App\Entity\PersonneSearch;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PersonneRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Doctrine\Persistence\ManagerRegistry;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
//use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Knp\Component\Pager\Pagination\PaginationInterface;
//use Knp\Component\Pager\PaginationInterface;

use Knp\Component\Pager\PaginatorInterface;
/**
 * Controller used to manage blog contents in the backend.
 *
 * Please note that the application backend is developed manually for learning
 * purposes. However, in your real Symfony application you should use any of the
 * existing bundles that let you generate ready-to-use backends without effort.
 *
 * See http://knpbundles.com/keyword/admin
 *
 * @Route("/admin/membre")
 * 
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class AdminMembreController extends AbstractController
{
private $repository;
private $em;
private $doctrine;
public function __construct(PersonneRepository $repository, EntityManagerInterface $em,ManagerRegistry $doctrine)
{
  $this->em=$em;
   $this->repository=$repository;
   $this->doctrine=$doctrine;
}

    /**
     * Lists all Post entities.
     * @Route("/new", methods="GET|POST", name="admin_membre_new")
     */

    public function new(Request $request)
    {
        $test='new';
       $personne = new Personne();
        $bapteme = new Bapteme();
        $integration = new Integration();
        $evenement=new Evenement();

        $personne->setAuthor($this->getUser());
       // $personne->addBapteme($bapteme);

        $baptemeForm = $this->createForm(BaptemeType::class, $bapteme);
        $integrationForm = $this->createForm(IntegrationType::class, $integration);
        $evenementForm =   $this->createForm(EvenementType::class, $evenement);
        $form = $this->createForm(PersonneType::class, $personne)->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);
        $baptemeForm->handleRequest($request);
        $integrationForm->handleRequest($request);
        $evenementForm->handleRequest($request);
      //  && $baptemeForm->isSubmitted() && $baptemeForm->isValid()

		  if ($form->isSubmitted() && $form->isValid())
		  {
            $em = $this->doctrine->getManager();
         //     $em->persist($bapteme);
            $em->persist($personne);
            $em->flush();


                  /*
                  if(null != $personne->getId())
                  {
                      if($baptemeForm->isSubmitted() && $baptemeForm->isValid())
                      {
                          $bapteme->setPersonne($personne);
                          $em->persist($bapteme);
                          $em->flush();
                      }

                      if($integrationForm->isSubmitted() && $integrationForm->isValid())
                      {
                          $integration->setPersonne($personne);
                          $em->persist($integration);
                          $em->flush();
                      }

                      if($evenement->isSubmitted() && $evenement->isValid())
                      {
                          $evenement->setPersonne($personne);
                          $em->persist($evenement);
                          $em->flush();
                      }

                      $em->flush();

                  }
                  */
              $this->addFlash('success', 'Ajout réussie');
            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See https://symfony.com/doc/current/controller.html#flash-messages

             if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('admin_membre_new');
           }
            return $this->redirectToRoute('admin_membre_index');
		 }
		return $this->render('admin/membre/new.html.twig', array(
            'personne' => $personne,
            'integration' => $integration,
            'form' => $form->createView(),
            'form1' => $baptemeForm->createView(),
            'form_integration' => $integrationForm->createView(),
            'form_evenement' => $evenementForm->createView(),
            'test' =>$test,
        ));
    } 
		//@IsGranted("edit", subject="post", message="Posts can only be edited by their authors.")
		/**
     * Displays a form to edit an existing Post entity.
     *
     * @Route("/{id<\d+>}/edit", methods="GET|POST", name="admin_membre_edit")
     * 
     */
    public function edit(Request $request, Personne $personne,ManagerRegistry $doctrine): Response
    {
        $test='edit';
        $integration = new Integration();
        $bapteme=new Bapteme();
        $evenement=new Evenement();
       // $personne->addBapteme($bapteme);
        $form = $this->createForm(PersonneType::class, $personne);
        $integrationForm = $this->createForm(IntegrationType::class, $integration);

        $evenementForm = $this->createForm(EvenementType::class, $evenement);

        $baptemeForm = $this->createForm(BaptemeType::class, $bapteme);
        $perso=$this->doctrine
            ->getRepository(Personne::class)
            ->find($personne->getId());
         //   $perso->setAuthor($this->getUser());
        $bapteme = $perso->getBaptemes();
        $integration = $perso->getIntegrations();
        $evenement = $perso->getEvenements();

        //        $baptemeForm = $this->createForm(BaptemeType::class, $bapteme);

        $form->handleRequest($request);
//        $baptemeForm->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {            
            $doctrine->getManager()->persist($personne);
            $doctrine->getManager()->flush();
            $this->addFlash('success', 'Modification réussie');
           // return $this->redirectToRoute('admin_membre_edit', ['id' => $personne->getId()]);
            return $this->redirectToRoute('admin_membre_index');
        }

        
        return $this->render('admin/membre/edit.html.twig', array(
            'personne' => $personne,
            'baptemes' =>$bapteme,
            'integrations' =>$integration,
            'evenements' =>$evenement,

            'form' => $form->createView(),
             'test'=>$test,
            'baptemeForm' =>  $baptemeForm->createView(),
        ));
    }

        /*
		 public function edit(Request $request, Personne $personne): Response
    {
         $bapteme=new  Bapteme();
        $form = $this->createForm(PersonneType::class, $personne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $doctrine->getManager()->flush();

            $this->addFlash('success', 'Modification réussie');

            return $this->redirectToRoute('admin_membre_edit', ['id' => $personne->getId()]);
        }

        return $this->render('admin/membre/edit.html.twig', [
            'personne' => $personne,
            'form' => $form->createView(),
        ]);
    }
		*/
   /**
     * Finds and displays a Membre entity.
     *
     * @Route("/{id<\d+>}", methods="GET", name="admin_membre_show")
     */
    public function show(Personne $personne): Response
    {
	$form = $this->createForm(PersonneType::class, $personne);

    
        $perso=$this->doctrine
            ->getRepository(Personne::class)
            ->find($personne->getId());
        $bapteme = $perso->getBaptemes();
        $integration=$perso->getIntegrations();
        $evenement=$perso->getEvenements();


 //   $bapteme= $this->repository->findAllBaptemeParPersonne($personne->getId());
   //     $baptemeForm = $this->createForm(BaptemeType::class, $bapteme);

        // This security check can also be performed
        // using an annotation: @IsGranted("show", subject="post", message="Posts can only be shown to their authors.")
       // $this->denyAccessUnlessGranted(PostVoter::SHOW, $post, 'Posts can only be shown to their authors.');


          return $this->render('admin/membre/show.html.twig', array(
              'personne' => $personne,
              'integrations' => $integration,
              'baptemes' =>$bapteme,
              'evenements'=>$evenement,
              'form' => $form->createView(),
             // 'form1' =>  $baptemeForm->createView(),
          ));




/*
        return $this->render('admin/membre/show.html.twig', [
            'personne' => $personne,'form' => $form->createView(),
        ]);
        */
    }


	 
	 
	   /**
     * Deletes a Post entity.
     *
     * @Route("/{id}/delete", methods="GET|POST", name="admin_membre_delete")
     * 
     */
    public function delete(Request $request, Personne $personne): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_membre_index');
        }

        // Delete the tags associated with this blog post. This is done automatically
        // by Doctrine, except for SQLite (the database used in this application)
        // because foreign key support is not enabled by default in SQLite
        //$post->getTags()->clear();

        $em = $this->doctrine->getManager();
        $em->remove($personne);
        $em->flush();
        $this->addFlash('success', 'Suppression réussie');
      //  $this->addFlash('success', 'personne.deleted_successfully');

        return $this->redirectToRoute('admin_membre_index');
		
    }
	 
	 /*
	 
	  public function index(PostRepository $posts): Response
    {
        $authorPosts = $posts->->findAll();(['author' => $this->getUser()], ['publishedAt' => 'DESC']);

        return $this->render('admin/blog/index.html.twig', ['posts' => $authorPosts]);
    }

	 */
	 
      /**
     * Lists all Post entities.
     * @Route("/", methods="GET|POST", name="admin_membre_index")
     */	

	 public function index(PersonneRepository $personnes,PaginatorInterface $paginator, Request $request): Response
    { $seach=new PersonneSearch();
	  $form=$this->createForm(PersonneSearchType::class,$seach);
	$form->handleRequest($request);
	  
	   $personnes=$paginator->paginate($this->repository->findAll_MembresQuery($seach),$request->query->getInt('page',1),12);
       return $this->render('admin/membre/index.html.twig',
		 ['personnes' => $personnes,
		 'current_menu'=>'personnes',
		 'form'=>$form->createView(),

		 ]);
    }
	
	



      /**
     * Lists all Post entities.
     * @Route("/data/download", methods="GET|POST", name="admin_membre_data_download")
     */	

	 public function adminMembreDataDownload(PersonneRepository $personnes,PaginatorInterface $paginator, Request $request): Response
     { 
        $seach=new PersonneSearch();
        $form=$this->createForm(PersonneSearchType::class,$seach);
      $form->handleRequest($request);
        
         $personnes=$this->repository->findAll_MembresQuery1();
        // $personnes=$paginator->paginate($this->repository->findAll_MembresQuery($seach),$request->query->getInt('page',1),12);
    
        // On definit les options du PDF
        $optionspdf=new Options();
        //Police par defaut
        $optionspdf->set('defaultFont','Arial');
        $optionspdf->setIsRemoteEnabled(true);
        //On instancie Dompdf
        $dompdf=new Dompdf($optionspdf);
        $context=stream_context_create([
            'ssl'=>[
                'verify_peer'=>FALSE,
                'verify_peer_name'=>FALSE,
                'allow_self_signed'=>TRUE
            ]
        ]);
        $dompdf->setHttpContext($context);
        $html=$this->renderView('admin/membre/download_membre.html.twig',
        ['personnes' => $personnes,
        
        //'form'=>$form->createView(),

        ]
    
        );
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4','Portrait');
        $dompdf->render();
        //On genere un nom de fichier
      //  $fichier='user-data-'.$this->getUsers()->getId().'.pdf';
        $fichier='user-data-'.'.pdf';
        //On envoie le pdf au navigateur
        $dompdf->stream($fichier,[
            'attachment'=>true
        ]);
        return new Response();

     }

     //@Route("/data/perso/download", methods="GET|POST", name="admin_membre_perso_data_download")
      /**
     * Lists all Post entities.
     * @Route("/data/download/{id<\d+>}", methods="GET", name="admin_membre_perso_data_download")
     * 
     */	
    public function admin_membre_perso_data_download(Personne $personne): Response
    {
	$form = $this->createForm(PersonneType::class, $personne);
        $perso=$this->doctrine
            ->getRepository(Personne::class)
            ->find($personne->getId());
        $bapteme = $perso->getBaptemes();
        $integration=$perso->getIntegrations();
        $evenement=$perso->getEvenements();

         // On definit les options du PDF
         $optionspdf=new Options();
         //Police par defaut
         $optionspdf->set('defaultFont','Arial');
         $optionspdf->setIsRemoteEnabled(true);
         //On instancie Dompdf
         $dompdf=new Dompdf($optionspdf);
         $context=stream_context_create([
             'ssl'=>[
                 'verify_peer'=>FALSE,
                 'verify_peer_name'=>FALSE,
                 'allow_self_signed'=>TRUE
             ]
         ]);
         $dompdf->setHttpContext($context);
        
            


        $html=$this->renderView('admin/membre/download_membre_perso.html.twig', array(
            'personne' => $personne,
              'integrations' => $integration,
              'baptemes' =>$bapteme,
              'evenements'=>$evenement,
             // 'form' => $form->createView(),
             
          ));

          $dompdf->loadHtml($html);
          $dompdf->setPaper('A4','Portrait');
          $dompdf->render();
          //On genere un nom de fichier
        //  $fichier='user-data-'.$this->getUsers()->getId().'.pdf';
          $fichier='user-data-'.'.pdf';
          //On envoie le pdf au navigateur
          $dompdf->stream($fichier,[
              'attachment'=>true
          ]);
          return new Response();
  

    }



     private function getData(): array
     {
         /**
          * @var $user User[]
          */
         $list = [];
         $persos = $this->em->getRepository(Personne::class)->findAll();
 
         foreach ($persos as $perso) {
             $list[] = [
                 $perso->getNom(),
                 $perso->getprenom(),
                 $perso->getDateNaissance(),
                 $perso->getPhonePersonnel(),
                 $perso->getAdresse()
 
             ];
         }
         return $list;
     }
 
     /**
      * @Route("/export",  name="export_data_Membre")
      */
     public function export()
     {
         $spreadsheet = new Spreadsheet();
 
         $sheet = $spreadsheet->getActiveSheet();
 
         $sheet->setTitle('User List');
 
         $sheet->getCell('A1')->setValue('Nom');
         $sheet->getCell('B1')->setValue('Prenom');
         $sheet->getCell('C1')->setValue('Date de naissance');
         $sheet->getCell('D1')->setValue('Phone');
         $sheet->getCell('E1')->setValue('Adresse');
 
         // Increase row cursor after header write
             $sheet->fromArray($this->getData(),null, 'A2', true);
         
             $date = date('d-m-y-'.substr((string)microtime(), 1, 8));
             $date = str_replace(".", "", $date);
             $filename = "export_".$date.".xlsx";
             try 
             {
                $writer = new Xlsx($spreadsheet);
                $writer->save($filename);
                $content = file_get_contents($filename);
             }  catch(Exception $e) 
             {
                exit($e->getMessage());
             }
                header("Content-Disposition: attachment; filename=".$filename);
                unlink($filename);
                exit($content);

          /*
 
         $writer = new Xlsx($spreadsheet);
 
        // $writer->save('C:\Users\Frenel\Documents\helloworld.xlsx');
         $writer->save('php://output');
         */
 
         return $this->redirectToRoute('admin_membre_index');
     }
   //  @Route("/export/data/membre_perso",  name="export_data_Membre_Perso")
     
     /**
      *  @Route("export/data/membre_perso/{id<\d+>}", methods="GET", name="admin_membre_perso_data_Excel")
      * 
      */
      public function export_membre_perso(Personne $personne)
      {
        


        $form = $this->createForm(PersonneType::class, $personne);
        $perso=$this->doctrine
            ->getRepository(Personne::class)
            ->find($personne->getId());
        $bapteme = $perso->getBaptemes();
        $integration=$perso->getIntegrations();
        $evenement=$perso->getEvenements();

        if ($perso->getStatutMat()==1)
        $statut = "Célibataire";
        else if($perso->getStatutMat()==2)
        $statut = "Célibataire";
        else if($perso->getStatutMat()==3)
        $statut = "veuf(ve)";
        else if($perso->getStatutMat()==4)
        $statut = "divorcé";
        else if($perso->getStatutMat()==4)
        $statut = "séparé";
        else
        $statut = "autre";




      if($perso->getSexe()==1)
        $sex = "F";
      else if($perso->getSexe()==0)
      $sex = "M";
      else
      $sex = "";


   if($perso->getCivilite()==1)
   $civ = "Monsieur";
   else if($perso->getCivilite()==2)
   $civ = "Madame";
   else
   $civ = "Autre";


          //return new Response();
  
          $spreadsheet = new Spreadsheet();
  
          $sheet = $spreadsheet->getActiveSheet();
  
          $sheet->setTitle('User List');
          $sheet->setCellValue('A1', 'Nom');
          $sheet->setCellValue('B1',$perso->getNom());
          $sheet->setCellValue('C1', 'Prenom');
          $sheet->setCellValue('D1',$perso->getPrenom());

          $sheet->setCellValue('A2', 'Sexe');
          $sheet->setCellValue('B2',$sex);
          $sheet->setCellValue('C2', 'Civilite');
          $sheet->setCellValue('D2',$civ);

          $sheet->setCellValue('A3', 'Date de naissance');
          $sheet->setCellValue('B3',$perso->getDateNaissance()->format('d-m-Y'));
          $sheet->setCellValue('C3', 'Lieu de naissance');
          $sheet->setCellValue('D3',$perso->getLieuNaissance());

          $sheet->setCellValue('A4', 'Nationalite');
          $sheet->setCellValue('B4',$perso->getNationalite());
          $sheet->setCellValue('C4', 'Profession');
          $sheet->setCellValue('D4',$perso->getProfession());

          $sheet->setCellValue('A5', 'Statut matrimonial');
          $sheet->setCellValue('B5',$statut);
          $sheet->setCellValue('C5', 'Adresse');
          $sheet->setCellValue('D5',$perso->getAdresse());

          $sheet->setCellValue('A6', 'Code Postal');
          $sheet->setCellValue('B6',$perso->getCodePostal());
          $sheet->setCellValue('C6', 'Ville');
          $sheet->setCellValue('D6',$perso->getVille());

          $sheet->setCellValue('A7', 'Phone domicile');
          $sheet->setCellValue('B7',$perso->getPhoneHome());
          $sheet->setCellValue('C7', 'Phone personnel');
          $sheet->setCellValue('D7',$perso->getPhonePersonnel());

          $sheet->setCellValue('A8', 'Phone Travail');
          $sheet->setCellValue('B8',$perso->getPhoneTravail());
          $sheet->setCellValue('C8', 'Email');
          $sheet->setCellValue('D8',$perso->getEmail());

          $sheet->setCellValue('A9', 'infos Additionnelles');
          $sheet->setCellValue('B9',$perso->getInfosAdd());



          $sheet->setCellValue('A10', 'Bapteme Information');

          $sheet->setCellValue('A11', 'Date Baptême (J/M/A)');
          $sheet->setCellValue('B11','Lieu de baptême');
          $sheet->setCellValue('C11', 'Baptisé par');
            $comp=11;
          foreach($bapteme as  $bapteme1)
          {
            $comp++;
            $sheet->setCellValue('A'.$comp, $bapteme1->getDateBapteme()->format('d-m-Y'));
            $sheet->setCellValue('B'.$comp,$bapteme1->getLieu());
            $sheet->setCellValue('C'.$comp,$bapteme1->getBaptiserPar());
          }
         
          $comp++;

          $sheet->setCellValue('A'. $comp, 'Integration information');
          $comp++;
          $sheet->setCellValue('A'.$comp, 'date dentrée (j/m/a)');
          $sheet->setCellValue('B'.$comp,'Infos');
          $sheet->setCellValue('C'.$comp, 'Date sortie');
          $sheet->setCellValue('D'.$comp, 'Motif');

          foreach($integration as  $integration1)
          {
            if($integration1->getInfosIn()==1)
			$motif1 = "baptême";
            else if($integration1->getInfosIn()==2)
            $motif1 = "transfert";
            else if($integration1->getInfosIn()==3)
            $motif1 = "profession de foi";
            else
            $motif1 = "autre";


            $comp++;
            $sheet->setCellValue('A'.$comp, $integration1->getDateIn()->format('d-m-Y'));
            $sheet->setCellValue('B'.$comp,$motif1);
            $sheet->setCellValue('C'.$comp,$integration1->getDateOut()->format('d-m-Y'));
            $sheet->setCellValue('D'.$comp,$integration1->getInfosOut());
          }




          $comp++;

          $sheet->setCellValue('A'. $comp, 'Evenement information');
          $comp++;
          $sheet->setCellValue('A'.$comp, 'Date (j/m/a)');
          $sheet->setCellValue('B'.$comp,'Type dévénement');
          $sheet->setCellValue('C'.$comp, 'Lieu');
          $sheet->setCellValue('D'.$comp, 'Infos additionnelles');

          foreach($evenement as  $evenement1)
          {
            $comp++;
            $sheet->setCellValue('A'.$comp, $evenement1->getEventDate()->format('d-m-Y'));
            $sheet->setCellValue('B'.$comp,$evenement1->getEventType());
            $sheet->setCellValue('C'.$comp,$evenement1->getEventLieu());
            $sheet->setCellValue('D'.$comp,$evenement1->getEventInfos());
          }





  /*

          $sheet->getCell('A1')->setValue('Nom');
          $sheet->getCell('B1')->setValue('Prenom');
          $sheet->getCell('C1')->setValue('Date de naissance');
          $sheet->getCell('D1')->setValue('Phone');
          $sheet->getCell('E1')->setValue('Adresse');
  
          // Increase row cursor after header write
              $sheet->fromArray($this->getData(),null, 'A2', true);
          
            
            */

            $date = date('d-m-y-'.substr((string)microtime(), 1, 8));
            $date = str_replace(".", "", $date);
            $filename = "export_".$date.".xlsx";
              try 
              {
                 $writer = new Xlsx($spreadsheet);
                 $writer->save($filename);
                 $content = file_get_contents($filename);
              }  catch(Exception $e) 
              {
                 exit($e->getMessage());
              }
                 header("Content-Disposition: attachment; filename=".$filename);
                 unlink($filename);
                 exit($content);
  
          return $this->redirectToRoute('admin_membre_index');
      }
  

	
    /**
     * Creates a new Post entity.
     *
     * @Route("/new", methods="GET|POST", name="admin_post_new")
     *
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
/*   
   public function new(Request $request): Response
    {
        $post = new Post();
        $post->setAuthor($this->getUser());

        // See https://symfony.com/doc/current/form/multiple_buttons.html
        $form = $this->createForm(PostType::class, $post)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See https://symfony.com/doc/current/forms.html#processing-forms
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($post);
            $em->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See https://symfony.com/doc/current/controller.html#flash-messages
            $this->addFlash('success', 'post.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('admin_post_new');
            }

            return $this->redirectToRoute('admin_post_index');
        }

        return $this->render('admin/blog/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }
*/
    /**
     * Finds and displays a Post entity.
     *
     * @Route("/{id<\d+>}", methods="GET", name="admin_post_show")
     */
/*

  public function show(Post $post): Response
    {
        // This security check can also be performed
        // using an annotation: @IsGranted("show", subject="post", message="Posts can only be shown to their authors.")
        $this->denyAccessUnlessGranted(PostVoter::SHOW, $post, 'Posts can only be shown to their authors.');

        return $this->render('admin/blog/show.html.twig', [
            'post' => $post,
        ]);
    }
*/
    /**
     * Displays a form to edit an existing Post entity.
     *
     * @Route("/{id<\d+>}/edit", methods="GET|POST", name="admin_post_edit")
     * @IsGranted("edit", subject="post", message="Posts can only be edited by their authors.")
     */
	 /*
    public function edit(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->flush();

            $this->addFlash('success', 'post.updated_successfully');

            return $this->redirectToRoute('admin_post_edit', ['id' => $post->getId()]);
        }

        return $this->render('admin/blog/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }
*/
    /**
     * Deletes a Post entity.
     *
     * @Route("/{id}/delete", methods="POST", name="admin_post_delete")
     * @IsGranted("delete", subject="post")
     */
	 /*
    public function delete(Request $request, Post $post): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_post_index');
        }

        // Delete the tags associated with this blog post. This is done automatically
        // by Doctrine, except for SQLite (the database used in this application)
        // because foreign key support is not enabled by default in SQLite
        $post->getTags()->clear();

        $em = $this->doctrine->getManager();
        $em->remove($post);
        $em->flush();

        $this->addFlash('success', 'post.deleted_successfully');

        return $this->redirectToRoute('admin_post_index');
    }
	*/
}
