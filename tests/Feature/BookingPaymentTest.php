<?php

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated user can view booking form', function () {
    $user = User::factory()->create();
    $tour = Tour::factory()->create([
        'status' => 'active',
        'max_people' => 20,
        'start_date' => now()->addDays(7),
        'end_date' => now()->addDays(10),
    ]);

    $response = $this->actingAs($user)
        ->get(route('bookings.create', $tour));

    $response->assertStatus(200);
    $response->assertViewIs('bookings.create');
    $response->assertViewHas('tour');
});

test('guest user is redirected to login when accessing booking form', function () {
    $tour = Tour::factory()->create();

    $response = $this->get(route('bookings.create', $tour));

    $response->assertRedirect(route('login'));
});

test('cannot book inactive tour', function () {
    $user = User::factory()->create();
    $tour = Tour::factory()->create(['status' => 'inactive']);

    $response = $this->actingAs($user)
        ->get(route('bookings.create', $tour));

    $response->assertRedirect();
    $response->assertSessionHas('error');
});

test('can create booking with valid data', function () {
    $user = User::factory()->create();
    $tour = Tour::factory()->create([
        'status' => 'active',
        'max_people' => 20,
        'price_adult' => 2000000,
        'price_child' => 1000000,
        'price_infant' => 0,
        'start_date' => now()->addDays(7),
    ]);

    $bookingData = [
        'name' => 'Nguyen Van A',
        'email' => 'nguyenvana@example.com',
        'phone' => '0901234567',
        'adults' => 2,
        'children' => 1,
        'infants' => 0,
        'special_requests' => 'Yêu cầu phòng view biển',
    ];

    $response = $this->actingAs($user)
        ->post(route('bookings.store', $tour), $bookingData);

    expect(Booking::where('email', 'nguyenvana@example.com')->exists())->toBeTrue();
    
    $booking = Booking::where('email', 'nguyenvana@example.com')->first();
    expect($booking->user_id)->toBe($user->id);
    expect($booking->tour_id)->toBe($tour->id);
    expect($booking->adults)->toBe(2);
    expect($booking->children)->toBe(1);
    expect($booking->total_people)->toBe(3);
    expect($booking->status)->toBe('pending');
    expect($booking->booking_code)->not->toBeNull();
    
    $response->assertRedirect();
});

test('booking requires valid data', function () {
    $user = User::factory()->create();
    $tour = Tour::factory()->create(['status' => 'active']);

    $response = $this->actingAs($user)
        ->post(route('bookings.store', $tour), []);

    $response->assertSessionHasErrors(['name', 'email', 'phone', 'adults']);
});

test('booking requires valid email format', function () {
    $user = User::factory()->create();
    $tour = Tour::factory()->create(['status' => 'active']);

    $response = $this->actingAs($user)
        ->post(route('bookings.store', $tour), [
            'name' => 'Nguyen Van A',
            'email' => 'invalid-email',
            'phone' => '0901234567',
            'adults' => 1,
        ]);

    $response->assertSessionHasErrors(['email']);
});

test('booking requires at least one adult', function () {
    $user = User::factory()->create();
    $tour = Tour::factory()->create(['status' => 'active']);

    $response = $this->actingAs($user)
        ->post(route('bookings.store', $tour), [
            'name' => 'Nguyen Van A',
            'email' => 'test@example.com',
            'phone' => '0901234567',
            'adults' => 0,
            'children' => 2,
        ]);

    $response->assertSessionHasErrors(['adults']);
});

test('authenticated user can view payment page', function () {
    $user = User::factory()->create();
    $booking = Booking::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($user)
        ->get(route('payments.show', $booking));

    $response->assertStatus(200);
    $response->assertViewIs('payments.show');
    $response->assertViewHas('booking');
});

test('user cannot view other users payment page', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $booking = Booking::factory()->create(['user_id' => $user2->id]);

    $response = $this->actingAs($user1)
        ->get(route('payments.show', $booking));

    $response->assertStatus(403);
});

test('redirects if booking already paid', function () {
    $user = User::factory()->create();
    $booking = Booking::factory()->confirmed()->create(['user_id' => $user->id]);
    
    Payment::factory()->success()->create([
        'booking_id' => $booking->id,
        'amount' => $booking->total_amount,
    ]);

    $response = $this->actingAs($user)
        ->get(route('payments.show', $booking));

    $response->assertRedirect(route('bookings.success', $booking));
});

test('can process mock visa payment successfully', function () {
    $user = User::factory()->create();
    $tour = Tour::factory()->create([
        'price_adult' => 2000000,
        'price_child' => 1000000,
        'price_infant' => 0,
    ]);
    
    $booking = Booking::factory()->create([
        'user_id' => $user->id,
        'tour_id' => $tour->id,
        'status' => 'pending',
        'adults' => 2,
        'children' => 1,
        'infants' => 0,
        'total_amount' => 5000000,
    ]);

    $paymentData = [
        'card_number' => '4111111111111111',
        'card_name' => 'NGUYEN VAN A',
        'expiry_date' => '12/25',
        'cvv' => '123',
    ];

    $response = $this->actingAs($user)
        ->post(route('payments.process.mock', $booking), $paymentData);

    // Check payment created
    expect(Payment::where('booking_id', $booking->id)->exists())->toBeTrue();
    
    $payment = Payment::where('booking_id', $booking->id)->first();
    expect($payment->status)->toBe('success');
    expect($payment->payment_method)->toBe('mock');
    expect((float)$payment->amount)->toBe(5000000.0);
    expect($payment->transaction_id)->toStartWith('MOCK-');
    expect($payment->paid_at)->not->toBeNull();
    
    // Check transaction data
    expect($payment->transaction_data)->toBeArray();
    expect($payment->transaction_data['card_last4'])->toBe('1111');
    expect($payment->transaction_data['card_name'])->toBe('NGUYEN VAN A');
    
    // Check booking confirmed
    $booking->refresh();
    expect($booking->status)->toBe('confirmed');
    
    $response->assertRedirect(route('bookings.success', $booking));
    $response->assertSessionHas('success');
});

test('mock payment validates card number', function () {
    $user = User::factory()->create();
    $booking = Booking::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($user)
        ->post(route('payments.process.mock', $booking), [
            'card_number' => '1234', // Too short
            'card_name' => 'NGUYEN VAN A',
            'expiry_date' => '12/25',
            'cvv' => '123',
        ]);

    $response->assertSessionHasErrors(['card_number']);
});

test('mock payment validates expiry date format', function () {
    $user = User::factory()->create();
    $booking = Booking::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($user)
        ->post(route('payments.process.mock', $booking), [
            'card_number' => '4111111111111111',
            'card_name' => 'NGUYEN VAN A',
            'expiry_date' => '12/2025', // Wrong format
            'cvv' => '123',
        ]);

    $response->assertSessionHasErrors(['expiry_date']);
});

test('mock payment validates cvv', function () {
    $user = User::factory()->create();
    $booking = Booking::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($user)
        ->post(route('payments.process.mock', $booking), [
            'card_number' => '4111111111111111',
            'card_name' => 'NGUYEN VAN A',
            'expiry_date' => '12/25',
            'cvv' => '12', // Too short
        ]);

    $response->assertSessionHasErrors(['cvv']);
});

test('cannot pay twice for same booking', function () {
    $user = User::factory()->create();
    $booking = Booking::factory()->confirmed()->create(['user_id' => $user->id]);
    
    Payment::factory()->success()->create([
        'booking_id' => $booking->id,
        'amount' => $booking->total_amount,
    ]);

    $paymentData = [
        'card_number' => '4111111111111111',
        'card_name' => 'NGUYEN VAN A',
        'expiry_date' => '12/25',
        'cvv' => '123',
    ];

    $response = $this->actingAs($user)
        ->post(route('payments.process.mock', $booking), $paymentData);

    $response->assertRedirect(route('bookings.success', $booking));
    
    // Should only have one successful payment
    expect(Payment::where('booking_id', $booking->id)
        ->where('status', 'success')
        ->count())->toBe(1);
});

test('can view booking details after payment', function () {
    $user = User::factory()->create();
    $booking = Booking::factory()->confirmed()->create(['user_id' => $user->id]);
    
    Payment::factory()->success()->create([
        'booking_id' => $booking->id,
        'amount' => $booking->total_amount,
    ]);

    $response = $this->actingAs($user)
        ->get(route('bookings.show', $booking));

    $response->assertStatus(200);
    $response->assertViewIs('bookings.show');
    $response->assertSee($booking->booking_code);
});

test('can view success page after payment', function () {
    $user = User::factory()->create();
    $booking = Booking::factory()->confirmed()->create(['user_id' => $user->id]);
    
    Payment::factory()->success()->create([
        'booking_id' => $booking->id,
        'amount' => $booking->total_amount,
    ]);

    $response = $this->actingAs($user)
        ->get(route('bookings.success', $booking));

    $response->assertStatus(200);
    $response->assertViewIs('bookings.success');
    $response->assertSee($booking->booking_code);
});

test('can view booking history', function () {
    $user = User::factory()->create();
    $bookings = Booking::factory()->count(3)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)
        ->get(route('bookings.history'));

    $response->assertStatus(200);
    $response->assertViewIs('bookings.history');
    
    foreach ($bookings as $booking) {
        $response->assertSee($booking->booking_code);
    }
});

test('booking generates unique booking code', function () {
    $booking1 = Booking::factory()->create();
    $booking2 = Booking::factory()->create();

    expect($booking1->booking_code)->not->toBe($booking2->booking_code);
    expect($booking1->booking_code)->toStartWith('BK');
    expect($booking2->booking_code)->toStartWith('BK');
});

test('payment generates unique payment code', function () {
    $booking = Booking::factory()->create();
    
    $payment1 = Payment::factory()->create(['booking_id' => $booking->id]);
    $payment2 = Payment::factory()->create(['booking_id' => $booking->id]);

    expect($payment1->payment_code)->not->toBe($payment2->payment_code);
    expect($payment1->payment_code)->toStartWith('PAY');
    expect($payment2->payment_code)->toStartWith('PAY');
});

test('checks if booking can be paid', function () {
    $pendingBooking = Booking::factory()->create(['status' => 'pending']);
    
    $paidBooking = Booking::factory()->confirmed()->create();
    Payment::factory()->success()->create(['booking_id' => $paidBooking->id]);

    expect($pendingBooking->canPay())->toBeTrue();
    expect($paidBooking->canPay())->toBeFalse();
});

test('checks if booking is paid', function () {
    $unpaidBooking = Booking::factory()->create(['status' => 'pending']);
    
    $paidBooking = Booking::factory()->confirmed()->create();
    Payment::factory()->success()->create(['booking_id' => $paidBooking->id]);

    expect($unpaidBooking->isPaid())->toBeFalse();
    expect($paidBooking->isPaid())->toBeTrue();
});

test('calculates correct total amount for multiple people', function () {
    $user = User::factory()->create();
    $tour = Tour::factory()->create([
        'status' => 'active',
        'price_adult' => 2000000,
        'price_child' => 1000000,
        'price_infant' => 500000,
        'max_people' => 20,
        'start_date' => now()->addDays(7),
    ]);

    $bookingData = [
        'name' => 'Nguyen Van A',
        'email' => 'test@example.com',
        'phone' => '0901234567',
        'adults' => 3,
        'children' => 2,
        'infants' => 1,
    ];

    $this->actingAs($user)
        ->post(route('bookings.store', $tour), $bookingData);

    $booking = Booking::where('email', 'test@example.com')->first();
    
    // 3 adults * 2,000,000 + 2 children * 1,000,000 + 1 infant * 500,000 = 8,500,000
    expect((float)$booking->total_amount)->toBe(8500000.0);
    expect($booking->total_people)->toBe(6);
});

test('full booking and payment workflow', function () {
    // Step 1: Create user and tour
    $user = User::factory()->create([
        'email' => 'testuser@example.com',
    ]);
    
    $tour = Tour::factory()->create([
        'name' => 'Tour Hà Nội - Hạ Long',
        'status' => 'active',
        'max_people' => 20,
        'price_adult' => 3000000,
        'price_child' => 1500000,
        'price_infant' => 0,
        'start_date' => now()->addDays(14),
        'end_date' => now()->addDays(17),
    ]);

    // Step 2: User creates a booking
    $bookingData = [
        'name' => 'Nguyễn Văn A',
        'email' => 'customer@example.com',
        'phone' => '0901234567',
        'adults' => 2,
        'children' => 1,
        'infants' => 1,
        'special_requests' => 'Cần phòng riêng cho gia đình',
    ];

    $this->actingAs($user)
        ->post(route('bookings.store', $tour), $bookingData)
        ->assertSessionHas('success');

    $booking = Booking::where('email', 'customer@example.com')->first();
    
    expect($booking)->not->toBeNull();
    expect($booking->status)->toBe('pending');
    expect((float)$booking->total_amount)->toBe(7500000.0); // 2*3M + 1*1.5M + 1*0
    expect($booking->booking_code)->not->toBeNull();

    // Step 3: User goes to payment page
    $this->actingAs($user)
        ->get(route('payments.show', $booking))
        ->assertStatus(200)
        ->assertSee($booking->booking_code)
        ->assertSee('7,500,000');

    // Step 4: User processes payment with Visa demo card
    $visaPaymentData = [
        'card_number' => '4532015112830366', // Test Visa card
        'card_name' => 'NGUYEN VAN A',
        'expiry_date' => '12/26',
        'cvv' => '123',
    ];

    $this->actingAs($user)
        ->post(route('payments.process.mock', $booking), $visaPaymentData)
        ->assertSessionHas('success')
        ->assertRedirect(route('bookings.success', $booking));

    // Step 5: Verify payment recorded correctly
    $booking->refresh();
    expect($booking->status)->toBe('confirmed');
    expect($booking->isPaid())->toBeTrue();
    
    $payment = $booking->getSuccessfulPayment();
    expect($payment)->not->toBeNull();
    expect($payment->payment_method)->toBe('mock');
    expect($payment->status)->toBe('success');
    expect((float)$payment->amount)->toBe(7500000.0);
    expect($payment->transaction_id)->toContain('MOCK-');
    expect($payment->transaction_data['card_last4'])->toBe('0366');
    expect($payment->paid_at)->not->toBeNull();

    // Step 6: User can view booking success page
    $this->actingAs($user)
        ->get(route('bookings.success', $booking))
        ->assertStatus(200)
        ->assertSee($booking->booking_code)
        ->assertSee('Đã Thanh Toán');

    // Step 7: User can view in booking history
    $this->actingAs($user)
        ->get(route('bookings.history'))
        ->assertStatus(200)
        ->assertSee($booking->booking_code)
        ->assertSee($tour->name);
});
