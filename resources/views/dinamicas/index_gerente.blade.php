@extends('layouts.app')

@section('content')
    <dinamicas-gerente :user="{{ \Illuminate\Support\Facades\Auth::user() }}"></dinamicas-gerente>
@endsection