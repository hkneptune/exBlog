<?
 /**
  *	用户提交数据检测类
  *	检测用户提交数据合法性
  *  useage:
  *		#1> include(rss.inc.php);
  *		#2> $check = new CheckData();
  *		#3> if(!$check->checkEmail("elliott.fengling.net", true))
  *				echo "Email格式有误";
  *          if(!$check->checkUrl("http://elliott.fengling.net", true))
  *				echo "网站地址有误";
  *      #4> 更多详细信息请参考下面各种方法前的注释
  *		  
  *	version: 1.0.0
  *  author: elliott [at] 2004-08-29
  */
 
 class CheckData
 {
 	/**
 	 *	检测email格式合法性
 	 *	@param $email 用户email地址
 	 *	@param $stuff 为 true 时email必须填写, 为 false 时email不是必须要填写的
 	 *	@return email格式合法则返回 true, 否则返回 false
 	 */
 	function checkEmail($email, $stuff)
 	{
 		if($stuff == true)
 		{
 			if($email == "")
 				return false;
 			if(!eregi("^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$",$email))
 				 return false;
 		}
 		elseif($stuff == false)
 		{
 			if($email != "")
 			{
 				if(!eregi("^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$",$email))
 					 return false;
 			}
 		}
 		return true;
 	}
     
 	/**
 	 *	检测用户姓名格式合法性,姓名长度不能少于3个字符
 	 *	@param $name 用户姓名变量
 	 *	@param $stuff 为 true 时姓名必须填写, 为 false 时姓名不是必须要填写的
 	 *	@param $en 为 true 时姓名必须为英文和下划线, 为 false 时可以输入中文
 	 *	@return 姓名格式合法则返回 true, 否则返回 false
 	 */
 	function checkName($name, $stuff, $en)
 	{
 		if($stuff == true)
 		{
 			if($name == "")
 				return false;
 			if($en == true)
 			{
 				if(!eregi("^[a-zA-Z0-9_]{3,30}$", $name))
 					return false;
 			}
 			else if($en == false)
 			{
 				if(eregi("[<>{}(),%#|^&!`$~/=\@*]",$name))
 					return false;
 			}
 		}
 		else if($stuff == false)
 		{
 			if($name != "")
 			{
 				if($en == true)
 				{
 					if(!eregi("^[a-zA-Z0-9_]{3,30}$", $name))
 						return false;
 				}
 				else if($en == false)
 				{
 					if(eregi("[<>{}(),%#|^&!`$~/=\@*]",$name))
 						return false;
 				}
 			}
 		}
 		return true;
 	}
 
 	/**
 	 *	检测用户密码合法性
 	 *	@param $p1 第一次输入的密码
 	 *	@param $p2 第二次输入的密码
 	 *	@param $stuff 为 true 时密码必须填写, 为 false 时密码不是必须要填写的
 	 *	@return 密码格式合法则返回 true, 否则返回 false
 	 */
 	function checkPassword($p1, $p2, $stuff)
 	{
 		if(strcmp($p1, $p2) != 0)
 			return false;
 		if($stuff == true)
 		{
 			if($p1 == "" || $p2 == "")
 				return false;
 			if(strlen($p1) < 6 || strlen($p1) > 30)
 				return false;
 			if(eregi("[<>{}(),%#|^&!`$]", $p1))
 				return false;
 		}
 		else if($stuff == false)
 		{
 			if($p1 != "" && $p2 != "")
 			{
 				if(strlen($p1) < 6 || strlen($p1) > 30)
 					return false;
 				if(eregi("[<>{}(),%#|^&!`$]", $p1))
 					return false;
 			}
 		}
 		return true;
 	}
 
 	/**
 	 *	检测网站URL合法性,网站地址必须以http://开头
 	 *	@param $url 网站URL地址
 	 *	@param $stuff 为 true 时网站URL必须填写, 为 false 时网站URL不是必须要填写的
 	 *	@return 网站URL格式合法则返回 true, 否则返回 false
 	 */
 	function checkUrl($url, $stuff)
 	{
 		if($stuff == true)
 		{
 			if($url == "")
 				return false;
 			if(!eregi("^http://.+\..+$", $url))
 				return false;
 		}
 		else if($stuff == false)
 		{
 			if($url != "")
 			{
 				if(!eregi("^http://.+\..+$", $url))
 					return false;
 			}
 		}
 		return true;
 	}
 
 	/**
 	 *	其它杂项输入检测
 	 *	@param $val 用户输入的值
 	 *	@param $stuff 为 true 时 $val 必须填写, 为 false 时 $val 不是必须要填写的
 	 *	@param $len 为数值时,表示 $val 的值必须达到 $len 的长度, 如不须检测填 0 即可
 	 *	@return $val 格式合法则返回 true, 否则返回 false
 	 */
 	function checkOther($val, $stuff, $len)
 	{
 		if($stuff == true)
 		{
 			if($val == "")
 				return false;
 			if(strlen($val) < $len)
 				return false;
 		}
 		else if($stuff == false)
 		{
 			if($val != "")
 			{
 				if(strlen($val) < $len)
 					return false;
 			}
 		}
 		return true;
 	}
 
 	/**
 	 *	检测 $val 是否为纯数字
 	 *	@param $val 用户输入的值
 	 *	@param $stuff 为 true 时 $val 必须填写, 为 false 时 $val 不是必须要填写的
 	 *	@param $mixLen 为数值时,表示 $val 的值必须达到 $mixLen 的长度, 如不须检测填 -1 即可
 	 *	@param $maxLen 为数值时,表示 $val 的值必须小于 $maxLen 的长度, 如不须检测埴一大数值即可
 	 *	@return $val 格式合法则返回 true, 否则返回 false
 	 */
 	function checkNumberOnly($val, $stuff, $mixLen, $maxLen)
 	{
 		if($stuff == true)
 		{
 			if($val == "")
 				return false;
 			if(strlen($val) < $mixLen || strlen($val) > $maxLen)
 				return false;
 			if(!eregi("^[0-9]+$", $val))
 				return false; 
 		}
 		else if($stuff == false)
 		{
 			if($val != "")
 			{
 				if(strlen($val) < $mixLen || strlen($val) > $maxLen)
 					return false;
 				if(!eregi("^[0-9]+$", $val))
 					return false; 
 			}
 		}
 		return true;
 	}
 
 }
 
 ?>