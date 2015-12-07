<?php
/**
 * 宠物种类分类控制器
 *
 * @author: yumie
 * @created: 13-9-26
 */
class PetCategoryAction extends ExtendAction {
	/*
	*初始化
	*/
	public function _initialize () {
		parent::_initialize();
	}

	public function index () {
		$categoryModel = D('BkPetCategory');
		$action = $this->_param('action');
		$level = $this->_get('level');
		$arrAssign = array();
		if ($action == 'sub' && $level == 2) {
			$parentId = $this->_get('id');
			//分页参数
			$url = '/iadmin.php/PetCategory/index/action/sub/level/2?';
			$url .= 'id=' . $parentId . '&';
			$url .= 'p=';
			$pageP = intval($_GET['p']);
			$params['page'] = empty($pageP) ? 1 : $pageP;
			$params['page_num'] = 10;
			//分类id上一级获取下一级信部分字段信息
			$fields = 'id,name,parent_id,create_time';
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
		} else if ($level == 1) {
			//分页参数
			$url = '/iadmin.php/PetCategory/index/level/1?';
			$url .= 'p=';
			$pageP = intval($_GET['p']);
			$params['page'] = empty($pageP) ? 1 : $pageP;
			$params['page_num'] = 10;
			$params['parent_id'] = 0;
			$fields = 'id,name,create_time';
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

		} else {
			$arrAssign['lang'] = $level . '级分类';
			//TODO nothing
		}
		$arrAssign['level'] = $level;
		foreach ($arrAssign as $key => $val) {
			$this->assign($key, $val);
		}
		$this->display('index');
	}

	//编辑分类
	public function editor () {
		$categoryModel = D('BkPetCategory');
		$arrAssign = array();
		$action = $this->_param('action');
		$data = $this->_param('data');
		$level = $this->_param('level');
		//分类id
		$id = $this->_param('id');
		$arrAssign['id'] = $id;
		$arrAssign['level'] = $level;
		//上一级分类名称
		if ($level == 2) {
			//获取上一级分类name
			$intparentId = $categoryModel->getFieldById($id, 'parent_id');
			$arrAssign['strName'] = $categoryModel->getFieldById($intparentId, 'name');

		} else {
			$arrAssign['strName'] = $categoryModel->getFieldById($id, 'name');
		}
		//编辑 界面
		if ($action == 'editor') {
			//获取分类id 信息
			$arrInfo = D('BkPetCategory')->getInfo($id, 'id,name,create_time');
			$arrAssign['arrInfo'] = $arrInfo;
			$arrAssign['lang'] = '编辑分类';
			$arrAssign['action'] = 'editorok';
		//添加一级分类 界面
		} elseif ($action == 'add') {
			$arrAssign['lang'] = '添加一级分类';
			$arrAssign['action'] = 'addok';
		//增加二级分类 界面
		} elseif ($action == 'addnext') {
			$arrAssign['lang'] = '增加二级分类';
			$arrAssign['action'] = 'addnextok';
			//上一级分类名称
			$arrAssign['strName'] = $categoryModel->getFieldById($id, 'name');
		//增加一级分类 操作
		} elseif ($action == 'addok') {
			$data1['name'] = $data['name'];
			$data1['create_time'] = time();
			$data1['update_time'] = time();
			$status = $categoryModel->addCategory($data1);
			if ($status) {
				//记录log
				$this->recordOperations(1, 31, $status);
				$this->jump('添加成功', '/iadmin.php/PetCategory/index/level/1');
			} else {
				echo "<script>alert('请重试');history.go(-1)</script>";
			}
		//增加二级分类 操作
		} elseif ($action == 'addnextok') {
			$data2['name'] = $data['name'];
			$data2['parent_id'] = $id;
			$data2['create_time'] = time();
			$data2['update_time'] = time();
			if (empty($data['name'])) {
				echo "<script>alert('名称不能为空');history.go(-1)</script>";
				exit;
			}
			$status = $categoryModel->addCategory($data2);
			if ($status) {
				//记录log
				$this->recordOperations(1, 31, $status);
				$this->jump('添加成功', '/iadmin.php/PetCategory/index/level/1');
			} else {
				echo "<script>alert('请重试');history.go(-1)</script>";
			}
		//编辑 操作
		} elseif ($action == 'editorok') {
			$data3['id'] = $id;
			$data3['name'] = $data['name'];
			$data3['update_time'] = time();
			if (empty($data['name'])) {
				echo "<script>alert('名称不能为空');history.go(-1)</script>";
				exit;
			}
			//记录log
			$fieldLog = array(
				'id' => array(
					'title' => '编号'
				),
				'name' => array(
					'title' => '分类名称'
				),
				'update_time' => array(
					'title' => '更新时间'
				)
			);
			$this->groupTip('BkPetCategory', 'id', $id, $fieldLog, $data3, 31);
			$status = $categoryModel->updateCategory($data3);
			if ($status) {
				if ($level == 1) {
					$this->jump('编辑成功', '/iadmin.php/PetCategory/index/level/1');
				} else {
					$this->jump('编辑成功', '/iadmin.php/PetCategory/index/action/sub/id/' . $intparentId . '/level/2');
				}
			} else {
				echo "<script>alert('请重试');history.go(-1)</script>";
			}
			/*	删除分类(暂时不用--Gavin)
			} elseif ($action == 'delete') {
				$status = $categoryModel->deleteCategory ($id);
				if ($status) {
					记录log
					$this->recordOperations(2,24,$status);
					$this-> jump('删除成功','/iadmin.php/PetCategory/index/level/1');
				}else {
					echo "<script>alert('请重试');history.go(-1)</script>";
				}*/

		} else {
			//TODO nothing
		}


		foreach ($arrAssign as $key => $val) {
			$this->assign($key, $val);
		}
		$this->display('editor');
	}

	//单个页面 二级分类页面
	public function subIndex () {
		$categoryModel = D('BkPetCategory');
		$arrAssign = array();
		//获取所有二级分类（分页）
		//分页参数
		$url = '/iadmin.php/PetCategory/subIndex?';
		$url .= 'p=';
		$pageP = intval($_GET['p']);
		$params['page'] = empty($pageP) ? 1 : $pageP;
		$params['page_num'] = 50;
		$params['level'] = 2;
		$fields = 'id,name,create_time';
		//获取所有顶级
		$arrAssign['arrAllTopList'] = $categoryModel->getAllTopList($params, $fields);
		//分页参数
		$totalPage = $categoryModel->totalPage; //多少页
		$count = $categoryModel->count; //本页条数
		$pageHtml = $this->page($url, $totalPage, $params['page_num'], $params['page'], $count);
		$arrAssign['pageHtml'] = $pageHtml;
		$arrAssign['url'] = $url;
		foreach ($arrAssign as $key => $val) {
			$this->assign($key, $val);
		}
		$this->display('subIndex');
	}

	//编辑二级分类
	public function editorSub () {

		$categoryModel = D('BkPetCategory');
		$action = $this->_param('action');
		$arrAssign = array();
		$data = $this->_param('data');
		$id = $this->_param('id');
		$arrAssign['id'] = $id;
		//获取上一级分类name
		$intparentId = $categoryModel->getFieldById($id, 'parent_id');
		$arrAssign['strName'] = $categoryModel->getFieldById($intparentId, 'name');
		//二级分类编辑 操作
		if ($action == 'subChildEdit') {
			$data1['id'] = $id;
			$data1['name'] = $data['name'];
			$data1['update_time'] = time();
			if (empty($data['name'])) {
				echo "<script>alert('名称不能为空');history.go(-1)</script>";
				exit;
			}
			//记录log
			$fieldLog = array(
				'id' => array(
					'title' => '编号'
				),
				'name' => array(
					'title' => '分类名称'
				)
			);
			$this->groupTip('BkPetCategory', 'id', $id, $fieldLog, $data1, 31);
			$status = $categoryModel->updateCategory($data1);
			if ($status) {
				$this->jump('编辑成功', '/iadmin.php/PetCategory/subIndex/');
			} else {
				echo "<script>alert('请重试');history.go(-1)</script>";
			}

		//二级分类编辑 界面
		} else if ($action == 'subChild') {
			$arrAssign['action'] = 'subChildEdit';
			//获取分类id 信息
			$arrInfo = D('BkPetCategory')->
				getInfo($id, 'id,name,create_time');
			$arrAssign['arrInfo'] = $arrInfo;
		}


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
?>