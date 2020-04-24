<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(UserRepository $userRepository)
    { 
        return $this->render('admin/index.html.twig', [
            'users' => $userRepository->findListUsers(),
        ]);
    }

    /**
     * @Route("/editRole/{id}", name="editRole")
     */
    public function editRole(User $user = null){
        if($user == null){
            return $this->redirectToRoute('pays');
        }

        if( $user->hasRole('ROLE_USER') ) {
            $user->setRoles( ['ROLE_ADMIN'] );
        }

        if($user->hasRole('ROLE_ADMIN')) {
            $user->setRoles(['ROLE_SUPERADMIN']);
        }
        if($user->hasRole('ROLE_SUPERADMIN')){
            $user->setRoles(['ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPERADMIN']);
        }

        else{
            $user->setRoles( ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPERADMIN'] );
        }
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $this->addFlash("success", "Role modifié");
        return $this->redirectToRoute('users');
    }

    /**
     * @Route("/users", name="users")
     */
    public function users(){
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findAll();

        return $this->render('admin/user.html.twig', [
            'users' => $users
        ]);
    }
}
