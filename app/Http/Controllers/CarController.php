<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Tag;
use App\Rules\LicensePlate;
use Illuminate\Http\JsonResponse;
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
     * Display a listing of cars belonging to the authenticated user.
     */
    public function myCars()
    {
        $cars = Car::with(['user', 'tags'])
            ->whereNull('sold_at')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('cars.my-cars', compact('cars'));
    }

    /**
     * Increment the view counter for a car.
     */
    public function incrementViews(Car $car)
    {
        $car->increment('views');

        return response()->noContent();
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
     * Check a license plate using the shared validation rule.
     */
    public function checkLicensePlate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'license_plate' => ['required', 'string', 'max:255', new LicensePlate],
        ]);

        $normalized = $this->normalizeLicensePlate($validated['license_plate']);

        return response()->json([
            'license_plate' => $this->formatLicensePlate($normalized),
            'message' => 'Kenteken is geldig.',
        ]);
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

        $validated['license_plate'] = $this->formatLicensePlate($validated['license_plate']);

        // Add the authenticated user's ID
        $validated['user_id'] = Auth::id();

        // Create the car
        $car = Car::create($validated);

        // Attach tags if provided
        if ($request->has('tags')) {
            $car->tags()->attach($request->tags);
        }

        return redirect()->route('cars.index')->with('success', 'Auto succesvol toegevoegd!');
    }

    private function normalizeLicensePlate(string $value): string
    {
        return strtoupper(str_replace([' ', '-'], '', $value));
    }

    private function formatLicensePlate(string $value): string
    {
        $normalized = $this->normalizeLicensePlate($value);

        if (strlen($normalized) !== 6) {
            return $normalized;
        }

        return substr($normalized, 0, 2) . '-' . substr($normalized, 2, 2) . '-' . substr($normalized, 4, 2);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $car = Car::findOrFail($id);

        // Check if the authenticated user owns the car
        if ($car->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $car->delete();

        return redirect()->route("cars.index")->with('success', 'Auto verwijderd!');
    }
}
