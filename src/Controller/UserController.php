<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use App\Security\UserAuthenticator;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * @Route("/{_locale}")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/profil", name="profil")
     */
    public function index(Request $request, UserInterface $user)
    {
        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }
}
