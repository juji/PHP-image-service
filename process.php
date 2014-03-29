<?php

function gd_resize($image_path, $name, $rwidth=false, $rheight=false){
	
	list($width, $height) = getimagesize($image_path);
	
	if( !$rwidth ) $rwidth = $rheight * $width / $height;
	if( !$rheight ) $rheight = $rwidth * $height / $width;
	
	if( $rwidth > $width && $rheight > $height){
		return $image_path;
	}
	else if( $rwidth > $width ) $rwidth = $width;
	else if( $rheight > $height ) $rheight = $height;
		
	
	$im = imagecreatetruecolor($rwidth, $rheight);
	$src = imagecreatefromstring(file_get_contents($image_path));
	imagecopyresampled($im, $src, 0, 0, 0, 0, $rwidth, $rheight, $width, $height);
	
	imagejpeg($im, $name, 100);
	imagedestroy($im);
	imagedestroy($src);
	
	return $name;
}

function gd_crop($image_path, $name, $rwidth=false, $rheight=false, $center='center'){
	
	list($width, $height) = getimagesize($image_path);
	if(!$rwidth) $rwidth = $rheight * $width / $height;
	if(!$rheight) $rheight = $rwidth * $height / $width;
	
	$cpoint_s = array($width/2,$height/2);
	$cpoint_r = array($rwidth/2,$rheight/2);
	
	if(strpos($center,'.') !== false){
		$pos = explode('.',$center);
		$left = $pos[0];
		$top = $pos[1];
	}else{
		switch($center){
			case 'center': $left = $cpoint_s[0]; $top = $cpoint_s[1]; break;
			case 'left': $left = $cpoint_r[0]; $top = $cpoint_s[1]; break;
			case 'right': $left = $width - $cpoint_r[0]; $top = $cpoint_s[1]; break;
			case 'top': $left = $cpoint_s[0]; $top = $cpoint_r[1]; break;
			case 'bottom': $left = $cpoint_s[0]; $top = $height - $cpoint_r[1]; break;
			case 'left-top': $left=$cpoint_r[0]; $top=$cpoint_r[1]; break;
			case 'left-bottom': $left=$cpoint_r[0]; $top = $height - $cpoint_r[1]; break;
			case 'right-top': $left = $width - $cpoint_r[0]; $top = $cpoint_r[1]; break;
			case 'right-bottom': $left = $width - $cpoint_r[0]; $top = $height - $cpoint_r[1]; break;
			default: return false;
		}
	}
	
	//convert center to position
	$left -= $cpoint_r[0];
	$top -= $cpoint_r[1];
	
	//normalize position and dimension
	if($top<0){ $rheight += $top; $top = 0; }
	if($left<0){ $rwidth += $left; $left = 0; }
	
	//normalize dimension
	if(($top+$rheight)>$height) { $rheight = $height - $top; }
	if(($left+$rwidth)>$width) { $rwidth = $width - $left; }
	
	$im = imagecreatetruecolor($rwidth, $rheight);
	$src = imagecreatefromstring(file_get_contents($image_path));
	imagecopyresampled($im, $src, 0, 0, $left, $top, $rwidth, $rheight, $rwidth, $rheight);
	
	imagejpeg($im, $name, 100);
	imagedestroy($im);
	imagedestroy($src);
	return $name;
}

/**
 * http://marchibbins.com/dev/gd/
 * @author Marc Hibbins
 */

/** Apply and deliver the image and clean up */
function gd_filter_image($image_path, $name, $filter_name)
{
	
	$filter = 'gd_filter_' . $filter_name;
	if (function_exists($filter)) {
		list($width, $height) = getimagesize($image_path);
		
		$im = imagecreatetruecolor($width, $height);
		$src = imagecreatefromstring(file_get_contents($image_path));
		imagecopyresampled($im, $src, 0, 0, 0, 0, $width, $height, $width, $height);
		
		$im = $filter($im);
		
		imagejpeg($im, $name, 100);
		imagedestroy($im);
		imagedestroy($src);
		return $name;
	}
	return false;
}

/** Apply 'Dreamy' preset */
function gd_filter_dreamy($im)
{
	imagefilter($im, IMG_FILTER_BRIGHTNESS, 20);
	imagefilter($im, IMG_FILTER_CONTRAST, -35);
	imagefilter($im, IMG_FILTER_COLORIZE, 60, -10, 35);
	imagefilter($im, IMG_FILTER_SMOOTH, 7);
	$im = gd_apply_overlay($im, 'scratch', 10);
	$im = gd_apply_overlay($im, 'vignette', 100);
	return $im;
}

/** Apply 'Blue Velvet' preset */
function gd_filter_velvet($im)
{
	imagefilter($im, IMG_FILTER_BRIGHTNESS, 5);
	imagefilter($im, IMG_FILTER_CONTRAST, -25);
	imagefilter($im, IMG_FILTER_COLORIZE, -10, 45, 65);
	$im = gd_apply_overlay($im, 'noise', 45);
	$im = gd_apply_overlay($im, 'vignette', 100);
	return $im;
}

/** Apply 'Chrome' preset */
function gd_filter_chrome($im)
{
	imagefilter($im, IMG_FILTER_BRIGHTNESS, 15);
	imagefilter($im, IMG_FILTER_CONTRAST, -15);
	imagefilter($im, IMG_FILTER_COLORIZE, -5, -10, -15);
	$im = gd_apply_overlay($im, 'noise', 45);
	$im = gd_apply_overlay($im, 'vignette', 100);
	return $im;
}

/** Apply 'Lift' preset */
function gd_filter_lift($im)
{
	imagefilter($im, IMG_FILTER_BRIGHTNESS, 50);
	imagefilter($im, IMG_FILTER_CONTRAST, -25);
	imagefilter($im, IMG_FILTER_COLORIZE, 75, 0, 25);
	$im = gd_apply_overlay($im, 'emulsion', 100);
	return $im;
}

/** Apply 'Canvas' preset */
function gd_filter_canvas($im)
{
	imagefilter($im, IMG_FILTER_BRIGHTNESS, 25);
	imagefilter($im, IMG_FILTER_CONTRAST, -25);
	imagefilter($im, IMG_FILTER_COLORIZE, 50, 25, -35);
	$im = gd_apply_overlay($im, 'canvas', 100);
	return $im;
}

/** Apply 'Vintage 600' preset */
function gd_filter_vintage($im)
{
	imagefilter($im, IMG_FILTER_BRIGHTNESS, 15);
	imagefilter($im, IMG_FILTER_CONTRAST, -25);
	imagefilter($im, IMG_FILTER_COLORIZE, -10, -5, -15);
	imagefilter($im, IMG_FILTER_SMOOTH, 7);
	$im = gd_apply_overlay($im, 'scratch', 7);
	return $im;
}

/** Apply 'Monopin' preset */
function gd_filter_monopin($im)
{
	imagefilter($im, IMG_FILTER_GRAYSCALE);
	imagefilter($im, IMG_FILTER_BRIGHTNESS, -15);
	imagefilter($im, IMG_FILTER_CONTRAST, -15);
	$im = gd_apply_overlay($im, 'vignette', 100);
	return $im;
}

/** Apply 'Antique' preset */
function gd_filter_antique($im)
{
	imagefilter($im, IMG_FILTER_BRIGHTNESS, 0);
	imagefilter($im, IMG_FILTER_CONTRAST, -30);
	imagefilter($im, IMG_FILTER_COLORIZE, 75, 50, 25);
	return $im;
}

/** Apply 'Black & White' preset */
function gd_filter_blackwhite($im)
{
	imagefilter($im, IMG_FILTER_GRAYSCALE);
	imagefilter($im, IMG_FILTER_BRIGHTNESS, 10);
	imagefilter($im, IMG_FILTER_CONTRAST, -20);
	return $im;
}

/** Apply 'Colour Boost' preset */
function gd_filter_boost($im)
{
	imagefilter($im, IMG_FILTER_CONTRAST, -35);
	imagefilter($im, IMG_FILTER_COLORIZE, 25, 25, 25);
	return $im;
}

/** Apply 'Sepia' preset */
function gd_filter_sepia($im)
{
	imagefilter($im, IMG_FILTER_GRAYSCALE);
	imagefilter($im, IMG_FILTER_BRIGHTNESS, -10);
	imagefilter($im, IMG_FILTER_CONTRAST, -20);
	imagefilter($im, IMG_FILTER_COLORIZE, 60, 30, -15);
	return $im;
}

/** Apply 'Partial blur' preset */
function gd_filter_blur($im)
{
	imagefilter($im, IMG_FILTER_SELECTIVE_BLUR);
	imagefilter($im, IMG_FILTER_GAUSSIAN_BLUR);
	imagefilter($im, IMG_FILTER_CONTRAST, -15);
	imagefilter($im, IMG_FILTER_SMOOTH, -2);
	return $im;
}

/** Apply a PNG overlay */
function gd_apply_overlay($im, $type, $amount)
{
	$width = imagesx($im);
	$height = imagesy($im);
	$filter = imagecreatetruecolor($width, $height);
	
	imagealphablending($filter, false);
	imagesavealpha($filter, true);
	
	$transparent = imagecolorallocatealpha($filter, 255, 255, 255, 127);
	imagefilledrectangle($filter, 0, 0, $width, $height, $transparent);
	
	$overlay = 'filters/' . $type . '.png';
	$png = imagecreatefrompng($overlay);
	imagecopyresampled($filter, $png, 0, 0, 0, 0, $width, $height, $width, $height);
	
	$comp = imagecreatetruecolor($width, $height);
	imagecopy($comp, $im, 0, 0, 0, 0, $width, $height);
	imagecopy($comp, $filter, 0, 0, 0, 0, $width, $height);
	imagecopymerge($im, $comp, 0, 0, 0, 0, $width, $height, $amount);
	
	imagedestroy($comp);
	return $im;
}
