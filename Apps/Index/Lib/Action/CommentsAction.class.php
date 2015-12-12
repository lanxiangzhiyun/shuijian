<?php
/**
 *  顾客口碑
 * @author fanghui
 * @date 2015/12/01
 *
 */
class CommentsAction extends Action {
    /**
     * 首页
     *
     */

    public function index($type){
        $this->display('CommentsIndex');
    }
}
