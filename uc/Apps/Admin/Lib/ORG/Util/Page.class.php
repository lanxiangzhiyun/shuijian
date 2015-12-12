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
		$html = "<div class='page_wrap'><div class='showpage'><span class='num'>本页共".$this->count."条</span>";
		$html.="<a class='page-prev' href='".$this->url.$prevPage."'><span>上一页</span></a>";
		if(intval($this->pcount)>7){
			if(intval($this->page)<=4){
				for($i=1;$i<=5;$i++){
					if($this->page==$i){
						$html.=" <span class='page-this'>".$i."</span>";
					}else{
						$html.="<a href='".$this->url.$i."'>".$i."</a>";
					}
				}
				$html.="<span class='page-break'>...</span>";
				$html.=" <a href='".$this->url.$this->pcount."'>".$this->pcount."</a>";

			}else if(intval($this->page)>=(intval($this->pcount)-3)){
				$html.=" <a href='".$this->url."1'>1</a>";
				$html.="<span class='page-break'>...</span>";
				for($i=(intval($this->pcount)-4);$i<=intval($this->pcount);$i++){
					if($this->page==$i){
						$html.=" <span class='page-this'>".$i."</span>";
					}else{
						$html.="<a href='".$this->url.$i."'>".$i."</a>";
					}
				}
			
			}else{
				$html.=" <a href='".$this->url."1'>1</a>";
				$html.="<span class='page-break'>...</span>";
				for($i=(intval($this->page)-2);$i<=(intval($this->page)+2);$i++){
					if($this->page==$i){
						$html.=" <span class='page-this'>".$i."</span>";
					}else{
						$html.="<a href='".$this->url.$i."'>".$i."</a>";
					}
				}
				$html.="<span class='page-break'>...</span>";
				$html.=" <a href='".$this->url.$this->pcount."'>".$this->pcount."</a>";
			}
		}else{
			for($i=1;$i<=$this->pcount;$i++){
				if($this->page==$i){
					$html.=" <span class='page-this'>".$i."</span>";
				}else{
					$html.="<a href='".$this->url.$i."'>".$i."</a>";
				}
			}
		}
		$html.="<a class='page-next' href='".$this->url.$nextPage."'><span>下一页</span></a>";
		$html.="<span class='num'>共".$this->pcount."页</span><span class='txt'>到第</span><span class='txt'><input class='input_num' type='text' url='".$this->url."' name='pageJump' id='pageJump' /></span><span class='txt'>页</span><a class='goto_btn' href='javascript:;'  title='确定'></a></div></div>";
		return $html;
	}

}
?>