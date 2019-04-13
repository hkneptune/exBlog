<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Verify image Generator
 * Copyright (C) 2005, 2006  exSoft.net
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 *  File: $RCSfile$
 *  Author: feeling <feeling@exblog.org>
 *  Last Modified: $Author$
 *  Date: $Date$
 *  Homepage: www.exblog.net
 *
 * $Id$
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class VerifyImage
{
	var $random_length = 5;
	var $verify_string = '';
	var $rand_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	var $bg_color = array();
	var $front_color = array();

	function VerifyImage()
	{
		if (FALSE == empty($_GET['length']) && TRUE == is_numeric($_GET['length'])) $this->random_length = intval($_GET['length']);

		if (TRUE == isset($_GET['bgr']) && TRUE == is_numeric($_GET['bgr']))
		{
			$tmp = intval($_GET['bgr']);
			if ($tmp >= 0 && $tmp <= 255) $this->bg_color[0] = $tmp;
		} 
		if (TRUE == isset($_GET['bgy']) && TRUE == is_numeric($_GET['bgy']))
		{
			$tmp = intval($_GET['bgy']);
			if ($tmp >= 0 && $tmp <= 255) $this->bg_color[1] = $tmp;
		} 
		if (TRUE == isset($_GET['bgb']) && TRUE == is_numeric($_GET['bgb']))
		{
			$tmp = intval($_GET['bgb']);
			if ($tmp >= 0 && $tmp <= 255) $this->bg_color[2] = $tmp;
		} 
		if (TRUE == isset($_GET['fr']) && TRUE == is_numeric($_GET['fr']))
		{
			$tmp = intval($_GET['fr']);
			if ($tmp >= 0 && $tmp <= 255) $this->front_color[0] = $tmp;
		} 
		if (TRUE == isset($_GET['fy']) && TRUE == is_numeric($_GET['fy']))
		{
			$tmp = intval($_GET['fy']);
			if ($tmp >= 0 && $tmp <= 255) $this->front_color[1] = $tmp;
		} 
		if (TRUE == isset($_GET['fb']) && TRUE == is_numeric($_GET['fb']))
		{
			$tmp = intval($_GET['fb']);
			if ($tmp >= 0 && $tmp <= 255) $this->front_color[2] = $tmp;
		} 

		$this->outputImage();
		if (FALSE == empty($_GET['type'])) $this->saveVerifyString($_GET['type']);
		exit();
	} 

	function outputImage()
	{
		mt_srand((double)microtime() * 1000000);
		$verify_string = $this->generate();
		$x_length = $this->random_length * 7 + 10;
		$y_length = 15;
		$image_id = imagecreate($x_length, $y_length);
		if (3 <= count($this->bg_color)) $bg = imagecolorallocate($image_id, $this->bg_color[0], $this->bg_color[1], $this->bg_color[2]);
		else $bg = imagecolorallocate($image_id, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
		if (3 <= count($this->front_color)) $textcolor = imagecolorallocate($image_id, $this->front_color[0], $this->front_color[1], $this->front_color[2]);
		else $textcolor = imagecolorallocate($image_id, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
		imagefill($image_id, $x_length, $y_length, $bg);
		imagestring($image_id, 3, 5, 0, $verify_string, $textcolor);
		for($i = 0;$i < 100;$i++)
		{
			$rand_color = ImageColorallocate($image_id, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
			imagesetpixel($image_id, mt_rand() % 70 , mt_rand() % 30 , $rand_color);
		} 
		header("Content-type: image/png");
		imagepng($image_id);
		imagedestroy($image_id);
	} 

	function generate($salt = "")
	{
		mt_srand((double)microtime() * 1000000);
		for ($i = $this->random_length; $i > strlen($salt);) $salt .= $this->rand_chars[mt_rand(0, strlen($this->rand_chars) - 1)];
		$this->verify_string = $salt;
		return $this->verify_string;
	} 

	function saveVerifyString($type = "login")
	{
		@session_start();
		$_SESSION['verify_string'][$type] = $this->verify_string;
	} 
} 

new VerifyImage();

?>
