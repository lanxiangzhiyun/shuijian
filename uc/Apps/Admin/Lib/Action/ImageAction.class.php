<?php
/**
 * 图片处理接口
 *
 * @author: Fongson
 * @created: 2013-08-08
 */
class ImageAction {

    /**
     * 图片上传接口
	 *
	 * @param filename string 文件名(upfile/upload/...)
	 * @param uid int 用户ID(如果图片目录不需要用用户id三级扩展则传0)
	 * @param subtype string 子模块类型
	 * @param method string 提交方法(ajax:异步提交;baidu:百度编辑器;intercept)
	 * @param id int 提交同数组图片所需，用于循环
	 * 
     */
    public function imageUpload($filename, $uid, $subtype, $method, $id = -1) {
    	//需要上传的文件
    	if($subtype == 'subtem' && $id > -1){
    		$_FILE	= $_FILES[$filename]["tmp_name"][$id];
    	}else{
    		$_FILE	= $_FILES[$filename]["tmp_name"];
    	}

		$post_data = array(
			'id' => $uid,
			'type'=>1,
			'aucode' => "boqii",
			'subtype' => $subtype,
			'method' => $method,
			'upfile'=>"@".$_FILE,//绝对路径
		 );
		$url = C('IMG_UPLOAD_DIR') ."/Server/upload.php"; 
		$result = post_url($url,$post_data);

		if($result && (strpos($result, '{') === false)) {
			if($method == "baidu") {
				$json = array('status'=>'fail', 'state'=>'上传失败！');
			} else {
				$json = array('status'=>'error', 'tip'=>'上传失败！');
			}
		} else {
			$json = json_decode($result, true);
		}
		if(($method == "baidu" || $method == "imagickBaidu") && $json) {
			// 原始文件名，表单名固定，不可配置
			$json['oriName'] = htmlspecialchars($_POST['fileName'], ENT_QUOTES); 
			// 上传图片框中的描述表单名称，
			$json['title'] = htmlspecialchars($_POST['pictitle'], ENT_QUOTES); 
		}
		return json_encode($json);
    }

	/**
	 * 图片截取
	 * @param $imgh 	原始图片的高
	 * @param $imgw 	原始图片的宽
	 * @param $nowh 	截取之后图片的高
	 * @param $noww 	截取之后图片的宽
	 * @param $ph 		截图高度为ph
	 * @param $picurl 	图片地址picurl
	 * @param $pw 		截图宽度为pw
	 * @param $px 		截图X坐标为px
	 * @param $py 		截图Y坐标为py
	 * @param $type 	图片的类型 
	 */
	public function imageIntercept($param) {
		$post_data = array(
			'imgh'=>$param['imgh'],
			'imgw'=>$param['imgw'],
			'nowh'=>$param['nowh'],
			'noww'=>$param['noww'],
			'ph'=>$param['ph'],
			'picurl'=>$param['picurl'],
			'pw'=>$param['pw'],
			'px'=>$param['px'],
			'py'=>$param['py'],
			'type'=>1,
			'subtype'=>$param['type'],
			'aucode'=>'boqii',
			'method'=>'imagickIntercept'
		);
		$url = C('IMG_UPLOAD_DIR') ."/Server/upload.php"; 
		$result = post_url($url,$post_data);
		if($result && (strpos($result, '{') === false)) {
			$json = array('status'=>'error', 'tip'=>'截图失败！');
		} else {
			$json = json_decode($result, true);
		}
		return json_encode($json);

	}
}
