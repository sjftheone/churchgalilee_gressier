<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{


     /**
     * @Route("/inscription", name="register", methods={"GET","POST"})
     * return Response
     */
    public function register():Response
    {
        return $this->render('registration/register.html.twig');

    }

}

