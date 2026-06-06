<x-layout title="Dashboard">
    <div class="row">
        <div class="col-md-6 mb-4">
            <x-card title="My Clearances" subtitle="Requests you've submitted">
                <p class="text-muted">View and track your clearance requests.</p>
                <a href="{{ route('clearances.index') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-arrow-right"></i> View Clearances
                </a>
            </x-card>
        </div>
        <div class="col-md-6 mb-4">
            <x-card title="Announcements" subtitle="Latest news">
                <p class="text-muted">Stay updated with barangay announcements.</p>
                <a href="{{ route('announcements.index') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-arrow-right"></i> View Announcements
                </a>
            </x-card>
        </div>
    </div>
    
    <x-card title="Quick Actions">
        <div class="d-grid gap-2">
            <a href="{{ route('clearances.create') }}" class="btn btn-outline-primary">
                <i class="bi bi-plus-lg"></i> Request New Clearance
            </a>
        </div>
    </x-card>
</x-layout>
