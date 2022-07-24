<?php

namespace App\Controller\Admin;

use App\Entity\Bapteme;
use App\Entity\Integration;
use App\Entity\Personne;
use App\Form\BaptemeType;
use App\Form\IntegrationType;
use App\Form\PersonneType;
use App\Repository\BaptemeRepository;
use App\Repository\IntegrationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
/**
 * @Route("/admin/integration")
 */
class AdminIntegrationController extends AbstractController
{
    private $doctrine;
public function __construct(ManagerRegistry $doctrine)
{
   $this->doctrine=$doctrine;
}


    /**
     * @Route("/", name="admin_integration_index", methods={"GET"})
     */
    public function index(IntegrationRepository $integrationRepository): Response
    {
        return $this->render('admin/integration/index.html.twig', [
            'integrations' => $integrationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{id}", name="admin_integration_new", methods={"GET","POST"})
     */
    public function new(Request $request,Personne $personne ): Response
    {
        $integration = new Integration();

        $perso=$this->doctrine
            ->getRepository(Personne::class)
            ->find($personne->getId());

        //   $bapteme = $perso->getBaptemes();

        $form = $this->createForm(IntegrationType::class, $integration);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->doctrine->getManager();
            $integration->setPersonne($personne);

            $entityManager->persist($integration);
            $entityManager->flush();
            // return $this->redirectToRoute('admin_bapteme_index', [], Response::HTTP_SEE_OTHER);
            $this->addFlash('success', 'Enregistrement  reusi');
            return $this->redirectToRoute('admin_membre_edit', ['id' => $personne->getId()]);
        }

        return $this->renderForm('admin/integration/new.html.twig', [
            'integration' => $integration,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_integration_show", methods={"GET"})
     */
    public function show(Integration $integration): Response
    {
        $form = $this->createForm(IntegrationType::class, $integration);

        return $this->render('admin/integration/show.html.twig', [
            'integration' => $integration,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_integration_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Integration $integration): Response
    {
        $form = $this->createForm(IntegrationType::class, $integration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->flush();
            $this->addFlash('success', 'Modification  réusie');
            return $this->redirectToRoute('admin_integration_edit', ['id' => $integration->getId()]);
           // return $this->redirectToRoute('bapteme_index');

            //  return $this->redirectToRoute('admin_membre_edit', ['id' => $personne->getId()]);
            // return $this->redirectToRoute('admin_membre_edit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/integration/edit.html.twig', [
            'integration' => $integration,
            'form' => $form,
        ]);
    }
    /**
     *
     * @Route("/{id}/delete", methods="GET|POST", name="admin_integration_delete")
     */
    public function delete(Request $request, Integration $integration): Response
    {

        if (!$this->isCsrfTokenValid('delete', $request->request->get('token')))
        {
            return $this->redirectToRoute('admin_integration_index');
        }

        //    if ($this->isCsrfTokenValid('delete'.$integration->getId(), $request->request->get('_token'))) {
        $entityManager = $this->doctrine->getManager();
        $entityManager->remove($integration);
        $entityManager->flush();
        $this->addFlash('success', 'Suppression réussie');

        return $this->redirectToRoute('admin_membre_index');
    }





}
