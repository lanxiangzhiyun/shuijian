<?php
header("Content-Type: text/html; charset=UTF-8");
set_time_limit(0);
class getApiData{
    public $apiurl;
    public $token;
    public $clienttoken;
    public $baseData = array();
    function __construct(){
        // $this->apiurl = "http://shopapi.boqii.com/";
        $this->apiurl = "";
        $this->baseData = array(
                //'version'  => '1.0',
                //'format'   => 'json',
            );
    }
    function _checkSafe($data){

        $host = $_SERVER['HTTP_HOST'];
        if(strpos($host,'boqii.com') === false){
            return false;
        }
        //echo $this->_clientSign($data,$this->clienttoken);
        return true;
    }

    function _submit($commit_url,$paramss){
        $postdata = http_build_query($paramss);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_URL, $commit_url);
        $ret = curl_exec($ch);
        curl_close($ch);
        return $ret;
    }

    function _get($commit_url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $commit_url);
        $ret = curl_exec($ch);
        curl_close($ch);
        return $ret;
    }


    function getData($data){
        //数据校验
        if(!$this->_checkSafe($data)){
          return json_encode(array('status'=>'fail','code'=>'-1','errorMsg'=>'error call api','data'=>''));
        }
        $this->apiurl = $data['linkurl'];
        $result = $this->_submit($this->apiurl,$data);
        return $result;
    }

    function getGetData($data){
        //数据校验
        if(!$this->_checkSafe($data)){
          return json_encode(array('status'=>'fail','code'=>'-1','errorMsg'=>'error call api','data'=>''));
        }
        $url = $data['url'];
        $result = $this->_get($url);
        return $result;
    }
}

//获取post数据
$paramss = array();
$paramss =  $_POST;

$apiData = new getApiData();
echo $apiData->getData($paramss);
