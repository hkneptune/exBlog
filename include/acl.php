<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * ACL(ACL: Access Control List) driver for exBlog
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
 * @class Acl acl.php "/include/acl.php"
 * @brief ACL(ACL: Access Control List) driver for exBlog
 * @author $Author$ 
 * @date : $Date$
 */
class Acl
{
	var $_errors = array();
	var $current_action = '';
	var $current_module = '';
	var $_dbop = 0;
	var $_user_info = array();
	var $acl_rules = array();

	function Acl(&$error_handler, $module_name = '*-*', $enabled = TRUE)
	{
		$this->_errors = &$error_handler;
		$this->current_module = $module_name;
		$this->enabled = $enabled;
	} 

	function handleAcl()
	{
		if (FALSE == preg_match('/^[[core]|[plugin]]\-/', $this->current_module)) $this->_errors[] = 'Invalid module name';
		elseif (FALSE == $this->enabled) $this->_errors[] = 'The ACL module was not enabled';
		else
		{
			if ('drop' == $this->compareAclRules($this->getAclRules($this->current_module), $this->getUserInfo())) return FALSE;
		} 
		return TRUE;
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

	/**
	 * 
	 * @brief Get ACL rules
	 * @param  $module_name Module name, it must be same as what saved in the module list
	 * @return A list of all matched rules. all elements in the list use a same name as name of fields in the table acl
	 */
	function getAclRules($module_name = '*-*')
	{
		$tmp_query_condition = ''; 
		// Generate SQL query string
		$query_string = "select * from {$this->_dbop->prefix}acl where";
		$tmp_splitor = explode('-', $module_name);
		if (2 > count($tmp_splitor)) $this->_errors[] = 'Invalid module found!';
		elseif ('*-*' != $module_name)
		{
			if ('*' == $tmp_splitor[0]) $tmp_query_condition .= " acl_module LIKE '%-";
			else $tmp_query_condition .= " acl_module LIKE '{$tmp_splitor[0]}-";
			if ('*' == $tmp_splitor[1]) $tmp_query_condition .= "%'";
			else $tmp_query_condition .= "{$tmp_splitor[1]}' or";
		} 
		$query_string .= $tmp_query_condition . ' acl_module="*-*" order by acl_type desc, acl_action asc'; 
		// Fetch ACL rules
		if (FALSE == count($this->_errors))
		{
			$this->_dbop->query($query_string);
			$this->acl_rules = $this->_dbop->fetchArrayBat(0, 'ASSOC');
			$this->_dbop->freeResult();
		} 
		return $this->acl_rules;
	} 

	/**
	 * 
	 * @brief Get method for how to do with a request
	 * @param  $acl_rules A list of ACL rules
	 * @param  $user_info Information about a visitor
	 * @return the method for how to do with a request, it would be 'accept' or 'drop'
	 */
	function compareAclRules($acl_rules, $user_info)
	{
		for ($i = 0; $i < count($acl_rules); $i++)
		{
			if (
				TRUE == $this->_compareIpAddress($user_info['ip'], $acl_rules[$i]['acl_ipzone']) // vatify IP address|| TRUE == $this->_comparePcre($user_info['agent'], $acl_rules[$i]['acl_agent']) // vatify User Agent|| TRUE == $this->_comparePcre($user_info['refer'], $acl_rules[$i]['acl_reger']) // vatify Referer
					) $this->current_action = $acl_rules[$i]['acl_action'];
			if ('drop' == $this->current_action || 'accept' == $this->current_action) break;
		} 
		return $this->current_action;
	} 

	/**
	 * 
	 * @internal 
	 * @brief Verify IP address
	 */
	function _compareIpAddress($ip_address_one, $ip_address_two)
	{
		$tmp_ip_splitor = explode('.', $ip_address_two);
		$step_length = min(count($tmp_ip_splitor), 4);
		for ($i = 0; $i < $step_length; $i++)
		{
			if ('*' == $tmp_ip_splitor[$i]) $tmp_pattern .= '\d{1,3}\.';
			else
			{
				$tmp_splitor = explode(',', $tmp_ip_splitor[$i]);
				for ($j = 0; $j < count($tmp_splitor); $j++)
				{
					if (TRUE == preg_match('/\d+\-\d+/', $tmp_splitor[$j])) $tmp_pattern .= '[{$tmp_splitor[$j]}]|';
					else $tmp_pattern .= "{$tmp_splitor[$j]}|";
				} 
				$tmp_pattern = substr($tmp_pattern, 0, -1) . '\.';
			} 
		} 
		$tmp_pattern = '/' . substr($tmp_pattern, 0, -2) . '/';
		return $this->_comparePcre($ip_address_one, $tmp_pattern);
	} 

	/**
	 * 
	 * @internal 
	 * @brief Verify by PCRE
	 */
	function _comparePcre($subject, $pattern)
	{
		if (TRUE == empty($pattern)) return FALSE;
		else return preg_match($pattern, $subject);
	} 
} 

?>
