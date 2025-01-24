<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Actualite;
use App\Form\ActualiteType;

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
}
