<?php
/**
 * 百科Model类
 *
 * @created 2013-01-16
 * @author yumie
 */
class UcBaikeModel extends Model {
	// 数据库表
	protected $trueTableName = 'bk_thread'; 
	
	/**
	 * 获取收藏词条数据
	 *
	 * @param $param array 参数数组
	 *						uid int 用户id
	 *						oruid int 他人id
	 *						page int 当前页码
	 *						pageNum int 页显数量
	 *
	 * @return array 词条列表数组
	 */
	public function getCollectionEntryList($param) {
		// 收藏人
		if($param['oruid']){
			$uid = $param['oruid'];
		} else{
			$uid = $param['uid'];
		}
		// 收藏词条id
		$entryIds = M()->Table('bk_recommend')->where('object_type=8 AND uid=' . $uid)->order('id DESC')->getField('object_id', true);
		if(!$entryIds) {
			return array();
		}
		// 收藏词条
		$entryList = M()->Table('bk_pet_detail')->where('id IN ('. implode(',', $entryIds) . ') AND status>=0')->field('name,id')->select();

		return $entryList;
	}

	/**
	 * 获取收藏问答数据
	 *
	 * @param $param array 参数数组
	 *						uid int 用户id
	 *						oruid int 他人id
	 *						page int 当前页码
	 *						pageNum int 页显数量
	 *
	 * @return array 收藏回答列表数组
	 */
	public function getCollectionAskList($param) {
		// 收藏人
		if($param['oruid']){
			$uid = $param['oruid'];
		} else{
			$uid = $param['uid'];
		}
		// 收藏问答id
		$askIds = M()->Table('bk_recommend')->where('object_type=6 AND uid=' . $uid)->order('id DESC')->getField('object_id', true);
		if(!$askIds) {
			return array();
		}
		// 分页
		$page = $param['page']?$param['page']:1;
		$pageNum = $param['page_num']?$param['page_num']:20;

		// 收藏问答
		$askList = M()->Table('bk_thread')->where('id IN ('. implode(',', $askIds) . ') AND status>=0')->order('id desc')->page($page)->limit($pageNum)->field('id,title,create_time')->select();
		// 收藏数
		$this->total = M()->Table('bk_thread')->where('id IN ('. implode(',', $askIds) . ') AND status>=0')->count();

		return $askList;
	}

	/**
	 * 获取关注问答列表
	 *
	 * @param $param array 参数数组
	 *						uid int 用户id
	 *						oruid int 他人id
	 *						page int 当前页码
	 *						pageNum int 页显数量
	 *
	 * @return array 关注问答列表数组
	 */
	public function getAttentionAskList($param) {
		// 关注人
		if($param['oruid']){
			$uid = $param['oruid'];
		} else{
			$uid = $param['uid'];
		}
		// 关注问答id
		$askIds = M()->Table('bk_recommend')->where('object_type=7 AND uid=' . $uid)->order('id DESC')->getField('object_id', true);
		if(!$askIds) {
			return array();
		}
		// 分页
		$page = $param['page']?$param['page']:1;
		$pageNum = $param['page_num']?$param['page_num']:20;

		// 关注问答
		$askList = M()->Table('bk_thread')->where('id IN ('. implode(',', $askIds) . ') AND status>=0')->order('id desc')->page($page)->limit($pageNum)->field('id,title,create_time')->select();
		// 关注数
		$this->total = M()->Table('bk_thread')->where('id IN ('. implode(',', $askIds) . ') AND status>=0')->count();

		return $askList;

	}

	/**
	 * 获取关注问答列表
	 *
	 * @param $param array 参数数组
	 *						uid int 用户id
	 *						oruid int 他人id
	 *
	 * @return array 关注问答列表数组
	 */
	public function getAttentionTagList($param) {
		
		// 关注人
		if($param['oruid']){
			$uid = $param['oruid'];
		} else{
			$uid = $param['uid'];
		}
		// 关注标签id
		$tagIds = M()->Table('bk_recommend')->where('object_type=10 AND uid=' . $uid)->order('id DESC')->getField('object_id', true);
		if(!$tagIds) {
			return array();
		}
		// 关注标签
		$tagList = M()->Table('uc_tag')->where('id IN ('. implode(',', $tagIds) . ') AND status>=0')->order('id desc')->field('id,name')->select();
		foreach ($tagList as $key => $val) {
			$tagList[$key]['url'] = C('BLOG_DIR').'/tag/'.$val['id'].'/';
		}
		// print_r($tagList);
		return $tagList;
	}

	/**
	 * 获取提问列表
	 *
	 * @param $param array 参数数组
	 *						uid int 用户id
	 *						oruid int 他人id
	 *						page int 当前页码
	 *						pageNum int 页显数量
	 *
	 * @return array 提问列表数组
	 */
	public function getAskList($param) {
		// 关注人
		if($param['oruid']){
			$uid = $param['oruid'];
		} else{
			$uid = $param['uid'];
		}
		// 分页信息
		$page = $param['page']?$param['page']:1;
		$pageNum = $param['page_num']?$param['page_num']:20;

		// 查询条件
		$where = 't.uid ='.$uid.' and t.status = 0';

		// 总条数
		$this->total = M()->Table('bk_thread t')->where($where)->count();
		// 提问列表数据
		$askList = M()->Table('bk_thread t')->join('bk_cat c ON t.cat_id = c.id')->where($where)->field('t.id,t.title,t.create_time,t.lastpost_uid,t.lastpost_nickname,t.view_num,t.comment_num,t.lastpost_time,t.expert_id,c.id as cid,c.name,c.code,c.parent_id')->order('t.id DESC')->page($page)->limit($pageNum)->select();
		// 提问数
		$this->total = M()->Table('bk_thread t')->join('bk_cat c ON t.cat_id = c.id')->where($where)->count(); 

		// Api Model
		$apiModel = D('Api');
		foreach($askList as $key=>$val){
			// 最后回复人信息
			$info = $apiModel->getUserInfo($val['lastpost_uid']);
			$askList[$key]['lastpost_nickname'] = $info['nickname'];
			$askList[$key]['lastpost_userlink'] = $info['url_link'];
			$askList[$key]['first_id'] = M()->Table('bk_cat')->where('status=0 and id='.$val['parent_id'])->getField('parent_id');
		}
// echo M()->getLastSql();echo '<pre>';print_r($askList);exit;
		return $askList;
	}

	/**
	 * 获取回答列表
	 *
	 * @param $param array 参数数组
	 *						uid int 用户id
	 *						oruid int 他人id
	 *						page int 当前页码
	 *						pageNum int 页显数量
	 *
	 * @return array 回答列表数组
	 */
	public function getReplyList($param) {
		// 关注人
		if($param['oruid']){
			$uid = $param['oruid'];
		} else{
			$uid = $param['uid'];
		}
		$where = 'uid ='.$uid.' AND status >= 0 AND is_check=1';
		// 去重帖子id
		$threadIds = M()->Table('bk_thread_comment')->where($where)->order('id DESC')->distinct(true)->getField('thread_id', true);

		if(!$threadIds) {
			return array();
		}
		$tids = implode(',',$threadIds);

		// 分页信息
		$page = $param['page']?$param['page']:1;
		$pageNum = $param['page_num']?$param['page_num']:20;

			$where = 't.id in ('.$tids.') AND c.status>=0 AND t.status>=0 AND  t.is_check=1 AND c.is_check=1';
			// 已回答
			//$where .= ' AND t.question_status=1';
			// 回答人
			$where .= ' AND c.uid='.$uid;
			// 问答
			$askList = M()->Table('bk_thread_comment c')->join('bk_thread t ON c.thread_id=t.id')->where($where)->order('c.id desc')->page($page)->limit($pageNum)->field('c.id,c.thread_id,t.title,c.content as reply_content,t.comment_num,t.lastpost_time,t.lastpost_uid,c.create_time,t.content,t.cat_id')->select();
			// 回答数
			$this->total = M()->Table('bk_thread_comment c')->join('bk_thread t ON c.thread_id=t.id')->where($where)->count();
			foreach ($askList as $k => $val) {
				$askList[$k]['cat'] = M()->Table('bk_cat')->field('id,name,code,parent_id')->where('status = 0 and id='.$val['cat_id'])->find(); 
				$askList[$k]['cat']['first_id'] = M()->Table('bk_cat')->where('status=0 and id='.$askList[$k]['cat']['parent_id'])->getField('parent_id');
			}
// echo M()->getLastSql();echo '<pre>';print_r($askList);exit;
		return $askList;
	}
}
?>