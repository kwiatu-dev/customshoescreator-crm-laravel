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

    public function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'filepond' => 'required|mimes:jpg,png,jpeg|max:5000'
            ]);
    
            if(!$validator->fails()){
                $file_name = $request->file('filepond')->getClientOriginalName();
                $validator->setAttributeNames(['filepond' => $file_name]);
                $dir = 'tmp';
                $image = $request->file('filepond');
                $image->store($dir, 'private');
                $name = $image->hashName();
    
                return response($name, 200)->header('Content-Type', 'text/plain');
            }
        }
        catch(Exception $ex) { }
        
        return response()->json($validator->errors(), 422);
    }

    public function destroy(string $name)
    {
        $disk = Storage::disk('private');
        $dir = 'tmp';
        $path = $dir .'/'. $name;

        if($disk->exists($path)){
            $disk->delete($path);

            return response(null, 200);
        }

        try{

        }
        catch(Exception $ex){
            return response(null, 422);
        }
    }
}
