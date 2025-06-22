@extends('layouts.app')

@section('content')
<div class="container">
    <h2>My Talk Proposals</h2>

    <a href="{{ route('talks.create') }}" class="btn btn-success mb-3">Submit New Talk</a>

    @foreach($proposals as $proposal)
        <div class="card mb-3">
            <div class="card-body">
                <h5>{{ $proposal->title }}</h5>
                <p>{{ $proposal->description }}</p>
                <p>Status: <strong>{{ ucfirst($proposal->status) }}</strong></p>

                @if($proposal->presentation_pdf)
                    <p>
                        <a href="{{ asset('storage/' . $proposal->presentation_pdf) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            View PDF
                        </a>
                    </p>
                @endif

                <a href="{{ route('talks.edit', $proposal) }}" class="btn btn-sm btn-warning">Edit</a>
            </div>
        </div>
    @endforeach
</div>
@endsection
