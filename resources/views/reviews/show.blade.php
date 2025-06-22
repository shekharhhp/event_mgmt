@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Review Proposal</h2>

    {{-- Flash Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Proposal Details --}}
    <div class="card mb-4">
        <div class="card-header">{{ $proposal->title }}</div>
        <div class="card-body">
            <p><strong>Speaker:</strong> {{ $proposal->speaker->name }}</p>
            <p><strong>Description:</strong><br>{{ $proposal->description }}</p>
            <p><strong>Tags:</strong>
                @foreach($proposal->tags as $tag)
                    <span class="badge bg-info text-dark">{{ $tag->name }}</span>
                @endforeach
            </p>
            @if($proposal->presentation_pdf)
                <p>
                    <a href="{{ asset('storage/' . $proposal->presentation_pdf) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                        View Presentation PDF
                    </a>
                </p>
            @endif

            {{-- Current Status --}}
            <p><strong>Status:</strong>
                <span class="badge bg-{{ $proposal->status == 'approved' ? 'success' : ($proposal->status == 'rejected' ? 'danger' : 'warning') }}">
                    {{ ucfirst($proposal->status) }}
                </span>
            </p>
        </div>
    </div>

    {{-- Review Form --}}
    <form method="POST" action="{{ route('reviews.store', $proposal->id) }}">
        @csrf

        <div class="mb-3">
            <label for="comments" class="form-label">Your Review</label>
            <textarea name="comments" id="comments" rows="4" class="form-control @error('comments') is-invalid @enderror" required>{{ old('comments') }}</textarea>
            @error('comments')
                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="rating" class="form-label">Rating (1–5)</label>
            <input type="number" name="rating" id="rating" min="1" max="5" class="form-control @error('rating') is-invalid @enderror" required value="{{ old('rating') }}">
            @error('rating')
                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Proposal Status</label>
            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                <option value="">-- Select --</option>
                <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            @error('status')
                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Submit Review</button>
        <a href="{{ route('reviews.index') }}" class="btn btn-secondary">Back to Dashboard</a>
    </form>

    {{-- Previous Reviews --}}
    @if($proposal->reviews->count())
        <div class="mt-5">
            <h4>Previous Reviews</h4>
            @foreach($proposal->reviews as $review)
                <div class="card mb-2">
                    <div class="card-body">
                        <strong>{{ $review->reviewer->name }}</strong> 
                        <span class="text-muted">({{ $review->created_at->format('Y-m-d') }})</span>
                        <p>Rating: <strong>{{ $review->rating }}/5</strong></p>
                        <p>{{ $review->comments }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
