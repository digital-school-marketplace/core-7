<?php

namespace App\Controller;

use App\Entity\Rating;
use App\Entity\User;
use App\Repository\RatingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/users/{id}/rate', name: 'user_rate', methods: ['POST'])]
class RatingController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly RatingRepository $ratingRepository,
    ) {}

    public function __invoke(Request $request, User $seller): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if ($seller->getId() === $currentUser->getId()) {
            $this->addFlash('danger', 'You cannot rate yourself.');
            return $this->redirectToRoute('user_show', ['id' => $seller->getId()]);
        }

        if (!$this->isCsrfTokenValid('rate' . $seller->getId(), $request->request->getString('_token'))) {
            $this->addFlash('danger', 'Invalid request.');
            return $this->redirectToRoute('user_show', ['id' => $seller->getId()]);
        }

        $score = max(1, min(5, (int) $request->request->get('score', 5)));

        $rating = $this->ratingRepository->findBySellerAndRater($seller, $currentUser)
            ?? (new Rating())->setSeller($seller)->setRater($currentUser);

        $rating->setScore($score);
        $rating->setComment($request->request->get('comment'));

        $this->em->persist($rating);
        $this->em->flush();

        $this->addFlash('success', 'Rating submitted — thanks for the feedback!');

        return $this->redirectToRoute('user_show', ['id' => $seller->getId()]);
    }
}
