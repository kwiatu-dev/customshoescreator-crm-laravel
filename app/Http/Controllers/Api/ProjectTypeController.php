<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProjectType;
use Illuminate\Http\Request;

class ProjectTypeController extends Controller
{
    public function find(Request $request)
    {
        $request->validate(['name' => 'required|string']);

        $type = ProjectType::where('name', $request->name)
            ->first(['id', 'name']);

        if (!$type) {
             return response()->json(['message' => 'Type not found'], 404);
        }

        return response()->json($type);
    }
}
