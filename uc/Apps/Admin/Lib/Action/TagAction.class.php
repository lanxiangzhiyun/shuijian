<?php
/*
 * 标签管理页面
 *
 * @modified by fongson 2014-05-22
 * @modified by JasonJiang 2014/01/12 百科二期标签改版
 */
class TagAction extends ExtendAction{
	
	/* 
	 * 标签管理页面
	 */
	public function index(){
		$tagModel = D('UcTag');
		//页显数量
		$limit = 20;
		//当前页，默认为第1页
		$page = $this->_get('page');
		if($page=='' || !is_numeric($page)){
			$page=1;
		}
		$where="tag.status>=0";

		$url='/iadmin.php/Tag/index?';
		//搜索条件
		$theDate = C('THE_DATE');
		$isData = 0;
		$noAllow = C('NO_ALLOW');
		$data = $this->_get('data');
		if($data){
			//标签名
			if(!in_array($data['keyword'],$noAllow) && !empty($data['keyword'])){
				$where.=" and tag.name like '%".$data['keyword']."%' ";
				$url.='data[keyword]='.urlencode($data['keyword']).'&';
				$this->assign('keyword',$data['keyword']);
			}
			//创建时间
			if(!empty($data['createtime'])){
				$where.=" and tag.dateline>=".getTime($theDate[$data['createtime']]['days']);
				$url.='data[createtime]='.$data['createtime'].'&';
				$this->assign('createtime',$data['createtime']);
			}
			//栏目
			if(!empty($data['type'])){
				$isData =1;
				$where.=" and tag.type=".$data['type'];
				$url.='data[type]='.$data['type'].'&';
				$this->assign('type',$data['type']);
			}
			//锁定状态
			if(!empty($data['locked'])){
				if ($data['locked'] == 2) {
					$where.=" and tag.locked=0";
					$url.='data[locked]=2&';
					$this->assign('locked',2);
				}else{
					$where.=" and tag.locked=".$data['locked'];
					$url.='data[locked]='.$data['locked'].'&';
					$this->assign('locked',$data['locked']);
				}
				
			}
			//使用次数
			if(!empty($data['startuse'])){
				$where.=" and tag.usetimes>=".$data['startuse'];
				$url.='data[startuse]='.$data['startuse'].'&';
				$this->assign('startuse',$data['startuse']);
			}
			if(!empty($data['enduse'])){
				$where.=" and tag.usetimes<=".$data['enduse'];
				$url.='data[enduse]='.$data['enduse'].'&';
				$this->assign('enduse',$data['enduse']);
			}
			/** modified by fongson 2014-05-22 隐藏资源数量
			if(!empty($data['startresour'])){
				$where.=" and tag.resourtimes>=".$data['startresour'];
				$url.='data[startresour]='.$data['startresour'].'&';
				$this->assign('startresour',$data['startresour']);
			}
			if(!empty($data['endresour'])){
				$where.=" and tag.resourtimes<=".$data['endresour'];
				$url.='data[endresour]='.$data['endresour'].'&';
				$this->assign('endresour',$data['endresour']);
			}
			*/
			//创建人
			if(!in_array($data['username'],$noAllow) && !empty($data['username'])){
				//查找创建人
				$strUids = D('UcAdmin')->getStrUidsByUsername($data['username']);
				if($strUids) {
					$where .= " and tag.uid IN (". $strUids.") ";
				}
				$url.='data[username]='.urlencode($data['username']).'&';
				$this->assign('username',$data['username']);
			}
		}
		// 仅显示百科标签与个人标签
		if($isData==0 ){
			$where.=" and tag.type IN (1,11) ";
		}
		// print_r($where);
		//搜索结果数
		$tagCount = $tagModel->hasTagCount($where);
		//当前页不能超过最后页
		$pcount = ceil($tagCount/$limit);
		if($page>=$pcount){
			$page=$pcount;
		}
		$this->assign('urlOrder',$url);
		//排序
		if($data['sort']){
			$sort = $data['sort'];
		} else {
			$sort = "id";
		}
		if($data['desc']) {
			$desc = $data['desc'];
		} else {
			$desc = 'desc';
		}
		$url.='data[sort]='.$sort .'&data[desc]=' .$data['desc'].'&';
		$order = $sort.' ' . $desc;
		$this->assign('sort',$sort);
		$this->assign('desc',$desc);
		$url.='page=';
		//搜索结果
		$tags = $tagModel->hasManyTags($page,$limit,$where,$order);

		//栏目
		$theColumn = C('THE_COLUMN');
		foreach($tags as $key=>$val){
			$tags[$key]['typeName']=$theColumn[$val['type']];
		}
		
		$pageHtml = $this->page($url,$pcount,$limit,$page,count($tags));

		$this->assign('url',$url.$page);
		$this->assign('theDate',$theDate);
		$this->assign('theColumn',$theColumn);
		$this->assign('pageHtml',$pageHtml);
		$this->assign('tags',$tags);
		$this->assign('page',$page);
		$this->display('index');	
	}

	/**
	 * 批量删除标签
	 */
	public function deleteTag(){
		// 待删除的标签id串
		$ids = $this->_get('deleteTag');
		$act = $this->_get('act');
		// 当前页码
		$page = $this->_get('page');
		// 解析标签id串
		$idArr = explode(',',$ids);
		$tagModel = D('UcTag');

		// “根标签”和“未分类”标签id
		$tagLimit = C("TAG_LIMIT");		
		foreach ($tagLimit as $v) {
			$tagids[] = $tagModel->where('name = "'.$v.'"')->getField('id');
		}

		// 查看删除的标签中，是否有锁定的标签
		foreach ($idArr as $key => $val) {
			// 标签信息
			$data = $tagModel->getInfoByTagId($val,'locked,name');
			// 标签被锁定，不能被删除
			if ($data['locked']) {
				$res = array('status'=>'error','msg'=>'标签“'.$data['name'].'”已被锁定，无法删除！');
				$this->ajaxReturn($res,'JSON');
			}
			// “根标签”和“未分类”标签不能被删除
			if (in_array($val, $tagids)) {
				$this->ajaxReturn(array('status'=>'error','msg'=>'不允许删除"根标签或"者"未分类"标签！'),'JSON');
			}
		}
		foreach($idArr as $key=>$val){
			if($val){
				$res = D('UcTag')->delTag($val);
				if ($res) {
					// 删除标签记录后台操作日志
					$this->recordOperations(2,11,$val);

					// 删除标签变更历史记录
					$tagModel -> addTagHistory('DEL',$val,$val);

					// 更新搜索库：删除标签
					$url = C("C_DIR") . "/index.php/Public/xs?param[operation_type]=del&param[config_object]=1&param[pid]=".$val."&param[type]=5";
					get_url($url);
				}
			}
		}
		
		if(empty($act)){
			//$this->redirect('/iadmin.php/Tag/index?page='.$page);
			echo "<script>history.back();</script>";
		}else{
			echo 1;
			exit;
		}
	}

	/*
	*标签锁定/取消锁定
	*/
	public function lockedTag(){
		
		$id = $this->_get('lockedTag');
		$tagModel = D('UcTag');
		$act = $this->_get('act');
		$idArr = explode(',',$id);
		// print_r($idArr);exit; 
		if (count($idArr)>1) { //批量锁定即使一个，也会多加个逗号
			foreach ($idArr as $val) {
				if ($val) {
					$tagInfo = $tagModel->getInfoByTagId($val,'locked');
					$tagModel->where(array('id'=>$val))->save(array('locked'=>1));
					// 添加标签变更历史记录
					if ($tagInfo['locked'] == 0) {
						$tagModel -> addTagHistory('MOD',$val,'locked',0,1);
					}
				}
			}
			// echo M()->getLastSql();exit;
			if(empty($act)){
				echo "<script>history.back();</script>";
			}else{

				echo 1;
				exit;
			}
		}else{
			$locked = $this->_get('locked');
			$page = $this->_get('page');
			$tagInfo = $tagModel->getInfoByTagId($id,'locked');
			$tagModel->where(array('id'=>$id))->save(array('locked'=>$locked));
			// 添加标签变更历史记录
			$tagModel -> addTagHistory('MOD',$id,'locked',$tagInfo['locked'],$locked);
			$this->redirect('/iadmin.php/Tag/index?page='.$page);
		}
		
	}

	/*
	*标签屏蔽/取消屏蔽
	*/
	// public function shieldTag(){
	// 	$id = $this->_get('shieldTag');
	// 	$status = $this->_get('status');
	// 	$page = $this->_get('page');
	// 	$tagModel = D('UcTag');
	// 	$tagModel->where(array('id'=>$id))->save(array('status'=>$status));
	// 	$this->redirect('/iadmin.php/Tag/index?page='.$page);
	// }

	

	/*
	 * 检查标签是否存在
	 */
	public function ajaxCheckTagIsExists(){

		$tagName = $this->_get('tagName');
		$tagName = trim($tagName);
		if (empty($tagName)) exit;	
		$res = D('UcTag')->checkTagIsExists($tagName);
		if ($res) {
			$data = array('status'=>'ok','msg'=>'该标签已经存在！');
		}else{
			$data = array('status'=>'false');
		}
		$this->ajaxReturn($data,'JSON');
	}

	/*
	 * 搜索类似的标签名
	 */
	public function ajaxSearchTagByName(){
		// get参数：操作标志
		$param['act'] = $this->_get('act');
		if(!$param['act']) {
			$this->ajaxReturn(array('status'=>'error', 'msg'=>'参数丢失！'),'JSON');
		}
		// get参数：标签id
		$param['id'] = $this->_get('tagId');

		// get参数：搜索关键词
		$param['keyword'] = $this->_get('keyword');
		if(!$param['keyword']) {
			$this->ajaxReturn(array('status'=>'error', 'msg'=>'参数丢失！'),'JSON');
		}
		// 搜索标签
		$res = json_decode(post_url(C('BK_DIR').'/index.php/Api/ajaxSearchTagList',$param), true);

		$this->ajaxReturn($res, 'JSON');
	}

	/*
	 * 添加父标签
	 */
	public function ajaxAppendPtag(){
		// 标签id
		$param['id'] = $this->_get('id');
		if (!$param['id']) {
			$this->ajaxReturn(array('status'=>'error','msg'=>'参数丢失！'),'JSON');
		}
		// 父标签id
		$param['pid'] = $this->_get('pid');
		if (!$param['pid']) {
			$this->ajaxReturn(array('status'=>'error','msg'=>'参数丢失！'),'JSON');
		}
		// 用户id
		$param['uid'] = session('boqiiUserId');
		// 用户名
		$param['username'] = session('boqiiUserName');
		// 用户类型
		$param['user_type'] = 10;		
		// 搜索标签
		$res = json_decode(post_url(C('BK_DIR').'/index.php/Api/ajaxAppendPtag',$param), true);
		// 添加历史记录
		if ($res['status'] == 'ok') {
			$tagName = D('UcTag')->where('id='.$param['pid'])->getField('name');
			D('UcTag')-> addTagHistory('APP',$param['id'],'父标签：'.$tagName);
		}

		$this->ajaxReturn($res, 'JSON');
	}


	/*
	 * 添加标签子标签
	 */
	public function ajaxAppendStag(){
		// 标签id
		$param['id'] = $this->_get('id');
		if (!$param['id']) {
			$this->ajaxReturn(array('status'=>'error','msg'=>'参数丢失！'),'JSON');
		}
		// 父标签id
		$param['pid'] = $this->_get('pid');
		if (!$param['pid']) {
			$this->ajaxReturn(array('status'=>'error','msg'=>'参数丢失！'),'JSON');
		}
		// 用户id
		$param['uid'] = session('boqiiUserId');
		// 用户名
		$param['username'] = session('boqiiUserName');
		// 用户类型
		$param['user_type'] = 10;		
		// 搜索标签
		$res = json_decode(post_url(C('BK_DIR').'/index.php/Api/ajaxAppendStag',$param), true);
		// 添加历史记录
		if ($res['status'] == 'ok') {
			$tagName = D('UcTag')->where('id='.$param['pid'])->getField('name');
			D('UcTag')-> addTagHistory('APP',$param['id'],'子标签：'.$tagName);
		}
		$this->ajaxReturn($res, 'JSON');
	}

	/*
	 * 删除标签父子级关系
	 */
	public function ajaxMovePtag(){
		// 标签id
		$param['id'] = $this->_get('id');
		if (!$param['id']) {
			$this->ajaxReturn(array('status'=>'error','msg'=>'参数丢失！'),'JSON');
		}
		// 父标签id
		$param['pid'] = $this->_get('pid');
		if (!$param['pid']) {
			$this->ajaxReturn(array('status'=>'error','msg'=>'参数丢失！'),'JSON');
		}
		// 用户id
		$param['uid'] = session('boqiiUserId');
		// 用户名
		$param['username'] = session('boqiiUserName');
		// 用户类型
		$param['user_type'] = 10;		
		// 搜索标签
		$res = json_decode(post_url(C('BK_DIR').'/index.php/Api/ajaxMovePtag',$param), true);
		// 添加历史记录
		if ($res['status'] == 'ok') {
			$tagName = D('UcTag')->where('id='.$param['pid'])->getField('name');
			D('UcTag') -> addTagHistory('MOV',$param['id'],'父标签：'.$tagName);
		}
		$this->ajaxReturn($res, 'JSON');
		
	}

	/*
	 * 删除标签子标签
	 */
	public function ajaxMoveStag(){
		// 标签id
		$param['id'] = $this->_get('id');
		if (!$param['id']) {
			$this->ajaxReturn(array('status'=>'error','msg'=>'参数丢失！'),'JSON');
		}
		// 父标签id
		$param['pid'] = $this->_get('pid');
		if (!$param['pid']) {
			$this->ajaxReturn(array('status'=>'error','msg'=>'参数丢失！'),'JSON');
		}
		// 用户id
		$param['uid'] = session('boqiiUserId');
		// 用户名
		$param['username'] = session('boqiiUserName');
		// 用户类型
		$param['user_type'] = 10;		
		// 搜索标签
		$res = json_decode(post_url(C('BK_DIR').'/index.php/Api/ajaxMoveStag',$param), true);
		// 添加历史记录
		if ($res['status'] == 'ok') {
			$tagName = D('UcTag')->where('id='.$param['pid'])->getField('name');
			D('UcTag') -> addTagHistory('MOV',$param['id'],'子标签：'.$tagName);
		}
		$this->ajaxReturn($res, 'JSON');
		
	}
	/*
	 * 添加文章，词条标签关系
	 */
	// public function ajaxAddTagObject(){
		
	// 	$data['id'] = $this->_get('id');
	// 	if (!$data['id']) exit;
	// 	$data['objId'] = $this->_get('objId');
	// 	$data['type']  = $this->_get('type');
	// 	// 添加标签
	// 	$res = D('UcTag')->addTagObject($data);
	// 	if ($res) {
	// 		$this->ajaxReturn(array('status'=>'ok'),'JSON');
	// 	}else{
	// 		$this->ajaxReturn(array('status'=>'error','msg'=>'添加标签失败！'),'JSON');
	// 	}
	// }

	/*
	 * 删除文章，词条标签关系
	 */
	// public function ajaxDelTagObject(){
	// 	$data['objId'] = $this->_get('objId');
	// 	$data['id'] = $this->_get('id');
	// 	if (!$data['id']) exit;
	// 	$data['objId'] = $this->_get('objId');
	// 	$data['type']  = $this->_get('type');
	// 	// 删除标签名
	// 	$res = D('UcTag')->delTagObject($data);
	// 	if ($res) {
	// 		$this->ajaxReturn(array('status'=>'ok'),'JSON');
	// 	}else{
	// 		$this->ajaxReturn(array('status'=>'error','msg'=>'删除标签失败！'),'JSON');
	// 	}
		
	// }

	/*
	 * 创建标签页面
	 */
	public function createTag(){
		$theColumn = C('THE_COLUMN');
		$this->assign('theColumn',$theColumn);
		
		$this->display('createTag');
	}
	/*
	 * 添加标签
	 */
	public function addTag(){
		// header('content-type:text/html;charset=utf-8');
		$data = $this->_post();
		$data['tag'] = trim($data['tag']);
		// 标签不能为空
		if(empty($data['tag'])) alert('请输入标签名！');
		if(mb_strlen($data['tag'],'utf-8') > 10) alert('标签名称 10个汉字以内！');
		// 判断标签是否存在
		$result = D('UcTag')->checkTagIsExists($data['tag']);
		if (!$result) {
			$param['name'] = $data['tag'];
		}else{
			alert('该标签已存在！');
		}
		
		// 判断照片
		if($data['pic_path']) 	$param['logo'] = $data['pic_path'];
		// 判断别名
		if($data['alias']) 		$param['alias'] = $data['alias'];
		// 判断描述
		if($data['content']){
			if (mb_strlen($data['content'],'utf-8') > 100) {
				alert('标签描述 100个字以内！');
			}
			$param['memo'] = $data['content'];
		} 	
		// 判断父级标签
		if($data['tagids']) 	$ptag = $data['tagids'];
		// 类型
		$param['type'] = $data['type'];

		$tagModel = D('UcTag');
		$param['uid'] = session('boqiiUserId');
		$param['modify_uid'] = session('boqiiUserId');
		
		//添加标签
		$res = $tagModel -> addTag($param,$ptag);
		if ($res) {
			//添加后台日志记录
			$this->recordOperations(1,11,$res);
			// 更新搜索库：新增标签
			$url = C("C_DIR") . "/index.php/Public/xs?param[operation_type]=add&param[config_object]=1&param[pid]=".$res."&param[type]=5";
			get_url($url);
			
			showmsg('添加成功！','/iadmin.php/Tag/index');
		}else{
			alert('添加失败！');
		}
		
	}

	
	/*
	 * 标签树页面
	 */
	public function tagTree(){
		$id = $this->_get('tagId');
		$tagModel = D('UcTag');
		//获得父级标签路径
		$tagName = $tagModel->where('status >= 0 and id = '.$id)->getField('name');
		// $parTagTree = $tagModel->getParTagTree($id,'',$tagName);
		// echo "<pre>";print_r($parTagTree);
		echo <<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>标签树</title>
<link rel="stylesheet" type="text/css" href="/Public/Admin/Css/frame.css"/>
<script type='text/javascript' src='/Public/Js/jquery.js'></script>
<script type='text/javascript' src='/Public/Admin/Js/globals.js'></script>
<script type='text/javascript' src='/Public/Js/bq.js'></script>
</head>
<body>
<!--tab开始-->
<div class="common_tab">
    <ul>
		<li><a href="/iadmin.php/Tag/index"><span>标签管理</span></a></li>
        <li><a href="/iadmin.php/Tag/editPage?id={$id}"><span>编辑标签</span></a></li>
        <li class="current"><a href="/iadmin.php/Tag/tagTree?tagId={$id}"><span>标签树</span></a>		</li>
    </ul>
</div>
<!--tab结束-->
<div class="wrap">
    <!--创建标签开始-->
    <div class="common_tb">
		<input type="hidden" value="{$id}" name="id" id="tagid"/>
		<!--<b>父级标签</b>
        <ul style="margin-left:60px;">-->
EOF;
/*$tree = '';
		$tagModel->getParTagTree($id,$tree,$id);
		dump($tree);
    	echo ' </ul>'; 
    	echo str_repeat('——  ——  ', 20);*/
    	echo '<br><br>
    	<b>子级标签</b>
    	<ul style="margin-left:60px;">';
    	$res = $tagModel->getSecTagTree($id,'',$tagName);
    	echo '<span>●&nbsp;&nbsp;'.$tagName.'</span><br>';
    	foreach ($res as $k => $v) {
    		echo $v['msg'];
    	}
    echo '</ul>
    </div> 
    <!--创建标签结束-->
    <script>
    	function loadMore(obj){
			var space = $(obj).attr("space");
			var tagid = $(obj).attr("tagid");
			var page  = $(obj).attr("page");
			if(tagid){
				$.getJSON(
					"/iadmin.php/Tag/ajaxGetSubRelation",
					{tagId:tagid,space:space,page:page},
					function(list){ 
						if(list.status == "ok"){
							var _str = "";
							for (var i=0; i < list.num; i++) { 
								_str += list[i]["msg"];
								if (list[i]["submsg"]) {
									_str += list[i]["submsg"];
								}	
							}
							//alert(_str);
							if(list.loadMoreTagList){
								_str += list.loadMoreTagList;
							}
							if(list.page > 1){
								$(obj).before(_str).remove();
								
							}else{
								$(obj).html(_str);
								
							}
							
							$(obj).removeAttr("onclick");
							$(obj).removeAttr("id");
						}else{
							alert(list.msg);
						}
					}
				)
			}
		}
    </script>
</div>
</body>
</html>';
   	
		// $this->assign('id',$id);
		// $this->display('tagTree');
	}

	/*
	 * 标签树页面
	 */
	public function treeStruct(){
		
		$tagModel = D('UcTag');
		$id = $tagModel->getBkParentTagId(C('TAG_LIMIT.1'));

		$subTagList = $tagModel->getSecTagTree($id,'',$tagName);
   		// echo "<pre>";print_r($subTagList);
		
		$this->assign('subTagList',$subTagList);
		$this->display('treeStruct');
	}


	/*
	 * 通过标签id获得关系表子级标签的id
	 */
	public function ajaxGetSubRelation(){
		$id = $this->_get('tagId');
		$num = $this->_get('space');
		$page = $this->_get('page');
		if (!$id) {
			$this->ajaxReturn(array('status'=>'error','msg'=>'参数丢失！'),'JSON');
		}
		$subTagList = D('UcTag')->getSubTagList($id,$num,$page);
		$this->ajaxReturn($subTagList,'JSON');
	}

	/*
	 * 批量添加父级标签和合并标签页面
	 */
	public function batchTagOperation(){
		$tagIds = trim($this->_get('tagIds'),',');
		// 得到相应的信息
		if ($tagIds) {
			$tagIdArr = explode(',', $tagIds);
		}
		$UcTag = D('UcTag');
		// 通过标签id依次得到标签名
		foreach ($tagIdArr as $key => $val) {
			$tag = $UcTag->getInfoByTagId($val,'name');
			$tagList[$key]['id'] = $val;
			$tagList[$key]['name'] = $tag['name']; 
		}
		// echo "<pre>";print_r($tagList);
		$this->assign('tagList',$tagList);
		$this->assign('tagIds',$tagIds);
		$this->display('batchOperation');
	}

	/*
	 * 批量添加父级标签和合并标签页面操作
	 */
	public function tagBatchOperation(){
		//ilocal.boqii.com/iadmin.php/Tag/tagBatchOperation?tagIds=27175,27174&tagId=27172&type=
		set_time_limit(0);
		
		// 起始标签组
		$tagId1 = $this->_post('tagids1');
		if (empty($tagId1)) {
			alert('请输入来源标签！');
		}
		// 目标标签
		$tagId2 = $this->_post('tagids2');
		if (empty($tagId2)) {
			alert('请输入目标标签！');
		}
		// 动作类型 1：批量合并 2：批量添加父级标签
		$type 	= $this->_post('type');
		if (!$type) {
			alert('请选择标签操作类型！');
		}

		$UcTag	= D('UcTag');
		$tagIdArr = explode(',', trim($tagId1,','));
		// 判断来源标签是否含有目标标签
		if (in_array($tagId2, $tagIdArr)) {
			alert('操作失败，来源标签中不能含有目标标签！');
		}
// echo "<pre>";print_r($tagIdArr);
		// 批量合并
		if ($type == 1) {
			// “根标签”和“未分类”标签id
			$tagLimit = C("TAG_LIMIT");		
			foreach ($tagLimit as $v) {
				$tagids[] = $UcTag->where('name = "'.$v.'"')->getField('id');
			}

			// 查看删除的标签中，是否有锁定的标签
			foreach ($tagIdArr as $key => $val) {
				// 标签信息
				$data = $UcTag->getInfoByTagId($val,'locked,name');
				
				// 标签被锁定，不能被删除
				if ($data['locked']) {
					// $res = array('status'=>'error','msg'=>'标签“'.$data['name'].'”已被锁定，无法删除！');
					// $this->ajaxReturn($res,'JSON');
					alert('来源标签中含有锁定标签，无法合并！');
				}
				// “根标签”和“未分类”标签不能被删除
				if (in_array($val, $tagids)) {
					// $this->ajaxReturn(array('status'=>'error','msg'=>'不允许删除"根标签或"者"未分类"标签！'),'JSON');
					alert('不允许合并“根标签”或者“未分类”标签！');
				}
			}
			// print_r($tagLimit);exit;
			// 合并关系： 父子级关系 文章，问答，词条关系  属性：别名，文章，问答，词条，关注数量  删除标签一 ！！！）
			$res = $UcTag->batchMergeRelation($tagId1,$tagId2);
			if ($res) {
				foreach ($tagIdArr as $key => $val) {
					// 添加标签变更历史记录
					$UcTag -> addTagHistory('MER',$val,'','','',$tagId2);
					// 更新搜索库：删除标签
					$url = C("C_DIR") . "/index.php/Public/xs?param[operation_type]=del&param[config_object]=1&param[pid]=".$val."&param[type]=5";
					get_url($url);
					// 更新搜索库：编辑标签二
					$url = C("C_DIR") . "/index.php/Public/xs?param[operation_type]=update&param[config_object]=1&param[pid]=".$tagId2."&param[type]=5";
					get_url($url);
				}
			}else{
				
				alert('合并失败！请检查来源标签是否正确！');
			}
			
			$msg = '批量合并成功！被合并的标签已经删除！';
			
		}else{ // 批量添加父级标签

			foreach ($tagIdArr as $k=>$val) {
				
				// 标签id
				$param['id'] = $val;
				
				// 父标签id
				$param['pid'] = $tagId2;
				
				// 用户id
				$param['uid'] = session('boqiiUserId');
				// 用户名
				$param['username'] = session('boqiiUserName');
				// 用户类型
				$param['user_type'] = 10;		
				// 搜索标签
				$res = json_decode(post_url(C('BK_DIR').'/index.php/Api/ajaxAppendPtag',$param), true);
				// 添加历史记录
				if ($res['status'] == 'ok') {
					$tag = $UcTag->getInfoByTagId($val,'name');
					$UcTag-> addTagHistory('APP',$param['id'],'父标签：'.$tag['name']);
				}else{
					
					alert($res['msg'].'添加父级操作中断！');
				}
			}
			$msg = '批量添加父级标签成功！';
		}
		showmsg($msg,'/iadmin.php/Tag/index');
	}
	
	/*
	 * 标签合并页面
	 */
	public function tagMerge(){
		$id = $this->_get('tagId');
		//判断是否是锁定标签
		$res = D('UcTag')->where('id='.$id)->getField('locked');
		if ($res) {
			alert('该标签已被锁定，如需合并，请先解锁！');
		}
		// 根标签和未分类标签不能合并
		$tagLimit = C("TAG_LIMIT");
		$tagStr = implode(',', $tagids);
		foreach ($tagLimit as $val) {
			$tagids[] = D('UcTag')->where('name = "'.$val.'"')->getField('id');
		}
		// print_r($tagisds);
		if (in_array($id, $tagids)) {
			alert('根标签和未分类标签禁止合并！');
		}
		$this->assign('id',$id);
		$this->display('tagMerge');
	}

	/*
	 * 合并标签
	 */
	public function mergeTag(){
		// $tagName = $this->_post('tag');
		$tagId1  = $this->_post('id');
		$tagId2  = $this->_post('tagids');
		//判断标签名
		if(empty($tagId2)) alert('标签名不能为空！');
		// if(mb_strlen(trim($tagName),'utf-8') > 10) alert('标签名称 10个汉字以内！');
		$UcTag	 = D('UcTag');
		// $tagId2  = $UcTag->getBkParentTagId($tagName);
		
		// 合并关系： 父子级关系 文章，问答，词条关系  属性：别名，文章，问答，词条，关注数量  删除标签一 ！！！）
		$res = $UcTag->mergeRelation($tagId1,$tagId2);
		
		if ($res) {
			// 添加标签变更历史记录
			$UcTag -> addTagHistory('MER',$tagId1,'','','',$tagId2);
			// 更新搜索库：删除标签
			$url = C("C_DIR") . "/index.php/Public/xs?param[operation_type]=del&param[config_object]=1&param[pid]=".$tagId1."&param[type]=5";
			get_url($url);
			// 更新搜索库：编辑标签二
			$url = C("C_DIR") . "/index.php/Public/xs?param[operation_type]=update&param[config_object]=1&param[pid]=".$tagId2."&param[type]=5";
			get_url($url);
			showmsg('合并成功！被合并的标签已经删除！','/iadmin.php/Tag/index');
			
		}else{
			alert('合并失败！');
		}
		// echo $tagId2;
	}

	/*
	 * 标签历史操作日志页面
	 */
	public function tagHistory(){
		$id = $this->_get('tagId');
		$tagHistoryList = D('UcTag')->getTagHistory($id);
		// echo "<pre>";print_r($tagHistoryList);

		$this->assign('id',$id);
		$this->assign('tagHistoryList',$tagHistoryList);
		$this->display('tagHistory');
	}

	/*
	 * 标签编辑页面
	 */
	public function editPage(){

		$id = $this->_get('id');
		// 通过标签id获得数据
		$Tag = D('UcTag')->getInfoByTagId($id);
		// 获得父子级标签
		$tagRelation = D('UcTag')->getRelationByTagId($id);
		$this->assign('tagRelation',$tagRelation);

		$theColumn = C('THE_COLUMN');
		$this->assign('theColumn',$theColumn[$Tag['type']]);

		// 所属栏目
		$UcAdmin = D('UcAdmin');
		// 获取创建者信息
		$createName = $UcAdmin->where(array('id'=>$Tag['uid']))->getField('username');
		// 获取修改者信息
		$modifyName = $UcAdmin->where(array('id'=>$Tag['modify_uid']))->getField('username');
		
		$this->assign('createName',$createName);
		$this->assign('modifyName',$modifyName);
		$this->assign('Tag',$Tag);
		$this->assign('id',$id);
		$this->display('editPage');
	}

	/*
	 * 提交编辑
	 */
	public function editTag(){
		// header('content-type:text/html;charset=utf-8');
		$boqiiUserId = session('boqiiUserId');
		$tagModel = D('UcTag');
		$data = $this->_post();
		$data['tag'] = trim($data['tag']);
		//标签不能为空
		if(empty($data['tag'])) alert('请输入标签名！');
		if(mb_strlen($data['tag'],'utf-8') > 10) alert('标签名称 10个汉字以内！');
		// 判断标签是否存在
		$tagTrueName = $tagModel->getInfoByTagId($data['id'],'name');
		if ($tagTrueName['name'] != $data['tag']) {
			$result = $tagModel->checkTagIsExists($data['tag']);
			if ($result) {
				alert('该标签已存在！');
			}
		}
		
		// 根标签和未分类标签不能修改名称
		$tagLimit = C("TAG_LIMIT");
		$tagName = $tagModel->where('id='.$data['id'])->getField('name');
		if (in_array($tagName, $tagLimit)) {
			if ($data['tag'] != $tagName) {
				alert('根标签和未分类标签禁止修改名称！');
			}
			
		}
		// exit;
		$param['name']	= $data['tag'];
		$param['id']	= $data['id'];
		//判断照片
		$param['logo'] = $data['pic_path'];
		//判断别名
		$param['alias'] = $data['alias']?$data['alias']:'';
		
		// 判断描述
		if($data['content']){
			if (mb_strlen($data['content'],'utf-8') > 100) {
				alert('标签描述 100个字以内！');
			}
			$param['memo'] = $data['content'];
		}else{
			$param['memo'] = '';
		}
		//判断是否锁定
		$param['locked'] = isset($data['locked']) ? $data['locked'] : '0';
		//判断父级标签
		$ptag = !empty($data['ptag']) ? $data['ptag'] : '';
		//判断子级标签
		$stag = !empty($data['stag']) ? $data['stag'] : '';
		//通过标签id获得相关信息
		$tagInfo = $tagModel->where('id='.$data['id'])->field('name,id,logo,alias,memo,locked')->select();
		
		// 判断改变的字段
		$arr = getChangeCloum($tagInfo,$param);

		$param['modify_uid'] = session('boqiiUserId');
		$res = $tagModel->saveTag($param,$ptag,$stag);
		if ($res) {
			foreach($arr as $key=>$val){
				// 添加后台操作日志记录
				if ($val['column'] == 'locked' && !$val['afterContent']) {
					$this->recordOperations(3,12,$param['id'],'','','',$val['column'],'1锁定','0不锁定');
				}else if ($val['column'] == 'locked' && $val['afterContent']) {
					$this->recordOperations(3,12,$param['id'],'','','',$val['column'],'0不锁定','1锁定');
				}else{
					$this->recordOperations(3,12,$param['id'],'','','',$val['column'],$val['beforeContent'],$val['afterContent']);
				}
				// 添加标签变更历史记录
				$tagModel -> addTagHistory('MOD',$param['id'],$val['column'],$val['beforeContent'],$val['afterContent']);
			}
			
			// 更新搜索库：编辑标签（标签名和别名）
			$url = C("C_DIR") . "/index.php/Public/xs?param[operation_type]=update&param[config_object]=1&param[pid]=".$param['id']."&param[type]=5";
			get_url($url);
			showmsg('编辑成功！','/iadmin.php/Tag/index');
		}else{
			alert('编辑失败！');
		}
		
	}

	/************************************ 资讯标签 START ************************************/

	/*
	 * 资讯标签管理页面
	 */
	public function newsTagList(){
		$tagModel = D('UcTag');
		//页显数量
		$limit = 20;
		//当前页，默认为第1页
		$page = $this->_get('page');
		if($page=='' || !is_numeric($page)){
			$page=1;
		}
		$where="tag.status>=0 AND tag.type=15";

		$url='/iadmin.php/Tag/newsTagList?';
		
		//搜索条件
		$theDate = C('THE_DATE');

		$noAllow = C('NO_ALLOW');
		$data = $this->_get('data');
		if($data){
			//标签名
			if(!in_array($data['keyword'],$noAllow) && !empty($data['keyword'])){
				$where.=" and tag.name like '%".$data['keyword']."%' ";
				$url.='data[keyword]='.urlencode($data['keyword']).'&';
				$this->assign('keyword',$data['keyword']);
			}
			//创建时间
			if(!empty($data['createtime'])){
				$where.=" and tag.dateline>=".getTime($theDate[$data['createtime']]['days']);
				$url.='data[createtime]='.$data['createtime'].'&';
				$this->assign('createtime',$data['createtime']);
			}
			//使用次数
			if(!empty($data['startuse'])){
				$where.=" and tag.usetimes>=".$data['startuse'];
				$url.='data[startuse]='.$data['startuse'].'&';
				$this->assign('startuse',$data['startuse']);
			}
			if(!empty($data['enduse'])){
				$where.=" and tag.usetimes<=".$data['enduse'];
				$url.='data[enduse]='.$data['enduse'].'&';
				$this->assign('enduse',$data['enduse']);
			}
			//创建人
			if(!in_array($data['username'],$noAllow) && !empty($data['username'])){
				//查找创建人
				$strUids = D('UcAdmin')->getStrUidsByUsername($data['username']);
				if($strUids) {
					$where .= " and tag.uid IN (". $strUids.") ";
				}
				$url.='data[username]='.urlencode($data['username']).'&';
				$this->assign('username',$data['username']);
			}
		}

		//搜索结果数
		$tagCount = $tagModel->hasTagCount($where);
		//当前页不能超过最后页
		$pcount = ceil($tagCount/$limit);
		if($page>=$pcount){
			$page=$pcount;
		}
		$this->assign('urlOrder',$url);
		//排序
		if($data['sort']){
			$sort = $data['sort'];
		} else {
			$sort = "id";
		}
		$url.='data[sort]='.$sort.'&';
		if($data['desc']) {
			$desc = $data['desc'];
		} else {
			$desc = 'desc';
		}
		$url.='data[desc]='.$desc.'&';
		$order = $sort.' ' . $desc;
		$this->assign('sort',$sort);
		$this->assign('desc',$desc);
		// echo $order;
		$url.='page=';
		//搜索结果
		$tags = $tagModel->getNewsTagList($page,$limit,$where,$order);
		//栏目
		$theColumn = C('THE_COLUMN');
		foreach($tags as $key=>$val){
			$tags[$key]['typeName']=$theColumn[$val['type']];
		}
		
		$pageHtml = $this->page($url,$pcount,$limit,$page,count($tags));

		$this->assign('url',$url.$page);
		$this->assign('theDate',$theDate);
		$this->assign('theColumn',$theColumn);
		$this->assign('pageHtml',$pageHtml);
		$this->assign('tags',$tags);
		// echo "<pre>";print_r($tags);
		$this->assign('page',$page);
		$this->display('newsTagList');	
	}

	/*
	*创建标签页面
	*/
	public function newsTagAdd(){
		$this->display('newsTagAdd');
	}

	/**
	 *标签编辑页面
	 */
	public function newsTagEdit(){
		$id = $this->_get('id');
		$tagModel = D('UcTag');
		$Tag = $tagModel->getInfoByTagId($id);
		$theColumn = C('THE_COLUMN');
		$this->assign('theColumn',$theColumn[$Tag['type']]);
		//所属栏目
		$UcAdmin = D('UcAdmin');
		//获取创建者信息
		$createName = $UcAdmin->getAdminInfoById($Tag['uid']);
		//获取修改者信息
		$modifyName = $UcAdmin->getAdminInfoById($Tag['modify_uid']);
		
		$this->assign('createName',$createName['username']);
		$this->assign('modifyName',$modifyName['username']);
		$this->assign('Tag',$Tag);
		$this->assign('id',$id);
		$this->display('newsTagEdit');
	}

	/* 
	 * 保存标签
	 */
	public function newsTagSave(){
		// 登录用户id
		$boqiiUserId = session('boqiiUserId');
		// 操作标志
		$act = $this->_post('act');
		// 新增处理
		if($act == 'add') {
			// 标签类型：资讯
			$data['type'] = 15;
			$tags = trim($this->_post('tags'));
			if(empty($tags)){
				alert('标签不能为空！');
			}
			$arr = explode (' ', $tags);
			// print_r($arr);exit;
			$tagModel = D('UcTag');
			foreach($arr as $key=>$val){
				$tagIdCount = $tagModel->newsTagIsExists($val);
				if(!$tagIdCount){
					$data['name']=$val;
					$data['dateline']=time();
					$data['uid']=$boqiiUserId;
					$data['modify_uid']=$boqiiUserId;
					$data['updatetime']=time();
					$id = $tagModel->add($data);

					$this->recordOperations(1,11,$id);
				}
			}
			$this->redirect('/iadmin.php/Tag/newsTagList');
		}
		else{// 编辑处理
			$data['id'] = $this->_post('id');

			$data['name'] = $this->_post('name');
			
			if($this->_post('status')){
				$data['status'] = $this->_post('status');
			}else{
				$data['status'] = 0;
			}
			// echo "<pre>";print_r($data);exit;
			$tagModel = D('UcTag');
			$Tag = $tagModel->where(array('id'=>$data['id']))->field('id,name,status')->select();
			// 查看变化的字段
			$arr = getChangeCloum($Tag,$data);
			foreach($arr as $key=>$val){
					$this->recordOperations(3,12,$data['id'],'','','',$val['column'],$val['beforeContent'],$val['afterContent']);
			}
			$data['modify_uid']=$boqiiUserId;
			$tagModel->save($data);
			$this->redirect('/iadmin.php/Tag/newsTagList');
		}
	}
	
	/**
	 * 屏蔽资讯标签
	 */
	public function newsTagShield(){
		// 标签id
	 	$id = $this->_get('shieldTag');
		// 状态（2屏蔽）
	 	$status = $this->_get('status');
		// 当前页码
	 	$page = $this->_get('page');
		// 屏蔽资讯 TODO
	 	D('UcTag')->where(array('id'=>$id))->save(array('status'=>$status));
		// 页面跳转
	 	$this->redirect('/iadmin.php/Tag/newsTagList?page='.$page);
	 }

	/**
	 * 批量删除资讯标签
	 */
	public function newsTagDel(){
		// 待删除的标签id串
		$ids = $this->_get('newsTagDel');
		$act = $this->_get('act');
		// echo $ids;exit;
		// 解析标签id串
		$idArr = explode(',',$ids);
		$tagModel = D('UcTag');
		foreach($idArr as $key=>$val){
			if($val){
				$res = D('UcTag')->delTag($val);
				if ($res) {
					// 删除标签记录后台操作日志
					$this->recordOperations(2,11,$val);
				}
			}
		}
		
		if(empty($act)){
			//$this->redirect('/iadmin.php/Tag/index?page='.$page);
			echo "<script>history.back();</script>";
		}else{
			echo 1;
			exit;
		}
	}

	/**
	 * 批量关联相关资讯
	 */
	public function newsTagReaNewsList(){
		// 待删除的标签id串
		$limit = 20;
		$page=$this->_get('page');

		// echo $tagId;
		if($page=='' || !is_numeric($page)){
			$page=1;
		}

		$url='/iadmin.php/Tag/newsTagReaNewsList?';
		$where="status = 1";
		// 储存标签id
		if ($this->_get('id')) {
			$tagId = $this->_get('id');
			$tagIdList = D('UcTag')->getNewsIdByTagId($tagId);
			$url .= "id={$tagId}&";
			$this->assign('tagId',$tagId);
		}
		
		if($this->_get('data')){
			$data = $this->_get('data');
			if(trim($data['title']) && ($data['title'] != '输入标题关键字')){
				$where .= " and title LIKE '%{$data['title']}%' ";
				$url .= "data[title]={$data['title']}&";
				$this->assign('title',$data['title']);
			}
			// if(trim($data['starttime'])){
			// 	$where.=" and create_time >= ".strtotime($data['starttime'].' 00:00:00');
			// 	$url.='data[starttime]='.$data['starttime'].'&';
			// 	$this->assign('starttime',$data['starttime']);
			// }
			// if(trim($data['endtime'])){
			// 	$where.=" and create_time <= ".strtotime($data['endtime'].' 23:59:59');
			// 	$url.='data[endtime]='.$data['endtime'].'&';
			// 	$this->assign('endtime',$data['endtime']);
			// }
			// if($data['status'] != 'all' &&  (isset($data['status'])) ){
			// 	$where .= " and status = {$data['status']}";
			// 	$url .= "data[status]={$data['status']}&";
			// 	$this->assign('status',$data['status']);
					
			// }
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
		$count = ceil($newsCount/$limit);
		if ($page > $count) {
			$page = $count;
		}
		$list = $news->getNewsList($where,$page,$limit,$tagId);
		
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
		
	
		$this->display('newsTagReaNewsList');
	}

	/**
	 * 批量资讯关联资讯标签
	 */
	public function relationTagAndNews(){
		// 待删除的标签id串
		$ids 	= $this->_post('id');
		$act 	= $this->_post('act');
		$tagId 	= $this->_post('tagId');
		// echo $ids;exit;
		// 解析标签id串
		$idArr = explode(',',$ids);
		$tagModel = D('UcTag');
		foreach($idArr as $key=>$val){
			if($val){
				//查看是否已经关联
				$id = $tagModel->tagAndNewsRelationIsExists($val,$tagId);
				if (!$id) {
					$res = $tagModel->relationTagAndNews($val,$tagId);
				}
				
			}
		}
		
		if(empty($act)){
			//$this->redirect('/iadmin.php/Tag/index?page='.$page);
			echo "<script>history.back();</script>";
		}else{
			echo 1;
			exit;
		}
	}

	/**
	 * 批量解除资讯关联资讯标签
	 */
	public function cancelTagAndNews(){
		// 待删除的标签id串
		$ids 	= $this->_post('id');
		$act 	= $this->_post('act');
		$tagId 	= $this->_post('tagId');
		// echo $ids;exit;
		// 解析标签id串
		$idArr = explode(',',$ids);
		$tagModel = D('UcTag');
		foreach($idArr as $key=>$val){
			if($val){
				//查看是否已经关联
				$id = $tagModel->tagAndNewsRelationIsExists($val,$tagId);
				if ($id) {
					$res = $tagModel->cancelTagAndNews($val,$tagId);
				}
				
			}
		}
		
		if(empty($act)){
			//$this->redirect('/iadmin.php/Tag/index?page='.$page);
			echo "<script>history.back();</script>";
		}else{
			echo 1;
			exit;
		}
	}
	/************************************ 资讯标签 END ************************************/

}
?>