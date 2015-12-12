<?php
//公共控制器
class PublicAction extends BaseAction {
	//404页面
	public function error () {
		if (empty($_GET['uid'])) {
			$_GET['uid'] = $this->_user['uid'];
		}
		$this->assign('uid', $_GET['uid']);
		$this->display('404');
	}

	//删除runtime缓存目录
	function remove_runtime ($dirName = null) {
		if ($dirName == null) {
			$dirName = '/webwww/uc/Runtime/Uc';
		}
		if (!is_dir($dirName)) {
			@unlink($dirName);
			return false;
		}
		$handle = @opendir($dirName);
		while (($file = @readdir($handle)) !== false) {
			if ($file != '.' && $file != '..') {
				$dir = $dirName . '/' . $file;
				is_dir($dir) ? $this->remove_runtime($dir) : @unlink($dir);
			}
		}
		closedir($handle);
		return rmdir($dirName);
	}

	//ajax方法：获取用户动态消息
	public function ajaxGetUserCnts () {
		$user = $this->_user;
		if ($user) {
			$userCnts = D("UcIndex")->ajaxGetUserCnts($user['uid']);
			if ($userCnts) {
				$data['status'] = $userCnts['status'];
				$data['fcnt'] = $userCnts['fcnt'];
				$data['ccnt'] = $userCnts['ccnt'];
				$data['mcnt'] = $userCnts['mcnt'];
				$data['ncnt'] = $userCnts['ncnt'];
			} else {
				$data['status'] = 'error';
			}
		} else {
			$data['status'] = 'login';
		}
		echo $_GET['callback'] . "({status:'" . $data['status'] . "',fcnt:'" . $data['fcnt'] . "',ccnt:'" . $data['ccnt'] . "',mcnt:'" . $data['mcnt'] . "',ncnt:'" . $data['ncnt'] . "',uid:'".$user['uid']."'});";
		exit;
	}

	//ajax方法：获取用户动态消息为个人中心所用
	public function ajaxGetUserCntsTwo () {
		$user = $this->_user;
		if ($user) {
			$userCnts = D("UcIndex")->ajaxGetUserCnts($user['uid']);
			if ($userCnts) {
				$data['status'] = $userCnts['status'];
				$data['fcnt'] = $userCnts['fcnt'];
				$data['ccnt'] = $userCnts['ccnt'];
				$data['mcnt'] = $userCnts['mcnt'];
				$data['ncnt'] = $userCnts['ncnt'];
			} else {
				$data['status'] = 'error';
			}
		} else {
			$data['status'] = 'login';
		}
		$this->ajaxReturn($data,'JSON');
		exit;
	}

	/**
	 * 对外接口，获取用户的关注数
	 * @param uid 用户编号
	 * @return int 关注数
	 */
	public function getAttentionCount () {
		$uid = $_GET['uid'];
		if (!$uid) {
			echo 0;
			exit;
		}
		//获取用户的关注数
		$attentions = D('UcRelation')->getOtherCounts($uid, 2);
		echo $attentions;
		exit;
	}

	//测试
	function sphinx () {
		error_reporting(E_ALL);
		header("Content-type: text/html; charset=utf-8");
		Vendor('coreseek.sphinxapi');
		$cl = new SphinxClient ();
		$cl->SetServer('192.168.107.129', 9312);
		//$cl->GetLastError();
		//设置超时时间
		$cl->SetConnectTimeout(10);
		//true以数组的格式返回
		$cl->SetArrayResult(true);
		//设置匹配模式
		$cl->SetMatchMode(SPH_MATCH_ANY);
		//设置排序模式
		$cl->SetSortMode(SPH_SORT_RELEVANCE);
		//设置属性过滤，用来过滤是帖子还是文章
		$cl->SetFilter('type', array(1), true);
		//设置结果集偏移量(分页)
		$cl->SetLimits(1, 8);
		//查询，第一个参数为要查询的内容，第二个参数为索引名
		$queryStr = '测试';
		$res = $cl->Query($queryStr, "bk_article_thread");
		print_r($res);
		exit;
		foreach ($res['matches'] as $key => $val) {
			if ($val['attrs']['type'] == 1) {
				$r = M('bk_article')->where('id=' . $val['attrs']['pid'])->field('title,content')->find();
			} else {
				$r = M('bk_thread')->where('id=' . $val['attrs']['pid'])->field('title,content')->find();
			}
			$r['content'] = strip_tags($r['content']);
			$opt = array("before_match" => "<font color='red'>", "after_match" => "</font>", "around" => 100, "chunk_separator" => "");
			$result = $cl->BuildExcerpts($r, 'bk_article_thread', $queryStr, $opt);
			print_r($result);
		}
		exit;
	}

	//测试
	function sphinx_user () {
		//error_reporting(E_ALL);
		//header("Content-type: text/html; charset=utf-8");
		//Vendor('coreseek.sphinxapi');
		//$cl = new SphinxClient ();
		//$cl->SetServer('192.168.107.129', 9312);
		//$cl->SetServer('172.166.76.251', 9312);
		//$cl->GetLastError();
		//设置超时时间
		//$cl->SetConnectTimeout(10);
		//true以数组的格式返回
		//$cl->SetArrayResult(true);
		//设置匹配模式
		//$cl->SetMatchMode(SPH_MATCH_ALL);
		//设置排序模式
		//$cl->SetSortMode(SPH_SORT_RELEVANCE);
		//设置属性过滤，用来过滤是帖子还是文章
		//$cl->SetFilter('type',array(1),true);
		//设置结果集偏移量(分页)
		//$cl->SetLimits(1,8);
		//查询，第一个参数为要查询的内容，第二个参数为索引名
		//$queryStr = 'ky';
		//$res = $cl->Query($queryStr, "user");
		import('@.ORG.Util.Coreseek');
		$co = new Coreseek(array('keyword'=>'uu','limit'=>200,'page'=>1,'conf'=>1));
		$res = $co->select();
		print_r($res);
		exit;
		foreach ($res['matches'] as $key => $val) {
			if ($val['attrs']['type'] == 1) {
				$r = M('bk_article')->where('id=' . $val['attrs']['pid'])->field('title,content')->find();
			} else {
				$r = M('bk_thread')->where('id=' . $val['attrs']['pid'])->field('title,content')->find();
			}
			$r['content'] = strip_tags($r['content']);
			$opt = array("before_match" => "<font color='red'>", "after_match" => "</font>", "around" => 100, "chunk_separator" => "");
			$result = $cl->BuildExcerpts($r, 'user', $queryStr, $opt);
			print_r($result);
		}
		exit;
	}

}

?>