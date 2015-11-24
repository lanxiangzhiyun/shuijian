<?php
/**
 * 微信接口类
 * */

class Wechat{
	const MSGTYPE_TEXT	= 'text';//文本消息
	const MSGTYPE_IMAGE	= 'image';//图片消息
	const MSGTYPE_LINK	= 'link';//链接消息
	const MSGTYPE_VOICE	= 'voice';//语音消息
	const MSGTYPE_VIDEO	= 'video';//视频消息
	const MSGTYPE_LOCATION	= 'location';//地理位置消息

	const MSGTYPE_EVENT = 'event';//事件推送(目前开启自定义菜单接口事件推送、关注与取消关注事件推送)
	const MSGTYPE_MUSIC = 'music';//回复音乐消息
	const MSGTYPE_NEWS	= 'news';//回复图文消息
	
	private $token;//自定义token
	
	private $_msg;//回复的xml消息
	private $_receive;//收取的xml消息
	private $_funcflag = false;//是否对消息进行星标

	public function __construct($options){
		$this->token = isset($options['token']) ? $options['token'] : '';
	}

	/**
	 * 微信签名验证
	 */	
	private function checkSignature(){
        $signature = isset($_GET["signature"])?$_GET["signature"]:'';
        $timestamp = isset($_GET["timestamp"])?$_GET["timestamp"]:'';
        $nonce = isset($_GET["nonce"])?$_GET["nonce"]:'';
        		
		$token = $this->token;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		return ($tmpStr == $signature) ? true : false;
	}
	
	/**
	 * For 微信接口接入验证
	 */
	public function valid(){
        $echoStr = isset($_GET["echostr"]) ? $_GET["echostr"]: '';
		if ($echoStr){//首次接入
			if($this->checkSignature()){
				die($echoStr);
			}else{
				die('Access Undefined');
			}
		}else{//常规验证
			if($this->checkSignature()){
				return true;
			}else{
				die('Access Undefined');
			}
		}
    }

	/**
	 * 日志记录
	 * @param string $log xml数据
	 * @param string $type 消息类型
	 * 日志记录
	 */
	 private function log($log,$type){
		file_put_contents('./WeChatLog_'.$type.'.txt',$log."\n",FILE_APPEND);
	 }

	/**
	 * 获取微信服务器发来的信息
	 */
	public function getRev(){
		$postStr = file_get_contents("php://input");
		$this->log($postStr,'receive');
		if (!empty($postStr)){
			$this->_receive = (array)simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
		}
		return $this;
	}

	/**
	 * 获取消息发送者
	 */
	public function getRevFrom(){
		return $this->_receive ? $this->_receive['FromUserName'] : false;
	}

	/**
	 * 获取消息接受者
	 */
	public function getRevTo(){
		return $this->_receive ? $this->_receive['ToUserName'] : false;
	}

	/**
	 * 获取接收消息的类型
	 */
	public function getRevType(){
		return $this->_receive ? $this->_receive['MsgType'] : false;
	}

	/**
	 * 获取消息ID
	 */
	public function getRevID(){
		return $this->_receive ? $this->_receive['MsgId'] : false;
	}

	/**
	 * 获取消息发送时间
	 */
	public function getRevCtime(){
		return $this->_receive ? $this->_receive['CreateTime'] : false;
	}

	/**
	 * 获取接收消息内容正文
	 */
	public function getRevContent(){
		return $this->_receive ? $this->_receive['Content'] : false;
	}

	/**
	 * 获取接收消息图片
	 */
	public function getRevPic(){
		return $this->_receive ? $this->_receive['PicUrl'] : false;
	}

	/**
	 * 获取接收消息链接
	 */
	public function getRevLink(){
		if (isset($this->_receive['Url'])){
			return array(
				'url'=>$this->_receive['Url'],
				'title'=>$this->_receive['Title'],
				'description'=>$this->_receive['Description']
			);
		} else 
			return false;
	}

	/**
	 * 获取接收地理位置
	 */
	public function getRevPos(){
		if (isset($this->_receive['Location_X'])){
			return array(
				'x'=>$this->_receive['Location_X'],
				'y'=>$this->_receive['Location_Y'],
				'scale'=>$this->_receive['Scale'],
				'label'=>$this->_receive['Label']
			);
		} else 
			return false;
	}

	/**
	 * 获取接收事件推送
	 */
	public function getRevEvent(){
		if (isset($this->_receive['Event'])){
			return array(
				'event'=>$this->_receive['Event'],
				'key'=>$this->_receive['EventKey'],//事件KEY值，与自定义菜单接口中KEY值对应 
			);
		} else 
			return false;
	}

	/**
	 * 获取接收语音推送
	 */
	public function getRevVoice(){
		if (isset($this->_receive['MediaId'])){
			return array(
				'mediaid'=>$this->_receive['MediaId'],
				'format'=>$this->_receive['Format'],
			);
		} else 
			return false;
	}	
	
	/**
	 * 过滤XML非法字符
	 */
	public static function xmlSafeStr($str){   
		return '<![CDATA['.preg_replace("/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/",'',$str).']]>';   
	} 

	/**
	 * 数据XML编码
	 * @param mixed $data 数据
	 * @return string
	 */
	public static function data_to_xml($data){
		$xml = '';
		foreach ($data as $key => $val) {
			is_numeric($key) && $key = 'item id="'.$key.'"';
			$xml    .=  '<'.$key.'>';
			$xml    .=  ( is_array($val) || is_object($val)) ? self::data_to_xml($val) : self::xmlSafeStr($val);
			list($key, ) = explode(' ', $key);
			$xml    .=  '</'.$key.">\n";
		}
		return $xml;
	}

	/**
	 * XML编码
	 * @param mixed $data 数据
	 * @return string
	*/
	public function xml_encode($data){
		$xml   = '<xml>'."\n";
		$xml   .= self::data_to_xml($data);
		$xml   .= '</xml>';
		return $xml;
	}

	/**
	 * 设置发送消息
	 * @param array $msg 消息数组
	 * @param bool $append 是否在原消息数组追加
	 */
    public function Message($msg = '',$append = false){
    		if (is_null($msg)) {
    			$this->_msg =array();
    		}elseif (is_array($msg)) {
    			if ($append)
    				$this->_msg = array_merge($this->_msg,$msg);
    			else
    				$this->_msg = $msg;
    			return $this->_msg;
    		} else {
    			return $this->_msg;
    		}
    }

	/**
	 * 对消息进行星标
	 * @param int $flag 1、0
	 */
    public function setFuncFlag($flag) {
    		$this->_funcflag = $flag;
    		return $this;
    }

	/**
	 * 设置回复消息
	 * Examle: $obj->text('hello')->reply();
	 * @param string $text
	 */
	public function text($text=''){
		$FuncFlag = $this->_funcflag ? 1 : 0;
		$msg = array(
			'ToUserName' => $this->getRevFrom(),
			'FromUserName'=>$this->getRevTo(),
			'MsgType'=>self::MSGTYPE_TEXT,
			'Content'=>$text,
			'CreateTime'=>time(),
			'FuncFlag'=>$FuncFlag
		);
		$this->Message($msg);
		return $this;
	}

	/**
	 * 设置回复音乐
	 * @param string $title
	 * @param string $desc
	 * @param string $musicurl
	 * @param string $hgmusicurl
	 * 数组结构
	 * "Music"=>array(
	 *		"Title"=>"一万个舍不得",
	 *		"Description"=>"蓝恩推荐歌曲 一万个舍不得",
	 *		"MusicUrl"=>"http://stream18.qqmusic.qq.com/32445392.mp3",
	 *		"HQMusicUrl"=>"http://stream18.qqmusic.qq.com/32445392.mp3"
	 *	),
	 */
	public function music($title,$desc,$musicurl,$hgmusicurl=''){
		$FuncFlag = $this->_funcflag ? 1 : 0;
		$msg = array(
			'ToUserName' => $this->getRevFrom(),
			'FromUserName'=>$this->getRevTo(),
			'CreateTime'=>time(),
			'MsgType'=>self::MSGTYPE_MUSIC,
			'Music'=>array(
				'Title'=>$title,
				'Description'=>$desc,
				'MusicUrl'=>$musicurl,
				'HQMusicUrl'=>$hgmusicurl
			),
			'FuncFlag'=>$FuncFlag
		);
		$this->Message($msg);
		return $this;
	}

	/**
	 * 设置回复图文
	 * @param array $newsData 
	 * 数组结构:
	 *  array(
	 *  	[0]=>array(
	 *  		'Title'=>'msg title',
	 *  		'Description'=>'summary text',
	 *  		'PicUrl'=>'http://www.domain.com/1.jpg',
	 *  		'Url'=>'http://www.domain.com/1.html'
	 *  	),
	 *  	[1]=>....
	 *  )
	 */
	public function news($newsData=array()){
		$FuncFlag = $this->_funcflag ? 1 : 0;
		$count = count($newsData);
		
		$msg = array(
			'ToUserName' => $this->getRevFrom(),
			'FromUserName'=>$this->getRevTo(),
			'MsgType'=>self::MSGTYPE_NEWS,
			'CreateTime'=>time(),
			'ArticleCount'=>$count,
			'Articles'=>$newsData,
			'FuncFlag'=>$FuncFlag
		);
		$this->Message($msg);
		return $this;
	}

	/**
	 * 
	 * 回复微信服务器, 此函数支持链式操作
	 * Example: $this->text('msg tips')->reply();
	 * @param string $msg 要发送的信息, 默认取$this->_msg
	 * @param bool $return 是否返回信息而不抛出到浏览器 默认:否
	 */
	public function reply($msg=array(),$return = false){
		if (empty($msg)) 
			$msg = $this->_msg;
		$xmldata=  $this->xml_encode($msg);
		$this->log($xmldata,'reply');
		if ($return)
			return $xmldata;
		else
			echo $xmldata;
	}
}