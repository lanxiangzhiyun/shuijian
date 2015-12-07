<?php
/**
 * 图片上传Action类
 *
 */
class UploadAction extends BaseAction {

	/**
	 * 百度编辑器图片上传(日志)
	 */
	public function bdImageUpload() {
		// 日志
		$type = "diary";

		$userinfo = $this->_user;
		if(empty($userinfo)) {
			echo json_encode(array('status'=>'fail', 'state'=>'请登录！'));
		} else {
			$uid = $userinfo['uid'];
		}
		//图片上传
		$result = A('Image')->imageUpload('upfile', $uid, $type, 'baidu');
		echo $result;
	} 

	/**
	 * 异步图片上传
	 */
	public function imageUpload() {
		$uid = $_POST['uid'];
		if(!$uid) {
			$userinfo = $this->_user;
			if($userinfo) {
				$uid = $userinfo['uid'];
			} else {
				echo json_encode(array('status'=>'fail', 'state'=>'请登录！'));exit;
			}
		}
		//图片模块类型
		$subtype = $_POST['type'];
		//图片上传
		$result = A('Image')->imageUpload('upload', $uid, $subtype, 'ajax');
		if(isset($_POST['from']) && $_POST['from'] == 'mobile') {
			$json = json_encode($result, true);
			echo $json['domain'] . '/' . $json['imgpath'];exit;
		} else {
			echo $result;exit;
		}
	}

	/**
	 * 图片截取
	 *            图片实际宽度为imgw
	 *            图片实际高度为imgh
	 *            图片当前宽度为noww
	 *            图片当前高度为nowh
	 *            截图X坐标为px
	 *            截图Y坐标为py
	 *            截图宽度为pw
	 *            截图高度为ph
	 *            图片地址picurl
	 */
	public function imageIntercept() {
		//图片上传
		$result = A('Image')->imageIntercept($_POST);
		if($result) {
			echo $result;exit;
		} else {
			echo json_encode(array('status'=>'error', 'tip'=>'截图失败！'));
		}
	}

} 
?>