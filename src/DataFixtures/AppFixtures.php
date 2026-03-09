<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Listing;
use App\Entity\Rating;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function load(ObjectManager $manager): void
    {
        // ── Categories ───────────────────────────────────────────
        $categoryNames = [
            'Science & Maths',
            'Lab & Equipment',
            'Humanities',
            'Languages',
            'Stationery & Supplies',
            'Computing & IT',
            'Art & Design',
            'Music',
            'Physical Education',
            'Reference & Revision',
        ];

        $categories = [];
        foreach ($categoryNames as $name) {
            $category = new Category();
            $category->setName($name);
            $manager->persist($category);
            $categories[] = $category;
        }

        // ── Users ────────────────────────────────────────────────
        $userData = [
            ['anna@example.com',       'password', ['ROLE_USER']],
            ['tom@example.com',        'password', ['ROLE_USER']],
            ['lisa@example.com',       'password', ['ROLE_USER']],
            ['mark@example.com',       'password', ['ROLE_USER']],
            ['emma@example.com',       'password', ['ROLE_USER']],
            ['admin@example.com',      'admin123', ['ROLE_USER', 'ROLE_ADMIN']],
            ['superadmin@example.com', 'admin123', ['ROLE_USER', 'ROLE_ADMIN']],
        ];

        $users = [];
        foreach ($userData as [$email, $plain, $roles]) {
            $user = new User();
            $user->setEmail($email);
            $user->setPassword($this->passwordHasher->hashPassword($user, $plain));
            $user->setRoles($roles);
            $manager->persist($user);
            $users[] = $user;
        }

        // ── Listings ─────────────────────────────────────────────
        $listingData = [
            ['Math Textbook Grade 10',       '15.00', 'Well-used but complete. All pages intact, some pencil notes.',      0, 0],
            ['Physics Workbook',             '20.00', 'Like new. Only first three chapters completed.',                    0, 1],
            ['Biology Reference Book',       '25.00', 'Excellent condition. Hardcover edition with full diagrams.',        0, 2],
            ['Statistics & Probability',     '18.00', 'Good condition. Highlighter on some pages.',                       0, 3],
            ['Algebra Practice Book',        '12.00', 'Barely used. Clean pages throughout.',                             0, 4],
            ['Chemistry Lab Kit',            '30.00', 'Full set, all pieces present. Minor wear on case.',                1, 1],
            ['Dissection Kit',               '22.00', 'Complete set in original pouch. Lightly used.',                   1, 2],
            ['Digital Caliper',              '14.00', 'Working perfectly. Batteries included.',                           1, 0],
            ['Safety Goggles (2 pairs)',      '8.00',  'Clean and scratch-free. Elastic still firm.',                     1, 3],
            ['Bunsen Burner Stand',          '11.00', 'Metal stand, slight rust on base but stable.',                    1, 4],
            ['History of Europe Notes',      '10.00', 'Handwritten notes, very detailed. A4 binder included.',           2, 2],
            ['Geography Textbook',           '16.00', 'Good condition. Some page corners folded.',                       2, 0],
            ['Philosophy Introduction',      '19.00', 'Paperback, lightly annotated. Great for beginners.',              2, 1],
            ['World History Atlas',          '23.00', 'Hardcover atlas, full colour maps. Very good condition.',         2, 3],
            ['Sociology Study Guide',        '13.00', 'Printed study guide with practice questions.',                    2, 4],
            ['French Dictionary',            '12.00', 'Compact edition. Perfect for exams.',                             3, 3],
            ['German Grammar Reference',     '17.00', 'Like new. Never really used.',                                    3, 1],
            ['Spanish Workbook A2',           '9.00', 'Half completed, remaining pages blank.',                          3, 0],
            ['Latin Vocabulary Cards',        '7.00', '200 flashcards, lightly used. Elastic band included.',            3, 2],
            ['English Literature Anthology', '21.00', 'Good condition. Some underlined passages.',                       3, 4],
            ['Notebook Bundle (5x)',          '8.00', 'Five A5 lined notebooks. Two partially used.',                    4, 4],
            ['Geometry Set',                  '6.00', 'Full set in tin. Compass slightly loose but functional.',         4, 0],
            ['Highlighter Pack (8 colours)',  '5.00', 'All working. Caps intact.',                                       4, 1],
            ['Lever Arch Folder Bundle',     '10.00', 'Three folders, assorted colours, light use.',                     4, 2],
            ['Scientific Calculator',        '28.00', 'Casio FX-85. Full working order. No scratches.',                 4, 3],
            ['Python Programming Guide',     '22.00', 'Great intro book. Some sticky note bookmarks.',                   5, 0],
            ['Web Development Handbook',     '27.00', 'Covers HTML, CSS, JS. Good condition.',                          5, 1],
            ['Computer Science Revision',    '15.00', 'A-level revision guide. Clean pages.',                           5, 2],
            ['Raspberry Pi Starter Kit',     '35.00', 'All components included. Used for one project.',                 5, 3],
            ['USB Flash Drive 32GB',          '9.00', 'Works perfectly. No files on it.',                               5, 4],
            ['Sketching Pencil Set',         '11.00', '12 pencils, 2B to 9B. Lightly used.',                            6, 1],
            ['A3 Sketchbook',                 '8.00', 'Half used. Good quality paper.',                                  6, 2],
            ['Watercolour Set',              '18.00', 'All colours present. Tubes mostly full.',                         6, 0],
            ['Art History Textbook',         '20.00', 'Hardcover with full-colour plates. Excellent condition.',        6, 3],
            ['Design Technology Folder',     '14.00', 'Full portfolio folder with sample projects inside.',             6, 4],
            ['Music Theory Grade 3 Book',    '10.00', 'ABRSM official book. Some pencil marks.',                        7, 3],
            ['Recorder (soprano)',            '7.00', 'Clean and working. Comes with case.',                             7, 0],
            ['Guitar Chord Poster',           '4.00', 'A2 size, laminated. Minor corner wear.',                         7, 1],
            ['Music Manuscript Notebook',     '5.00', 'Blank manuscript paper. Half used.',                             7, 2],
            ['Keyboard Lesson Book 1',        '9.00', 'Beginner lessons. Good condition.',                              7, 4],
            ['Sports Science Textbook',      '19.00', 'GCSE level. Good condition with minimal notes.',                 8, 4],
            ['Athletic Training Journal',     '6.00', 'Partially filled training log. Spiral bound.',                   8, 0],
            ['Resistance Bands Set',         '12.00', 'Three bands, light/medium/heavy. Good condition.',               8, 1],
            ['Swimming Training Manual',     '15.00', 'Paperback. Clean copy.',                                         8, 2],
            ['PE Theory Revision Cards',      '8.00', '80 revision cards covering key topics.',                         8, 3],
            ['Oxford Study Dictionary',      '14.00', 'Hardcover. Very good condition.',                                9, 2],
            ['Revision Planner & Timetable',  '5.00', 'Printed planner, mostly unused.',                               9, 1],
            ['Past Exam Papers Bundle',      '11.00', 'Five years of past papers. Some completed in pencil.',           9, 0],
            ['Mind Maps Study Pack',          '9.00', 'Pre-made mind maps for 8 subjects.',                             9, 3],
            ['Flashcard Box Set',            '13.00', '150 blank cards with index box. Never used.',                    9, 4],
        ];

        foreach ($listingData as [$name, $price, $description, $categoryIndex, $userIndex]) {
            $listing = new Listing();
            $listing->setName($name);
            $listing->setPrice($price);
            $listing->setDescription($description);
            $listing->setCategory($categories[$categoryIndex]);
            $listing->setUser($users[$userIndex]);
            $manager->persist($listing);
        }

        // ── Ratings ───────────────────────────────────────────────
        // [seller index, rater index, score, comment]
        // A user cannot rate themselves, so seller != rater throughout
        $ratingData = [
            [0, 1, 5, 'Super fast handover, item exactly as described. Highly recommend!'],
            [0, 2, 4, 'Good seller, book was in great shape. Would buy from again.'],
            [0, 3, 5, 'Really friendly and punctual. Perfect transaction.'],
            [1, 0, 4, 'Item was as listed. Quick and easy pickup.'],
            [1, 2, 5, 'Brilliant seller — even threw in some extra notes for free!'],
            [1, 4, 3, 'Took a while to respond but item was fine in the end.'],
            [2, 0, 5, 'Very honest about the condition. Great experience.'],
            [2, 1, 4, 'Smooth handover. Book was clean and complete.'],
            [2, 3, 2, 'Item had more wear than described. Bit disappointed.'],
            [3, 0, 5, 'Absolutely perfect. Would buy from this seller again without hesitation.'],
            [3, 1, 4, 'Good communication, easy pickup at school.'],
            [3, 4, 5, 'Lovely seller, great price for the condition.'],
            [4, 0, 3, 'Item was okay, but description was a bit generous on condition.'],
            [4, 1, 5, 'Really nice person, super helpful. Great seller.'],
            [4, 2, 4, 'All good. Prompt and reliable.'],
        ];

        foreach ($ratingData as [$sellerIndex, $raterIndex, $score, $comment]) {
            $rating = new Rating();
            $rating->setSeller($users[$sellerIndex]);
            $rating->setRater($users[$raterIndex]);
            $rating->setScore($score);
            $rating->setComment($comment);
            $manager->persist($rating);
        }

        $manager->flush();
    }
}
