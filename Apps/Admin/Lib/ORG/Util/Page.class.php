<?php
class Page{

	private $url;
	private $pcount;
	private $limit;
	private $page;
	private $count;

	public function __construct($url,$pcount,$limit,$page,$count){
		$this->url = $url;
		$this->pcount = $pcount;
		$this->limit = $limit;
		$this->page = $page;
		$this->count = $count;
	}

	/*
	*返回组合的page  html代码
	*/
	public function pageHtml(){
		$html = $this->rules();
		return $html;
	}

	/*
	*排列规则
	*/
	private function rules(){
		$prevPage = intval($this->page)-1;
		if($prevPage<=0){
			$prevPage = 1;
		}
		$nextPage = intval($this->page)+1;
		if($nextPage>=$this->pcount){
			$nextPage = $this->pcount;
		}
		$html = "<div class=\"row\"><div class=\"col-xs-12\">";
		$html .= " <div class=\"pull-left\"><div class=\"pull-left\"><ul class=\"pagination\"><li class=\"disabled\"><a>共:".$this->count."条</li></ul></div></div>";
		$html .= "<div class=\"pull-left\"><ul class=\"pagination\">";
		$html.="<li><a class='page-prev' href='".$this->url.$prevPage."'><span>«</span></a></li>";
		if(intval($this->pcount)>7){
			if(intval($this->page)<=4){
				for($i=1;$i<=5;$i++){
					if($this->page==$i){
						$html.="<li class=\"active\"><span>".$i."</span></li>";
					}else{
						$html.="<li><a href='".$this->url.$i."'>".$i."</a></li>";
					}
				}
				$html.="<li><span class='page-break'>...</span></li>";
				$html.="<li><a href='".$this->url.$this->pcount."'>".$this->pcount."</a></li>";

			}else if(intval($this->page)>=(intval($this->pcount)-3)){
				$html.="<li><a href='".$this->url."1'>1</a></li>";
				$html.="<li><span class='page-break'>...</span></li>";
				for($i=(intval($this->pcount)-4);$i<=intval($this->pcount);$i++){
					if($this->page==$i){
						$html.="<li class=\"active\"><span>".$i."</span></li>";
					}else{
						$html.="<li><a href='".$this->url.$i."'>".$i."</a></li>";
					}
				}

			}else{
				$html.="<li><a href='".$this->url."1'>1</a></li>";
				$html.="<li><span class='page-break'>...</span></li>";
				for($i=(intval($this->page)-2);$i<=(intval($this->page)+2);$i++){
					if($this->page==$i){
						$html.="<li class=\"active\"><span>".$i."</span></li>";
					}else{
						$html.="<li><a href='".$this->url.$i."'>".$i."</a></li>";
					}
				}
				$html.="<li><span class='page-break'>...</span></li>";
				$html.="<li><a href='".$this->url.$this->pcount."'>".$this->pcount."</a></li>";
			}
		}else{
			for($i=1;$i<=$this->pcount;$i++){
				if($this->page==$i){
					$html.="<li class=\"active\"><span>".$i."</span></li>";
				}else{
					$html.="<li><a href='".$this->url.$i."'>".$i."</a></li>";
				}
			}
		}
		$html.="<li><a class='page-next' href='".$this->url.$nextPage."'><span>»</span></a></li>";
		$html .= "</div></div></div>";
		return $html;
	}

}
?>
