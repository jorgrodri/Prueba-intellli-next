<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Author;
use App\Book;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthorBookTest extends TestCase
{
    use RefreshDatabase;

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();

        // Create user and get token for all tests
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->token = JWTAuth::fromUser($user);
    }

    /** @test */
    public function it_can_create_an_author()
    {
        $response = $this->postJson('/api/authors', [
            'name' => 'Gabriel',
            'last_name' => 'Garcia Marquez',
        ], ['Authorization' => "Bearer {$this->token}"]);

        $response->assertStatus(201)
            ->assertJson(['name' => 'Gabriel']);

        $this->assertDatabaseHas('authors', ['last_name' => 'Garcia Marquez']);
    }

    /** @test */
    public function it_updates_book_count_when_book_is_created()
    {
        // 1. Create Author
        $author = Author::create([
            'name' => 'J.K.',
            'last_name' => 'Rowling',
            'books_count' => 0
        ]);

        // 2. Create Book for this Author via API
        $response = $this->postJson('/api/books', [
            'title' => 'Harry Potter',
            'description' => 'Fantasy novel',
            'publish_date' => '1997-06-26',
            'author_id' => $author->id
        ], ['Authorization' => "Bearer {$this->token}"]);

        $response->assertStatus(201);

        // 3. Verify Author's book_count was updated by the Job
        // Re-fetch author from DB
        $author->refresh();

        $this->assertEquals(1, $author->books_count, 'Book count should be 1 after creating a book');

        // Create another book
        $this->postJson('/api/books', [
            'title' => 'Harry Potter 2',
            'publish_date' => '1998-07-02',
            'author_id' => $author->id
        ], ['Authorization' => "Bearer {$this->token}"]);

        $author->refresh();
        $this->assertEquals(2, $author->books_count, 'Book count should be 2 after creating another book');
    }

    /** @test */
    public function it_validates_book_creation()
    {
        // We expect validation error, so enable exception handling for this test
        $this->withExceptionHandling();

        $response = $this->postJson('/api/books', [
            'title' => '', // Empty title
        ], ['Authorization' => "Bearer {$this->token}"]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'publish_date', 'author_id']);
    }
}
