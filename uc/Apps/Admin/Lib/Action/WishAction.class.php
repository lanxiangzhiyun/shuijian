<?php
class WishAction extends ExtendAction{
	//列表
	public function index(){
		$wishModel = D('Wish');
		
		//当前页
		$param['page'] = $_GET['page'];
		if($param['page'] =='' || !is_numeric($param['page'])){
			$param['page'] = 1;
		}
		//每页显示条数
		$param['pageNum'] = 10;
		
		//url地址
		$url='/iadmin.php/Wish/index?';
		
		$url.="page=";
		$list = $wishModel->getWishList($param);
		$this->assign('list', $list);
		//print_r($list);
		if($param['page'] >= $wishModel->pagecount){
			$param['page'] = $wishModel->pagecount;
		}
		$pageHtml = $this->page($url,$wishModel->pagecount,$param['pageNum'],$param['page'],$wishModel->subtotal);
		$this->assign('pageHtml',$pageHtml);
		$this->assign('url',$url.$param['page']);
		$this->assign('page',$param['page']);
		$this->assign('type',$param['type']);
		
		$this->display('index');
	}
	
	//显示编辑页面
	public function edit(){
		$id = $this->_get('id');
		if($id){
			$wishModel = D('Wish');
			$info = $wishModel->getWishDetail($id);
			$this->assign('info',$info);
		}
		$this->display('edit');
	}
	
	//处理编辑
	public function save(){
		header("Content-type: text/html; charset=utf-8"); 
		$wishModel = D('Wish');
		$id = $this->_post('id');
		if($id){
			$param['id'] = $id;
			$param['wish_num'] = $this->_post('wish_num');
			$wishModel->editInfo($param);
		}
		echo "<script>alert('修改成功');location.href='/iadmin.php/Wish/index';</script>";
		exit;
	}
	
	//导入数据
	public function addInfo(){
		$wishModel = D('Wish');
		$wishModel->addInfo();
		echo 'OK';
	}
}
?>