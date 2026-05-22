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