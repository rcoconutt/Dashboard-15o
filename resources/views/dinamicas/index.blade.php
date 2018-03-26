@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="col-md-2 col-sm-8 offset-md-10 ">
                    <a class="btn btn-block btn-success" href="{{ route('dinamicas.create') }}">Crear dinámica</a>
                </div>

                <hr>
                <dinamicas></dinamicas>
            </div>
        </div>
    </div>
@endsection
