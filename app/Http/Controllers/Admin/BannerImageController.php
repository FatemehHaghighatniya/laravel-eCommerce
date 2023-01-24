<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BannerImageController extends Controller
{
    public function upload($image)
    {
        $fileNameImage=generateFileName($image->getClientOriginalName());
        $image->move(public_path(env('BANNER_IMAGES_UPLOAD_PATH')),$fileNameImage);
        return $fileNameImage;
    }


}
