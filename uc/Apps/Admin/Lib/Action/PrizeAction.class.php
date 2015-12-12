<?php
/**
 * 抽奖管理
 *
 * created 2013-10-28
 * author zzy
 */
class PrizeAction extends ExtendAction{
	 

	//奖品列表
	public function index(){
		 
		$list = D('Prize')->getPrizeList();
		$this->assign('list',$list);
		$this->display('index');
	}

	//奖品编辑页面
	public function editPrize(){
		$id = $this->_get('id');
		$prizeModel = D('Prize');
		$info = $prizeModel->getPrizeInfo($id);
		$this->assign('info',$info);
		if($_POST){
			$data = $this->_post('data');
			$prizeModel->save($data);
			$this->redirect('/iadmin.php/Prize/index');
		}
		$this->display('editPrize');
	}
	
	//抽奖明细页
	public function prizeLogList(){
		$prizeModel = D('Prize');
		$param['page'] = $this->_get('page') ? $this->_get('page') : 1;
		$param['limit'] = 20;
		$param['order'] = 'pl.id desc';
		$data = $this->_get('data');
		$url = "/iadmin.php/Prize/prizeLogList?";
		$where = '1=1';
		if($data){
			if(!empty($data['keyword'])){
				$where.=" and p.title like '%".$data['keyword']."%' ";
				$url.='data[keyword]='.urlencode($data['keyword']).'&';
				$this->assign('keyword',$data['keyword']);
			}
			if(!empty($data['prize'])){
				$where.=" and p.prize like '%".$data['prize']."%' ";
				$url.='data[prize]='.urlencode($data['prize']).'&';
				$this->assign('prize',$data['prize']);
			}
			if(trim($data['starttime'])){
				$where.=" and pl.create_time >= ".strtotime($data['starttime'].' 00:00:00');
				$url.='data[starttime]='.$data['starttime'].'&';
				$this->assign('starttime',$data['starttime']);
			}
			if(trim($data['endtime'])){
				$where.=" and pl.create_time <= ".strtotime($data['endtime'].' 23:59:59');
				$url.='data[endtime]='.$data['endtime'].'&';
				$this->assign('endtime',$data['endtime']);
			}
		}
		$param['where'] = $where;
		$count = $prizeModel->getPrizeLogCount($param);
		$pcount = ceil($count/$param['limit']);
		if($param['page'] >= $pcount){
			$param['page'] = $pcount;
		}
		$list = $prizeModel->getPrizeLogList($param);
		$url .= "page=";
		$pageHtml = $this->page($url,$pcount,$param['limit'],$param['page'],count($list));
		$this->assign('pageHtml',$pageHtml);
		$this->assign('list',$list);
		$this->display('prizeLogList');
	}

	//每天清空使用数据
	public function flushData(){
		$act = $this->_post('act');
		if($act == 'flush'){
			D('Prize')->query("update market_prize set use_num=0");
			echo json_encode(array('status'=>'ok','msg'=>'重置成功！'));
		}else{
			echo json_encode(array('status'=>'false','msg'=>'重置失败！请重新操作！'));
		}
		exit;
	}
}
?>