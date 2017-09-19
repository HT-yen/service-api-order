@extends('layouts.layout1')

@section('title', 'Page Title')

@section('sidebar')
    @parent

    <p>This is appended to the master sidebar.</p><!-- 1 -->
@endsection

@section('content')
    <p>This is my body content.</p>
@endsection

@section('continue_content')

@show