@extends('layouts.app')

@section('content')
    <dinamicas :user="{{ \Illuminate\Support\Facades\Auth::user() }}"></dinamicas>
@endsection
