<?php
/**
 * BbsThread Model类
 */
class BbsThreadModel extends RelationModel{
	
	protected $tableName='bbs_threads';
	
	public $_link = array(           
			'BbsPost'=>array(
				 'mapping_type'=>HAS_ONE,                   
				 'class_name'=>'BbsPost',
				 'mapping_name'=>'bbs_posts', 
				 'foreign_key'=>'tid'
			)
	);

	
	public function hotThreads($tid){
		$result = $this->where('bbs_threads.tid='.$tid)->relation(true)->select();
		return $result;
	}
	
	//获取图片贴xml数据
	public function getPicThreadList($tidArray){
		if(is_array($tidArray)) {
			$tidStr = implode(',',$tidArray);
		}
		$where = 't.tid in ('.$tidStr.')';
		$thread = M()->Table("bbs_threads t")->where($where)->field("t.tid,t.fid,t.subject,t.views,t.dateline,t.otid")->select();
		$newArray = array();
		foreach($thread as $k=>$v){
			//tid
			$newArray[$k]["tid"] = $v['tid'];
			//url
			$newArray[$k]["url"] = C('BBS_DIR')."/content/picviewthread-".$v['tid'].".html";
			//标题
			$newArray[$k]["subject"] = $v["subject"];
			//发布时间
			$newArray[$k]["dateline"] = date("YmdHm",$v['dateline']);
			//查看数
			if($v['views'] >=500){
				$newArray[$k]["views"] = 100;
			}elseif($v['views'] >400 && $v['views'] <=500){
				$newArray[$k]["views"] = 90;
			}elseif($v['views'] >300 && $v['views'] <=400){
				$newArray[$k]["views"] = 80;
			}elseif($v['views'] >=200 && $v['views'] <=300){
				$newArray[$k]["views"] = 70;
			}elseif($v['views'] >=100 && $v['views'] <200){
				$newArray[$k]["views"] = 60;
			}elseif($v['views'] >=0 && $v['views'] <100){
				$newArray[$k]["views"] = 50;
			}
			//标签
			$tags = $this->query("SELECT pt.tag_id, tt.tag_name FROM bbs_pictags pt LEFT JOIN bbs_thread_tags tt ON pt.tag_id=tt.tag_id WHERE tid=".$v['tid']." ORDER BY id");
			$tagArray = array();
			foreach($tags as $tk=>$tv){
				$tagArray[] = $tv['tag_name'];
			}
			if($tagArray){
				$newArray[$k]['tag'] = implode('$$',$tagArray);
			}else{
				$newArray[$k]['tag'] = "";
			}
			
			//图片帖
			$picsList = M()->Table("bbs_picthreads")->where("tid=".$v['tid']." AND is_cover=0 AND status=0")->field("pic_path")->order("is_cover desc,id")->select();
			$newArray[$k]['picList'] = $picsList;
			
		}
		return $newArray;
	}
}
?>