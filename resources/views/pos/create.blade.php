@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-sm-12">
    <pos-session
      :shifts="{{ $shifts }}"
      :devices="{{ $devices }}"
      :initial-session="'{{ $sessionNumber }}'"
      cancel-url="{{ route('pos.sessions.index') }}"
    ></pos-session>
  </div>
</div>
@endsection
