@extends('layouts.app')

@section('content')
    <dinamicas-supervisor :user="{{ \Illuminate\Support\Facades\Auth::user() }}"></dinamicas-supervisor>
@endsection