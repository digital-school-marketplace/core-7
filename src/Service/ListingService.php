<?php

namespace App\Service;

class ListingService
{
    public function getListings() : array {
        return [
            [
                'id' => 1,
                'item' => 'Math Textbook',
                'condition' => 'Good',
                'price' => 15,
                'seller' => 'Anna M.',
                'date' => '2026-02-10',
            ],
            [
                'id' => 2,
                'item' => 'Physics Workbook',
                'condition' => 'Like New',
                'price' => 20,
                'seller' => 'Tom K.',
                'date' => '2026-02-12',
            ],
            [
                'id' => 3,
                'item' => 'Chemistry Lab Kit',
                'condition' => 'Used',
                'price' => 30,
                'seller' => 'Lisa P.',
                'date' => '2026-02-08',
            ],
            [
                'id' => 4,
                'item' => 'Biology Reference Book',
                'condition' => 'Excellent',
                'price' => 25,
                'seller' => 'Mark S.',
                'date' => '2026-02-14',
            ],
            [
                'id' => 5,
                'item' => 'History Notes',
                'condition' => 'Good',
                'price' => 10,
                'seller' => 'Emma R.',
                'date' => '2026-02-11',
            ],
        ];
    }
}
