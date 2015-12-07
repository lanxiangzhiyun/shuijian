<?php
/**
 * 专题Action类
 *
 * @author: zlg
 * @created: 2013-08-01
 */
class SubjectAction extends ExtendAction {
	/**
	 * 专题列表页
	 */
	public function getList () {
		$arrAssign = array();
		$subjectModel = D('Subject');
		// URL参数
		$data = $this -> _get('data');
		$arrAssign['data'] = $data;
		// 分页参数
		// 当前页码
		$data['page']= isset($_GET['page']) ? $_GET['page'] : 1;
		// 页显数量
		$data['pageNum'] = 10;
		// 获取字段
		$data['fields'] = 'id,name,img_path,create_time,author,type,click_rate,pettype';
		// 当前页url地址
		$url='/iadmin.php/Subject/getList?';
		// 专题标题
		if($data['name'] && !in_array($data['name'],'输入专题内容关键字')){
			$url.='data[name]='.urlencode($data['name']).'&';
			$arrAssign['name'] = $data['name'];
		}
		// 开始日期
		if($data['start_time']){
			$url.='data[start_time]='.$data['start_time'].'&';
			$arrAssign['start_time'] = $data['start_time'];
		}
		if($data['end_time']){
			$url.='data[end_time]='.$data['end_time'].'&';
			$arrAssign['end_time'] = $data['end_time'];
		}
		// 专题类型
		if($data['type']){
			$url.=' data[type]='.$data['type'].'&';
			$arrAssign['type'] = $data['type'];
		}
		// 宠物类别
		if($data['pettype']){
			$url.=' data[pettype]='.$data['pettype'].'&';
			$arrAssign['pettype'] = $data['pettype'];
		}

		$url.="page=";

		// 获取专题类型
		$arrType =  C('ZT_TYPE');
		$arrAssign['arrType']  = $arrType;

		// 宠物类别
		$arrPetType =  C('PET_TYPE');
		$arrAssign['arrPetType']  = $arrPetType;

		// 获取专题列表
		$arrList = $subjectModel -> getList($data);
		$arrAssign['arrList'] = $arrList;

		// 分页数据
		$pageHtml = $this->page($url,$subjectModel->pagecount, $data['pageNum'],$data['page'],$subjectModel->subtotal);
		$this->assign('pageHtml',$pageHtml);

		foreach ($arrAssign as $key => $val) {
			$this -> assign($key,$val);
		}

		$this -> display('subjectList');
	}

	/**
	 * 新增专题/编辑专题页
	 */
	public function addSubject () {
		$arrAssign = array();

		// URL参数：专题id
		$id = $this -> _get('id');

		// 编辑专题
		if (intval($id)){
			$arrAssign['tip'] = '编辑专题';

			//获取专题
			$arrInfo =  M('zt_tb') -> where (array('id'=>$id,'status'=>0)) -> find();
			$arrAssign['arrInfo'] = $arrInfo;
		} 
		// 新增专题
		else {
			$arrAssign['tip'] = '新增专题';
		}

		// 获取专题类型
		$arrType =  C('ZT_TYPE');
		$arrAssign['arrType']  = $arrType;

		// 宠物类别
		// $arrPetType =  C('PET_TYPE');
		// $arrAssign['arrPetType']  = $arrPetType;

		foreach ($arrAssign as $key => $val) {
			$this -> assign($key,$val);
		}

		$this -> display('addSubject');

	}
	
	/**
	 * 保存专题
	 */
    public function addZt () {
        $data = $this -> _get('data');
        $subjectModel = D('Subject');
        $result = $subjectModel -> addSubject($data);
       if ($result) {
           echo "<script>location.href='/iadmin.php/Subject/getList'</script>";
       }else {
           echo "<script>alert('操作失败!');history.back();</script>";
       }
    }

    /**
	 * ajax 删除专题
	 */
    public function ajaxDelList () {
        $subjectModel = D('Subject');
        $ids = $this->_get('ajaxDelList');
        $act = $this->_get('act');
        $page = $this->_get('page');
        $idArr = array_filter(explode(',',$ids));

        $result =$subjectModel -> delSubject ($idArr) ;
        if(empty($act)){
            echo "<script>location.href='/iadmin.php/Subject/getList';</script>";
        }else{
            echo 1;
            exit;
        }
    }

}
