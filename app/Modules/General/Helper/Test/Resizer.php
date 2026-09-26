<?php
namespace App\Modules\General\Helper\Test;

class Resizer {

    public static function resizePic($main, $copy, $w, $h, $ext, $delete = false) {
        list($w_orig, $h_orig) = getimagesize($main);
        $scale_ratio = $w_orig / $h_orig;
        if(($w / $h) > $scale_ratio) {
            $w = $h * $scale_ratio;
        } else {
            $h = $w / $scale_ratio;
        }
        $img = "";
        $ext = strtolower($ext);

        // تشخیص نوع فایل بر اساس محتوای آن
        $imageInfo = getimagesize($main);
        $mimeType = $imageInfo['mime'];

        switch ($mimeType) {
            case 'image/jpeg':
                $img = imagecreatefromjpeg($main);
                break;
            case 'image/png':
                $img = imagecreatefrompng($main);
                break;
            case 'image/gif':
                $img = imagecreatefromgif($main);
                break;
            default:
                throw new \Exception("Unsupported image type");
        }

        $tci = imagecreatetruecolor($w, $h);
        imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
        imagejpeg($tci, $copy, 90);
        if($delete) unlink($main);
    }}
