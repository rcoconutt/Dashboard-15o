@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <zonas-update :estados="{{ $estados }}" :zona="{{ $municipio }}" :user="{{ \Illuminate\Support\Facades\Auth::user() }}"></zonas-update>
            </div>
        </div>
    </div>
@endsection