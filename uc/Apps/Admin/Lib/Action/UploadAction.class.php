<?php
/**
 * 图片上传Action类
 *
 */
class UploadAction {

	/**
	 * 百度编辑器图片上传(百科文章,Using)
	 */
	public function bkArticleImageUpload() {
		// 百科文章
		$type = "article";

		//图片上传
		//$result = A('Image')->imageUpload('upfile', 0, $type, 'baidu');
		// 图片上传改用imagick处理
		$result = A('Image')->imageUpload('upfile', 0, $type, 'imagickBaidu');
		echo $result;
	} 

	/**
	 * 百度编辑器图片上传(百科小组介绍,Dropped)
	 */
	public function bkTeamImageUpload() {
		// 百科小组介绍
		$type = "team";

		//图片上传
		//$result = A('Image')->imageUpload('upfile', 0, $type, 'baidu');
		// 图片上传改用imagick处理
		$result = A('Image')->imageUpload('upfile', 0, $type, 'imagickBaidu');

		echo $result;
	} 

	/**
	 * 百度编辑器图片上传(百科宠物种类内容)
	 */
	public function bkPettypeImageUpload() {
		// 百科宠物种类内容
		$type = "pettype2";

		//图片上传
		//$result = A('Image')->imageUpload('upfile', 0, $type, 'baidu');
		// 图片上传改用imagick处理
		$result = A('Image')->imageUpload('upfile', 0, $type, 'imagickBaidu');
		echo $result;
	} 

	/**
	 * 百度编辑器图片上传（站内信）
	 */
	public function ucMsgUpload() {
		//站内信
		$type = "msg";

		//图片上传
		$result = A('Image')->imageUpload('upfile', 0, $type, 'baidu');
		echo $result;

	}

	/**
	 * 百度编辑器图片上传(资讯)
	 */
	public function vetNewsImageUpload() {
		// 百科文章
		$type = "news";

		//图片上传
		$result = A('Image')->imageUpload('upfile', 0, $type, 'baidu');
		echo $result;
	} 

	/**
	 * 异步图片上传
	 */
	public function imageUpload() {
		// 图片模块类型
		$subtype = $_POST['type'];
		// 使用imagick库处理图片或者gd库处理图片
		if(in_array($subtype, array('article', 'pettype', 'bbs_push', 'category', 'ads'))) {
			$method = 'imagick';
		} else {
			$method = 'ajax';
		}
		// 图片上传改用imagick处理
		$result = A('Image')->imageUpload('upload', 0, $subtype, $method);
		// 移动端直接返回图片绝对地址
		if(isset($_POST['from']) && $_POST['from'] == 'mobile') {
			$json = json_encode($result, true);
			echo $json['domain'] . '/' . $json['imgpath'];
		} else {
			echo $result;
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