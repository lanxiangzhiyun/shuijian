<?php
/**
 * 宠物管理
 */
class PetAction extends ExtendAction{
	
	/*
	*宠物列表页
	*/
	public function index(){
		$limit=10;
		$page = $this->_get('page');
		if($page=='' || !is_numeric($page)){
			$page=1;
		}

		$where="pet.uid=user.uid and pet.valid=1";

		$url='/iadmin.php/Pet/index?';
		
		//搜索条件
		$noAllow = C('NO_ALLOW');
		
		if($this->_get('data')){
			$data = $this->_get('data');
			
			if(!in_array($data['petname'],$noAllow) && !empty($data['petname'])){
				$where.=" and pet.petname like '%".$data['petname']."%' ";
				$url.='data[petname]='.urlencode($data['petname']).'&';
				$this->assign('petname',$data['petname']);
			}

			if(trim($data['starttime'])){
				$where.=" and pet.cretime >= ".strtotime($data['starttime'].' 00:00:00');
				$url.='data[starttime]='.$data['starttime'].'&';
				$this->assign('starttime',$data['starttime']);
			}
			if(trim($data['endtime'])){
				$where.=" and pet.cretime <= ".strtotime($data['endtime'].' 23:59:59');
				$url.='data[endtime]='.$data['endtime'].'&';
				$this->assign('endtime',$data['endtime']);
			}
			if($data['petstatus']!=''){
				$where.=" and pet.petstatus=".$data['petstatus'];
				$url.='data[petstatus]='.$data['petstatus'].'&';
				$this->assign('petstatus',$data['petstatus']);
			}
			if($data['is_default']!=''){
				$where.=" and pet.is_default=".$data['is_default'];
				$url.='data[is_default]='.$data['is_default'].'&';
				$this->assign('is_default',$data['is_default']);
			}
			if(trim($data['ispic'])){
				if($data['ispic']==1){
					$where.=" and pet.picpath!='' ";
				}else{
					$where.=" and pet.picpath='' ";
				}
				$url.='data[ispic]='.$data['ispic'].'&';
				$this->assign('ispic',$data['ispic']);
			}
			
			if(!in_array($data['user'],$noAllow) && !empty($data['user'])){
				if($data['select']==1){
					$where.=" and user.nickname like '%".trim($data['user'])."%' ";
				}else if($data['select']==2){
					if(is_numeric($data['user'])){
						$where.=" and user.uid=".trim($data['user']);
					}
				}
				$url.='data[user]='.urlencode($data['user']).'&';
				$url.='data[select]='.$data['select'].'&';

				$this->assign('select',$data['select']);
				$this->assign('user',$data['user']);
			}
		}

		$where .="  and pet.pettype = class.id";
		$petType = C('PET_TYPE');
		$this->assign('petType',$petType);
		$ucUserPet = D('UcUserPet');
		$UserPetCount = $ucUserPet->hasPetCount($where);
		$pcount = ceil($UserPetCount/$limit);
		if($page>=$pcount){
			$page=$pcount;
		}

		$url.='page=';

		$UserPets = $ucUserPet->hasUserAndPet($page,$limit,$where);
		$pageHtml = $this->page($url,$pcount,$limit,$page,count($UserPets));
		$petType = C('PET_TYPE');
		foreach($UserPets as $key=>$val){
			$UserPets[$key]['petTypes'] = $petType[$val['petstatus']];
			$UserPets[$key]['picpath'] = getSmallPicPath($val['picpath']);
		}

		$this->assign('url',$url.$page);
		$this->assign('UserPets',$UserPets);
		$this->assign('pageHtml',$pageHtml);
		$this->assign('page',$page);
		$this->display('index');
	}


	/*
	*逻辑删除宠物
	*/
	public function deletePet(){
		$ids = $this->_get('deletePet');
		$act = $this->_get('act');
		$page = $this->_get('page');
		$isNotice = $this->_get('isNotice');
		$idArr = explode(',',$ids);
		$ucUserPet = D('UcUserPet');
		foreach($idArr as $key=>$val){
			if($val){
				if($isNotice==1){
					$uid = $ucUserPet->where(array('id'=>$val))->select();
					$this->recordOperations(2,7,$val,$isNotice,$uid[0]['uid'],9);
				}else{
					$this->recordOperations(2,7,$val);
				}
				$ucUserPet->where(array('id'=>$val))->save(array('valid'=>0));
			}
		}
		if(empty($act)){
			//$this->redirect('/iadmin.php/Pet/index?page='.$page);
			echo "<script>history.back();</script>";
		}else{
			echo 1;
			exit;
		}
	}

	/*
	*删除宠物头像
	*/
	public function deletePetPic(){

		$isNotice = $this->_get('isNotice');
		$id = $this->_get('deletePetPic');
		$ucUserPet = D('UcUserPet');
		$ucUserPet->where(array('id'=>$id))->save(array('picpath'=>''));
		
		if($isNotice==1){
			$uid = $ucUserPet->where(array('id'=>$id))->select();
			$this->recordOperations(2,7,$id,$isNotice,$uid[0]['uid'],8);
		}else{
			$this->recordOperations(2,7,$id);
		}

		$this->redirect('/iadmin.php/Pet/index');
	}
	
	/*
	*编辑页面
	*/
	public function editPage(){
		$id = $this->_get('id');
		$ucUserPet = D('UcUserPet');
		$Pet = $ucUserPet->where(array('id'=>$id,'valid'=>1))->select();
		$Pet[0]['title'] = $ucUserPet -> getPetName ($Pet[0]['pettype']);
		$date = time();
		if($Pet[0]['petbday']==0){
			$Pet[0]['petbday']=$date;
		}
		if($Pet[0]['adopte_time']==0){
			$Pet[0]['adopte_time']=$date;
		}
		if($Pet[0]['immune_time']==0){
			$Pet[0]['immune_time']=$date;
		}
		if($Pet[0]['repell_time']==0){
			$Pet[0]['repell_time']=$date;
		}
		$SPENDING = C('SPENDING');
		$PET_TYPE = C('PET_TYPE');
		$this->assign('SPENDING',$SPENDING);
		$this->assign('PET_TYPE',$PET_TYPE);
		$this->assign('id',$id);
		$this->assign('Pet',$Pet);
		$this->display('editPage');
	}

	/*
	*提交修改后的内容
	*/
	public function postEdit(){
		if($this->_post('data')){
			$data = $this->_post('data');
			$data['petbday'] = strtotime($data['petbday']);
			$data['adopte_time'] = strtotime($data['adopte_time']);
			$data['repell_time'] = strtotime($data['repell_time']);
			$data['immune_time'] = strtotime($data['immune_time']);
			
			$ucUserPet = D('UcUserPet');
			//需要做比对的字段
			$Pet = $ucUserPet->where(array('id'=>$data['id']))->field('id,petname,petgender,weight,petbday,adopte_time,lineages,petstatus,spending,is_default,is_repellend,repell_time,is_immnued,immune_time,character,foods,toys,specialty,instructions')->select();
			//获取用户id
			$uid = $ucUserPet->where(array('id'=>$data['id']))->field('uid,picpath')->select();
			if($this->_post('is_deletepic')==1){
				$ucUserPet->where(array('id'=>$data['id']))->save(array('picpath'=>''));
				$this->recordOperations(2,7,$data['id'],1,$uid[0]['uid'],8);
			}
			foreach($Pet as $key=>$val){
				foreach($data as $k=>$v){
					if($v!=$val[$k]){
						$arr[$k]['column']=$k;
						$arr[$k]['beforeContent']=$val[$k];
						$arr[$k]['afterContent']=$v;
					}
				}
			}

			foreach($arr as $key=>$val){
					$this->recordOperations(3,7,$data['id'],1,$uid[0]['uid'],8,$val['column'],$val['beforeContent'],$val['afterContent']);
			}

			$data['updatetime'] = time();
			$ucUserPet->save($data);
			$this->redirect('/iadmin.php/Pet/editPage?id='.$data['id']);
		}else{
			$this->redirect('/iadmin.php/Pet/index');
		}
		
		
	}

	
	/**
	 * 宠物列表页(无线App)
	 */
	public function petIndex(){
		// 页显数量
		$limit = 10;
		// 当前页码
		$page = $this->_get('page');
		if($page=='' || !is_numeric($page)){
			$page=1;
		}
		// 查询条件
		$where="pet.uid=user.uid and pet.valid=1";

		$url='/iadmin.php/Pet/petIndex?';
		
		// 搜索条件(搜索屏蔽字段)
		$noAllow = C('NO_ALLOW');
		// URL参数
		if($this->_get('data')){
			$data = $this->_get('data');
			// 宠物名
			if(!in_array($data['petname'],$noAllow) && !empty($data['petname'])){
				$where.=" and pet.petname like '%".$data['petname']."%' ";
				$url.='data[petname]='.urlencode($data['petname']).'&';
				$this->assign('petname',$data['petname']);
			}
			// 添加时间
			if(trim($data['starttime'])){
				$where.=" and pet.cretime >= ".strtotime($data['starttime'].' 00:00:00');
				$url.='data[starttime]='.$data['starttime'].'&';
				$this->assign('starttime',$data['starttime']);
			}
			if(trim($data['endtime'])){
				$where.=" and pet.cretime <= ".strtotime($data['endtime'].' 23:59:59');
				$url.='data[endtime]='.$data['endtime'].'&';
				$this->assign('endtime',$data['endtime']);
			}
			// 是否有头像
			if(trim($data['ispic'])){
				if($data['ispic']==1){
					$where.=" and pet.picpath!='' ";
				}else{
					$where.=" and pet.picpath='' ";
				}
				$url.='data[ispic]='.$data['ispic'].'&';
				$this->assign('ispic',$data['ispic']);
			}
			// 用户搜索
			if(!in_array($data['user'],$noAllow) && !empty($data['user'])){
				if($data['select']==1){
					$where.=" and user.nickname like '%".trim($data['user'])."%' ";
				}else if($data['select']==2){
					if(is_numeric($data['user'])){
						$where.=" and user.uid=".trim($data['user']);
					}
				}
				$url.='data[user]='.urlencode($data['user']).'&';
				$url.='data[select]='.$data['select'].'&';

				$this->assign('select',$data['select']);
				$this->assign('user',$data['user']);
			}
		}

//		// 宠物状态：'0'=>'正常','1'=>'征婚','2'=>'需要被领养','3'=>'已经去世了','4'=>'已送人','5'=>'已走失','6'=>'希望出售/转让'
//		$petType = C('PET_TYPE');
//		$this->assign('petType',$petType);

		$userPetModel = D('BoqiiUserPet');

		// 宠物总数量
		$UserPetCount = $userPetModel->hasPetCount($where);
		$pcount = ceil($UserPetCount/$limit);
		if($page>=$pcount){
			$page=$pcount;
		}

		$url.='page=';

		// 宠物列表
		$UserPets = $userPetModel->hasUserAndPet($page,$limit,$where);
		$pageHtml = $this->page($url,$pcount,$limit,$page,count($UserPets));

		foreach($UserPets as $key=>$val){
			//$UserPets[$key]['petTypes'] = $petType[$val['petstatus']];
			$UserPets[$key]['picpath'] = getSmallPicPath($val['picpath']);
		}

		$this->assign('url',$url.$page);
		$this->assign('UserPets',$UserPets);
		$this->assign('pageHtml',$pageHtml);
		$this->assign('page',$page);

		$this->display('pet_index');
	}


	/**
	 * 逻辑删除宠物
	 */
	public function petDel(){
		// 待删除的宠物id
		$ids = $this->_get('petDel');
		$act = $this->_get('act');
		$page = $this->_get('page');
		// 是否站内信通知
		$isNotice = $this->_get('isNotice');
		$idArr = explode(',',$ids);
		$userPetModel = D('BoqiiUserPet');
		foreach($idArr as $key=>$val){
			if($val){
				if($isNotice==1){
					$uid = $userPetModel->where(array('id'=>$val))->select();
					$this->recordOperations(2,7,$val,$isNotice,$uid[0]['uid'],9);
				}else{
					$this->recordOperations(2,7,$val);
				}
				$userPetModel->where(array('id'=>$val))->save(array('valid'=>0));
			}
		}
		if(empty($act)){
			//$this->redirect('/iadmin.php/Pet/index?page='.$page);
			echo "<script>history.back();</script>";
		}else{
			echo 1;
			exit;
		}
	}

	/**
	 * 删除宠物头像
	 */
	public function petpicDel(){
		// 是否站内信通知
		$isNotice = $this->_get('isNotice');
		// 需要删除头像的宠物id
		$id = $this->_get('deletePetPic');
		// 清空宠物头像
		$userPetModel = D('BoqiiUserPet');
		$userPetModel->where(array('id'=>$id))->save(array('picpath'=>''));

		// 站内信通知&操作记录
		if($isNotice==1){
			$uid = $userPetModel->where(array('id'=>$id))->select();
			$this->recordOperations(2,7,$id,$isNotice,$uid[0]['uid'],8);
		}else{
			$this->recordOperations(2,7,$id);
		}

		$this->redirect('/iadmin.php/Pet/petIndex');
	}
	
	/**
	 * 编辑页面
	 */
	public function petEdit(){
		// 宠物头像id
		$id = $this->_get('id');
		// 宠物信息
		$userPetModel = D('BoqiiUserPet');
		$pet = $userPetModel->where(array('id'=>$id,'valid'=>1))->find();
		// 宠物种族
		$pet['raceName'] = $userPetModel -> getPetRaceName ($pet['race']);
		// 宠物家族
		$pet['familyName'] = $userPetModel -> getPetFamilyName ($pet['family']);

		$this->assign('id',$id);
		$this->assign('pet',$pet);

		$this->display('pet_info');
	}

	/**
	 * 提交修改后的内容
	 */
	public function petSave(){
		if($this->_post('data')){
			$data = $this->_post('data');
			// 宠物出生日
			if($data['petbday']) {
				$data['petbday'] = strtotime($data['petbday']);
			}
			
			$userPetModel = D('BoqiiUserPet');
			// 需要做比对的字段
			$Pet = $userPetModel->where(array('id'=>$data['id']))->field('id,petname,petgender,petbday,lineages')->select();
			// 获取用户id
			$uid = $userPetModel->where(array('id'=>$data['id']))->field('uid,picpath')->select();
			if($this->_post('is_deletepic')==1){
				$userPetModel->where(array('id'=>$data['id']))->save(array('picpath'=>''));
				$this->recordOperations(2,7,$data['id'],1,$uid[0]['uid'],8);
			}
			foreach($Pet as $key=>$val){
				foreach($data as $k=>$v){
					if($v!=$val[$k]){
						$arr[$k]['column']=$k;
						$arr[$k]['beforeContent']=$val[$k];
						$arr[$k]['afterContent']=$v;
					}
				}
			}

			foreach($arr as $key=>$val){
					$this->recordOperations(3,7,$data['id'],1,$uid[0]['uid'],8,$val['column'],$val['beforeContent'],$val['afterContent']);
			}

			$data['updatetime'] = time();
			$userPetModel->save($data);
			$this->redirect('/iadmin.php/Pet/petEdit?id='.$data['id']);
		}else{
			$this->redirect('/iadmin.php/Pet/petIndex');
		}
		
		
	}
}
?>