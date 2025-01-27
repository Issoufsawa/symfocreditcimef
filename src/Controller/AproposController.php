<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Apropos;
use App\Form\AproposType;
use Doctrine\Persistence\ManagerRegistry;

final class AproposController extends AbstractController
{
    #[Route('/apropos', name: 'app_apropos')]
    public function index(Request $request,EntityManagerInterface $entityManager): Response
    {

        $actuc= new Apropos();
        $form=$this->createForm(AproposType::class, $actuc);
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
          
            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
    
                     // Déplacer le fichier dans le répertoire configuré pour les images
                    $imageFile->move(
                    $this->getParameter('images_directory'),  // Assurez-vous que ce paramètre est configuré dans `services.yaml`
                    $newFilename
                );
    
                // Définir la date de création (create_ad) à la date actuelle
                $actuc->setCreateAd(new \DateTime());
    
                // Met à jour la propriété `imagePath` de l'entité Actuc
                $actuc->setImagePath($newFilename);
            }
    
            // Sauvegarde de l'entité dans la base de données
            $entityManager->persist($actuc);
            $entityManager->flush();
    
            $this->addFlash('success', 'A propos ajouté avec succès!');
            return $this->redirectToRoute('app_apropos');
        }
    
    
        return $this->render('apropos/index.html.twig', [
           'form' => $form->createView(),
        ]);
    }


    #[Route('/listeapropos ', name: 'app_liste_apropos')]
    public function listeactualitevideo(ManagerRegistry $mr, Request $request, EntityManagerInterface $entityManager) : Response
    {
        // Nombre d'éléments par page
        $limit = 3;
    
        // Numéro de la page (par défaut 1)
        $page = max(1, $request->query->getInt('page', 1));
    
        // Calculer l'offset
        $offset = ($page - 1) * $limit;
    
        // Récupérer le repository
        $repository = $mr->getRepository(Apropos::class);
    
        // Récupérer le total des entrées
        $total = $entityManager->createQueryBuilder()
            ->select('count(a.id)')
            ->from(Apropos::class, 'a')
            ->getQuery()
            ->getSingleScalarResult();
    
        // Récupérer les entrées paginées
        $allactualiteimage = $repository->findBy([], null, $limit, $offset);
    
        // Calculer le nombre total de pages
        $totalPages = ceil($total / $limit);
    
        // Renvoyer les données à la vue
        return $this->render('liste_apropos/index.html.twig', [
            'allactualiteimage' => $allactualiteimage,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }
    
    #[Route('/apropos/{id}/edit', name: 'apropos_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, $id, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'entité Actuc par son ID
        $actuc = $entityManager->getRepository(Apropos::class)->find($id);
    
        if (!$actuc) {
            throw $this->createNotFoundException('A propos avec l\'ID ' . $id . ' n\'existe pas.');
        }
    
        $form = $this->createForm(AproposType::class, $actuc);
        $form->handleRequest($request);
    
        // Traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion des fichiers, sauvegarde, redirection...
        }
            // Création du formulaire pour l'édition de l'entité
    $form = $this->createForm(AproposType::class, $actuc);
    $form->handleRequest($request);
    
    // Traitement du formulaire une fois soumis
    if ($form->isSubmitted() && $form->isValid()) {
        // Vérification de l'upload de l'image
        $imageFile = $form->get('image')->getData();
        if ($imageFile) {
            $newFilename = uniqid() . '.' . $imageFile->guessExtension();
    // Déplacement de l'image dans le répertoire configuré
            $imageFile->move(
                $this->getParameter('images_directory'), // Assurez-vous que ce paramètre est défini dans `services.yaml`
                $newFilename
            );
    
            // Mise à jour du chemin de l'image dans l'entité
            $actuc->setImagePath($newFilename);
        }
        // Sauvegarde des modifications dans la base de données
        $entityManager->flush();
    
        // Ajout d'un message flash pour indiquer que la modification a été réussie
        $this->addFlash('success', 'a propos modifié avec succès!'); 
    // Redirection vers la liste des jeux    
    return $this->redirectToRoute('app_liste_apropos');
    }
        return $this->render('apropos/edit.html.twig', [
            'form' => $form->createView(),
            'apropos' => $actuc,
        ]);
    }
    
    #[Route('/apropos/{id}/delete', name: 'apropos_delete', methods: ['POST'])]
    public function delete(Request $request, $id, EntityManagerInterface $entityManager): Response
    {
        // Utilisation de EntityManagerInterface pour récupérer l'entité Actuc
        $actuc = $entityManager->getRepository(Apropos::class)->find($id);
    
        if (!$actuc) {
            throw $this->createNotFoundException('A propos avec l\'ID ' . $id . ' n\'existe pas.');
        }
    
        // CSRF validation
        if ($this->isCsrfTokenValid('delete' . $actuc->getId(), $request->request->get('_token'))) {
            $entityManager->remove($actuc);
            $entityManager->flush();
            $this->addFlash('success','A propos supprimé avec succès!');
        }
    
        return $this->redirectToRoute('app_liste_apropos');
    }
    }
    

