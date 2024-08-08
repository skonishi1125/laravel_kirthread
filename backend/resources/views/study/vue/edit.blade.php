@extends('layouts.app')
@section('content')
<h4>vue.jsテストです</h4>
<p>{{ $post['id'] }}, {{ $post['message'] }}</p>
<div id="app">
  <message-editor 
      post-id="{{ $post->id }}" 
      initial-message="{{ $post->message }}">
  </message-editor>
</div>
@endsection