<?php

namespace App\Controller;

use App\Entity\ContenuPanier;
use App\Entity\Panier;
use App\Entity\Produit;
use App\Form\PanierType;
use App\Repository\ContenuPanierRepository;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/{_locale}")
 */
/**
 * @Route("/panier")
 */
class PanierController extends AbstractController
{
    /**
     * @Route("/", name="panier_index", methods={"GET"})
     */
    public function index(PanierRepository $panierRepository, ContenuPanierRepository $contenuPanierRepo): Response
    {   
        $panier = $panierRepository->findOneBy(['Utilisateur' => $this->getUser(), 'Etat' => false]);
        $contenuPanier= $contenuPanierRepo->findAll();
        return $this->render('panier/index.html.twig', [
            'paniers' => $panierRepository->findAll()
        ]);
    }
    /**
     * @Route("/{id}", name="panier_show", methods={"GET"})
     */
    public function show(Panier $panier): Response
    {
        return $this->render('panier/show.html.twig', [
            'panier' => $panier,
        ]);
    }
    /**
     * @Route("/panier/achat", name="achat_panier")
     */
    public function edit(Request $request, Panier $panier, PanierRepository $panierRepository): Response
    {
        $panier = $panierRepository ->findOneBy(['user' => $this->getUser(), 'status' => false]);
        $em = $this->getDoctrine()->getManager();

        $panier
            ->setEtat(true)
            ->setDateAchat(new \DateTime());
        $em->persist($panier);
        $em->flush();
        return $this->redirectToRoute('produit_index');
    }

    /**
     * @Route("/{id}", name="panier_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Panier $panier): Response
    {
        if ($this->isCsrfTokenValid('delete'.$panier->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($panier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('panier_index');
    }
}
