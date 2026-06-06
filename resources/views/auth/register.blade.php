<x-guest-layout>
    <style>
        .auth-card {
            width: 100%;
            max-width: 460px;
            background: #ffffff;
            border-radius: 22px;
            padding: 34px;
            box-shadow: 0 25px 60px rgba(15, 23, 42, 0.25);
            border: 1px solid #e5e7eb;
            transition: 0.25s ease;
        }

        .auth-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 32px 75px rgba(15, 23, 42, 0.32);
        }

        .auth-title {
            text-align: center;
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .auth-subtitle {
            text-align: center;
            font-size: 14px;
            color: #64748b;
            margin-bottom: 26px;
        }

        .form-group {
            margin-bottom: 17px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 7px;
        }

        .form-input,
        .form-select {
            width: 100%;
            height: 45px;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 15px;
            color: #0f172a;
            outline: none;
            background: #ffffff;
        }

        .form-input:focus,
        .form-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
        }

        .auth-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 24px;
        }

        .auth-link {
            font-size: 14px;
            font-weight: 600;
            color: #2563eb;
            text-decoration: none;
        }

        .auth-link:hover {
            text-decoration: underline;
        }

        .auth-btn {
            background: #1e3a8a;
            color: white;
            border: none;
            padding: 12px 22px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .auth-btn:hover {
            background: #172554;
        }

        .error-text {
            color: #dc2626;
            font-size: 13px;
            margin-top: 6px;
        }

        @media (max-width: 480px) {
            .auth-card {
                padding: 26px 22px;
            }

            .auth-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .auth-btn {
                width: 100%;
            }

            .auth-link {
                text-align: center;
            }
        }
    </style>

    <div class="auth-card">
        <h1 class="auth-title">Create Resident Account</h1>
        <p class="auth-subtitle">Register to request clearances and view barangay announcements</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Full Name</label>
                <input
                    id="name"
                    class="form-input"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Enter your full name"
                >

                @error('name')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input
                    id="email"
                    class="form-input"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="username"
                    placeholder="Enter your email"
                >

                @error('email')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input
                    id="password"
                    class="form-input"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="Enter your password"
                >

                @error('password')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input
                    id="password_confirmation"
                    class="form-input"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Confirm your password"
                >

                @error('password_confirmation')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="role" class="form-label">Register as</label>
                <select id="role" name="role" class="form-select" required>
                    <option value="resident">Resident</option>
                </select>

                @error('role')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="auth-actions">
                <a class="auth-link" href="{{ route('login') }}">
                    Already registered?
                </a>

                <button type="submit" class="auth-btn">
                    Register
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>