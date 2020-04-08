<?php

namespace App\Services;

/**
 * crop and resize the loaded image to 300x400 portraits.
 *
 */
class FaceDetector {
	function user_portraits_face_detect_crop_resize($jpg_file_path, $face, $size, $show_all = 0) {
		$path_arr = explode('/', $jpg_file_path);
		$file_name_full = array_pop($path_arr);
		$path = implode('/', $path_arr);
		$file_name = explode('.', $file_name_full);
		$jpg_file_path_temp = $path . '/' . $file_name[0] . '_1.jpg';
	
		if (!function_exists('imagecreatefromjpeg') || $size <> 1) {
			//if GD library is not installed just copy the file.
			copy($jpg_file_path, $jpg_file_path_temp);
			return array($jpg_file_path_temp);
		}
	
		if (!function_exists('face_detect') || $face < 1) {
			//face detection is not possible since openCV is not installed
			$jpg_file_path_temp = $path . '/' . $file_name[0] . '_0.jpg';
			//resize the image to 300x400
			$result = imagecreatetruecolor(300, 400);
			$src = imagecreatefromjpeg($jpg_file_path);
			$original_w = imagesx($src);
			$original_h = imagesy($src);
			imagecopyresampled($result, $src, 0, 0, 0, 0, 300, 400, $original_w, $original_h);
			imagedestroy($src);
			//file will be written to to a temp new file
			imagejpeg($result, $jpg_file_path_temp);
			imagedestroy($result);
			return array($jpg_file_path_temp);
		}
	
		//face detection process
		// -> expermental face detection
		// if portraits selected from both methods' results 100% accuracy can be reached.
		if ($face == 1) {
			// method-1 around produces 95% accurate result
			$method = app_path('OpenCV/haarcascades/haarcascade_frontalface_alt_tree.xml');
			//$method = '/usr/local/share/opencv/haarcascades/haarcascade_frontalface_alt_tree.xml';
		} else {
			// method-2 around produces 90% accurate result
			$method = '/usr/share/OpenCV/haarcascades/haarcascade_frontalface_alt_tree.xml';
			//$method = '/usr/local/share/opencv/haarcascades/haarcascade_frontalface_alt_tree.xml';
		}
		$src = imagecreatefromjpeg($jpg_file_path);
		$coords = face_detect($jpg_file_path, $method);
		$i = 1;
		$images = array();
		if (empty($coords) || !is_array($coords)) {
			$coords = array();
		}
		foreach ($coords as $coord) {
			$x = (int) ($coord['x'] - $coord['w'] * 0.3); //move to 30% of face width left
			$y = (int) ($coord['y'] - $coord['h'] * 0.45); //move to 45% of face height up
			$w = (int) ($coord['w'] + $coord['w'] * 0.6);  //increase width of portrait photo 60%
			$h = (int) ($coord['h'] + $coord['h'] * 1.2);  //increase height of portrait photo 110%
			//get cropped image
			$temp_img = imagecreatetruecolor($w, $h);
			imagecopy($temp_img, $src, 0, 0, $x, $y, $w, $h);
			//resize the image to 300x400
			$result = imagecreatetruecolor(300, 400);
			imagecopyresampled($result, $temp_img, 0, 0, 0, 0, 300, 400, $w, $h);
			imagedestroy($temp_img);
	
			$jpg_file_path_temp = $path . '/' . $file_name[0] . '_' . $i++ . '.jpg';
			imagejpeg($result, $jpg_file_path_temp);
			imagedestroy($result);
			$images[] = $jpg_file_path_temp;
		}
		//drupal_set_message(t('Note: Please check the result images since openCV has aroun 95% accuracy.'), 'warning', FALSE);
		//file will be written to a temp new file
		return $images;
	}
}