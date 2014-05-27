<?php

/**
 * Image tool 1.2.0
 *
 * Different tools/functions to perform various tasks w/ images
 */
class ImageTool {

	/**
	 * Resize image
	 *
	 * Options:
	 * - 'input' Input file (path or gd resource)
	 * - 'output' Output path. If not specified, gd resource is returned
	 * - 'quality' Output image quality (JPG only). Value from 0 to 100
	 * - 'compression' Output image compression (PNG only). Value from 0 to 9
	 * - 'units' Scale units. Percents ('%') and pixels ('px') are avalaible
	 * - 'enlarge' if set to false and width or height of the destination image is bigger than source image's width or height, then leave source image's dimensions untouched
	 * - 'chmod' What permissions should be applied to destination image
	 * - 'keepRatio' If true and both output width and height is specified and crop is set to false, image is resized with respect to it's original ratio. If false (default), image is simple scaled.
	 * - 'paddings' If not empty and both output width and height is specified and keepRatio is set to true, padding borders are applied. You can specify color here. If true, then white color will be applied
	 * - 'afterCallbacks' Functions to be executed after resize. Example: [['unsharpMask', 70, 3.9, 0]]; First passed argument is f-ion name. Executed function's first argument must be gd image instance
	 * - 'crop' If true (default) crop excess portions of image to fit in specified size
	 * - 'height' Output image's height. If left empty, this is auto calculated (if possible)
	 * - 'width' Output image's width. If left empty, this is auto calculated (if possible)
	 *
	 * @param array $options An array of options
	 * @return mixed boolean or GD resource if output was set to null
	 */
	public static function resize($options = array()) {
		$options = array_merge(array(
			'afterCallbacks' => null,
			'compression' => null,
			'keepRatio' => false,
			'paddings' => true,
			'enlarge' => true,
			'quality' => null,
			'chmod' => null,
			'units' => 'px',
			'height' => null,
			'output' => null,
			'width' => null,
			'input' => null,
			'crop' => true
		), $options);

		// if output path (directories) doesn't exist, try to make whole path
		if (!self::createPath($options['output'])) {
			return false;
		}

		$input_extension = self::getImageType($options['input']);
		$output_extension = self::getExtension($options['output']);

		$src_im = self::openImage($options['input']);
		unset($options['input']);

		if (!$src_im) {
			return false;
		}

		// calculate new w, h, x and y

		if (!empty($options['width']) && !is_numeric($options['width'])) {
			return false;
		}
		if (!empty($options['height']) && !is_numeric($options['height'])) {
			return false;
		}

		// get size of the original image
		$input_width = imagesx($src_im);
		$input_height = imagesy($src_im);

		//calculate destination image w/h

		// turn % into px
		if ($options['units'] == '%') {
			if ($options['height'] != null) {
				$options['height'] = round($input_height * $options['height'] / 100);
			}

			if ($options['width'] != null) {
				$options['width'] = round($input_width * $options['width'] / 100);
			}
		}

		// if keepRatio is set to true, check output width/height and update them
		// as neccessary
		if ($options['keepRatio'] && $options['width'] != null && $options['height'] != null) {
			$input_ratio = $input_width / $input_height;
			$output_ratio = $options['width'] / $options['height'];

			$original_width = $options['width'];
			$original_height = $options['height'];

			if ($input_ratio > $output_ratio) {
				$options['height'] = $input_height * $options['width'] / $input_width;
			} else {
				$options['width'] = $input_width * $options['height'] / $input_height;
			}
		}

		// calculate missing width/height (if any)
		if ($options['width'] == null && $options['height'] == null) {
			$options['width'] = $input_width;
			$options['height'] = $input_height;
		} else if ($options['height'] == null) {
			$options['height'] = round(($options['width'] * $input_height) / $input_width);
		} else if ($options['width'] == null) {
			$options['width'] = round(($options['height'] * $input_width) / $input_height);
		}

		$src_x = 0;
		$src_y = 0;
		$src_w = $input_width;
		$src_h = $input_height;

		if ($options['enlarge'] == false && ($options['width'] > $input_width || $options['height'] > $input_height)) {
			$options['width'] = $input_width;
			$options['height'] = $input_height;
		} else if ($options['crop'] == true) {
			if (($input_width / $input_height) > ($options['width'] / $options['height'])) {
				$ratio = $input_height / $options['height'];
				$src_w = $ratio * $options['width'];
				$src_x = round(($input_width - $src_w) / 2);
			} else {
				$ratio = $input_width / $options['width'];
				$src_h = $ratio * $options['height'];
				$src_y = round(($input_height - $src_h) / 2);
			}
		}

		$dst_im = imagecreatetruecolor($options['width'], $options['height']);

		if (!$dst_im) {
			imagedestroy($src_im);
			return false;
		}

		// transparency or white bg instead of black
		if (in_array($input_extension, array('png', 'gif'))) {
			if (in_array($output_extension, array('png', 'gif'))) {
				imagealphablending($dst_im, false);
				imagesavealpha($dst_im, true);
				$transparent = imagecolorallocatealpha($dst_im, 255, 255, 255, 127);
				imagefilledrectangle($dst_im, 0, 0,$options['width'], $options['height'], $transparent);
			} else {
				$white = imagecolorallocate($dst_im, 255, 255, 255);
				imagefilledrectangle($dst_im, 0, 0, $options['width'], $options['height'], $white);
			}
		}

		$r = imagecopyresampled($dst_im, $src_im, 0, 0, $src_x, $src_y, $options['width'], $options['height'], $src_w, $src_h);

		if (!$r) {
			imagedestroy($src_im);
			return false;
		}

		if ($options['keepRatio'] && $options['paddings']) {
			if ($options['width'] != $original_width || $options['height'] != $original_height) {
				$bg_im = imagecreatetruecolor($original_width, $original_height);

				if (!$bg_im) {
					imagedestroy($bg_im);
					return false;
				}

				if ($options['paddings'] === true) {
					$rgb = array(255, 255, 255);
				} else {
					$rgb = self::readColor($options['paddings']);
					if (!$rgb) {
						$rgb = array(255, 255, 255);
					}
				}

				$color = imagecolorallocate($bg_im, $rgb[0], $rgb[1], $rgb[2]);
				imagefilledrectangle($bg_im, 0, 0, $original_width, $original_height, $color);

				$x = round(($original_width - $options['width']) / 2);
				$y = round(($original_height - $options['height']) / 2);

				imagecopy($bg_im, $dst_im, $x, $y, 0, 0, $options['width'], $options['height']);

				$dst_im = $bg_im;
			}
		}

		if (!self::afterCallbacks($dst_im, $options['afterCallbacks'])) {
			return false;
		}

		return self::saveImage($dst_im, $options);
	}


	/**
	 * Get file extension
	 *
	 * @param string $filename Filename
	 * @return string
	 */
	public static function getExtension($filename) {
		if (!is_string($filename)) {
			return '';
		}

		$pos = strrpos($filename, '.');

		if ($pos === false) {
			return '';
		}

		return strtolower(substr($filename, $pos + 1));
	}

	/**
	 * Open image as gd resource
	 *
	 * @param string $input Input (path) image
	 * @return mixed
	 */
	public static function openImage($input) {
		if (is_resource($input)) {
			if (get_resource_type($input) == 'gd') {
				return $input;
			}
		} else {
			if (is_string($input) && preg_match('/^https?:\/\//', $input)) {
				$image = file_get_contents($input);
				if (!$image) {
					return false;
				}

				return imagecreatefromstring($image);
			}

			switch (self::getImageType($input)) {
				case 'jpg':
					return imagecreatefromjpeg($input);
				break;

				case 'png':
					return imagecreatefrompng($input);
				break;

				case 'gif':
					return imagecreatefromgif($input);
				break;
			}
		}

		return false;
	}

	/**
	 * Get image type from file
	 *
	 * @param string $input Input (path) image
	 * @param string $extension (optional) Extension (type)
	 * @param boolean $extension If true, check by extension
	 * @return string
	 */
	public static function getImageType($input, $extension = false) {
		if ($extension) {
			switch (self::getExtension($input)) {
				case 'jpg':
				case 'jpeg':
					return 'jpg';
				break;

				case 'png':
					return 'png';
				break;

				case 'gif':
					return 'gif';
				break;
			}
		} else if (is_string($input) && is_file($input)) {
			$info = getimagesize($input);

			switch ($info['mime']) {
				case 'image/pjpeg':
				case 'image/jpeg':
				case 'image/jpg':
					return 'jpg';
				break;

				case 'image/x-png':
				case 'image/png':
					return 'png';
				break;

				case 'image/gif':
					return 'gif';
				break;
			}
		}

		return '';
	}

	/**
	 * Save image gd resource as image
	 *
	 * Image type is determined by $output extension so it must be present.
	 *
	 * Options:
	 * - 'compression' Output image's compression. Currently only PNG (value 0-9) supports this
	 * - 'quality' Output image's quality. Currently only JPG (value 0-100) supports this
	 * - 'output' Output path. If not specified, image resource is returned
	 *
	 * @param mixed $im Image resource
	 * @param string $output Output path
	 * @param mixed $options An array of additional options
	 * @return boolean
	 */
	public static function saveImage(&$im, $options = array()) {
		foreach (array('compression', 'quality', 'chmod') as $v) {
			if (is_null($options[$v])) {
				unset($options[$v]);
			}
		}

		$options = array_merge(array(
			'compression' => 9,
			'quality' => 100,
			'output' => null
		), $options);

		switch (self::getImageType($options['output'], true)) {
			case 'jpg':
				if (ImageJPEG($im, $options['output'], $options['quality'])) {
					if (!empty($options['chmod'])) {
						chmod($options['output'], $options['chmod']);
					}
					return true;
				}
			break;

			case 'png':
				if (ImagePNG($im, $options['output'], $options['compression'])) {
					if (!empty($options['chmod'])) {
						chmod($options['output'], $options['chmod']);
					}
					return true;
				}
			break;

			case 'gif':
				if (ImageGIF($im, $options['output'])) {
					if (!empty($options['chmod'])) {
						chmod($options['output'], $options['chmod']);
					}
					return true;
				}
			break;

			case '':
				return $im;
			break;
		}

		unset($im);
		return false;
	}

	/**
	 * Try to create specified path
	 *
	 * If specified path is empty, return true
	 *
	 * @param string $output_path
	 * @param mixed $chmod Each folder's permissions
	 * @return boolean
	 */
	public static function createPath($output_path, $chmod = 0777) {
		if (empty($output_path)) {
			return true;
		}

		$arr_output_path = explode(DIRECTORY_SEPARATOR, $output_path);

		unset($arr_output_path[count($arr_output_path)-1]);

		$dir_path = implode($arr_output_path, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

		if (!file_exists($dir_path)) {
			if (!mkdir($dir_path, $chmod, true)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Autorotate image
	 *
	 * Options:
	 * - 'input' Input file (path or gd resource)
	 * - 'output' Output path. If not specified, gd resource is returned
	 * - 'afterCallbacks' Functions to be executed after this one
	 * - 'quality' Output image quality (JPG only). Value from 0 to 100
	 * - 'compression' Output image compression (PNG only). Value from 0 to 9
	 * - 'chmod' What permissions should be applied to destination image
	 *
	 * @param mixed $options An array of options
	 * @return mixed boolean or GD resource if output was set to null
	 */
	public static function autorotate($options = array()) {
		$options = array_merge(array(
			'afterCallbacks' => null,
			'compression' => null,
			'quality' => null,
			'input' => null,
			'output' => null,
			'chmod' => null
		), $options);

		$type = self::getImageType($options['input']);

		if ($type == 'jpg' && function_exists('exif_read_data')) {
			$exif = exif_read_data($options['input']);
		}

		$src_im = self::openImage($options['input']);
		unset($options['input']);

		if (!$src_im) {
			return false;
		}

		if (!empty($exif['Orientation'])) {
			$orientation = $exif['Orientation'];
		} else if (!empty($exif['IFD0']['Orientation'])) {
			$orientation = $exif['IFD0']['Orientation'];
		} else {
			return self::saveImage($src_im, $options);
		}

    switch ($orientation) {
			case 1:
				return self::saveImage($src_im, $options);
			break;

			case 2: // horizontal flip
				$dst_im = self::flip(array('input' => $src_im, 'mode' => 'horizontal'));
			break;

			case 3: // 180 rotate left
				$dst_im = self::rotate(array('input' => $src_im, 'degrees' => 180));
			break;

			case 4: // vertical flip
				$dst_im = self::flip(array('input' => $src_im, 'mode' => 'vertical'));
			break;

			case 5: // vertical flip + 90 rotate right
				$dst_im = self::flip(array('input' => $src_im, 'mode' => 'vertical'));
				$dst_im = self::rotate(array('input' => $src_im, 'degrees' => 90));
			break;

			case 6: // 90 rotate right
				$dst_im = self::rotate(array('input' => $src_im, 'degrees' => 90));
			break;

			case 7: // horizontal flip + 90 rotate right
				$dst_im = self::flip(array('input' => $src_im, 'mode' => 'horizontal'));
				$dst_im = self::rotate(array('input' => $src_im, 'degrees' => 90));
			break;

			case 8: // 90 rotate left
				$dst_im = self::rotate(array('input' => $src_im, 'degrees' => 270));
			break;

			default:
				return self::saveImage($src_im, $options);
    }

    if (!$dst_im) {
			return false;
		}

		if (!self::afterCallbacks($dst_im, $options['afterCallbacks'])) {
			return false;
		}

    return self::saveImage($dst_im, $options);
	}


	/**
	 * Rotate image by specified angle (only agles divisable by 90 are supported)
	 *
	 * Options:
	 * - 'input' Input file (path or gd resource)
	 * - 'output' Output path. If not specified, gd resource is returned
	 * - 'degrees' Degrees to rotate by (divisible by 90)
	 * - 'afterCallbacks' Functions to be executed after this one
	 * - 'quality' Output image quality (JPG only). Value from 0 to 100
	 * - 'compression' Output image compression (PNG only). Value from 0 to 9
	 * - 'chmod' What permissions should be applied to destination image
	 *
	 * @param mixed $options An array of options
	 * @return mixed boolean or GD resource if output was set to null
	 */
	public static function rotate($options = array()) {
		$options = array_merge(array(
			'afterCallbacks' => null,
			'compression' => null,
			'quality' => null,
			'input' => null,
			'output' => null,
			'degrees' => 'horizontal',
			'chmod' => null
		), $options);

		$src_im = self::openImage($options['input']);
		unset($options['input']);

		if (!$src_im) {
			return false;
		}

		$w = imagesx($src_im);
		$h = imagesy($src_im);

		switch ($options['degrees']) {
			case 90:
				$dst_im = imagecreatetruecolor($h, $w);
			break;

			case 180:
				$dst_im = imagecreatetruecolor($w, $h);
			break;

			case 270:
				$dst_im = imagecreatetruecolor($h, $w);
			break;

			case 360:
			case 0:
				return self::saveImage($src_im, $options);
			break;

			default:
				return false;
		}

		if (!$dst_im) {
			return false;
		}

		for ($i=0; $i<$w; $i++) {
			for ($j=0; $j<$h; $j++) {
				$reference = imagecolorat($src_im, $i, $j);
				switch ($options['degrees']) {
					case 90:
						if (!@imagesetpixel($dst_im, ($h-1)-$j, $i, $reference)) {
							return false;
						}
					break;

					case 180:
						if (!@imagesetpixel($dst_im, $w-$i, ($h-1)-$j, $reference)) {
							return false;
						}
					break;

					case 270:
						if (!@imagesetpixel($dst_im, $j, $w-$i, $reference)) {
							return false;
						}
					break;
				}
			}
		}

		if (!self::afterCallbacks($dst_im, $options['afterCallbacks'])) {
			return false;
		}

		return self::saveImage($dst_im, $options);
	}

	/**
	 * Flip image
	 *
	 * Options:
	 * - 'input' Input file (path or gd resource)
	 * - 'output' Output path. If not specified, gd resource is returned
	 * - 'mode' Flip mode: horizontal, vertical, both
	 * - 'afterCallbacks' Functions to be executed after this one
	 * - 'quality' Output image quality (JPG only). Value from 0 to 100
	 * - 'compression' Output image compression (PNG only). Value from 0 to 9
	 * - 'chmod' What permissions should be applied to destination image
	 *
	 * @param mixed $options An array of options
	 * @return mixed boolean or GD resource if output was set to null
	 */
	public static function flip($options = array()) {
		$options = array_merge(array(
			'afterCallbacks' => null,
			'compression' => null,
			'quality' => null,
			'input' => null,
			'output' => null,
			'mode' => 'horizontal',
			'chmod' => null
		), $options);

		$src_im = self::openImage($options['input']);
		unset($options['input']);

		if (!$src_im) {
			return false;
		}

		$w = imagesx($src_im);
		$h = imagesy($src_im);
		$dst_im = imagecreatetruecolor($w, $h);

		switch ($options['mode']) {
			case 'horizontal':
				for ($x=0 ; $x<$w ; $x++) {
					for ($y=0 ; $y<$h ; $y++) {
						imagecopy($dst_im, $src_im, $w-$x-1, $y, $x, $y, 1, 1);
					}
				}
			break;

			case 'vertical':
				for ($x=0 ; $x<$w ; $x++) {
					for ($y=0 ; $y<$h ; $y++) {
						imagecopy($dst_im, $src_im, $x, $h-$y-1, $x, $y, 1, 1);
					}
				}
			break;

			case 'both':
				for ($x=0 ; $x<$w ; $x++) {
					for ($y=0 ; $y<$h ; $y++) {
						imagecopy($dst_im, $src_im, $w-$x-1, $h-$y-1, $x, $y, 1, 1);
					}
				}
			break;

			default:
				return false;
		}

		if (!self::afterCallbacks($dst_im, $options['afterCallbacks'])) {
			return false;
		}

		return self::saveImage($dst_im, $options);
	}

	/**
	 * PNG ALPHA CHANNEL SUPPORT for imagecopymerge();
	 * This is a f-ion like imagecopymerge but it handle alpha channel well!!!
	 **/
	public static function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct){
		// getting the watermark width
		$w = imagesx($src_im);
		// getting the watermark height
		$h = imagesy($src_im);

		// creating a cut resource
		$cut = imagecreatetruecolor($src_w, $src_h);
		// copying that section of the background to the cut
		imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);

		// placing the watermark now
		imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
		return imagecopymerge($dst_im, $cut, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct);
	}

	/**
	 * Pixelate image
	 *
	 * Options:
	 * - 'input' Input file (path or gd resource)
	 * - 'output' Output path. If not specified, gd resource is returned
	 * - 'blocksize' Size of each pixel
	 * - 'afterCallbacks' Functions to be executed after this one
	 * - 'quality' Output image quality (JPG only). Value from 0 to 100
	 * - 'compression' Output image compression (PNG only). Value from 0 to 9
	 * - 'chmod' What permissions should be applied to destination image
	 *
	 * @param mixed $options An array of options
	 * @return mixed boolean or GD resource if output was set to null
	 */
	public static function pixelate($options) {
		$options = array_merge(array(
			'afterCallbacks' => null,
			'compression' => null,
			'quality' => null,
			'blocksize' => 10,
			'output' => null,
			'input' => null,
			'chmod' => null
		), $options);

		$img = self::openImage($options['input']);
		unset($options['input']);

		if (!$img) {
			return false;
		}

		$w = imagesx($img);
		$h = imagesy($img);

		for($x=0; $x<$w; $x+=$options['blocksize']) {
			for($y=0; $y<$h; $y+=$options['blocksize']) {
				$colors = array(
					'alpha' => 0,
					'red' => 0,
					'green' => 0,
					'blue' => 0,
					'total' => 0
				);

				for ($block_x = 0 ; $block_x < $options['blocksize'] ; $block_x++) {
					for ($block_y = 0 ; $block_y < $options['blocksize'] ; $block_y++) {
						$current_block_x = $x + $block_x;
						$current_block_y = $y + $block_y;

						if ($current_block_x >= $w || $current_block_y >= $h) {
							continue;
						}

						$color = imagecolorat($img, $current_block_x, $current_block_y);
						imagecolordeallocate($img, $color);

						$colors['alpha'] += ($color >> 24) & 0xFF;
						$colors['red'] += ($color >> 16) & 0xFF;
						$colors['green'] += ($color >> 8) & 0xFF;
						$colors['blue'] += $color & 0xFF;
						$colors['total']++;
					}
				}

				$color = imagecolorallocatealpha(
					$img,
					$colors['red'] / $colors['total'],
					$colors['green'] / $colors['total'],
					$colors['blue'] / $colors['total'],
					$colors['alpha'] / $colors['total']
				);

				imagefilledrectangle($img, $x, $y, ($x + $options['blocksize'] - 1), ($y + $options['blocksize'] - 1), $color);
			}
		}

		if (!self::afterCallbacks($img, $options['afterCallbacks'])) {
			return false;
		}

		return self::saveImage($img, $options);
	}

	/**
	 * Perform afterCallbacks on specified image
	 *
	 * @param resource $im Image to perform callback on
	 * @param mixed $functions Callback functions and their arguments
	 * @return boolean
	 */
	public static function afterCallbacks(&$im, $functions) {
		if (empty($functions)) {
			return true;
		}

		foreach ($functions as $v) {
			$v[1]['input'] = $im;

			$im = self::$v[0]($v[1]);

			if (!$im) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Read color (convert various formats into rgb)
	 *
	 * Supported values: rgb (array), hex (string), int (integer)
	 *
	 * @param mixed $color Input color
	 * @return array Array of rgb values
	 */
	public static function readColor($color) {
		if (is_array($color)) {
			if (count($color) == 3) {
				return $color;
			}
		} else if (is_string($color)) {
			return self::hex2rgb($color);
		} else if (is_int($color)) {
			return self::hex2rgb(dechex($color));
		}

		return false;
	}

}

?>