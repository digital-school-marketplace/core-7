<?php

namespace App\Controller;

use App\Entity\Listing;
use App\Form\ListingForm;
use App\Repository\ListingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/listings', name: 'listing_')]
class ListingController extends AbstractController
{
    public function __construct(
        private readonly ListingRepository      $listingRepository,
        private readonly EntityManagerInterface $em,
    )
    {
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $page = max(1, $request->query->getInt('page', 1));
        $listings = $this->listingRepository->findPaginated($page, 20);

        return $this->render('listing/index.html.twig', [
            'listings' => $listings,
            'currentPage' => $page,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $listing = new Listing();
        $form = $this->createForm(ListingForm::class, $listing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($listing);
            $this->em->flush();

            $this->addFlash('success', 'Listing created successfully.');

            return $this->redirectToRoute('listing_index');
        }

        return $this->render('listing/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Listing $listing): Response
    {
        return $this->render('listing/show.html.twig', [
            'listing' => $listing,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Listing $listing): Response
    {
        $form = $this->createForm(ListingForm::class, $listing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            $this->addFlash('success', 'Listing updated successfully.');

            return $this->redirectToRoute('listing_show', ['id' => $listing->getId()]);
        }

        return $this->render('listing/edit.html.twig', [
            'listing' => $listing,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Listing $listing): Response
    {
        if ($this->isCsrfTokenValid('delete' . $listing->getId(), $request->getPayload()->getString('_token'))) {
            $this->em->remove($listing);
            $this->em->flush();

            $this->addFlash('success', 'Listing deleted.');
        }

        return $this->redirectToRoute('listing_index');
    }
}

