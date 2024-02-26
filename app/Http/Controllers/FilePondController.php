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
            'images' => 'required|mimes:jpg,png,jpeg|max:5000'
        ]);

        if($request->file('images')->getClientOriginalName()){
            $validator->setAttributeNames(['images' => $request->file('images')->getClientOriginalName()]);
        }

        if(!$validator->fails()){
            $folder = 'tmp';
            $imageFile = $request->file('images');
            $subfolder = uniqid('filepond-');
            $imageName = $imageFile->hashName();
            $path = $imageFile->storeAs($folder .'/'. $subfolder, $imageName, 'private');

            return response()->json([
                'folder' => $folder, 
                'subfolder' => $subfolder, 
                'path' => dirname($path),
                'name' => $imageName
            ], 200);
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
    public function destroy(Request $request, string $subfolder)
    {
        $disk = Storage::disk('private');
        $folder = $request->string('folder');
        $path = $folder .'/'. $subfolder;

        if($disk->exists($path)){
            $disk->deleteDirectory($path);

            return response()->json([], 200);
        }

        return response()->json([], 422);
    }
}
