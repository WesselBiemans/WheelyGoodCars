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

        $countScope = $request->query('count_scope', 'all');

        if (! in_array($countScope, ['all', 'exclude_unsold', 'exclude_sold'], true)) {
            $countScope = 'all';
        }

        $tags = Tag::query()
            ->withCount([
                'cars as cars_count' => function ($query) use ($countScope) {
                    if ($countScope === 'exclude_unsold') {
                        $query->whereNotNull('sold_at');
                    }

                    if ($countScope === 'exclude_sold') {
                        $query->whereNull('sold_at');
                    }
                },
            ])
            ->orderByDesc('cars_count')
            ->orderBy('name')
            ->get();

        return view('admin.tags.index', [
            'tags' => $tags,
            'countScope' => $countScope,
        ]);
    }
}
