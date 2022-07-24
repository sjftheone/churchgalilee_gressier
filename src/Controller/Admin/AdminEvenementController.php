<?php

namespace App\Controller\Admin;

use App\Entity\Evenement;
use App\Entity\Integration;
use App\Entity\Personne;
use App\Form\EvenementType;
use App\Form\IntegrationType;
use App\Repository\EvenementRepository;
use App\Repository\IntegrationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @Route("/admin/evenement")
 */
class AdminEvenementController extends AbstractController
{

    private $doctrine;
    public function __construct(ManagerRegistry $doctrine)
    {
       $this->doctrine=$doctrine;
    }

    /**
     * @Route("/", name="admin_evenement_index", methods={"GET"})
     */
    public function index(IntegrationRepository $evenementRepository): Response
    {
        return $this->render('admin/evenement/index.html.twig', [
            'evenements' => $evenementRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{id}", name="admin_evenement_new", methods={"GET","POST"})
     */
    public function new(Request $request,Personne $personne ): Response
    {
        $evenement = new Evenement();

        $perso=$this->doctrine
            ->getRepository(Personne::class)
            ->find($personne->getId());

        //   $bapteme = $perso->getBaptemes();

        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->doctrine->getManager();
            $evenement->setPersonnes($personne);

            $entityManager->persist($evenement);
            $entityManager->flush();
            $this->addFlash('success', 'Enregistrement  reusie');
            // return $this->redirectToRoute('admin_bapteme_index', [], Response::HTTP_SEE_OTHER);
            return $this->redirectToRoute('admin_membre_edit', ['id' => $personne->getId()]);
        }

        return $this->renderForm('admin/evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }


    /**
     * @Route("/{id}", name="admin_evenement_show", methods={"GET"})
     */
    public function show(Evenement $evenement): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);

        return $this->render('admin/evenement/show.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_evenement_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Evenement $evenement): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->flush();
            $this->addFlash('successs', 'Modification réusie');
            return $this->redirectToRoute('admin_evenement_edit', ['id' => $evenement->getId()]);
            // return $this->redirectToRoute('bapteme_index');

            //  return $this->redirectToRoute('admin_membre_edit', ['id' => $personne->getId()]);
            // return $this->redirectToRoute('admin_membre_edit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }
    /**
     *
     * @Route("/{id}/delete", methods="GET|POST", name="admin_evenement_delete")
     */
    public function delete(Request $request, Evenement $evenement): Response
    {

        if (!$this->isCsrfTokenValid('delete', $request->request->get('token')))
        {
            return $this->redirectToRoute('admin_evenement_index');
        }

        //    if ($this->isCsrfTokenValid('delete'.$integration->getId(), $request->request->get('_token'))) {
        $entityManager = $this->doctrine->getManager();
        $entityManager->remove($evenement);
        $entityManager->flush();
        $this->addFlash('success', 'Suppression réussie');

        return $this->redirectToRoute('admin_membre_index');
    }



}
