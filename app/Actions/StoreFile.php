<?php

namespace App\Actions;

use Illuminate\Http\UploadedFile;

class StoreFile
{

    public function __construct(private string $folder_path)
    {
    }

    public function execute(UploadedFile $file, string $file_prefix): string
    {
        $filename = $file_prefix.'_'. time().".".$file->extension();
        $file->move(public_path($this->folder_path), $filename);

        return $this->folder_path.'/'.$filename;
    }
}
