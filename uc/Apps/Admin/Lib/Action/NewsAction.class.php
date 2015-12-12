<?php
/**
 * 资讯管理
 * @author: Seven
 * @Created: 14-7-7
 */
class NewsAction extends ExtendAction{

    /**
     * 资讯列表
     */
	public function newsList(){
		
		$limit = 20;
		$page=$this->_get('page');
		if($page=='' || !is_numeric($page)){
			$page=1;
		}
		$url='/iadmin.php/News/newsList?';
		$where="status != -1";
		if($this->_get('data')){
			$data = $this->_get('data');
			if(trim($data['title']) && ($data['title'] != '输入标题关键字')){
				$where .= " and title LIKE '%{$data['title']}%' ";
				$url .= "data[title]={$data['title']}&";
				$this->assign('title',$data['title']);
			}
			if(trim($data['starttime'])){
				$where.=" and create_time >= ".strtotime($data['starttime'].' 00:00:00');
				$url.='data[starttime]='.$data['starttime'].'&';
				$this->assign('starttime',$data['starttime']);
			}
			if(trim($data['endtime'])){
				$where.=" and create_time <= ".strtotime($data['endtime'].' 23:59:59');
				$url.='data[endtime]='.$data['endtime'].'&';
				$this->assign('endtime',$data['endtime']);
			}
			if($data['status'] != 'all' &&  (isset($data['status'])) ){
				$where .= " and status = {$data['status']}";
				$url .= "data[status]={$data['status']}&";
				$this->assign('status',$data['status']);
					
			}
			if(trim($data['big_column_id'])){
				
				$where .= " and big_column_id = {$data['big_column_id']}";
				$url .= "data[big_column_id]={$data['big_column_id']}&";
				$this->assign('big_column_id',$data['big_column_id']);
				//大栏目存在获取小栏目
				$columnInfo = D("News")->getColumnInfoById($data['big_column_id']);
				$this->assign('columnInfo',$columnInfo);
				
			}
			if(trim($data['column_id'])){
				$where .= " and column_id = {$data['column_id']}";
				$url .= "data[column_id]={$data['column_id']}&";
				$this->assign('column_id',$data['column_id']);
			}
			if(trim($data['three_column_id'])){
				$where .= " and three_column_id = {$data['three_column_id']}";
				$url .= "data[three_column_id]={$data['three_column_id']}&";
				$this->assign('three_column_id',$data['three_column_id']);
				$three_column_name = M('news_column')->where("id = {$data['three_column_id']}")->getField('name');
				$this->assign('three_column_name',$three_column_name);
				
			}
		}
	
		$url.="page=";
		$news = D('News');
		$newsCount = $news->getNewsCount($where);
		$list = $news->getNewsList($where,$page,$limit);
		if(ceil($newsCount/$limit) > 1){
			$pageHtml = $this->page($url,ceil($newsCount/$limit),$limit,$page,count($list));
		}
		
		//获取资讯所有分类
		$allColumn = $news->getAllColumn();
		$statusArray = array('-1'=>'删除','0'=>'待审核','1'=>'已审核');
		//返回所有大栏目
		
		$bigColumn =  $news->getBigColumn();
		$this->assign('bigColumn',$bigColumn);
		$this->assign('statusArray',$statusArray);
		$this->assign('allColumn',$allColumn);
		$this->assign('list',$list);
		$this->assign('pageHtml',$pageHtml);
		$this->display('newsList');
	}
	
	/**
	 * 添加资讯
	 */
	public function  addNews(){
		$news = D('News');
		if($_POST){
			$data['title'] = trim($_POST['title']);
			$data['big_column_id'] = intval($_POST['big_column_id']);
			$data['column_id'] = intval($_POST['column_id']);
			$data['custom_visits_number'] = intval($_POST['custom_visits_number']);
			$data['total_visits_number'] = 	intval($_POST['custom_visits_number']);
			$data['status'] = intval($_POST['status']);
			$data['summary'] = trim($_POST['summary']);
			$data['content'] = urldecode($_POST['content']);
			$data['pic_path'] = trim($_POST['pic_path']);
			$data['create_time']  = time();
			$data['three_column_id'] = intval($_POST['three_column_id']);
			M('news_information')->add($data);
			$this->redirect('/iadmin.php/News/newsList');
 		}
		//返回所有大栏目
		$bigColumn =  $news->getBigColumn();
		$this->assign('bigColumn',$bigColumn);
		$this->display('addNews');
	}
	
	/**
	 * 编辑资讯
	 */
	public function editNews(){
		$news = D('News');
		$id = intval($_GET['id']);
		$idInfo =  M("news_information")->where("id = {$id} ")->find();
		//返回所有大栏目
		$bigColumn =  $news->getBigColumn();
		$this->assign('bigColumn',$bigColumn);
		//大栏目存在获取小栏目
		$columnInfo = D("News")->getColumnInfoById($idInfo['big_column_id']);
		//三级分类名称\
		$idInfo['three_column_name'] = M('news_column')->where("id = {$idInfo['three_column_id']}")->getField('name');
		if($_POST){
			$idInfo =  M("news_information")->where("id = {$_POST['id']}")->find();
			//返回所有大栏目
			$data['title'] = trim($_POST['title']);
			$data['big_column_id'] = intval($_POST['big_column_id']);
			$data['column_id'] = intval($_POST['column_id']);
			$data['custom_visits_number'] = intval($_POST['custom_visits_number']);
			$data['total_visits_number'] = $idInfo['really_visits_number'] + $data['custom_visits_number']; //更新总访问量
			$data['three_column_id'] = intval($_POST['three_column_id']);
			$data['status'] = intval($_POST['status']);
			$data['summary'] = trim($_POST['summary']);
			$data['content'] = urldecode($_POST['content']);
			$data['pic_path'] = trim($_POST['pic_path']);
			$data['create_time']  = time();
			M('news_information')->where("id = {$_POST['id']} ")->save($data);
			$this->redirect('/iadmin.php/News/newsList');
		}
		$this->assign('columnInfo',$columnInfo);
		$this->assign('idInfo',$idInfo);
		$this->display("editNews");
	}
	
	
	
	/**
	 * 根据大栏目ID获取对应的子栏目
	 */
	public function AjaxGetColumnInfo(){
		$id = intval($_POST['id']);
		$info = D("News")->getColumnInfoById($id);
		$this->ajaxReturn($info,'json');
		
	}
	
	/**
	 * 修改资讯状态
	 */
	public function updateNews(){
		$id = $_POST['id'];
		$data['status'] = intval($_POST['status']);
		if(strstr($id,",")){	//批量修改状态
			$id =rtrim($id,',');
			$data = M('news_information')->where("id in ( {$id})")->save($data);
			$this -> ajaxReturn($data, 'JSON');
		}else {		//修改单个状态
			$id = intval($_POST['id']);
			$data = M('news_information')->where("id = {$id}")->save($data);
			$this -> ajaxReturn($data, 'JSON');
		}
	}
	
	/**
	 * 资讯栏目列表
	 * @param number $parent_id
	 */
	public function columnList($parent_id = 0){
		if($_GET['parent_id']){
			$parent_id = $_GET['parent_id'];
		}
		$list = M("news_column")->where("parent_id = {$parent_id}")->select();
		foreach ($list as $key=>$val){
			switch ($val['type']){
				case 0 :
					$list[$key]['countNews'] = M("news_information")->where("big_column_id = {$val['id']} and status != -1 ")->count();
					break;
				case 1 :
					$list[$key]['countNews'] = M("news_information")->where("column_id = {$val['id']} and status != -1  ")->count();
					break;
				case 2 :
					$list[$key]['countNews'] = M("news_information")->where("three_column_id = {$val['id']} and status != -1  ")->count();
					break;
			}
			$list[$key]['countNews'] = $list[$key]['countNews']  ? $list[$key]['countNews']  :　0;
		}
		$this->assign('parent_id',$parent_id);
		$this->assign('list',$list);
		$this->display("columnList");
		
	}
	
	/**
	 * 添加栏目
	 */
	public function saveColumn(){
		
		if($_POST){
			$data['name'] = trim($_POST['name']);
			$data['introduction'] = urldecode($_POST['introduction']);
			$data['parent_id'] = intval($_POST['parent_id']);
			$data['type'] = intval($_POST['type']);
			$data['sort'] = intval($_POST['sort']);
			M('news_column')->add($data);
			$this->redirect('/iadmin.php/News/columnList?parent_id='.$_POST['parent_id']);
		}
		//上级分类名称_
		$parent_name = M('news_column')->where("id = {$_GET['parent_id']}")->getField('name');
		$parent_name = $parent_name ? $parent_name : '当前分类为最上级分类';
		$type = intval($_GET['type']);
		$this->assign('type',$type);
		$this->assign('parent_name',$parent_name);
		$this->assign('parent_id',$_GET['parent_id']);
		$this->display("saveColumn");
	}
	
	/**
	 * 编辑栏目
	 */
	public function editColumn(){
		if($_GET['id']){
			$id  = intval($_GET['id']);
			$idInfo = M("news_column")->where("id = {$id}")->find();
			$parent_name = M('news_column')->where("id = {$idInfo['parent_id']}")->getField('name');
			$parent_name = $parent_name ? $parent_name : '当前分类为最上级分类';
			$this->assign('parent_name',$parent_name);
			$this->assign('idInfo',$idInfo);
			$this->display("editColumn");
		}
		if($_POST){
			$data['name'] = trim($_POST['name']);
			$data['introduction'] = urldecode($_POST['introduction']);
			$data['sort'] = intval($_POST['sort']);
			M('news_column')->where("id = {$_POST['id']}")->save($data);
			$this->redirect('/iadmin.php/News/columnList?parent_id='.$_POST['parent_id']);
		}
	}
	
	/**
	 * ajax验证栏目名称是否重复
	 */
	public function AjaxCheckColumnName(){
		$where = "1 = 1";
		if($_POST['name']){
			$name = trim($_POST['name']);
			$where .= " and name = '{$name}'  ";
		}
		if($_POST['id']){
			$id = intval($_POST['id']);
			$where .= " and id != {$id}";
		}
		$id = intval($_POST['id']);
		$name = trim($_POST['name']);
		$result = M("news_column")->where($where)->count();
		$this->ajaxReturn($result,'json');
	}
	
	/**
	 * Ajax搜索三级分类
	 */
	public function AjaxGetThreeLevelColumnInfo(){
		$column_id = $_POST['column_id'];
		$search_name = $_POST['search_name'];
		$info = M('news_column')->where("parent_id = {$column_id} and name = '{$search_name}' and  status = 0  and type = 2")->field('id,name')->find();
		$this->ajaxReturn($info,'json');
	}
	
	/**
	 * 批量修改资讯三级分类
	 */
	public function AjaxCheckNewsThreeColumn(){
		$column_id = $_POST['column_id'];
		$ids = rtrim($_POST['ids'],','); 
		$updatedata['three_column_id'] = intval($_POST['three_column_id']);
		$updatedata['column_id'] = intval($_POST['column_id']);
		$updatedata['big_column_id'] = intval($_POST['big_column_id']);
		$result = M('news_information')->where("id in ({$ids})")->save($updatedata);
		$data['status'] = 'ok';
		
		$this->ajaxReturn($data,'json');
	}
	
	/**
	 * 删除栏目
	 */
	public function AjaxDelColumn(){
		$type = $_POST['type'];
		$id = $_POST['id'];
		if($type && $id){
			switch ($type){
				case 1 :
					$countNews = M("news_information")->where("column_id = {$id} and status != -1 ")->count();
					break;
				case 2 :
					$countNews = M("news_information")->where("three_column_id = {$id} and status != -1 ")->count();
					break;
			}

			if($countNews){
				$data['msg'] = "该栏目下的资讯不为空，不可删除";
				$data['status'] = 'error';
			}else{
				M('news_column')->where("id = {$id} ")->delete();
				$data['msg'] = "删除成功";
				$data['status'] = 'ok'; 
			}
			
			$this->ajaxReturn($data,'json');
		}
	}
	
	
	public function uploadExcelColumn(){
		$upload = $_FILES['upload'];
		if($upload){
			$files = $_FILES['upload'];
			$file = $this->uploadExcle();
			//echo $file;die();
			$array = $this->readerExcle($file);
			$bigArray = array();
			foreach ($array[0] as $k => $v){
				$result = M('news_column')->where("name = '{$v}'")->getField('id');
				$bigArray[$k] = $result;
			}
			
			unset($array[0]);
			$array = array_values($array);
			foreach ($array as $k => $v){
				foreach ($v as $key => $val){
					$info['parent_id'] = $bigArray[$key];
					$info['name'] = $val;
					$info['type'] = 2;
					$info['status']  = 0;
					$info['introduction'] = ' ';
					$count = M("news_column")->where("parent_id = {$info['parent_id']} and name = '{$info['name']}' and type = 2 ")->count();
					if(!$count){
						$result = M("news_column")->add($info);
						if($result){
							echo 'ok<br />';
						}
					}
				}
			}
			//dump($array);
			die();
			dump($array);die();
		}
		$this->display('uploadExcelColumn');
	}
	
	//上传excle表格
	public function uploadExcle(){
		$files = $_FILES['upload'];
		$arr = explode('.',$files['name']);
		$filename = "./Upload/excel/business_".time().'.'.$arr[count($arr)-1];
		if(move_uploaded_file($files['tmp_name'],$filename)){
			return $filename;
		}
		exit;
	}
	
	//读取excle表格数据
	public function readerExcle($file){
		//$file= "./Upload/excel/business_1411551193.xlsx";
		$arrFile = pathinfo($file);
		//读出excel
		vendor('excel.PHPExcel');
		$objPHPExcel = PHPExcel_IOFactory::load($file);
		$arrExcel = $objPHPExcel->getSheet(0)->toArray();
		array_shift($arrExcel);
	
		return  $arrExcel;
	}
	
	
}