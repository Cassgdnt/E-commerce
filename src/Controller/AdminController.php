<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/editRole/{id}", name="editRole")
     */
    public function editRole(User $user = null){
        if($user == null){
            return $this->redirectToRoute('pays');
        }

        if( $user->hasRole('ROLE_ADMIN') ){
            $user->setRoles( ['ROLE_USER'] );
        }
        else{
            $user->setRoles( ['ROLE_USER', 'ROLE_ADMIN'] );
        }
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $this->addFlash("success", "Role modifié");
        return $this->redirectToRoute('produit_index');
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
