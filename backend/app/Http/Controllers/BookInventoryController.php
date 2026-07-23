<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCopy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookInventoryController extends Controller
{
    public function index(): JsonResponse
    {
        $books = Book::with('copies')->latest()->paginate(15);
        return response()->json($books);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'isbn' => 'required|string|unique:books',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'genre' => 'required|string|max:100',
            'description' => 'nullable|string',
            'cover_image_path' => 'nullable|string',
            'file_path' => 'nullable|string',
            'publication_year' => 'nullable|integer',
            'initial_copies' => 'nullable|integer|min:1',
        ]);

        $initialCopiesCount = $validated['initial_copies'] ?? 1;

        $book = Book::create([
            'isbn' => $validated['isbn'],
            'title' => $validated['title'],
            'author' => $validated['author'],
            'publisher' => $validated['publisher'] ?? null,
            'genre' => $validated['genre'],
            'description' => $validated['description'] ?? null,
            'cover_image_path' => $validated['cover_image_path'] ?? null,
            'file_path' => $validated['file_path'] ?? null,
            'publication_year' => $validated['publication_year'] ?? null,
            'total_copies' => $initialCopiesCount,
            'available_copies' => $initialCopiesCount,
        ]);

        for ($i = 1; $i <= $initialCopiesCount; $i++) {
            BookCopy::create([
                'book_id' => $book->id,
                'barcode' => 'BC-' . strtoupper($book->isbn) . '-' . str_pad((string)$i, 3, '0', STR_PAD_LEFT),
                'condition' => 'good',
                'status' => 'available',
                'location_rack' => 'Rack-' . rand(1, 10),
            ]);
        }

        return response()->json(['message' => 'Book and copies created successfully', 'book' => $book->load('copies')], 201);
    }

    public function show(Book $book): JsonResponse
    {
        return response()->json($book->load('copies', 'reservations'));
    }

    public function update(Request $request, Book $book): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'author' => 'sometimes|string|max:255',
            'genre' => 'sometimes|string|max:100',
            'description' => 'sometimes|string',
            'is_blocked' => 'sometimes|boolean',
        ]);

        $book->update($validated);

        return response()->json(['message' => 'Book updated successfully', 'book' => $book]);
    }

    public function destroy(Book $book): JsonResponse
    {
        $book->delete();
        return response()->json(['message' => 'Book removed from catalog']);
    }
}
