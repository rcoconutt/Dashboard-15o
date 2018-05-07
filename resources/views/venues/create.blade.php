@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <venues-create :zonas="{{ $zonas }}" :user="{{ \Illuminate\Support\Facades\Auth::user() }}"></venues-create>
            </div>
        </div>
    </div>
@endsection