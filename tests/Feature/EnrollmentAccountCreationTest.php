<?php

use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('auto account creation middleware redirects if force_password_change is true', function () {
    $user = User::create([
        'name' => 'Test Student',
        'email' => 'student@school.com',
        'password' => bcrypt('password123'),
        'role' => 'student',
        'force_password_change' => true,
    ]);

    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('password.change'));
});

test('change password page updates password and sets force_password_change to false', function () {
    $user = User::create([
        'name' => 'Test Student',
        'email' => 'student@school.com',
        'password' => bcrypt('password123'),
        'role' => 'student',
        'force_password_change' => true,
    ]);

    $this->actingAs($user);

    Livewire::test(\App\Livewire\Auth\ChangePassword::class)
        ->set('password', 'newpassword123')
        ->set('password_confirmation', 'newpassword123')
        ->call('save')
        ->assertRedirect(route('student.dashboard'));

    expect($user->fresh()->force_password_change)->toBeFalse();
    expect(Hash::check('newpassword123', $user->fresh()->password))->toBeTrue();
});
