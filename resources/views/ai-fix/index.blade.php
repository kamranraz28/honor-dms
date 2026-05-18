<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AI Code Fix</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">

    <h3 class="mb-3">AI Code Fix</h3>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Analyze form --}}
    <form method="POST" action="{{ route('ai-fix.analyze') }}">
        @csrf
        <textarea name="prompt"
                  class="form-control"
                  rows="3"
                  placeholder="Undefined variable: products (View: products/index.blade.php)"
                  required></textarea>

        <button class="btn btn-primary mt-2">Analyze</button>
    </form>

    {{-- Show result ONLY if there is a pending fix --}}
    @if(isset($fix))
        <hr>

        <div class="card">
            <div class="card-body">

                <h6>Proposed Fix (Diff)</h6>
                <pre class="bg-light p-2">{{ $fix->diff }}</pre>

                <ul>
                    <li><b>Issue:</b> {{ $fix->report['issue'] ?? '-' }}</li>
                    <li><b>Cause:</b> {{ $fix->report['cause'] ?? '-' }}</li>
                    <li><b>Fix:</b> {{ $fix->report['fix'] ?? '-' }}</li>
                    <li><b>Risk:</b> {{ $fix->report['risk'] ?? '-' }}</li>
                    <li><b>Confidence:</b> {{ $fix->report['confidence'] ?? '-' }}</li>
                </ul>

                <form method="POST" action="{{ route('ai-fix.approve', $fix->id) }}" class="d-inline">
                    @csrf
                    <button class="btn btn-success btn-sm">Approve</button>
                </form>

                <form method="POST" action="{{ route('ai-fix.decline', $fix->id) }}" class="d-inline">
                    @csrf
                    <button class="btn btn-danger btn-sm">Decline</button>
                </form>

            </div>
        </div>
    @endif

</div>

</body>
</html>
