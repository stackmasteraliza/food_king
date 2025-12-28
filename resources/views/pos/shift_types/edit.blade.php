@extends('layouts.pos')

@section('content')
    <div class="container">
        <h1>Edit Shift Type</h1>
        <form action="{{ route('shift-types.update', $shiftType->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $shiftType->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $shiftType->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="parent_shift_id">Parent Shift (Optional)</label>
                <select name="parent_shift_id" id="parent_shift_id" class="form-control @error('parent_shift_id') is-invalid @enderror">
                    <option value="">None</option>
                    @foreach($parentShifts as $shift)
                        <option value="{{ $shift->id }}" {{ old('parent_shift_id', $shiftType->parent_shift_id) == $shift->id ? 'selected' : '' }}>{{ $shift->name }}</option>
                    @endforeach
                </select>
                @error('parent_shift_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="image">Image/Icon URL (Optional)</label>
                <input type="text" name="image" id="image" class="form-control @error('image') is-invalid @enderror" value="{{ old('image', $shiftType->image) }}">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary mt-3">Update Shift Type</button>
        </form>
    </div>
@endsection
