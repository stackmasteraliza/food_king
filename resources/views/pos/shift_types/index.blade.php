@extends('layouts.pos')

@section('content')
    <div class="container">
        <h1>Shift Types</h1>
        <a href="{{ route('shift-types.create') }}" class="btn btn-primary mb-3">Create New Shift Type</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Parent Shift</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($shiftTypes as $shiftType)
                    <tr>
                        <td>{{ $shiftType->id }}</td>
                        <td>{{ $shiftType->name }}</td>
                        <td>{{ $shiftType->description ?? 'N/A' }}</td>
                        <td>{{ $shiftType->parentShift->name ?? 'None' }}</td>
                        <td>
                            <a href="{{ route('shift-types.edit', $shiftType->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('shift-types.destroy', $shiftType->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
