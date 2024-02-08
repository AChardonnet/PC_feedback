<?php

namespace App\Controller;

use App\Entity\Feedback;
use App\Form\ValidatorType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_MODERATOR')]
#[Route('/moderator')]
class ModeratorController extends AbstractController
{
    #[Route('/feedbacks', name : 'moderator_feedbacks')]
    public function invalidFeedbacks(EntityManagerInterface $entityManager)
    {
        return $this->render('moderator/invalidFeedbacks.html.twig', [
            'invalidFeedbacks' => $entityManager->getRepository(Feedback::class)->invalidFeedbacks(),
        ]);
    }

    #[Route('/feedback/{id}', name: 'moderator_feedback')]
    public function validateFeedback(int $id, EntityManagerInterface $entityManager, Request $request)
    {
        $feedback = $entityManager->getRepository(Feedback::class)->find($id);

        $form = $this->createForm(ValidatorType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('validate')->isClicked()) {
                $feedback->setValid(true);
            } else {
                $entityManager->remove($feedback);
            }
            $entityManager->flush();

            return $this->redirectToRoute('moderator_feedbacks');
        }

        return $this->render('moderator/validateFeedback.html.twig', [
            'feedback' => $feedback,
            'form' => $form,
        ]);
    }
}
