<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Contacte;

final class ContacteController extends AbstractController
{
    #[Route('/contacte', name: 'app_contacte', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {


        if ($request->isMethod('POST')) {
            $nom = $request->request->get('nom');
            $email = $request->request->get('email');
            $subject = $request->request->get('subject');
            $phone = $request->request->get('phone');
            $message = $request->request->get('message');
    
            // Valider les données
            if (empty($nom) || empty($email) || empty($subject)  || empty($phone) || empty($message)) {
                $this->addFlash('error', 'Tous les champs sont obligatoires.');
                return $this->redirectToRoute('app_contacte');
            }
    
            
            // Créer l'objet Appointement et assigner les données
            $contacte = new Contacte();
            $contacte->setName($nom);
            $contacte->setEmail($email);
            $contacte->setSubject($subject);
            $contacte->setPhone($phone);  
            $contacte->setMessage($message);
            $contacte->setCreateAd(new \DateTime());
    
            // Sauvegarder l'entité
            try {
                $entityManager->persist($contacte);
                $entityManager->flush();
    
                $this->addFlash('success', 'Votre message a été envoyé avec succès.');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'enregistrement.');
            }
    
            return $this->redirectToRoute('app_contacte');
        }
    



        return $this->render('contacte/index.html.twig', [
            'controller_name' => 'ContacteController',
        ]);
    }
}
