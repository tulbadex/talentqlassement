<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::query()
                ->when(request('name'), 
                    fn($builder) => $builder->where('name', request('name')),
                    fn($builder) => $builder
                )
                ->when(request('company'), 
                    fn($builder) => $builder->where('company', request('company')),
                    fn($builder) => $builder
                )
                ->when(request('publisher'), 
                    fn($builder) => $builder->where('publisher', request('publisher')),
                    fn($builder) => $builder
                )
                ->when(request('release_date'), 
                    fn($builder) => $builder->where('release_date', request('release_date')),
                    fn($builder) => $builder
                )
                ->orderBy('id', 'desc')->paginate(20);
        return response()->json([
            'status_code' => 200,
            'status' => 'success',
            "data" => BookResource::collection($books)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBookRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        
        /* $book = Book::create([
            "name" => request('name'),
            "isbn" => request('isbn'),
            "authors" => request('authors'),
            "number_of_pages" => request('number_of_pages'),
            "publisher" => request('publisher'),
            "country" => request('country'),
            "release_date" => request('release_date')
        ]); */

        $book = Book::create(request()->all());

        return response()->json([
            'status_code' => 201,
            'status' => "success",
            'data' => BookResource::make($book)
        ], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        return response()->json([
            'status_code' => 200,
            'status' => 'success',
            'data' => BookResource::make($book)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBookRequest  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        $book->update($request->all());
        return response()->json([
            'status_code' => 200,
            'status' => 'success',
            'message' => $book->name.' updated successfully',
            'data' => BookResource::make($book)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        $book_name = $book->name;
        $book->delete();
        return response()->json([
            'status_code' => 204,
            'status' => 'success',
            'message' => "The book '".$book_name."' was deleted successfully",
            'data' => BookResource::make($book)
        ], 204);
    }

    public function getDataFromIceAndFireAPI()
    {
        $data = Http::get("https://www.anapioficeandfire.com/api/books?name=".request('name'))->json();
  
        if ($data) {
            return response()->json([
                'status_code' => 200,
                'status' => 'successfully',
                'data' => BookResource::collection($data)
            ]);
        }
        return response()->json([
            'status_code' => 404,
            'status' => 'not found',
            'data' => BookResource::collection($data)
        ], 404);
    }
}
