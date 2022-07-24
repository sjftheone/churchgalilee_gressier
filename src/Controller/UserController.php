<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Personne;
use App\Entity\User;
use App\Form\EvenementType;
use App\Form\PersonneType;
use App\Form\Type\ChangePasswordType;
use App\Form\UserType;
use App\Form\UserPersonnelType;

use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Controller used to manage current user.
 *
 * @Route("/profile")
 * @IsGranted("ROLE_USER")
 *
 * @author Romain Monteil <monteil.romain@gmail.com>
 */
class UserController extends AbstractController
{

    private $doctrine;
    public function __construct(ManagerRegistry $doctrine)
    {
       $this->doctrine=$doctrine;
    }
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }




    /**
     * @Route("/edit", methods="GET|POST", name="user_personnel_edit")
     */
    public function edit_personnel(Request $request): Response
    {
         $user = $this->getUser();

        $form = $this->createForm(UserPersonnelType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->flush();
            $this->addFlash('success', 'user successfully update');
            return $this->redirectToRoute('user_personnel_edit');
        }

        return $this->render('user/personnel_edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/{id}/edit", methods="GET|POST", name="user_edit")
     */
    public function edit(Request $request,User $user,UserPasswordHasherInterface $passwordEncoder): Response
    {
       // $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user)

            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => 
            [
                'label' => false,
                'attr' => [
                    'class' => 'form-control trapezoid',
                    'placeholder' => 'Password'
                ],
                'row_attr' => [
                    'class' => 'col-6', 
                ]
            ],
            'second_options' => [
                'label' => false,
                'attr' => [
                    'class' => 'form-control trapezoid',
                    'placeholder' => 'repeat Password'
                ],
                'row_attr' => [
                    'class' => 'col-6', 
                ]
            ],
            ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->hashPassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $this->doctrine->getManager()->flush();

            $this->addFlash('success', 'user.updated_successfully');

            return $this->redirectToRoute('user_index');

        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new/", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordHasherInterface $passwordEncoder ): Response
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user)
            ->add('plainPassword', RepeatedType::class, array(
            'type' => PasswordType::class,
            'first_options'  => 
            [
                'label' => false,
                'attr' => [
                    'class' => 'form-control trapezoid',
                    'placeholder' => 'Password'
                ],
                'row_attr' => [
                    'class' => 'col-10', 
                ]
            ],
            'second_options' => [
                'label' => false,
                'attr' => [
                    'class' => 'form-control trapezoid',
                    'placeholder' => 'repeat Password'
                ],
                'row_attr' => [
                    'class' => 'col-10', 
                ]
            ],
        ))

        ;


     //   $form = $this->createForm(PersonneType::class, $personne)->add('saveAndCreateNew', SubmitType::class);

// 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->hashPassword($user, $user->getPlainPassword());
            $user->setPassword($password);


            // 4) save the User!
            $em = $this->doctrine->getManager();
            $em->persist($user);
            $em->flush();

            // return $this->redirectToRoute('admin_bapteme_index', [], Response::HTTP_SEE_OTHER);
            return $this->redirectToRoute('user_index');
        }
        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }






        /**
     * @Route("/change-password", methods="GET|POST", name="user_change_password")
     */
    public function changePassword(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($hasher->hashPassword($user, $form->get('newPassword')->getData()));

            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('security_logout');
        }

        return $this->render('user/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }




    /**
     *
     * @Route("/{id}/delete", methods="GET|POST", name="user_delete")
     */
    public function delete(Request $request, User $user): Response
    {

        if (!$this->isCsrfTokenValid('delete', $request->request->get('token')))
        {
            return $this->redirectToRoute('user_index');
        }

        //    if ($this->isCsrfTokenValid('delete'.$integration->getId(), $request->request->get('_token'))) {
        $entityManager = $this->doctrine->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
        $this->addFlash('success', 'Suppression utilisateur réussie');

        return $this->redirectToRoute('user_index');
    }








}
