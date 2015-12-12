<?php
class UcSearchAction extends BaseAction {
	protected $arrType ;
	public function __construct() {
        parent::__construct();
		//需要登录验证的类型
		$this->arrType = array(1,2);
    }
	//搜索
	public function search(){
		$search = D('UcSearch');
		$userinfo = $this->_user;
		$param['uid'] = $userinfo['uid'];
		$type = $_GET['t'];
		if(in_array($type,$this->arrType) && !$param['uid']) {
			$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			header("Location: " . get_rewrite_url('User', 'login') . '?referer=' . urlencode($url)); exit;
		}
		$keyword = trim($_GET['keyword']);
		if (!mb_check_encoding($keyword, 'utf-8')){
			$keyword = iconv('gb2312', 'utf-8', $keyword);
		}
		if($keyword || $keyword === '0'){
			if($type == 1 || $type == ""){
				//找人
				if($keyword || $keyword === '0') $param['keyword'] = $keyword;
				
				$param['page'] = intval($_GET['p']);
				$param['page_num'] = 12;
				$list = $search->getSearchUserList($param);
				//print_r($list);
				if($search->total == 0){
					$list = $search->getRandUserList($param['uid']);
				}
				
				$this->assign('list', $list);
				import("ORG.Page");
				$Page = new Page($search->total, $param['page_num'],"UcSearch,search","","t=".$type."&keyword=".$keyword."");
				$this->assign('page', $Page->show());
				
				$this->assign('total',$search->total);
				$this->assign('keyword',$keyword);
				$this->assign('userinfo',$userinfo);
				$this->assign('p',intval($_GET['p']));
			}elseif($type == 2){
				$url = C('BBS_DIR').'/search?keyword='.urlencode($keyword);
				die('<script>location.href="'.$url.'";</script>');
				/*die('<script>window.open("'.$url.'");</script>');*/
			}elseif($type == 3){
				$url = $keyword?C('SHOP_DIR').'/search?keyword='.urlencode($keyword).'&cid=0&bid=0&aid=0':C('SHOP_DIR').'/search?keyword=&cid=0&bid=0&aid=0';
				die('<script>location.href="'.$url.'";</script>');
			}elseif($type == 4){
				$url = C('BK_DIR').'search?t='.$type.'&keyword='.urlencode($keyword);
				die('<script>location.href="'.$url.'";</script>');
			}elseif($type == 5){
				$url = C('VET_DIR').'/search-0-0-0-0-'.urlencode($keyword).'.html';
				die('<script>location.href="'.$url.'";</script>');
			}
		}else{
			$param['uid'] = $userinfo['uid'];
			$list = $search->getRandUserList($param['uid']);
			$this->assign('list', $list);
		}
		$this->display('search');
	}
	
}
?>