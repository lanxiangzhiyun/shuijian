<?php
/**
 * 抽奖投注控制器
 *
 * @author: zlg
 * @created: 13-1-8
 */
class BetAction extends ExtendAction{
    /*
	*初始化
	*/
    public function _initialize()
    {

        parent::_initialize();
    }

    //兑奖中心
    public function index(){
        $where = '1=1';
        $url = '/iadmin.php/Bet/index?';
        $data = $this->_get('data');
        $blogID = $this->_get('blogID');
        //查询全部结果
        if (!empty($blogID)) {
            $strId = implode(',', $blogID);
            foreach ($blogID as $key=>$val) {
                     $this->recordOperations(3,18,$val,'','','','id','未受理','已受理');
            }
            if (!empty($strId)) {
                //受理咔咔
                D('Bet')->updateChangeStatus($strId);
            }
        }
        if (!empty($data['select']) || $data['select'] != '') {
            $where .= ' and change_status='.$data['select'];
            $url .= 'data[select]=' . $data['select'] . '&';
            $arrAssign['select'] = $data['select'];
        }
        if (!empty($data['user_name'])) {
            $where .= " and user_name='{$data['user_name']}'";
            $url .= 'data[user_name]=' . $data['user_name'] . '&';
            $arrAssign['user_name'] = $data['user_name'];
        }
        if (!empty($data['selectype']) && $data['selectype'] != '') {
            $where .= ' and type='.$data['selectype'];
            $url .= 'data[selectype]=' . $data['selectype'] . '&';
            $arrAssign['type'] = $data['selectype'];
        }
        if (!empty($data['starttime'])) {
            $intStartTime = strtotime($data['starttime']);
            $where .= ' and applychange_time >='.$intStartTime;
            $url .= 'data[starttime]='.$data['starttime'].'&';
            $arrAssign['starttime'] = $data['starttime'];
        }
        if (!empty($data['endtime'])) {
            $intEndtTime = strtotime($data['endtime'])+3600*24;
            $where .= ' and applychange_time <='.$intEndtTime;
            $url .= 'data[endtime]=' . $data['endtime'] . '&';
            $arrAssign['endtime'] = $data['endtime'];
        }
        $url .= 'p=';
        $page = intval($_GET['p']);
        $param['page'] = empty($page) ? 1 : $page;
        $param['page_num'] = 25;
        $param['action'] = $data['action'];
        $arrAssign['arrSearchList'] = D('Bet')->getSearchList($where, $param);
        $pcount = D('Bet')->pcount; //多少页
        $count = D('Bet')->count; //本页条数
        if ($pcount < $param['page']) {
           $param['page'] = $pcount;
        }
        $pageHtml = $this->page($url, $pcount, $param['page_num'], $param['page'], $count);
        $arrAssign['pageHtml'] = $pageHtml;
        $arrAssign['url'] = $url;
        $arrAssign['typeArr'] = array(1=>'狗',2=>'猫',3=>'其他');
        //导出excel
        if ($data['action'] == "exportExcel") {
            import('@.ORG.Util.PhpExcel');
            $doc[] = array('类型','日 期', '申请ID', '姓 名', '电 话', '地 区', '地 址', '兑换QQ', 'Email', '投入啵币', '商品名称','饲养宠物','宠物品种','状态');
            foreach ($arrAssign['arrSearchList'] as $k => $v) {
                $doc[] = array(str_replace(array("<font color='red'>",'</font>'), '', $v['typeName']),$v['applychange_time'], $v['user_name'], $v['name'], $v['phone'], $v['cityName'], $v['address'], $v['user_qq'], $v['email'], $v['change_icons'], $v['change_product'],$arrAssign['typeArr'][$v['pettype']],$v['pet'],$v['applyMsg']);
            }

            $xls = new Excel_XML;
            $xls->addArray($doc);
            $xls->generateXML("feedback_" . date("Y-m-d"));
            die;
        }
        
        foreach ($arrAssign as $key => $val) {
            $this->assign($key, $val);
        }
        $this->display('index');
    }

    //添加奖品
    public function prize()
    {
        $action = $this->_param('action');
//添加奖品界面
        if (empty($action)) {
            // 获得抽奖奖品总概率
            $rateSum = D('Bet')->getPrizeRateSum();
            $this->assign('rateSum',$rateSum);
            $this->assign('action', 'add');
            $this->assign('tip', '添加奖品');
            $this->display('prize');
////添加奖品操作
        } else if ($action == 'add') {
            $data = $this->_post();
            // echo "<pre>";print_r($data);exit();
			// 保存表单数据 包括附件数据
            $params = array();
            // 奖品名称
            $params['name']             = $data['name'] ? trim($data['name']) : alert('请输入名称！');
            // 奖品图片
            $params["attach_url"]       = $data['pic_path'] ? $data['pic_path'] : alert('请上传图片！');
            // 奖品说明
            $params['content']          = $data['content'] ? trim($data['content']) : alert('请输入说明！');
            // 根据类型判断兑奖/抽奖
            $params['ptype']            = $data['type'] - 1;
            if ($data['type'] == 1) {
                $params['djprice']      = $data['change'] ? trim($data['change']) : alert('请输入兑奖啵币数量！');
            }else{
                // 抽奖几率
                // $params['draw_rate']    = $data['draw_rate'] ? trim($data['draw_rate']) : alert('请输入抽奖几率！');
                // 设定总概率
                $params['draw_rate'] = trim($data['draw_rate']);
                if($params['draw_rate']){
                    $rateSum = D('Bet')->getPrizeRateSum();
                    $locarate = $rateSum + $params['draw_rate'];
                    if($locarate > 0.5){
                        alert('总概率已超过0.5，请重新输入');
                    }
                }
            }
            // 奖品价值
            if ($data['is_goods'] == 2 && !$data['prize_value']) {
                alert('当类别为啵币时请输入奖品价值（啵币）！');
            }
            $params['prize_value']  = trim($data['prize_value']);
            
            // 每天限制抽中/兑换数量
            $params['day_num_limit']    = trim($data['day_num_limit']);
            // 特别说明
            $params['special_explain']  = $data['special_explain'] ? trim($data['special_explain']) : '';
            
            //是否商品 0商品 1非商品 2啵币
            $params['is_goods'] = 0;
            if ($data['is_goods']) {
                $params['is_goods'] = $data['is_goods'];
            }
            //奖品url
            $params['prize_url']    = $data['prize_url'] ? trim($data['prize_url']) : '';
            $params['create_time']  = time();  
            $status = D('Bet')->addPrize($params);
            if ($status) {
                $this->recordOperations(1,19,$status);
                if ($data['type'] == 1) {
                    showmsg('添加奖品成功！','/iadmin.php/Bet/prize?action=list');
                }
                showmsg('添加奖品成功！','/iadmin.php/Bet/prize?action=lottery');
            } else {
                // echo "<script>alert('请重试');history.go(-1)</script>";
                alert('添加奖品失败！');
            }
//兑奖奖品列表界面
        } elseif ($action == 'list') {
            $url = '/iadmin.php/Bet/prize?action=list&';
            $pageP = intval($_GET['p']);
            $param['page'] = empty($pageP) ? 1 : $pageP;
            $param['page_num'] = 10;
            $arrPrizeList = D('Bet')->getPrizeList('status = 0 and ptype = 0', $param);
            $arrAssign['arrPrizeList'] = $arrPrizeList;
            $pcount = D('Bet')->pcount; //多少页
            $count = D('Bet')->count; //本页条数
            $url .= 'p=';
            $pageHtml = $this->page($url, $pcount, $param['page_num'], $param['page'], $count);
            $arrAssign['pageHtml'] = $pageHtml;

            // 奖品类型
            $arrAssign['type'] = array('兑奖奖品','抽奖奖品');
            // 兑奖title
            $arrAssign['tip'] = '兑奖奖品列表';
            foreach ($arrAssign as $key => $val) {
                $this->assign($key, $val);
            }
            $this->display('prizeList');
//抽奖奖品列表
        } elseif ($action == 'lottery') {
            $url = '/iadmin.php/Bet/prize?action=lottery&';
            $pageP = intval($_GET['p']);
            $param['page'] = empty($pageP) ? 1 : $pageP;
            $param['page_num'] = 10;
            $arrPrizeList = D('Bet')->getPrizeList('status = 0 and ptype = 1', $param);
            $arrAssign['arrPrizeList'] = $arrPrizeList;
            $pcount = D('Bet')->pcount; //多少页
            $count = D('Bet')->count; //本页条数
            $url .= 'p=';
            $pageHtml = $this->page($url, $pcount, $param['page_num'], $param['page'], $count);
            $arrAssign['pageHtml'] = $pageHtml;
            // 当前抽奖需要啵币数量
            $arrAssign['bobi'] = D('Bet')->getBobiNum(array('key'=>'draw_coins'));
            // 奖品类型
            $arrAssign['type'] = array('兑奖奖品','抽奖奖品');
            // 兑奖title
            $arrAssign['tip'] = '抽奖奖品列表';
            foreach ($arrAssign as $key => $val) {
                $this->assign($key, $val);
            }
            $this->display('lotteryList');
//编辑奖品界面
        } else if ($action == 'edit') {

            $arrAssign['pid'] = $this->_get('pid');

            //查询奖品信息
            $arrAssign['detail']  = D('Bet')->getPrizeInfo($arrAssign['pid']);
            // echo "<pre>";print_r($arrAssign['detail']);
            $arrAssign['action']  = 'editGo';
            $arrAssign['tip']     = '编辑奖品';
            if ($arrAssign['detail']['ptype'] == 0) {
                $arrAssign['url'] = '/iadmin.php/Bet/prize?action=list';
            }elseif ($arrAssign['detail']['ptype'] == 1) {
                $arrAssign['url'] = '/iadmin.php/Bet/prize?action=lottery';
            }
            // 获得抽奖奖品总概率
            $rateSum = D('Bet')->getPrizeRateSum();
            $this->assign('rateSum',$rateSum);
            //  统一加载
            foreach ($arrAssign as $key => $val) {
                $this->assign($key, $val);
            }
            $this->display('prize');
//编辑操作
        } elseif ($action == 'editGo') {
            $data = $this->_post();
            // echo "<pre>";print_r($data);exit();
            // 保存表单数据 包括附件数据
            $params = array();
            // 奖品名称
            $params['name']             = $data['name'] ? trim($data['name']) : alert('奖品名称不能为空！');
            // 奖品图片
            $params["attach_url"]       = $data['pic_path'] ? $data['pic_path'] : alert('奖品图片不能为空！');
            // 奖品说明
            $params['content']          = $data['content'] ? trim($data['content']) : alert('奖品说明不能为空！');
            // 根据类型判断兑奖/抽奖
            $params['ptype']            = $data['type'] - 1;
            if ($data['type'] == 1) {
                $params['djprice']      = $data['change'] ? trim($data['change']) : alert('兑奖啵币数量不能为空！');
                $params['draw_rate']    = '';
                $params['prize_value']  = '';
                // 操作记录字段
                $fields = 'prize_url,is_goods,special_explain,day_num_limit,djprice,ptype,content,attach_url,name';
            }else if($data['type'] == 2){
                // 抽奖几率
                // $params['draw_rate']    = $data['draw_rate'] ? trim($data['draw_rate']) : alert('抽奖几率不能为空！');
                $params['draw_rate'] = trim($data['draw_rate']);
                if($params['draw_rate']){
                    $rateSum = D('Bet')->getPrizeRateSum();
                    $locarate = $rateSum + $params['draw_rate'];
                    if($locarate > 0.5){
                        alert('总概率已超过0.5，请重新输入');
                    }
                }
               
                $params['djprice']      = '';
                // 操作记录字段
                $fields = 'prize_url,is_goods,special_explain,day_num_limit,prize_value,draw_rate,ptype,content,attach_url,name';
            }
             // 奖品价值
            if ($data['is_goods'] == 2 && !$data['prize_value']) {
                alert('当类别为啵币时请输入奖品价值（啵币）！');
            }
           
            $params['prize_value']  = trim($data['prize_value']);
            
            // 每天限制抽中/兑换数量
            $params['day_num_limit']    = trim($data['day_num_limit']);
            // 特别说明
            $params['special_explain']  = $data['special_explain'] ? trim($data['special_explain']) : '';
            
            //是否商品 0商品 1非商品 2啵币
            $params['is_goods'] = 0;
            if ($data['is_goods']) {
                $params['is_goods'] = $data['is_goods'];
            }
            //奖品url
            $params['prize_url']    = $data['prize_url'] ? trim($data['prize_url']) : '';
           
//修改前奖品信息

            $arrPrizeInfo = D('Bet')->getPrizeInfoEw ($data['pid'],$fields);
            $status = D('Bet')->editPrize($data['pid'],$params);
            if ($status) {
                // 编辑记录
                $arr = getChangeCloum($arrPrizeInfo,$params);
                // echo "<pre>";print_r($params);print_r($arrPrizeInfo);print_r($arr);exit;
                foreach ($arr as $k => $val) {
                    $this->recordOperations(3,19,$data['pid'],'','','',$val['column'],$val['beforeContent'],$val['afterContent']);
                }
                // echo "<script>alert('数据保存成功');location.href= '" . $_SERVER['HTTP_REFERER'] . "' ;</script>";
                if ($data['type'] == 1) {
                    showmsg('数据更新成功！','/iadmin.php/Bet/prize?action=list');
                }
                showmsg('数据更新成功！','/iadmin.php/Bet/prize?action=lottery');
            } else {
                alert('数据更新失败！');
            }
//删除 奖品
        } elseif ($action == 'delete') {
            $pid = $this->_get('pid');

            $status = D('Bet')->deletePrize($pid);
            if ($status) {
                $this->recordOperations(2,19,$pid);
                echo "<script>location.href= '" . $_SERVER['HTTP_REFERER'] . "' ;</script>";
            } else {
                alert('删除失败！');
            }
//上架或下架
        } // elseif ($action == 'setBobi') {
        //     $valid = $this->_get('valid') == 1 ? 0 : 1;
        //     $status = D('Bet')->editPrizeValid($this->_get('pid'), $valid);
        //     if ($status) {
        //         $this->recordOperations(3,19,$this->_get('pid'),'','','','valid',$this->_get('valid'),$valid);
        //         echo "<script>alert('操作成功');window.location.href='{$_SERVER['HTTP_REFERER']}';</script>";
        //     } else {
        //         echo "<script>alert('操作失败');history.back(-1)</script>";
        //     }
        // }

    }

    /**
     * 抽奖啵币设置
    */
    public function setBobi(){
        // 获得当前抽奖啵币数量
        $bobi = D('Bet')->getBobiNum(array('key'=>'draw_coins'));
        $this->assign('bobi',$bobi);
        
        $this->display('setBobi');
    }

    /**
     * 抽奖啵币设置
     */
    public function saveBobi(){
        // print_r($this->_post());exit;
        $bobi = $this->_post('bobi');
        $res = D('Bet')->saveBobiNum($bobi);
        if ($res) {
            showmsg('设置成功！','/iadmin.php/Bet/prize?action=lottery');
        }else{
            alert('设置失败！');
        }
    }

    /*******************************************************/
//    public function uploadImg()
//    { //$_SERVER['DOCUMENT_ROOT'] . "/../". C("WWW_FILENAME") . "/" . $upload_config['fileUrl'] . "/"
//        import('@.ORG.Util.UploadFile');
//        $upload = new UploadFile();
//        $upload->maxSize = 10485760; // 设置附件上传大小
//        $upload->allowExts = array('jpg', 'gif', 'png', 'jpeg'); // 设置附件上传类型
//        $path = '/data/exchange/';
//
//        if (!is_dir($path)) {
//            $temp = explode('/', $path);
//            $cur_dir = '';
//            for ($i = 0; $i < count($temp); $i++) {
//                $cur_dir .= $temp[$i] . '/';
//                if (!is_dir($cur_dir)) {
//                    mkdir($cur_dir, 0777);
//                }
//            }
//        }
//
//        $upload->savePath = $_SERVER['DOCUMENT_ROOT'] . "/../". C("WWW_FILENAME") .$path; // 设置附件上传目录
//        if (!$upload->upload()) { // 上传错误提示错误信息
//            $this->error($upload->getErrorMsg());
//        } else { // 上传成功 获取上传文件信息svnuc\Data\U\ADS
//            $info = $upload->getUploadFileInfo();
//        }
//
//        return $info;
//    }
}
