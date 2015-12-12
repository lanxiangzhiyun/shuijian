<?php

/**
* 专题模型
*
* @author: jasonjiang
* @date: 2014/08/19
*/
class SubTemModel extends Model{
	protected $trueTableName = 'zt_template_new';
	/**
	* 获得专题列表
	*
	* $param 
	* 
	*/
	public function getSubjectList($param){
		//分页参数
        $page = isset($param['page']) ? $param['page'] : 1;
        $pageNum = isset($param['pageNum']) ? $param['pageNum'] : 10;
    	
    	$where = "status = 0";
        //专题名称
        if (!empty($param['name']) && $param['name'] !== '输入专题内容关键字') {
            $where .=  " and title like '%{$param['name']}%'";
        }

        //开始时间
    	if (!empty($param['start_time'])) {
        	$where .=  " and create_time >=".strtotime($param['start_time']);
    	}
		//结束时间
        if (!empty($param['end_time'])) {
            $where .=  " and create_time <=".(strtotime($param['end_time'])+3590*24);
        }
        
        // //专题类型
        // if (!empty($param['type'])) {
        //     $where .=  " and subject_type =".$param['type'];
        // }
        if (empty($param['field'])) {
         	$param['field'] = 'id';
       	}

		
		//数据总条数
		$this ->total = M()->Table('zt_subject_template') -> where ($where)-> count();
		//总页数
    	$this->pagecount = ceil(($this->total)/$pageNum);
      	if( $page  >= $this->pagecount){
			$page = $this->pagecount;
		}
        //得到该页数据
		$result = M()->Table('zt_subject_template')-> page($page)-> where($where)->field($param['field'])->order('create_time desc')->limit($pageNum)->select();
		//当前条数	
		$this->subtotal = count($result);
    	//完善数据
    	foreach ($result as $key => $val) {
          	if (empty($val['create_time'])) {
             	$result[$key]['create_time'] = '';
          	} else {
          	    $result[$key]['create_time'] = date('Y-m-d H:i:s',$val['create_time']);
          	}
          	//获得该专题的评论数量
          	$result[$key]['count'] = D('SubTem')->getCommentCount($val['id']);
          	//获得该专题的等待审核
          	$result[$key]['check'] = D('SubTem')->getCommentCheck($val['id']);
        	//作者
         	$result[$key]['username'] = M('uc_admin') -> where(array('id'=>$val['author'])) -> getField('username');
   		}

		return $result;
	}

	/**
	* 获得新专题列表
	*
	* $param array
	* 
	*/
	public function getNewSubjectList($param){
		//分页参数
        $page = isset($param['page']) ? $param['page'] : 1;
        $pageNum = isset($param['pageNum']) ? $param['pageNum'] : 10;
    	
    	$where = "status = 0";
        //专题名称
        if (!empty($param['name']) && $param['name'] !== '输入专题内容关键字') {
            $where .=  " and title like '%{$param['name']}%'";
        }

        //开始时间
    	if (!empty($param['start_time'])) {
        	$where .=  " and create_time >=".strtotime($param['start_time']);
    	}
		//结束时间
        if (!empty($param['end_time'])) {
            $where .=  " and create_time <=".(strtotime($param['end_time'])+3590*24);
        }
        
        // //专题类型
        // if (!empty($param['type'])) {
        //     $where .=  " and subject_type =".$param['type'];
        // }
        if (empty($param['field'])) {
         	$param['field'] = 'id';
       	}

		
		//数据总条数
		$this ->total = $this -> where ($where) -> count();
		//总页数
    	$this->pagecount = ceil(($this->total)/$pageNum);
      	if( $page  >= $this->pagecount){
			$page = $this->pagecount;
		}
        //得到该页数据
		$result = $this-> page($page)-> where($where)->field($param['field'])->order('create_time desc')->limit($pageNum)->select();
		// echo $this->getLastSql();
		//当前条数	
		$this->subtotal = count($result);
    	//完善数据
    	foreach ($result as $key => $val) {
          	if (empty($val['create_time'])) {
             	$result[$key]['create_time'] = '';
          	} else {
          	    $result[$key]['create_time'] = date('Y-m-d H:i:s',$val['create_time']);
          	}
        	//作者
         	$result[$key]['username'] = M('uc_admin') -> where(array('id'=>$val['uid'])) -> getField('username');
   		}

		return $result;
	}

	/**
	* 获得未审核的评论
	*
	* $param
	* 
	*/
	public function getCommentCheck($param){
		if(!empty($param)){
			$where = array('template_id'=>$param,'status'=>-1);
			$CommentCheck = M()->Table('zt_subject_comment')->where($where)->count();
		}
		
		return $CommentCheck;
	}
	/**
	* 获得评论列表
	*
	* $param
	* 
	*/
	public function getCommentList($param){
		//分页参数
        $page = isset($param['page']) ? $param['page'] : 1;
        $pageNum = isset($param['pageNum']) ? $param['pageNum'] : 30;
       
        //获得模板id
        if($param['id']){
        	$where = 'template_id = '.$param['id'];
        }else{
        	$where = '1';
        }
        //专题名称
        if (!empty($param['name']) && $param['name'] !== '输入评论内容关键字') {
        	
			$where .=  " and content like '%{$param['name']}%'";
        	
        }
       	//是否审核	
        if($param['type']){
        	if ($param['type'] == 1) {
        		$where .= " and status =0"; 
        	}else if($param['type'] == 2){
        		$where .= " and status =-1"; 
        	}
        	
        }
      
        //数据总条数
		$this ->commtotal = M()->Table('zt_subject_comment') -> where ($where) ->field('id') -> count();
		//总页数
    	$this->commpagecount = ceil(($this->commtotal)/$pageNum);
		if( $page  >= $this->commpagecount){
			$page = $this->commpagecount;
		}
        //得到该页数据
		$result = M()->Table('zt_subject_comment')->page ($page)->where ($where)->field($param['field'])->order('comment_time desc')->limit($pageNum)->select();
		//echo '<pre>';print_r($result); echo M()->getLastSql();
		//当前条数	
		$this->commsubtotal = count($result);

    	//完善数据
    	foreach ($result as $key => $val) {
          	if (empty($val['comment_time'])) {
	            $result[$key]['comment_time'] = '';
          	} else {
          	    $result[$key]['comment_time'] = date('Y-m-d H:i:s',$val['comment_time']);
          	}
        	//作者
         	$result[$key]['title'] = M('zt_subject_template') -> where(array('id'=>$val['template_id'])) -> getField('title');
         	//echo M()->getLastSql();echo '<pre>';print_r($result);exit;
   		}
		
		return $result;
	}

	/**
	* 获得该专题名
	*
	* $param 模板id
	* 
	*/
	public function getSubjectName($param){
		if(!empty($param)){
			$where = 'id = '.$param;
			$subjectName = M()->Table('zt_subject_template')->where($where)->getField('title');
		}
		return $subjectName;
	}


	/**
	* 获得专题评论数
	*
	* $param 	模板id
	* 
	*/
	public function getCommentCount($param){
		if(!empty($param)){
			$where = 'template_id = '.$param;
			$CommentCount = M()->Table('zt_subject_comment')->where($where)->count();
		}
		
		return $CommentCount;
	}

	/**
	 * 添加或编辑新数据	
	 *
	 * @param array 表单数据
	 *
	 *
	 * @$_FILES array 图片文件数据
	 * 			$ban_pic 大图
	 *  		$bg_pic	背景图片
	 *  		$head_pic 头部图片
	*/
	public function addNewSubject($param){
		$temData 	= $param['tem'];
		$moduleData	= $param['module'];

		// 分离模版数据
		$tem['title'] 		= trim($temData['title']);
		$tem['ban_pic'] 	= trim($temData['ban_pic']);
		$tem['ban_color'] 	= $temData['ban_color'];
		$tem['bg_pic'] 		= $temData['bg_pic'];
		$tem['bg_fcolor'] 	= $temData['bg_fcolor'];
		// 导航栏
		$tem['is_nav'] = 0;
		if($temData['is_nav']){
			$tem['is_nav'] 		= $temData['is_nav'];
			$tem['nav_color'] 	= $temData['nav_color'];
		}
		// 微博
		$tem['is_weibo'] = 0;
		if ($temData['is_weibo']) {
			$tem['is_weibo'] 	= $temData['is_weibo'];
			$tem['weibo_align'] = $temData['weibo_align'] ? $temData['weibo_align'] : 0;
			$tem['weibo_url'] 	= $temData['weibo_url'] ? trim($temData['weibo_url']) : '';
			// 检查微博代码 n是一栏，y是二栏
			$weiboCloumn = strpos(str_replace('<', '', $tem['weibo_url']),'column="n"');
			$weiboCloumy = strpos(str_replace('<', '', $tem['weibo_url']),'column="y"');
			if(($tem['weibo_align'] == 1 && $weiboCloumy) || ($tem['weibo_align'] == 2 && $weiboCloumn)){
				return array('status'=>'fail','msg'=>'您的微博代码与您选择的微博栏目不符！'); 
			}
		}
		
		// 问卷
		$tem['is_question'] = 0;
		if ($temData['is_question']) {
			$tem['is_question'] 	= $temData['is_question'];
			if(($tem['weibo_align']==1 && $tem['question_align']==2) || ($tem['weibo_align']==2 && $tem['question_align']==1)){
				return array('status'=>'fail','msg'=>'请选择正确的微博和问卷的显示方式！');
			}
			
			$tem['question_align'] 	= $temData['question_align'] ? $temData['question_align'] : 0;
			$tem['question_url'] 	= $temData['question_url'] ? trim($temData['question_url']) : '';
		}
		// 编辑
		if($temData['id']){
			$tem['update_time'] = time();
			$res = M('zt_template_new')->where('id = '.$temData['id'])->save($tem);
			// 删除所有相关模块和模块内容
			$midList = M('zt_template_module')->where('template_id='.$temData['id'])->getField('id',true);
			M('zt_template_module')->where('template_id='.$temData['id'])->delete();
			M('zt_template_module_content')->where('mid in ('.implode(',',$midList).')')->delete();
			$temId = $temData['id'];
			$msg = '编辑专题成功！';
		}else{ // 添加
			$tem['uid'] = session('boqiiUserId');
			$tem['create_time'] = time();
			$temId = M('zt_template_new')->add($tem);
			if(!$temId){
				return array('status'=>'fail','msg'=>'添加专题失败！');
			}
			$msg = '添加专题成功！';
		}
		// 添加模块及模块内容
		if(is_array($moduleData) && count($moduleData) > 0){
			foreach ($moduleData as $k => $v) {
				// 判断不同模块添加不同模块及内容
				if(is_array($v) && count($v) > 0){
					foreach ($v as $kk => $vv) {
						if(!empty($vv)){
							// 添加模块
							$moduleArr[$kk]['template_id'] = $temId;
							$moduleArr[$kk]['type'] 	   = $k;
							$moduleArr[$kk]['create_time'] = time();
							if($k){
								$moduleArr[$kk]['order']   = $vv['modid'];
							}
							// 模块标题有水平排列字段
							if ($k == 7) {
								$moduleArr[$kk]['align']   = $vv['align'] ? $vv['align'] : 0;
							}
							$mid = M('zt_template_module')->add($moduleArr[$kk]);
							unset($moduleArr);
							if(!empty($vv) && $mid){
								unset($vv['modid']);
								unset($vv['align']);
								// 这里判断不同的模块添加不同的模块内容数据
								$time = time();
								foreach ($vv as $i => $val) {
								 	if($val['tit'] || $val['pic'] || $val['link'] || $val['art'] || $val['tag']){
								 		if($val['tit']){
											$list[$i]['title'] 	  = trim($val['tit']);
										}
										if($val['tab']){
											$list[$i]['title'] 	  = trim($val['tab']);
										}
										if($val['pic']){
											$list[$i]['pic'] 	  = trim($val['pic']);
										}
										if($val['link']){
											$list[$i]['url'] 	  = trim($val['link']);
										}
										if($val['art']){
											$list[$i]['content']  = trim($val['art']);
										}
										
										$list[$i]['mid'] 		  = $mid;
										$list[$i]['create_time']  = $time;
										$cid = M('zt_template_module_content')->add($list[$i]);
									}
								} 
								unset($list);
							}
						}
					}
				}
			}
		}
		return array('status'=>'ok','msg'=>$msg);
	}
	/**
	 * 添加或编辑数据	
	 *
	 * @param array 表单数据
	 *
	 *
	 * @$_FILES array 图片文件数据
	 * 			$ban_pic 大图
	 *  		$bg_pic	背景图片
	 *  		$head_pic 头部图片
	*/
	public function addSubject($param){
	
		//分离数据，专题and内容
		foreach ($param as $key => $val) {
			if($key == 'tem'){
				//专题数据
				$tem[$key] = $val; 
			}elseif($key != 'id'){
				//内容数据
				$con[$key] = $val;
			}
		}
		if(!empty($tem['tem']['ban_h'])){
			$data['ban_h'] 	= trim($tem['tem']['ban_h']);
		}

		//判断全局数据
		if(empty($tem['tem']['title'])) {
			return '专题名称不能为空！';
		}else{
			$data['title'] 	= trim($tem['tem']['title']);

			if ($tem['tem']['ban_pic']) {
				$data['ban_pic']	= trim($tem['tem']['ban_pic']);
			}
			if ($tem['tem']['bg_pic']) {
				$data['bg_pic']	=  trim($tem['tem']['bg_pic']);
			}
			if ($tem['tem']['bg_ffamily']) {
				$data['bg_ffamily']	=  trim($tem['tem']['bg_ffamily']);
			}
			if ($tem['tem']['bg_fsize']) {
				$data['bg_fsize']	=  trim($tem['tem']['bg_fsize']);
			}
			if ($tem['tem']['bg_fcolor']) {
				$data['bg_fcolor']	=  trim($tem['tem']['bg_fcolor']);
			}
		}
		 //判断头部数据
		if($tem['tem']['head1']) {
			if (empty($tem['tem']['head_title'])) {
				return '头部标题不能为空！';
			}
			if (empty($tem['tem']['head_content'])) {
				return '头部内容不能为空！';
			}
			if (empty($tem['tem']['head_url'])) {
				return '头部链接地址不能为空！';
			}
			if (empty($tem['tem']['head_pic'])) {
				return '头部图片不能为空！';
			}
			$data['head1'] 			= trim($tem['tem']['head1']);
			$data['head_title'] 	= trim($tem['tem']['head_title']);
			$data['head_content'] 	= trim($tem['tem']['head_content']);
			$data['head_url'] 		= trim($tem['tem']['head_url']);
			$data['head_pic']	 	= trim($tem['tem']['head_pic']);
			if($tem['tem']['head_ffamily']){
				$data['head_ffamily'] 	= trim($tem['tem']['head_ffamily']);
			}
			if($tem['tem']['head_fsize']){
				$data['head_fsize'] 	= trim($tem['tem']['head_fsize']);
			}
			if($tem['tem']['head_fcolor']){
				$data['head_fcolor'] 	= trim($tem['tem']['head_fcolor']);
			}
			if($tem['tem']['head_bgcolor']){
				$data['head_bgcolor'] 	= trim($tem['tem']['head_bgcolor']);
			}
			
		}
		
		//选择投票 数据判断
		if($tem['tem']['vote']) {
			if (empty($tem['tem']['vote_title']) || empty($tem['tem']['vote_a']) || empty($tem['tem']['vote_b'])) {
				return '投票栏数据不能为空！';
			}
			$data['vote'] 		= trim($tem['tem']['vote']);
			$data['vote_title'] = trim($tem['tem']['vote_title']);
			$data['vote_a'] 	= trim($tem['tem']['vote_a']);
			$data['vote_b'] 	= trim($tem['tem']['vote_b']);
			if($tem['tem']['vote_anum']){
				$data['vote_anum'] 	= trim($tem['tem']['vote_anum']);
			}
			if($tem['tem']['vote_bnum']){
				$data['vote_bnum'] 	= trim($tem['tem']['vote_bnum']);
			}
			
		}
		//侧边栏样式 判断 如果存在就放进data
		if ($tem['tem']['sidebar_ffamily']) {
			$data['sidebar_ffamily'] = trim($tem['tem']['sidebar_ffamily']);
		}
		if ($tem['tem']['sidebar_fsize']) {
			$data['sidebar_fsize'] = trim($tem['tem']['sidebar_fsize']);
		}
		if ($tem['tem']['sidebar_fcolor']) {
			$data['sidebar_fcolor'] = trim($tem['tem']['sidebar_fcolor']);
		}
		if ($tem['tem']['sidebar_bgcolor']) {
			$data['sidebar_bgcolor'] = trim($tem['tem']['sidebar_bgcolor']);
		}
		if ($tem['tem']['sidebar_line']) {
			$data['sidebar_line'] = trim($tem['tem']['sidebar_line']);
		}
		//内容样式 判断 如果存在就放进data
		if ($tem['tem']['con_ffamily']) {
			$data['con_ffamily'] = trim($tem['tem']['con_ffamily']);
		}
		if ($tem['tem']['con_fsize']) {
			$data['con_fsize'] = trim($tem['tem']['con_fsize']);
		}
		if ($tem['tem']['con_fcolor']) {
			$data['con_fcolor'] = trim($tem['tem']['con_fcolor']);
		}
		if ($tem['tem']['con_bgcolor']) {
			$data['con_bgcolor'] = trim($tem['tem']['con_bgcolor']);
		}
		if ($tem['tem']['con_line']) {
			$data['con_line'] = trim($tem['tem']['con_line']);
		}
		
		//goto------------------------------------------------------------

		//添加
		if(!$param['id']){
			$data['author'] 		= session('boqiiUserId');
			$data['create_time'] 	= time();
			// 添加专题数据
			$template_id = M()->Table('zt_subject_template')-> add($data);
			if(!$template_id){
				echo "<script text='text/javascript'>alert('添加专题数据失败!请核对信息');history.back();</script>";
			}
			foreach ($_FILES as $k => $v) {
				if($k != 'con_pic'){
					unset($_FILES[$k]);
				}
			}
			//获得内容数据	
			$counts = count($con['con_title']);

			for ($i=0; $i < $counts; $i++) { 
				
				$list[$i]['con_title'] 		= $con['con_title'][$i];
				$list[$i]['con_url'] 		= $con['con_url'][$i];
				$list[$i]['content'] 		= $con['content'][$i];
				$list[$i]['template_id'] 	= $template_id;
				$list[$i]['create_time'] 	= time();
				
				//获得内容图片上传
				if ($_FILES['con_pic']['error'][$i] == 0) {
					$result = A('Image')->imageUpload('con_pic', 0, 'subtem', 'ajax' ,$i);
					$uploadinfo = json_decode($result, true);
					if($uploadinfo['status'] == 'ok') {
						$list[$i]['con_pic'] = $uploadinfo['imgpath'];
					}else {
						//通过内容url获得文章的标图
						$urlarr = substr($list[$i]['con_url'],strrpos($list[$i]['con_url'],'/')+1);
						$urlStr = explode('.', $urlarr);
						$pic_path = M()->Table('bk_article')->where('id='.$urlStr[0])->getField('pic_path');
						if(!empty($pic_path)){
							$list[$i]['con_pic'] = str_replace('_y', '_s', $pic_path);
							
						}
					}
				}else{
					//通过内容url获得文章的标图
					$urlarr = substr($list[$i]['con_url'],strrpos($list[$i]['con_url'],'/')+1);
					$urlStr = explode('.', $urlarr);
					$pic_path = M()->Table('bk_article')->where('id='.$urlStr[0])->getField('pic_path');
					if(!empty($pic_path)){
						$list[$i]['con_pic'] = str_replace('_y', '_s', $pic_path);
						
					}
				}
				
			}
			//添加专题数据
			
			$result = M()->Table('zt_subject_content')-> addAll($list);

		}else{	//编辑
			$data['author'] 		= session('boqiiUserId');
			$data['update_time'] 	= time();
			
			//添加专题数据
			$result = M()->Table('zt_subject_template')-> where(array('id'=>$param['id']))-> save($data);
			if(!$result){
				echo "<script text='text/javascript'>alert('更新专题数据失败!请核对信息');history.back();</script>";
			}
			foreach ($_FILES as $k => $v) {
				if($k != 'con_pic'){
					unset($_FILES[$k]);
				}
			}
			
			//获得内容数据	
			$counts = count($con['con_title']);
			// echo '<pre>';print_r($_FILES);exit;
					//die("<script>alert('". $counts . count($_FILES['con_pic']) ."');history.back();</script>");
			for ($i=0; $i < $counts; $i++) {
				//如果内容id存在编辑内容
				if($con['con_id'][$i]){
		
					$list[$i]['con_title']	 	= $con['con_title'][$i];
					$list[$i]['con_url']	 	= $con['con_url'][$i];
					$list[$i]['content']	 	= $con['content'][$i];
					$id							= $con['con_id'][$i];
					$list[$i]['update_time'] 	= time();
					
					//获得内容图片上传
					if ($_FILES['con_pic']['error'][$i] == 0) {
						$result = A('Image')->imageUpload('con_pic', 0, 'subtem', 'ajax' ,$i);
						
						$uploadinfo = json_decode($result, true);

						if($uploadinfo['status'] == 'ok') {
							$list[$i]['con_pic'] = $uploadinfo['imgpath'];	
						}
						else {
							// echo "<script>alert('".$uploadinfo['tip']."图片上传出错！');history.back();</script>";
							//通过内容url获得文章的标图
							$urlarr = substr($list[$i]['con_url'],strrpos($list[$i]['con_url'],'/')+1);
							$urlStr = explode('.', $urlarr);
							$pic_path = M()->Table('bk_article')->where('id='.$urlStr[0])->getField('pic_path');

							if(!empty($pic_path)){
								$list[$i]['con_pic'] = str_replace('_y', '_s', $pic_path);
								
							}
							// else{
							// 	$list[$i]['con_pic'] = '';
								
							// }
						}
					}
					$result = M()->Table('zt_subject_content') ->where(array('id'=>$id))-> save($list[$i]);
					if(!$result){
						die("<script>alert('专题第".$i."内容更新失败，请再次核对信息确认！');'</script>");
					}
				}else{
					//内容id不存在，添加内容
					if($con['con_title'][$i]){
						$res[$i]['con_title']	 = $con['con_title'][$i];
					}
					if($con['con_url'][$i]){
						$res[$i]['con_url']	 	= $con['con_url'][$i];
					}
					if($con['content'][$i] && trim($con['content'][$i]) != '请输入200字以内......'){
						$res[$i]['content']	 	= $con['content'][$i];
					}
					$res[$i]['template_id'] 	= $param['id'];
					$res[$i]['create_time'] 	= time();
					
					//获得内容图片上传
					//获得内容图片上传
					if ($_FILES['con_pic']['error'][$i] == 0) {
						$result = A('Image')->imageUpload('con_pic', 0, 'subtem', 'ajax' ,$i);
						$uploadinfo = json_decode($result, true);
						if($uploadinfo['status'] == 'ok') {
							$res[$i]['con_pic'] = $uploadinfo['imgpath'];
						}else {
							// die("<script>alert('".$uploadinfo['tip']."图片上传出错！');history.back();</script>");
							//通过内容url获得文章的标图
							$urlarr = substr($res[$i]['con_url'],strrpos($res[$i]['con_url'],'/')+1);
							$urlStr = explode('.', $urlarr);
							$pic_path = M()->Table('bk_article')->where('id='.$urlStr[0])->getField('pic_path');
							if(!empty($pic_path)){
								$res[$i]['con_pic'] = str_replace('_y', '_s', $pic_path);
								
							}
						}
					}else{
						//通过内容url获得文章的标图
						$urlarr = substr($res[$i]['con_url'],strrpos($res[$i]['con_url'],'/')+1);
						$urlStr = explode('.', $urlarr);
						$pic_path = M()->Table('bk_article')->where('id='.$urlStr[0])->getField('pic_path');
						if(!empty($pic_path)){
							$res[$i]['con_pic'] = str_replace('_y', '_s', $pic_path);
							
						}
					}
					
					$result = M()->Table('zt_subject_content')-> add($res[$i]);
					if(!$result){
						die("<script>alert('专题第".$i."个内容添加失败，请再次核对信息确认！');'</script>");
					}
				}
				
			}
		}
		return $result;
	}

	/**
	* 删除专题
	*
	* $id 专题id
	* 
	*/
    public function delList ($id) {
        if (is_array($id)) {
           	$where =array('id' => array('in', $id));
           	$res = array('template_id' => array('in', $id));
        } else {
           	$where = array('id' => array('in', "$id"));
            $res = array('template_id' => array('in', "$id"));
        }
        //删除专题
        $result = M()->Table('zt_subject_template') -> where($where) -> save(array('status'=> -1));
       
        //删除内容
        M()->Table('zt_subject_content') -> where($res) -> save(array('status'=> -1));
        
        //删除评论
		M()->Table('zt_subject_comment') -> where($res) -> save(array('status'=> -1));
	
        //GOTO 删除专题相关内容head2和相关评论
        if ($result !== FALSE) {
            $data =  1;
        } else {
            $data = 0;
        }
        return $data;
    }

    /**
	* 删除新专题
	*
	* $id 专题id
	* 
	*/
    public function delNewList ($id) {	
        if(!$id){
        	return 0;
        }
        $where 		= array('id' => array('in', $id));
        $where1 	= array('template_id' => array('in', $id));
        $midList 	= M('zt_template_module')->where($where1)->getField('id',true);
        $where3 	= array('mid'=>array('in',$midList));
       
        //删除专题
        $result = $this->where($where)->delete();
       
        //删除模块
        M()->Table('zt_template_module') -> where($where1) -> delete();
        
        //删除模块内容
		M()->Table('zt_template_module_content') -> where($where3) -> delete();
	
        //GOTO 删除专题相关内容head2和相关评论
        if ($result !== FALSE) {
            $data =  1;
        } else {
            $data = 0;
        }
        return $data;
    }
    /**
	* 删除专题评论
	*
	* $id int 评论id
	* 
	*/
    public function delComment ($id) {
        if (is_array($id)) {
            $where =array('id' => array('in', $id));

        } else {
            $where = array('id' => array('in', "$id"));
        }
        $result = M()->Table('zt_subject_comment') -> where($where) -> delete();
        
        if ($result !== FALSE) {
            $data =  1;
        } else {
            $data = 0;
        }
        return $data;
    }

    /**
	 * 根据新专题id获得数据
	 *
	 * $id int 专题id
	 * 
	 */
    public function getNewDetail ($id) {
        if (!$id) {
        	return array();
        }
        $info = $this->where('status = 0 and id = '.$id)->find();
        if(!$info){
        	return array();
        }
     	$moduleList = M('zt_template_module')->field('id,type,align,order')->where('template_id = '.$id)->order('`order`')->select();
     	if($moduleList){
     		foreach ($moduleList as $k => $val) {
	     		$subList = M('zt_template_module_content')->where('mid = '.$val['id'])->order('id')->select();
	     		if($val['type'] == 0){
	     			unset($moduleList[$k]);
	     			$info['navList'] = $val;
	     			$info['navList']['subList'] = $subList;
	     		}else{
	     			$moduleList['typeCount'][$val['type']]++;
	     			$moduleList[$k]['typeCount'] = $moduleList['typeCount'][$val['type']];	
	     			$moduleList[$k]['subList'] = $subList;
	     		}
	     	}
	     	$info['moduleList'] = $moduleList;	
     	}
     	// echo "<pre>";print_r($info);
     	return $info;
    }

}