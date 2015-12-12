<?php
/*
 * ThreadAction 百科帖子
 */
class ThreadAction extends ExtendAction{
	/**
	 * 问答列表
	 */
	public function askList() {
		$articleModel = D('BkArticle');
		// 当前页码
		$param['page'] = isset($_GET['page']) ? intval($_GET['page']) : 1;
		// 页显数量
		$param['pageNum'] = 20;
		// 当前页面链接
		$url = '/iadmin.php/Thread/askList?';
		// 搜索条件
		$noAllow = C('NO_ALLOW');
		
		// 查询参数
		// 问题标题
		if($_GET['title'] && !in_array($_GET['title'],$noAllow)){
			$param['title'] = trim($_GET['title']);
			$url.='title='.urlencode($param['title']).'&';
			$this->assign('title',$param['title']);
		}
		// 问题标题
		if($_GET['tag_name'] && !in_array($_GET['tag_name'],'输入标签关键字')){
			$param['tag_name'] = trim($_GET['tag_name']);
			$url.='tag_name='.urlencode($param['tag_name']).'&';
			$this->assign('tag_name',$param['tag_name']);
		}
		// 问题提问时间
		if($_GET['starttime']){
			$param['starttime'] = $_GET['starttime'];
			$url.='starttime='.$param['starttime'].'&';
			$this->assign('starttime',$param['starttime']);
		}
		if($_GET['endtime']){
			$param['endtime'] = $_GET['endtime'];
			$url.='endtime='.$param['endtime'].'&';
			$this->assign('endtime',$param['endtime']);
		}
		// 三级分类
		if($_GET['thirdCatId']){
			// 三级分类
			$param['thirdCatId'] = $_GET['thirdCatId'];
			$url.='thirdCatId='.$param['thirdCatId'].'&';
			// 二级分类
			$parent = $articleModel->getParentCatByCatId($param['thirdCatId']);
			$param['secondCatId'] = $parent['parent_id'];

			// 一级分类
			$parent = $articleModel->getParentCatByCatId($param['secondCatId']);
			$param['firstCatId'] = $parent['parent_id']; 

			$this->assign('thirdCatId',$param['thirdCatId']);
			$this->assign('secondCatId',$param['secondCatId']);
			$this->assign('firstCatId',$param['firstCatId']);
		}
		// 二级分类（没有选择三级分类，则查找该二级分类下所有分类）
		if($_GET['secondCatId'] && $_GET['thirdCatId'] == ''){
			// 二级分类
			$param['secondCatId'] = $_GET['secondCatId'];
			$url.='secondCatId='.$param['secondCatId'].'&';
			// 一级分类
			$parent = $articleModel->getParentCatByCatId($param['secondCatId']);
			$param['firstCatId'] = $parent['parent_id'];

			$this->assign('firstCatId',$param['firstCatId']);
			$this->assign('secondCatId',$param['secondCatId']);
		}
		// 一级分类（没有选择二级分类，则查找所有分类）
		if($_GET['firstCatId'] && $_GET['secondCatId'] == ''){
			$param['firstCatId'] = $_GET['firstCatId'];
			$url.='firstCatId='.$param['firstCatId'].'&';

			$this->assign('firstCatId',$param['firstCatId']);
		}
		// 问题是否审核
		if(isset($_GET['is_check'])){
			$param['is_check'] = $_GET['is_check'];
		} else {
			$param['is_check'] = -1;
		}
		$url.='is_check='.$param['is_check'].'&';
		$this->assign('is_check',$param['is_check']);
		// 问题是否精华
		if(isset($_GET['is_digest'])){
			$param['is_digest'] = $_GET['is_digest'];
		} else {
			$param['is_digest'] = -1;
		}
		$url.='is_digest='.$param['is_digest'].'&';
		$this->assign('is_digest',$param['is_digest']);
		// 问题是否置顶
		if(isset($_GET['is_top'])){
			$param['is_top'] = $_GET['is_top'];
		} else {
			$param['is_top'] = -1;
		}
		$url.='is_top='.$param['is_top'].'&';
		$this->assign('is_top',$param['is_top']);
		// 问题是否紧急
		if(isset($_GET['is_urgent'])){
			$param['is_urgent'] = $_GET['is_urgent'];
		} else {
			$param['is_urgent'] = -1;
		}
		$url.='is_urgent='.$param['is_urgent'].'&';
		$this->assign('is_urgent',$param['is_urgent']);
		// 提问用户
		if(!in_array($_GET['user'],$noAllow) && !empty($_GET['user'])){
			$param['user'] = $_GET['user'];
			$param['select'] = $_GET['select'];

			$url.='data[user]='.($param['user']).'&';
			$url.='data[select]='.$param['select'].'&';

			$this->assign('select',$param['select']);
			$this->assign('user',$param['user']);
		}
		// 排序字段
		if($_GET['order']){
			$param['order'] = $_GET['order'];
			$url.='order='.$param['order'].'&';
			$this->assign('order',$param['order']);
		}
		$url.="page=";

		// 问答Model实例化
		$threadModel = D('BkThread');
		// 问题列表
		$list = $threadModel->getAskList($param);
		$this->assign('threads',$list);
		if($param['page'] >= $threadModel->pagecount){
			$param['page'] = $threadModel->pagecount;
		}
		// 分页信息
		$pageHtml = $this->page($url,$threadModel->pagecount,$param['pageNum'],$param['page'],$threadModel->subtotal);
		$this->assign('pageHtml',$pageHtml);
		$this->assign('url',$url.$param['page']);
		$this->assign('page',$param['page']);
				
		// 全部一级分类列表
		$catList = $articleModel->getParentCatList();
		$this->assign('catList',$catList);
		
		$this->display('list');
	}

	/**
	 * 百科问题回答列表
	 */
	public function askCommentList(){
		// 页显数量
		$limit = 20;
		// 当前页码
		$page=$this->_get('page');
		if($page=='' || !is_numeric($page)){
			$page=1;
		}
		// 当前页URL
		$url = '/iadmin.php/Thread/askCommentList?';
		// 查询条件
		$where = "threadcomment.uid=user.uid AND threadcomment.thread_id=thread.id AND thread.cat_id=cat.id AND threadcomment.status>=0";
		
		// 查询条件
		// 不加入插条字段
		$noAllow = C('NO_ALLOW');
		if($this->_get('data')){
			$data = $this->_get('data');
			// 回答内容
			if(!in_array($data['content'],$noAllow) && !empty($data['content'])){
				$where.=" AND threadcomment.content like '%".$data['content']."%' ";
				$this->assign('content',$data['content']);
				$url.='data[content]='.($data['content']).'&';
			}
			// 回答时间
			if(trim($data['starttime'])){
				$where.=" AND threadcomment.create_time >= ".strtotime($data['starttime'].' 00:00:00');
				$url.='data[starttime]='.$data['starttime'].'&';
				$this->assign('starttime',$data['starttime']);
			}
			if(trim($data['endtime'])){
				$where.=" AND threadcomment.create_time <= ".strtotime($data['endtime'].' 23:59:59');
				$url.='data[endtime]='.$data['endtime'].'&';
				$this->assign('endtime',$data['endtime']);
			}
			// 三级分类
			if($data['thirdCatId']){
				// 三级分类
				$param['thirdCatId'] = $data['thirdCatId'];
				$url.='data[thirdCatId]='.$param['thirdCatId'].'&';
				$where.=" AND thread.cat_id=".$data['thirdCatId'];

				// 二级分类
				$parent = D('BkArticle')->getParentCatByCatId($param['thirdCatId']);
				$param['secondCatId'] = $parent['parent_id'];

				// 一级分类
				$parent = D('BkArticle')->getParentCatByCatId($param['secondCatId']);
				$param['firstCatId'] = $parent['parent_id']; 

				$this->assign('thirdCatId',$param['thirdCatId']);
				$this->assign('secondCatId',$param['secondCatId']);
				$this->assign('firstCatId',$param['firstCatId']);
			}
			// 二级分类（没有选择三级分类，则查找该二级分类下所有分类）
			if($data['secondCatId'] && $data['thirdCatId'] == ''){
				// 二级分类
				$param['secondCatId'] = $data['secondCatId'];
				$url.='data[secondCatId]='.$param['secondCatId'].'&';
				// 三级分类di
				$thirdCatIdList = D('BkArticle')->getSubCatListByParentId($param['secondCatId']);
				foreach($thirdCatIdList as $v){
					$thirdCatIds[] = $v['id'];
				}
				$strThirdCatIds = implode(",",$thirdCatIds);
				$where = $where ." and thread.cat_id in (".$strThirdCatIds.")";
				// 一级分类
				$parent = D('BkArticle')->getParentCatByCatId($param['secondCatId']);
				$param['firstCatId'] = $parent['parent_id'];

				$this->assign('firstCatId',$param['firstCatId']);
				$this->assign('secondCatId',$param['secondCatId']);
			}
			// 一级分类（没有选择二级分类，则查找所有分类）
			if($data['firstCatId'] && $data['secondCatId'] == ''){
				$param['firstCatId'] = $data['firstCatId'];
				$url.='data[firstCatId]='.$param['firstCatId'].'&';

				// 一级分类下的所有二级分类
				$secondCatIdList = D('BkArticle')->getSubCatListByParentId($param['firstCatId']);
				foreach($secondCatIdList as $v){
					$secondCatIds[] = $v['id'];
				}
				$strSecondCatIds = implode(",",$secondCatIds);
				// 一级分类下的所有三级分类
				$thirdCatIdList = D('BkArticle')->getSubCatListByParentIds($strSecondCatIds);
				foreach($thirdCatIdList as $v){
					$thirdCatIds[] = $v['id'];
				}
				$strThirdCatIds = implode(",",$thirdCatIds);

				$where = $where ." and thread.cat_id in (".$strThirdCatIds.")";

				$this->assign('firstCatId',$param['firstCatId']);
			}
			// 提问标题
			if(!in_array($data['title'],$noAllow) && !empty($data['title'])){
				$where.=" AND thread.title like '%".$data['title']."%' ";
				$url.='data[title]='.($data['title']).'&';
				$this->assign('title',$data['title']);
			}
			// 回答人
			if(!in_array($data['user'],$noAllow) && !empty($data['user'])){
				if($data['select']==1){
					$where.=" AND user.nickname like '%".trim($data['user'])."%' ";
				}else if($data['select']==2){
					if(is_numeric($data['user'])){
						$where.=" AND user.uid=".trim($data['user']);
					}
				}
				$url.='data[user]='.($data['user']).'&';
				$url.='data[select]='.$data['select'].'&';

				$this->assign('select',$data['select']);
				$this->assign('user',$data['user']);
			}
			// 回答是否审核
			if($data['is_check']>-1) {
				$where.=" AND threadcomment.is_check=".$data['is_check'];
				$url.='data[is_check]='.$data['is_check'].'&';
				$this->assign('is_check',$data['is_check']);
			}
		}
		$url.='page=';
		// 问题回答Model实例化
		$threadCommentModel = D('BkThreadComment');
		// 问题回答数
		$threadCommentCount = $threadCommentModel->hasThreadCommentCount($where);
		$pcount = ceil($threadCommentCount/$limit);
		if($page>=$pcount){
			$page=$pcount;
		}
		// 问题回答列表
		$threadComments = $threadCommentModel->hasThreadCommentUser($page,$limit,$where);
		// 分页信息
		$pageHtml = $this->page($url,$pcount,$limit,$page,count($threadComments));
		$this->assign('url',$url.$page);
		$this->assign('pageHtml',$pageHtml);
		$this->assign('page',$page);
		$this->assign('threadComments',$threadComments);

		// 获取所有子分类列表
		$catList = D('BkArticle')->getParentCatList();
		$this->assign('catList',$catList);

		$this->display('askCommentList');
	}

	/**
	 * 问题详细页
	 */
	public function detail(){
		// 问题Model实例化
		$thread = D('BkThread');
		// 问题id
		$id = $this->_get('id');
		// 问答信息
		$detail = $thread->getThreadDetail($id);
		$this->assign('detail',$detail);

		$this->display('detail');
	}

	/**
	 * 编辑问答保存
	 */
	public function saveThread() {
		// 问题Model实例化
		$threadModel = D('BkThread');
		// post提交参数
		$data = $_POST;
		// 编辑问答保存
		$result = $threadModel->editThread($data);

		// 返回操作
		if($result) {
			echo "<script>alert('标签修改成功');location.href='/iadmin.php/Thread/askList';</script>";
			exit;
		} else {
			echo "<script>alert('标签修改失败');location.href='/iadmin.php/Thread/detail/id/" . $data['id'] . "';</script>";
			exit;
		}
	}

	/**
	 * 删除百科问答
	 */
	public function deleteThread(){
		// 问题id串接
		$ids = $this->_get('deleteThread');
		// 操作
		$act = $this->_get('act');
		// 当前分页（返回时用）
		$page = $this->_get('page');
		// 是否站内信通知
		$isNotice = $this->_get('isNotice');
		// 问题id串分割
		$idArr = explode(',',$ids);
		// 问题Model实例化
		$threadModel = D('BkThread');
		// 问题回答Model实例化
		$threadCommentModel = D('BkThreadComment');

		// 循环处理删除操作
		foreach($idArr as $key=>$val){
			if($val){
				// 发送站内信，记录删除操作
				if($isNotice==1){
					$uid = $threadModel->where(array('id'=>$val))->select();
					$this->recordOperations(2,21,$val,$isNotice,$uid[0]['uid'],11);
				}
				// 记录删除操作
				else{
					$this->recordOperations(2,21,$val);
				}
				// 逻辑删除问题的所有回答
				$threadCommentModel->where(array('thread_id'=>$val))->save(array('status'=>-1));
				// 逻辑删除问题
				$threadModel->where(array('id'=>$val))->save(array('status'=>-1));

				// 更新搜索库（删除问题）
				$url = C("C_DIR") . "/index.php/Public/xs?param[operation_type]=del&param[config_object]=1&param[pid]=".$val."&param[type]=2";
				get_url($url);
			}
		}
		
		// 返回操作
		if(empty($act)){
			//$this->redirect('/iadmin.php/Thread/index?page='.$page);
			echo "<script>history.back();</script>";
		}else{
			echo 1;
			exit;
		}
	}

	/**
	 * 问题回答详细页
	 */
	public function cdetail(){
		// 问题回答Model实例化
		$threadCommentModel = D('BkThreadComment');
		// 问题回答id
		$id = $this->_get('id');
		// 问题回答信息
		$detail = $threadCommentModel->getThreadCommentDetail($id);
		$this->assign('detail',$detail);

		$this->display('detailComment');
	}

	/**
	 * 百科问题回答删除
	 */
	public function deleteThreadComment(){
		// 问题回答id串接
		$ids = $this->_get('deleteThreadComment');
		// 操作
		$act = $this->_get('act');
		// 当前页码
		$page = $this->_get('page');
		// 是否站内信通知
		$isNotice = $this->_get('isNotice');
		// 问题回答id分割
		$idArr = explode(',',$ids);
		// 问题回答Model实例化
		$threadCommentModel = D('BkThreadComment');
		// 问题Model实例化
		$threadModel = D('BkThread');

		foreach($idArr as $key=>$val){
			if($val){
				// 站内信通知，并记录删除操作
				if($isNotice==1){
					$uid = $threadCommentModel->where(array('id'=>$val))->select();
					$this->recordOperations(2,23,$val,$isNotice,$uid[0]['uid'],12);
				}
				// 记录删除操作
				else{
					$this->recordOperations(2,23,$val);
				}
				// 删除回答操作
				$threadCommentModel->delThreadComment(array('id'=>$val));
			}
		}
		
		// 返回操作
		if(empty($act)){
			//$this->redirect('/iadmin.php/Thread/threadComment?page='.$page);
			echo "<script>history.back();</script>";
		}else{
			echo 1;
			exit;
		}
	}

	/**
	 * 审核百科问题
	 */
	public function check(){
		// 问题id
		$id = $this->_get('id');
		// 当前页码
		$page = $this->_get('page');
		// 问题Model实例化
		$threadModel = D('BkThread');
		// 审核问题
		$result = $threadModel->check(array('id'=>$id));
		// 返回
		$this->redirect('/iadmin.php/Thread/askList?page='.$page);
	}

	/**
	 * 批量审核百科问题
	 */
	public function batchCheck(){
		// 英文逗号串接的问题id
		$ids = $this->_post('ids');
		// 问题Model实例化
		$threadModel = D('BkThread');
		// 分割问题id串
		$idArr = explode(',',$ids);

		// 循环审核操作
		foreach($idArr as $key=>$val){
			if($val){
				// 记录审核操作
				$this->recordOperations(3,21,$val);
				// 审核问题
				$threadModel->check(array('id'=>$val));
			}
		}

		echo 1;
		exit;
	}

	/**
	 * 审核百科问题回答
	 */
	public function checkComment(){
		// 问题回答id
		$id = $this->_get('id');
		// 当前页码（返回时用）
		$page = $this->_get('page');
		// 问题回答Model实例化
		$commentModel = D('BkThreadComment');
		// 审核问题回答
		$result = $commentModel->checkComment(array('id'=>$id));
		// 返回操作
		$this->redirect('/iadmin.php/Thread/askCommentList?page='.$page);
	}

	/****************************************************************************************/
	/**
	 *百科帖子列表页（del）
	 */
	public function index(){
		$limit = 20;
		$page=$this->_get('page');
		if($page=='' || !is_numeric($page)){
			$page=1;
		}

		$url='/iadmin.php/Thread/index?';
		$where="thread.uid=user.uid AND thread.cat_id=cat.id AND thread.status>=0";

		//搜索条件
		$noAllow = C('NO_ALLOW');
		if($this->_get('data')){
			$data = $this->_get('data');

			if(!in_array($data['title'],$noAllow) && !empty($data['title'])){
				$where.=" AND thread.title like '%".$data['title']."%' ";
				$url.='data[title]='.($data['title']).'&';
				$this->assign('title',$data['title']);
			}
			if(trim($data['starttime'])){
				$where.=" AND thread.create_time >= ".strtotime($data['starttime'].' 00:00:00');
				$url.='data[starttime]='.$data['starttime'].'&';
				$this->assign('starttime',$data['starttime']);
			}
			if(trim($data['endtime'])){
				$where.=" AND thread.create_time <= ".strtotime($data['endtime'].' 23:59:59');

				$url.='data[endtime]='.$data['endtime'].'&';
				$this->assign('endtime',$data['endtime']);
			}
			if($data['cat_id'] > 0) {
				$where.=" AND thread.cat_id=".$data['cat_id'];
				$url.='data[cat_id]='.$data['cat_id'].'&';
				$this->assign('cat_id',$data['cat_id']);
			}
			if($data['is_check']>-1) {
				$where.=" AND thread.is_check=".$data['is_check'];
				$url.='data[is_check]='.$data['is_check'].'&';
				$this->assign('is_check',$data['is_check']);
			}
			if(!in_array($data['user'],$noAllow) && !empty($data['user'])){
				if($data['select']==1){
					$where.=" AND user.nickname like '%".trim($data['user'])."%' ";
				}else if($data['select']==2){
					if(is_numeric($data['user'])){
						$where.=" AND user.uid=".trim($data['user']);
					}
				}
				$url.='data[user]='.($data['user']).'&';
				$url.='data[select]='.$data['select'].'&';

				$this->assign('select',$data['select']);
				$this->assign('user',$data['user']);
			}
		}
		if($_GET['order']){
			$order = $_GET['order'];
			$url.='order='.$order.'&';
		}

		$threadModel = D('BkThread');
		$threadCount = $threadModel->hasThreadCount($where);
		$pcount = ceil($threadCount/$limit);
		if($page>=$pcount){
			$page=$pcount;
		}
		
		$url.='page=';

		$threads = $threadModel->hasUserAndThread($page,$limit,$where, $order);
		$pageHtml = $this->page($url,$pcount,$limit,$page,count($threads));

		// 获取所有子分类列表
		$catList = $threadModel->getAllSubCategoryList();
		$this->assign('catList', $catList);

		$this->assign('url',$url.$page);
		$this->assign('order',$order);

		$this->assign('pageHtml',$pageHtml);
		$this->assign('page',$page);
		$this->assign('threads',$threads);
		$this->display('index');
	}

	/*
	 * 百科帖子评论列表
	 */
	public function threadComment(){
		$limit = 20;
		$page=$this->_get('page');
		if($page=='' || !is_numeric($page)){
			$page=1;
		}
		
		$url='/iadmin.php/Thread/threadComment?';
		$where="threadcomment.uid=user.uid AND threadcomment.thread_id=thread.id AND thread.cat_id=cat.id AND threadcomment.status>=0";
		
		//搜索条件
		$noAllow = C('NO_ALLOW');
		if($this->_get('data')){
			$data = $this->_get('data');

			if(!in_array($data['content'],$noAllow) && !empty($data['content'])){
				$where.=" AND threadcomment.content like '%".$data['content']."%' ";
				$this->assign('content',$data['content']);
				$url.='data[content]='.($data['content']).'&';
			}
			if(trim($data['starttime'])){
				$where.=" AND threadcomment.create_time >= ".strtotime($data['starttime'].' 00:00:00');
				$url.='data[starttime]='.$data['starttime'].'&';
				$this->assign('starttime',$data['starttime']);
			}
			if(trim($data['endtime'])){
				$where.=" AND threadcomment.create_time <= ".strtotime($data['endtime'].' 23:59:59');
				$url.='data[endtime]='.$data['endtime'].'&';
				$this->assign('endtime',$data['endtime']);
			}
			if($data['cat_id'] > 0) {
				$where.=" AND thread.cat_id=".$data['cat_id'];
				$url.='data[cat_id]='.$data['cat_id'].'&';
				$this->assign('cat_id',$data['cat_id']);
			}
			if(!in_array($data['title'],$noAllow) && !empty($data['title'])){
				$where.=" AND thread.title like '%".$data['title']."%' ";
				$url.='data[title]='.($data['title']).'&';
				$this->assign('title',$data['title']);
			}
			if(!in_array($data['user'],$noAllow) && !empty($data['user'])){
				if($data['select']==1){
					$where.=" AND user.nickname like '%".trim($data['user'])."%' ";
				}else if($data['select']==2){
					if(is_numeric($data['user'])){
						$where.=" AND user.uid=".trim($data['user']);
					}
				}
				$url.='data[user]='.($data['user']).'&';
				$url.='data[select]='.$data['select'].'&';

				$this->assign('select',$data['select']);
				$this->assign('user',$data['user']);
			}
			if($data['is_check']>-1) {
				$where.=" AND threadcomment.is_check=".$data['is_check'];
				$url.='data[is_check]='.$data['is_check'].'&';
				$this->assign('is_check',$data['is_check']);
			}
		}
		
		$threadCommentModel = D('BkThreadComment');
		$threadCommentCount = $threadCommentModel->hasThreadCommentCount($where);
		$pcount = ceil($threadCommentCount/$limit);
		if($page>=$pcount){
			$page=$pcount;
		}
		$url.='page=';
		$threadComments = $threadCommentModel->hasThreadCommentUser($page,$limit,$where);
		$pageHtml = $this->page($url,$pcount,$limit,$page,count($threadComments));
		$this->assign('url',$url.$page);
		$this->assign('pageHtml',$pageHtml);
		$this->assign('page',$page);
		$this->assign('threadComments',$threadComments);
//		$teamList = D('BkThread')->getTeamList();
//		$this->assign('teamList', $teamList);
		// 获取所有子分类列表
		$catList = D('BkThread')->getAllSubCategoryList();
		$this->assign('catList', $catList);

		$this->display('threadComment');
	}
}
?>