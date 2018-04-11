@extends('layouts.app')

@section('content')
    <dinamicas-embajador :user="{{ \Illuminate\Support\Facades\Auth::user() }}"></dinamicas-embajador>
@endsection