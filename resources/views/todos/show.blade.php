@extends('layouts.app')
@section('content')
<div>{{ $todo['title'] }}</div>
<div>{{ $todo['content'] }}</div>
<div>{{ $todo['due'] }}</div>
@endsection