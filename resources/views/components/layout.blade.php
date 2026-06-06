<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Barangay Information System' }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --primary: #667eea;
            --secondary: #764ba2;
            --success: #48bb78;
            --danger: #f56565;
            --warning: #ed8936;
            --info: #4299e1;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7fafc;
        }

        .sidebar {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            min-height: 100vh;
            color: white;
            padding: 0;
        }

        .sidebar .logo {
            padding: 2rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 1rem 1.5rem;
            border-left: 3px solid transparent;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
            border-left-color: white;
        }

        .topbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .main-content {
            padding: 2rem;
        }

        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background: var(--secondary);
            border-color: var(--secondary);
        }

        .badge {
            padding: 0.5rem 0.75rem;
            font-weight: 500;
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="d-flex">
        @auth
            <nav class="sidebar d-flex flex-column" style="width: 250px;">
                <div class="logo">
                    <h5 class="mb-0">
                        <i class="bi bi-building"></i> BIS
                    </h5>
                    <small>Barangay System</small>
                </div>

                <ul class="nav flex-column flex-grow-1">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                           href="{{ route('dashboard') }}">
                            <i class="bi bi-house-door"></i> Dashboard
                        </a>
                    </li>

                    @if(auth()->user()->isResident())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('clearances.index') || request()->routeIs('clearances.create') || request()->routeIs('clearances.show') ? 'active' : '' }}"
                               href="{{ route('clearances.index') }}">
                                <i class="bi bi-file-text"></i> My Clearances
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('announcements.*') ? 'active' : '' }}"
                               href="{{ route('announcements.index') }}">
                                <i class="bi bi-megaphone"></i> Announcements
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('residents.*') ? 'active' : '' }}"
                               href="{{ route('residents.index') }}">
                                <i class="bi bi-people"></i> Residents
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('households.*') ? 'active' : '' }}"
                               href="{{ route('households.index') }}">
                                <i class="bi bi-houses"></i> Households
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('clearances.admin') ? 'active' : '' }}"
                               href="{{ route('clearances.admin') }}">
                                <i class="bi bi-file-check"></i> Clearances
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('blotters.*') ? 'active' : '' }}"
                               href="{{ route('blotters.index') }}">
                                <i class="bi bi-journal"></i> Blotters
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"
                               href="{{ route('reports.index') }}">
                                <i class="bi bi-bar-chart"></i> Reports
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('announcements.*') ? 'active' : '' }}"
                               href="{{ route('announcements.index') }}">
                                <i class="bi bi-megaphone"></i> Announcements
                            </a>
                        </li>
                    @endif
                </ul>

                <div class="border-top p-3">
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf

                        <button type="submit" class="nav-link btn btn-link text-white text-start w-100">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </div>
            </nav>
        @endauth

        <div class="flex-grow-1">
            <div class="topbar">
                <h6 class="mb-0">
                    {{ $title ?? 'Barangay Information System' }}
                </h6>

                @auth
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary text-capitalize">
                            {{ auth()->user()->role }}
                        </span>

                        <span>
                            {{ auth()->user()->name }}
                        </span>
                    </div>
                @endauth
            </div>

            <div class="main-content">
                @if (! empty($header))
                    <div class="mb-4">
                        {{ $header }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong>

                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="bi bi-info-circle"></i> {{ session('info') }}

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{ $slot }}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>