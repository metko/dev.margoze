@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <div class="p-4 py-16 text-3xl mt-4">
        Dashboard
    </div>
</div>

@endsection
