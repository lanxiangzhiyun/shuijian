<?php
/**
 * 友情链接Action类
 *
 * @author zlg
 * @created 2013-08-07
 */
class HelpAction extends ExtendAction{
    /**
	 * 友情链接列表页
	 */
    public function getList () {
        $arrAssign = array();
        $helpModel = D('Help');
        $data = $this -> _get('data');
        $arrAssign['data'] = $data;
        //分页参数
        $data['page']= isset($_GET['page']) ? $_GET['page'] : 1;
        $data['pageNum'] = 10;
        // 字段
        $data['fields'] = 'id,title,url,sort,type,create_time';

		// 当前url地址
        $url='/iadmin.php/Help/getList?';
		// 查询条件
		// 友链名称
        if($data['title'] && !in_array($data['title'],'输入友情链接内容关键字')){
            $url.='data[title]='.urlencode($data['title']).'&';
            $arrAssign['title'] = $data['title'];
        }
		// 添加时间
        if($data['start_time']){
            $url.='data[start_time]='.$data['start_time'].'&';
            $arrAssign['start_time'] = $data['start_time'];
        }
        if($data['end_time']){
            $url.='data[end_time]='.$data['end_time'].'&';
            $arrAssign['end_time'] = $data['end_time'];
        }
		// 友链类型
        if ($data['type']){
            $url.=' data[type]='.$data['type'].'&';
            $arrAssign['type'] = $data['type'];
        }

        $url.="page=";

        // 获取友链类型
        $arrType =  C('HELP_TYPE');
        $arrAssign['arrType']  = $arrType;

		// 获取友链列表
        $arrList = $helpModel -> getList($data);
        $arrAssign['arrList'] = $arrList;
		// 获取分页信息
        $pageHtml = $this->page($url,$helpModel->pagecount, $data['pageNum'],$data['page'],$helpModel->subtotal);
        $this->assign('pageHtml',$pageHtml);

        foreach ($arrAssign as $key => $val) {
            $this -> assign($key,$val);
        }

        $this -> display('helpList');
    }

    /** 
	 * 添加友情链接页面
	 */
    public function addHelpList () {
        $arrAssign = array();
		// 友链id
        $id = $this -> _get('id');
		// 编辑页
        if (intval($id)){
            $arrAssign['tip'] = '编辑友情链接';

            //获取友情链接
            $arrInfo =  M('boqii_link') -> where (array('id'=>$id)) -> find();
            $arrAssign['arrInfo'] = $arrInfo;
        } 
		// 新增页
		else {
            $arrAssign['tip'] = '新增友情链接';
        }

        // 获取友情链接类型
        $arrType =  C('HELP_TYPE');
        $arrAssign['arrType']  = $arrType;

        foreach ($arrAssign as $key => $val) {
            $this -> assign($key,$val);
        }
        $this -> display('addHelpLIst');
    }

    /**
	 * 添加友情链接
	 */
    public function addHelp() {
		// URL参数
        $data = $this -> _get('data');
		// 新增友链
        $helpModel = D('Help');
        $result = $helpModel -> addList($data);
        if ($result) {
            echo "<script>location.href='/iadmin.php/Help/getList'</script>";
        }else {
            echo "<script>alert('操作失败!');history.back();</script>";
        }
    }

    /**
	 * 删除友情链接
	 */
    public function ajaxDelList () {
        $helpModel = D('Help');
		// URL参数：待删除友链id字符串（英文逗号串接）
        $ids = $this->_get('ajaxDelList');
		// URL参数：操作
        $act = $this->_get('act');
		// URL参数：当前页码
        $page = $this->_get('page');

		// 分割友链id字符串
        $idArr = array_filter(explode(',',$ids));
		// 删除友链
        $result =$helpModel -> delList ($idArr) ;
        if(empty($act)){
            echo "<script>location.href='/iadmin.php/Help/getList';</script>";
        }else{
            echo 1;
            exit;
        }
    }
}
