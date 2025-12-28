@extends('layouts.pos')

@section('content')
    <div class="container">
        <h1>Start POS Session</h1>
        <form action="{{ route('pos.start.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="shift_type_id">Shift Type</label>
                <select name="shift_type_id" id="shift_type_id" class="form-control @error('shift_type_id') is-invalid @enderror">
                    <option value="">Select Shift Type</option>
                    @foreach($shiftTypes as $shiftType)
                        <option value="{{ $shiftType->id }}">{{ $shiftType->name }}</option>
                    @endforeach
                </select>
                @error('shift_type_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="device_id">POS Device</label>
                <input type="text" name="device_id" id="device_id" class="form-control @error('device_id') is-invalid @enderror" value="{{ old('device_id') }}" placeholder="Enter Device ID or Name">
                @error('device_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="opening_float">Opening Float (Optional)</label>
                <input type="number" name="opening_float" id="opening_float" class="form-control @error('opening_float') is-invalid @enderror" value="{{ old('opening_float', 0.00) }}" step="0.01" min="0">
                @error('opening_float')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary mt-3">Start Session</button>
        </form>
    </div>
@endsection
