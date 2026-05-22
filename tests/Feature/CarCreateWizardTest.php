<?php

use App\Models\Car;
use App\Models\User;

it('renders the car creation wizard', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $response = $this->actingAs($user)->get(route('cars.create'));

    $response->assertOk();
    $response->assertSee('Kenteken');
    $response->assertSee('Kenteken Controleren');
    $response->assertSee('Auto Toevoegen');
});

it('checks a license plate through the shared rule', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $response = $this->actingAs($user)->getJson(route('cars.check-license-plate', [
        'license_plate' => 'ab-12-cd',
    ]));

    $response->assertOk();
    $response->assertJson([
        'license_plate' => 'AB-12-CD',
        'message' => 'Kenteken is geldig.',
    ]);
});

it('rejects an invalid license plate through the shared rule', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $response = $this->actingAs($user)->getJson(route('cars.check-license-plate', [
        'license_plate' => 'not-valid',
    ]));

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors('license_plate');
});

it('redirects to the cars overview after creating a car', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'store-test@example.com',
        'password' => 'password',
    ]);

    $response = $this->actingAs($user)->post(route('cars.store'), [
        'license_plate' => 'ab12cd',
        'brand' => 'Toyota',
        'model' => 'Yaris',
        'price' => 8500,
        'mileage' => 120000,
    ]);

    $response->assertRedirect(route('cars.index'));
    $response->assertSessionHas('success', 'Auto succesvol toegevoegd!');

    expect(Car::count())->toBe(1);
});

it('shows my cars link only to logged in users', function () {
    $guestResponse = $this->get(route('home'));

    $guestResponse->assertOk();
    $guestResponse->assertDontSee('My Cars');

    $user = User::create([
        'name' => 'Nav User',
        'email' => 'nav-user@example.com',
        'password' => 'password',
    ]);

    $authResponse = $this->actingAs($user)->get(route('home'));

    $authResponse->assertOk();
    $authResponse->assertSee('My Cars');
    $authResponse->assertSee(route('cars.my-cars'), false);
});

it('shows only the authenticated users cars on the my cars page', function () {
    $owner = User::create([
        'name' => 'Owner User',
        'email' => 'owner-user@example.com',
        'password' => 'password',
    ]);

    $otherUser = User::create([
        'name' => 'Other User',
        'email' => 'other-user@example.com',
        'password' => 'password',
    ]);

    Car::create([
        'user_id' => $owner->id,
        'license_plate' => 'AB-12-CD',
        'brand' => 'Toyota',
        'model' => 'Yaris',
        'price' => 8500,
        'mileage' => 120000,
    ]);

    Car::create([
        'user_id' => $otherUser->id,
        'license_plate' => 'EF-34-GH',
        'brand' => 'Honda',
        'model' => 'Civic',
        'price' => 9200,
        'mileage' => 98000,
    ]);

    $response = $this->actingAs($owner)->get(route('cars.my-cars'));

    $response->assertOk();
    $response->assertSee('Toyota Yaris');
    $response->assertSee('AB-12-CD');
    $response->assertDontSee('Honda Civic');
    $response->assertDontSee('EF-34-GH');
});

it('allows guests to view the full car overview', function () {
    $owner = User::create([
        'name' => 'Guest Overview User',
        'email' => 'guest-overview@example.com',
        'password' => 'password',
    ]);

    Car::create([
        'user_id' => $owner->id,
        'license_plate' => 'ZZ-99-ZZ',
        'brand' => 'Volkswagen',
        'model' => 'Golf',
        'price' => 12950,
        'mileage' => 110000,
    ]);

    $response = $this->get(route('cars.index'));

    $response->assertOk();
    $response->assertSee('Volkswagen Golf');
    $response->assertSee('ZZ-99-ZZ');
});

it('increments the car view counter when the view button is clicked', function () {
    $owner = User::create([
        'name' => 'View Counter User',
        'email' => 'view-counter@example.com',
        'password' => 'password',
    ]);

    $car = Car::create([
        'user_id' => $owner->id,
        'license_plate' => 'AA-11-BB',
        'brand' => 'Audi',
        'model' => 'A3',
        'price' => 14950,
        'mileage' => 89000,
        'views' => 4,
    ]);

    $response = $this->post(route('cars.views.increment', $car));

    $response->assertNoContent();
    expect($car->refresh()->views)->toBe(5);
});

it('shows the view count on the public car overview and my cars page', function () {
    $owner = User::create([
        'name' => 'Views Display User',
        'email' => 'views-display@example.com',
        'password' => 'password',
    ]);

    $car = Car::create([
        'user_id' => $owner->id,
        'license_plate' => 'CC-22-DD',
        'brand' => 'BMW',
        'model' => '1 Series',
        'price' => 16950,
        'mileage' => 76000,
        'views' => 12,
    ]);

    $publicResponse = $this->get(route('cars.index'));
    $publicResponse->assertOk();
    $publicResponse->assertSee('12 weergaven');

    $authResponse = $this->actingAs($owner)->get(route('cars.my-cars'));
    $authResponse->assertOk();
    $authResponse->assertSee('12 weergaven');
    $authResponse->assertSee('BMW 1 Series');
});