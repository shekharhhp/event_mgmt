@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Talk Proposal</h2>

    <form method="POST" action="{{ route('talks.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="text" name="title" class="form-control mb-2" placeholder="Title" required>

        <textarea name="description" class="form-control mb-2" placeholder="Description" required></textarea>

        <input type="file" name="presentation_pdf" class="form-control mb-2" accept="application/pdf">

        <select name="tags[]" multiple class="form-control mb-2">
            @foreach($tags as $tag)
                <option value="{{ $tag->id }}">{{ $tag->name }}</option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
