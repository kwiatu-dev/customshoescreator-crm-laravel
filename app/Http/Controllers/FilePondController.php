<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Dotenv\Exception\ValidationException;
use Illuminate\Support\Facades\Validator;

class FilePondController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'images' => 'required|mimes:jpg,png,jpeg|max:8000'
        ]);

        if(!$validator->fails()){
            $image = $request->file('images');
            $dir = uniqid('filepond-');
            $name = $image->hashName();
            $image->storeAs('tmp/'. $dir, $name, 'private');

            return response($dir, 200)->header('Content-Type', 'text/plain');
        }

        return response()->json($validator->errors(), 422);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $dir)
    {
        $disk = Storage::disk('private');
        $path = 'tmp/'. $dir;

        if($disk->exists($path)){
            $disk->deleteDirectory($path);

            return response(null, 200);
        }

        return response(null, 404);
    }
}
