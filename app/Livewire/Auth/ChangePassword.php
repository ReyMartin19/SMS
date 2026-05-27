<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ChangePassword extends Component
{
    public $password = '';
    public $password_confirmation = '';

    public function rules()
    {
        return [
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function save()
    {
        $this->validate();

        $user = auth()->user();
        $user->update([
            'password' => Hash::make($this->password),
            'force_password_change' => false,
        ]);

        session()->flash('success', 'Your password has been changed successfully.');

        $role = $user->role;
        $redirectUrl = match ($role) {
            'superadmin', 'admin' => route('admin.dashboard'),
            'teacher' => route('teacher.dashboard'),
            'student' => route('student.dashboard'),
            'parent' => route('parent.dashboard'),
            default => route('home'),
        };

        return redirect()->to($redirectUrl);
    }

    public function render()
    {
        return view('livewire.auth.change-password')
            ->layout('layouts.auth');
    }
}
