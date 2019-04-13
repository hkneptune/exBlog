<?php
/**
 * @class Upload upload.php "common/upload.php"
 * @brief @~chinese 上传工具集
 * @author feeling <feeling@4ulm.org>
 * @date 2005-01-17 20:20:20
 * @todo @~chinese @li 添加根据文件头判断文件类型
 **/
class Upload
{

	/**
	 * @brief @~chinese 构造器
	 * @param $form_inputname @~chinese 文件的表单元素名称，缺省为 file
	 * @param $use_orgfile @~chinese 是否使用原始文件名，缺省为否
	 * @param $overwrite @~chinese 是否覆盖已经存在的同名文件，缺省为是
	 **/
	function Upload($form_inputname = 'file', $use_orgfile=0, $overwrite = 1)
	{
		$url = parse_url($_SERVER['HTTP_REFERER']);
		$this->referer_domain = '';
		$this->domain_check = 0;
		$this->overwrite = $overwrite;
		$this->domain = $url["scheme"]."://".$url["host"];
		$this->max_filesize = (empty($_POST['MAX_FILE_SIZE'])) ? 2097152 : intval($_POST['MAX_FILE_SIZE']);
		$this->file_name = $_FILES[$form_inputname]['tmp_name'];
		$this->file_size = $_FILES[$form_inputname]['size'];
		$this->orgfile = strtolower($_FILES[$form_inputname]['name']);
		$this->use_orgfile = $use_orgfile;
		$this->allow_exts = array("gif", "png", "jpg");
	}

	/**
	 * @brief @~chinese 设置上传文件在服务器上的保存目录
	 * @param $dir_path @~chinese 上传文件在服务器上的保存目录
	 **/
	function setDir($dir_path)
	{
		if (substr($dir_path, -1) == "/") $dir_path = substr($dir_path, 0, strlen($dir_path)-1);
		$this->upload_dir = $dir_path;
	}

	/**
	 * @brief @~chinese 设置新的文件名
	 * @param $new_name @~chinese 需要设置的新文件名
	 **/
	function setFilename($new_name='')
	{
		(FALSE == empty($new_name)) ? $this->_changeFilename($this->use_orgfile, $new_name) : $this->_changeFilename($this->use_orgfile);
	}

	/**
	 * @brief @~chinese 获取上传文件在服务器上的保存文件名
	 **/
	function getFilename()
  	{
		if (TRUE == empty($this->orgfile)) return "";
  		else return $this->copyfile;
  	}

	/**
	 * @brief @~chinese 复制上传文件
	 * @param $uploaded_name @~chinese 已上传数据部分在服务器上保存的文件名
	 **/
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
	 * @private
	 * @internal
	 * @brief @~chinese 断点续传上传文件
	 * @param $domain @~chinese 文件上传的源域
	 * @param $uploaded_name @~chinese 已上传数据部分在服务器上保存的文件名
	 **/
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
	 * @private
	 * @internal
	 * @brief @~chinese 修改已上传文件在服务器上的文件名
	 * @param $use_orgfile @~chinese 是否使用原始文件名。0 ＝ 不使用，1 ＝ 使用
	 * @param $new_name @~chinese 需要设置的新文件名。如果本参数为空，则强制使用原始文件名
	 **/
	function _changeFilename($use_orgfile = 0, $new_name = '')
	{
		if (TRUE == empty($new_name) && FALSE == $use_orgfile)
		{
			$extension = $this->_getExtension($this->orgfile);
			$new_name = strtolower($this->_randName(10));
			$new_name .= $extension;
			$new_name = strtolower($new_name);
			$this->copyfile = $this->upload_dir . "/$new_name";
		}
		elseif (TRUE == $use_orgfile) $this->copyfile = $this->upload_dir . "/" . $this->orgfile;
		else
		{
			$extension = $this->_getExtension($this->orgfile);
			$this->copyfile = $this->upload_dir . "/$new_name";
			$this->copyfile .= $extension;
		}
		return $this->copyfile;
	}

	/**
	 * @private
	 * @internal
	 * @brief @~chinese 获取指定文件的扩展名
	 * @param $filename @~chinese 需要获取扩展名的文件名
	 **/
	function _getExtension($filename)
	{
		return strtolower(strrchr($filename, "."));
  	}

	/**
	 * @private
	 * @internal
	 * @brief @~chinese 检查上传文件的扩展名是否合法
	 * @return @~chinese 合法则返回 TRUE，否则返回 FALSE
	 **/
	function _checkExtension()
	{
		$this->orgfile = ereg_replace("%20", " ", $this->orgfile);
		return in_array(substr($this->_getExtension($this->orgfile), 1), $this->allow_exts);
	}

	/**
	 * @private
	 * @internal
	 * @brief @~chinese 检查上传文件的大小是否超过最大大小限制
	 * @return @~chinese 未超过就返回 TRUE，否则返回 FALSE
	 **/
	function _checkFilesize()
	{
		return ($this->file_size < $this->max_filesize);
	}

	/**
	 * @private
	 * @internal
	 * @brief @~chinese 检查上传文件在服务器上的临时文件是否存在
	 * @return @~chinese 如果存在则返回 TRUE，否则返回 FALSE
	 **/
	function _checkExisted()
	{
		return @file_exists($this->copyfile);
	}

	/**
	 * @private
	 * @internal
	 * @brief @~chinese 生成指定长度的随机字符串
	 * @param $name_len @~chinese 指定的随机字符串长度。缺省为 10 个字符
	 **/
	function _randName($name_len = 10)
	{
		$allchar = 'ABCDEFGHIJKLNMOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz01234567890_';
		$str = '';
		mt_srand((double) microtime() * 1000000);
		for ($i = 0; $i<$name_len ; $i++) $str .= substr( $allchar, mt_rand(0, 25), 1) ;
		return $str;
	}
}
?>
