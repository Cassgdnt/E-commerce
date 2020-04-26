<?php

namespace App\Controller;

use App\Entity\ContenuPanier;
use App\Entity\Panier;
use App\Entity\Produit;
use App\Form\ContenuPanierType;
use App\Repository\ContenuPanierRepository;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/{_locale}")
 */
/**
 * @Route("/contenu_panier")
 */
class ContenuPanierController extends AbstractController
{
    /**
     * @Route("/", name="contenu_panier_index", methods={"GET"})
     */
    public function index(ContenuPanierRepository $contenuPanierRepository): Response
    {
        return $this->render('contenu_panier/index.html.twig', [
            'contenu_paniers' => $contenuPanierRepository->findAll(),
        ]);
    }
    /**
     *@Route("/add/{id}", name="addPanier")
     */
    public function ajout(Produit $produit, $id, ProduitRepository $repository, PanierRepository $panierRepo, ContenuPanier $contenuPanier, Request $request): Response
    {
        $produit=$repository->find($id);
        $panier = $panierRepo->findOneBy(['Utilisateur' => $this->getUser(), 'Etat' => false]);
        $em = $this->getDoctrine()->getManager();
        if($panier==null){
            $panier= new Panier;
            $panier->setEtat(false);
            $panier->setUtilisateur($this->getUser());
            $panier->setContenuPanier($contenuPanier);
            $em->persist($panier);
        }
    return $this->render('produit/show.html.twig', [
        'produit'=>$produit
    ]);
    }

    /**
     * @Route("/new", name="contenu_panier_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $contenuPanier = new ContenuPanier();
        $form = $this->createForm(ContenuPanierType::class, $contenuPanier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contenuPanier);
            $entityManager->flush();

            return $this->redirectToRoute('contenu_panier_index');
        }

        return $this->render('contenu_panier/new.html.twig', [
            'contenu_panier' => $contenuPanier,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contenu_panier_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ContenuPanier $contenuPanier): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contenuPanier->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($contenuPanier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('contenu_panier_index');
    }
}
