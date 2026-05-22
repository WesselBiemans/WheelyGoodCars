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