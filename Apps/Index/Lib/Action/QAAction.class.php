<?php
/**
 *  帮助中心
 * @author fanghui
 * @date 2015/12/01
 *
 */
class QAAction extends Action {
    /**
     * 首页
     *
     */
    public function index(){

        $this->display('QAIndex');
    }
}
