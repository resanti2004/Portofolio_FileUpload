<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class FileController extends Controller
{
    public function createFile(){
        Storage::disk('local')->put('file.txt', 'Contents');
    }

    public function getFile(){
        $exists = Storage::disk('local')->exists('file.txt');
        if($exists){
            $contents = Storage::get('file.txt');
        }
        echo $contents;
    }
    
    public function downloadFile(){
        return Storage::download("file.txt");
    }

    public function copyFile(){
        Storage::copy("file.txt", "move/file.txt");
    }

    public function moveFile(){
        Storage::copy("file.txt", "move/file.txt");
    }


}