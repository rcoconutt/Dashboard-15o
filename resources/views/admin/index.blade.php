@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @if ($errors->has('error'))
                    <div class="alert alert-danger">
                        <strong>{{ $errors->first('error') }}</strong>
                    </div>
                @endif
                <hr>
                <admin :message="'{{ (session('message')) ? session('message') : ""  }}'" :user="{{ \Illuminate\Support\Facades\Auth::user() }}"></admin>
            </div>
        </div>
    </div>
@endsection