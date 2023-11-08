@extends('auth.layouts')

@section('content')

<div class="row justify-content-center mt-5">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Dashboard</div>
            <div class="card-body">
                @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    {{ $message }}
                </div>
                @else
                <div class="alert alert-success">
                    You are logged in!
                </div>
                @endif

                <!-- Tambahkan tombol untuk ke halaman users di sini -->
                <div class="d-flex justify-content-center">
                    <a href="{{ route('users') }}" class="btn btn-secondary">Go to Users</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
