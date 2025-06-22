@extends('layouts.app')

@section('content')
<div class="container">
    <h2>My Talk Proposals</h2>

    <a href="{{ route('talks.create') }}" class="btn btn-success mb-4">Submit New Talk</a>

    @forelse($proposals as $proposal)
        <div class="card mb-4">
            <div class="card-header">
                <strong>{{ $proposal->title }}</strong>
                <span class="float-end badge bg-{{ $proposal->status == 'approved' ? 'success' : ($proposal->status == 'rejected' ? 'danger' : 'warning') }}">
                    {{ ucfirst($proposal->status) }}
                </span>
            </div>
            <div class="card-body">
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

                <a href="{{ route('talks.edit', $proposal) }}" class="btn btn-warning btn-sm">Edit</a>
            </div>

            {{-- Show Reviews --}}
            @if($proposal->reviews->count())
                <div class="card-footer">
                    <strong>Reviews:</strong>
                    @foreach($proposal->reviews as $review)
                        <div class="mt-2 p-2 border rounded bg-light">
                            <strong>{{ $review->reviewer->name }}</strong>
                            <span class="text-muted">({{ $review->created_at->format('Y-m-d') }})</span>
                            <p>Rating: <strong>{{ $review->rating }}/5</strong></p>
                            <p>{{ $review->comments }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @empty
        <div class="alert alert-info">You haven't submitted any proposals yet.</div>
    @endforelse
</div>
@endsection
