<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #222; }
        h1 { font-size: 20px; margin-bottom: 4px; }
        .meta { color: #666; margin-bottom: 18px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; vertical-align: top; }
        th { background: #f1f1f1; }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <div class="meta">Generated: {{ $generatedAt->format('M d, Y h:i A') }}</div>

    <table>
        <thead>
            <tr>
                @foreach($headings as $heading)
                    <th>{{ $heading }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
                <tr>
                    @foreach($keys as $key)
                        <td>{{ data_get($row, $key, '') }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($headings) }}">No records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
