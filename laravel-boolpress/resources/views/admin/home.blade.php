@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    {{ __('Benvenuto ' . Auth::user()->name . '!') }}

                    <br>

                    All'interno del blog per ora ci sono:
                    <ul>
                        <li>
                            Posts: {{ $statistics["posts"] }}
                        </li>
                    </ul>
                </div>
                <div class="links">
                    <a href=" {{route('admin.posts.create')}} ">Create</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
