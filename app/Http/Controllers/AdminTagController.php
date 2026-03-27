<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class AdminTagController extends Controller
{
    /**
     * Display tags with their usage count.
     */
    public function index(Request $request)
    {
        abort_unless($request->user()?->isAdmin(), 403);

        $tags = Tag::query()
            ->withCount('cars')
            ->orderByDesc('cars_count')
            ->orderBy('name')
            ->get();

        return view('admin.tags.index', [
            'tags' => $tags,
        ]);
    }
}
