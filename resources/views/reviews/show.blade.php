@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Review Proposal</h2>

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
                <p><a href="{{ asset('storage/' . $proposal->presentation_pdf) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                    View PDF
                </a></p>
            @endif
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

        <button type="submit" class="btn btn-primary">Submit Review</button>
        <a href="{{ route('reviews.index') }}" class="btn btn-secondary">Back to Dashboard</a>
    </form>
</div>
@endsection
