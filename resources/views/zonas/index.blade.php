@extends('layouts.app')

@section('content')
    <div>
        <zonas-index :message="'{{ (session('message')) ? session('message') : ""  }}'" :user="{{ \Illuminate\Support\Facades\Auth::user() }}"></zonas-index>
    </div>
@endsection