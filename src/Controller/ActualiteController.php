<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Actualite;
use App\Form\ActualiteType;
use Doctrine\Persistence\ManagerRegistry;

final class ActualiteController extends AbstractController
{
    #[Route('/actualite', name: 'app_actualite')]
    public function index(Request $request,EntityManagerInterface $entityManager): Response
    {

        $actuc= new Actualite();
        $form=$this->createForm(ActualiteType::class, $actuc);
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
    
            $this->addFlash('success', 'actualité ajouté avec succès!');
            return $this->redirectToRoute('app_actualite');
        }
    
        return $this->render('actualite/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }





    #[Route('/listeactualite ', name: 'app_listeactualite')]
public function listeactualitevideo(ManagerRegistry $mr, Request $request, EntityManagerInterface $entityManager) : Response
{
    // Nombre d'éléments par page
    $limit = 3;

    // Numéro de la page (par défaut 1)
    $page = max(1, $request->query->getInt('page', 1));

    // Calculer l'offset
    $offset = ($page - 1) * $limit;

    // Récupérer le repository
    $repository = $mr->getRepository(Actualite::class);

    // Récupérer le total des entrées
    $total = $entityManager->createQueryBuilder()
        ->select('count(a.id)')
        ->from(Actualite::class, 'a')
        ->getQuery()
        ->getSingleScalarResult();

    // Récupérer les entrées paginées
    $allactualiteimage = $repository->findBy([], null, $limit, $offset);

    // Calculer le nombre total de pages
    $totalPages = ceil($total / $limit);

    // Renvoyer les données à la vue
    return $this->render('listeactualite/index.html.twig', [
        'allactualiteimage' => $allactualiteimage,
        'currentPage' => $page,
        'totalPages' => $totalPages,
    ]);
}

#[Route('/actualite/{id}/edit', name: 'actualite_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, $id, EntityManagerInterface $entityManager): Response
{
    // Récupérer l'entité Actuc par son ID
    $actuc = $entityManager->getRepository(Actualite::class)->find($id);

    if (!$actuc) {
        throw $this->createNotFoundException('L\'actualite avec l\'ID ' . $id . ' n\'existe pas.');
    }

    $form = $this->createForm(ActualiteType::class, $actuc);
    $form->handleRequest($request);

    // Traitement du formulaire
    if ($form->isSubmitted() && $form->isValid()) {
        // Gestion des fichiers, sauvegarde, redirection...
    }
        // Création du formulaire pour l'édition de l'entité
$form = $this->createForm(ActualiteType::class, $actuc);
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
    $this->addFlash('success', 'actualite modifié avec succès!'); 
// Redirection vers la liste des jeux    
return $this->redirectToRoute('app_listeactualite');
}
    return $this->render('actualite/edit.html.twig', [
        'form' => $form->createView(),
        'actualiteimage' => $actuc,
    ]);
}

#[Route('/actualite/{id}/delete', name: 'actualite_delete', methods: ['POST'])]
public function delete(Request $request, $id, EntityManagerInterface $entityManager): Response
{
    // Utilisation de EntityManagerInterface pour récupérer l'entité Actuc
    $actuc = $entityManager->getRepository(Actualite::class)->find($id);

    if (!$actuc) {
        throw $this->createNotFoundException('actualite avec l\'ID ' . $id . ' n\'existe pas.');
    }

    // CSRF validation
    if ($this->isCsrfTokenValid('delete' . $actuc->getId(), $request->request->get('_token'))) {
        $entityManager->remove($actuc);
        $entityManager->flush();
        $this->addFlash('success', 'actualite supprimé avec succès!');
    }

    return $this->redirectToRoute('app_listeactualite');
}
}
