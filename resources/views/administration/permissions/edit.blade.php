@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ isset($permission) ? 'Edit Permission' : 'Create Permission' }}</h1>

    <form action="{{ isset($permission) 
        ? route('mgpermissions.update', ['mgpermission' => $permission->id])
        : route('mgpermissions.store') }}" method="POST">
        @csrf
        @if(isset($permission))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label class="form-label">Permission Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $permission->name ?? '') }}" required>
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button class="btn btn-success" type="submit">{{ isset($permission) ? 'Update' : 'Create' }}</button>
        <a href="{{ route('mgpermissions.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
