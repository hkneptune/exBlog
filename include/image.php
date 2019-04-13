<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *
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

/**
 * ~chinese 图像文件维护工具
 * 
 * @class Image image.php "common/image.php"
 * @brief 
 * @author feeling <feeling@4ulm.org> 
 * @date $Date$
 */

class Image
{
	/**
	 * ~chinese 目标图像文件
	 * 
	 * @brief 
	 */

	var $filename = "";

	/**
	 * ~chinese 目标图像文件属性列表。其中包括以下几个索引：
	 * ~chinese 目标图像文件宽度
	 * ~chinese 目标图像文件高度
	 * ~chinese 目标图像文件类型
	 * ~chinese 目标图像文件其他属性
	 * 
	 * @brief 
	 * @li width
	 * @li height
	 * @li type
	 * @li attr
	 */

	var $property = array();

	/**
	 * ~chinese 目标图像文件的长宽。其中包括 width 和 height 两个索引
	 * 
	 * @brief 
	 */

	var $size = array();

	/**
	 * ~chinese 图像缩放的比率，保持高宽比率
	 * 
	 * @brief 
	 */

	var $resize_rate = 100;

	/**
	 * ~chinese 图像缩放的长宽比率。其中包括两个元素，第一个为宽度比率，第二个为高度比率
	 * 
	 * @brief 
	 */

	var $resize_numeric = array();

	/**
	 * ~chinese 当前操作图像文件的扩展名
	 * 
	 * @internal 
	 * @brief 
	 */

	var $ext = "";

	/**
	 * ~chinese 是否输出到文件。FALSE ＝ 不输出到文件，TRUE ＝ 输出到文件
	 * 
	 * @brief 
	 */

	var $output = FALSE;

	/**
	 * ~chinese 新建图像文件名称
	 * 
	 * @brief 
	 */

	var $new_name = "";

	/**
	 * ~chinese 当前操作图像文件句柄
	 * 
	 * @internal 
	 * @brief 
	 */

	var $image_id = 0;

	/**
	 * ~chinese 在图像文件中插入字符串时可能使用到的需要嵌入的 TTF 字体文件
	 * 
	 * @brief 
	 */

	var $ttf_font = "/var/www/data/fonts/simsun.ttf";

	/**
	 * ~chinese 构造器
	 * ~chinese 指定需要操作的目标图像文件
	 * 
	 * @brief 
	 * @param  $filename 
	 */

	function Image($filename = "")
	{
		if (FALSE == empty($filename)) $this->filename = $filename;

		$this->new_name = $this->filename;
	} 

	/**
	 * ~english Get property of a image file
	 * ~chinese 获取图像文件的属性
	 * ~english target file
	 * ~chinese 目标文件
	 * ~english FALSE when something was wrong, else return a array with indices as below
	 * ~chinese 如果出现错误则返回 FALSE，否则返回一个包括以下索引的数组：
	 * ~english Width of this file
	 * ~chinese 文件的宽度
	 * ~english Height of this file
	 * ~chinese 文件的高度
	 * ~english Type of this file
	 * ~chinese 文件的类型
	 * 
	 * @brief 
	 * @param  $filename 
	 * @return 
	 * @li width
	 * @li height
	 * @li type
	 * @li attr
	 */

	function getProperty($filename = "")
	{
		if (TRUE == empty($filename)) $filename = $this->filename;

		if (list($width, $height, $type, $attr) = getimagesize($filename))
		{
			$this->property = array("width" => $width, "height" => $height, "type" => $type, "attr" => $attr);

			return $this->property;
		} 

		else return FALSE;
	} 

	/**
	 * ~english Get width and height of a image file
	 * ~chinese 获取图像文件的宽度和高度
	 * ~english A reference of a image file created by imagecreate()
	 * ~chinese 由 imagecreate() 创建的图像文件指针
	 * ~english FALSE when something was wrong, else return a array with indices "width" and "height"
	 * ~chinese 如果出现错误则返回 FALSE，否则将返回包括 "width" 和 "height" 两个索引的数组
	 * 
	 * @brief 
	 * @param  $image_id 
	 * @return 
	 */

	function getSize($image_id = "")
	{
		if (!is_resource($image_id)) $image_id = $this->image_id;

		if (($width = imagesx($image_id)) && ($height = imagesy($image_id)))
		{
			$this->size = array("width" => $width, "height" => $height);

			return $this->size;
		} 

		else return FALSE;
	} 

	/**
	 * ~english Get type of a image file
	 * ~chinese 获取图像文件的类型
	 * ~english the target file
	 * ~chinese 目标文件
	 * ~english FALSE when something was wrong, else return type of this file
	 * ~chinese 如果出现错误则返回 FALSE，否则将返回本文件的类型
	 * 
	 * @brief 
	 * @param  $filename 
	 * @return 
	 */

	function getType($filename = "")
	{
		if (TRUE == empty($filename)) $filename = $this->filename;

		/*		if (count($this->property) < 1) $property = $this->getProperty($filename);

		else $property = $this->property;

*/ $property = $this->getProperty($filename);

		if (is_array($property)) return $property["type"];

		else return FALSE;
	} 

	/**
	 * ~english Get extended name of a file
	 * ~chinese 获取指定文件的扩展名
	 * ~english  the target file
	 * ~chinese 目标文件
	 * 
	 * @brief 
	 * @param  $filename 
	 */

	function getExt($filename = "")
	{
		if (empty($filename)) $filename = $this->filename;

		$this->ext = substr($filename, strrpos($filename, ".") + 1);

		return $this->ext;
	} 

	/**
	 * ~english Set rate for resizing a image file
	 * ~chinese 设置缩放图像文件的比率
	 * ~english rate for resizing, if it was a array, do as new width and new height
	 * ~chinese 缩放比率，如果其为数组，则作为新的宽度和高度
	 * ~english always TRUE
	 * ~chinese 总是返回 TRUE
	 * 
	 * @brief 
	 * @param  $resize_rate 
	 * @return 
	 */

	function setResize($resize_rate)
	{
		if (is_array($resize_rate) && count($resize_rate) > 1) $this->resize_numeric = array($resize_rate[0], $resize_rate[1]);

		elseif (!is_array($resize_rate) && is_numeric($resize_rate)) $this->resize_rate = intval($resize_rate);

		else $this->resize_rate = 100;

		return TRUE;
	} 

	/**
	 * ~english Set extend name of a file
	 * ~chinese 设置文件的扩展名
	 * ~english New extend name
	 * ~chinese 新的扩展名
	 * ~english Null
	 * ~chinese 空
	 * 
	 * @brief 
	 * @param  $new_ext 
	 * @return 
	 */

	function setExt($new_ext)
	{
		$this->ext = $new_ext;
	} 

	/**
	 * ~english Set new name of a file
	 * ~chinese 设置文件的新名称
	 * ~english New name
	 * ~chinese 新文件名
	 * ~english Null
	 * ~chinese 空
	 * 
	 * @brief 
	 * @param  $new_name 
	 * @return 
	 */

	function setName($new_name)
	{
		$file_ext = $this->getExt();

		$this->new_name = $new_name . "." . $file_ext;
	} 

	/**
	 * ~english Open a image file
	 * ~chinese 打开图像文件
	 * ~english target file
	 * ~chinese 目标文件
	 * ~english a reference when successful, else FALSE
	 * ~chinese 如果成功则放回一个指针，否则返回 FALSE
	 * 
	 * @brief 
	 * @param  $filename 
	 * @return 
	 */

	function open($filename = "")
	{
		if (empty($filename)) $filename = $this->filename;

		$file_type = $this->getType($filename);

		switch ($file_type)
		{
			case IMAGETYPE_GIF: $fun_name = "imagecreatefromgif";
				break;

			case IMAGETYPE_JPEG: $fun_name = "imagecreatefromjpeg";
				break;

			case IMAGETYPE_PNG: $fun_name = "imagecreatefrompng";
				break;

			case IMAGETYPE_WBMP: $fun_name = "imagecreatefromwbmp";
				break;

			default: $fun_name = "imagecreatefromgd2";
				break;
		} 

		$file_id = $fun_name($filename);

		if (TRUE == is_resource($file_id)) return $file_id;

		else return FALSE;
	} 

	/**
	 * ~english Create a image file
	 * ~chinese 创建一个图像文件
	 * ~english optional, only for JPEG, and ranges from 0 (worst quality, smaller file) to 100(best quality, biggest file)
	 * ~chinese 可选，只针对 JPEG，同时返回从0（质量最差，文件较小）到100（质量最好，文件最大）
	 * ~english TRUE when successful, else FALSE
	 * ~chinese 如果成功则返回 TRUE，否则返回 FALSE
	 * 
	 * @private 
	 * @internal 
	 * @brief 
	 * @param  $file_id 
	 * @param  $quality 
	 * @return 
	 */

	function _output(&$file_id, $quality = 75)
	{
		$file_ext = $this->getExt($this->new_name);

		switch ($file_ext)
		{
			case "gif": $fun_name = "imagegif";
				break;

			case "png": $fun_name = "imagepng";
				break;

			case "wbmp": $fun_name = "imagewbmp";
				break;

			default: $fun_name = "imagejpeg";
				break;
		} 

		if (TRUE == is_resource($file_id))
		{
			if (FALSE == $this->output)
			{
				if ($fun_name !== "imagejpeg") $fun_name($file_id);

				else $fun_name($file_id, '', $quality);
			} 

			else
			{
				if ($fun_name !== "imagejpeg") $fun_name($file_id, $this->new_name);

				else $fun_name($file_id, $this->new_name, $quality);
			} 
		} 

		else return FALSE;
	} 

	/**
	 * ~english resize a image file
	 * ~chinese 缩放图像文件
	 * ~english the filename to resize
	 * ~chinese 需要缩放的文件名
	 * ~english the filename to save
	 * ~chinese 保存到的目标文件名
	 * ~english optional, only for JPEG, and ranges from 0 (worst quality, smaller file) to 100(best quality, biggest file)
	 * ~chinese 可选，只针对 JPEG，同时返回从0（质量最差，文件较小）到100（质量最好，文件最大）
	 * ~english TRUE when successful, else FALSE
	 * ~chinese 如果成功则返回 TRUE，否则返回 FALSE
	 * 
	 * @brief 
	 * @param  $filename 
	 * @param  $new_filename 
	 * @param  $quality 
	 * @return 
	 */

	function resize($filename = "", $new_filename = "", $quality = 75)
	{
		if (TRUE == empty($filename)) $filename = $this->filename;

		if (FALSE == empty($new_filename)) $this->new_name = $new_filename . "." . $this->getExt($filename);

		$property = $this->getProperty($filename);

		if (count($this->resize_numeric) > 1) $size = $this->resize_numeric;

		else $size = array($property["width"] * $this->resize_rate / 100, $property["height"] * $this->resize_rate / 100);

		$src_file_id = $this->open($filename);

		$dst_file_id = imagecreatetruecolor($size[0], $size[1]);

		imagecopyresampled($dst_file_id, $src_file_id, 0, 0, 0, 0, $size[0], $size[1], $property["width"], $property["height"]);

		$result = $this->_output($dst_file_id, $quality);

		imagedestroy($src_file_id);

		imagedestroy($dst_file_id);

		return $result;
	} 

	/**
	 * ~english Create bar with some datas
	 * ~chinese 根据指定数据创建柱形图
	 * ~english datas to reference
	 * ~chinese 需要参考的数据列表
	 * ~english titles of datas
	 * ~chinese 数据主题列表
	 * ~english a list of a unit number.
	 * ~chinese 单元数字列表
	 * ~english It should include indexes as below:
	 * ~chinese 其应该包括以下索引：
	 * ~english default was 30
	 * ~chinese 缺省为 30
	 * ~english default was 20
	 * ~chinese 缺省为 20
	 * ~english default was 10
	 * ~chinese 缺省为 10
	 * ~english default was 1
	 * ~chinese 缺省为 1
	 * ~english optional, only for JPEG, and ranges from 0 (worst quality, smaller file) to 100(best quality, biggest file)
	 * ~chinese 可选，只针对 JPEG，同时返回从0（质量最差，文件较小）到100（质量最好，文件最大）
	 * ~english filename for extended characters (NO DOCUMENTED!)
	 * ~chinese 扩展字符文件名（尚未文档）
	 * ~english TRUE when successful, else FALSE
	 * ~chinese 如果成功则返回 TRUE，否则返回 FALSE
	 * 
	 * @brief 
	 * @param  $datas 
	 * @param  $names 
	 * @param  $per_perpety 
	 * @n 
	 * @li per_w
	 * @li per_h
	 * @li per_n
	 * @li per_p
	 * @li quality
	 * @param  $filename 
	 * @param  $extend_char_file 
	 * @return 
	 */

	function createBar($datas, $names, $per_perpety, $filename = "", $extend_char_file = FALSE)
	{
		if ($extend_char_file) include_once($extend_char_file);

		$per_w = (array_key_exists("per_w", $per_perpety)) ? intval($per_perpety["per_w"]) : 30;

		$per_h = (array_key_exists("per_h", $per_perpety)) ? intval($per_perpety["per_h"]) : 20;

		$per_n = (array_key_exists("per_n", $per_perpety)) ? intval($per_perpety["per_n"]) : 10;

		$per_p = (array_key_exists("per_p", $per_perpety)) ? intval($per_perpety["per_p"]) : 1;

		$number = count($datas);

		$img_width = ($number + 2) * per_w;

		$img_height = $per_h * $per_n;

		$img_width_total = $img_w;

		$img_width_total = $img_h + 30;

		$image_id = imagecreate($img_width_total, $img_height_total);

		$color_back = imagecolorallocate($image, 0, 0, 0);

		$color_table = imagecolorallocate($image, 188, 188, 188);

		$color_pole_b = imagecolorallocate($image, 204, 0, 255);

		$color_pole_f = imagecolorallocate($image, 152, 0, 188);

		$color_pole_s = imagecolorallocate($image, 231, 132, 255);

		$color_text = imagecolorallocate($image, 255, 217, 38);

		imagefilledrectangle($image_id, 0, 0, $img_width_total, $img_height_total, $color_back);

		$style = array($color_table, $color_table, $color_back, $color_back, $color_back, $color_back);

		imagesetstyle($image, $style);

		for ($i = 0; $i < $img_height / $per_h; $i++)
		{
			imageline($image_id, $per_w, $i * $per_h, $img_width - $per_w, $i * $per_h, IMG_COLOR_STYLED);

			imageline($image_id, $per_w, $i * $per_h, $per_w / 2, $i * $per_h + $per_h * 2 / 3 - 1, IMG_COLOR_STYLED);

			$str_score = $per_h * ($img_height / $per_h - $i - 1) * $per_p;

			imagestring($image_id, 3, $img_width - $per_w + 6, $i * $per_h - 11, $str_score, $color_table);
		} 

		imageline($image_id, $per_w, 0, $img_width - $per_w, 0, $color_table);

		imageline($image_id, $per_w, $per_h * ($per_n - 1), $img_width - $per_w, $per_h * ($per_n - 1), $color_table);

		imageline($image_id, $per_w / 2, $img_height - $per_h / 3 - 1, $img_width - $per_w * 3 / 2, $img_height - $per_h / 3 - 1, $color_table);

		imageline($image_id, $per_w / 2, $per_h * 2 / 3, $per_w / 2, $img_height - $per_h / 3 - 1, $color_table);

		imageline($image_id, $per_w, 0, $per_w / 2, $per_h * 2 / 3 - 1, $color_table);

		imageline($image_id, $per_w, $per_h * ($per_n - 1), $per_w / 2, $img_height - $per_h / 3 - 1, $color_table);

		imageline($image_id, $img_width - $per_w, $per_h * ($per_n - 1), $img_width - $per_w / 2 - $per_w, $img_height - $per_h / 3 - 1, $color_table);

		imageline($image_id, $img_width - $per_w, 0, $img_width - $per_w, $img_height - $per_h, $color_table);

		imageline($image_id, $per_w, 0, $per_w, $img_height - $per_h, $color_table);

		for ($i = 0; $i < $number; $i++)
		{
			$pn = array(

				($i + 1) * $per_w,

				$img_height - $datas[$i] / $per_p - $per_h * 2 / 3,

				($i + 1) * $per_w,

				$img_height - $per_h * 2 / 3 - 1,

				($i + 2) * $per_w - $per_w / 4 - 1,

				$img_height - 1 - $per_h * 2 / 3,

				($i + 2) * $per_w - 1,

				$img_height - 1 - $per_h,

				($i + 2) * $per_w - 1,

				$img_h - $datas[$i] / $per_p - $per_h + 1,

				($i + 1) * $per_w + $per_w / 4,

				$img_height - $datas[$i] / $per_p - $per_h + 1

				);

			imagefilledpolygon($image_id, $pn, 6, $color_pole_b);

			$ps = array(

				($i + 1) * $per_w,

				$img_height - $datas[$i] / $per_p - $per_h * 2 / 3,

				($i + 2) * $per_w - $per_w / 4 - 1,

				$img_height - $datas[$i] / $per_p - $per_h * 2 / 3,

				($i + 2) * $per_w - 2,

				$img_height - $datas[$i] / $per_p - $per_h + 1,

				($i + 1) * $per_w + $per_w / 4,

				$img_height - $datas[$i] / $per_p - $per_h + 1

				);

			imagefilledpolygon($image_id, $ps, 4, $color_pole_s);

			imagefilledrectangle($image_id, ($i + 1) * $per_w,

				$img_height - $datas[$i] / $per_p - $per_h * 2 / 3 + 1,

				($i + 2) * $per_w - $per_w / 4 - 1,

				$img_height - $per_h * 2 / 3 - 1, $color_pole_f

				);

			$len = strlen($datas[$i]);

			$p_x = ($i * $per_w) + $per_w + ($per_w - $len * 5 - 3) / 2;

			imagestring($image_id, 2, ceil($p_x), $img_height - $datas[$i] / $per_p - 1.6 * $per_h, $datas[$i], $color_text);
		} 

		for ($i = 0; $i < $number; $i++)
		{
			$str = gb2utf8(base64_decode($names[$i]));

			imagettftext($image_id, 9, 270, $per_w * ($i + 2) - $per_w / 2 - 8, $img_height + 5, $color_table, $this->ttf_font, $str);
		} 

		$result = $this->_output($image_id);

		imagedestroy($image_id);

		return $result;
	} 

	/**
	 * ~chinese 为指定的图片添加水印
	 * ~chinese 需要添加水印的图片
	 * ~chinese 需要添加的水印类型@li string 字符串@li image 图片
	 * ~chinese 需要添加的水印内容@li 如果 $mark_type 为 string，则本参数为水印字符串@li 如果 $mark_type 为 image，则本参数为水印图片名
	 * 
	 * @brief 
	 * @param  $image_file 
	 * @param  $mark_type 
	 * @param  $mark_name 
	 */

	function addWaterMark($mark_type, $mark_name, $image_file = '')
	{
		if (TRUE == empty($image_file)) $image_file = $this->filename;

		$image_id = $this->open($image_file);

		switch ($mark_type)
		{
			case "string" : $dst_image_id = $this->_addStringMark($image_id, $mark_name);
				break;

			case "image" : $dst_image_id = $this->_addImageMark($image_id, $mark_name);
				break;

			default: $dst_image_id = $this->_addStringMark($image_id, $mark_name);
				break;
		} 

		$result = $this->_output($dst_image_id);

		imagedestroy($image_id);

		return $result;
	} 

	/**
	 * ~chinese 添加字符串水印效果
	 * ~chinese 已经打开的图片文件指针
	 * ~chinese 需要作为水印的字符串
	 * ~chinese 使用内建字体
	 * 
	 * @internal 
	 * @private 
	 * @brief 
	 * @param  $image_id 
	 * @param  $string_added 
	 * @note 
	 */

	function _addStringMark(&$image_id, $string_added)
	{ 
		// 使用字体
		$font_name = 3; 
		// 获取当前图片的大小
		$image_size = $this->getSize($image_id);

		$tmp_image_id = imagecreatetruecolor($image_size["width"], $image_size["height"]);

		$color_back = imagecolorallocate($tmp_image_id, 0, 0, 0); 
		// 设置字体颜色
		$text_color = imagecolorallocate($tmp_image_id, 0x00, 0x00, 0x00); 
		// 获取字符串长度
		$text_length = strlen($string_added); 
		// 获取字符串在图片中的长度
		$text_length_in_image = imagefontwidth($font_name) * $text_length; 
		// 获取字符串在图片中的高度
		$text_height = imagefontheight($font_name); 
		// 字符串在图片中的起始位置
		$image_position = array("x" => ((($image_size["width"] - $text_length_in_image - 5) < 0) ? 0 : ($image_size["width"] - $text_length_in_image - 5)),

			"y" => ((($image_size["height"] - $text_height - 5) < 0) ? 0 : ($image_size["height"] - $text_height - 5))

			); 
		// 在图片左下脚插入字符串
		imagecopy($tmp_image_id, $image_id, 0, 0, 0, 0, $image_size["width"], $image_size["height"]);

		if (FALSE == empty($string_added)) imagestring($tmp_image_id, $font_name, $image_position['x'], $image_position['y'], $string_added, $text_color);

		return $tmp_image_id;
	} 

	/**
	 * ~chinese 添加图片水印效果
	 * 
	 * @internal 
	 * @private 
	 * @brief 
	 */

	function _addImageMark(&$image_id, $target_image)
	{ 
		// 打开需要作为水印的图片文件
		$src_image_id = $this->open($target_image); 
		// 获取水印文件大小
		$src_image_size = $this->getSize($src_image_id); 
		// 获取目标图片大小
		$image_size = $this->getSize($image_id); 
		// 水印在目标图片上的位置
		$image_position = array("x" => (($src_image_size["width"] > $image_size["width"]) ? 0 : ($image_size["width"] - $src_image_size["width"])),

			"y" => (($src_image_size["height"] > $image_size["height"]) ? 0 : ($image_size["height"] - $src_image_size["height"]))

			);

		$tmp_image_id = imagecreatetruecolor($image_size["width"], $image_size["height"]); 
		// 插入水印图片
		imagecopy($tmp_image_id, $image_id, 0, 0, 0, 0, $image_size["width"], $image_size["height"]);

		imagecopy($tmp_image_id, $src_image_id, $image_position["x"], $image_position["y"], 0, 0, $src_image_size["width"], $src_image_size["height"]);

		return $tmp_image_id;
	} 
} 

?>

