<?php
/**
 * @class ExtensionLib extension.php "/include/extension.php"
 * @brief Extension Common Library
 * @author feeling <feeling@exblog.org>
 * @date $Date$
 **/
class ExtensionLib
{
	/**
	 * @brief the default path of the configure file
	 **/
	var $config_file = "";

	function ExtensionLib()
	{
		
	}

	/**
	 * @brief Set path of the configure file
	 * @param $file_name The path of the configure file
	 * @return NULL
	 **/
	function setConfigureFile($file_name)
	{
		if (TRUE == @file_exists($file_name)) $this->config_file = $file_name;
	}

	function addAExtension()
	{
		
	}

	/**
	 * @brief Remove all configure items about a extension.
	 * @param $extension_id Position ID of a extension was in the configure parameter.@n In the fact, Position ID was numberic index in the configure array.
	 * @return Return TRUE if successful, else FALSE.
	 **/
	function deleteAExtension($extension_id)
	{
		if (FALSE == is_numeric($extension_id) || (0 > $extension_id || (count($this->config['plugin']) - 1) < $extension_id)) die("Invalid extension ID");
		$tmp_array_1 = array_slice($this->config['plugin'], 0, $extension_id);
		$tmp_array_2 = array_slice($this->config['plugin'], $extension_id + 1);
		$this->config['plugin'] = array_merge($tmp_array_1, $tmp_array_2);
		return $this->_writeIntoConfigFile();
	}

	function modifyExtensionSetting()
	{
		
	}

	/**
	 * @brief Add configure options for a extension
	 * @param $option_name Name of a option.@n But it could be a array for adding many options in the same time.
	 * @param $option_value Value of a option.@n It could be empty when adding many options in the same time.
	 * @param $extension_id Position ID of a extension was in the configure parameter.@n In the fact, Position ID was numberic index in the configure array.
	 * @return Return TRUE if successful, else FALSE.
	 * @note $option_name would be a array with elements as bellow when adding many options in the same time:
	 *             @li name Name of a option
	 *            @li value Value of a option
	 * @see ::_addOptionForExtension()
	 **/
	function addOptionsForExtension($option_name, $option_value = '', $extension_id = -1)
	{
		if (TRUE == is_array($option_name))
		{
			for ($i = 0; $i < count($option_name); $i++) $this->addOptionForExtension($option_name[$i]['name'], $option_name[$i]['value'], $extension_id);
		}
		else $this->addOptionForExtension($option_name, $option_name, $extension_id);
		return $this->_writeIntoConfigFile();
	}

	/**
	 * @internal
	 * @brief Add a configure option for a extension
	 * @param $option_name Name of a option.
	 * @param $option_value Value of a option.
	 * @param $extension_id Position ID of a extension was in the configure parameter.@n In the fact, Position ID was numberic index in the configure array.
	 * @return Always be TRUE.
	 **/
	function _addOptionForExtension($option_name, $option_value = '', $extension_id = -1)
	{
		if (-1 == $extension_id) $extension_id = count($this->config['plugin']);
		else $extension_id = intval($extension_id);
		$this->config['plugin'][$extension_id][$option_name] = $option_value;
		return TRUE;
	}

	/**
	 * @internal
	 * @brief Load configration about all extensions
	 * @param $file_name path of the configure file.@n If it was empty, the default path ExtensionLib#$config_file was used.
	 * @return A array for all configure options
	 * @note global variable ExtensionLib::$config was assigned during loading.
	 **/
	function _loadExtensionConfig($file_name='')
	{
		if (TRUE == empty($file_name)) $file_name = $this->config_file;
		if (TRUE == empty($file_name)) die("The configure file was not provided");
		if (TRUE == @file_exists($this->config_file))
		{
			include_once($this->config_file);
			$this->config['plugin'] = $plugins;
			return $this->config['plugin'];
		}
		else die("Could not load configurations for all extensions");
	}

	/**
	 * @internal
	 * @brief Write configure options into the config file
	 * @param $file_name path of the configure file.@n If it was empty, the default path ExtensionLib#$config_file was used.
	 * @return return TRUE if successful, else return FALSE
	 **/
	function _writeIntoConfigFile($file_name = '')
	{
		if (TRUE == empty($file_name)) $file_name = $this->config_file;
		if (TRUE == empty($file_name)) die("The configure file was not provided");

		// building content
		$content = '';
		for ($i = 0; $i < count($this->config['plugin']); $i++)
		{
			$content .= "\$plugins[] = array(\n";
			foreach ($this->config['plugin'] as $key => $value)
			{
				if ('last_modify' != $key)
				{
					if (TRUE == is_bool($value)) $content .= "\t\"$key\"\t=> $value,\n";
					if (TRUE == is_numeric($value)) $content .= "\t\"$key\"\t=> " . intval($value) . ",\n";
				}
			}
			$content .= "\t\"last_modify\"\t=> " . time() . "\n);\n";
		}

		// backup the old configure file
		$this->_backupConfigFile($file_name);
		// write into the configure file
		$file_id = @fopen($file_name, "wb");
		@flock($file_id, LOCK_EX);
		$result = @fwrite($file_id, $content);
		@flock($file_id, LOCK_UN);
		@fclose($file_id);

		return $result;
	}

	/**
	 * @internal
	 * @brief Backup the configure file
	 * @param $file_name path of the configure file.@n If it was empty, the default path #$config_file was used.
	 * @return return TRUE if successful, else return FALSE
	 **/
	function _backupConfigFile($file_name = '')
	{
		if (TRUE == empty($file_name)) $file_name = $this->config_file;
		if (TRUE == empty($file_name)) die("The configure file was not provided");

		if (FALSE == @file_exists($file_name)) die("The configure file was not existed");

		$backup_filename = dirname($file_name) . "/" . basename($file_name, ".php") . "_YmdHis" . date(time()) . ".php";
		$result = @rename($file_name, $backup_filename);

		return $result;
	}

	function getMemberInfo($member_id = 0, $member_name = '', $email = '', $passwd = '')
	{
		$query_string = "select * from {$this->_dbop->prefix}admin where";
		$added = FALSE;
		if (FALSE == empty($member_id))
		{
			$query_string .= " id=$member_id";
			$added = TRUE;
		}
		else
		{
			if (FALSE == empty($member_name))
			{
				$query_string .= " user='" . $member_name . "'";
				$added = TRUE;
			}
		}
	}
}
?>