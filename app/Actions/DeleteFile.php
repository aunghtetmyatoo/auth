<?php

namespace App\Actions;

use Illuminate\Support\Facades\File;

class DeleteFile
{
    public function execute(string $path)
    {
        if(File::exists(public_path($path))){
            File::delete(public_path($path));
        }
    }
}
