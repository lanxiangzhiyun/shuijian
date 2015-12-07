<?php
/**
 * 百科用户管理 控制器
 *
 * @author: zlg
 * @created: 13-1-18
 */
class BkExpertAction extends ExtendAction{

	//管理首页
	public function index() {
		$arrAssign = array();
		$expertModel = D('BkExpert');
		//分页参数
		$url = '/iadmin.php/BkExpert/index?';
		$url .= 'p=';
		$pageP = intval($_GET['p']);
		$params['page'] = empty($pageP) ? 1 : $pageP;
		$params['page_num'] = 10;
		//成员列表
		$strFields = 'uid,level,introduce,article_num,attention_num,create_time';
		$arrList = $expertModel->getList($strFields,$params);
		$arrAssign['arrList'] = $arrList;
		//分页参数
		$totalPage  = $expertModel->totalPage; //多少页
		$count   = $expertModel->count; //本页条数
		$pageHtml = $this->page($url, $totalPage, $params['page_num'], $params['page'], $count);
		$arrAssign['pageHtml'] = $pageHtml;
		$arrAssign['url'] = $url;

		foreach ($arrAssign as $key => $val) {
			$this->assign($key, $val);
		}
		$this->display('index');
	}
	//添加\编辑界面
	public function addMember() {
		$arrAssign = array();
		$expertModel = D('BkExpert');
		$action = $this->_param('action');
		$data = $this->_param('data');
		$uid = $this->_get('uid');
//添加 操作
		if($action == 'addok'){
			//检测用户
			$intUid = $expertModel->getUserInfo($data['uid']);
			if (empty($intUid)) {
				$data['status'] = 'emptyuser';
				$this->ajaxReturn($data,'JSON');
			}
			//字段检测
			if(!is_numeric($data['level'])) {
				$data['status'] = 'levelerror';
				$this->ajaxReturn($data,'JSON');
			}
			//专家角色
			if($data['level'] == 5){
				$arrFields = array();
				$arrFields['uid'] = $data['uid'];
				$arrFields['level'] = $data['level'];
				$arrFields['skill_subject'] = $data['skill_subject'];
				$arrFields['introduce'] = $data['introduce'];
				$arrFields['create_time'] = time();
	       //小编角色
			} else {
				$arrFields = array();
				$arrFields['uid'] = $data['uid'];
				$arrFields['level'] = $data['level'];
				$arrFields['introduce'] = $data['introduce'];
				$arrFields['create_time'] = time();
			}
			//添加接口
			$status = $expertModel->addMember($arrFields);
			if($status) {
				//更改为百科标志
				$this->editIsBaike ($data['uid']);
			   //记录log
				$this->recordOperations(1,26,$status);
				$data['status'] = 'true';
				$this->ajaxReturn($data,'JSON');
			} else {
				$data['status'] = 'false';
				$this->ajaxReturn($data,'JSON');
			}

//编辑 界面
		}elseif ($action =='edit'){
			$arrAssign['action'] = 'editok';
			$arrAssign['lang'] = '编辑成员';
			$arrAssign['uid'] = $uid;
			//所有专家、小编信息
			$strFields = 'uid,name,level,skill_subject,introduce';
			$arrInfo = $expertModel->getInfo($uid,$strFields);
			$arrAssign['arrInfo'] = $arrInfo;

//编辑 操作
		} elseif ($action == 'editok') {
			$arrFields = array();
			$arrFields['uid']         = $data['uid'];
			$arrFields['name']        = $data['name'];
			$arrFields['skill_subject'] = $data['skill_subject'];
			$arrFields['introduce']   = $data['introduce'];
			$arrFields['update_time'] = time();
			//记录log
			$fieldLog = array(
				'uid'=>array(
					'title'=>'用户编号'
				),
				'name'=>array(
					'title'=>'名称'
				),
				'introduce'=>array(
					'title'=>'简介'
				),
				'update_time'=>array(
					'title'=>'更新时间'
				)
			);
			$this->groupTip('BkExpert','id',$data['uid'],$fieldLog,$arrFields,25);
			//编辑接口
			$status = $expertModel->editMember ($arrFields);
			if($status) {
				$data['status'] = 'true';
				$this->ajaxReturn($data,'JSON');
			} else {
				$data['status'] = 'false';
				$this->ajaxReturn($data,'JSON');
			}

//添加 界面
		}else{
			$arrAssign['action'] = 'addok';
			$arrAssign['lang'] = '添加成员';
			//TODO nothing
		}
		foreach ($arrAssign as $key => $val) {
			$this->assign($key, $val);
		}
		$this->display('addMember');
	}

	function jump($mess='',$url='')
	{
		if (!empty($mess))
			echo  "<script>alert('".$mess."')</script>";
		if (!empty($url))
			echo "<script>location.href='".$url."'</script>";
	}

	//更改为百科标志
	public function editIsBaike ($uid) {
		$expertModel = D('BkExpert');
		//更改为百科标志
		$status = $expertModel->editFlag ($uid);
		return $status;
	}

}
