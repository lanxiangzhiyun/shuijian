<?php
/**
 * 广告管理Action类
 */
class AdAction extends ExtendAction{
	/**
	 * 广告列表
	 */
	public function index(){
		//当前页
		$page = $this->_get('page');
		if($page=='' || !is_numeric($page)){
			$page=1;
		}
		//非搜索字段值
		$noAllow = C('NO_ALLOW');
		//当前页链接
		$url='/iadmin.php/Ad/index?';
		//搜索条件
		$where="ad.uid=admin.id and ad.status=0 and ad.module_type!=2";
		//按创建时间倒序
		$order = "id desc";
		//搜索参数
		if($this->_get('data')){
			$data = $this->_get('data');
			//广告标题
			if(!in_array($data['title'],$noAllow) && !empty($data['title'])){
				$where.=" and ad.title like '%".$data['title']."%' ";
				$url.='data[title]='.urlencode($data['title']).'&';
				$this->assign('title',$data['title']);
			}
			//创建时间开始段
			if(trim($data['starttime'])){
				$where.=" and ad.createtime >= ".strtotime($data['starttime'].' 00:00:00');
				$url.='data[starttime]='.$data['starttime'].'&';
				$this->assign('starttime',$data['starttime']);
			}
			//创建时间结束段
			if(trim($data['endtime'])){
				$where.=" and ad.createtime <= ".strtotime($data['endtime'].' 23:59:59');
				$url.='data[endtime]='.$data['endtime'].'&';
				$this->assign('endtime',$data['endtime']);
			}
			//广告位编号
			if(!empty($data['code'])){
				$where.=" and ad.code=".$data['code'];
				$url.='data[code]='.$data['code'].'&';
				$this->assign('code',$data['code']);
			}
		}
		//页显数量
		$limit = 10;
		$advertisementModel = D('UcAdvertisement');
		//搜索广告记录数
		$adCount = $advertisementModel->hasAdvertisementCount($where);
		$pcount = ceil($adCount/$limit);
		if($page>=$pcount){
			$page=$pcount;
		}
		$url.='page=';
		//搜索广告
		$ads = $advertisementModel->hasManyAdvertisement($page,$limit,$where,$order);
		
		//分页
		$pageHtml = $this->page($url,$pcount,$limit,$page,count($ads));

		//广告位
		$adposition = C('AD_POSITION');
		foreach($ads as $key=>$val){
			$ads[$key]['adposition']=$adposition[$val['code']];
		}

		$this->assign('url',$url.$page);
		$this->assign('adposition',$adposition);
		$this->assign('pageHtml',$pageHtml);
		$this->assign('page',$page);
		$this->assign('ads',$ads);
		$this->display('index');
	}
	
	/**
	 * 广告删除
	 */
	public function deleteAd(){
		//参数
		$ids = $this->_get('deleteAd');
		$act = $this->_get('act');
		//当前页
		$page = $this->_get('page');
		//待删除广告id数组
		$idArr = explode(',',$ids);
		//删除广告并作操作记录
		$advertisementModel = D('UcAdvertisement');
		foreach($idArr as $key=>$val){
			if($val){
				$this->recordOperations(2,14,$val);
				$advertisementModel->where(array('id'=>$val))->save(array('status'=>-1));
			}
		}
		if(empty($act)){
			//$this->redirect('/iadmin.php/Ad/index?page='.$page);
			echo "<script>history.back();</script>";
		} else{
			echo 1;
			exit;
		}
	}

	/**
	 * 广告添加页面和编辑页面
	 */
	public function add(){
		//编辑广告
		if($this->_get('id')){
			//广告Model
			$advertisementModel = D('UcAdvertisement');

			//广告信息
			$ad = $advertisementModel->where(array('id'=>$this->_get('id')))->find();
			$this->assign('ad',$ad);
		}
		//广告位
		$adposition = C('AD_POSITION');
		$this->assign('adposition',$adposition);

		$this->display('add');
	}

	/**
	 * 保存广告信息
	 */
	public function editAd(){
		//广告信息参数
		if($this->_post('data')){
			//参数数组
			$data = $this->_post('data');
			//后台管理员id
			$data['uid'] = session('boqiiUserId');
			
			$advertisementModel = D('UcAdvertisement');
			//编辑保存
			if($data['id']){
				$field = "id,title,linkpath,code,uid";

				$uid = $advertisementModel->where(array('id'=>$data['id']))->field($field)->select();
				//判断不同字段
				foreach($uid as $key=>$val){
					foreach($data as $k=>$v){
						if($v!=$val[$k]){
							$arr[$k]['column']=$k;
							$arr[$k]['beforeContent']=$val[$k];
							$arr[$k]['afterContent']=$v;
						}
					}
				}
				//记录到操作日志
				foreach($arr as $key=>$val){
						$this->recordOperations(3,14,$data['id'],'','','',$val['column'],$val['beforeContent'],$val['afterContent']);
				}
				//保存修改信息
				$advertisementModel->save($data);
				$this->redirect('/iadmin.php/Ad/add?id='.$data['id'],'',3,'广告修改成功~');
				exit;
			}
			//新增保存
			else{
				$data['createtime'] = time();
				if(empty($data['code'])){
					echo "<script>alert('广告位置不能为空');history.back();</script>";
					exit;
				}
				$data['module_type'] = 1;
				$id = $advertisementModel->add($data);
				//记录到操作日志
				$this->recordOperations(1,14,$id);
				$this->redirect('/iadmin.php/Ad/add','',3,'广告添加成功~');
				exit;
			}
		}
	}
}

?>
