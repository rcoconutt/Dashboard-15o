@extends('layouts.app')

@section('content')
    <div>
        <venues-index :message="'{{ (session('message')) ? session('message') : ""  }}'" :user="{{ \Illuminate\Support\Facades\Auth::user() }}"></venues-index>
    </div>
@endsection