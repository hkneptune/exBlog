<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Unloading setting manager for exBlog
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

define('MODULE_NAME', 'core-adm.upload');

require_once("./public.php");

class UploadHandler extends CommonLib
{
	var $errors = array();

	var $dir_config = array();

	var $base_path = "";

	var $return_type = "ubb";

	var $return_form_element = "";

	/*

	function getDirConfig($dir)

	{

		$dir_config = array();

		$dir = $this->escapeSqlCharsFromData($dir);

		$query_string = "select * from {$this->_dbop->prefix}upload where match(destination_folder) against ($dir) limit 0, 1";

		$this->_dbop->query($query_string);

		if (FALSE == $this->_dbop->getNumRows()) $this->errors[] = $this->_lang['upload'][0];

		else $dir_config = $this->_dbop->fetchArray(0, 'ASSOC');

		$this->_dbop->freeResult();

		return $dir_config;

	}

*/

	function UploadHandler()
	{
		$this->_initEnv(MODULE_NAME, 'upload', 1);

		if (FALSE == empty($this->_input['_GET']['type']) && 'ubb' != $this->_input['_GET']['type']) $this->return_type = 'html';

		if (FALSE == empty($this->_input['_GET']['element'])) $this->return_form_element = $this->_input['_GET']['element'];

		$this->base_path = realpath("../upload");

		if (TRUE == preg_match("/win/i", PHP_OS)) $this->base_path = str_replace("\\", "/", $this->base_path); 
		// 返回值数据模板
		$this->return_string = "<script language=javascript>window.opener.document.forms[0].%s.value += '%s';</script>";

		$this->ubb_return_code = array("image" => "[url=%s][img]%s[/img][/url]",

			"file" => "[url=%s]{$this->_lang['upload'][50]}[/url]"

			);

		$this->html_return_code = array("image" => "<a href=\"%s\" title=\"Link\" target=\"_blank\"><img src=\"%s\" alt=\"image\" border=\"0\" /></a>",

			"file" => "<a href=\"%s\" title=\"Link\" target=\"_blank\">{$this->_lang['upload'][50]}</a>"

			);

		if (FALSE == empty($this->_input['_POST']['action']))
		{
			switch ($this->_input['_POST']['action'])
			{
				case 'upload' : $this->doUpload("upload_file");
					$this->printUploadForm($this->errors);
					break;

				case 'addsetting' : $this->_addUploadFolderSetting();
					break;

				case 'modifysetting' : $this->modifyUploadFolderSetting();
					break;

				default : $this->printUploadSetting();
					break;
			} 
		} elseif (FALSE == empty($this->_input['_GET']['action']))
		{
			switch ($this->_input['_GET']['action'])
			{
				case 'edit' : $this->printUploadSetting();
					break;

				case 'delete' : $this->removeUploadFolderSetting();
					break;

				case 'addForm': $this->printUploadForm();
					break;

				default : $this->printUploadSetting();
			} 
		} 

		else $this->printUploadSetting();

		$this->_destroyEnv();
	} 

	function printUploadForm($errors = '')
	{ 
		// 获取所有支持的文件类型
		$query_string = "select up_type from {$this->_dbop->prefix}upload";

		$this->_dbop->query($query_string);

		if (TRUE == $this->_dbop->getNumRows()) $tmp = $this->_dbop->fetchArrayBat(0, "ASSOC");

		else $tmp = array();

		$this->_dbop->freeResult();

		$allow_types = '';

		for ($i = 0; $i < count($tmp); $i++) $allow_types .= ", {$tmp[$i]['up_type']}";

		if (FALSE == empty($allow_types)) $allow_types = substr($allow_types, 1);

		else $allow_types = ""; 
		// 输出页面
		$this->printPageHeader();

		echo "

<table width=340 border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\" align=\"center\">

  <tr><td>

    <form method=\"post\" action=\"{$_SERVER['PHP_SELF']}?{$_SERVER['QUERY_STRING']}\" enctype=\"multipart/form-data\">

    <input type=hidden name=action value=upload>
		";
		if (FALSE == empty($errors))
		{
			if (FALSE == is_array($errors)) $errors = array($errors);
			echo "<table width=\"90%\" border=\"0\" class=\"main\"><tr><td>";
			for ($i = 0; $i < count($errors); $i++) echo "<li>{$errors[$i]}";
			echo "</td></tr></table>";
		} 
		echo "

    <table width=\"100%\" border=\"0\" class=\"main\">

      <tr><td colspan=\"2\" class=\"menu\" align=\"center\"><b>{$this->_lang['upload'][43]}</b></td></tr>

      <tr><td align=\"center\" valign=\"top\">

        <table width=90% cellpadding=2 cellspacing=2 border=0 align=center class=\"main\">

          <tr>

            <td align=left colspan=2>{$this->_lang['upload'][46]} $allow_types</td>

          </tr>

          <tr>

            <td align=left>

            {$this->_lang['upload'][44]}

            <br /><input type=file name=upload_file class=\"input\" />

            </td>

            <td align=right valign=bottom><input type=submit value=\"{$this->_lang['upload'][45]}\"></td>

          </tr>

        </table>

      </td></tr>

    </table>

    </form>

  </td></tr>

</table>

	";

		$this->printPageFooter();
	} 

	function printUploadSetting()
	{
		if (FALSE == empty($this->_input['_GET']['path'])) $folder_setting = $this->_getUploadFolderSetting($this->_input['_GET']['path']);

		$folders_setting = $this->_getUploadFolderSetting();

		$this->printPageHeader();

		echo "

<form action={$_SERVER['PHP_SELF']} method=POST>

<table width=\"500\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">

  <tr><td valign=top>

    <table width=\"100%\" border=\"0\" class=\"main\">

      <tr><td colspan=\"4\" class=\"menu\" align=\"center\"><b>{$this->_lang['upload'][2]}</b></td></tr>

      <tr><td colspan=4>{$this->_lang['upload'][51]}" . realpath("../upload") . "</td></tr>

      <tr bgcolor=#F0F0F0>

        <td width=40%>{$this->_lang['upload'][3]}</td>

        <td width=20% align=center>{$this->_lang['upload'][4]}</td>

        <td width=20% align=center>{$this->_lang['upload'][5]}</td>

        <td width=20% align=center>{$this->_lang['upload'][6]}</td>

      </tr>

		";

		for ($i = 0; $i < count($folders_setting); $i++)
		{
			echo "

      <tr>

        <td>{$folders_setting[$i]['destination_folder']}</td>

        <td align=center>{$folders_setting[$i]['up_type']}</td>

        <td align=center>{$folders_setting[$i]['watermark_type']}</td>

        <td align=center>

          <a href={$_SERVER['PHP_SELF']}?action=edit&path={$folders_setting[$i]['destination_folder']}>{$this->_lang['upload'][7]}</a>

          | <a href={$_SERVER['PHP_SELF']}?action=delete&path={$folders_setting[$i]['destination_folder']}>{$this->_lang['upload'][8]}</a>

        </td>

      </tr>

			";
		} 

		echo "

    </table>

    <hr size=1 width=90% align=center noshadow>

    <table width=\"100%\" border=\"0\" class=\"main\">

      <form action={$_SERVER['PHP_SELF']} method=POST>

    	 <tr><td colspan=\"3\" class=\"menu\" align=\"center\"><b>" . ((FALSE == empty($this->_input['_GET']['path'])) ? $this->_lang['upload'][40] : $this->_lang['upload'][9]) . "</b></td></tr>

      <tr><td align=right>{$this->_lang['upload'][10]}</td><td><input type=text name=destination_folder class=\"input\" value='" . ((FALSE == empty($this->_input['_GET']['path'])) ? $folder_setting['destination_folder'] : '') . "' " . ((FALSE == empty($this->_input['_GET']['path'])) ? 'disabled' : '') . "></td><td>{$this->_lang['upload'][18]}</td></tr>

      <tr><td align=right>{$this->_lang['upload'][11]}</td><td><input type=text name=file_max_size class=\"input\" value='" . ((FALSE == empty($this->_input['_GET']['path'])) ? $folder_setting['max_file_size'] : '') . "'></td><td>{$this->_lang['upload'][19]}</td></tr>

      <tr><td align=right>{$this->_lang['upload'][12]}</td><td><input type=text name=file_type class=\"input\" value='" . ((FALSE == empty($this->_input['_GET']['path'])) ? $folder_setting['up_type'] : '') . "'></td><td>{$this->_lang['upload'][20]}</td></tr>

      <tr><td align=right>{$this->_lang['upload'][13]}</td><td><select name=watermark_type class=\"input\"><option value='string' " . ((FALSE == empty($this->_input['_GET']['path']) && 'string' == $folder_setting['watermark_type']) ? 'selected' : '') . ">文字</option><option value='image' " . ((FALSE == empty($this->_input['_GET']['path']) && 'image' == $folder_setting['watermark_type']) ? 'selected' : '') . ">图片</option></select></td></tr>

      <tr><td align=right>{$this->_lang['upload'][14]}</td><td><input type=text name=watermark class=\"input\" value='" . ((FALSE == empty($this->_input['_GET']['path'])) ? $folder_setting['watermark'] : '') . "'></td><td>{$this->_lang['upload'][21]}</td></tr>

      <tr><td align=right>{$this->_lang['upload'][15]}</td><td><input type=text name=max_width class=\"input\" value='" . ((FALSE == empty($this->_input['_GET']['path'])) ? $folder_setting['max_width'] : '') . "'></td><td>{$this->_lang['upload'][22]}</td></tr>

      <tr><td align=right>{$this->_lang['upload'][16]}</td><td><input type=text name=max_height class=\"input\" value='" . ((FALSE == empty($this->_input['_GET']['path'])) ? $folder_setting['max_height'] : '') . "'></td><td>{$this->_lang['upload'][23]}</td></tr>

      <tr><td align=right>{$this->_lang['upload'][47]}</td><td><input type=text name=image_url class=\"input\" value='" . ((FALSE == empty($this->_input['_GET']['path'])) ? $folder_setting['up_url'] : '') . "'></td><td>&nbsp;</td></tr>

      <tr><td colspan=3 align=center><input type=submit value=\"" . ((FALSE == empty($this->_input['_GET']['path'])) ? $this->_lang['upload'][41] : $this->_lang['upload'][17]) . "\" class=\"botton\"></td></tr>";

		if (FALSE == empty($this->_input['_GET']['path'])) echo "<input type=hidden name=action value='modifysetting'><input type=hidden name=destination_folder value='{$folder_setting['destination_folder']}'>";

		else echo "<input type=hidden name=action value='addsetting'>";

		echo "

      </form>

    </table>

  </td></tr>

</table>

		";

		$this->printPageFooter();
	} 

	function getDirByExtension($file_name)
	{
		$extension = strtolower(substr(strrchr($file_name, "."), 1));

		$query_string = "select * from {$this->_dbop->prefix}upload where up_type like '%$extension%' limit 0, 1";

		$this->_dbop->query($query_string);

		if (FALSE == $this->_dbop->getNumRows()) $this->errors[] = $this->_lang['upload'][0];

		else $this->dir_config = $this->_dbop->fetchArray(0, 'ASSOC');

		$this->_dbop->freeResult();

		return $this->dir_config;
	} 

	function doUpload($input_field)
	{
		$result = FALSE;

		include_once("../include/upload.php");

		$upload_op = new Upload($input_field);

		$dir_config = $this->getDirByExtension($upload_op->orgfile);

		if (TRUE == count($dir_config))
		{
			$file_type = "file";

			$upload_op->setDir($this->base_path . $dir_config['destination_folder']);

			$upload_op->setFilename(time());

			$upload_op->allow_exts = explode(",", $dir_config['up_type']);

			$upload_op->max_filesize = intval($dir_config['max_file_size']) * 1.024 * 1.024;

			$result = $upload_op->doCopy();

			$filename = $upload_op->getFilename();

			unset($upload_op);

			if (FALSE == $result) $this->errors[] = $this->_lang['upload'][2];

			else $filenames = array($filename, $filename);

			$code_type_funname = (('ubb' == $this->return_type) ? "ubb_return_code" : "html_return_code"); 
			// 处理图片文件
			if (TRUE == $this->_isImageFile($filename))
			{
				$filenames = $this->_doWithImageFile($filename);

				$file_type = "image";
			} 

			if (FALSE == count($this->errors) && FALSE == empty($this->return_form_element)) printf($this->return_string, $this->return_form_element, sprintf($this->
					{
						$code_type_funname} 
					[$file_type], $dir_config['up_url'] . basename($filenames[1]), $dir_config['up_url'] . basename($filenames[0])));
		} 

		return $result;
	} 

	function _isImageFile($file_name)
	{
		$extension = strtolower(substr(strrchr($file_name, "."), 1));

		if ("gif" == $extension || "jpg" == $extension || "png" == $extension) return TRUE;

		else return FALSE;
	} 

	function _doWithImageFile($file_name)
	{
		include_once("../include/image.php");

		$image_op = new Image($file_name);

		$image_op->output = TRUE; 
		// 缩略图名称
		$simage_name = dirname($file_name) . "/s_" . basename($file_name);

		$image_op->new_name = $simage_name;

		$old_size = $image_op->getProperty($file_name);

		$image_op->resize_rate = min($this->dir_config['max_width'] / $old_size['width'], $this->dir_config['max_height'] / $old_size['height']) * 100;

		$image_op->resize($file_name);

		if (FALSE == empty($this->dir_config['watermark'])) $image_op->addWaterMark($this->dir_config['watermark_type'], $this->dir_config['watermark'], $simage_name); 
		// 销毁对象
		unset($image_op);

		$image_op = new Image($file_name);

		$image_op->output = TRUE;

		if (FALSE == empty($this->dir_config['watermark_type'])) $image_op->addWaterMark($this->dir_config['watermark_type'], $this->dir_config['watermark'], $file_name); 
		// 销毁对象
		unset($image_op);

		return array($simage_name, $file_name);
	} 

	function _getUploadFolderSetting($path = "")
	{
		$folders_setting = array();

		$query_string = "select * from {$this->_dbop->prefix}upload";

		if (FALSE == empty($path)) $query_string .= " where destination_folder='$path'";

		$this->_dbop->query($query_string);

		if (TRUE == $this->_dbop->getNumRows()) $folders_setting = $this->_dbop->fetchArrayBat(0, 'ASSOC');

		elseif (FALSE == empty($path)) $this->_showError($this->_lang['upload'][0], $_SERVER['PHP_SELF']);

		$this->_dbop->freeResult();

		if (FALSE == empty($path)) $folders_setting = $folders_setting[0];

		return $folders_setting;
	} 

	function _checkUploadSettingForm()
	{
		if (TRUE == empty($this->_input['_POST']['destination_folder'])) $this->errors[] = $this->_lang['upload'][28];

		elseif (FALSE == preg_match("/^\/(\w+\/)*?$/", $this->_input['_POST']['destination_folder'])) $this->errors[] = $this->_lang['upload'][29];

		if (TRUE == empty($this->_input['_POST']['file_max_size']) || FALSE == is_numeric($this->_input['_POST']['file_max_size'])) $this->errors[] = $this->_lang['upload'][30];

		else $this->_input['_POST']['file_max_size'] = intval($this->_input['_POST']['file_max_size']);

		if (TRUE == empty($this->_input['_POST']['max_width']) || FALSE == is_numeric($this->_input['_POST']['max_width'])) $this->errors[] = $this->_lang['upload'][31];

		else $this->_input['_POST']['max_width'] = intval($this->_input['_POST']['max_width']);

		if (TRUE == empty($this->_input['_POST']['max_height']) || FALSE == is_numeric($this->_input['_POST']['max_height'])) $this->errors[] = $this->_lang['upload'][32];

		else $this->_input['_POST']['max_height'] = intval($this->_input['_POST']['max_height']);

		if (TRUE == empty($this->_input['_POST']['file_type'])) $this->errors[] = $this->_lang['upload'][33];

		elseif (FALSE == preg_match("/^\w+(,\w+)*$/", $this->_input['_POST']['file_type'])) $this->errors[] = $this->_lang['upload'][34];
		else
		{
			$tmp = explode(",", $this->_input['_POST']['file_type']);
			for ($i = 0; $i < count($tmp); $i++) $tmp[$i] = strtolower($tmp[$i]);
			$tmp = array_unique($tmp);
			$this->_input['_POST']['file_type'] = implode(",", $tmp);
		} 

		if (TRUE == empty($this->_input['_POST']['watermark_type']) || 'image' !== $this->_input['_POST']['watermark_type']) $this->_input['_POST']['watermark_type'] = 'string';

		if ('image' == $this->_input['_POST']['watermark_type'] && FALSE == empty($this->_input['_POST']['watermark']))
		{
			if (FALSE == @file_exists($this->_input['_POST']['watermark']) || FALSE == @is_file($this->_input['_POST']['watermark'])) $this->errors[] = $this->_lang['upload'][35];

			elseif (FALSE == $this->_isImageFile($this->_input['_POST']['watermark'])) $this->errors[] = $this->_lang['upload'][36];
		} elseif (TRUE == empty($this->_input['_POST']['watermark'])) $this->_input['_POST']['watermark'] = '';

		elseif (TRUE == empty($this->_input['_POST']['image_url'])) $this->errors[] = $this->_lang['upload'][48];

		elseif (FALSE == preg_match("/^\/(\w+\/)*?$/", $this->_input['_POST']['image_url'])) $this->errors[] = $this->_lang['upload'][49];
	} 

	function _addUploadFolderSetting()
	{
		$this->_checkUploadSettingForm();

		$this->escapeSqlCharsFromData($this->_input);

		if (TRUE == count($this->errors)) $this->_showError($this->errors, $_SERVER['PHP_SELF']); 
		// 查询指定目录设置是否存在
		$query_string = "select destination_folder from {$this->_dbop->prefix}upload where destination_folder='" . $this->_input['_POST']['destination_folder'] . "'";

		$this->_dbop->query($query_string);

		if (TRUE == $this->_dbop->getNumRows()) $this->errors[] = $this->_lang['upload'][24];

		$this->_dbop->freeResult();

		if (TRUE == count($this->errors)) $this->_showError($this->errors, $_SERVER['PHP_SELF']); 
		// 检查指定的目录是否存在，如果不存在，尝试新建
		$dir_path = $this->base_path . $this->_input['_POST']['destination_folder'];

		if (FALSE == @file_exists($dir_path)) @mkdir($dir_path, 0755) or $this->errors[] = sprintf($this->_lang['upload'][25], $dir_path);

		elseif (FALSE == @is_dir($dir_path)) $this->errors[] = sprintf($this->_lang['upload'][26], $dir_path);

		if (FALSE == count($this->errors))
		{ 
			// 添加目录设置
			$query_string = "insert into {$this->_dbop->prefix}upload (

				max_file_size, destination_folder, up_type, max_width, max_height, watermark_type, watermark, up_url

				) values (

				" . intval($this->_input['_POST']['file_max_size']) . ", '{$this->_input['_POST']['destination_folder']}', '{$this->_input['_POST']['file_type']}',

				" . intval($this->_input['_POST']['max_width']) . ", " . intval($this->_input['_POST']['max_height']) . ",

				'{$this->_input['_POST']['watermark_type']}', '{$this->_input['_POST']['watermark']}', '{$this->_input['_POST']['image_url']}')";

			$this->_dbop->query($query_string);

			$this->_dbop->freeResult();

			$this->showMesg(sprintf($this->_lang['upload'][27], $this->_input['_POST']['destination_folder']), $_SERVER['HTTP_REFERER']);
		} 

		else $this->_showError($this->errors, $_SERVER['PHP_SELF']);
	} 

	function removeUploadFolderSetting()
	{
		if (TRUE == empty($this->_input['_GET']['path'])) $this->_showError($this->_lang['upload'][37], $_SERVER['PHP_SELF']);

		else
		{
			$old_setting = array(); 
			// 检查指定的目录配置是否存在
			$query_string = "select destination_folder from {$this->_dbop->prefix}upload where destination_folder='" . $this->_input['_GET']['path'] . "'";

			$this->_dbop->query($query_string);

			if (FALSE == $this->_dbop->getNumRows()) $this->errors[] = $this->_lang['upload'][38];

			else $old_setting = $this->_dbop->fetchArray(0, 'ASSOC');

			$this->_dbop->freeResult(); 
			// 删除指定的目录
			if (TRUE == count($old_setting)) $remove_result = @rmdir($this->base_path . $old_setting['destination_folder']); 
			// 删除指定的目录设置纪录
			if (TRUE == $remove_result)
			{
				$query_string = "delete from {$this->_dbop->prefix}upload where destination_folder='" . $this->_input['_GET']['path'] . "'";

				$this->_dbop->query($query_string);

				$this->_dbop->freeResult();
			} 

			$this->showMesg(sprintf($this->_lang['upload'][39], $this->_input['_GET']['path']), $_SERVER['PHP_SELF']);
		} 
	} 

	function modifyUploadFolderSetting()
	{
		$this->_checkUploadSettingForm();

		$this->escapeSqlCharsFromData($this->_input);

		if (TRUE == count($this->errors)) $this->_showError($this->errors, $_SERVER['PHP_SELF']); 
		// 查询指定目录设置是否存在
		$query_string = "select destination_folder from {$this->_dbop->prefix}upload where destination_folder='" . $this->_input['_POST']['destination_folder'] . "'";

		$this->_dbop->query($query_string);

		if (FALSE == $this->_dbop->getNumRows()) $this->errors[] = $this->_lang['upload'][38];

		$this->_dbop->freeResult();

		if (TRUE == count($this->errors)) $this->_showError($this->errors, $_SERVER['PHP_SELF']); 
		// 更新目录设置
		$query_string = "update {$this->_dbop->prefix}upload set

			max_file_size=" . intval($this->_input['_POST']['file_max_size']) . ",

			up_type='{$this->_input['_POST']['file_type']}',

			max_width=" . intval($this->_input['_POST']['max_width']) . ",

			max_height=" . intval($this->_input['_POST']['max_height']) . ",

			watermark_type='{$this->_input['_POST']['watermark_type']}',

			watermark='{$this->_input['_POST']['watermark']}',

			up_url='{$this->_input['_POST']['image_url']}'

			where destination_folder='" . $this->_input['_POST']['destination_folder'] . "'";

		$this->_dbop->query($query_string);

		$this->_dbop->freeResult();

		$this->showMesg(sprintf($this->_lang['upload'][42], $this->_input['_POST']['destination_folder']), $_SERVER['HTTP_REFERER']);
	} 
} 

new UploadHandler();

?>
