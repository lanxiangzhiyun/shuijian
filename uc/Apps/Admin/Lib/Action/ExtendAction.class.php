<?php
/*
*后台中心的权限，登陆判断
*/
class ExtendAction extends Action{

	protected $uid;
	protected $username;
	protected $opration;
	/*
	*初始化
	*/   
	public function _initialize(){
		
		//初始化检查用户是否登陆
		$allowACtion = array('login','loginCheck','loginOut','getNotice');

		$ip = my_get_client_ip();
		$session_key = session_id().$ip;
		if(!$_SESSION[$session_key] && !in_array($_GET['_URL_']['1'],$allowACtion)){
			session('boqiiUserId',null);
			session('boqiiUserName',null);
			$this->redirect('/iadmin.php/Index/login');
		}

		$boqiiUserId = session('boqiiUserId');
		if(empty($boqiiUserId)){
			if(!in_array($_GET['_URL_']['1'],$allowACtion)){
				$this->checkLogin();
			}
		}
		//初始化检查用户权限
		$this->checkRbac();
	}

	/*
	*获取用户信息
	*/
	protected function _init(){
		
	}

	/*
	*检查用户是否登陆
	*/
	protected function checkLogin(){
		//获得session
		$boqiiUserId = session('boqiiUserId');
		
		if($boqiiUserId){
			$this->redirect('/iadmin.php/Index/index');
		}else{
			$this->redirect('/iadmin.php/Index/login');
		}
	}

	/*
	*检查用户权限
	*/
	protected function checkRbac(){
		$Useroperation = explode(',',session('boqiiOperation'));
		$this->assign('Useroperation',$Useroperation);
	}

	/*
	*分页方法
	*/
	protected function page($url,$pcount,$limit,$page,$count){
		import('@.ORG.Util.Page');
		$pages = new Page($url,$pcount,$limit,$page,$count);
		return $pages->pageHtml();
	}

	/*
	*操作日志（修改）
	*@model 要实例化的model
	*@column 条件字段
	*@object_id 条件
	*@field 需要搜索的字段 array('name'=>array('title'=>'日志内容','flag'=>1)) name 字段 title 提示 flag 1表示 XXX被修改（主要是针对副文本编辑器） 
	*@newData 表单提交的新数据name名称要和字段一一对应
	*@operation 操作位置(config配置)
	*/
	protected function groupTip($model,$column,$object_id,$field,$newData,$operation){

		$dModel = D($model);
		foreach($field as $key=>$val){
			$fieldStr .= ','.$key;
		}
		$where[$column]=$object_id;
		$fieldStr = substr($fieldStr,1);

		//搜索所需对比字段数据
		$data = $dModel ->where($where)->field($fieldStr)->find();
	
		$isCheck = 0;
		//记录的操作内容
		$strTip='';
		foreach($data as $key=>$val){
			foreach($newData as $k=>$v){
				if($key==$k && $val!=$v){//判断字段是否一致 判断内容是否一致
					
					if($field[$key]['flag']==1){
						$strTip .= $field[$key]['title'].'被修改;';
						$isCheck++;
					}else{
						$strTip .= $field[$key]['title'].'由'.$val.'改为'.$v.';';
						$isCheck++;
					}
				}
			}
		}
		//echo $isCheck;
		
		if($isCheck>0){
			$this->recordOperations(3,$operation,$object_id,'','','','',$strTip);
		}
	}
	
	/*
	*记录到操作日志 ($type 操作类型(1:增 2:删 3:改) 
	$operation 操作位置 
	$object_id 操作对象id 
	$is_setnotice 判断是否发送站内信 
	$to_uid 发送给某个用户 
	$notice_type 站内信类型  
	$column操作字段 
	$beforeContent 修改前内容 
	$afterContent 修改后内容)
	*/
	protected function recordOperations($type,$operation,$object_id,$is_setnotice=null,$to_uid=null,$notice_type=null,$column=null,$beforeContent=null,$afterContent=null){

			switch ($type){
				case 1:
				  $data['operationtext']=  "新增了一条数据,ID编号为".$object_id;
				  break;  
				case 2:
				  $data['operationtext']=  '删除了一条数据,ID编号为'.$object_id;
				  break;
				 case 3:
					if($afterContent){
						$data['operationtext']=  "ID编号为".$object_id."  字段".$column."由".$beforeContent."改为".$afterContent;
					}else{
						$data['operationtext']=  "ID编号为".$object_id."  ".$beforeContent;
					}
				  break;
				 case 4:
				  $data['operationtext']=  "ID编号为".$object_id."  日志内容被修改";
				  break;
				 case 5:
				  $data['operationtext']=  "ID编号为".$object_id."  文章内容被修改";
				  break;
				 case 6:
				  $data['operationtext']=  "ID编号为".$object_id."  分类信息被修改";
				  break;
				 case 7:
				  $data['operationtext']=  "ID编号为".$object_id."  百科成员信息被修改";
				  break;
				default:
				  return '你没进行任何操作';
				  break;
			}
			if($type==4){
				$type=3;
			}
			if($is_setnotice==1){
				//发送站内信
				$this->setNotice($object_id,$to_uid,$notice_type,$type);
			}
			
			$ucOperation = D('UcOperation');
			$ucOperation->create();
			$data['userid']=session('boqiiUserId');
			$data['username']=session('boqiiUserName');
			$data['truename']=session('boqiiTrueName');
			$data['position']=$operation;
			$data['object_id']=$object_id;
			$data['type']=$type; //记录操作类型
			$data['operation_type']=1;
			$data['operationtime']=time();
			$data['status']=0;
			$ucOperation->add($data);
	}
	
	/*
	*图片上传
	*/
	protected function upload(){
		import('@.ORG.Util.UploadFile');
		$upload = new UploadFile();
		$upload->maxSize  = 3145728 ;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$path =  'Data/U/ADS/';

		if(!is_dir($path)){
			$temp = explode('/',$path);
			$cur_dir = '';
			for($i = 0;$i < count($temp);$i++){
				$cur_dir .= $temp[$i].'/';
				if (!is_dir($cur_dir)){
					mkdir($cur_dir,0777);
				}
			}
		}

		$upload->savePath = $path;// 设置附件上传目录
		if(!$upload->upload()) {// 上传错误提示错误信息
			$this->error($upload->getErrorMsg());
		}else{// 上传成功 获取上传文件信息svnuc\Data\U\ADS
			$info =  $upload->getUploadFileInfo();
		}
		
		return $info;
		
	}

	/*
	*站内信	//(需要优化)
	*/
	protected function setNotice($object_id='',$to_uid,$notice_type,$type='',$param=''){
		//$notice_type 100-200是bbs这面站内信
		if($notice_type<100){
			$noticeType = C('NOTICE_DIR');
			$model = D($noticeType[$notice_type]['model']);
			$where[$noticeType[$notice_type]['where']]=$object_id;
			//print_r($noticeType[$notice_type]);
			$data = $model->where($where)->select();
			//判断内容是否为空
			if(strip_tags($data[0][$noticeType[$notice_type]['column']])){
				
				$tip = strip_tags($data[0][$noticeType[$notice_type]['column']]);
			}else{
				$tip = 	'(于'.date('Y-m-d H:i:s',$data[0][$noticeType[$notice_type]['other']]).'发布)';
			}

			if(in_array($notice_type,array('3','4','5','10'))){
				$subContent = '“'.mb_substr($tip,0,20,'utf-8').'”...';
			}else if($notice_type==9){
				$subContent='资料';
			}else{
				$subContent = $tip;
			}
			//个人中心
			if($type==2){
				$content = "您的".$noticeType[$notice_type]['name']." ".$subContent."因包含违规信息，已由管理员删除，给您带来的不便，深表歉意。<br />如有任何问题，可联系论坛管理员。";
			}else if($type==3){
				$content = "您的".$noticeType[$notice_type]['name']." ".$subContent."可能包含不合适的内容，已由管理员更正，给您带来的不便，深表歉意。<br />如有任何问题，可联系论坛管理员。";	
			}else if($type==4){
				$content = "您在".$noticeType[$notice_type]['name']." ".$subContent."中的图片由于内容违规，已被管理员删除，相关联的相册对应的该图片也一并删除，如有问题，请联系论坛管理员";
			}else if($type==5){
				$content = "您在".$noticeType[$notice_type]['name']." ".$subContent."中的图片由于内容违规，已被管理员删除，使用此图的日志中对应图片也一并删除，如有问题，请联系论坛管理员";
			}else if($type==6){
				$content = "您在相册中的".$subContent."图片由于内容违规，已被管理员删除，使用此图的日志中对应图片也一并删除，如有问题，请联系论坛管理员";
			}
			
			//百科
			if($param){
				$content = "您在".$param['oteam_name']."发布的帖子《".$param['title']."》因内容不符，已被管理员移动到".$param['team_name']."。如您未加入".$param['team_name']."，系统将自动为您加入，您也可以选择退出该组，退出该组不影响您已发布的内容。如需退出，进入该小组后点击退出即可。";
			}
		}else if($notice_type>=100 && $notice_type<=200){
			$content = $param['content'];
		}

		$ucNoticeDetail = D('UcNoticeDetail');
		$ucNotices = D('UcNotice');
		$noticeData['content']=$content;
		$noticeData['type']=1;
		$noticeData['dateline']=time();
		$noticeId = $ucNotices->add($noticeData);

		$detailData['sendid']=1328680;
		$detailData['receverid']=$to_uid;
		$detailData['msgid']=$noticeId;
		$detailData['sendstatus']=0;
		$detailData['recevestatus']=0;
		$detailData['readstatus']=0;
		$ucNoticeDetail->add($detailData);
	}
}
?>