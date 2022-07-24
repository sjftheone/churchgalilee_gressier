<?php

namespace App\Controller\Admin;

use App\Entity\Bapteme;
use App\Entity\Personne;
use App\Form\BaptemeType;
use App\Form\PersonneType;
use App\Repository\BaptemeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @Route("/admin/bapteme")
 */
class AdminBaptemeController extends AbstractController
{

    private $doctrine;
    public function __construct(ManagerRegistry $doctrine)
    {
       $this->doctrine=$doctrine;
    }


    /**
     * @Route("/", name="bapteme_index", methods={"GET"})
     */
    public function index(BaptemeRepository $baptemeRepository): Response
    {
        return $this->render('admin/bapteme/index.html.twig', [
            'baptemes' => $baptemeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{id}", name="admin_bapteme_new", methods={"GET","POST"})
     */
    public function new(Request $request,Personne $personne ): Response
    {
        $bapteme = new Bapteme();

        $perso=$this->doctrine
            ->getRepository(Personne::class)
            ->find($personne->getId());

     //   $bapteme = $perso->getBaptemes();

        $form = $this->createForm(BaptemeType::class, $bapteme);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->doctrine->getManager();
           $bapteme->setPersonne($personne);

            $entityManager->persist($bapteme);
            $entityManager->flush();

           // return $this->redirectToRoute('admin_bapteme_index', [], Response::HTTP_SEE_OTHER);
            return $this->redirectToRoute('admin_membre_edit', ['id' => $personne->getId()]);
        
        }

        return $this->renderForm('admin/bapteme/new.html.twig', [
            
            'bapteme' => $bapteme,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_bapteme_show", methods={"GET"})
     */
    public function show(Bapteme $bapteme): Response
    {
        $form = $this->createForm(BaptemeType::class, $bapteme);

        return $this->render('admin/bapteme/show.html.twig', [
            'bapteme' => $bapteme,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_bapteme_edit", methods={"GET","POST"})
     */
    public function edit(Request $request,Bapteme $bapteme): Response
    {
        $form = $this->createForm(BaptemeType::class, $bapteme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->flush();
            $this->addFlash('success', 'Modification  reusie');
          
            return $this->redirectToRoute('admin_bapteme_edit', ['id' => $bapteme->getId()]);
           // return $this->redirect($request->request->get('referer'));
            //   return $this->redirectToRoute('bapteme_index');

          //  return $this->redirectToRoute('admin_membre_edit', ['id' => $personne->getId()]);
           // return $this->redirectToRoute('admin_membre_edit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/bapteme/edit.html.twig', [
            'bapteme' => $bapteme,
            'form' => $form,
        ]);
    }
//@Route("/{id}", name="admin_bapteme_delete", methods={"POST"})
    /**
     *
     * @Route("/{id}/delete", methods="GET|POST", name="admin_bapteme_delete")
     */
    public function delete(Request $request, Bapteme $bapteme): Response
    {

        if (!$this->isCsrfTokenValid('delete', $request->request->get('token')))
        {
            return $this->redirectToRoute('admin_bapteme_index');
        }

    //    if ($this->isCsrfTokenValid('delete'.$bapteme->getId(), $request->request->get('_token'))) {
            $entityManager = $this->doctrine->getManager();
            $entityManager->remove($bapteme);
            $entityManager->flush();
        $this->addFlash('success', 'Suppression réussie');

            return $this->redirectToRoute('admin_membre_index');
    }
}
