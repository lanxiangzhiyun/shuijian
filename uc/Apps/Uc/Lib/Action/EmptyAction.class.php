<?php
//���з��ʲ����Ŀ���������ת��404ҳ��
class EmptyAction extends BaseAction {
	function _empty(){
		header("HTTP/1.0 404 Not Found");
		$this->assign('uid',$this->_user['uid']);
		$this->display('Public:404');
	}
	// 404
	function index() {
		header("HTTP/1.0 404 Not Found");
		$this->assign('uid',$this->_user['uid']);
		$this->display('Public:404');
	}
}
?>