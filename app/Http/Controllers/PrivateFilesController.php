<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PrivateFilesController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $catalog, string $file)
    {
        abort_if(
            !Storage::disk('private') ->exists("$catalog/$file"),
            404,
            "The file doesn't exist"
        );

        return Storage::disk('private')->response("$catalog/$file");
    }
}
