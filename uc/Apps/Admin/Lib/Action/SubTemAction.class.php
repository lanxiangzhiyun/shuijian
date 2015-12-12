<?php
	/**
	* 专题模板控制器
	*
	*+------------------------------------------------------------------------
	* @author: jasonjiang
	*+------------------------------------------------------------------------
	* @date: 2014/08/18
	*+------------------------------------------------------------------------
	*/
	class SubTemAction extends ExtendAction{

		/**
		* 专题模板管理列表
		*
		*/
		public function index(){

			//获得数据库数据
			$subject = D('SubTem');
			
          	$data = $this -> _get('data');
          	//url地址
          	$url='/iadmin.php/SubTem/index?';
          	//搜索条件
          	if($data['name'] && !in_array(trim($data['name']),'输入专题内容关键字')){
              	$url.='data[name]='.urlencode($data['name']).'&';
              	$this->assign('name',$data['name']);
			}
			if($data['start_time']){
			  	$url.='data[start_time]='.$data['start_time'].'&';
			  	$this->assign('start_time',$data['start_time']);
			}
			if($data['end_time']){
			  	$url.='data[end_time]='.$data['end_time'].'&';
			  	$this->assign('end_time',$data['end_time']);
			}
			
         	//分页参数
         	$data['page']= isset($_GET['page']) ? $_GET['page'] : 1;
          	$data['pageNum'] = 10;
          	//查询字段
          	$data['field'] = 'id,title,create_time,author,subject_type,click_num';
			$subjectList = $subject->getSubjectList($data);

			$url .= 'page=';
			if($data['page'] >= $subject->pagecount){
				$data['page']  = $subject->pagecount;
			}

			$pageHtml = $this->page($url,$subject->pagecount, $data['pageNum'],$data['page'],$subject->subtotal);
			$this->assign('page',$data['page']);
          	if($subject->pagecount > 1){
				$this->assign('pageHtml',$pageHtml);
			}
			$this->assign('subjectList',$subjectList);

			$this->display();
		}

		/**
		* 专题模板管理列表
		*
		*/
		public function newIndex(){

			//获得数据库数据
			$subject = D('SubTem');
			
          	$data = $this -> _get('data');
          	//url地址
          	$url='/iadmin.php/SubTem/newIndex?';
          	//搜索条件
          	if($data['name'] && !in_array(trim($data['name']),'输入专题内容关键字')){
              	$url.='data[name]='.urlencode($data['name']).'&';
              	$this->assign('name',$data['name']);
			}
			if($data['start_time']){
			  	$url.='data[start_time]='.$data['start_time'].'&';
			  	$this->assign('start_time',$data['start_time']);
			}
			if($data['end_time']){
			  	$url.='data[end_time]='.$data['end_time'].'&';
			  	$this->assign('end_time',$data['end_time']);
			}
			
         	//分页参数
         	$data['page']= isset($_GET['page']) ? $_GET['page'] : 1;
          	$data['pageNum'] = 10;
          	//查询字段
          	$data['field'] = 'id,title,create_time,uid,click_num';
			$subjectList = $subject->getNewSubjectList($data);

			$url .= 'page=';
			if($data['page'] >= $subject->pagecount){
				$data['page']  = $subject->pagecount;
			}

			$pageHtml = $this->page($url,$subject->pagecount, $data['pageNum'],$data['page'],$subject->subtotal);
			$this->assign('page',$data['page']);
          	if($subject->pagecount > 1){
				$this->assign('pageHtml',$pageHtml);
			}
			$this->assign('subjectList',$subjectList);

			$this->display('newIndex');
		}

		

		/**
		* 该专题评论显示
		*	
		*/
		public function comment(){
			//获得数据库数据
			$subject = D('SubTem');
			$data = $this->_get('data');

			$data['id'] = $_GET['id'];
			
			//设置分页初始值
			$data['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
			$data['pageNum'] = 10;

			//url地址
          	$url='/iadmin.php/SubTem/comment?';
          	if ($data['id']){
			  	$url.='id='.$data['id'].'&';
			}
			//搜索条件
          	if($data['name'] && !in_array(trim($data['name']),'输入评论内容关键字')){
              	$url.='data[name]='.urlencode($data['name']).'&';
              	$this->assign('name',$data['name']);
			}
          	if($data['type']){
          		$url.='data[type]='.$data['type'].'&';
			  	$this->assign('type',$data['type']);

          	}
          
			//相关字段
			$data['field'] = 'id,content,comment_time,commenter_id,status,address';

			$url .= 'page=';
			//echo '<pre>';print_r($url);
			//获取评论列表
			$commentList = $subject->getCommentList($data);
			//如果超过页面数据则为最大
			if( $data['page']  >= $subject->commpagecount){
				$data['page'] = $subject->commpagecount;
			}
			//获取专题名
			$subjectName = $subject->getSubjectName($data['id']);
			$pageHtml = $this->page($url,$subject->commpagecount, $data['pageNum'],$data['page'],$subject->commsubtotal);

			$this->assign('page',$data['page']);
			$this->assign('id',$data['id']);
			$this->assign('commentList',$commentList);
			$this->assign('subjectName',$subjectName);
			//页数分页
			if($subject->commpagecount > 1){
				$this->assign('pageHtml',$pageHtml);
			}
			$this->display();
		}
		
		/**
		* 添加专题模板显示
		*
		*/
		public function addSubject(){
			$id = $this -> _get('id');
			$arrAssign['id'] = $id;
			if (intval($id)){
	           	$arrAssign['tip'] = '编辑专题';
	            //获取专题
	           	$arrInfo =  M()->Table('zt_subject_template') -> where (array('id'=>$id,'status'=>0)) -> find();
	           	$contentInfo = M()->Table('zt_subject_content') -> where (array('template_id'=>$id,'status'=>0)) -> order('id') -> select();
	           	 //echo '<pre>';echo M()->getLastSql();print_r($contentInfo);exit;
	            $arrAssign['arrInfo'] = $arrInfo;
	            $arrAssign['contentInfo'] = $contentInfo;
	           
	        } else {
	           $arrAssign['tip'] = '新增专题';
	        }

	        foreach ($arrAssign as $key => $val) {
	            $this -> assign($key,$val);
	        }
			$this->display('addSubject');
		}

		
		/**
		* 添加或编辑专题数据
		*	$id 
		*/
		public function saveSubject(){

			$param = $this->_post();
			// echo '<pre>'; print_r($param);exit;
			$subject = D('SubTem');
			$result = $subject->addSubject($param);
			
			if ($result) {
				if(!is_int($result)){
					echo "<script text='text/javascript'>alert('".$result."!');history.back();</script>";
				}else{
					echo "<script text='text/javascript'>alert('操作成功！');location.href='/iadmin.php/SubTem/index'</script>";
				}
			}else {
			   echo "<script text='text/javascript'>alert('专题部分内容无法添加');location.href='/iadmin.php/SubTem/index'</script>";
			}

		}

		/**
		 * 添加新专题模板显示
		 *
		 */
		public function addNewSubject(){
			$id = $this -> _get('id');
			$arrAssign['id'] = $id;
			if (intval($id)){
	           	$arrAssign['tip'] = '编辑新专题';
	            //获取专题
	           	$arrAssign['temInfo'] = D('SubTem')->getNewDetail($id);
	           	// echo "<pre>";print_r($arrAssign['temInfo']);exit;
	        } else {
	           $arrAssign['tip'] = '新增新专题';
	        }
	        foreach ($arrAssign as $key => $val) {
	            $this -> assign($key,$val);
	        }
			$this->display('addNewSubject');
		}

		/**
		 * 添加或编辑新专题数据
		 *	$id 
		 */
		public function saveNewSubject(){
			
			$param = $this->_post();
			$subject = D('SubTem');
			// 判断条件
			if (empty($param['tem']['title'])) {
				alert('专题名称不能为空');
			}
			$result = $subject->addNewSubject($param);
			
			if ($result['status'] == 'ok') {
				// 添加日志

				showmsg($result['msg'],'/iadmin.php/SubTem/newIndex');
			}
			alert($result['msg']);
			

		}
		/**
		* 删除专题内容
		*	
		*/
		public function ajaxDelContent(){
			$id = $this->_get('id');
			if($id){
				$where = array('id'=>$id);
				$result = M()->Table('zt_subject_content') -> where($where) -> save(array('status'=> -1));
			}
			if($result){
				$this->ajaxReturn('ok','JSON');
			}else{
				$this->ajaxReturn('false','JSON');
			}
		}	
		/**
		* ajax删除专题数据
		*	
		*/
		public function ajaxDelSubject(){
			$subjectModel = D('SubTem');
	        $ids = $this->_get('ajaxDelSubject');
	        $act = $this->_get('act');
	        $page = $this->_get('page');
	        $idArr = array_filter(explode(',',$ids));

	        $result =$subjectModel -> delList ($idArr) ;
	      
	        if(empty($act)){
	            echo "<script>location.href='/iadmin.php/SubTem/index';</script>";
	        }else{
	            echo 1;
	            exit;
	        }
		}

		/**
		* ajax删除新专题数据
		*	
		*/
		public function ajaxDelNewSubject(){
			$subjectModel = D('SubTem');
	        $ids = $this->_get('ajaxDelNewSubject');
	        $act = $this->_get('act');
	        $page = $this->_get('page');
	        $idArr = array_filter(explode(',',$ids));
	        
	        $result = $subjectModel -> delNewList ($idArr) ;
	       	// 日志记录
	      	// $this->recordOperations(2,21,$val);
	        if(empty($act)){
	            echo "<script>location.href='/iadmin.php/SubTem/index';</script>";
	        }else{
	            echo 1;
	            exit;
	        }
		}
		/**
		* ajax删除专题评论
		*	
		*/
		public function ajaxDelComment(){
			$subjectModel = D('SubTem');
	        $ids = $this->_get('ajaxDelComment');
	        $act = $this->_get('act');
	        $page = $this->_get('page');
	        $idArr = array_filter(explode(',',$ids));

	        $result =$subjectModel -> delComment ($idArr) ;
	        if(empty($act)){
	            echo "<script>location.href='/iadmin.php/SubTem/comment';</script>";
	        }else{
	            echo 1;
	            exit;
	        }
		}
		/**
		* 所有评论显示页面
		*	
		*/
		public function commentList(){
			//获得数据库数据
			$subject = D('SubTem');
			$data = $this->_get('data');
			//设置分页初始值
			$data['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
			$data['pageNum'] = 10;

			$url='/iadmin.php/SubTem/commentList?';
			
          	//搜索条件
          	if($data['name'] && !in_array(trim($data['name']),'输入评论内容关键字')){
              	$url.='data[name]='.urlencode($data['name']).'&';
              	$this->assign('name',$data['name']);
			}
			if($data['start_time']){
			  	$url.='data[start_time]='.$data['start_time'].'&';
			  	$this->assign('start_time',$data['start_time']);
			}
			if($data['end_time']){
			  	$url.='data[end_time]='.$data['end_time'].'&';
			  	$this->assign('end_time',$data['end_time']);
			}
          	if($data['type']){
          		$url.='data[type]='.$data['type'].'&';
			  	$this->assign('type',$data['type']);

          	}
          	$this->assign('data',$data);
			//相关字段
			$data['field'] = 'id,template_id,content,comment_time,commenter_id,status,address';

			$url .= 'page=';
			//echo '<pre>';print_r($url);
			//获取评论列表
			$commentList = $subject->getCommentList($data);
			if( $data['page']  >= $subject->commpagecount){
				$data['page'] = $subject->commpagecount;
			}
			//分页
			$pageHtml = $this->page($url,$subject->commpagecount, $data['pageNum'],$data['page'],$subject->commsubtotal);

			$this->assign('page',$data['page']);
			$this->assign('id',$data['id']);
			$this->assign('commentList',$commentList);

			if($subject->commpagecount > 1){
				$this->assign('pageHtml',$pageHtml);
			}
			$this->display();
		}

		/**
		* 该专题评论是否审核
		*	
		*/
		public function ajaxCheckComm(){
			$id 		= $this->_post('id');		//专题id
			
			$where = array('id'=>$id);
			$result = M()->Table('zt_subject_comment')->where($where)->save(array('status'=>0));
			echo $result;
		}
	}