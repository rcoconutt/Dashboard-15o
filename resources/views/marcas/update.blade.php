@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <marcas-update :destilados="{{ $destilados }}" :marca="{{ $marca }}" :user="{{ \Illuminate\Support\Facades\Auth::user() }}"></marcas-update>
            </div>
        </div>
    </div>
@endsection