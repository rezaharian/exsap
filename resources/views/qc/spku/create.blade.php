@extends('layouts.app')

@section('content')
    <div class=" ">
        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show small">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show small">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger small">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h5 class="fw-semibold text-center mb-4">Form Input SPKu</h5>

        <form action="{{ route('qc.spku.store') }}" method="POST" class="px-1">
            @csrf

            @include('qc.spku.fhead')
            @include('qc.spku.fdetail')

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary btn-md  rounded-pill shadow-sm">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
