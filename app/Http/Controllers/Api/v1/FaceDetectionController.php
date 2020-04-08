<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\FaceDetector;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Intervention\Image\Facades\Image;

use function App\Services\user_portraits_face_detect_crop_resize;

class FaceDetectionController extends Controller
{
    public function store(Request $request)
    {
        foreach ($request->file('photo') as $file) {
        //    dd($file->getPathname());
            // $raw_img = Image::make($file);
            // $img = $raw_img->encode('jpeg', 100);
            // $img->save();

            $detector = new FaceDetector();

            $edited_path = $detector->user_portraits_face_detect_crop_resize($file->getPathname(), 1, 1);
            // if(!file_exists($edited_path[0])) dd($edited_path);
            $edited = Image::make($edited_path[0] ?? dd($edited_path, $file->getClientOriginalName()));
            $edited->save(public_path('test') . '/' . $file->getClientOriginalName(), 100, 'jpg');

//             try {
//                 $detector = new FaceDetector(app_path('detection.dat'));
//                 $detector->faceDetect($img->dirname . '/' . $img->basename);
//             } catch (\Exception $e) {
//                 return response()->json(['msg' => $e->getMessage()], 404);
//             }

//             $co = $detector->getCoordinates();

//             $w = ((int)$co['w'] + 100 > $img->getWidth() ?(int)$co['w']: (int)$co['w'] + 100);
//             $h = ((int)$co['w'] + 100 > $img->getHeight() ? (int)$co['w'] : (int)$co['w'] + 100);
// //            dd($w, $h, $img->getWidth(), $img->getHeight());
// //            $img->fit($w, $h);
//             $img->crop((int)$co['w'], (int)$co['w'], (int)($co['x']), (int)($co['y']));
//             $img->fit(200, 250);
//             $img->save(public_path('test') . '/' . $file->getClientOriginalName(), 100, 'jpg');
        }

        return 'done';
    }
}
