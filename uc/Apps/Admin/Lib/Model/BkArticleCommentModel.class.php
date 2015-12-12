<?php
class BkArticleCommentModel extends RelationModel {
	protected $tableName = "bk_article_comment";
	
	//文章评论列表
	public function getArticleCommentList($param) {
		$keyword = $param['keyword'];
		$starttime = $param['starttime'];
		$endtime = $param['endtime'];
		$category = $param['category'];
		$title = $param['title'];
		$selectType = $param['slt_type'];
		$user = $param['user'];
		$where = "a.status = 0";
		if(!empty($keyword)) {
			$where = $where ." and a.content like '%".$keyword."%' ";
		}
		if(!empty($starttime)) {
			$where = $where ." and a.create_time >= ".strtotime($starttime.' 00:00:00');
		}
		if(!empty($endtime)) {
			$where = $where ." and a.create_time <= ".strtotime($endtime.' 23:59:59');
		}
		// 三级分类
		if(!empty($param['thirdCatId'])) {
			$where = $where ." and d.cat_id = ".$param['thirdCatId']."";
		}
		// 没有选择三级分类，选择了二级分类
		if(!empty($param['secondCatId']) && empty($param['thirdCatId'])){
			$thirdCatIdList = D('BkArticle')->getSubCatListByParentId($param['secondCatId']);
			foreach($thirdCatIdList as $v){
				$thirdCatIds[] = $v['id'];
			}
			$strThirdCatIds = implode(",",$thirdCatIds);
			$where = $where ." and d.cat_id in (".$strThirdCatIds.")";
		}
		// 没有选择三级分类和二级分类，选择了一级分类
		if(!empty($param['firstCatId']) && empty($param['secondCatId']) && empty($param['thirdCatId'])){
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

			$where = $where ." and d.cat_id in (".$strThirdCatIds.")";
		}
		if(!empty($title)) {
			$where = $where ." and d.title like '%".$title."%' ";
		}
		if(!empty($selectType)) {
			if($selectType == 1 && $user) {
				$where = $where ." and c.nickname like '%".$user."%' ";
			}else if($selectType == 2 && is_numeric($user)) {
				$where = $where ." and c.uid=".$user;
			}
		}

		$page = $param['page']?$param['page']:1;
		$pageNum = $param['pageNum']?$param['pageNum']:20;
		$pageStart = ($page-1)*$pageNum;
		//总记录数
		$this->total = M()->Table("bk_article_comment a")->join("boqii_users c ON a.uid = c.uid")->join("bk_article d ON a.article_id = d.id")->join("bk_cat b ON d.cat_id = b.id")->where($where)->count();
		$listarr =  M()->Table("bk_article_comment a")->field("a.*,b.name,c.nickname,c.uid,d.title")->join("boqii_users c ON a.uid = c.uid")->join("bk_article d ON a.article_id = d.id")->join("bk_cat b ON d.cat_id = b.id")->where($where)->order("a.create_time desc")->limit("$pageStart, $pageNum")->select();

		//当前页条数
	
		$this->subtotal = count($listarr);
		//总页数
		$this->pagecount = ceil(($this->total)/$pageNum);
		$list = array();
		foreach($listarr as $lists){
			$lists["content"] = stripslashes($lists["content"]);
            $lists["content"] = preg_replace('/<img .*?  src=[\"|\'].*?[\"|\'] .*? \/?>/isx','[有图片]',$lists["content"]);
			$lists["create_time"] = date('Y-m-d h:i',$lists["create_time"]);
			$list[] = $lists;
		}
		//print_r($list);
		return $list;
	}
	
	//查询评论信息
	public function getArticleComment($id) {
		$where = "id = ".$id;
		return M()->Table("bk_article_comment")->field('uid,content')->where($where)->find();
	}
	
	//文章评论删除
	public function delAritcleComment($id) {
		$where = "id = ".$id;
		$data['status'] = -1;
		$r = M()->Table("bk_article_comment")->where($where)->save($data);
		if($r) {
			$articleinfo = M()->Table("bk_article_comment")->where($where)->find();
            $articleCommentinfo = M()->Table("bk_article")->where('id='.$articleinfo['article_id'])->find();
			if($articleCommentinfo['comment_num']>0){
                if ($articleinfo['is_check'] == 1){
                    //文章评论数-1
                    M()->Table('bk_article')->where('id='.$articleinfo['article_id'])->setDec('comment_num');
                }
			}
		}
		return $r;
	}

    //文章评论审核
    public function checkComment($id,$type){

        if(!in_array($type,array(0,1)) || !$id){
           return  array('msg'=>'非法参数!');
        }
        if ($type == 1){
            $comment = $this ->where("id = $id")->find();
            if($comment['is_check'] == 1){
                return  array('msg'=>'不能重复审核!');
            }
            $this ->where("id = $id")->setField('is_check',1);
            M()->Table('bk_article')->where('id='.$comment['article_id'])->setInc('comment_num');
            $article = M()->Table('bk_article')->where('id ='.$comment['article_id'])->find();
            import('Common.manual_common',APP_PATH,'.php');
            $dynamic['uid'] = $comment['uid'];
            $dynamic['cretime'] = time();
             if($comment['parent_id'] != 0){
                 //回复
                 $dynamic['type'] = 5;
                 $dynamic['ouid'] = $comment['uid'];
                 $dynamic['operatetype'] = 6;
                 $dynamic['oid'] = $comment['id'];
                 $dynamic['mid'] = $article['id'];
             }else{
                 $dynamic['type'] = 8;
                 $dynamic['ouid'] = $article['authorid'];
                 $dynamic['operatetype'] = 5;
                 $dynamic['oid'] = $comment['id'];
                 $dynamic['mid'] = $comment['article_id'];
             }
            add_dynamic($dynamic);
        }

        return array('status'=>'ok');
    }

}
?>