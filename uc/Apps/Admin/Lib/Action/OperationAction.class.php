<?php
/*
*OperationAction 操作日志
*/
class OperationAction extends ExtendAction{
	
	/*
	*操作日志列表
	*/
	public function index(){
		$limit = 20;
		$page=$this->_get('page');
		if($page=='' || !is_numeric($page)){
			$page=1;
		}
		$ucOperation = D('UcOperation');
		
		$url='/iadmin.php/Operation/index?';

		$where="status=0";
		
		//搜索条件
		$noAllow = C('NO_ALLOW');
		if($this->_get('data')){
			$data = $this->_get('data');
			if(!in_array($data['truename'],$noAllow) && !empty($data['truename'])){
				$where.=" and truename like '%".$data['truename']."%' ";
				$url.='data[truename]='.urlencode($data['truename']).'&';
				$this->assign('truename',$data['truename']);
			}
			if(trim($data['starttime'])){
				$where.=" and operationtime >= ".strtotime($data['starttime'].' 00:00:00');
				$url.='data[starttime]='.$data['starttime'].'&';
				$this->assign('starttime',$data['starttime']);
			}
			if(trim($data['endtime'])){
				$where.=" and operationtime <= ".strtotime($data['endtime'].' 23:59:59');
				$url.='data[endtime]='.$data['endtime'].'&';
				$this->assign('endtime',$data['endtime']);
			}
			if(!empty($data['select'])){
				$where.=" and type=".$data['select'];
				$url.='data[select]='.$data['select'].'&';
				$this->assign('select',$data['select']);
			}
			
		}
		$where.=" and operation_type=1";
		$url.='page=';
		$OperationCount = $ucOperation->where($where)->count();
		$pcount = ceil($OperationCount/$limit);
		if($page>=$pcount){
			$page=$pcount;
		}

		
		
		$Operations = $ucOperation->where($where)->order('id desc')->page($page)->limit($limit)->select();
		$pageHtml = $this->page($url,$pcount,$limit,$page,count($Operations));
		$logOperation = C('LOG_OPERATION');
		$logType=C('LOG_OPERATION_TYPE');
		foreach($Operations as $key=>$val){
			$Operations[$key]['logUrl']=$logOperation[$val['position']];
			$Operations[$key]['logType']=$logType[$val['type']];
		}
		$this->assign('pageHtml',$pageHtml);
		$this->assign('page',$page);
		$this->assign('Operations',$Operations);
		$this->display('index');
	}

	/*
	*百科操作日志列表
	*/
	public function baikeOperation(){
		$limit = 20;
		$page=$this->_get('page');
		if($page=='' || !is_numeric($page)){
			$page=1;
		}
		$ucOperation = D('UcOperation');
		
		$url='/iadmin.php/Operation/baikeOperation?';

		$where="status=0";
		
		//搜索条件
		$noAllow = C('NO_ALLOW');
		if($this->_get('data')){
			$data = $this->_get('data');
			if(!in_array($data['truename'],$noAllow) && !empty($data['truename'])){
				$where.=" and truename like '%".$data['truename']."%' ";
				$url.='data[truename]='.urlencode($data['truename']).'&';
				$this->assign('truename',$data['truename']);
			}
			if(trim($data['starttime'])){
				$where.=" and operationtime >= ".strtotime($data['starttime'].' 00:00:00');
				$url.='data[starttime]='.$data['starttime'].'&';
				$this->assign('starttime',$data['starttime']);
			}
			if(trim($data['endtime'])){
				$where.=" and operationtime <= ".strtotime($data['endtime'].' 23:59:59');
				$url.='data[endtime]='.$data['endtime'].'&';
				$this->assign('endtime',$data['endtime']);
			}
			if(!empty($data['select'])){
				$where.=" and type=".$data['select'];
				$url.='data[select]='.$data['select'].'&';
				$this->assign('select',$data['select']);
			}
			
		}
		$where.=" and operation_type=2";
		$url.='page=';
		$OperationCount = $ucOperation->where($where)->count();
		$pcount = ceil($OperationCount/$limit);
		if($page>=$pcount){
			$page=$pcount;
		}

		
		
		$Operations = $ucOperation->where($where)->order('id desc')->page($page)->limit($limit)->select();
		$pageHtml = $this->page($url,$pcount,$limit,$page,count($Operations));
		$logOperation = C('FONT_POSITION');
		$logType=C('FONT_LOG_OPERATION_TYPE');
		foreach($Operations as $key=>$val){
			$Operations[$key]['logUrl']=$logOperation[$val['position']];
			$Operations[$key]['logType']=$logType[$val['type']];
		}
		$this->assign('pageHtml',$pageHtml);
		$this->assign('page',$page);
		$this->assign('Operations',$Operations);
		$this->display('baikeOperation');
	}
}
?>