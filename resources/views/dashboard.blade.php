@extends('layout')

@section('content')
<div class="container">
    <h1>Welcome to the Dashboard!</h1>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-primary">Logout</button>
    </form>
</div>
@endsection
