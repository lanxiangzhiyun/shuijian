<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// |         lanfengye <zibin_5257@163.com>
// +----------------------------------------------------------------------

class Page {
    
    // 分页栏每页显示的页数
    public $rollPage = 5;
    // 页数跳转时要带的参数
    public $parameter  ;
    // 分页URL地址
    public $url     =   '';
    // 默认列表每页显示行数
    public $listRows = 20;
    // 起始行数
    public $firstRow    ;
    // 分页总页面数
    protected $totalPages  ;
    // 总行数
    protected $totalRows  ;
    // 当前页数
    protected $nowPage    ;
    // 分页的栏的总页数
    protected $coolPages   ;
    // 分页页面类型
    protected $urlType    ;
    // 分页页面参数
    protected $param;

    // 分页显示定制
    protected $config  =    array('header'=>'条记录','prev'=>'上一页','next'=>'下一页','first'=>'第一页','last'=>'最后一页','theme'=>' %totalRow% %header% %nowPage%/%totalPage% 页 %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
    // 默认分页变量名
    protected $varPage;

    /**
     * 架构函数
     * @access public
     * @param array $totalRows  总的记录数
     * @param array $listRows  每页显示记录数
     * @param array $parameter  分页跳转的参数
     */
    public function __construct($totalRows,$listRows='',$urlType='',$param='', $parameter='',$url='') {
        $this->totalRows    =   $totalRows;
        $this->parameter    =   $parameter;
        $this->urlType = $urlType;
        $this->param = $param;
        $this->varPage      =   C('VAR_PAGE') ? C('VAR_PAGE') : 'p' ;
        if(!empty($listRows)) {
            $this->listRows =   intval($listRows);
        }
        $this->totalPages   =   ceil($this->totalRows/$this->listRows);     //总页数
        $this->coolPages    =   ceil($this->totalPages/$this->rollPage);
        $this->nowPage      =   !empty($_GET[$this->varPage])?intval($_GET[$this->varPage]):1;
        if(!empty($this->totalPages) && $this->nowPage>$this->totalPages) {
            $this->nowPage  =   $this->totalPages;
        }
        $this->firstRow     =   $this->listRows*($this->nowPage-1);
    }

    public function setConfig($name,$value) {
        if(isset($this->config[$name])) {
            $this->config[$name]    =   $value;
        }
    }

    /**
     * 分页显示输出
     * @access public
     */
    public function showBK() {
        if(0 == $this->totalRows) return '';
        $p              =   $this->varPage;
        $nowCoolPage    =   ceil($this->nowPage/$this->rollPage);

        // 分析分页参数
        if($this->url){
            $depr       =   C('URL_PATHINFO_DEPR');
            $url        =   rtrim(U('/'.$this->url,'',false),$depr).$depr.'__PAGE__';
        }else{
            if($this->parameter && is_string($this->parameter)) {
                parse_str($this->parameter,$parameter);
            }elseif(empty($this->parameter)){
                unset($_GET[C('VAR_URL_PARAMS')]);
                if(empty($_GET)) {
                    $parameter  =   array();
                }else{
                    $parameter  =   $_GET;
                }
            }
            $parameter[$p]  =   '__PAGE__';
            $url            =   U('',$parameter);
        }
        //上下翻页字符串
        $upRow          =   $this->nowPage-1;
        $downRow        =   $this->nowPage+1;
        if ($upRow>0){
            $upPage     =   "<a href='".str_replace('__PAGE__',$upRow,$url)."'>".$this->config['prev']."</a>";
        }else{
            $upPage     =   '';
        }

        if ($downRow <= $this->totalPages){
            $downPage   =   "<a href='".str_replace('__PAGE__',$downRow,$url)."'>".$this->config['next']."</a>";
        }else{
            $downPage   =   '';
        }
        // << < > >>
        if($nowCoolPage == 1){
            $theFirst   =   '';
            $prePage    =   '';
        }else{
            $preRow     =   $this->nowPage-$this->rollPage;
            $prePage    =   "<a href='".str_replace('__PAGE__',$preRow,$url)."' >上".$this->rollPage."页</a>";
            $theFirst   =   "<a href='".str_replace('__PAGE__',1,$url)."' >".$this->config['first']."</a>";
        }
        if($nowCoolPage == $this->coolPages){
            $nextPage   =   '';
            $theEnd     =   '';
        }else{
            $nextRow    =   $this->nowPage+$this->rollPage;
            $theEndRow  =   $this->totalPages;
            $nextPage   =   "<a href='".str_replace('__PAGE__',$nextRow,$url)."' >下".$this->rollPage."页</a>";
            $theEnd     =   "<a href='".str_replace('__PAGE__',$theEndRow,$url)."' >".$this->config['last']."</a>";
        }
        // 1 2 3 4 5
        $linkPage = "";
        for($i=1;$i<=$this->rollPage;$i++){
            $page       =   ($nowCoolPage-1)*$this->rollPage+$i;
            if($page!=$this->nowPage){
                if($page<=$this->totalPages){
                    $linkPage .= "&nbsp;<a href='".str_replace('__PAGE__',$page,$url)."'>&nbsp;".$page."&nbsp;</a>";
                }else{
                    break;
                }
            }else{
                if($this->totalPages != 1){
                    $linkPage .= "&nbsp;<span class='current'>".$page."</span>";
                }
            }
        }
        $pageStr     =   str_replace(
            array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%prePage%','%linkPage%','%nextPage%','%end%'),
            array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$upPage,$downPage,$theFirst,$prePage,$linkPage,$nextPage,$theEnd),$this->config['theme']);
        return $pageStr;
    }


    /**
     +----------------------------------------------------------
     * 分页显示输出
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     */
    public function show() {
        if(0 == $this->totalRows) return '';
        $p              =   $this->varPage;
        $nowCoolPage    =   ceil($this->nowPage/$this->rollPage);
//        $url =  $_SERVER['REQUEST_URI'];

        $urlTypes = explode(",", $this->urlType);
        if($this->parameter) {
            $queryStr = preg_replace("/&".$p."=(\d+)/","",$this->parameter);
        }
        if(isset($queryStr)) {
            $queryStr = "?".$queryStr;
        } else {
            $queryStr = '';
        }

//        // 分析分页参数
//        if($this->url){
//            $depr       =   C('URL_PATHINFO_DEPR');
//            $url        =   rtrim(U('/'.$this->url,'',false),$depr).$depr.'__PAGE__';
//        }else{
//            if($this->parameter && is_string($this->parameter)) {
//                parse_str($this->parameter,$parameter);
//            }elseif(empty($this->parameter)){
//                unset($_GET[C('VAR_URL_PARAMS')]);
//                if(empty($_GET)) {
//                    $parameter  =   array();
//                }else{
//                    $parameter  =   $_GET;
//                }
//            }
//            $parameter[$p]  =   '__PAGE__';
//            $url            =   U('',$parameter);
//        }
    
        //上下翻页字符串
        $upRow   = $this->nowPage-1;
        $downRow = $this->nowPage+1;
        if ($upRow>0){

            $upPage="<a class='page-prev' href='".get_rewrite_url($urlTypes[0], $urlTypes[1], $this->param, $upRow).$queryStr."'>".$this->config['prev']."</a>";
        }else{
            $upPage="";
        }

        if ($downRow <= $this->totalPages){
            $downPage="<a class='page-next' href='".get_rewrite_url($urlTypes[0], $urlTypes[1], $this->param, $downRow).$queryStr."'>".$this->config['next']."</a>";
        }else{
            $downPage="";
        }
        // << < > >>
        if($nowCoolPage == 1){
            $theFirst = "<a href='".get_rewrite_url($urlTypes[0], $urlTypes[1], $this->param, 1).$queryStr."' >1</a>";
            $prePage = "";
        }else{
            $preRow =  $this->nowPage-$this->rollPage;
            //$prePage = "<a href='".str_replace('__PAGE__',$preRow,$url)."' >上".$this->rollPage."页</a>";
            $theFirst = "<a href='".get_rewrite_url($urlTypes[0], $urlTypes[1], $this->param, 1).$queryStr."' >1</a>";
        }
        if($nowCoolPage == $this->coolPages){
            $nextPage = "";
            $theEndRow = $this->totalPages;
            $theEnd="<a href='".get_rewrite_url($urlTypes[0], $urlTypes[1], $this->param, $theEndRow).$queryStr."' >".$this->totalPages."</a>";
        }else{
            $nextRow = $this->nowPage+$this->rollPage;
            $theEndRow = $this->totalPages;
            $nextPage = "<a href='".get_rewrite_url($urlTypes[0], $urlTypes[1], $this->param, $nextRow).$queryStr."' >下".$this->rollPage."页</a>";
            //$theEnd = "<a href='".get_rewrite_url($this->urlType, $typeid, $theEndRow, $tagid).$queryStr."' >".$this->config['last']."</a>";
            $theEnd = "<a href='".get_rewrite_url($urlTypes[0], $urlTypes[1], $this->param, $theEndRow).$queryStr."' >".$this->totalPages."</a>";
            
        }
        // 1 2 3 4 5
        $linkPage = "";

        //不足5页
        if($this->totalPages >= 2 && $this->totalPages <= 5) {
            $start = 1;
            $end = $this->totalPages;
        }
        //超过5页
        elseif($this->totalPages > 5) {
            //当前页
            if($this->nowPage >= $this->totalPages-2) {
                $end = $this->totalPages;
                $start = $end - 4;                
            } elseif($this->nowPage <= 2) {
                $start = 1;
                $end = 5;    
            } else {
                $start = $this->nowPage - 2 > 0 ? $this->nowPage - 2 : $this->nowPage;
                $end = $start + 4;
            }
        }
        
        for($i=$start;$i<=$end;$i++) {
            if($i == $this->nowPage) {
                 $linkPage .= "<span class='page-this'>".$i."</span>";
            } else {
                $linkPage .= "<a href='".get_rewrite_url($urlTypes[0], $urlTypes[1], $this->param, $i).$queryStr."'>".$i."</a>";
            }
        }

        
        //首页链接
        $index = '';

        //1、若是只有一页，显示空白
        if($this->totalPages == 1){
            $config  =    array('header'=>$index);
            
        }
        //2、若是页数只有2页，则显示空白，当前及关联页数，上一页，下一页按钮（上一页、下一页的显示遵循第二条原则，以下不重复累述）
        //若当前页是最后一页则不显示下一页按钮；若当前页是第一页则不显示上一页按钮
        elseif($this->totalPages == 2) {
            //1 2 下一页
            if($this->nowPage == 1) {
                $config  =    array('header'=>$index, 'theme'=>' %header% %linkPage% %downPage%');
                $pageStr = str_replace(array('%header%','%linkPage%','%downPage%'), array($index, $linkPage,$downPage),$config['theme']);
            }
            //上一页 1 2
            else {
                $config  =    array('header'=>$index, 'theme'=>' %header% %upPage% %linkPage%');
                $pageStr = str_replace(array('%header%', '%upPage%', '%linkPage%'), array($index, $upPage, $linkPage), $config['theme']);
            }
        }        
        //3、若是页数<=5页，则显示空白，当前及关联页数，上一页，下一页按钮
        elseif($this->totalPages <= 5) {
            //当前第一页： 1 2 3 (4 5) 下一页
            if($this->nowPage == 1) {
                $config  = array('header'=>$index, 'theme'=>' %header% %linkPage% %downPage%');
                $pageStr = str_replace(array('%header%','%linkPage%','%downPage%'), array($index, $linkPage, $downPage),$config['theme']);
            } 
            //当前最后一页： 上一页 1 2 3 4 5/ 上一页 1 2 3 4/ 上一页 1 2 3
            elseif($this->nowPage == $this->totalPages) {
                $config  = array('header'=>$index, 'theme'=>' %header% %upPage% %linkPage%');
                $pageStr = str_replace(array('%header%','%upPage%','%linkPage%'), array($index, $upPage, $linkPage),$config['theme']);
            } 
            //其他页： 上一页 1 2 3 4 5 下一页
            else {
                $config  = array('header'=>$index, 'theme'=>' %header% %upPage% %linkPage% %downPage%');
                $pageStr = str_replace(array('%header%','%upPage%','%linkPage%', '%downPage%'), array($index, $upPage, $linkPage, $downPage),$config['theme']);
            }
        }            
        //4、若是页数>5页，则显示空白，第一页，当前及关联页数，上一页，下一页，最后一页
        else {
            //当前第一页
            if($this->nowPage == 1) {
                $config  = array('header'=>$index, 'theme'=>' %header% %linkPage% '.'<span class="page-break">...</span>'.' %end% %downPage%');
                $pageStr = str_replace(array('%header%','%linkPage%', '%end%', '%downPage%'), array($index,$linkPage, $theEnd, $downPage),$config['theme']);
            }
            //当前最后一页
            elseif($this->nowPage == $this->totalPages) {
                $config  = array('header'=>$index, 'theme'=>' %header% %upPage% %first% '.'<span class="page-break">...</span>'.' %linkPage%');
                $pageStr = str_replace(array('%header%', '%upPage%', '%first%', '%linkPage%'), array($index, $upPage, $theFirst,  $linkPage),$config['theme']);
            }
            //其他页
            else {
                if($this->nowPage <= 3) {
                    $theme = ' %header% %upPage% %linkPage% '.'<span class="page-break">...</span>'.' %end% %downPage%';
                }elseif($this->nowPage >= $this->totalPages-2){
                    $theme = ' %header% %upPage% %first% '.'<span class="page-break">...</span>'.' %linkPage% %downPage%';
                }
                else {
                    $theme = ' %header% %upPage% %first% '.'<span class="page-break">...</span>'.' %linkPage% '.'<span class="page-break">...</span>'.' %end% %downPage%';
                }
                $config  = array('header'=>$index, 'theme'=>$theme);
                $pageStr = str_replace(array('%header%', '%upPage%', '%first%', '%linkPage%', '%end%', '%downPage%'), array($index, $upPage, $theFirst, $linkPage, $theEnd, $downPage),$config['theme']);
            }
        }

        //$this->config = $config; 

        return $pageStr;
    }

    /**
     +----------------------------------------------------------
     * 分页显示输出
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     */
    public function frontShow() {
        if(0 == $this->totalRows) return '';
        $p              =   $this->varPage;
        $nowCoolPage    =   ceil($this->nowPage/$this->rollPage);
        // 分析分页参数
//        if($this->url){
//            $depr       =   C('URL_PATHINFO_DEPR');
//            $url        =   rtrim(U('/'.$this->url,'',false),$depr).$depr.'__PAGE__';
//        }else{
//            if($this->parameter && is_string($this->parameter)) {
//                parse_str($this->parameter,$parameter);
//            }elseif(empty($this->parameter)){
//                unset($_GET[C('VAR_URL_PARAMS')]);
//                if(empty($_GET)) {
//                    $parameter  =   array();
//                }else{
//                    $parameter  =   $_GET;
//                }
//            }
//            $parameter[$p]  =   '__PAGE__';print_r($parameter);echo '<br>';
//            $url            =   U('',$parameter);//获取域名或主机地址 
//        }
        if(empty($_SERVER['QUERY_STRING'])) {
            if(isset($_GET[$p])) {
                $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            } else {
                $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] . "/p/1";
            }
        } else {
            if(isset($_GET[$p])) {
                $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'?'.$_SERVER['QUERY_STRING'];
            } else {
                $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] . "/p/1".'?'.$_SERVER['QUERY_STRING'];;
            }
        }
        $url = preg_replace("/".$p."\/(\d+)/",$p."/__PAGE__",$url);

        //上下翻页字符串
        $upRow   = $this->nowPage-1;
        $downRow = $this->nowPage+1;
        if ($upRow>0){
            $upPage="<a class='page-prev' href='".str_replace('__PAGE__',$upRow,$url)."'>".$this->config['prev']."</a>";
        }else{
            $upPage="";
        }

        if ($downRow <= $this->totalPages){
            $downPage="<a class='page-next' href='".str_replace('__PAGE__',$downRow,$url)."'>".$this->config['next']."</a>";
        }else{
            $downPage="";
        }
        // << < > >>
        if($nowCoolPage == 1){
            $theFirst = "<a href='".str_replace('__PAGE__',1,$url)."' >1</a>";
            $prePage = "";
        }else{
            $preRow =  $this->nowPage-$this->rollPage;
            //$prePage = "<a href='".str_replace('__PAGE__',$preRow,$url)."' >上".$this->rollPage."页</a>";
            $theFirst = "<a href='".str_replace('__PAGE__',1,$url)."' >1</a>";
        }
        if($nowCoolPage == $this->coolPages){
            $nextPage = "";
            $theEnd="<a href='".str_replace('__PAGE__',$theEndRow,$url)."' >".$this->totalPages."</a>";
        }else{
            $nextRow = $this->nowPage+$this->rollPage;
            $theEndRow = $this->totalPages;
            $nextPage = "<a href='".str_replace('__PAGE__',$nextRow,$url)."' >下".$this->rollPage."页</a>";
            //$theEnd = "<a href='".get_rewrite_url($this->urlType, $typeid, $theEndRow, $tagid).$queryStr."' >".$this->config['last']."</a>";
            $theEnd = "<a href='".str_replace('__PAGE__',$theEndRow,$url)."' >".$this->totalPages."</a>";
            
        }
        // 1 2 3 4 5
        $linkPage = "";

        //不足5页
        if($this->totalPages >= 2 && $this->totalPages <= 5) {
            $start = 1;
            $end = $this->totalPages;
        }
        //超过5页
        elseif($this->totalPages > 5) {
            //当前页
            if($this->nowPage >= $this->totalPages-2) {
                $end = $this->totalPages;
                $start = $end - 4;                
            } elseif($this->nowPage <= 2) {
                $start = 1;
                $end = 5;    
            } else {
                $start = $this->nowPage - 2 > 0 ? $this->nowPage - 2 : $this->nowPage;
                $end = $start + 4;
            }
        }
        
        for($i=$start;$i<=$end;$i++) {
            if($i == $this->nowPage) {
                 $linkPage .= "<span class='page-this'>".$i."</span>";
            } else {
                $linkPage .= "<a href='".str_replace('__PAGE__',$i,$url)."'>".$i."</a>";
            }
        }

        
        //首页链接
        $index = '';

        //1、若是只有一页，显示空白
        if($this->totalPages == 1){
            $config  =    array('header'=>$index);
            
        }
        //2、若是页数只有2页，则显示空白，当前及关联页数，上一页，下一页按钮（上一页、下一页的显示遵循第二条原则，以下不重复累述）
        //若当前页是最后一页则不显示下一页按钮；若当前页是第一页则不显示上一页按钮
        elseif($this->totalPages == 2) {
            //1 2 下一页
            if($this->nowPage == 1) {
                $config  =    array('header'=>$index, 'theme'=>' %header% %linkPage% %downPage%');
                $pageStr = str_replace(array('%header%','%linkPage%','%downPage%'), array($index, $linkPage,$downPage),$config['theme']);
            }
            //上一页 1 2
            else {
                $config  =    array('header'=>$index, 'theme'=>' %header% %upPage% %linkPage%');
                $pageStr = str_replace(array('%header%', '%upPage%', '%linkPage%'), array($index, $upPage, $linkPage), $config['theme']);
            }
        }        
        //3、若是页数<=5页，则显示空白，当前及关联页数，上一页，下一页按钮
        elseif($this->totalPages <= 5) {
            //当前第一页： 1 2 3 (4 5) 下一页
            if($this->nowPage == 1) {
                $config  = array('header'=>$index, 'theme'=>' %header% %linkPage% %downPage%');
                $pageStr = str_replace(array('%header%','%linkPage%','%downPage%'), array($index, $linkPage, $downPage),$config['theme']);
            } 
            //当前最后一页： 上一页 1 2 3 4 5/ 上一页 1 2 3 4/ 上一页 1 2 3
            elseif($this->nowPage == $this->totalPages) {
                $config  = array('header'=>$index, 'theme'=>' %header% %upPage% %linkPage%');
                $pageStr = str_replace(array('%header%','%upPage%','%linkPage%'), array($index, $upPage, $linkPage),$config['theme']);
            } 
            //其他页： 上一页 1 2 3 4 5 下一页
            else {
                $config  = array('header'=>$index, 'theme'=>' %header% %upPage% %linkPage% %downPage%');
                $pageStr = str_replace(array('%header%','%upPage%','%linkPage%', '%downPage%'), array($index, $upPage, $linkPage, $downPage),$config['theme']);
            }
        }            
        //4、若是页数>5页，则显示空白，第一页，当前及关联页数，上一页，下一页，最后一页
        else {
            //当前第一页
            if($this->nowPage == 1) {
                $config  = array('header'=>$index, 'theme'=>' %header% %linkPage% '.'<span class="page-break">...</span>'.' %end% %downPage%');
                $pageStr = str_replace(array('%header%','%linkPage%', '%end%', '%downPage%'), array($index,$linkPage, $theEnd, $downPage),$config['theme']);
            }
            //当前最后一页
            elseif($this->nowPage == $this->totalPages) {
                $config  = array('header'=>$index, 'theme'=>' %header% %upPage% %first% '.'<span class="page-break">...</span>'.' %linkPage%');
                $pageStr = str_replace(array('%header%', '%upPage%', '%first%', '%linkPage%'), array($index, $upPage, $theFirst,  $linkPage),$config['theme']);
            }
            //其他页
            else {
                if($this->nowPage <= 3) {
                    $theme = ' %header% %upPage% %linkPage% '.'<span class="page-break">...</span>'.' %end% %downPage%';
                }elseif($this->nowPage >= $this->totalPages-2){
                    $theme = ' %header% %upPage% %first% '.'<span class="page-break">...</span>'.' %linkPage% %downPage%';
                }
                else {
                    $theme = ' %header% %upPage% %first% '.'<span class="page-break">...</span>'.' %linkPage% '.'<span class="page-break">...</span>'.' %end% %downPage%';
                }
                $config  = array('header'=>$index, 'theme'=>$theme);
                $pageStr = str_replace(array('%header%', '%upPage%', '%first%', '%linkPage%', '%end%', '%downPage%'), array($index, $upPage, $theFirst, $linkPage, $theEnd, $downPage),$config['theme']);
            }
        }

        return $pageStr;
    }
}