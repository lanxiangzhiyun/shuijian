<?php
class WishModel extends RelationModel {
	protected $trueTableName = 'zt_wish_info';
	
	//列表
	public function getWishList($param){
		$where = 'status = 0';
		//分页
		$page = $param['page']?$param['page']:1;
		$pageNum = $param['pageNum']?$param['pageNum']:20;
		$pageStart = ($page-1)*$pageNum;
		
		$this->total = $this->where($where)->count();
		$wishList = $this->where($where)->order('id DESC')->limit("$pageStart, $pageNum")->select();
		
		//当前页条数
		$this->subtotal = count($wishList);
		//总页数
		$this->pagecount = ceil(($this->total)/$pageNum);
			
		if(!$wishList) {
			return array();
		}
		
		$wishInfo = $this->getCityInfo();
		foreach($wishList as $key=>$val){
			$wishList[$key]['city'] = $wishInfo[$val['cid']]['city_name'];
			$wishList[$key]['organization'] = $wishInfo[$val['cid']]['organization'];
			$admin = M()->Table('uc_admin')->where('id ='.$val['update_adminid'])->field('username')->find();
			$wishList[$key]['admin'] = $admin['username'];
		}
		return $wishList;
	}
	
	//投票配置选项
	public function getCityInfo(){
		$info = array(
			array('id'=>'1','cid'=>1,'city_name'=>'黑龙江','organization'=>'鹤岗流浪动物救助站'),
			array('id'=>'2','cid'=>2,'city_name'=>'吉林','organization'=>''),
			array('id'=>'3','cid'=>3,'city_name'=>'辽宁','organization'=>'大连市微善爱护动物协会'),
			array('id'=>'4','cid'=>4,'city_name'=>'北京','organization'=>'伴侣动物公益联盟'),
			array('id'=>'5','cid'=>5,'city_name'=>'天津','organization'=>'天津领养日'),
			array('id'=>'6','cid'=>6,'city_name'=>'新疆','organization'=>''),
			array('id'=>'7','cid'=>7,'city_name'=>'甘肃','organization'=>'兰州小动物保护协会'),
			array('id'=>'8','cid'=>8,'city_name'=>'内蒙古','organization'=>''),
			array('id'=>'9','cid'=>9,'city_name'=>'西藏','organization'=>''),
			array('id'=>'10','cid'=>10,'city_name'=>'青海','organization'=>'西宁流浪动物救助'),
			array('id'=>'11','cid'=>11,'city_name'=>'宁夏','organization'=>'银川守护者动物之家'),
			array('id'=>'12','cid'=>12,'city_name'=>'陕西','organization'=>'西安领养'),
			array('id'=>'13','cid'=>13,'city_name'=>'山西','organization'=>''),
			array('id'=>'14','cid'=>14,'city_name'=>'河北','organization'=>'唐山市小动物救助中心'),
			array('id'=>'15','cid'=>15,'city_name'=>'山东','organization'=>''),
			array('id'=>'16','cid'=>16,'city_name'=>'河南','organization'=>'郑州宠协流浪动物救助站'),
			array('id'=>'17','cid'=>17,'city_name'=>'湖北','organization'=>'武汉同心流浪宠物义工团'),
			array('id'=>'18','cid'=>18,'city_name'=>'重庆','organization'=>'重庆小动物保护协会'),
			array('id'=>'19','cid'=>19,'city_name'=>'四川','organization'=>''),
			array('id'=>'20','cid'=>20,'city_name'=>'云南','organization'=>''),
			array('id'=>'21','cid'=>21,'city_name'=>'贵州','organization'=>''),
			array('id'=>'22','cid'=>22,'city_name'=>'湖南','organization'=>'长沙市小动物保护协会'),
			array('id'=>'23','cid'=>23,'city_name'=>'江西','organization'=>'南昌小动物协会'),
			array('id'=>'24','cid'=>24,'city_name'=>'安徽','organization'=>'合肥小动物关怀中心'),
			array('id'=>'25','cid'=>25,'city_name'=>'江苏','organization'=>''),
			array('id'=>'26','cid'=>26,'city_name'=>'上海','organization'=>'吴阿姨流浪猫狗中心'),
			array('id'=>'27','cid'=>27,'city_name'=>'浙江','organization'=>'杭州流浪动物救助基地'),
			array('id'=>'28','cid'=>28,'city_name'=>'福建','organization'=>'福州小动物保护中心'),
			array('id'=>'29','cid'=>29,'city_name'=>'广东','organization'=>'熙熙森林广州猫'),
			array('id'=>'30','cid'=>30,'city_name'=>'广西','organization'=>''),
			array('id'=>'31','cid'=>31,'city_name'=>'海南','organization'=>'海南省小动物保护协会'),
			array('id'=>'32','cid'=>32,'city_name'=>'香港','organization'=>''),
			array('id'=>'33','cid'=>33,'city_name'=>'台湾','organization'=>''),
			array('id'=>'34','cid'=>34,'city_name'=>'澳门','organization'=>'猫神传奇领养中心'),
		);
		$infos = array();
		foreach($info as $k=>$v){
			$infos[$v['id']]['cid'] = $v['cid'];
			$infos[$v['id']]['city_name'] = $v['city_name'];
			$infos[$v['id']]['organization'] = $v['organization'];
		}
		return $infos;
	}
	
	//编辑
	public function editInfo($param){
		$where = "id = ".$param['id'];
		$data['wish_num'] = $param['wish_num'];
		$data['update_time'] = time();
		$data['update_adminid'] = session('boqiiUserId');
		
		$r = M()->Table('zt_wish_info')->where($where)->save($data);
		if($r){
			return true;
		}else{
			return false;
		}
	}
	
	//获取详情
	public function getWishDetail($id){
		$where = "id = ".$id;
		$detail = M()->Table('zt_wish_info')->where($where)->find();
		$info = $this->getCityInfo();
		$detail['city'] = $info[$detail['cid']]['city_name'];
		return $detail;
	}
	
	//批量录入
	public function addInfo(){
		$info = $this->getCityInfo();
		foreach($info as $key=>$val){
			$data['cid'] = $key;
			$data['create_time'] = time();
			$data['status'] = 0;
			$this->add($data);
		}
		return true;
	}
}
?>