<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Uploading tools kit for exBlog
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

/**
 * ~chinese 上传工具集
 * ~chinese
 * 
 * @class Upload upload.php "/include/upload.php"
 * @brief 
 * @author feeling <feeling@exblog.org> 
 * @date $Date$
 * @todo 
 * @li 添加根据文件头判断文件类型
 */
class Upload
{
	/**
	 * ~chinese 构造器
	 * ~chinese 文件的表单元素名称，缺省为 file
	 * ~chinese 是否使用原始文件名，缺省为否
	 * ~chinese 是否覆盖已经存在的同名文件，缺省为是
	 * 
	 * @brief 
	 * @param  $form_inputname 
	 * @param  $use_orgfile 
	 * @param  $overwrite 
	 */
	function Upload($form_inputname = 'file', $use_orgfile = 0, $overwrite = 1)
	{
		$url = parse_url($_SERVER['HTTP_REFERER']);
		$this->referer_domain = '';
		$this->domain_check = 0;
		$this->overwrite = $overwrite;
		$this->domain = $url["scheme"] . "://" . $url["host"];
		$this->max_filesize = (empty($_POST['MAX_FILE_SIZE'])) ? 2097152 : intval($_POST['MAX_FILE_SIZE']);
		$this->file_name = $_FILES[$form_inputname]['tmp_name'];
		$this->file_size = $_FILES[$form_inputname]['size'];
		$this->orgfile = strtolower($_FILES[$form_inputname]['name']);
		$this->use_orgfile = $use_orgfile;
		$this->allow_exts = array("gif", "png", "jpg");
	} 

	/**
	 * ~chinese 设置上传文件在服务器上的保存目录
	 * ~chinese 上传文件在服务器上的保存目录
	 * 
	 * @brief 
	 * @param  $dir_path 
	 */
	function setDir($dir_path)
	{
		if (substr($dir_path, -1) == "/") $dir_path = substr($dir_path, 0, strlen($dir_path)-1);
		$this->upload_dir = $dir_path;
	} 

	/**
	 * ~chinese 设置新的文件名
	 * ~chinese 需要设置的新文件名
	 * 
	 * @brief 
	 * @param  $new_name 
	 */
	function setFilename($new_name = '')
	{
		(FALSE == empty($new_name)) ? $this->_changeFilename($this->use_orgfile, $new_name) : $this->_changeFilename($this->use_orgfile);
	} 

	/**
	 * ~chinese 获取上传文件在服务器上的保存文件名
	 * 
	 * @brief 
	 */
	function getFilename()
	{
		if (TRUE == empty($this->orgfile)) return "";
		else return $this->copyfile;
	} 

	/**
	 * ~chinese 复制上传文件
	 * ~chinese 已上传数据部分在服务器上保存的文件名
	 * 
	 * @brief 
	 * @param  $uploaded_name 
	 */
	function doCopy($uploaded_name = '')
	{
		if (FALSE == empty($this->file_name))
		{
			if (FALSE == $this->_checkExtension())
			{
				trigger_error("Extension was not accepted", E_USER_WARNING);
				$this->result_code = 1;
			} 
			else
			{
				if (FALSE == empty($uploaded_name)) $this->setFilename($uploaded_name);
				elseif (TRUE == empty($this->copyfile)) $this->setFilename();
				if (TRUE == $this->_checkExisted())
				{
					if (TRUE == $this->overwrite)
					{
						if (FALSE == unlink($this->copyfile))
						{
							trigger_error("Could not remove a file", E_USER_ERROR);
							$this->result_code = 3;
						} 
						else $this->result_code = $this->_resumeCopy($this->domain, $uploaded_name);
					} 
					else
					{
						trigger_error("File was existed and could not overwrite", E_USER_ERROR);
						$this->result_code = 4;
					} 
				} 
				else $this->result_code = $this->_resumeCopy($this->domain);
			} 
		} 
		else
		{
			trigger_error("Temp file was not existed", E_USER_ERROR);
			$this->result_code = 2;
		} 
		return !$this->result_code;
	} 

	/**
	 * ~chinese 断点续传上传文件
	 * ~chinese 文件上传的源域
	 * ~chinese 已上传数据部分在服务器上保存的文件名
	 * 
	 * @private 
	 * @internal 
	 * @brief 
	 * @param  $domain 
	 * @param  $uploaded_name 
	 */
	function _resumeCopy($domain, $uploaded_name = '')
	{
		if (TRUE == $this->domain_check)
		{
			if ($domain != $this->referer_domain)
			{
				trigger_error("Have no permission to use this script", E_USER_ERROR);
				return 7;
			} 
		} 
		if (FALSE == $this->_checkFilesize())
		{
			trigger_error("File size had exceeded the max value", E_USER_ERROR);
			return 5;
		} 
		if (TRUE == @copy($this->file_name, $this->copyfile)) return 0;
		else
		{
			trigger_error("Could not upload a file", E_USER_ERROR);
			return 6;
		} 
	} 

	/**
	 * ~chinese 修改已上传文件在服务器上的文件名
	 * ~chinese 是否使用原始文件名。0 ＝ 不使用，1 ＝ 使用
	 * ~chinese 需要设置的新文件名。如果本参数为空，则强制使用原始文件名
	 * 
	 * @private 
	 * @internal 
	 * @brief 
	 * @param  $use_orgfile 
	 * @param  $new_name 
	 */
	function _changeFilename($use_orgfile = 0, $new_name = '')
	{
		if (TRUE == empty($new_name) && FALSE == $use_orgfile)
		{
			$extension = $this->_getExtension($this->orgfile);
			$new_name = strtolower($this->_randName(10));
			$new_name .= $extension;
			$new_name = strtolower($new_name);
			$this->copyfile = $this->upload_dir . "/$new_name";
		} elseif (TRUE == $use_orgfile) $this->copyfile = $this->upload_dir . "/" . $this->orgfile;
		else
		{
			$extension = $this->_getExtension($this->orgfile);
			$this->copyfile = $this->upload_dir . "/$new_name";
			$this->copyfile .= $extension;
		} 
		return $this->copyfile;
	} 

	/**
	 * ~chinese 获取指定文件的扩展名
	 * ~chinese 需要获取扩展名的文件名
	 * 
	 * @private 
	 * @internal 
	 * @brief 
	 * @param  $filename 
	 */
	function _getExtension($filename)
	{
		return strtolower(strrchr($filename, "."));
	} 

	/**
	 * ~chinese 检查上传文件的扩展名是否合法
	 * ~chinese 合法则返回 TRUE，否则返回 FALSE
	 * 
	 * @private 
	 * @internal 
	 * @brief 
	 * @return 
	 */
	function _checkExtension()
	{
		$this->orgfile = ereg_replace("%20", " ", $this->orgfile);
		return in_array(strtolower(substr($this->_getExtension($this->orgfile), 1)), $this->allow_exts);
	} 

	/**
	 * ~chinese 检查上传文件的大小是否超过最大大小限制
	 * ~chinese 未超过就返回 TRUE，否则返回 FALSE
	 * 
	 * @private 
	 * @internal 
	 * @brief 
	 * @return 
	 */
	function _checkFilesize()
	{
		return ($this->file_size < $this->max_filesize);
	} 

	/**
	 * ~chinese 检查上传文件在服务器上的临时文件是否存在
	 * ~chinese 如果存在则返回 TRUE，否则返回 FALSE
	 * 
	 * @private 
	 * @internal 
	 * @brief 
	 * @return 
	 */
	function _checkExisted()
	{
		return @file_exists($this->copyfile);
	} 

	/**
	 * ~chinese 生成指定长度的随机字符串
	 * ~chinese 指定的随机字符串长度。缺省为 10 个字符
	 * 
	 * @private 
	 * @internal 
	 * @brief 
	 * @param  $name_len 
	 */
	function _randName($name_len = 10)
	{
		$allchar = 'ABCDEFGHIJKLNMOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz01234567890_';
		$str = '';
		mt_srand((double) microtime() * 1000000);
		for ($i = 0; $i < $name_len ; $i++) $str .= substr($allchar, mt_rand(0, 25), 1) ;
		return $str;
	} 
} 

?>
