<?php

namespace App\Services;

use App\Models\Book;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAiRecommendationService
{
    protected string $apiKey;
    protected string $model;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key', 'OPENAI_API_KEY_PLACEHOLDER');
        $this->model = config('services.openai.model', 'gpt-4o-mini');
    }

    public function generateRecommendation(ChatSession $session, string $userPrompt): array
    {
        // 1. Fetch available books context from DB
        $booksContext = Book::where('is_blocked', false)
            ->limit(15)
            ->get(['title', 'author', 'genre', 'description', 'isbn'])
            ->toArray();

        $contextText = "Available Library Inventory:\n" . json_encode($booksContext, JSON_PRETTY_PRINT);
        $systemPrompt = "You are SmartLib AI, an expert digital library librarian. Recommend relevant books from the inventory context below based on user inquiries.\n\n" . $contextText;

        // Save user message
        ChatMessage::create([
            'chat_session_id' => $session->id,
            'sender' => 'user',
            'message' => $userPrompt,
            'tokens_used' => (int)(strlen($userPrompt) / 4),
        ]);

        $aiResponseText = "";
        $tokensConsumed = 0;

        if ($this->apiKey !== 'OPENAI_API_KEY_PLACEHOLDER') {
            try {
                $response = Http::withHeaders([
                    'Authorization' => "Bearer {$this->apiKey}",
                    'Content-Type' => 'application/json',
                ])->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $this->model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userPrompt],
                    ],
                    'max_tokens' => 500,
                ]);

                $json = $response->json();
                $aiResponseText = $json['choices'][0]['message']['content'] ?? "Here are some books from our catalog that match your query.";
                $tokensConsumed = $json['usage']['total_tokens'] ?? 150;
            } catch (\Throwable $e) {
                Log::error('OpenAI Recommendation Call Failed', ['error' => $e->getMessage()]);
                $aiResponseText = "I found several great recommendations in our catalog: 'The Great Gatsby' and 'Clean Code'.";
                $tokensConsumed = 85;
            }
        } else {
            // Intelligent fallback for demonstration
            $matchingBooks = Book::where('genre', 'like', "%{$userPrompt}%")
                ->orWhere('title', 'like', "%{$userPrompt}%")
                ->orWhere('author', 'like', "%{$userPrompt}%")
                ->limit(3)
                ->get();

            if ($matchingBooks->count() > 0) {
                $titles = $matchingBooks->pluck('title')->implode(', ');
                $aiResponseText = "Based on your request, I highly recommend checking out: **{$titles}** from our library catalog!";
            } else {
                $aiResponseText = "Welcome! I recommend checking out our top titles like **Clean Code** by Robert C. Martin and **The Pragmatic Programmer**.";
            }
            $tokensConsumed = 120;
        }

        // Save AI message
        $aiMessage = ChatMessage::create([
            'chat_session_id' => $session->id,
            'sender' => 'ai',
            'message' => $aiResponseText,
            'tokens_used' => $tokensConsumed,
        ]);

        $session->increment('total_tokens_used', $tokensConsumed);

        return [
            'message' => $aiMessage,
            'tokens_used' => $tokensConsumed,
        ];
    }
}
