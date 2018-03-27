@extends('layouts.app')

@section('content')
    @if(\Illuminate\Support\Facades\Auth::user()->rol == 0)
        <users-admin :brand="{{ \Illuminate\Support\Facades\Auth::user()->brand_id }}"></users-admin>
    @else
        <users :brand="{{ \Illuminate\Support\Facades\Auth::user()->brand_id }}"></users>
    @endif
@endsection
