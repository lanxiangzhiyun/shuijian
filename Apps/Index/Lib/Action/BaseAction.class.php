<?php
/*
*后台中心的权限，登陆判断
*/
class BaseAction extends Action{

	// 用户信息
	protected $_user = '';
	/**
	 * 构造方法
	 */
	public function __construct() {
		parent :: __construct();
		// 初始化登录用户信息
		safe();
		// 初始化登录用户信息
		$userinfo = user_login_check();
		$this -> _user = $userinfo;
	}

	
	/**
	 * 检查用户是否登录，如果未登陆跳转至登陆页面
	 *
	 * @param url $ 未登陆要指定跳转的url
	 */
	protected function checkLogin($url = null) {
		if (empty($this -> _user)) {
			$currentUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			if (!empty($url)) {
				$currentUrl = $url;
			}
			$loginUrl = C("BLOG_DIR") . '/user/login?referer=';
			header("Location:" . $loginUrl . $currentUrl);
		}
	}

	protected function _empty(){
		header("HTTP/1.0 404 Not Found");
		$this->display('Public:404');
		exit;
	}

	/*
	*分页方法
	*/
	protected function page($url,$pcount,$limit,$page,$count){
		import('@.ORG.Util.Page');
		$pages = new Page($url,$pcount,$limit,$page,$count);
		return $pages->pageHtml();
	}

	
}
?>