<?php
/**
 * 文章Action类
 *
 * @modified by Fongson 2014-09-28 百科改版
 */
class ArticleAction extends ExtendAction {
	/**
	 * 文章列表
	 */
	public function articleList() {
		// 文章Model实例化
		$articleModel = D('BkArticle');
		
		// 当前页码
		$param['page'] = isset($_GET['page']) ? intval($_GET['page']) : 1;
		// 页显数量
		$param['pageNum'] = 20;
		// 当前页面链接
		$url='/iadmin.php/Article/articleList?';
		$noAllow = C('NO_ALLOW');
		// 查询参数
		// 文章标题
		if($_GET['keyword'] && !in_array($_GET['keyword'],$noAllow)){
			$param['keyword'] = trim($_GET['keyword']);
			$url.='keyword='.urlencode($param['keyword']).'&';
		}
		// 文章发布时间
		if($_GET['starttime']){
			$param['starttime'] = $_GET['starttime'];
			$url.='starttime='.$param['starttime'].'&';
		}
		if($_GET['endtime']){
			$param['endtime'] = $_GET['endtime'];
			$url.='endtime='.$param['endtime'].'&';
		}
		// 文章作者
		if($_GET['user'] && !in_array($_GET['user'],$noAllow)){
			$param['user'] = $_GET['user'];
			$url.='user='.$param['user'].'&';
		}
		// 三级分类
		if($_GET['thirdcat']){
			// 三级分类
			$param['thirdCatId'] = $_GET['thirdcat'];
			$url.='thirdcat='.$param['thirdCatId'].'&';
			// 二级分类
			$param['secondCatId'] = $_GET['category'];
			$url.='category='.$param['secondCatId'].'&';
			// 一级分类
			$parent = $articleModel->getParentCatByCatId($param['secondCatId']);
			$param['firstCatId'] = $parent['parent_id']; 

			$this->assign('thirdCatId',$param['thirdCatId']);
			$this->assign('secondCatId',$param['secondCatId']);
			$this->assign('firstCatId',$param['firstCatId']);
		}
		// 二级分类（没有选择三级分类，则查找该二级分类下所有分类）
		if($_GET['category'] && $_GET['thirdcat'] == ''){
			// 二级分类
			$param['secondCatId'] = $_GET['category'];
			$url.='category='.$param['secondCatId'].'&';

			// 一级分类
			$parent = $articleModel->getParentCatByCatId($param['secondCatId']);
			$param['firstCatId'] = $parent['parent_id'];
			$url.='category='.$param['secondCatId'].'&';

			$this->assign('firstCatId',$param['firstCatId']);
			$this->assign('secondCatId',$param['secondCatId']);
		}
		// 一级分类（没有选择二级分类，则查找所有分类）
		if($_GET['parent'] && $_GET['category'] == ''){
			// 一级分类
			$param['firstCatId'] = $_GET['parent'];
			$url.='parent='.$param['firstCatId'].'&';

			$this->assign('firstCatId',$param['firstCatId']);
		}
		// 标签名称
		if($_GET['tagname']&& !in_array($_GET['tagname'],$noAllow)){
			// 标签
			$param['tagname'] = trim($_GET['tagname']);
			$url.='tagname='.urlencode($param['tagname']).'&';
			$this->assign('tagname',$param['tagname']);
		}
		// 排序字段&方式
		if($_GET['order']){
			$param['order'] = $_GET['order'];
			$url.='order='.$param['order'].'&';
		}
	
		$url.="page=";
		
		// 文章列表
		$list = $articleModel->getArticleList($param);
		$this->assign('list', $list);

		if($param['page'] >= $articleModel->pagecount){
			$param['page'] = $articleModel->pagecount;
		}
		$pageHtml = $this->page($url,$articleModel->pagecount,$param['pageNum'],$param['page'],$article->subtotal);
		$this->assign('pageHtml',$pageHtml);
		$this->assign('url',$url.$param['page']);
		$this->assign('page',$param['page']);
		
		// 查询参数
		$this->assign('starttime',$param['starttime']);
		$this->assign('endtime',$param['endtime']);
		$this->assign('keyword',$param['keyword']);
		$this->assign('user',$param['user']);
		$this->assign('tagname',$param['tagname']);
		// 排序参数
		$this->assign('order',$param['order']);

		// 全部一级分类列表
		$catList = $articleModel->getParentCatList();
		$this->assign('catList',$catList);
		
		$this->display('articleList');
	}
	

	
	/**
	 * 文章添加/编辑页面
	 */
	public function addArticle(){
		// 文章Model实例化
		$articleModel = D('BkArticle');
		// 文章id
		$id = $this->_get('id');
		if($id){
			// 文章详细信息
			$detail = $articleModel->getArticleDetail($id);
			// echo "<pre>";print_r($detail);
			// 文章作者角色
			$gruop = $articleModel->getGroupByUid($detail['authorid']);
			$detail['level'] = $gruop['level'];
			// 获取二级分类
			$secondCat = $articleModel->getParentCatByCatId($detail['cat_id']);
			$detail['secondcat'] = $secondCat['parent_id'];
			// 获取一级分类
			$parcat = $articleModel->getParentCatByCatId($detail['secondcat']);
			$detail['parcat'] = $parcat['parent_id'];
			// 一级分类id
			$this->assign('parcat',$detail['parcat']);
			// 二级分类id
			$this->assign('secondCatId',$detail['secondcat']);
			// 三级分类id
			$this->assign('thirdCatId',$detail['cat_id']);

			$this->assign('detail',$detail);
		}
		// 所有一级分类列表
		$catList = $articleModel->getParentCatList();
		$this->assign('catList',$catList);

		// 获取所有专家信息
		$expertUser = $articleModel->getExpertUser();
		$this->assign('expertUser',$expertUser);
		// 获取所有小编信息
		$editUser = $articleModel->getEditUser();
		$this->assign('editUser',$editUser);

		$this->display('addArticle');
	}

	/**
	 * 添加/编辑文章保存
	 */
	public function saveArticle() {
		// 文章Model实例化
		$articleModel = D('BkArticle');
		// post提交参数
		$data = $_POST;
		// 文章内容
		$data['content']  = urldecode($data['content']);	
		// 专家
		if($data['usertype'] == 5){
			$data['authorid'] = $data['expert'];
		}
		// 小编
		if($data['usertype'] == 3){
			$data['authorid'] = $data['editer'];
		}
		// 分类id（三级分类id）
		$data['cat_id'] = $data['thirdcat'];

		// 标题title、摘要summary、标签tag、关键词（页面meta）

		// 编辑文章，记录到操作日志
		if($data['id']){
			// 文章信息
			$info = $articleModel->getArticleDetail($data['id']);
			$field = array(
					'title'=>array(
						'title'=>'标题'	
					),
					'authorid'=>array(
						'title'=>'用户ID'
					),
					'summary'=>array(
						'title'=>'简介',
						'flag'=>1
					),
					'content'=>array(
						'title'=>'内容',
						'flag'=>1
					),
					'pic_path'=>array(
						'title'=>'图片'
					),
					'cat_id'=>array(
						'title'=>'分类'
					),
			);
			$this->groupTip('BkArticle','id',$data['id'],$field,$data,20);
			// 编辑文章保存
			$articleModel->editArticle($data);
			//$this->recordOperations(5,20,$data['id']);
			echo "<script>alert('文章修改成功');location.href='/iadmin.php/Article/articleList';</script>";
			exit;
		}else{
			// 添加文章保存
			$id = $articleModel->addArticle($data);
			$this->recordOperations(1,20,$id);

			echo "<script>alert('文章添加成功');location.href='/iadmin.php/Article/articleList';</script>";
			exit;
		}
	}

	/** 
	 * 删除文章操作
	 */
	public function deleteArticle(){
		// 文章Model实例化
		$articleModel = D('BkArticle');
		// 删除文章id串接
		$ids = $this->_get('deleteArticle');
		// 操作
		$act = $this->_get('act');
		// 当前页码
		$page = $this->_get('page');
		// 文章id串分割
		$idArr = explode(',',$ids);
		foreach($idArr as $key=>$val){
			if($val){
				// 记录删除文章操作
				$this->recordOperations(2,20,$val);
				// 删除文章操作
				$articleModel->delAritcle($val);

				// 更新搜索库（删除文章）
				$url = C("C_DIR") . "/index.php/Public/xs?param[operation_type]=del&param[config_object]=1&param[pid]=".$val."&param[type]=1";
				get_url($url);
			}
		}
		// 返回
		if(empty($act)){
			echo "<script>history.back();</script>";
		}else{
			echo 1;
			exit;
		}
	}

	/**
	 * ajax方法：获取下属分类
	 */
	public function getSubCategory() {
		// 文章Model实例化
		$articleModel = D('BkArticle');
		// 分类id
		$pid = $_GET['id'];

		// 获取所有子分类
		$subCatList = $articleModel->getSubCatListByParentId($pid);

		// json值返回
		$this->ajaxReturn($subCatList,'JSON');
	}

	/** 
	 * 文章评论列表
	 */
	public function articleComment() {
		// 文章Model实例化
		$articleModel = D('BkArticle');
		// 文章评论Model实例化
		$comment = D('BkArticleComment');

		// 文章评论id（文章评论详情显示）
        $id = $this ->_get('id');
        if ($id) {
			// 文章评论信息
           $commentOne = $comment->getArticleComment($id);
            $this->assign('id',$id);
            $this->assign('commentOne',$commentOne);
            $this->display('articleComment');
            exit;
        }

		// 当前页码
		$param['page'] = $_GET['page'];
		if($param['page'] =='' || !is_numeric($param['page'])){
			$param['page'] = 1;
		}
		// 页显数量
		$param['pageNum'] = 20;

		// url地址
		$url='/iadmin.php/Article/articleComment?';
		// 标题
		$noAllow = C('NO_ALLOW');
		if($_GET['keyword'] && !in_array($_GET['keyword'],$noAllow)){
			$param['keyword'] = trim($_GET['keyword']);
			$url.='keyword='.urlencode($param['keyword']).'&';
		}
		// 发布时间
		if($_GET['starttime']){
			$param['starttime'] = $_GET['starttime'];
			$url.='starttime='.$param['starttime'].'&';
		}
		if($_GET['endtime']){
			$param['endtime'] = $_GET['endtime'];
			$url.='endtime='.$param['endtime'].'&';
		}
		// 三级分类
		if($_GET['thirdcat']){
			$param['thirdCatId'] = $_GET['thirdcat'];
			$url.='thirdcat='.$param['thirdCatId'].'&';
			// 二级分类
			$param['secondCatId'] = $_GET['category'];
			$url.='category='.$param['secondCatId'].'&';
			// 一级分类
			$parent = $articleModel->getParentCatByCatId($param['secondCatId']);
			$param['firstCatId'] = $parent['parent_id']; 

			$this->assign('thirdCatId',$param['thirdCatId']);
			$this->assign('secondCatId',$param['secondCatId']);
			$this->assign('firstCatId',$param['firstCatId']);
		}
		// 二级分类（没有选择三级分类，则查找该二级分类下所有分类）
		if($_GET['category'] && $_GET['thirdcat'] == ''){
			$param['secondCatId'] = $_GET['category'];
			$url.='category='.$param['secondCatId'].'&';

			// 一级分类
			$parent = $articleModel->getParentCatByCatId($param['secondCatId']);
			$param['firstCatId'] = $parent['parent_id'];
			$url.='category='.$param['secondCatId'].'&';

			$this->assign('firstCatId',$param['firstCatId']);
			$this->assign('secondCatId',$param['secondCatId']);
		}
		// 一级分类（没有选择二级分类，则查找所有分类）
		if($_GET['parent'] && $_GET['category'] == ''){
			$param['firstCatId'] = $_GET['parent'];
			$url.='parent='.$param['firstCatId'].'&';

			$this->assign('firstCatId',$param['firstCatId']);
		}
		if($_GET['title'] && !in_array($_GET['title'],$noAllow)){
			$param['title'] = trim($_GET['title']);
			$url.='title='.urlencode($param['title']).'&';
		}
		if($_GET['slt_type'] && $_GET['user'] && !in_array($_GET['user'],$noAllow)){
			$param['slt_type'] = $_GET['slt_type'];
			$url.='slt_type='.$param['slt_type'].'&';
			$param['user'] = $_GET['user'];
			$url.='user='.$param['user'].'&';
		}
		$url.="page=";
		
		// 文章评论列表
		$list = $comment->getArticleCommentList($param);
		$this->assign('list', $list);

		if($param['page'] >= $comment->pagecount){
			$param['page'] = $comment->pagecount;
		}
		// 分页信息
		$pageHtml = $this->page($url,$comment->pagecount,$param['pageNum'],$param['page'],$comment->subtotal);
		$this->assign('pageHtml',$pageHtml);
		$this->assign('url',$url.$param['page']);
		$this->assign('page',$param['page']);
		
		$this->assign('keyword',$param['keyword']);
		$this->assign('starttime',$param['starttime']);
		$this->assign('endtime',$param['endtime']);
		$this->assign('title',$param['title']);
		$this->assign('slt_type',$param['slt_type']);
		$this->assign('user',$param['user']);
		
		// 全部一级分类列表
		$catList = $articleModel->getParentCatList();
		$this->assign('catList',$catList);
		
		$this->display('articleComment');
	}

	/**
	 * 删除评论
	 */
	public function deleteArticleComment(){
		// 文章评论Model实例化
		$comment = D('BkArticleComment');
		// 文章评论id串接
		$ids = $this->_get('deleteArticleComment');
		// 操作
		$act = $this->_get('act');
		// 当前页码（返回时用）
		$page = $this->_get('page');
		// 是否站内信通知
		$isNotice = $this->_get('isNotice');
		// 文章评论id串分割
		$idArr = explode(',',$ids);

		foreach($idArr as $key=>$val){
			if($val){
				// 文章评论信息
				$uid = $comment->getArticleComment($val);
				// 站内信通知，记录操作
				if($isNotice==1){
					$this->recordOperations(2,22,$val,$isNotice,$uid['uid'],10);
				}else{
					$this->recordOperations(2,22,$val);
				}
				
				// 删除文章评论
				$comment->delAritcleComment($val);
			}
		}

		// 操作返回
		if(empty($act)){
			echo "<script>history.back();</script>";
		}else{
			echo 1;
			exit;
		}
	}

    /**
	 * 审核评论
	 */
    public function ajaxCheck(){
		// 文章评论id
        $id = $this->_get('id');
		// 类型？
        $type =$this->_get('val');
		// 文章评论Model实例化
        $comment = D('BkArticleComment');
		// 审核文章评论
        $result =$comment ->checkComment($id,$type);

        $this->ajaxReturn($result,'JSON');
    }
}
?>