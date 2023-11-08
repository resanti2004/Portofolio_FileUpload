<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;



class ImageController extends Controller
{
    public function showUsers(){
    $users = User::all(); // Mengambil data pengguna dari model User (sesuaikan dengan model Anda)
    return view('users.index', ['users' => $users]);
    }

    public function edit($id){
    $user = User::find($id);
    return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id){
        $user = User::find($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoPath = $photo->store('photos', 'public'); // Simpan foto ke direktori 'storage/app/public/photos'
            $user->photo = $photoPath;
        }

        $user->save();

        return redirect()->route('users')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy($id) {
        $user = User::find($id);
    
        // Hapus file foto pengguna jika ada
        $fileToDelete = public_path('storage/' . $user->photo);
    
        if (File::exists($fileToDelete)) {
            File::delete($fileToDelete);
    
            // Set atribut foto pengguna menjadi null atau sesuai dengan nilai default jika perlu
            $user->photo = null;
            $user->save();
    
            return redirect()->back()->with('success', 'Foto pengguna berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Foto pengguna tidak ditemukan.');
        }
    }
    

    public function resizeForm(User $user){
    return view('users.resize', compact('user'));
    }


    public function resizeImage(Request $request, User $user)
    {
        $this->validate($request, [
            'size' => 'required|in:thumbnail,square',
            'photo' => 'required|string',
        ]);
        // dd($request);
        // dd($request);
        $sizePhoto = $request->input('size');
        // dd(Storage::exists('photos/original/' . $user->photo));
        if (Storage::exists($user->photo)) {
            $originalImagePath = $user->photo;
            if ($sizePhoto === 'thumbnail') {
                $resizedImage = Image::make(Storage::disk('public')->get($originalImagePath));
                $resizedImage->fit(160, 90);
                Storage::disk('public')->put('photos/thumbnail/' . $user->photo, $resizedImage->stream());
            } elseif ($sizePhoto === 'square') {
                $resizedImage = Image::make(Storage::disk('public')->get($originalImagePath));
                $resizedImage->fit(100, 100);
                Storage::disk('public')->put('photos/square/' . $user->photo, $resizedImage->stream());
            }
        }

        $users = User::all();
        return view('users.index', compact('users'))->with('success', 'User photo is resized successfully.');
    }

    
}
