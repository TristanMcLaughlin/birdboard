@extends('layouts.app')

@section('content')
    @if($project)
        {{$project->title}}
        {{$project->description}}
    @endif
    <a href="/projects">Back</a>
@endsection
