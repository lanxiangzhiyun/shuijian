<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\AuthController;
use Think\Auth;
use Think\Model;
use Think\Db;
use OT\Database;

class SysController extends AuthController {
    //站点设置
    public function sys(){
    	$sys=M('sys')->where(array('sys_id'=>1))->find();
    	$this->assign('sys',$sys)->display();
    }
    
    //保存站点设置
    public function runsys(){
    	if (!IS_AJAX){
    		$this->error('提交方式不正确',0,0);
    	}else{
    		M('sys')->save($_POST);
    		$this->success('站点设置保存成功',1,1);
    	}
    }
    //站点设置
    public function wesys(){
    	$sys=M('sys')->where(array('sys_id'=>1))->find();
    	$this->assign('sys',$sys)->display();
    }
    
    //保存微信设置
    public function runwesys(){
    	if (!IS_AJAX){
    		$this->error('提交方式不正确',0,0);
    	}else{
    		M('sys')->save($_POST);
    		$this->success('微信设置保存成功',U('wesys'),1);
    	}
    }
    //发送邮件设置
    public function emailsys(){
    	$sys=M('sys')->where(array('sys_id'=>1))->find();
    	$this->assign('sys',$sys)->display();
    }

    //保存邮箱设置
    public function runemail(){
    	if (!IS_AJAX){
    		$this->error('提交方式不正确',0,0);
    	}else{
    		M('sys')->save($_POST);
    		$this->success('邮件设置保存成功',1,1);
    	}
    }
    
/************************************管理员模块****************************************/
    
	public function admin_list(){
		$admin=M('admin');
		$val=I('val');
		$auth = new Auth();
		$this->assign('testval',$val);
		if($val){
			$map['admin_username']= array('like',"%".$val."%");
		}
		
		$count= $admin->where($map)->count();// 查询满足要求的总记录数
		$Page= new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数

		foreach($map as $key=>$val) {
			$Page->parameter[$key]=urlencode($val);
		}
		$show= $Page->show();// 分页显示输出

		$admin_list=$admin->where($map)->order('admin_id')->limit($Page->firstRow.','.$Page->listRows)->select();

		foreach ($admin_list as $k=>$v){
			$group = $auth->getGroups($v['admin_id']);
			$admin_list[$k]['group'] = $group[0]['title'];
		}
		
		$this->assign('admin_list',$admin_list);
		$this->assign('page',$show);
		
		$this->display();
	}
    
    public function admin_list_add(){
		$auth_group=M('auth_group')->select();
		$this->assign('auth_group',$auth_group);
		$this->display();
	}

	public function admin_list_runadd(){
		$admin=M('admin');
		$admin_access=M('auth_group_access');
		$check_user=$admin->where(array('admin_username'=>I('admin_username')))->find();
		if ($check_user){
			$this->error('用户已存在，请重新输入用户名',0,0);
		}
		$sldata=array(
			'admin_username'=>I('admin_username'),
			'admin_pwd'=>I('admin_pwd','','md5'),
			'admin_email'=>I('admin_email'),
			'admin_tel'=>I('admin_tel'),
			'admin_open'=>I('admin_open'),
			'admin_realname'=>I('admin_realname'),
			'admin_ip'=>get_client_ip(),
			'admin_addtime'=>time(),
		);
		$result=$admin->add($sldata);
		$accdata=array(
			'uid'=>$result,
			'group_id'=>I('group_id'),
		);
		$admin_access->add($accdata);
		$this->success('管理员添加成功',U('admin_list'),1);
	}
	
	public function admin_list_edit(){
		$auth_group=M('auth_group')->select();
		$admin_list=M('admin')->where(array('admin_id'=>I('admin_id')))->find();
		$auth_group_access=M('auth_group_access')->where(array('uid'=>$admin_list['admin_id']))->getField('group_id');
		$this->assign('admin_list',$admin_list);
		$this->assign('auth_group',$auth_group);
		$this->assign('auth_group_access',$auth_group_access);
		$this->display();
	}
	
	public function admin_list_runedit(){
		$admin_list=M('admin');
		$admin_pwd=I('admin_pwd');
		$group_id=I('group_id');
		$admindata['admin_id']=I('admin_id');
		if ($admin_pwd){
			$admindata['admin_pwd']=I('admin_pwd','','md5');
		}
		$admindata['admin_email']=I('admin_email');
		$admindata['admin_tel']=I('admin_tel');
		$admindata['admin_realname']=I('admin_realname');
		$admindata['admin_open']=I('admin_open');
		$admin_list->save($admindata);
		//修改用户组
		M('auth_group_access')->where(array('uid'=>I('admin_id')))->setField('group_id',$group_id);
		$this->success('管理员修改成功',U('admin_list'),1);
	}
    
    public function admin_list_del(){
    	$admin_id=I('admin_id');
    	if (empty($admin_id)){
    		$this->error('用户ID不存在',U('admin_list'),1);
    	}
    	M('admin')->where(array('admin_id'=>I('admin_id')))->delete();
    	M('auth_group_access')->where(array('uid'=>I('admin_id')))->delete();
    	$this->redirect('admin_list');
    }
    
    public function admin_list_state(){
    	$id=I('x');
    	if (empty($id)){
    		$this->error('用户ID不存在',U('admin_list'),1);
    	}
    	$status=M('admin')->where(array('admin_id'=>$id))->getField('admin_open');//判断当前状态情况
    	if($status==1){
    		$statedata = array('admin_open'=>0);
    		$auth_group=M('admin')->where(array('admin_id'=>$id))->setField($statedata);
    		$this->success('状态禁止',1,1);
    	}else{
    		$statedata = array('admin_open'=>1);
    		$auth_group=M('admin')->where(array('admin_id'=>$id))->setField($statedata);
    		$this->success('状态开启',1,1);
    	}
    
    }
    
    //用户组管理
    public function admin_group(){
    	$auth_group=M('auth_group')->select();
    	$this->assign('auth_group',$auth_group);
    	$this->display();
    }
    
    public function admin_group_runadd(){
    	if (!IS_AJAX){
    		$this->error('提交方式不正确',0,0);
    	}else{
    		$sldata=array(
    			'title'=>I('title'),
    			'status'=>I('status'),
    			'addtime'=>time(),
    		);
    		M('auth_group')->add($sldata);
    		$this->success('用户组添加成功',U('admin_group'),1);
    	}
    }
    
    public function admin_group_del(){
    	M('auth_group')->where(array('id'=>I('id')))->delete();
    	$this->redirect('admin_group');
    }
    
    public function admin_group_edit(){
    	$group=M('auth_group')->where(array('id'=>I('id')))->find();
    	$this->assign('group',$group);
    	$this->display();
    }

    public function admin_group_runedit(){
    	if (!IS_AJAX){
    		$this->error('提交方式不正确',0,0);
    	}else{
    		$sldata=array(
    				'id'=>I('id'),
    				'title'=>I('title'),
    				'status'=>I('status'),
    		);
    		M('auth_group')->save($sldata);
    		$this->success('用户组修改成功',U('admin_group'),1);
    	}
    }
    
    public function admin_group_state(){
    	$id=I('x');
    	$status=M('auth_group')->where(array('id'=>$id))->getField('status');//判断当前状态情况
    	if($status==1){
    		$statedata = array('status'=>0);
    		$auth_group=M('auth_group')->where(array('id'=>$id))->setField($statedata);
			$this->success('状态禁止',1,1);
    	}else{
    		$statedata = array('status'=>1);
    		$auth_group=M('auth_group')->where(array('id'=>$id))->setField($statedata);
    		$this->success('状态开启',1,1);
    	}
    }
    
    public function admin_rule(){
    	$nav = new \Org\Util\Leftnav;
    	$admin_rule=M('auth_rule')->order('sort')->select();
    	$arr = $nav::rule($admin_rule);
	   	$this->assign('admin_rule',$arr);//权限列表
    	$this->display();
    }
    
    public function admin_rule_add(){
    	if(!IS_AJAX){
    		$this->error('提交方式不正确',0,0);
    	}else{
    		$admin_rule=M('auth_rule');
    		$sldata=array(
    				'name'=>I('name'),
    				'title'=>I('title'),
    				'status'=>I('status'),
    				'sort'=>I('sort'),
    				'addtime'=>time(),
    				'pid'=>I('pid'),
    				'css'=>I('css'),
    		);
    		$admin_rule->add($sldata);
    		$this->success('权限添加成功',U('admin_rule'),1);
    	}
    }
    
    public function admin_rule_state(){
    	$id=I('x');
    	$statusone=M('auth_rule')->where(array('id'=>$id))->getField('status');//判断当前状态情况
    	if($statusone==1){
    		$statedata = array('status'=>0);
    		$auth_group=M('auth_rule')->where(array('id'=>$id))->setField($statedata);
    		$this->success('状态禁止',1,1);
    	}else{
    		$statedata = array('status'=>1);
    		$auth_group=M('auth_rule')->where(array('id'=>$id))->setField($statedata);
    		$this->success('状态开启',1,1);
    	}
    
    }
    
    public function ruleorder(){
    	if (!IS_AJAX){
    		$this->error('提交方式不正确',0,0);
    	}else{
    		$auth_rule=M('auth_rule');
    		foreach ($_POST as $id => $sort){
    			$auth_rule->where(array('id' => $id ))->setField('sort' , $sort);
    		}
    		$this->success('排序更新成功',U('admin_rule'),1);
    	}
    }
    
    public function admin_rule_edit(){
    	$admin_rule=M('auth_rule')->where(array('id'=>I('id')))->find();
    	$this->assign('rule',$admin_rule);
    	$this->display();
    }

    public function admin_rule_runedit(){
        	if(!IS_AJAX){
    		$this->error('提交方式不正确',0,0);
    	}else{
    		$admin_rule=M('auth_rule');
    		$sldata=array(
    				'id'=>I('id'),
    				'name'=>I('name'),
    				'title'=>I('title'),
    				'status'=>I('status'),
    				'css'=>I('css'),
    				'sort'=>I('sort'),
    		);
    		$admin_rule->save($sldata);
    		$this->success('权限修改成功',U('admin_rule'),1);
    	}
    }
    
    public function admin_rule_del(){
    	M('auth_rule')->where(array('id'=>I('id')))->delete();
    	$this->redirect('admin_rule');
    }
    
    //三重权限配置
    public function admin_group_access(){
    	$admin_group=M('auth_group')->where(array('id'=>I('id')))->find();
    	$m = M('auth_rule');
    	$data = $m->field('id,name,title')->where('pid=0')->select();
    	foreach ($data as $k=>$v){
    		$data[$k]['sub'] = $m->field('id,name,title')->where('pid='.$v['id'])->select();
    		foreach ($data[$k]['sub'] as $kk=>$vv){
    			$data[$k]['sub'][$kk]['sub'] = $m->field('id,name,title')->where('pid='.$vv['id'])->select();
    		}
    	}
    	//p($data);die;
    	$this->assign('admin_group',$admin_group);	// 顶级
    	$this->assign('datab',$data);	// 顶级
    	$this->display();
    }
    
    public function admin_group_runaccess(){
    	$m = M('auth_group');
    	$new_rules = I('new_rules');
    	$imp_rules = implode(',', $new_rules).',';
    	$sldata=array(
    		'id'=>I('id'),
    		'rules'=>$imp_rules,	
    	);
    	if($m->save($sldata)){
    		$this->success('权限配置成功',U('admin_group'),1);
    	}else{
    		$this->error('权限配置失败');
    	}
    }
    
    
    /************************************数据库备份、还原****************************************/

    public function database($type = null){
    	if(empty($type)){
    		$type='export';
    	}
        switch ($type) {
            /* 数据还原 */
            case 'import':
                //列出备份文件列表
                $path = realpath(C('DB_PATH'));
                $flag = \FilesystemIterator::KEY_AS_FILENAME;
                $glob = new \FilesystemIterator($path,  $flag);

                $list = array();
                foreach ($glob as $name => $file) {
                    if(preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql(?:\.gz)?$/', $name)){
                        $name = sscanf($name, '%4s%2s%2s-%2s%2s%2s-%d');

                        $date = "{$name[0]}-{$name[1]}-{$name[2]}";
                        $time = "{$name[3]}:{$name[4]}:{$name[5]}";
                        $part = $name[6];

                        if(isset($list["{$date} {$time}"])){
                            $info = $list["{$date} {$time}"];
                            $info['part'] = max($info['part'], $part);
                            $info['size'] = $info['size'] + $file->getSize();
                        } else {
                            $info['part'] = $part;
                            $info['size'] = $file->getSize();
                        }
                        $extension        = strtoupper(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
                        $info['compress'] = ($extension === 'SQL') ? '-' : $extension;
                        $info['time']     = strtotime("{$date} {$time}");

                        $list["{$date} {$time}"] = $info;
                    }
                }
                $title = '数据还原';
                break;

            /* 数据备份 */
            case 'export':
                $Db    = Db::getInstance();
                $list  = $Db->query('SHOW TABLE STATUS FROM '.C('DB_NAME'));
                $list  = array_map('array_change_key_case', $list);
                $title = '数据备份';
                break;

            default:
                $this->error('参数错误！');
        }

        //渲染模板
        $this->assign('meta_title', $title);
        $this->assign('data_list', $list);
        $this->display($type);
    }

    public function import(){
    			$path = realpath(C('DB_PATH'));
    			$flag = \FilesystemIterator::KEY_AS_FILENAME;
    			$glob = new \FilesystemIterator($path,  $flag);
    
    			$list = array();
    			foreach ($glob as $name => $file) {
    				if(preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql(?:\.gz)?$/', $name)){
    					$name = sscanf($name, '%4s%2s%2s-%2s%2s%2s-%d');
    
    					$date = "{$name[0]}-{$name[1]}-{$name[2]}";
    					$time = "{$name[3]}:{$name[4]}:{$name[5]}";
    					$part = $name[6];
    
    					if(isset($list["{$date} {$time}"])){
    						$info = $list["{$date} {$time}"];
    						$info['part'] = max($info['part'], $part);
    						$info['size'] = $info['size'] + $file->getSize();
    					} else {
    						$info['part'] = $part;
    						$info['size'] = $file->getSize();
    					}
    					$extension        = strtoupper(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
    					$info['compress'] = ($extension === 'SQL') ? '-' : $extension;
    					$info['time']     = strtotime("{$date} {$time}");
    
    					$list["{$date} {$time}"] = $info;
    				}
    			}


    	//渲染模板
    	$this->assign('meta_title', $title);
    	$this->assign('data_list', $list);
    	$this->display($type);
    }
    
    
    
    /**
     * 优化表
     * @param  String $tables 表名
     * @author slackck<876902658@qq.com>
     */
    public function optimize($tables = null){
        if($tables) {
            $Db   = Db::getInstance();
            if(is_array($tables)){
                $tables = implode('`,`', $tables);
                $list = $Db->query("OPTIMIZE TABLE `{$tables}`");

                if($list){
                    $this->success("数据表优化完成！");
                } else {
                    $this->error("数据表优化出错请重试！");
                }
            } else {
                $list = $Db->query("OPTIMIZE TABLE `{$tables}`");
                if($list){
                    $this->success("数据表'{$tables}'优化完成！");
                } else {
                    $this->error("数据表'{$tables}'优化出错请重试！");
                }
            }
        } else {
            $this->error("请指定要优化的表！");
        }
    }

    /**
     * 修复表
     * @param  String $tables 表名
     * @author slackck<876902658@qq.com>
     */
    public function repair($tables = null){
        if($tables) {
            $Db   = Db::getInstance();
            if(is_array($tables)){
                $tables = implode('`,`', $tables);
                $list = $Db->query("REPAIR TABLE `{$tables}`");

                if($list){
                    $this->success("数据表修复完成！");
                } else {
                    $this->error("数据表修复出错请重试！");
                }
            } else {
                $list = $Db->query("REPAIR TABLE `{$tables}`");
                if($list){
                    $this->success("数据表'{$tables}'修复完成！");
                } else {
                    $this->error("数据表'{$tables}'修复出错请重试！");
                }
            }
        } else {
            $this->error("请指定要修复的表！");
        }
    }

    /**
     * 删除备份文件
     * @param  Integer $time 备份时间
     * @author slackck<876902658@qq.com>
     */
    public function del($time = 0){
        if($time){
            $name  = date('Ymd-His', $time) . '-*.sql*';
            $path  = realpath(C('DB_PATH')) . DIRECTORY_SEPARATOR . $name;
            array_map("unlink", glob($path));
            if(count(glob($path))){
                $this->success('备份文件删除失败，请检查权限！');
            } else {
                $this->success('备份文件删除成功！');
            }
        } else {
            $this->error('参数错误！');
        }
    }

    /**
     * 备份数据库
     * @param  String  $tables 表名
     * @param  Integer $id     表ID
     * @param  Integer $start  起始行数
     * @author slackck<876902658@qq.com>
     */
    public function export($tables = null, $id = null, $start = null){
        if(IS_POST && !empty($tables) && is_array($tables)){ //初始化
            //读取备份配置
            $config = array(
            	'path'     => realpath(C('DB_PATH')) . DIRECTORY_SEPARATOR,
                //'path'     => C('DB_PATH'),
                'part'     => C('DB_PART'),
                'compress' => C('DB_COMPRESS'),
                'level'    => C('DB_LEVEL'),
            );
            //$this->error($config['path'],0,0);
            //检查是否有正在执行的任务
            $lock = "{$config['path']}backup.lock";
            if(is_file($lock)){
                $this->error('检测到有一个备份任务正在执行，请稍后再试！',0,0);
            } else {
                //创建锁文件
                file_put_contents($lock, NOW_TIME);
            }

            //检查备份目录是否可写
            is_writeable($config['path']) || $this->error('备份目录不存在或不可写，请检查后重试！',0,0);
            session('backup_config', $config);

            //生成备份文件信息
            $file = array(
                'name' => date('Ymd-His', NOW_TIME),
                'part' => 1,
            );
            session('backup_file', $file);

            //缓存要备份的表
            session('backup_tables', $tables);

            //创建备份文件
            $Database = new Database($file, $config);
            if(false !== $Database->create()){
                $tab = array('id' => 0, 'start' => 0);
                $this->success('初始化成功！', '', array('tables' => $tables, 'tab' => $tab));
            } else {
                $this->error('初始化失败，备份文件创建失败！',0,0);
            }
        } elseif (IS_GET && is_numeric($id) && is_numeric($start)) { //备份数据
            $tables = session('backup_tables');
            //备份指定表
            $Database = new Database(session('backup_file'), session('backup_config'));
            $start  = $Database->backup($tables[$id], $start);
            if(false === $start){ //出错
                $this->error('备份出错！',0,0);
            } elseif (0 === $start) { //下一表
                if(isset($tables[++$id])){
                    $tab = array('id' => $id, 'start' => 0);
                    $this->success('备份完成！', '', array('tab' => $tab));
                } else { //备份完成，清空缓存
                    unlink(session('backup_config.path') . 'backup.lock');
                    session('backup_tables', null);
                    session('backup_file', null);
                    session('backup_config', null);
                    $this->success('备份完成！',1,1);
                }
            } else {
                $tab  = array('id' => $id, 'start' => $start[0]);
                $rate = floor(100 * ($start[0] / $start[1]));
                $this->success("正在备份...({$rate}%)", '', array('tab' => $tab));
            }

        } else { //出错
            $this->error('参数错误！');
        }
    }

    
}