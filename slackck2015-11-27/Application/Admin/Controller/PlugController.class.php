<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\AuthController;
use Think\Auth;

class PlugController extends AuthController {
    public function plug_link_list(){
    	$type=I('type');
    	$val=I('val');
    	if (!empty($type)){
    		$map['plug_link_typeid']=  array('eq',I('type'));
    	}
    	if (!empty($val)){
    		$map['plug_link_name|plug_link_url'] = array('like',"%".$val."%");
    	}
    	
    	
    	$link_type=M('plug_linktype')->select();
    	$plug_link=D('Plug_link')->where($map)->order('plug_link_addtime desc')->relation(true)->select();
    	$this->assign('plug_link',$plug_link);
    	$this->assign('link_type',$link_type);
    	$this->assign('val',$val);
		$this->display();
    }
    
    public function plug_linktype_list(){
    	$link_type=M('plug_linktype')->select();
    	$this->assign('link_type',$link_type);
    	$this->display();
    }
    
    public function plug_link_runadd(){
    	if (!IS_AJAX){
    		$this->error('提交方式不正确',0,0);
    	}else{
    		$sl_data=array(
    			'plug_link_name'=>I('plug_link_name'),
    			'plug_link_url'=>I('plug_link_url'),
    			'plug_link_typeid'=>I('plug_link_typeid'),
    			'plug_link_qq'=>I('plug_link_qq'),
    			'plug_link_order'=>I('plug_link_order'),
    			'plug_link_addtime'=>time(),
    			'plug_link_open'=>I('plug_link_open'),
    		);
    		M('plug_link')->add($sl_data);
    		$this->success('友情链接添加成功',U('plug_link_list'),1);
    	}
    }
    
    public function plug_link_del(){
    	$p=I('p');
    	M('plug_link')->where(array('plug_link_id'=>I('plug_link_id')))->delete();
    	$this->redirect('plug_link_list', array('p' => $p));
    }

    public function plug_link_state(){
    	$id=I('x');
    	$status=M('plug_link')->where(array('plug_link_id'=>$id))->getField('plug_link_open');//判断当前状态情况
    	if($status==1){
    		$statedata = array('plug_link_open'=>0);
    		$auth_group=M('plug_link')->where(array('plug_link_id'=>$id))->setField($statedata);
    		$this->success('状态禁止',1,1);
    	}else{
    		$statedata = array('plug_link_open'=>1);
    		$auth_group=M('plug_link')->where(array('plug_link_id'=>$id))->setField($statedata);
    		$this->success('状态开启',1,1);
    	}
    }
    
    public function plug_link_edit(){
    	$plug_link_id=I('plug_link_id');
    	$plug_link=M('plug_link')->where(array('plug_link_id'=>$plug_link_id))->find();
		$sl_data['plug_link_id']=$plug_link['plug_link_id'];
		$sl_data['plug_link_name']=$plug_link['plug_link_name'];
		$sl_data['plug_link_url']=$plug_link['plug_link_url'];
		$sl_data['plug_link_qq']=$plug_link['plug_link_qq'];
		$sl_data['plug_link_order']=$plug_link['plug_link_order'];
		$sl_data['plug_link_open']=$plug_link['plug_link_open'];
		$sl_data['plug_link_typeid']=$plug_link['plug_link_typeid'];
		$sl_data['status']=1;
		$this->ajaxReturn($sl_data,'json');
    }
    
    public function plug_link_runedit(){
    	if (!IS_AJAX){
    		$this->error('提交方式不正确',0,0);
    	}else{
    		$sl_data=array(
    				'plug_link_id'=>I('plug_link_id'),
    				'plug_link_name'=>I('plug_link_name'),
    				'plug_link_url'=>I('plug_link_url'),
    				'plug_link_typeid'=>I('plug_link_typeid'),
    				'plug_link_qq'=>I('plug_link_qq'),
    				'plug_link_order'=>I('plug_link_order'),

    		);
    		M('plug_link')->save($sl_data);
    		$this->success('友情链接修改成功',U('plug_link_list'),1);
    	}
    }

/**********************************************友情链接所属栏目***********************************************************/    

    public function plug_linktype_del(){
    	$link_type=M('plug_linktype')->where(array('plug_linktype_id'=>I('id')))->delete();
    	$this->redirect('plug_linktype_list');
    }
    
    public function plug_linktype_add(){
    	$plug_linktype=M('plug_linktype');
    	$plug_linktype->add($_POST);
    	$this->success('栏目添加成功',U('plug_linktype_list'),1);
    }
    
    public function plug_linktype_edit(){
    	$plug_linktype=M('plug_linktype');
    	$sl_data=array(
    			'plug_linktype_id'=>I('plug_linktype_id'),
    			'plug_linktype_name'=>I('plug_linktype_name'),
    			'plug_linktype_order'=>I('plug_linktype_order'),
    	);
    	$plug_linktype->save($sl_data);
    	$this->success('友情链接栏目修改成功',U('plug_linktype_list'),1);
    }
    
    public function linktype_order(){
    	if (!IS_AJAX){
    		$this->error('提交方式不正确',0,0);
    	}else{
    		$plug_linktype=M('plug_linktype');
    		foreach ($_POST as $plug_linktype_id => $plug_linktype_order){
    			$plug_linktype->where(array('plug_linktype_id' => $plug_linktype_id ))->setField('plug_linktype_order' , $plug_linktype_order);
    		}
    		$this->success('排序更新成功',U('plug_linktype_list'),1);
    	}
    }

}