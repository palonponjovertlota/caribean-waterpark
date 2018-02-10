<?php

namespace App\Services;

use File, Storage, Carbon, Image;

class ImageUploader {

    public static function upload($file, $directory) {
        $file_ext = $file->getClientOriginalExtension();
        $file_name = Helper::create_filename($file_ext);
        $thumbnail = ['height' => 500, 'width' => 500];

        $base_directory = $directory;
        $thumbs_directory = $directory.'/thumbnails';

        if (! Storage::exists($base_directory)) {
            Storage::makeDirectory($base_directory, $mode = 0777, true, true);
        }

        if (! Storage::exists($thumbs_directory)) {
            Storage::makeDirectory($thumbs_directory, $mode = 0777, true, true);
        }

        $path = $file->move($base_directory, $file_name);

        if (in_array($file_ext, ['jpeg', 'jpg', 'png', 'gif'])) {
            Image::make($base_directory.'/'.$file_name)
                ->crop($thumbnail['width'], $thumbnail['height'])
                ->save($thumbs_directory.'/'.$file_name, 95);
        }

        return [
            'file_path' => $path,
            'file_directory' => $base_directory,
            'file_name' => $file_name
        ];
    }
}