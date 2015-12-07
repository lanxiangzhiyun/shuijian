<?php

/**
 * 推送Model类
 */
class PushModel extends Model {
	protected $trueTableName = 'bbs_pushes';
	
	/**
	 * 根据id取得推送内容 
	 *
	 * @param $bid int 推送记录id 
	 * @return array 推送内容数组
	 */
	public function getPushById($bid) {
		$where = " bid=".$bid;
		
		return $this->where($where)->field("bid,type,tid,subject,content,linkurl,attachurl,postdate,valid")->find();
	}
	
	/**
	 * 根据条件取得推送内容
	 *
	 * @param $param array 推送参数数组
	 * @return array 推送内容列表数组
	 */
	public function getPushlist($param) {
		//WHERE条件
		$where = " 1 AND valid = 1 ";
	
		if(!empty($param['type'])) {
			$where .= " AND type = " . $param['type'];
		}
		if(!empty($param['subject'])) {
			$where .= " AND subject like '%".$param['subject']."%'";
		}
		
		//SORT排序
		$sort = "postdate DESC";
		
		$pushModel = M($this->trueTableName);
		$page = $param['page']?$param['page']:1;
		$page_num = $param['page_num']?$param['page_num']:20;
		$page_start = ($page-1)*$page_num;
		
		$this->total = $pushModel->where($where)->count();
		$pushlist =  $pushModel->field('bid,type,tid,subject,content,linkurl,attachurl,postdate,valid')->where($where)->order($sort)->limit("$page_start, $page_num")->select();
		
		foreach($pushlist as $pk => $push){		
			//操作日期
			$pushlist[$pk]['postdate'] = date("Y-m-d H:i",$push['postdate']);
			//版块名称
			$pushlist[$pk]['typename'] = $this->getPushType($push['type']);

		}
		return $pushlist;
	}
	
	/**
	 * 保存推送内容
	 *
	 * @param $push array 推送内容数组
	 * @return array 处理结果
	 */
	public function savePush($push) {
		$pushModel = M($this->trueTableName);
		//添加推送内容
		if($push['act'] == 'add') {
			$data['type'] = $push['type'];
			$data['tid'] = intval($push['tid']);
			$data['subject'] = $push['subject'];
			$data['content'] = $push['content'];
			$data['linkurl'] = $push['linkurl'];
			if(!empty($push['attachurl'])) { 
				$data['attachurl'] = $push['attachurl'];
			}
			$data['postdate'] = time();
			$data['valid'] = 1;
			return $pushModel->add($data);
		}
		//编辑推送内容
		else {
			$data['type'] = $push['type'];
			$data['tid'] = intval($push['tid']);
			$data['subject'] = $push['subject'];
			$data['content'] = $push['content'];
			$data['linkurl'] = $push['linkurl'];
			if(!empty($push['attachurl'])) { 
				$data['attachurl'] = $push['attachurl'];
			}
			$data['postdate'] = time();
			$data['valid'] = 1;
			return $pushModel->where('bid ='.$push['bid'])->save($data);
		}
	}
	
	/**
	 * 删除推送内容（逻辑删除）
	 *
	 * @param $bid int 推送id
	 * @return array 处理结果
	 */
	public function deletePush($bid) {
		$pushModel = M($this->trueTableName);
		$data['valid'] = 0;
		return $pushModel->where('bid ='.$bid)->save($data);
	}
	
	/**
	 * 取得推送版块列表
	 *
	 * @return array 推送版块列表
	 */
	public function getPushTypeList() {
		$typeList =  array( 1 => "焦点图", 
										2 => "社区精华（标题+摘要）", 
										3 => "社区精华（标题）", 
										4 => "精彩图文（标题+摘要）", 
										5 => "精彩图文（图片）", 
										6 => "宠友俱乐部（图片轮换）", 
										7 => "宠友俱乐部（标题+摘要）",  
										9 => "宠友俱乐部（活动广告）",
										10 => "各地分会（图片轮换）", 
										11 => "各地分会（标题+摘要）",  
										13 => "各地分会（活动广告）", 
										14 => "八卦娱乐（图片轮换）", 
										15 => "八卦娱乐（标题+摘要）",  
										17 => "八卦娱乐（活动广告）", 
										20 => "首页右三广告", 
										21 => "美图秀秀焦点图", 
										22 =>"美图秀秀图片列表",
										23 => "美图秀秀内容页右美图推送",
										24 => "论坛明星",
										25 => "狗狗论坛推荐",
										26 => "各地论坛推荐",
										27=>"哈士奇/阿拉斯加版头明星",
										28=>"萨摩版头明星",
										29=>"贵宾犬版头明星",
										30=>"比熊版头明星",
										31=>"金毛/拉布拉多版头明星",
										32=>"苏牧版头明星",
										33=>"雪纳瑞版头明星",
										34=>"西施/京巴版头明星",
										35=>"混血儿版头明星",
										36=>"其他犬种版头明星",
										37=>"猫咪论坛版头明星",
										38=>"小宠水族论坛版头明星",
										39=>"上海论坛版头明星",
										40=>"论坛版头明显背景图"
		);	
		
		return $typeList;
	}
	
	/**
	 * 取得推送版块名称 
	 *
	 * @param $type int 推送版块
	 * @return string 推送版块名称
	 */
	function getPushType($type) {
		$typelist = $this->getPushTypeList();
		
		return $typelist[$type];		
	}
}
?>