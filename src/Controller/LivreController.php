<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\LivreType;
use App\Form\SearchLivreType; // Import the SearchLivreType form type
use App\Form\SearchLivreByAuthorType; 
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LivreController extends AbstractController
{

    /* Fonction de récupération de tous les livres */
    #[Route("/", name: "app_livres")]
    public function livres(LivreRepository $livreRepository)
    {
        $livres = $livreRepository->findAll();
        return $this->render('livre/listesLivres.html.twig', array(
            'livres' => $livres
        ));
    }

    /* Fonction d'ajout d'un livre */
    #[Route('/ajouter', name: 'app_ajouter_livre')]
    public function ajouterLivre(Request $request, EntityManagerInterface $em)
    {
        $livre = new Livre();
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($livre);
            $em->flush();
            return $this->redirectToRoute('app_livres');
        }
        return $this->render('livre/ajouterLivre.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /* Fonction de modification d'un livre */
    #[Route("/modifier/{id<\d+>}", name: "app_modifier_livre")]
    public function modifierLivre(Request $request, Livre $livre, EntityManagerInterface $em)
    {
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_livres');
        }
        return $this->render('livre/modifierLivre.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /* Fonction de suppression d'un livre */
    #[Route("/supprimer/{id<\d+>}", name: "app_supprimer_livre")]
    public function supprimerLivre(Livre $livre, EntityManagerInterface $em)
    {
        $em->remove($livre);
        $em->flush();
        return $this->redirectToRoute('app_livres');
    }


    /* Fonction de recherche d'un livre par nom*/
    #[Route('/recherche', name: 'app_recherche_livre')]
    public function rechercheLivre(Request $request, LivreRepository $livreRepository)
    {
        $form = $this->createForm(SearchLivreType::class); // Create the search form
        $form->handleRequest($request);

        $livres = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $searchTerm = $form->get('nomLivre')->getData();
            if ($searchTerm) {
                $livres = $livreRepository->findByNomLivre($searchTerm);
            } else {
                // If the search term is empty, show all books
                $livres = $livreRepository->findAll();
            }
        } else {
            // If form is not submitted, show all books
            $livres = $livreRepository->findAll();
        }

        return $this->render('livre/rechercheLivre.html.twig', [
            'form' => $form->createView(),
            'livres' => $livres,
        ]);
    }
/* Function for searching books by author */
#[Route('/recherche-par-auteur', name: 'app_recherche_par_auteur')]
public function rechercheParAuteur(Request $request, LivreRepository $livreRepository)
{
    $form = $this->createForm(SearchLivreByAuthorType::class); // Create the search form
    $form->handleRequest($request);

    $livres = [];
    if ($form->isSubmitted() && $form->isValid()) {
        $searchTerm = $form->get('auteurLivre')->getData();
        if ($searchTerm) {
            $livres = $livreRepository->findByAuteurLivre($searchTerm);
        } else {
            // If the search term is empty, show all books
            $livres = $livreRepository->findAll();
        }
    } else {
        // If form is not submitted, show all books
        $livres = $livreRepository->findAll();
    }

    return $this->render('livre/rechercheParAuteur.html.twig', [
        'form' => $form->createView(),
        'livres' => $livres,
    ]);
}
}
