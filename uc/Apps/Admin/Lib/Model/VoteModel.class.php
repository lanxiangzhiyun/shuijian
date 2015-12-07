<?php
class VoteModel extends RelationModel {
	protected $trueTableName = 'zt_vote_info';
	
	//列表
	public function getVoteList($param){
		$where = 'status = 0 AND `type` = '.$param['type'];
		//分页
		$page = $param['page']?$param['page']:1;
		$pageNum = $param['pageNum']?$param['pageNum']:20;
		$pageStart = ($page-1)*$pageNum;
		
		$this->total = $this->where($where)->count();
		$voteList = $this->where($where)->order('id DESC')->limit("$pageStart, $pageNum")->select();
		
		//当前页条数
		$this->subtotal = count($voteList);
		//总页数
		$this->pagecount = ceil(($this->total)/$pageNum);
			
		if(!$voteList) {
			return array();
		}
		
		$voteInfo = $this->getVoteInfo();
		foreach($voteList as $key=>$val){
			$voteList[$key]['uid'] = $voteInfo[$val['pid']]['uid'];
			$voteList[$key]['username'] = $voteInfo[$val['pid']]['username'];
			$voteList[$key]['img'] = $voteInfo[$val['pid']]['img'];
			$admin = M()->Table('uc_admin')->where('id ='.$val['update_adminid'])->field('username')->find();
			$voteList[$key]['admin'] = $admin['username'];
		}
		return $voteList;
	}
	
	//投票配置选项
	public function getVoteInfo(){
		$voteInfo = array(
			array('id'=>'1','uid'=>1,'type'=>'1','username'=>'小白头','img'=>C('BLOG_DIR').'/subject/images/hsy/voice/top01.jpg','sound'=>C('BLOG_DIR').'/subject/images/hsy/voice/voice/0.mp3'),
			array('id'=>'2','uid'=>2,'type'=>'1','username'=>'连佳佳','img'=>C('BLOG_DIR').'/subject/images/hsy/voice/top02.jpg','sound'=>C('BLOG_DIR').'/subject/images/hsy/voice/voice/1.mp3'),
			array('id'=>'3','uid'=>3,'type'=>'1','username'=>'晶晶','img'=>C('BLOG_DIR').'/subject/images/hsy/voice/top03.jpg','sound'=>C('BLOG_DIR').'/subject/images/hsy/voice/voice/2.mp3'),
			array('id'=>'4','uid'=>4,'type'=>'1','username'=>'妞妞','img'=>C('BLOG_DIR').'/subject/images/hsy/voice/top04.jpg','sound'=>C('BLOG_DIR').'/subject/images/hsy/voice/voice/3.mp3'),
			array('id'=>'5','uid'=>5,'type'=>'1','username'=>'奇葩唱团','img'=>C('BLOG_DIR').'/subject/images/hsy/voice/top05.jpg','sound'=>C('BLOG_DIR').'/subject/images/hsy/voice/voice/4.mp3'),
			array('id'=>'6','uid'=>6,'type'=>'1','username'=>'麻袋','img'=>C('BLOG_DIR').'/subject/images/hsy/voice/top06.jpg','sound'=>C('BLOG_DIR').'/subject/images/hsy/voice/voice/5.mp3'),
			array('id'=>'7','uid'=>7,'type'=>'1','username'=>'高兴','img'=>C('BLOG_DIR').'/subject/images/hsy/voice/top07.jpg','sound'=>C('BLOG_DIR').'/subject/images/hsy/voice/voice/6.mp3'),
			array('id'=>'8','uid'=>8,'type'=>'1','username'=>'多多','img'=>C('BLOG_DIR').'/subject/images/hsy/voice/top08.jpg','sound'=>C('BLOG_DIR').'/subject/images/hsy/voice/voice/7.mp3'),
			array('id'=>'9','uid'=>9,'type'=>'1','username'=>'Dollar','img'=>C('BLOG_DIR').'/subject/images/hsy/voice/top09.jpg','sound'=>C('BLOG_DIR').'/subject/images/hsy/voice/voice/8.mp3'),
			array('id'=>'10','uid'=>10,'type'=>'1','username'=>'周大贝','img'=>C('BLOG_DIR').'/subject/images/hsy/voice/top10.jpg','sound'=>C('BLOG_DIR').'/subject/images/hsy/voice/voice/9.mp3'),
			array('id'=>'11','uid'=>11,'type'=>'2','username'=>'小咪','img'=>C('BLOG_DIR').'/subject/images/diy/diy/10.jpg','url'=>'http://bbs.boqii.com/content/viewthread-3102455.html'),
			array('id'=>'12','uid'=>12,'type'=>'2','username'=>'牛奶','img'=>C('BLOG_DIR').'/subject/images/diy/diy/9.jpg','url'=>'http://bbs.boqii.com/content/viewthread-3101236.html'),
			array('id'=>'13','uid'=>13,'type'=>'2','username'=>'双双','img'=>C('BLOG_DIR').'/subject/images/diy/diy/8.jpg','url'=>'http://bbs.boqii.com/content/viewthread-3102079.html'),
			array('id'=>'14','uid'=>14,'type'=>'2','username'=>'拉啡','img'=>C('BLOG_DIR').'/subject/images/diy/diy/7.jpg','url'=>'http://bbs.boqii.com/content/viewthread-3102013.html'),
			array('id'=>'15','uid'=>15,'type'=>'2','username'=>'呆呆','img'=>C('BLOG_DIR').'/subject/images/diy/diy/6.jpg','url'=>'http://bbs.boqii.com/content/viewthread-3099756.html'),
			array('id'=>'16','uid'=>16,'type'=>'2','username'=>'帆帆','img'=>C('BLOG_DIR').'/subject/images/diy/diy/5.jpg','url'=>'http://bbs.boqii.com/content/viewthread-3100047.html'),
			array('id'=>'17','uid'=>17,'type'=>'2','username'=>'胖子','img'=>C('BLOG_DIR').'/subject/images/diy/diy/4.jpg','url'=>'http://bbs.boqii.com/content/viewthread-3099814.html'),
			array('id'=>'18','uid'=>18,'type'=>'2','username'=>'七月','img'=>C('BLOG_DIR').'/subject/images/diy/diy/3.jpg','url'=>'http://bbs.boqii.com/content/viewthread-3100068.html'),
			array('id'=>'19','uid'=>19,'type'=>'2','username'=>'点点巧克力','img'=>C('BLOG_DIR').'/subject/images/diy/diy/2.jpg','url'=>'http://bbs.boqii.com/content/viewthread-3101294.html'),
			array('id'=>'20','uid'=>20,'type'=>'2','username'=>'腾讯','img'=>C('BLOG_DIR').'/subject/images/diy/diy/1.jpg','url'=>'http://bbs.boqii.com/content/viewthread-3102752.html'),
			array('id'=>'21','uid'=>21,'type'=>'3','username'=>'萌萌','img'=>C('BLOG_DIR').'/subject/images/tuhao/images/10.jpg','url'=>'http://bbs.boqii.com/content/viewthread-3110193.html'),
			array('id'=>'22','uid'=>22,'type'=>'3','username'=>'小呜','img'=>C('BLOG_DIR').'/subject/images/tuhao/images/9.jpg','url'=>'http://bbs.boqii.com/content/viewthread-3107990.html'),
			array('id'=>'23','uid'=>23,'type'=>'3','username'=>'兔子','img'=>C('BLOG_DIR').'/subject/images/tuhao/images/8.jpg','url'=>'http://bbs.boqii.com/content/viewthread-3108524.html'),
			array('id'=>'24','uid'=>24,'type'=>'3','username'=>'丝丝','img'=>C('BLOG_DIR').'/subject/images/tuhao/images/7.jpg','url'=>'http://bbs.boqii.com/content/viewthread-3108126.html'),
			array('id'=>'25','uid'=>25,'type'=>'3','username'=>'yika','img'=>C('BLOG_DIR').'/subject/images/tuhao/images/6.jpg','url'=>'http://bbs.boqii.com/content/viewthread-3110006.html'),
			array('id'=>'26','uid'=>26,'type'=>'3','username'=>'Mango','img'=>C('BLOG_DIR').'/subject/images/tuhao/images/5.jpg','url'=>'http://bbs.boqii.com/content/viewthread-3108365.html'),
			array('id'=>'27','uid'=>27,'type'=>'3','username'=>'12','img'=>C('BLOG_DIR').'/subject/images/tuhao/images/4.jpg','url'=>'http://bbs.boqii.com/content/viewthread-3108769.html'),
			array('id'=>'28','uid'=>28,'type'=>'3','username'=>'好太后','img'=>C('BLOG_DIR').'/subject/images/tuhao/images/3.jpg','url'=>'http://bbs.boqii.com/content/viewthread-3109409.html'),
			array('id'=>'29','uid'=>29,'type'=>'3','username'=>'二毛','img'=>C('BLOG_DIR').'/subject/images/tuhao/images/2.jpg','url'=>'http://bbs.boqii.com/content/viewthread-3109098.html'),
			array('id'=>'30','uid'=>30,'type'=>'3','username'=>'四爷','img'=>C('BLOG_DIR').'/subject/images/tuhao/images/1.jpg','url'=>'http://bbs.boqii.com/content/viewthread-3108708.html'),
			array('id'=>'31','uid'=>31,'type'=>'4','username'=>'家长名：蜘蛛侠','img'=>C('BLOG_DIR').'/subject/images/toupiao/images/-8.jpg'),
			array('id'=>'32','uid'=>32,'type'=>'4','username'=>'家长名：小甄','img'=>C('BLOG_DIR').'/subject/images/toupiao/images/-7.jpg'),
			array('id'=>'33','uid'=>33,'type'=>'4','username'=>'家长名：小美','img'=>C('BLOG_DIR').'/subject/images/toupiao/images/-6jpg'),
			array('id'=>'34','uid'=>34,'type'=>'4','username'=>'家长名：美女','img'=>C('BLOG_DIR').'/subject/images/toupiao/images/-5.jpg'),
			array('id'=>'35','uid'=>35,'type'=>'4','username'=>'家长名：宝儿','img'=>C('BLOG_DIR').'/subject/images/toupiao/images/-4.jpg'),
			array('id'=>'36','uid'=>36,'type'=>'4','username'=>'家长名：豆豆','img'=>C('BLOG_DIR').'/subject/images/toupiao/images/-3.jpg'),
			array('id'=>'37','uid'=>37,'type'=>'4','username'=>'家长名：爱爱跳跳','img'=>C('BLOG_DIR').'/subject/images/toupiao/images/-2.jpg'),
			array('id'=>'38','uid'=>38,'type'=>'4','username'=>'家长名：阿狼','img'=>C('BLOG_DIR').'/subject/images/toupiao/images/-1.jpg'),
			array('id'=>'39','uid'=>39,'type'=>'5','username'=>'☆SammioοО','img'=>C('BLOG_DIR').'/subject/images/baobeitaili/images/20.jpg'),
			array('id'=>'40','uid'=>40,'type'=>'5','username'=>'樊樊不烦','img'=>C('BLOG_DIR').'/subject/images/baobeitaili/images/19.jpg'),
			array('id'=>'41','uid'=>41,'type'=>'5','username'=>'keegin','img'=>C('BLOG_DIR').'/subject/images/baobeitaili/images/18.jpg'),
			array('id'=>'42','uid'=>42,'type'=>'5','username'=>'咕噜酱&叮当酱','img'=>C('BLOG_DIR').'/subject/images/baobeitaili/images/17.jpg'),
			array('id'=>'43','uid'=>43,'type'=>'5','username'=>'小小皮球','img'=>C('BLOG_DIR').'/subject/images/baobeitaili/images/16.jpg'),
			array('id'=>'44','uid'=>44,'type'=>'5','username'=>'沧海','img'=>C('BLOG_DIR').'/subject/images/baobeitaili/images/15.jpg'),
			array('id'=>'45','uid'=>45,'type'=>'5','username'=>'麦兜','img'=>C('BLOG_DIR').'/subject/images/baobeitaili/images/14.jpg'),
			array('id'=>'46','uid'=>46,'type'=>'5','username'=>'猫之旭语/ka','img'=>C('BLOG_DIR').'/subject/images/baobeitaili/images/13.jpg'),
			array('id'=>'47','uid'=>47,'type'=>'5','username'=>'我们爱拾壹','img'=>C('BLOG_DIR').'/subject/images/baobeitaili/images/12.jpg'),
			array('id'=>'48','uid'=>48,'type'=>'5','username'=>'遥遥','img'=>C('BLOG_DIR').'/subject/images/baobeitaili/images/11.jpg'),
			array('id'=>'49','uid'=>49,'type'=>'5','username'=>'金毛粑粑','img'=>C('BLOG_DIR').'/subject/images/baobeitaili/images/10.jpg'),
			array('id'=>'50','uid'=>50,'type'=>'5','username'=>'独行刀客','img'=>C('BLOG_DIR').'/subject/images/baobeitaili/images/9.jpg'),
			array('id'=>'51','uid'=>51,'type'=>'5','username'=>'雪宝-圈圈','img'=>C('BLOG_DIR').'/subject/images/baobeitaili/images/8.jpg'),
			array('id'=>'52','uid'=>52,'type'=>'5','username'=>'呼噜哈拉猪','img'=>C('BLOG_DIR').'/subject/images/baobeitaili/images/7.jpg'),
			array('id'=>'53','uid'=>53,'type'=>'5','username'=>'狗大圣','img'=>C('BLOG_DIR').'/subject/images/baobeitaili/images/6.jpg'),
			array('id'=>'54','uid'=>54,'type'=>'5','username'=>'晴天*love*嘟嘟','img'=>C('BLOG_DIR').'/subject/images/baobeitaili/images/5.jpg'),
			array('id'=>'55','uid'=>55,'type'=>'5','username'=>'天空空空的','img'=>C('BLOG_DIR').'/subject/images/baobeitaili/images/4.jpg'),
			array('id'=>'56','uid'=>56,'type'=>'5','username'=>'比猫更懒','img'=>C('BLOG_DIR').'/subject/images/baobeitaili/images/3.jpg'),
			array('id'=>'57','uid'=>57,'type'=>'5','username'=>'瓜瓜爱宝宝','img'=>C('BLOG_DIR').'/subject/images/baobeitaili/images/2.jpg'),
			array('id'=>'58','uid'=>58,'type'=>'5','username'=>'哈球球','img'=>C('BLOG_DIR').'/subject/images/baobeitaili/images/1.jpg'),
			array('id'=>'59','uid'=>59,'type'=>'6','username'=>'卡布粑粑-卡布和豆豆','img'=>C('BLOG_DIR').'/subject/images/qunaer/images/10.jpg'),
			array('id'=>'60','uid'=>60,'type'=>'6','username'=>'高兴爸-高兴','img'=>C('BLOG_DIR').'/subject/images/qunaer/images/9.jpg'),
			array('id'=>'61','uid'=>61,'type'=>'6','username'=>'拾壹麻麻-拾壹','img'=>C('BLOG_DIR').'/subject/images/qunaer/images/8.jpg'),
			array('id'=>'62','uid'=>62,'type'=>'6','username'=>'风的翅膀-喵小黑','img'=>C('BLOG_DIR').'/subject/images/qunaer/images/7.jpg'),
			array('id'=>'63','uid'=>63,'type'=>'6','username'=>'幸福淘小淘-淘淘','img'=>C('BLOG_DIR').'/subject/images/qunaer/images/6.jpg'),
			array('id'=>'64','uid'=>64,'type'=>'6','username'=>'猫叔粑粑-偶是猫哥偶叫屁乐喵','img'=>C('BLOG_DIR').'/subject/images/qunaer/images/5.jpg'),
			array('id'=>'65','uid'=>65,'type'=>'6','username'=>'董吉宇-乌拉','img'=>C('BLOG_DIR').'/subject/images/qunaer/images/4.jpg'),
			array('id'=>'66','uid'=>66,'type'=>'6','username'=>'leo-乖乖','img'=>C('BLOG_DIR').'/subject/images/qunaer/images/3.jpg'),
			array('id'=>'67','uid'=>67,'type'=>'6','username'=>'姚姚-wishbone','img'=>C('BLOG_DIR').'/subject/images/qunaer/images/2.jpg'),
			array('id'=>'68','uid'=>68,'type'=>'6','username'=>'爰朝-咪咪豆','img'=>C('BLOG_DIR').'/subject/images/qunaer/images/1.jpg'),
			array('id'=>'69','uid'=>69,'type'=>'7','username'=>'迷离、眼角的痣ㄣ','img'=>C('BLOG_DIR').'/subject/images/moqidu/images/h_10.jpg'),
			array('id'=>'70','uid'=>70,'type'=>'7','username'=>'淡影疏花','img'=>C('BLOG_DIR').'/subject/images/moqidu/images/h_9.jpg'),
			array('id'=>'71','uid'=>71,'type'=>'7','username'=>'Las-vegas','img'=>C('BLOG_DIR').'/subject/images/moqidu/images/h_8.jpg'),
			array('id'=>'72','uid'=>72,'type'=>'7','username'=>'Visa麻麻','img'=>C('BLOG_DIR').'/subject/images/moqidu/images/h_7.jpg'),
			array('id'=>'73','uid'=>73,'type'=>'7','username'=>'COOKA酷卡','img'=>C('BLOG_DIR').'/subject/images/moqidu/images/h_6.jpg'),
			array('id'=>'74','uid'=>74,'type'=>'7','username'=>'Ｊust Ｉn','img'=>C('BLOG_DIR').'/subject/images/moqidu/images/h_5.jpg'),
			array('id'=>'75','uid'=>75,'type'=>'7','username'=>'九妈迪迪','img'=>C('BLOG_DIR').'/subject/images/moqidu/images/h_4.jpg'),
			array('id'=>'76','uid'=>76,'type'=>'7','username'=>'风的翅膀','img'=>C('BLOG_DIR').'/subject/images/moqidu/images/h_3.jpg'),
			array('id'=>'77','uid'=>77,'type'=>'7','username'=>'廖爰朝','img'=>C('BLOG_DIR').'/subject/images/moqidu/images/h_2.jpg'),
			array('id'=>'78','uid'=>78,'type'=>'7','username'=>'仰小望','img'=>C('BLOG_DIR').'/subject/images/moqidu/images/h_1.jpg'),
			array('id'=>'79','uid'=>79,'type'=>'8','username'=>'爰朝','img'=>C('BLOG_DIR').'/subject/images/moqidu/images/h_20.jpg'),
			array('id'=>'80','uid'=>80,'type'=>'8','username'=>'小郁','img'=>C('BLOG_DIR').'/subject/images/moqidu/images/h_19.jpg'),
			array('id'=>'81','uid'=>81,'type'=>'8','username'=>'夏伈心','img'=>C('BLOG_DIR').'/subject/images/moqidu/images/h_18.jpg'),
			array('id'=>'82','uid'=>82,'type'=>'8','username'=>'太上千年老妖','img'=>C('BLOG_DIR').'/subject/images/moqidu/images/h_17.jpg'),
			array('id'=>'83','uid'=>83,'type'=>'8','username'=>'肆儿-古牧Cookie','img'=>C('BLOG_DIR').'/subject/images/moqidu/images/h_16.jpg'),
			array('id'=>'84','uid'=>84,'type'=>'8','username'=>'时光呆','img'=>C('BLOG_DIR').'/subject/images/moqidu/images/h_15.jpg'),
			array('id'=>'85','uid'=>85,'type'=>'8','username'=>'神仙难救神仙难救','img'=>C('BLOG_DIR').'/subject/images/moqidu/images/h_14.jpg'),
			array('id'=>'86','uid'=>86,'type'=>'8','username'=>'偶是猫哥偶叫屁乐喵','img'=>C('BLOG_DIR').'/subject/images/moqidu/images/h_13.jpg'),
			array('id'=>'87','uid'=>87,'type'=>'8','username'=>'你笑落谁心','img'=>C('BLOG_DIR').'/subject/images/moqidu/images/h_12.jpg'),
			array('id'=>'88','uid'=>88,'type'=>'8','username'=>'萌宠&葡萄西瓜','img'=>C('BLOG_DIR').'/subject/images/moqidu/images/h_11.jpg'),
			array('id'=>'89','uid'=>89,'type'=>'8','username'=>'美豆麻','img'=>C('BLOG_DIR').'/subject/images/moqidu/images/h_10.jpg'),
			array('id'=>'90','uid'=>90,'type'=>'8','username'=>'花色','img'=>C('BLOG_DIR').'/subject/images/moqidu/images/h_9.jpg'),
			array('id'=>'91','uid'=>91,'type'=>'8','username'=>'葫芦妈','img'=>C('BLOG_DIR').'/subject/images/moqidu/images/h_8.jpg'),
			array('id'=>'92','uid'=>92,'type'=>'8','username'=>'咕噜酱&叮当酱','img'=>C('BLOG_DIR').'/subject/images/moqidu/images/h_7.jpg'),
			array('id'=>'93','uid'=>93,'type'=>'8','username'=>'风的翅膀','img'=>C('BLOG_DIR').'/subject/images/moqidu/images/h_6.jpg'),
			array('id'=>'94','uid'=>94,'type'=>'8','username'=>'多多麻麻','img'=>C('BLOG_DIR').'/subject/images/moqidu/images/h_5.jpg'),
			array('id'=>'95','uid'=>95,'type'=>'8','username'=>'奔跑de蜗牛','img'=>C('BLOG_DIR').'/subject/images/moqidu/images/h_4.jpg'),
			array('id'=>'96','uid'=>96,'type'=>'8','username'=>'Visa麻麻','img'=>C('BLOG_DIR').'/subject/images/moqidu/images/h_3.jpg'),
			array('id'=>'97','uid'=>97,'type'=>'8','username'=>'Spy','img'=>C('BLOG_DIR').'/subject/images/moqidu/images/h_2.jpg'),
			array('id'=>'98','uid'=>98,'type'=>'8','username'=>'Corgivivi','img'=>C('BLOG_DIR').'/subject/images/moqidu/images/h_1.jpg'),
			array('id'=>'99','uid'=>99,'type'=>'9','username'=>'阿拉斯加-Bobby','img'=>C('ZHUANTI_DIR').'/www/images/ceyan/Bobby.jpg'),
			array('id'=>'100','uid'=>100,'type'=>'9','username'=>'萨摩-Ocean','img'=>C('ZHUANTI_DIR').'/www/images/ceyan/Ocean.jpg'),
			array('id'=>'101','uid'=>101,'type'=>'9','username'=>'白娘子','img'=>C('ZHUANTI_DIR').'/www/images/ceyan/bailiangzi.jpg'),
			array('id'=>'102','uid'=>102,'type'=>'9','username'=>'比熊-金三胖','img'=>C('ZHUANTI_DIR').'/www/images/ceyan/jinsanpang.jpg'),
			array('id'=>'103','uid'=>103,'type'=>'9','username'=>'高地折耳-宝贝','img'=>C('ZHUANTI_DIR').'/www/images/ceyan/baby.jpg'),
			array('id'=>'104','uid'=>104,'type'=>'9','username'=>'金毛-毛毛','img'=>C('ZHUANTI_DIR').'/www/images/ceyan/maomao.jpg'),
			array('id'=>'105','uid'=>105,'type'=>'9','username'=>'金毛-遥遥','img'=>C('ZHUANTI_DIR').'/www/images/ceyan/yaoyao.jpg'),
			array('id'=>'106','uid'=>106,'type'=>'9','username'=>'阿拉斯加-King','img'=>C('ZHUANTI_DIR').'/www/images/ceyan/King.jpg'),
			array('id'=>'107','uid'=>107,'type'=>'9','username'=>'阿拉斯加-甜甜','img'=>C('ZHUANTI_DIR').'/www/images/ceyan/tiantian.jpg'),
			array('id'=>'108','uid'=>108,'type'=>'9','username'=>'比熊-雄雄','img'=>C('ZHUANTI_DIR').'/www/images/ceyan/xiongxiong.jpg'),
			array('id'=>'109','uid'=>109,'type'=>'9','username'=>'布偶猫-暖暖','img'=>C('ZHUANTI_DIR').'/www/images/ceyan/nuannuan.jpg'),
			array('id'=>'110','uid'=>110,'type'=>'9','username'=>'哈士奇-kimi','img'=>C('ZHUANTI_DIR').'/www/images/ceyan/kimi.jpg'),
			array('id'=>'111','uid'=>111,'type'=>'9','username'=>'金丝熊-小呆','img'=>C('ZHUANTI_DIR').'/www/images/ceyan/xiaodai.jpg'),
			array('id'=>'112','uid'=>112,'type'=>'9','username'=>'柯基-Lucky','img'=>C('ZHUANTI_DIR').'/www/images/ceyan/Lucky.jpg'),
			array('id'=>'113','uid'=>113,'type'=>'9','username'=>'萨摩-Miruku','img'=>C('ZHUANTI_DIR').'/www/images/ceyan/Miruku.jpg'),
			array('id'=>'114','uid'=>114,'type'=>'9','username'=>'萨摩-阿朗','img'=>C('ZHUANTI_DIR').'/www/images/ceyan/alang.jpg'),
			array('id'=>'115','uid'=>115,'type'=>'9','username'=>'泰迪-豆豆','img'=>C('ZHUANTI_DIR').'/www/images/ceyan/doudou.jpg'),
			array('id'=>'116','uid'=>116,'type'=>'9','username'=>'泰迪-小东西','img'=>C('ZHUANTI_DIR').'/www/images/ceyan/xiaodongxi.jpg'),
			array('id'=>'117','uid'=>117,'type'=>'9','username'=>'雪纳瑞-没来','img'=>C('ZHUANTI_DIR').'/www/images/ceyan/meilai.jpg'),
			array('id'=>'118','uid'=>118,'type'=>'9','username'=>'英短-段小咪','img'=>C('ZHUANTI_DIR').'/www/images/ceyan/duanxiaomi.jpg'),
		);
		$newVote = array();
		foreach($voteInfo as $k=>$v){
			$newVote[$v['id']]['type'] = $v['type'];
			$newVote[$v['id']]['uid'] = $v['uid'];
			$newVote[$v['id']]['username'] = $v['username'];
		}
		return $newVote;
	}
	
	//编辑
	public function editVote($param){
		$where = "id = ".$param['id'];
		$data['vote_num'] = $param['vote_num'];
		$data['update_time'] = time();
		$data['update_adminid'] = session('boqiiUserId');
		
		$r = M()->Table('zt_vote_info')->where($where)->save($data);
		if($r){
			return true;
		}else{
			return false;
		}
	}
	
	//获取详情
	public function getVoteDetail($id){
		$where = "id = ".$id;
		$voteDetail = M()->Table('zt_vote_info')->where($where)->find();
		$voteInfo = $this->getVoteInfo();
		$voteDetail['uid'] = $voteInfo[$voteDetail['pid']]['uid'];
		$voteDetail['username'] = $voteInfo[$voteDetail['pid']]['username'];
		return $voteDetail;
	}
	
	//批量录入
	public function addVoteInfo(){
		$voteInfo = $this->getVoteInfo();
		foreach($voteInfo as $key=>$val){
			if($val['type'] == 9){
				$data['pid'] = $key;
				$data['create_time'] = time();
				$data['status'] = 0;
				$data['type'] = 9;
				$this->add($data);
			}
		}
		return true;
	}

	/**
 	 * 获得品牌投票列表
 	 * @param array 
 	 * 			$page int 当前页
 	 *			$pageNum int 每页数量
	 */
	public function getBrandVoteList($param){
		$where = 'type = 7 or type = 8';
		//分页
		$page = $param['page']?$param['page']:1;
		$pageNum = $param['pageNum']?$param['pageNum']:20;
		$pageStart = ($page-1)*$pageNum;
		
		$this->brandtotal = M()->Table('boqii_subjects')->where($where)->count();
		$brandVoteList = M()->Table('boqii_subjects')->where($where)->order('joiners DESC')->limit("$pageStart, $pageNum")->select();
		// echo M()->getLastSql();
		//当前页条数
		$this->brandsubtotal = count($brandVoteList);
		//总页数
		$this->brandpagecount = ceil(($this->brandtotal)/$pageNum);
			
		if(!$brandVoteList) {
			return array();
		}
		foreach ($brandVoteList as $key => $val) {
			$brandVoteList[$key]['postdate'] = date('Y-m-d H:i:s',$val['postdate']);
			if ($val['type'] == 8) {
				$brandVoteList[$key]['img_url'] = C('BLOG_DIR').'/subject/images/315hx/img/'.$val['attachurl'];	
				$brandVoteList[$key]['typeName'] = '信赖宠物品牌';
			}else if($val['type'] == 7){
				$brandVoteList[$key]['img_url'] = C('BLOG_DIR').'/subject/images/315hx/logo/'.$val['attachurl'];
				$brandVoteList[$key]['typeName'] = '新锐宠物品牌';	
			}
			
		}
		// echo "<pre>";print_r($brandVoteList);
		return $brandVoteList;
	}

	/**
 	 * 通过id过的品牌投票详情信息
 	 * @param $sid int 品牌投票
	 */
	public function getBrandVoteDetail($sid){
		$where = "sid = ".$sid;
		$brandVoteDetail = M()->Table('boqii_subjects')->where($where)->find();
		if ($brandVoteDetail['type'] == 8) {
			$brandVoteDetail['img_url'] = C('BLOG_DIR').'/subject/images/315hx/img/'.$brandVoteDetail['attachurl'];	
			
		}else if($brandVoteDetail['type'] == 7){
			$brandVoteDetail['img_url'] = C('BLOG_DIR').'/subject/images/315hx/logo/'.$brandVoteDetail['attachurl'];
			
		}
		return $brandVoteDetail;
	}

	/**
 	 * 修改品牌投票信息
 	 * @param array
 	 *			$sid int 品牌投票sid
 	 *			$joiner int 品牌投票数量
	 */
	public function saveBrandVoteInfo($param){
		
		$where = 'sid = '.$param['sid'];
		$res = M()->Table('boqii_subjects')->where($where)->save(array('joiners'=>$param['joiners']));
		return $res;
	}
}
?>