<?php

use App\Models\Car;
use App\Models\Tag;
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

it('filters the public car overview by selected tags', function () {
    $owner = User::create([
        'name' => 'Tag Filter User',
        'email' => 'tag-filter@example.com',
        'password' => 'password',
    ]);

    $electric = Tag::create([
        'name' => 'Electric',
        'color' => '#0EA5E9',
    ]);

    $diesel = Tag::create([
        'name' => 'Diesel',
        'color' => '#64748B',
    ]);

    $electricCar = Car::create([
        'user_id' => $owner->id,
        'license_plate' => 'EE-11-EE',
        'brand' => 'Tesla',
        'model' => 'Model 3',
        'price' => 27950,
        'mileage' => 42000,
    ]);
    $electricCar->tags()->attach($electric->id);

    $bothCar = Car::create([
        'user_id' => $owner->id,
        'license_plate' => 'BB-33-BB',
        'brand' => 'Hyundai',
        'model' => 'Ioniq 5',
        'price' => 32950,
        'mileage' => 18000,
    ]);
    $bothCar->tags()->attach([$electric->id, $diesel->id]);

    $dieselCar = Car::create([
        'user_id' => $owner->id,
        'license_plate' => 'DD-22-DD',
        'brand' => 'BMW',
        'model' => '320d',
        'price' => 18950,
        'mileage' => 89000,
    ]);
    $dieselCar->tags()->attach($diesel->id);

    $response = $this->get(route('cars.index', ['tags' => [$electric->id, $diesel->id]]));

    $response->assertOk();
    $response->assertSee('Hyundai Ioniq 5');
    $response->assertDontSee('BMW 320d');
    $response->assertDontSee('Tesla Model 3');
});

it('filters my cars by selected tags', function () {
    $owner = User::create([
        'name' => 'My Cars Tag User',
        'email' => 'my-cars-tag@example.com',
        'password' => 'password',
    ]);

    $sport = Tag::create([
        'name' => 'Sport',
        'color' => '#EF4444',
    ]);

    $family = Tag::create([
        'name' => 'Family',
        'color' => '#22C55E',
    ]);

    $sportCar = Car::create([
        'user_id' => $owner->id,
        'license_plate' => 'SS-33-SS',
        'brand' => 'Porsche',
        'model' => 'Cayman',
        'price' => 45950,
        'mileage' => 51000,
    ]);
    $sportCar->tags()->attach($sport->id);

    $bothCar = Car::create([
        'user_id' => $owner->id,
        'license_plate' => 'BB-55-BB',
        'brand' => 'Audi',
        'model' => 'A5 Sportback',
        'price' => 37950,
        'mileage' => 64000,
    ]);
    $bothCar->tags()->attach([$sport->id, $family->id]);

    $familyCar = Car::create([
        'user_id' => $owner->id,
        'license_plate' => 'FF-44-FF',
        'brand' => 'Volkswagen',
        'model' => 'Touran',
        'price' => 16950,
        'mileage' => 102000,
    ]);
    $familyCar->tags()->attach($family->id);

    $response = $this->actingAs($owner)->get(route('cars.my-cars', ['tags' => [$sport->id, $family->id]]));

    $response->assertOk();
    $response->assertSee('Audi A5 Sportback');
    $response->assertDontSee('Volkswagen Touran');
    $response->assertDontSee('Porsche Cayman');
});