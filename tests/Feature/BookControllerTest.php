<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    /** @test  */
    public function itReturnsAllBooks()
    {
        Book::factory()->count(30)->create();
        
        $response = $this->get('api/v1/books');
        $response->assertOk()
            ->assertJsonStructure(['status_code', 'status', 'data']);
    }

    /** @test  */
    public function itSearchesBooksByNameBooks()
    {
        $book = Book::factory()->create();
        Book::factory()->count(30)->create();
        
        $response = $this->get('api/v1/books?name='.$book->name);
        $response->assertOk();
    }

    /** @test  */
    public function itSearchesBooksByYearBooks()
    {
        $book = Book::factory()->create();
        Book::factory()->count(20)->create();
        $year = date('Y', strtotime($book->release_date));
        
        $response = $this->get('api/v1/books?release_date='.$year);
        $response->assertOk();
        $response->dump();
    }

    /** @test  */
    public function itCreateABook()
    {
        $response = $this->postJson('api/v1/books', Book::factory()->raw());
        $response->assertStatus(201);
    }

    /** @test  */
    public function itUpdateABook()
    {
        $book = Book::factory()->create();
        $response = $this->patchJson('api/v1/books/'.$book->id, [
            'name' => 'The first updated book'
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('books', [
            'name' => "The first updated book"
        ]);
    }

    /** @test  */
    public function itDeleteABook()
    {
        $book = Book::factory()->create();
        $response = $this->deleteJson('api/v1/books/'.$book->id);
        $response->assertStatus(204);
        $this->assertModelMissing($book);
    }

    /** @test  */
    public function itDisplayASingleBook()
    {
        $book = Book::factory()->create();
        $response = $this->get('api/v1/books/'.$book->id);
        $response->assertStatus(200);
    }
}
