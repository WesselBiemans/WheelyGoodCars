<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Tag;
use App\Rules\LicensePlate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarController extends Controller
{
    /**
     * Display a listing of cars for sale.
     */
    public function index()
    {
        $cars = Car::with(['user', 'tags'])
            ->whereNull('sold_at')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('cars.index', compact('cars'));
    }

    /**
     * Show the form for creating a new car.
     */
    public function create()
    {
        $tags = Tag::all();
        return view('cars.create', compact('tags'));
    }

    /**
     * Store a newly created car in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'license_plate' => ['required', 'string', 'max:255', new LicensePlate],
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'mileage' => 'required|integer|min:0',
            'seats' => 'nullable|integer|min:1',
            'doors' => 'nullable|integer|min:1',
            'production_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'weight' => 'nullable|integer|min:0',
            'color' => 'nullable|string|max:255',
            'image' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        // Normalize license plate format (remove spaces/dashes, convert to uppercase, then add standard dashes)
        $normalized = strtoupper(str_replace([' ', '-'], '', $validated['license_plate']));

        // Add dashes in the standard format (XX-XX-XX pattern based on length and type)
        if (strlen($normalized) === 6) {
            $validated['license_plate'] = substr($normalized, 0, 2) . '-' . substr($normalized, 2, 2) . '-' . substr($normalized, 4, 2);
        }

        // Add the authenticated user's ID
        $validated['user_id'] = Auth::id();

        // Create the car
        $car = Car::create($validated);

        // Attach tags if provided
        if ($request->has('tags')) {
            $car->tags()->attach($request->tags);
        }

        return redirect()->route('cars.create')->with('success', 'Auto succesvol toegevoegd!');
    }
}
