@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <marcas-create :destilados="{{ $destilados }}" :user="{{ \Illuminate\Support\Facades\Auth::user() }}"></marcas-create>
            </div>
        </div>
    </div>
@endsection