<?php

namespace App\Controller;

use App\Entity\ContenuPanier;
use App\Entity\Panier;
use App\Entity\Produit;
use App\Form\ContenuPanierType;
use App\Form\ProduitType;
use App\Repository\ContenuPanierRepository;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
/**
 * @Route("/{_locale}")
 */
/**
 * @Route("/produit")
 */
class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="produit_index", methods={"GET"})
     */
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="produit_new", methods={"GET","POST"})
     */
    public function new(Request $request, Produit $produit=null): Response
    {   if(!$produit){
        $produit = new Produit();
    }
    $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fichier = $form->get('photoProduit')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($fichier) {
                $newFilename = uniqid().'.'.$fichier->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $fichier->move(
                        $this->getParameter('upload_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    $this->addFlash('error', "Impossible d'uploader le fichier");
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $produit->setPhoto($newFilename);
            }
            
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('produit_index');
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="produit_show")
     */
    public function show(Produit $produit): Response
    {
        
    return $this->render('produit/show.html.twig', [
            'produit' => $produit,
    ]);
    }

    /**
     * @Route("/add/{id}", name="add")
     */
    public function add($id, ProduitRepository $repository, ContenuPanier $contenupanier=null, ContenuPanierRepository $repoContenu, PanierRepository $repoPanier, Request $request){
        $produit = $repository->find($id);
        $panier = $repoPanier->findOneBy(['Utilisateur' => $this->getUser(), 'Etat' => false]);
        $manager = $this->getDoctrine()->getManager();

        if ($panier === null) {
            $panier = new Panier();
            $panier->setUtilisateur($this->getUser());
            $manager->persist($panier);
            
        }

        $contenu = new ContenuPanier();
        
        $form = $this->createForm(ContenuPanierType::class, $contenu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contenu->setDate(new \DateTime())
                ->setProduit($produit)
                ->addPanier($panier);

            $manager->persist($contenu);
            $manager->flush();
            return $this->redirectToRoute("produit_index");
        }
         return $this->render('produit/add.html.twig', [
            'produit' => $produit,
            'form_panier' => $form->createView()
        ]);
    }


    /**
     * @Route("/{id}/edit", name="produit_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Produit $produit): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="produit_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Produit $produit): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('produit_index');
    }
}
