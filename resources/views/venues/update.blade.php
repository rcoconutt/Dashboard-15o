@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <venues-update :zonas="{{ $zonas }}" :venue="{{ $venue}}" :user="{{ \Illuminate\Support\Facades\Auth::user() }}"></venues-update>
            </div>
        </div>
    </div>
@endsection