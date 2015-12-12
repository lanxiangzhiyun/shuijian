<?php
/**
 * Uc Comment Action类
 */
class UcCommentAction extends BaseAction {
    /**
     * 构造方法
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * 评论列表
     */
    public function commentList(){
        //用户信息
        $user = $this->_user;
        if($user) {
            //我的页面
            $obj = 'me';

            //未登录
            if(!$user['uid']) {
                header("Location: " . get_rewrite_url('User', 'login') . '?referer=' . 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); exit;
            }
			//类型：5：我发表的评论；6：我收到的回复
			$type = $this -> _get('t') ? $this -> _get('t') : 5;
            $ucIndexModel = D("UcIndex");

			//广告图片
			$advModel = D('Advertisement');
			$midad = $advModel->getAdvertisement('10009');
			$this->assign("midad", $midad);
			$rightad = $advModel->getAdvertisement('10008');
			$this->assign("rightad", $rightad);

            //系统公告
            $gonggaoList = $ucIndexModel->getAnnouncements(10);
            $this->assign("gonggaoList", $gonggaoList);
            //热门话题
            $hotThreads = $ucIndexModel->getIndexHotThreads();
            $this->assign("hotThreads", $hotThreads);
            //热门宠物日志
            $hotDiaryList = D("UcDiary")->getHotDiaryList();
            $this->assign("hotDiaryList", $hotDiaryList);
            $param['uid'] = $user['uid'];
            $param['loginuid'] = $user['uid'];
            $param['type'] =$type;//5：我发表的评论；6：我收到的回复
            $param['page_num'] = 20;
            $param['page'] = isset($_GET['p']) ? $_GET['p'] : 1;
            $dynamics = $ucIndexModel->getUserDynamics($param);
            $this->assign("type", $type);
            $this->assign("dynamics", $dynamics);

            import("ORG.Page");
            $Page = new Page($ucIndexModel->total, $param['page_num'], "UcComment,commentList",$type);
            $this->assign('page', $Page->show());

            $this->assign("location", "myComments");
            $this->assign("obj", "me");
            $this->display('commentList');
        }
        else {
            header("Location: " . get_rewrite_url('User', 'login') . '?referer=' . 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); exit;
        }
    }
}