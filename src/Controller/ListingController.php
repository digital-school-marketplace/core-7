<?php

namespace App\Controller;

use App\Repository\ListingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ListingController extends AbstractController
{
    #[Route('/listings', name: 'listing_index')]
    public function index(ListingRepository $listingRepository): Response
    {
        $listings = $listingRepository->findAll();

        return $this->render('listing/index.html.twig', [
            'listings' => $listings,
        ]);
    }
}
