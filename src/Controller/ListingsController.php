<?php

namespace App\Controller;

use App\Service\ListingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ListingsController extends AbstractController
{

    private ListingService $service;

    public function __construct(ListingService $listingService) {
        $this->service = $listingService;
    }

    // Route for showing all current listings at /listings/current
    #[Route('/listings/current', name: 'listings_current')]
    public function current(): Response
    {
        // Twig template to render listings page
        return $this->render('listings/listings.html.twig', [
            'listings' => $this->service->getListings(),
        ]);
    }

    // Route for showing detailed listing info
    #[Route('/listings/{id}', name: 'listings_detailed')]
    public function show(int $id): Response
    {
        // Looping through all listings to get infos for a certain item
        $listing = null;
        foreach ($this->service->getListings() as $item) {
            if ($item['id'] === $id) {
                $listing = $item;
                break;
            }
        }

        if (!$listing) {
            return $this->render('error/error.html.twig', [
                'error' => '404 Listing not found',
                'msg' => 'This listing does not exist.',
            ]);
        }

        // Twig template to render detailed listing page
        return $this->render('listings/detail.html.twig', [
            'listing' => $listing,
        ]);
    }
}
