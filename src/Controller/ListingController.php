<?php

namespace App\Controller;

use App\Entity\Listing;
use App\Form\ListingForm;
use App\Repository\CategoryRepository;
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
        private readonly CategoryRepository $categoryRepository,
    )
    {
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $page     = max(1, $request->query->getInt('page', 1));
        $category = $request->query->get('category') ? (int) $request->query->get('category') : null;;
        $minPrice = $request->query->get('min_price') ? (float) $request->query->get('min_price') : null;
        $maxPrice = $request->query->get('max_price') ? (float) $request->query->get('max_price') : null;
        $sortBy   = $request->query->get('sort', 'newest') ?? 'newest';

        $listings = $this->listingRepository->findFiltered($category, $minPrice, $maxPrice, $sortBy, $page);

        return $this->render('listing/index.html.twig', [
            'listings'   => $listings,
            'categories' => $this->categoryRepository->findAll(),
            'currentPage' => $page,
            'filters'    => [
                'category' => $category,
                'minPrice' => $minPrice,
                'maxPrice' => $maxPrice,
                'sortBy'   => $sortBy,
            ],
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $listing = new Listing();
        $form = $this->createForm(ListingForm::class, $listing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $listing->setUser($this->getUser());
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
        $recommendations = $this->listingRepository->findRecommendations($listing);

        return $this->render('listing/show.html.twig', [
            'listing'         => $listing,
            'recommendations' => $recommendations,
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

