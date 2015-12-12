<?php

/**
 * API接口共通函数程序文件
 *
 * 创建日期：	2011-08-16
 * 创建者：	Fongson
 */


// 数据处理MODELS【开始】
/**
 * 通过SQL语句查询
 *
 * @param	string	$sql	检索SQL文
 */
function FindInfo($sql)
{
	global $db;
	$table_data = array();
	$rs = $db->query($sql);
	while($ret_rs = $db->fetch_array($rs))
	{
		$table_data[] = $ret_rs;
	}
	
	return $table_data;
}

/**
 * 通过数组添加数据
 *
 * @param	array	$Data_array	需要添加的键/值数组
 * @param	array	$Data_only	唯一键/值数组
 * @param	string	$tables		检索用数据库表
 *
 * @return	boolean	true/false	是否操作成功
 */
function AddInfo($Data_array = array(), $Data_Only = array(), $tables)
{
	global $db;
	$flag = false;
	$sql_key = "";
	$sql_value = "";
	$dot = "";
	// 获取数据库表结构
	$fields_data = FindInfo("show fields from " . $tables);
	foreach($fields_data AS $key=>$value)
	{
		$fields_array[$key] = $value;
	}
	$fields_count = count($fields_array);
	
	// 获取返回字段的值
	for($i=0; $i<$fields_count; $i++)
	{
		$field = $fields_array[$i];
		if(isset($Data_array[$field["Field"]]))
		{
			$flag=true;
			$sql_key .= $dot.$field["Field"];
			$sql_value .= $dot . "'" . $Data_array[$field["Field"]] . "'";
			$dot = " , ";
		}
	}
	if($flag)
	{
		if(IsSetBeing($Data_Only, $tables))
		{
			$sql = "INSERT INTO " . $tables . " (" . $sql_key . ") VALUES(" . $sql_value . ");";
			$db->query($sql);
			//$db->exec($sql);
			return true;
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}
/**
 * 通过数组修改数据
 *
 * @param	array	$Data_array	需要添加的键/值数组
 * @param	string	$where		检索用where子条件（不包含where关键词）
 * @param	string	$tables		检索用数据库表
 *
 * @return	boolean	true/false	是否操作成功
 */
function EditInfo($Data_array = array(), $where, $tables)
{
	global $db;
	$flag = false;
	$sql_str = "";
	$dot = " , ";
	// 获取数据库表结构
	$fields_obj = FindInfo("show fields from ".$tables);
	foreach($fields_obj AS $key=>$value)
	{
		$fields_array[$key] = $value;
	}
	$fields_count = count($fields_array);
	// 获取返回字段的值
	for($i=0; $i<$fields_count; $i++){
		$field = $fields_array[$i];
		if(isset($Data_array[$field["Field"]]))
		{
			$flag = true;
			$sql_str .= $dot . $field["Field"] . "='" . $Data_array[$field["Field"]] . "'";
		}
	}
	$sql_str = substr($sql_str, 3);
	if($flag){
		$sql = "UPDATE " . $tables . " SET " . $sql_str . " WHERE " . $where . ";";
		$db->query($sql);
	//	$db->exec($sql);
		return true;
	}else{
		return false;
	}
}

/**
 * 检测资料是否存在
 *
 * @param	array	$Data_Only	表唯一键/值数组
 * @param	string	$tables		查询数据库表名
 *
 * @return	boolean	是否存在（true：数据不存在；false：数据已存在）
 */
function IsSetBeing($Data_Only = array(), $tables)
{
	if(empty($Data_Only))
	{
		return true;
	}
	else
	{
		$table_data = FindInfo("select " . $Data_Only[0] . " from " . $tables . " where " . $Data_Only[0] . "='" . $Data_Only[1] . "'");
		$table_data_array = array();
		foreach($table_data as $key => $value)
		{
			$table_data_array[$key] = $value;
		}
		
		// 数据是否存在
		if(empty($table_data_array))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
// 数据处理MODELS【结束】

// 获取客户端真实IP地址【开始】
/**
 * 获取客户端IP地址
 * 
 * @return	string	IP地址
 */
function GetIPAddress(){
	if(!empty($_SERVER["HTTP_CLIENT_IP"])) 
	{
		$cip = $_SERVER["HTTP_CLIENT_IP"];
	}
	else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) 
	{
		$cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	}
	else if(!empty($_SERVER["REMOTE_ADDR"])) 
	{
		$cip = $_SERVER["REMOTE_ADDR"];
	}
	else 
	{
		$cip = "";
	}
	
	preg_match("/[\d\.]{7,15}/", $cip, $cips);
	$cip = $cips[0] ? $cips[0] : 'unknown';
	unset($cips);
	
	return $cip;
}
// 获取客户端真实IP地址【结束】

// 取得指定位随机数字字符串【开始】
/**
 * 取得指定位的随机数字字符串
 *
 * @param	int	$num	指定长度
 *
 * @return	string		随机数字字符串
 */
function GetRandNum($num)
{
	$rnd = '';
	for($i = 0; $i < $num; $i++)
	{
		$rnd .= mt_rand(0, 9);
	}
	
	return $rnd;
}
// 取得指定位随机数字字符串【结束】

// 用户名存在判断【开始】
/**
 * 判断用户名是否已存在数据库表中，如果已经存在，则根据用户来源加上随机数字字符串生成新的用户名，继续判断，直到没有重复为止
 * 新用户名 = 原用户名 + '_tb'/'_p' + 3位随机数
 * 
 * @param	string	$username		用户名
 * @param	string	$new_username	新用户名（默认为空）
 * @param	string	$froms_coms		网站来源（支付宝：taobao；云计算门户：pcn；默认为空）
 *
 * @return	string	不重复的用户名
 */ 
function GetNotExistUsername($username, $new_username = '', $froms_coms = '')
{
	global $db;
	
	// 判断用户名是否已存在
	if(empty($username)) 
	{
		$username = $froms_coms.GetRandNum(3).date('ymdHis',time());
	}

	// 判断用户名是否已存在
	if(empty($new_username))
	{
		$new_username = $username;
	}
	
	$user = $db->get_value("select username from boqii_users where username = '$new_username'");
	
	// 存在该用户名
	if(!empty($user))
	{
		// 重组用户名字符串（原用户名+网站来源标识+3位随机数字字符串）
		$new_username = $username;
		// 支付宝快捷登录
		if($froms_coms == 'taobao')
		{
			$new_username .= '_tb';
		}
		// 云计算门户
		else if($froms_coms == 'pcn')
		{
			$new_username .= '_p';
		}
		else if($froms_coms == 'qq')
		{
			$new_username .= '_qq';
		}
		else if($froms_coms == 'cb')
		{
			$new_username .= '_cb';
		}
		else if($froms_coms == 'rfanli'){//啊返利的用户名标识
			$new_username .= '';
		}
		else if($froms_coms == 'sina')
		{
			$new_username .= '_sa';
		}
		else
		{
			$new_username .= '_';
		}
		
		// 3位随机数字字符串
		$new_username .= GetRandNum(3);
		
		// 再次判断是否存在新的用户名字符串，直到不存在重复为止
		$new_username = GetNotExistUsername($username, $new_username, $froms_coms);
	}
	
	if(empty($new_username))
	{
		$new_username = $username;
	}

	return $new_username;
}
// 用户名存在判断【结束】

/**
 * 数据编码
 *
 * @param	string	$string
 * @param	string	$operation
 * @param	string	$key
 *
 * @return	string
 */
function set_authcode($string, $operation, $key = '') 
{

	$boqii_auth_key = md5($_SERVER['HTTP_USER_AGENT']);

	$key = md5($key ? $key : $boqii_auth_key);
	$key_length = strlen($key);

	$string = $operation == 'DECODE' ? base64_decode($string) : substr(md5($string . $key), 0, 8) . $string;
	$string_length = strlen($string);

	$rndkey = $box = array();
	$result = '';

	for($i = 0; $i <= 255; $i++) 
	{
		$rndkey[$i] = ord($key[$i % $key_length]);
		$box[$i] = $i;
	}

	for($j = $i = 0; $i < 256; $i++) 
	{
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) 
	{
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') 
	{
		if(substr($result, 0, 8) == substr(md5(substr($result, 8).$key), 0, 8)) 
		{
			return substr($result, 8);
		} 
		else 
		{
			return '';
		}
	} 
	else 
	{
		return str_replace('=', '', base64_encode($result));
	}
}

/**
 * 处理用户数据，设定用户登录COOKIE信息
 *
 * 如果用户是第一次访问波奇网，则为用户创建相应用户数据记录
 *
 * @param	array	$froms_data	参数数组
 * 								froms_coms 		网站来源，比如taobao，pcn
 * 								froms_uids 		来源网站用户id
 * 								froms_username	来源网站用户名
 *								froms_realname	来源网站用户姓名或昵称
 * 								froms_email 	来源网站用户email
 * 								froms_pass 		来源网站用户密码（密码，默认为空）
 * 								city_id 		用户收货地址id（默认为0）
 */
function operate_user($froms_datas)
{
	global $db;
	
	if(empty($froms_datas))
	{
		return;
	}
	
	// 提取参数
	extract($froms_datas);
	
	// 设置来源网站参数【开始】	
	$cookie_time = (time() + 3600 * 48);
	if(!$_COOKIE['froms_coms']){
		setcookie("froms_coms", $froms_coms, $cookie_time, '/', '.boqii.com', 0);
		setcookie("froms_uids", $froms_uids, $cookie_time, '/', '.boqii.com', 0);
	}
	setcookie("froms_username", $froms_realname, $cookie_time, '/', '.boqii.com', 0);
	// 设置来源网站参数【结束】	
			
	// 第一次登录创建网站用户并登录，之后访问本网站则直接登录
	// 查找用户是否已存在于网站中
	$uid = $db->get_value("select uid from boqii_users where froms_coms = '" . $froms_coms . "' and froms_uids = '" . $froms_uids . "' ");

	// 如果为第一次登录，会员信息尚未保存到网站数据库中，则创建网站用户并登录
	if(empty($uid))
	{
		// 设定初始值［开始］
		// 操作时间
		$action_time = time();
		// 用户IP地址
		$ips = GetIPAddress();	
		// 用户地址（默认为0）
		if(empty($city_id))
		{
			$city_id = 0;
		}
		//$data_insert_into = false;
		//$data_not_come_in = true;
		// 暂定为6位随机数字字符串
		if(empty($froms_pass))
		{
			$froms_pass = GetRandNum(6);
		}
		// MD5加密明码字符串
		$pwd = md5($froms_pass);
		// 设定初始值［结束］

		// 用户名存在判断，如果存在重复，则以用户名+'_tb'/'_p'/网站来源+3位随机数作为新的用户名判断，直至不重复为止
		$froms_username = GetNotExistUsername($froms_username, '', $froms_coms);
		
		//用户基础表
		$boqii_users = array();
		$boqii_users['username']	= $froms_username;
		$boqii_users["nickname"]	= $froms_username;
		$boqii_users["realname"]	= $froms_realname;
		$boqii_users['password']	= $pwd;
		$boqii_users['regip']		= $ips;
		$boqii_users["lastip"]		= $ips;
		$boqii_users['regdate']		= $action_time;
		$boqii_users["lastvisit"] 	= $action_time;
		$boqii_users["froms_coms"]	= $froms_coms;
		$boqii_users["froms_uids"]	= $froms_uids;
		$boqii_users["froms_pass"]	= $froms_pass;//明码存储密码串
		$email_pattern = "/([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?/i";
		if(preg_match($email_pattern,$froms_username))
		{
			$boqii_users["email"] 	= $froms_username;
		}				
		AddInfo($boqii_users, array("username", $froms_username), "boqii_users");
		
		// 取出刚刚添加的boqii_users表数据
		$table_data = FindInfo("select uid from boqii_users where username='" . $froms_username . "'");
		foreach($table_data as $values)
		{
			$uid = (int)$values["uid"];
			//$data_not_come_in = false;
		}
	
		//用户扩展表
		$boqii_users_extend = array();
		$boqii_users_extend['uid'] = $uid;
		$boqii_users_extend["city_id"] 	= $city_id;
		AddInfo($boqii_users_extend, array("uid", $uid), "boqii_users_extend");
		
		//blog扩展表新增数据
		//$blog_userinfo = array();
		//$blog_userinfo['uid']	= $uid;
		//$blog_userinfo['style']	= 'default';
		//AddInfo($blog_userinfo, array("uid", $uid), "blog_userinfo");
						
		//论坛扩展表
		$boqii_users_extendbbs = array();
		$boqii_users_extendbbs['uid'] = $uid;
		$boqii_users_extendbbs['groupid'] = 9;//默认用户组
		AddInfo($boqii_users_extendbbs, array("uid", $uid), "boqii_users_extendbbs");

	}
	// 用户已存在数据库中，取出用户信息
	$user = $db->get_one("select uid, password from boqii_users where froms_coms = '" . $froms_coms . "' and froms_uids = '" .$froms_uids ."' ");
	if($user)
	{
		
		$uid = $user['uid'];
		$pwd = $user['password'];
		// 写入cookie［开始］
		$time_now = time();
		$cookie_time = ($time_now + 3600 * 48);
		setcookie('boqii_auth', set_authcode("$pwd\t$uid",'ENCODE'), $cookie_time, '/', '.boqii.com', $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);
		setcookie('boqii_cookietime', $cookie_time, 0, '/', '.boqii.com', $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);
		// 写入cookie［结束］
	}
}

/**
	获取用户默认收货地址
*/
function getUserDefaultAddress($uid)
{
	global $db;
	$sql = "SELECT * FROM shop_accept_address_u WHERE uid='".$uid."' ORDER BY ifdefault DESC LIMIT 1";
	$addressInfo = $db->get_one($sql);
	return $addressInfo;

}
?>