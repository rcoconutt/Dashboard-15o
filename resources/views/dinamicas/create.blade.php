@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <create-dinamica :user="{{ \Illuminate\Support\Facades\Auth::user() }}"></create-dinamica>
            </div>
        </div>
    </div>
@endsection