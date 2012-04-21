<?php

defined('COT_CODE') or die('Wrong URL');

global $catd_set;
if (!isset($catd_set))
{
	$val = $cfg['plugin']['catdesc']['set'];

		$val = explode('|', $val);
		$val = array_map('trim', $val);
		if (!empty($val[0]))
		{
			$thumbs = array();
			if (!empty($val[1]) > 0)
			{
				$varfields = explode(' ', $val[1]);
				foreach ($varfields as $val2)
				{
					$val2 = explode('-', $val2);
					$val2[3] = (!in_array($val2[3], array('crop', 'height', 'width'))) ? 'auto' : $val2[3];
					$thumbs[$val2[0]] = array('x' => (int)$val2[1], 'y' => (int)$val2[2], 'set' => $val2[3]);
				}
			}

			$val[0] = (!empty($val[0])) ? $val[0] : 'datas/photos';
			$val[0] .= (substr($val[0], -1) == '/') ? '' : '/';
			$catd_set = array(
				'path' => $val[0],
				'thumbs' => $thumbs,
				'ext' => (!empty($val[2])) ? explode(' ', $val[2]) : array('jpg', 'jpeg', 'png', 'gif'),
				'max' => ((int)$val[3] > 0) ? $val[3] : 0
			);
		}
}

if (!function_exists(cot_thumb))
{

	/**
	 * Creates image thumbnail
	 *
	 * @param string $source Original image path
	 * @param string $target Thumbnail path
	 * @param int $width Thumbnail width
	 * @param int $height Thumbnail height
	 * @param string $resize resize options: crop auto width height
	 * @param int $quality JPEG quality in %
	 */
	function cot_thumb($source, $target, $width, $height, $resize = 'crop', $quality = 85)
	{
		$ext = strtolower(pathinfo($source, PATHINFO_EXTENSION));
		list($width_orig, $height_orig) = getimagesize($source);
		$x_pos = 0;
		$y_pos = 0;

		if ($resize == 'crop')
		{
			$newimage = imagecreatetruecolor($width, $height);
			$width_temp = $width;
			$height_temp = $height;

			if ($width_orig / $height_orig > $width / $height)
			{
				$width = $width_orig * $height / $height_orig;
				$x_pos = -($width - $width_temp) / 2;
				$y_pos = 0;
			}
			else
			{
				$height = $height_orig * $width / $width_orig;
				$y_pos = -($height - $height_temp) / 2;
				$x_pos = 0;
			}
		}
		else
		{
			if ($resize == 'auto')
			{
				if ($width_orig < $width && $height_orig < $height)
				{
					$width = $width_orig;
					$height = $height_orig;
				}
				else
				{
					if ($width_orig / $height_orig > $width / $height)
					{
						$height = $width * $height_orig / $width_orig;
					}
					else
					{
						$width = $height * $width_orig / $height_orig;
					}
				}
			}

			if ($resize == 'width')
			{
				if ($width_orig > $width)
				{
					$height = $height_orig * $width / $width_orig;
				}
				else
				{
					$width = $width_orig;
					$height = $height_orig;
				}
			}

			if ($resize == 'height')
			{
				if ($height_orig > $height)
				{
					$width = $width_orig * $height / $height_orig;
				}
				else
				{
					$width = $width_orig;
					$height = $height_orig;
				}
			}
			$newimage = imagecreatetruecolor($width, $height);//
		}

		switch ($ext)
		{
			case 'gif':
				$oldimage = imagecreatefromgif($source);
				break;
			case 'png':
				imagealphablending($newimage, false);
				imagesavealpha($newimage, true);
				$oldimage = imagecreatefrompng($source);
				break;
			default:
				$oldimage = imagecreatefromjpeg($source);
				break;
		}

		imagecopyresampled($newimage, $oldimage, $x_pos, $y_pos, 0, 0, $width, $height, $width_orig, $height_orig);

		switch ($ext)
		{
			case 'gif':
				imagegif($newimage, $target);
				break;
			case 'png':
				imagepng($newimage, $target);
				break;
			default:
				imagejpeg($newimage, $target, $quality);
				break;
		}

		imagedestroy($newimage);
		imagedestroy($oldimage);
	}

}
?>
