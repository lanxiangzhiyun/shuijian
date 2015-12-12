<?php
/**
 * Api接口操作
 *
 * @created 2014-11-12
 * @author Fongson
 */
class ApiAction extends Action {
	/**
	 * 批量更新专家文章数，分类下文章数
	 */
	public function batchUpdateArticleNum() {
		// 文章Model实例化
		$articleModel = D('BkArticle');
		// 更新文章数目
		$articleModel->updateArticleNum();
		// 更新分类文章数
		$articleModel->updateParentArticleNum();
		// 更新专家文章数量
		$articleModel->updateExpertArticleNum();
	}

	/**
	 * 导出百科文章相关数据
	 */
	public function outputBaikeArticle() {
		set_time_limit(0);
		header("Content-Type: text/html;charset=utf-8");
		// 不分页
		$nopage = isset($_GET['nopage']) ? $this->_get('nopage') : 0;
		// 当前页码
		$page = isset($_GET['p']) ? $this->_get('p') : 1;
		// 页显数量
		$limit = 1500;

		// 小宠饲养、小宠医疗、水族饲养、水族医疗
		$secCatIds = M()->Table('bk_cat')->where('code IN ("spetfd","aqfd","spetmd","aqmd")')->getField('id', true);
		// 下属三级分类
		$cats = M()->Table('bk_cat')->where('parent_id IN ('. implode(',', $secCatIds) .')')->field('id,code,name')->select();
		foreach($cats as $key => $val) {
			$catIds[] = $val['id'];
			$catNames[$val['id']] = $val['name'];
			$catCodes[$val['id']] = $val['code'];
		}

		// 文章id、标题、摘要、三级分类名、三级分类code、标签名
		if($nopage == 1) {
			$articles = M()->Table('bk_article')->where('status=0 AND cat_id IN (' . implode(',', $catIds) .')')->field('id,title,summary,cat_id')->order('cat_id')->select();
		} else {
			$articles = M()->Table('bk_article')->where('status=0 AND cat_id IN (' . implode(',', $catIds) .')')->field('id,title,summary,cat_id')->order('cat_id')->page($page)->limit($limit)->select();
		}
		if(!$articles) {
			echo '已导出完毕！';exit;
		}
		echo '总数：'. M()->Table('bk_article')->where('status=0 AND cat_id IN (' . implode(',', $catIds) .')')->count();
		foreach($articles as $akey => $aval) {
			$articles[$akey]['cat_name'] = $catNames[$aval['cat_id']];
			$articles[$akey]['cat_code'] = $catCodes[$aval['cat_id']];
			// 标签
			$tagnames = M()->Table('boqii_tag_object o')->join('uc_tag t ON o.tag_id=t.id')->where('o.object_type=1 AND o.object_id='.$aval['id'].' AND o.status=0 AND t.status=0')->getField('name', true);
			$articles[$akey]['tagname'] = implode(',', $tagnames);
		}

		import('@.ORG.Util.PhpExcel');
		$doc[] = array ('文章id','标题','摘要','三级分类','三级分类code','标签名');
		foreach($articles as $k=>$v){
			$doc[] = array ($v['id'],$v['title'],$v['summary'],$v['cat_name'],$v['cat_code'],$v['tagname']);
		}
		 
		$xls = new Excel_XML;
		$xls->addArray($doc);
		$xls->generateXML("article_".date("Y-m-d"));
		die;
	}	


	/**
	 * 服务券评论图片调整
	 */
    public function praseVetCouponCommentImage() {
	   	set_time_limit(0);
	   	// 分页参数
	   	$page = isset($_GET['p']) ? $this->_get('p') : 1;
	   	$pageNum = 100;

    	// 
    	$commentList = M()->Table('o2o_comment_img')->where('status=0')->page($page)->limit($pageNum)->field('id,img_path')->select();
     	if(!$commentList) {
     		echo 'end';exit;
     	}	
    	foreach($commentList as $key=>$val) {

			$json = get_url(C('IMG_UPLOAD_DIR').'/Server/image.php?type=appcomment&imgpath='. C('IMG_DIR') . '/' .$val['img_path']);
			$result = json_decode($json, true);
			if($result['status'] == 'ok') {
					error_log(print_r(array('status'=>'ok', 'type'=>'服务券评论图片', 'path'=>C('IMG_DIR') . '/' .$val['img_path']), 1), 3, LOG_PATH . 'coupon' . date('Y-m-d').'.log');
			} else {
					error_log(print_r(array('status'=>'error', 'type'=>'服务券评论图片', 'path'=>C('IMG_DIR') . '/' .$val['img_path']), 1), 3, LOG_PATH . 'coupon' . date('Y-m-d').'.log');
			}
						
		}

   		header("Location: " . C('I_DIR') . '/iadmin.php/Api/praseVetCouponCommentImage/p/'.($page+1)); exit;		
    }	

    /**
	 * 批量增加现有用户的密码强度
	 */
    // public function batchAddPasswordSafe() {
    // 	$action = $this->_get('action');
    // 	$key = $this->_get('key');//6337ea668903972ec4e3dbc10be9ec09 
    // 	if(strcmp($key, '6337ea668903972ec4e3dbc10be9ec09')) exit;

    // 	// echo 2;exit;
    // 	switch ($action) {
    // 		case 'uc':
    // 			// 获取所有现有用户
		  //   	$adminList = M('uc_admin')->field('id,password')->where(array('status'=>0))->select();
		    	
		  //   	foreach ($adminList as $k => $val) {
		  //   		$password = doubleMd5($val['id'],$val['password']);
		  //   		M('uc_admin')->where('id = '.$val['id'])->save(array('password'=>$password));
		  //   	}
    // 			break;
    // 		case 'u':
    // 			// 获取所有现有用户
		  //   	$adminList = M('info_admin')->field('id,password')->where(array('status'=>0))->select();
		    	
		  //   	foreach ($adminList as $k => $val) {
		    		
		  //   		$password = doubleMd5($val['id'],$val['password']);
		  //   		M('info_admin')->where('id = '.$val['id'])->save(array('password'=>$password));
		  //   	}
    // 			break;
    // 		case 'bbs':
    // 			// 获取所有现有用户
		  //   	$adminList = M('new_bbs_adminuser')->field('uid,userpass')->where(array('valid'=>1))->select();
		    	
		  //   	foreach ($adminList as $k => $val) {
		    		
		  //   		$password = doubleMd5($val['uid'],$val['userpass']);
		  //   		M('new_bbs_adminuser')->where('uid = '.$val['uid'])->save(array('userpass'=>$password));
		  //   	}
    // 			break;
    // 		case 'vet':
    // 			// 获取所有现有用户
		  //   	$adminList = M('o2o_admin')->field('id,username,password')->where(array('status'=>array('egt',0)))->select();
		  //   	// echo "<pre>";print_r($adminList);echo M()->getLastSql();exit;
		  //   	foreach ($adminList as $k => $val) {
		    		
		  //   		$password = doubleMd5($val['id'],$val['password']);
		  //   		M('o2o_admin')->where('id = '.$val['id'])->save(array('password'=>$password));
		  //   	}
    // 			break;
    // 		default:
    // 			echo 'fail';
    // 			break;
    // 	}
    // 	echo 'success';
    // 	// echo "<pre>";print_r($res);exit;
    // }
}

?>