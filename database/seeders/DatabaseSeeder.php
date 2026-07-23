<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Fine;
use App\Models\Librarian;
use App\Models\Loan;
use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin User
        $admin = User::create([
            'name' => 'System Admin',
            'email' => 'admin@library.org',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 2. Librarian User
        $librarianUser = User::create([
            'name' => 'Head Librarian',
            'email' => 'librarian@library.org',
            'password' => Hash::make('password123'),
            'role' => 'librarian',
        ]);

        Librarian::create([
            'user_id' => $librarianUser->id,
            'employee_id' => 'LIB-1002',
            'department' => 'Circulation & OPAC Services',
        ]);

        // 3. Member User
        $memberUser = User::create([
            'name' => 'Alex Johnson',
            'email' => 'member@library.org',
            'password' => Hash::make('password123'),
            'role' => 'member',
        ]);

        $member = Member::create([
            'user_id' => $memberUser->id,
            'member_number' => 'MEM-2026',
            'membership_tier' => 'student',
            'borrow_limit' => 5,
            'is_banned' => false,
        ]);

        // 4. Books
        $booksData = [
            [
                'isbn' => '978-0132350884',
                'title' => 'Clean Code: A Handbook of Agile Software Craftsmanship',
                'author' => 'Robert C. Martin',
                'publisher' => 'Prentice Hall',
                'genre' => 'Technology',
                'description' => 'Even bad code can function. But if code isn\'t clean, it can bring a development organization to its knees.',
                'publication_year' => 2008,
                'total_copies' => 3,
                'available_copies' => 2,
            ],
            [
                'isbn' => '978-0201616224',
                'title' => 'The Pragmatic Programmer: Your Journey To Mastery',
                'author' => 'Andrew Hunt & David Thomas',
                'publisher' => 'Addison-Wesley',
                'genre' => 'Technology',
                'description' => 'One of the most significant books in software development for pragmatic career growth.',
                'publication_year' => 1999,
                'total_copies' => 2,
                'available_copies' => 1,
            ],
            [
                'isbn' => '978-0743273565',
                'title' => 'The Great Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'publisher' => 'Scribner',
                'genre' => 'Classic Fiction',
                'description' => 'A tragic story of Jay Gatsby, a self-made millionaire, and his pursuit of Daisy Buchanan.',
                'publication_year' => 1925,
                'total_copies' => 2,
                'available_copies' => 2,
            ],
        ];

        foreach ($booksData as $bData) {
            $book = Book::create($bData);
            for ($i = 1; $i <= $book->total_copies; $i++) {
                $copy = BookCopy::create([
                    'book_id' => $book->id,
                    'barcode' => 'BC-' . str_replace('-', '', $book->isbn) . '-' . str_pad((string)$i, 3, '0', STR_PAD_LEFT),
                    'condition' => 'good',
                    'status' => $i === 1 && $book->id === 1 ? 'checked_out' : 'available',
                    'location_rack' => 'Rack-' . rand(1, 10),
                ]);

                if ($i === 1 && $book->id === 1) {
                    $loan = Loan::create([
                        'book_copy_id' => $copy->id,
                        'member_id' => $member->id,
                        'loan_date' => now()->subDays(10),
                        'due_date' => now()->addDays(4),
                        'status' => 'active',
                        'renewal_count' => 0,
                    ]);
                }
            }
        }
    }
}
