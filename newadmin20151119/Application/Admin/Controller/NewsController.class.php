<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\AuthController;
use Think\Auth;

class NewsController extends AuthController {
	
/************************************************文章管理**************************************************/
	//文章列表
    public function news_list(){
    	$keytype=I('keytype',news_title);
    	$key=I('key');
    	$opentype_check=I('opentype_check','');
    	$diyflag=I('diyflag','');
    	//查询：时间格式过滤
    	$sldate=I('reservation','');//获取格式 2015-11-12 - 2015-11-18
    	$arr = explode(" - ",$sldate);//转换成数组
    	$arrdateone=strtotime($arr[0]);
    	$arrdatetwo=strtotime($arr[1].' 23:55:55');
    	//map架构查询条件数组
    	$map['news_back']= 0;
    	$map[$keytype]= array('like',"%".$key."%");
    	if ($opentype_check!=''){
    	$map['news_open']= array('eq',$opentype_check);
    	}
    	$map['news_time'] = array(array('egt',$arrdateone),array('elt',$arrdatetwo),'AND');
    	if ($diyflag){
    		$map[] ="FIND_IN_SET('$diyflag',news_flag)";
    	}
    	//p($map);die;
    	$count= M('news')->where($map)->count();// 查询满足要求的总记录数
    	$Page= new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
    	$show= $Page->show();// 分页显示输出
    	$news=D('News')->where($map)->limit($Page->firstRow.','.$Page->listRows)->order('news_time desc')->relation(true)->select();
    	$diyflag_list=M('diyflag')->select();//文章属性数据
    	
    	$this->assign('opentype_check',$opentype_check);
    	$this->assign('keytype',$keytype);
    	$this->assign('keyy',$key);
    	$this->assign('sldate',$sldate);
    	$this->assign('diyflag_check',$diyflag);
    	$this->assign('diyflag',$diyflag_list);
    	$this->assign('news',$news);
    	$this->assign('page',$show);
		$this->display();
    }
    
    //添加文章
    public function news_listadd(){
    	$column=M('column');
		$diyflag=M('diyflag');
    	$nav = new \Org\Util\Leftnav;
    	$column_next=$column->where('column_type <> 5 and column_type <> 2')-> order('column_order') -> select();
		$diyflag=$diyflag->select();
    	$arr = $nav::column($column_next);
    	$this->assign('column',$arr);
		$this->assign('diyflag',$diyflag);
    	$this->display();
    }
    
    public function news_runadd(){
    	if (!IS_AJAX){
    		$this->error('提交方式不正确',U('news_list'),0);
    	}
    	$file=I('file0');
    	$news=M('news');
    	//获取图片上传后路径
    	$upload = new \Think\Upload();// 实例化上传类
    	$upload->maxSize   =     3145728 ;// 设置附件上传大小
    	$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
    	$upload->rootPath  =     './Public/Uploads/'; // 设置附件上传根目录
    	$upload->savePath  =     ''; // 设置附件上传（子）目录
    	$upload->saveRule  =     'time';
    	$info   =   $upload->upload();
    	if($info) {
    		$img_url='/uploads/'.$info[file0][savepath].$info[file0][savename];//如果上传成功则完成路径拼接
    		
    	}elseif(!$file){
    			$img_url='';//否则如果字段为空，表示没有上传任何文件，赋值空
    	}else{
    			$this->error($upload->getError());//否则就是上传错误，显示错误原因
    	}
    	//获取文章属性
    	$news_flag=I('news_flag');
    	$flag=array();
    	foreach ($news_flag as $v){
    		$flag[]=$v[0];
    	}
    	$flagdata=implode(',',$flag);
    	
    	$sl_data=array(
    			'news_title'=>I('news_title'),
    			'news_titleshort'=>I('news_titleshort'),
    			'news_columnid'=>I('news_columnid'),
    			'news_flag'=>$flagdata,
    			'news_zaddress'=>I('news_zaddress'),
    			'news_key'=>I('news_key'),
    			'news_tag'=>I('news_key'),
    			'news_source'=>I('news_source'),
    			'news_img'=>$img_url,
    			'news_open'=>I('news_open'),
    			'news_scontent'=>I('news_scontent'),
    			'news_content'=>I('news_content'),
    			'news_auto'=>cookie('admin_realname'),
    			'news_time'=>time(),
    			'news_hits'=>200,
    	);
    	$news->add($sl_data);
    	$this->success('文章添加成功,返回列表页',U('news_list'),1);
    }
    

    public function news_runedit(){
    	if (!IS_AJAX){
    		$this->error('提交方式不正确',U('news_list'),0);
    	}
    	$news=M('news');
    	//获取图片上传后路径
    	$checkpic=I('checkpic');
    	$oldcheckpic=I('oldcheckpic');
    	//$this->error($oldcheckpic,0,0);
    	if ($checkpic!=$oldcheckpic){
	    	$upload = new \Think\Upload();// 实例化上传类
	    	$upload->maxSize   =     3145728 ;// 设置附件上传大小
	    	$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
	    	$upload->rootPath  =     './Public/Uploads/'; // 设置附件上传根目录
	    	$upload->savePath  =     ''; // 设置附件上传（子）目录
	    	$upload->saveRule  =     'time';
	    	$info   =   $upload->upload();
	    	if($info) {
	    		$img_url='/uploads/'.$info[file0][savepath].$info[file0][savename];//如果上传成功则完成路径拼接
	    	}else{
	    		$this->error($upload->getError());//否则就是上传错误，显示错误原因
	    	}
	    	
    	}
    	//获取文章属性
    	$news_flag=I('news_flag');
    	$flag=array();
    	foreach ($news_flag as $v){
    		$flag[]=$v[0];
    	}
    	$flagdata=implode(',',$flag);
    	

    	$sl_data=array(
    			'n_id'=>I('n_id'),
    			'news_title'=>I('news_title'),
    			'news_titleshort'=>I('news_titleshort'),
    			'news_columnid'=>I('news_columnid'),
    			'news_flag'=>$flagdata,
    			'news_zaddress'=>I('news_zaddress'),
    			'news_key'=>I('news_key'),
    			'news_tag'=>I('news_key'),
    			'news_source'=>I('news_source'),
    			'news_open'=>I('news_open'),
    			'news_scontent'=>I('news_scontent'),
    			'news_content'=>I('news_content'),
    			'news_auto'=>cookie('admin_realname'),
    	);
    	if ($checkpic!=$oldcheckpic){
    		$sl_data['news_img']=$img_url;
    	}
    	
    	$news->save($sl_data);
    	$this->success('文章修改成功,返回列表页',U('news_list'),1);
    }
    
    public function news_list_del(){
    	$p=I('p');
    	M('news')->where(array('n_id'=>I('n_id')))->setField('news_back',1);//转入回收站
    	$this->redirect('news_list', array('p' => $p));
    }

    public function news_back_open(){
    	$p=I('p');
    	M('news')->where(array('n_id'=>I('n_id')))->setField('news_back',0);//转入回收站
    	$this->redirect('news_back', array('p' => $p));
    }
    
    //回收站
    public function news_back(){
    	$keytype=I('keytype',news_title);
    	$key=I('key');
    	$opentype_check=I('opentype_check','');
    	$diyflag=I('diyflag','');
    	//查询：时间格式过滤
    	$sldate=I('reservation','');//获取格式 2015-11-12 - 2015-11-18
    	$arr = explode(" - ",$sldate);//转换成数组
    	$arrdateone=strtotime($arr[0]);
    	$arrdatetwo=strtotime($arr[1].' 23:55:55');
    	//map架构查询条件数组
    	$map['news_back']= 1;
    	$map[$keytype]= array('like',"%".$key."%");
    	if ($opentype_check!=''){
    	$map['news_open']= array('eq',$opentype_check);
    	}
    	$map['news_time'] = array(array('egt',$arrdateone),array('elt',$arrdatetwo),'AND');
    	if ($diyflag){
    		$map[] ="FIND_IN_SET('$diyflag',news_flag)";
    	}
    	//p($map);die;
    	$count= M('news')->where($map)->count();// 查询满足要求的总记录数
    	$Page= new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
    	$show= $Page->show();// 分页显示输出
    	$news=D('News')->where($map)->limit($Page->firstRow.','.$Page->listRows)->order('news_time desc')->relation(true)->select();
    	$diyflag_list=M('diyflag')->select();//文章属性数据
    	$this->assign('opentype_check',$opentype_check);
    	$this->assign('keytype',$keytype);
    	$this->assign('keyy',$key);
    	$this->assign('sldate',$sldate);
    	$this->assign('diyflag_check',$diyflag);
    	$this->assign('diyflag',$diyflag_list);
    	$this->assign('news',$news);
    	$this->assign('page',$show);
		$this->display();
    	    }
    
    public function news_back_del(){
    	$news_back=M('news')->where(array('n_id'=>I('n_id')))->delete();
    	$this->redirect('news_back', array('p' => $p));
    }

    public function news_back_alldel(){
    	$p = I('p');
    	$ids = I('n_id','',htmlspecialchars);
    	if(empty($ids)){
    		$this -> error("请选择删除文章");//判断是否选择了文章ID
    	}
    	$model = D('news');
    	if(is_array($ids)){//判断获取文章ID的形式是否数组
    		$where = 'n_id in('.implode(',',$ids).')';
    	}else{
    		$where = 'n_id='.$ids;
    	}
    	M('news')->where($where)->delete();
    	$this->success("成功把文章删除，不可还原！",U('news_back',array('p'=>$p)),1);
    }
    
    public function news_list_alldel(){
    	$p = I('p');
    	$ids = I('n_id','',htmlspecialchars);
    	if(empty($ids)){
    		$this -> error("请选择删除文章");//判断是否选择了文章ID
    	}
    	$model = D('news');
    	if(is_array($ids)){//判断获取文章ID的形式是否数组
    		$where = 'n_id in('.implode(',',$ids).')';
    	}else{
    		$where = 'n_id='.$ids;
    	}
    	M('news')->where($where)->setField('news_back',1);//转入回收站
    	$this->success("成功把文章移至回收站！",U('news_list',array('p'=>$p)),1);
    }
    

    
	public function news_list_edit(){
		$news_list=M('news')->where(array('n_id'=>I('n_id')))->find();
		$column=M('column');
		$diyflag=M('diyflag');
		$nav = new \Org\Util\Leftnav;
		$column_next=$column->where('column_type <> 5 and column_type <> 2')-> order('column_order') -> select();
		$diyflag=$diyflag->select();
		$arr = $nav::column($column_next);
		$this->assign('column',$arr);
		$this->assign('diyflag',$diyflag);
		$this->assign('news_list',$news_list);
		$this->display();
	}
	
	public function news_list_state(){
		$id=I('x');
		$status=M('news')->where(array('n_id'=>$id))->getField('news_open');//判断当前状态情况
		if($status==1){
			$statedata = array('news_open'=>0);
			$auth_group=M('news')->where(array('n_id'=>$id))->setField($statedata);
			$this->success('状态禁止',1,1);
		}else{
			$statedata = array('news_open'=>1);
			$auth_group=M('news')->where(array('n_id'=>$id))->setField($statedata);
			$this->success('状态开启',1,1);
		}
	}
	
/************************************************栏目管理**************************************************/
	//栏目管理
	public function news_column(){
		$column=M('column');
		$nav = new \Org\Util\Leftnav;
		$column=$column->order('column_order')->select();
		$arr = $nav::column($column);
		$this->assign('arr',$arr);
		$this->display();
	}
	
	//添加栏目
	public function news_columnadd(){
		$column_leftid=I('c_id',0);
		$this->assign('column_leftid',$column_leftid);
		$this->display();
	}
	
	public function runnews_columnadd(){
		if (!IS_AJAX){
			$this->error('提交方式不正确',U('News/news_column'),0);
		}else{
			$data=array(
					'column_name'=>I('column_name'),
					'column_enname'=>I('column_enname'),
					'column_type'=>I('column_type'),
					'column_leftid'=>I('column_leftid'),
					'column_address'=>I('column_address'),
					'column_open'=>I('column_open',0),
					'column_order'=>I('column_order'),
					'column_title'=>I('column_title'),
					'column_key'=>I('column_key'),
					'column_des'=>I('column_des'),
					'column_content'=>I('column_content'),
			);
			M('column')->add($data);
			$this->success('栏目保存成功',U('News/news_column'),1);
		}
	}
	
	//删除栏目
	public function news_columndel(){
		$column=M('column')->where(array('c_id'=>I('c_id')))->delete();
		$this->redirect('news_column');
	}

	

	public function leftnavorder(){
		if (!IS_AJAX){
			$this->error('提交方式不正确',0,0);
		}else{
			$column=M('column');
			foreach ($_POST as $id => $sort){
				$column->where(array('c_id' => $id ))->setField('column_order' , $sort);
			}
			$this->success('排序更新成功',U('news_column'),1);
		}
	}
	

	public function column_state(){
		$id=I('x');
		$status=M('column')->where(array('c_id'=>$id))->getField('column_open');//判断当前状态情况
		if($status==1){
			$statedata = array('column_open'=>0);
			$auth_group=M('column')->where(array('c_id'=>$id))->setField($statedata);
			$this->success('状态禁止',1,1);
		}else{
			$statedata = array('column_open'=>1);
			$auth_group=M('column')->where(array('c_id'=>$id))->setField($statedata);
			$this->success('状态开启',1,1);
		}
	}
	
	
	
	
}