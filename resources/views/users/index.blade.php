@extends('auth.layouts')

@section('content')
    <div class="container">
        <div class="title col-12 text-center my-5">
            <h1>Daftar Pengguna</h1>
        </div>
        <table class="table">
            <thead class="bg-primary text-white text-center">
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Photo</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td class="align-middle">{{ $user->name }}</td>
                        <td class="align-middle">{{ $user->email }}</td>
                        <td class="align-middle">
                            <img src="{{ asset('storage/'.$user->photo) }}" width="150px">
                        </td>
                        <td class="align-middle">
                            <!-- Tambahkan tombol-tombol aksi di sini, seperti Edit, Hapus, dan Resize -->
                            <a href="{{ route('edit', $user->id) }}" class="btn btn-primary">Edit</a>
                            <form action="{{ route('users.delete', $user->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Anda yakin ingin menghapus pengguna ini?')">Hapus</button>
                            </form>
                            <a href="{{ route('users.resize', $user->id) }}" class="btn btn-warning">Resize</a>



                            {{-- <form action="{{ route('users.resize', $user->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-secondary">Resize</button>
                            </form> --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
