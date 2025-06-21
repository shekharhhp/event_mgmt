@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Talk Proposal</h2>

    <form method="POST" action="{{ route('talks.update', $talk) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <input type="text" name="title" class="form-control mb-2" value="{{ $talk->title }}" required>

        <textarea name="description" class="form-control mb-2" required>{{ $talk->description }}</textarea>

        <input type="file" name="presentation_pdf" class="form-control mb-2" accept="application/pdf">

        <select name="tags[]" multiple class="form-control mb-2">
            @foreach($tags as $tag)
                <option value="{{ $tag->id }}" @if($talk->tags->contains($tag->id)) selected @endif>{{ $tag->name }}</option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
