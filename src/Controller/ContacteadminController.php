<?php

namespace App\Controller;

use App\Entity\Contacte;
use App\Repository\ContacteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class ContacteadminController extends AbstractController
{
    #[Route('/contacteadmin', name: 'app_contacteadmin')]
    public function index(ContacteRepository  $ContacteRepository ): Response
    {
        $contact = $ContacteRepository->findAll();
        return $this->render('contacteadmin/index.html.twig', [
            'controller_name' => 'ContacteadminController',
            'contact'=> $contact
        ]);
    }

    #[Route('/contacteadmin/{id}/delete', name: 'contacteadmin_delete', methods: ['POST'])]
    public function delete(Request $request, $id, EntityManagerInterface $entityManager): Response
    {
        // Utilisation de EntityManagerInterface pour récupérer l'entité Actuc
        $actuc = $entityManager->getRepository(Contacte::class)->find($id);

        if (!$actuc) {
            throw $this->createNotFoundException('Le contacte avec l\'ID ' . $id . ' n\'existe pas.');
        }

        // CSRF validation
        if ($this->isCsrfTokenValid('delete' . $actuc->getId(), $request->request->get('_token'))) {
            $entityManager->remove($actuc);
            $entityManager->flush();
            $this->addFlash('success', 'contacte supprimé avec succès!');
        }

        return $this->redirectToRoute('app_contacteadmin');
    }
}
