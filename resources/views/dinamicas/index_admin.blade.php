@extends('layouts.app')

@section('content')
    <dinamicas-admin :user="{{ \Illuminate\Support\Facades\Auth::user() }}"></dinamicas-admin>
@endsection