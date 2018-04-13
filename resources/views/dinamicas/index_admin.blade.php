@extends('layouts.app')

@section('content')
    <div>
        <dinamicas-admin :message="'{{ (session('message')) ? session('message') : ""  }}'" :user="{{ \Illuminate\Support\Facades\Auth::user() }}"></dinamicas-admin>
    </div>
@endsection