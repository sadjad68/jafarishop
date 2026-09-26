<?php

namespace App\Modules\General\Helper\Test;

use Illuminate\Support\Facades\File;

class UploadImg
{
    public function uploadPic($file, $path, $resize = true)
    {
        $pathMain = $path . '/main/';
        if ($resize) {
            $pathBig = $path . '/big/';
            $pathMedium = $path . '/medium/';
            $pathSmall = $path . '/small/';
        }


        $extension = $file->getClientOriginalExtension();
        $extension1 = $file->getClientOriginalName();
        $ext = ['jpg', 'JPG', 'jpeg', 'JPEG', 'png', 'PNG'];
        if (in_array($extension, $ext)) {
            if (!File::isDirectory($path)) {
                File::makeDirectory($path);
            }
            if (!File::isDirectory($pathMain)) {
                File::makeDirectory($pathMain);
            }
            if ($resize) {
                if (!File::isDirectory($pathBig)) {
                    File::makeDirectory($pathBig);
                }
                if (!File::isDirectory($pathMedium)) {
                    File::makeDirectory($pathMedium);
                }
                if (!File::isDirectory($pathSmall)) {
                    File::makeDirectory($pathSmall);
                }
            }


//            $fileName = md5(microtime()) . ".$extension";
            $fileName = md5(microtime()). "$extension1";
            $file->move($pathMain, $fileName);
            if ($resize) {
                $kaboom = explode(".", $fileName);
                $fileExt = end($kaboom);
                Resizer::resizePic($pathMain . $fileName, $pathSmall . $fileName, 200, 200, $fileExt);
                Resizer::resizePic($pathMain . $fileName, $pathMedium . $fileName, 400, 400, $fileExt);
                Resizer::resizePic($pathMain . $fileName, $pathBig . $fileName, 1000, 1000, $fileExt, True);

            }
            return $fileName;
        } else {
            return false;
        }
    }

}
