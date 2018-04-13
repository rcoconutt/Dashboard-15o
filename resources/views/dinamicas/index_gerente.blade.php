@extends('layouts.app')

@section('content')
    <dinamicas-gerente :message="'{{ (session('message')) ? session('message') : ""  }}'" :user="{{ \Illuminate\Support\Facades\Auth::user() }}"></dinamicas-gerente>
@endsection