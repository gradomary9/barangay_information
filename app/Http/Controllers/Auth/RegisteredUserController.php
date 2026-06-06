<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email', 'unique:residents,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'resident',
            ]);

            $nameParts = preg_split('/\s+/', trim($request->name));

            $firstName = $nameParts[0] ?? 'Resident';
            $lastName = count($nameParts) > 1 ? end($nameParts) : 'User';

            $middleName = null;

            if (count($nameParts) > 2) {
                $middleParts = array_slice($nameParts, 1, -1);
                $middleName = implode(' ', $middleParts);
            }

            Resident::create([
                'user_id' => $user->id,
                'email' => $request->email,
                'household_id' => null,
                'first_name' => $firstName,
                'middle_name' => $middleName,
                'last_name' => $lastName,

                // Default values because your residents table requires these fields.
                // Admin can edit these later in Resident Management.
                'birth_date' => now()->toDateString(),
                'gender' => 'other',
                'contact_number' => 'Not provided',
                'address' => 'Not provided',
            ]);

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}