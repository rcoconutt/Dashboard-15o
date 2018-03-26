@extends('layouts.app')

@section('content')
    <users :brand="{{ \Illuminate\Support\Facades\Auth::user()->brand_id }}"></users>
@endsection
