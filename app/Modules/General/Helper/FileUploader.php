<?php

namespace App\Modules\General\Helper;

use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class FileUploader
{
    protected $image;
    protected $resizes;
    protected $path;
    protected $quality;
    protected $extensions;
    protected $base_folder;

    public function __construct($image, $path)
    {
        $this->image = $image;
        $this->path = trim($path, '/');
//        $site = SiteHelper::getInformation();
        $base_folder = strlen(config('setting.base_upload_folder')) > 0 ? config('setting.base_upload_folder') . "/" : "";
        $this->base_folder = $base_folder;
    }

    public function setSizes($sizes)
    {
        $this->resizes = $sizes;
    }
    public function setQuality($quality = 70)
    {
        $this->quality = $quality;
    }

    public function setExtensions($extensions)
    {
        $this->extensions = $extensions;
    }

    private function makeDirectory($path)
    {
        if(strlen($this->base_folder) > 0 && !File::isDirectory($this->base_folder)){
            File::makeDirectory($this->base_folder);
        }
        if(!File::isDirectory($this->base_folder."uploads")){
            File::makeDirectory($this->base_folder."uploads");
        }
        if (!File::isDirectory($path)) {
            File::makeDirectory($path);
        }
        return true;
    }

//    public function upload()
//    {
//        $file_extension = $this->image->getClientOriginalExtension();
//        if ($this->extensions && count($this->extensions) > 0 && !in_array(strtolower($file_extension), $this->extensions)) {
//            return false;
//        } else {
//            $this->makeDirectory($this->base_folder.$this->path);
//            $file_name = md5(microtime()) . ".$file_extension";
//            if ($this->resizes && count($this->resizes) > 0) {
//                $this->makeDirectory($this->base_folder."$this->path/main");
//                $img = new ImageManager();
//                $main_image_path = $this->base_folder . "$this->path/main/$file_name";
//                $img->make($this->image)
//
//                    ->encode('webp', 90)
//                    ->save($this->base_folder."$this->path/main/$file_name");
//
////                $this->image->move($this->base_folder."$this->path/main", $file_name);
//                foreach ($this->resizes as $size_path => $sizes) {
//                    $this->makeDirectory($this->base_folder."$this->path/$size_path");
//                    $img = new ImageManager();
//                    $img->make($this->base_folder."$this->path/main/$file_name")
//                        ->resize($sizes[0], $sizes[1])
//                        ->encode('webp')
//                        //second arg is the quality from 0 to 100
//                        ->save($this->base_folder."$this->path/$size_path/$file_name",$this->quality);
//                }
//                if (file_exists($main_image_path)) {
//                    unlink($main_image_path);
//                }
//            }else{
//                $this->image->move($this->base_folder."$this->path", $file_name);
//            }
//            return $file_name;
//        }
//    }
    public function upload()
    {
        $file_extension = $this->image->getClientOriginalExtension();
        if ($this->extensions && count($this->extensions) > 0 && !in_array(strtolower($file_extension), $this->extensions)) {
            return false;
        }
        $this->makeDirectory($this->base_folder . $this->path);
        $file_name = md5(microtime()) . ".$file_extension";
        if ($this->resizes && count($this->resizes) > 0) {
            $this->makeDirectory($this->base_folder . "$this->path/main");
            $manager = new ImageManager(new Driver());
            $main_path = $this->base_folder . "$this->path/main/$file_name";
            $manager->read($this->image->getPathname())
                ->save($main_path);
            foreach ($this->resizes as $size_path => $sizes) {
                $this->makeDirectory($this->base_folder . "$this->path/$size_path");
                $manager->read($main_path)
                    ->scaleDown($sizes[0], $sizes[1])
                    ->toWebp($this->quality ?? 90)
                    ->save($this->base_folder . "$this->path/$size_path/$file_name");
            }
             unlink($main_path);

        } else {
            $this->image->move($this->base_folder . $this->path, $file_name);
        }

        return $file_name;
    }

}
