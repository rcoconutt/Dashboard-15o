@extends('layouts.app')

@section('content')
    <div>
        <destilados-index :message="'{{ (session('message')) ? session('message') : ""  }}'" :user="{{ \Illuminate\Support\Facades\Auth::user() }}"></destilados-index>
    </div>
@endsection