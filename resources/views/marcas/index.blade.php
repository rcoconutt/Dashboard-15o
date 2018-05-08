@extends('layouts.app')

@section('content')
    <div>
        <marcas-index :message="'{{ (session('message')) ? session('message') : ""  }}'" :user="{{ \Illuminate\Support\Facades\Auth::user() }}"></marcas-index>
    </div>
@endsection