<?php
/**
 * 百科词条分类Action类
 *
 * @created 2014-09-28
 * @author Fongson
 */
class BkEntryCatAction extends ExtendAction {
	/*
	 * 初始化
	 */
	public function _initialize () {
		parent::_initialize();
	}

	/**
	 * 一级分类
	 */ 
	public function index () {
		$categoryModel = D('BkEntryCat');
		// 操作标志
		$action = $this->_param('action');
		// 当前分类层级
		$level = $this->_get('level');
		$arrAssign = array();
		// 指定一级分类的二级分类列表页
		if ($action == 'sub') {
			$parentId = $this->_get('id');
			//分页参数
			$url = '/iadmin.php/BkEntryCat/index/action/sub/level/' . $level . '?';
			$url .= 'id=' . $parentId . '&';
			$url .= 'p=';
			$pageP = intval($_GET['p']);
			$params['page'] = empty($pageP) ? 1 : $pageP;
			$params['page_num'] = 10;
			//分类id上一级获取下一级信部分字段信息
			$fields = 'id,name,introduce,entry_num,level,sort,parent_id,create_time,code';
			$arrAssign['arrAllTopList'] = $categoryModel->getSubList($parentId, $params, $fields);
			//分页参数
			$totalPage = $categoryModel->totalPage; //多少页
			$count = $categoryModel->count; //本页条数
			$pageHtml = $this->page($url, $totalPage, $params['page_num'], $params['page'], $count);
			$arrAssign['pageHtml'] = $pageHtml;
			$arrAssign['url'] = $url;
			//文字替换
			$arrAssign['lang'] = '二级分类';
			$arrAssign['parentId'] = $parentId;
			$arrAssign['action'] = $action;
		} 
		// 一级分类
		else if ($level == 1) {
			//分页参数
			$url = '/iadmin.php/BkEntryCat/index/level/1?';
			$url .= 'p=';
			$pageP = intval($_GET['p']);
			$params['page'] = empty($pageP) ? 1 : $pageP;
			$params['page_num'] = 10;
			$params['level'] = 1;
			$fields = 'id,name,introduce,entry_num,level,sort,create_time,code';
			//获取所有顶级
			$arrAssign['arrAllTopList'] = $categoryModel->getAllTopList($params, $fields);
			//分页参数
			$totalPage = $categoryModel->totalPage; //多少页
			$count = $categoryModel->count; //本页条数
			$pageHtml = $this->page($url, $totalPage, $params['page_num'], $params['page'], $count);
			$arrAssign['pageHtml'] = $pageHtml;
			$arrAssign['url'] = $url;
			//文字替换
			$arrAssign['lang'] = '一级分类';
			$arrAssign['action'] = $action;

		} 
		else if($level == 3) {
			//分页参数
			$url = '/iadmin.php/BkEntryCat/index/level/3?';
			$url .= 'p=';
			$pageP = intval($_GET['p']);
			$params['page'] = empty($pageP) ? 1 : $pageP;
			$params['page_num'] = 10;
			$params['level'] = $level;
			$fields = 'id,name,introduce,entry_num,level,sort,create_time,code';
			//获取所有顶级
			$arrAssign['arrAllTopList'] = $categoryModel->getAllTopList($params, $fields);
			//分页参数
			$totalPage = $categoryModel->totalPage; //多少页
			$count = $categoryModel->count; //本页条数
			$pageHtml = $this->page($url, $totalPage, $params['page_num'], $params['page'], $count);
			$arrAssign['pageHtml'] = $pageHtml;
			$arrAssign['url'] = $url;
			//文字替换
			$arrAssign['lang'] = '三级分类';
			$arrAssign['action'] = $action;
		}
		else {
			$arrAssign['lang'] = $level . '级分类';
			//TODO nothing
		}
		$arrAssign['level'] = $level;
		foreach ($arrAssign as $key => $val) {
			$this->assign($key, $val);
		}
		$this->display('index');
	}

	/**
	 * 编辑分类
	 */
	public function editor () {
		// 词条分类Model类实例化
		$categoryModel = D('BkEntryCat');
		// 页面变量
		$arrAssign = array();
		// 操作
		$action = $this->_param('action');
		// 参数
		$data = $this->_param('data');
		// 当前分类层级
		$level = $this->_param('level');
		$arrAssign['level'] = $level;
		// 分类id
		$id = $this->_param('id');
		$arrAssign['id'] = $id;

		// 上一级分类名称
		if ($level == 2) {
			//获取上一级分类name
			$intparentId = $categoryModel->getFieldById($id, 'parent_id');
			$arrAssign['strName'] = $categoryModel->getFieldById($intparentId, 'name');

		} else {
			$arrAssign['strName'] = $categoryModel->getFieldById($id, 'name');
		}
		// 编辑 界面
		if ($action == 'editor') {
			// 获取分类id 信息
			$arrInfo = D('BkEntryCat')->getInfo($id, 'id,name,introduce,entry_num,level,sort,create_time,code');
			$arrAssign['arrInfo'] = $arrInfo;
			$arrAssign['lang'] = '编辑分类';
			$arrAssign['action'] = 'editorok';
			
		} 
		// 添加一级分类 界面
		elseif ($action == 'add') {
			$arrAssign['lang'] = '添加一级分类';
			$arrAssign['action'] = 'addok';
		} 
		// 增加二级分类 界面
		elseif ($action == 'addnext') {
			$arrAssign['lang'] = '增加二级分类';
			$arrAssign['action'] = 'addnextok';
			//上一级分类名称
			$arrAssign['strName'] = $categoryModel->getFieldById($id, 'name');
		} 
		// 增加三级分类 界面
		elseif ($action == 'addthree') {
			$arrAssign['lang'] = '增加三级分类';
			$arrAssign['action'] = 'addthreeok';
			//上一级分类名称
			$arrAssign['strName'] = $categoryModel->getFieldById($id, 'name');
		} 
		// 增加一级分类 操作
		elseif ($action == 'addok') {
			// 分类名
			$data1['name'] = $data['name'];
			// 分类介绍
			$data1['introduce'] = $data['introduce'];
			// 分类层级
			$data1['level'] = 1;
			// 创建时间
			$data1['create_time'] = time();
			// 分类code
			$data1['code'] = $data['code'];
			if (empty($data['code'])) {
				echo "<script>alert('代号不能为空！');history.go(-1)</script>";
				exit;
			}
			// 检测分类code是否已存在
			$code = $categoryModel->getBoolCode($data['code']);
			if (!empty($code)) {
				echo "<script>alert('代号已经存在,换个别的吧~');history.go(-1)</script>";
				exit;
			}
			// 添加分类
			$status = $categoryModel->addCategory($data1);
			if ($status) {
				//记录log
				$this->recordOperations(1, 24, $status);
				$this->jump('添加成功！', '/iadmin.php/BkEntryCat/index/level/1');
			} else {
				echo "<script>alert('请重试！');history.go(-1)</script>";
			}
		} 
		// 增加二级分类 操作
		elseif ($action == 'addnextok') {
			// 分类名
			$data2['name'] = $data['name'];
			if (empty($data['name'])) {
				echo "<script>alert('分类名称不能为空！');history.go(-1)</script>";
				exit;
			}
			// 分类介绍
			$data2['introduce'] = $data['introduce'];
			if (empty($data['introduce'])) {
				echo "<script>alert('分类介绍不能为空！');history.go(-1)</script>";
				exit;
			}
			// 排序
			$data2['sort'] = $data['sort'];
			if (!is_numeric($data['sort'])) {
				echo "<script>alert('分类排序请填写数字！');history.go(-1)</script>";
				exit;
			}
			// 父分类id
			$data2['parent_id'] = $id;
			// 分类层级
			$data2['level'] = 2;
			// 分类代号
			$data2['code'] = $data['code'];
			if (empty($data['code'])) {
				echo "<script>alert('分类代号不能为空！');history.go(-1)</script>";
				exit;
			}
			// 检测分类代号是否已存在
			$code = $categoryModel->getBoolCode($data['code']);
			if (!empty($code)) {
				echo "<script>alert('代号已经存在,换个别的吧~');history.go(-1)</script>";
				exit;
			}
			// 创建时间
			$data2['create_time'] = time();
			// 添加二级分类
			$status = $categoryModel->addCategory($data2);
			if ($status) {
				//记录log
				$this->recordOperations(1, 24, $status);

				$this->jump('添加成功！', '/iadmin.php/BkEntryCat/subIndex/pid/'.$id);
			} else {
				echo "<script>alert('请重试！');history.go(-1)</script>";
			}
		} 
		// 增加三级分类 操作
		elseif ($action == 'addthreeok') {
			// 分类名
			if (empty($data['name'])) {
				echo "<script>alert('分类名称不能为空！');history.go(-1)</script>";
				exit;
			}
			$data2['name'] = $data['name'];
			// 分类介绍
			$data2['introduce'] = $data['introduce'];
			if (empty($data['introduce'])) {
				echo "<script>alert('分类介绍不能为空！');history.go(-1)</script>";
				exit;
			}
			// 分类排序
			$data2['sort'] = $data['sort'];
			if (!is_numeric($data['sort'])) {
				echo "<script>alert('排序请填写数字');history.go(-1)</script>";
				exit;
			}
			// 父分类id
			$data2['parent_id'] = $id;
			// 当前分类层级
			$data2['level'] = 3;
			// 分类代号
			$data2['code'] = $data['code'];
			if (empty($data['code'])) {
				echo "<script>alert('分类代号不能为空！');history.go(-1)</script>";
				exit;
			}
			// 检测分类代号是否已存在
			$code = $categoryModel->getBoolCode($data['code']);
			if (!empty($code)) {
				echo "<script>alert('代号已经存在,换个别的吧~');history.go(-1)</script>";
				exit;
			}
			// 创建时间
			$data2['create_time'] = time();
			// 保存分类
			$status = $categoryModel->addCategory($data2);
			if ($status) {
				// 记录log
				$this->recordOperations(1, 24, $status);
				$this->jump('添加成功！', '/iadmin.php/BkEntryCat/threeIndex/pid/'.$id);
			} else {
				echo "<script>alert('请重试！');history.go(-1)</script>";
			}
		} 
		// 编辑 操作
		elseif ($action == 'editorok') {
			// 分类id
			$data3['id'] = $id;
			// 分类名
			$data3['name'] = $data['name'];
			if (empty($data['name'])) {
				echo "<script>alert('分类名称不能为空！');history.go(-1)</script>";
				exit;
			}
			// 分类介绍
			$data3['introduce'] = $data['introduce'];
			if (empty($data['introduce'])) {
				echo "<script>alert('分类描述不能为空！');history.go(-1)</script>";
				exit;
			}
			// 分类层级
			if ($level != 1) {
				// 分类排序
				$data3['sort'] = $data['sort'];
				if (!is_numeric($data['sort'])) {
					echo "<script>alert('分类排序请填写数字！');history.go(-1)</script>";
					exit;
				}
			}
			// 更新时间
			$data3['update_time'] = time();
			// 记录log
			$fieldLog = array(
				'id' => array(
					'title' => '编号'
				),
				'name' => array(
					'title' => '分类名称'
				),
				'introduce' => array(
					'title' => '分类简介'
				),
				'sort' => array(
					'title' => '排序'
				),
				'update_time' => array(
					'title' => '更新时间'
				)
			);
			$this->groupTip('BkEntryCat', 'id', $id, $fieldLog, $data3, 24);
			// 更新分类
			$status = $categoryModel->updateCategory($data3);
			if ($status) {
				if ($level == 1) {
					$this->jump('编辑成功', '/iadmin.php/BkEntryCat/index/level/1');
				} else {
					$this->jump('编辑成功', '/iadmin.php/BkEntryCat/index/action/sub/id/' . $intparentId . '/level/2');
				}
			} else {
				echo "<script>alert('请重试');history.go(-1)</script>";
			}
//删除分类(暂时不用--Gavin)
//		} elseif ($action == 'delete') {
//			$status = $categoryModel->deleteCategory ($id);
//			if ($status) {
//				//获取小组 主键 id
//				$intTid = $categoryModel->getTeamPkId($id);
//				$arrTeam['id'] =  $intTid;
//				$arrTeam['status'] =  -1;
//				//删除小组
//				D('BkTeam')->saveTeamInfo($arrTeam);
//				//记录log
//				$this->recordOperations(2,24,$status);
//				$this-> jump('删除成功','/iadmin.php/BkEntryCat/index/level/1');
//			}else {
//				echo "<script>alert('请重试');history.go(-1)</script>";
//			}

		} else {
			//TODO nothing
		}


		foreach ($arrAssign as $key => $val) {
			$this->assign($key, $val);
		}
		$this->display('editor');
	}

	/**
	 * 单个页面 二级分类页面
	 */
	public function subIndex () {
		$arrAssign = array();
		
		// 当前页链接
		$url = '/iadmin.php/BkEntryCat/subIndex';

		$categoryModel = D('BkEntryCat');

		// 获取所有二级分类（分页）
		// 是否指定一级分类
		$parentId = $this->_get('pid') ? $this->_get('pid') : 0;
		if($parentId) {
			$params['parent_id'] = $parentId;
			$url .= '/pid/'.$parentId;
			$arrAssign['parentId'] = $parentId;
		}
		// 当前分类层级
		$level = 2; 
		$arrAssign['level'] = $level;
		$arrAssign['lang'] = '二级分类';
		$url .= '/level/'.$level;

		$url .= '?p=';
		// 当前页码
		$params['page'] = isset($_GET['p']) ? intval($_GET['p']) : 1;
		// 页显数量
		$params['page_num'] = 50;
		// 当前层级
		$params['level'] = $level;
		// 读取字段
		$fields = 'id,name,introduce,entry_num,level,sort,create_time,code,parent_id';
		// 获取分类列表数据
		$arrAssign['arrAllTopList'] = $categoryModel->getAllTopList($params, $fields);
		// 分页参数
		$totalPage = $categoryModel->totalPage; //多少页
		$count = $categoryModel->count; //本页条数
		$pageHtml = $this->page($url, $totalPage, $params['page_num'], $params['page'], $count);
		$arrAssign['pageHtml'] = $pageHtml;
		$arrAssign['url'] = $url;

		// 页面变量赋值
		foreach ($arrAssign as $key => $val) {
			$this->assign($key, $val);
		}
		$this->display('subIndex');
	}

	/**
	 * 所有三级分类列表页面
	 */
	public function threeIndex() {
		// 页面变量
		$arrAssign = array();
		// 当前页链接
		$url = '/iadmin.php/BkEntryCat/threeIndex';
		// 词条分类Model类实例化
		$categoryModel = D('BkEntryCat');
		// 是否指定上级分类
		$parentId = $this->_get('pid') ? $this->_get('pid') : 0;
		if($parentId) {
			$params['parent_id'] = $parentId;
			$url .= '/pid/'.$parentId;
			$arrAssign['parentId'] = $parentId;
		}
		// 当前分类层级
		$level = 3; 
		$arrAssign['level'] = $level;
		$arrAssign['lang'] = '三级分类';
		// 当前分类层级
		$url .= '/level/'.$level;

		$url .= '?p=';

		// 获取所有三级分类（分页）
		// 当前页码
		$params['page'] = isset($_GET['p']) ? intval($_GET['p']) : 1;
		// 页显数量
		$params['page_num'] = 50;
		$params['level'] = $level;
		$fields = 'id,name,introduce,entry_num,level,sort,create_time,code,parent_id';
		// 获取
		$arrAssign['arrAllTopList'] = $categoryModel->getAllTopList($params, $fields);
		// 分页参数
		$totalPage = $categoryModel->totalPage; //多少页
		$count = $categoryModel->count; //本页条数
		$pageHtml = $this->page($url, $totalPage, $params['page_num'], $params['page'], $count);
		$arrAssign['pageHtml'] = $pageHtml;
		$arrAssign['url'] = $url;

		// 页面变量赋值
		foreach ($arrAssign as $key => $val) {
			$this->assign($key, $val);
		}
		$this->display('threeIndex');
	}

	/**
	 * 编辑二级/三级分类
	 */
	public function editorSub () {
		// 词条分类Model类实例化
		$categoryModel = D('BkEntryCat');
		// 操作标志
		$action = $this->_param('action');
		$arrAssign = array();
		// 参数数组
		$data = $this->_param('data');
		// 分类id
		$id = $this->_param('id');
		$arrAssign['id'] = $id;
		// 获取上一级分类name
		$intparentId = $categoryModel->getFieldById($id, 'parent_id');
		$arrAssign['strName'] = $categoryModel->getFieldById($intparentId, 'name');

		// 二级/三级分类编辑 操作
		if ($action == 'subChildEdit') {
			// 分类id
			$data1['id'] = $id;
			// 分类名
			if (empty($data['name'])) {
				echo "<script>alert('分类名称不能为空！');history.go(-1)</script>";
				exit;
			}
			$data1['name'] = $data['name'];
			// 分类代号
			if (empty($data['code'])) {
				echo "<script>alert('分类代号不能为空！');history.go(-1)</script>";
				exit;
			}
			$data1['code'] = $data['code'];
			// 分类介绍
			if (empty($data['introduce'])) {
				echo "<script>alert('分类介绍不能为空！');history.go(-1)</script>";
				exit;
			}
			$data1['introduce'] = $data['introduce'];
			// 分类排序
			if (!is_numeric($data['sort'])) {
				echo "<script>alert('分类排序请填写数字！');history.go(-1)</script>";
				exit;
			}
			$data1['sort'] = $data['sort'];
			// 更新时间
			$data1['update_time'] = time();
			// 记录log
			$fieldLog = array(
				'id' => array(
					'title' => '编号'
				),
				'name' => array(
					'title' => '分类名称'
				),
				'code' => array(
					'title' => '分类代号'
				),
				'introduce' => array(
					'title' => '分类简介'
				),
				'sort' => array(
					'title' => '排序'
				),
				'update_time' => array(
					'title' => '更新时间'
				)
			);
			$this->groupTip('BkEntryCat', 'id', $id, $fieldLog, $data1, 24);

			// 更新
			$status = $categoryModel->updateCategory($data1);
			if ($status) {
				// 当前分类层级
				$level = $categoryModel->getFieldById($id, 'level');
				if($level == 2) {
					$this->jump('编辑成功！', '/iadmin.php/BkEntryCat/subIndex/pid/'.$intparentId);
				} elseif($level == 3) {
					$this->jump('编辑成功！', '/iadmin.php/BkEntryCat/threeIndex/pid/'.$intparentId);
				}
			} else {
				echo "<script>alert('请重试！');history.go(-1)</script>";
			}
		} 
		// 二级/三级分类编辑界面
		else if ($action == 'subChild') {
			// 操作标志
			$arrAssign['action'] = 'subChildEdit';
			// 获取分类id 信息
			$arrInfo = D('BkEntryCat')->getInfo($id, 'id,name,introduce,entry_num,level,sort,create_time,code,parent_id');

			$arrAssign['arrInfo'] = $arrInfo;
		}


		// 变量assign
		foreach ($arrAssign as $key => $val) {
			$this->assign($key, $val);
		}

		$this->display('subChild');
	}


	function jump ($mess = '', $url = '') {
		if (!empty($mess))
			echo  "<script>alert('" . $mess . "')</script>";
		if (!empty($url))
			echo "<script>location.href='" . $url . "'</script>";
	}
}
