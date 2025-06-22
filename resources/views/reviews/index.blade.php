@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Review Dashboard</h2>

    {{-- Filter Form --}}
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <label for="tag" class="form-label">Filter by Tag</label>
            <select name="tag" id="tag" class="form-select">
                <option value="">-- All Tags --</option>
                @foreach($tags as $tag)
                    <option value="{{ $tag->id }}" {{ request('tag') == $tag->id ? 'selected' : '' }}>
                        {{ $tag->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label for="speaker" class="form-label">Speaker Name</label>
            <input type="text" name="speaker" id="speaker" value="{{ request('speaker') }}" class="form-control">
        </div>

        <div class="col-md-3">
            <label for="date" class="form-label">Submitted Date</label>
            <input type="date" name="date" id="date" value="{{ request('date') }}" class="form-control">
        </div>

        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">Search</button>
            <a href="{{ route('reviews.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    {{-- Proposals Table --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Title</th>
                <th>Speaker</th>
                <th>Tags</th>
                <th>Submitted</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($proposals as $proposal)
                <tr>
                    <td>{{ $proposal->title }}</td>
                    <td>{{ $proposal->speaker->name }}</td>
                    <td>
                        @foreach($proposal->tags as $tag)
                            <span class="badge bg-info text-dark">{{ $tag->name }}</span>
                        @endforeach
                    </td>
                    <td>{{ $proposal->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('reviews.show', $proposal->id) }}" class="btn btn-sm btn-success">Review</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No proposals found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $proposals->links() }}
</div>
<script src="https://js.pusher.com/8.0/pusher.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.Echo !== 'undefined') {
            window.Echo.channel('reviewers')
                .listen('.talk-submitted', (e) => {
                    alert(`New talk submitted: ${e.proposal.title} by ${e.proposal.speaker.name}`);
                    location.reload();
                });

            window.Echo.connector.pusher.connection.bind('connected', () => {
                console.log('Pusher connected');
            });
        } else {
            console.error('Echo is not initialized.');
        }
    });
</script>
@endsection
