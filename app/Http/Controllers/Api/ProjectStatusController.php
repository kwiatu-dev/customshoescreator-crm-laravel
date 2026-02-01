<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProjectStatus;
use Illuminate\Http\Request;

class ProjectStatusController extends Controller
{
    public function find(Request $request)
    {
        $request->validate(['name' => 'required|string']);

        $status = ProjectStatus::where('name', $request->name)
            ->first(['id', 'name']);

        if (!$status) {
            return response()->json(['message' => 'Status not found'], 404);
        }

        return response()->json($status);
    }
}
