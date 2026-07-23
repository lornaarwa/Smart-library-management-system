<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\Book;
use App\Services\CatalogSearchEngine;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CatalogSearchEngineTest extends TestCase
{
    use RefreshDatabase;

    protected CatalogSearchEngine $engine;

    protected function setUp(): void
    {
        parent::setUp();
        $this->engine = new CatalogSearchEngine();
    }

    public function test_it_can_search_books_by_title_query(): void
    {
        Book::create([
            'title' => 'Clean Code Principles',
            'author' => 'Robert Martin',
            'isbn' => '9780132350884',
            'genre' => 'Software',
            'total_copies' => 5,
            'available_copies' => 5,
        ]);

        Book::create([
            'title' => 'Design Patterns',
            'author' => 'Erich Gamma',
            'isbn' => '9780201633610',
            'genre' => 'Software',
            'total_copies' => 3,
            'available_copies' => 3,
        ]);

        $results = $this->engine->searchByQuery('Clean');
        $this->assertEquals(1, $results->total());
        $this->assertEquals('Clean Code Principles', $results->first()->title);
    }
}
