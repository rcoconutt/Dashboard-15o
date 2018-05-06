@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <zonas-create :estados="{{ $estados }}" :user="{{ \Illuminate\Support\Facades\Auth::user() }}"></zonas-create>
            </div>
        </div>
    </div>
@endsection