<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * SysLog driver for exBlog
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
 * 
 * @brief SysLog driver for exBlog
 * @author $Author$ 
 * @date $Date$
 */
class SysLog
{
	/**
	 * 
	 * @brief a list of messages. saved by level, and with fetching time
	 */
	var $message_bus = array();
	/**
	 * 
	 * @brief Start time for logging
	 */
	var $start_time = 0;
	/**
	 * 
	 * @brief log filename. Macro same as function strftime() in php could be used in it.
	 */
	var $log_file = './messages_%Y%m%d.log';
	var $_user_info = array();
	var $log_level = 0;
	/**
	 * 
	 * @brief Whether enable syslog
	 */
	var $log_enabled = FALSE;

	function SysLog($log_enabled, $log_file = '')
	{
		$this->start_time = time();
		$this->log_enabled = $log_enabled;
		if (FALSE == empty($log_file)) $this->log_file = $log_file;
		$this->log_file = strftime($this->log_file, time());
		$this->getUserInfo();
		$from_file = $_SERVER['PHP_SELF'];
		if (FALSE == empty($_SERVER['QUERY_STRING'])) $from_file .= "?{$_SERVER['QUERY_STRING']}";
		$this->fetchMessage("FROM $from_file" , 1, FALSE);
	} 

	/**
	 * 
	 * @brief Fetch messages
	 * @param  $message message to log
	 * @param  $level level of messages. 1 = Error, 2 = Notice, 3 = Variables dump, 0 = All
	 * @return The message would be return
	 */
	function fetchMessage($message, $level = 0, $additional_info = TRUE)
	{
		if (TRUE == $this->log_enabled)
		{
			if (TRUE == is_array($message))
			{
				for ($i = 0; $i < count($message); $i++) $this->fetchMessage($message[$i], $level);
			} 
			else $this->messages_bus[$level][] = array('time' => time(), 'message' => $message, 'additional_info' => $additional_info);
		} 
		return $message;
	} 

	/**
	 * 
	 * @brief Write messages into a log file by message level
	 * @param  $level Max level of messages. 1 = Error, 2 = Notice, 3 = Variables dump, 0 = All
	 * @param  $log_file filename for logging
	 */
	function write2LogFile($level = 0, $log_file = '')
	{
		$result = TRUE;
		if (TRUE == $this->log_enabled)
		{
			$content = '';
			$level = intval($level);
			if (TRUE == empty($log_file)) $log_file = $this->log_file;
			if (TRUE == empty($level) || FALSE == is_numeric($level)) $level = $this->log_level;
			if (TRUE == count($this->messages_bus))
			{
				foreach ($this->messages_bus as $key => $value)
				{
					if (0 == $level || $key <= $level)
					{
						for ($i = 0; $i < count($value); $i++)
						{
							if (TRUE == $value[$i]['additional_info']) $content .= "{$value[$i]['time']}|{$value[$i]['message']}|{$this->_user_info['ip']}|{$this->_user_info['agent']}|{$this->_user_info['refer']}\n";
							else $content .= "{$value[$i]['message']}\n";
						} 
					} 
				} 
				if (FALSE == empty($content))
				{
					$content = '# Start Log at ' . date("Y-m-d H:i:s", $this->start_time) . "\n$content";
					$file_id = @fopen($log_file, 'a+b');
					@flock($file_id, LOCK_EX);
					$result = @fwrite($file_id, $content);
					@flock($file_id, LOCK_UN);
					@fclose($file_id);
				} 
			} 
		} 
		return $result;
	} 

	/**
	 * 
	 * @brief Get information about a visitor
	 * @return A array used 'ip', 'agent' and 'refer' as elements
	 * @notice All elements in the return array:
	 * @li ip The IP address from which the user is viewing the current page
	 * @li agent Contents of the User-Agent: header from the current request, if there is one. This is a string denoting the user agent being which is accessing the page. A typical example is: Mozilla/4.5 [en] (X11; U; Linux 2.2.9 i586).
	 * @li refer The address of the page (if any) which referred the user agent to the current page. This is set by the user agent. Not all user agents will set this, and some provide the ability to modify HTTP_REFERER as a feature. In short, it cannot really be trusted.
	 */
	function getUserInfo()
	{
		if (FALSE == count($this->_user_info))
		{ 
			// Get IP address
			if (FALSE == empty($_SERVER['HTTP_CLIENT_IP'])) $this->_user_info['ip'] = $_SERVER['HTTP_CLIENT_IP'];
			elseif (FALSE == empty($_SERVER['HTTP_X_FORWARDED_FOR'])) $this->_user_info['ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
			else $this->_user_info['ip'] = $_SERVER['REMOTE_ADDR']; 
			// Get user agent
			$this->_user_info['agent'] = $_SERVER['HTTP_USER_AGENT']; 
			// Get referer
			if (FALSE == empty($_SERVER['HTTP_REFERER'])) $this->_user_info['refer'] = $_SERVER['HTTP_REFERER'];
			else $this->_user_info['refer'] = '';
		} 

		return $this->_user_info;
	} 
} 

?>
