<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\AuthController;
use Think\Auth;

class MemberController extends AuthController {

/************************************************会员组列表管理**************************************************/
/*
 *会员组显示列表 
 */
	public function member_group(){
		$member_group=M('member_group');
		$member_group_list=$member_group->order('member_group_order')->select();
		$this->assign('member_group_list',$member_group_list);
		$this->display();
	}

/*
 * 会员组添加方法
 */
	public function member_group_runadd(){
		if (!IS_AJAX){
			$this->error('提交方式不正确',0,0);
		}else{
			M('member_group')->add($_POST);
			$this->success('会员组添加成功',U('member_group'),1);
		}
	}

/*
 * 会员组删除
 */
	public function member_group_del(){
		$member_group_id=I('member_group_id');
		if (empty($member_group_id)){
			$this->error('会员组ID不存在',U('member_group'),0);
		}
		M('member_group')->where(array('member_group_id'=>I('member_group_id')))->delete();
		$this->redirect('member_group');
	}

/*
 * 改变会员组状态
 */
	public function member_group_state(){
		$member_group_id=I('x');
		if (!$member_group_id){
			$this->error($member_group_id,U('member_group'),0);
		}
		$status=M('member_group')->where(array('member_group_id'=>$member_group_id))->getField('member_group_open');//判断当前状态情况
		if($status==1){
			$statedata = array('member_group_open'=>0);
			M('member_group')->where(array('member_group_id'=>$member_group_id))->setField($statedata);
			$this->success('状态禁止',1,1);
		}else{
			$statedata = array('member_group_open'=>1);
			M('member_group')->where(array('member_group_id'=>$member_group_id))->setField($statedata);
			$this->success('状态开启',1,1);
		}
	}
	
/*
 * 排序更新
 */
	public function member_group_order(){
		if (!IS_AJAX){
			$this->error('提交方式不正确',0,0);
		}else{
			$member_group=M('member_group');
			foreach ($_POST as $id => $sort){
				$member_group->where(array('member_group_id' => $id ))->setField('member_group_order' , $sort);
			}
			$this->success('排序更新成功',U('member_group'),1);
		}
	}
	
/*
 * 修改会员组返回值
 */	
	public function member_group_edit(){
		$member_group_id=I('member_group_id');
		$member_group=M('member_group')->where(array('member_group_id'=>$member_group_id))->find();
		
		$sl_data['member_group_id']=$member_group['member_group_id'];
		$sl_data['member_group_name']=$member_group['member_group_name'];
		$sl_data['member_group_open']=$member_group['member_group_open'];
		$sl_data['member_group_toplimit']=$member_group['member_group_toplimit'];
		$sl_data['member_group_bomlimit']=$member_group['member_group_bomlimit'];
		$sl_data['member_group_order']=$member_group['member_group_order'];
		
		$sl_data['status']=1;
		$this->ajaxReturn($sl_data,'json');
	}
	
/*
 * 修改用户组方法
 */
	public function member_group_runedit(){
		if (!IS_AJAX){
			$this->error('提交方式不正确',0,0);
		}else{
			$sl_data=array(
					'member_group_id'=>I('member_group_id'),
					'member_group_name'=>I('member_group_name'),
					'member_group_toplimit'=>I('member_group_toplimit'),
					'member_group_bomlimit'=>I('member_group_bomlimit'),
					'member_group_order'=>I('member_group_order'),
	
			);
			M('member_group')->save($sl_data);
			$this->success('用户组修改成功',U('member_group'),1);
		}
	}
	
	
	
	
	
	
	
	
}