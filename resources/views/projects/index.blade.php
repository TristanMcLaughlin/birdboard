@extends('layouts.app')

@section('content')
    <div style="display: flex; align-items: center;">
        <h1 style="margin-right: auto;">Birdboard</h1>
        <a href="/projects/create">Create new project</a>
    </div>
    @forelse($projects as $project)
        <li>
            <a href="{{$project->path()}}">{{$project->title}}</a>
        </li>
    @empty
        <li>No projects</li>
    @endforelse
@endsection
