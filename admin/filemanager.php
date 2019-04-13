<?php
/* *
 * PHP Connector for the File Manager in the FCKeditor
 * -------------------------------------------
 *    POST VARIABLES
 *    	Command	:  FileUpload/GetFolders/_getFoldersAndFiles()Default)/CreateFolder
 *    	Type		:  
 *    	CurrentFolder	: The current path of a folder
 * */

require_once("./public.php");

/**
 * @class FileManager filemanager.php "/admin/filemanager.php"
 * @brief A file manager interface for FCKEditor
 * @author feeling <feeling@exblog.org>
 * @date $Date$
 **/
class FileManager extends CommonLib
{

	/**
	 * @internal
	 * @brief Error messages
	 **/
	var $errors = array();

	/**
	 * @brief the current command.
	 * @note Fetch from GET data
	 **/
	var $command = "GetFoldersAndFiles";

	/**
	 * @brief Type of a folder
	 * @warning This attribute was not used and it always was empty
	 **/
	var $type = "";

	/**
	 * @brief the current folder
	 * @note It was same as field 'destination_folder' in the table upload, not a real path on file system of your server.
	 **/
	var $current_folder = "/";

	/**
	 * @internal
	 * @brief Input data
	 **/
	var $_input = array();

	/**
	 * @brief the default command. It was used if #$command was empty.
	 **/
	var $default_command = "GetFoldersAndFiles";

	/**
	 * @brief base path(prefix) of real path of all folders.
	 * @warning In this version, it was real path of the directory '/upload', and you could not change it.
	 **/
	var $base_path = "";

	/**
	 * @brief Setting of the current folder
	 * @note It was a array, fields in the table upload were all of its indices
	 **/
	var $folder_setting = "";

	/**
	 * @internal
	 * @brief NULL
	 **/
	var $base_path = "";

	/**
	 * @brief Constructor
	 **/
	function FileManager()
	{

		$this->_input = array_merge($_GET, $_POST);
		$this->_input['_GET'] = $_GET;
		$this->_input['_POST'] = $_POST;

		$commands = array("FileUpload", "GetFolders", "GetFoldersAndFiles", "CreateFolder");
		$this->base_path = realpath("../upload");

		if (FALSE == empty($this->_input['_GET']['Command'])) $this->command = $this->_input['_GET']['Command'];
		if (FALSE == in_array($this->command, $commands)) $this->command = $this->default_command;
		if (FALSE == empty($this->_input['_GET']['CurrentFolder'])) $this->current_folder = $this->_input['_GET']['CurrentFolder'];

		$this->base_path = realpath("../upload");

		$this->_loadDatabaseConfig();

		$this->folder_setting = $this->_getFolderSettingByPath($this->current_folder);

		if ('FileUpload' == $this->command) $this->UploadFile();
		else
		{
			$this->setXmlHeader();

			if ('GetFolders' == $this->command || 'GetFoldersAndFiles' == $this->command) $this->printFoldersAndFiles();
			elseif ('CreateFolder' == $this->command) $this->createFolder();

			$this->_setXmlFooter();
		}

		exit();
	}

	/**
	 * @internal
	 * @brief send header for controling data stream
	 **/
	function _setHeader()
	{
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT') ;
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT') ;
		header('Cache-Control: no-store, no-cache, must-revalidate') ;
		header('Cache-Control: post-check=0, pre-check=0', false) ;
		header('Pragma: no-cache') ;
		header( 'Content-Type:text/xml; charset=utf-8' ) ;
	}

	/**
	 * @internal
	 * @brief send XML header and XML defined lines
	 **/
	function setXmlHeader()
	{
		$this->_setHeader();
		echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?>";
		echo "<Connector command=\"{$this->command}\" resourceType=\"{$this->type}\">";
		echo "<CurrentFolder path=\"" . $this->_convertToXmlAttribute($this->current_folder) . "\" url=\"{$this->folder_setting['url']}\" />";
	}

	/**
	 * @internal
	 * @brief send footer of a XML file
	 * @see FileManager::setXmlHeader()
	 **/
	function _setXmlFooter()
	{
		echo "</Connector>";
	}

	/**
	 * @brief Print out a list of sub-folders and(or) files in a folder
	 * @note The list was in XML format as bellow:
	 * @n &lt;Folders&gt;
	 * @n   &lt;Folder name="folder name" /&gt;
	 * @n &lt;/Folders&gt;
	 * @n &lt;Files&gt;
	 * @n &lt;File name="filename" size="file size" /&gt;
	 * @n &lt;/Files&gt;
	 * @internal
	 * @see FileManager::_getFoldersAndFiles()
	 **/
	function printFoldersAndFiles()
	{
		$folders_and_files = $this->_getFoldersAndFiles();
		echo "<Folders>";
		for ($i = 0; $i < count($folders_and_files['dir']); $i++) echo "<Folder name=\"" . $this->_convertToXmlAttribute(basename($folders_and_files['dir'][$i]['destination_folder'])) . "\" />";
		echo "</Folders>";
		if (TRUE == count($folders_and_files['file']))
		{
			echo "<Files>";
			for ($i = 0; $i < count($folders_and_files['file']); $i++) echo "<File name=\"" . $this->_convertToXmlAttribute($folders_and_files['file'][$i]['name']) . "\" size=\"{$folders_and_files['file'][$i]['size']}\" />";
			echo "</Files>";
		}
	}

	/**
	 * @internal
	 * @brief Get a list of all sub-folders and(or) files in a folder
	 * @param $path Path of a folder. It was same as field 'destination_folder' in the table upload.
	 * @warning the paramter $path was not used. The current folder #$current_folder always was used.
	 * @return A array with two elements, 'dir' and 'file', would be return if successful.
	 *   @li element 'dir' only included a array which had used directory name to be its exclusive value,
	 *   @li many arrays which had two elements, 'name' and 'size', composed the element 'file'
	 **/
	function _getFoldersAndFiles($path = "")
	{
		$entries = array("dir" => array(), "file" => array());
		if (TRUE == empty($path)) $path = $this->current_folder;
		if (TRUE == count($this->folder_setting))
		{
			// Get directories in a directory
			$query_string = "select destination_folder from {$this->_dbop->prefix}upload where destination_folder REGEXP '^{$this->folder_setting['destination_folder']}([^/]*)/$'";
			echo $query_string;
			$this->_dbop->query($query_string);
			if (TRUE == $this->_dbop->getNumRows()) $entries['dir'] = $this->_dbop->fetchArrayBat(0, 'ASSOC');
			$this->_dbop->freeResult();

			// Get files in a directory
			if ('GetFoldersAndFiles' == $this->command)
			{
				$dir_id = @dir($this->base_path . $path);
				while (FALSE !== ($entry = $dir_id->read()))
				{
					if ("." !== $entry && ".." !== $entry && TRUE == @is_file($this->base_path . $path . $entry))
					{
						$entries['file'][] = array(
							"name"	=> $entry,
							"size"		=> filesize($this->base_path . $path . $entry)
						);
					}
				}
				$dir_id->close();
			}
		}
		return $entries;
	}

	/**
	 * @brief Create a sub-folder in a folder
	 * @param $path Path of a folder. It was same as field 'destination_folder' in the table upload.
	 * @warning the paramter $path was not used. The current folder #$current_folder always was used.
	 **/
	function createFolder($path = "")
	{
		if (TRUE == empty($path)) $path = $this->current_folder;
		if (FALSE == empty($this->_input['_GET']['NewFolderName']))
		{
			// was the folder existed?
			$query_string = "select destination_folder from {$this->_dbop->prefix}upload where destination_folder='" . $path . trim($this->_input['_GET']['NewFolderName']) . "'";
			$this->_dbop->query($query_string);
			if (TRUE == $this->_dbop->getNumRows()) $this->errors[] = 'The folder was existed';
			$this->_dbop->freeResult();
			if (FALSE == count($this->errors))
			{
				$real_path = $this->base_path . $path . trim($this->_input['_GET']['NewFolderName']);
				if (TRUE == @mkdir($real_path, 0755))
				{
					$query_string = "insert into {$this->_dbop->prefix}upload (
						max_file_size, destination_folder, up_type, max_width, max_height, watermark_type, watermark, url
						) values (
						{$this->folder_setting['max_file_size']}, '" . $path . trim($this->_input['_GET']['NewFolderName']) . "/',
						'{$this->folder_setting['up_type']}', {$this->folder_setting['max_width']}, {$this->folder_setting['max_height']},
						'{$this->folder_setting['watermark_type']}', '{$this->folder_setting['watermark']}',
						'{$this->folder_setting['url']}" . trim($this->_input['_GET']['NewFolderName']) . "/'
						)";
					$this->_dbop->query($query_string);
					$this->_dbop->freeResult();
					return TRUE;
				}
				else $this->errors[] = 'Could not create the folder';
			}
		}
		else $this->errors[] = 'Name of the folder must not be empty';
		if (TRUE == count($this->errors))
		{
			// Create the "Error" node.
			for ($i = 0; $i < count($this->errors); $i++) echo '<Error number=\"103\" originalDescription=\"' . $this->_convertToXmlAttribute($this->errors[$i]) . '\" />' ;
		}
		return $this->errors;
	}

	/**
	 * @brief Upload File in a folder
	 * @param $path Path of a folder. It was same as field 'destination_folder' in the table upload.
	 **/
	function UploadFile($path = '')
	{
		if (TRUE == empty($path)) $path = $this->current_folder;
		include_once("../include/upload.php");
		$uploadop = new Upload("NewFile");
		$uploadop->setDir($this->base_path . $this->folder_setting['destination_folder']);
		$uploadop->setFilename(time());
		$uploadop->setDir($path);
		$uploadop->allow_exts = explode(",", $this->folder_setting['up_type']);
		$uploadop->max_filesize = intval($this->folder_setting['max_file_size']) * 1.024 * 1.024;
		$uploadop->doCopy();
		$result = $uploadop->result_code;
		$filename = $uploadop->getFilename();
		unset($uploadop);

		switch ($result)
		{
			case 2: $error_number = 202; break;
			case 6: $error_number = 201; break;
			default : $error_number = 0; break;
		}

		echo '<script type="text/javascript">';
		echo 'window.parent.frames["frmUpload"].OnUploadCompleted(' . $error_number . ',"' . str_replace('"', '\\"', basename($filename)) . '");';
		echo '</script>' ;
	}

	/* *
	 * @internal
	 * @brief Get setting for a folder
	 * @param $path Path of a folder. It was same as field 'destination_folder' in the table upload.
	 **/
	function _getFolderSettingByPath($path = "")
	{
		if (TRUE == empty($path)) $path = $this->current_folder;
		$folders_setting = array();
		$query_string = "select * from {$this->_dbop->prefix}upload";
		if (FALSE == empty($path)) $query_string .= " where destination_folder='$path'";
		$this->_dbop->query($query_string);
		if (TRUE == $this->_dbop->getNumRows()) $folders_setting = $this->_dbop->fetchArrayBat(0, 'ASSOC');
		$this->_dbop->freeResult();
		if (FALSE == empty($path)) $folders_setting = $folders_setting[0];
		return $folders_setting;
	}

	/**
	 * @internal
	 * @brief Convert string to UTF-8 encoding
	 **/
	function _convertToXmlAttribute($value)
	{
		return utf8_encode(htmlspecialchars($value));
	}
}

new FileManager();
?>