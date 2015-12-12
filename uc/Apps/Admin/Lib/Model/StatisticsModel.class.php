<?php
class StatisticsModel extends RelationModel {
	protected $trueTableName = 'zt_baike_dog';
	
	 public function getChoiceCount($table,$field,$i){
		$where = "".$field." = '".$i."'";
		$result = M($table)->where($where)->getField('COUNT(*) as num');
		return $result;
	}
	
	public function getChoiceCount2($table,$field,$i){
		$where = "FIND_IN_SET($i,$field)";
		$result = M($table)->where($where)->getField('COUNT(*) as num');
		return $result;
	}
	
	public function getChoiceCount3($table,$field,$i,$sid){
		$where = "FIND_IN_SET($i,$field) and sid = ".$sid;
		$result = M($table)->where($where)->getField('COUNT(*) as num');
		return $result;
	}
	
	//excel导出
	public function getExcle(){
		$c1 = $this->getChoiceCount('zt_baike_dog','gender',1);
		$c2 = $this->getChoiceCount('zt_baike_dog','gender',2);
		$c3 = $this->getChoiceCount('zt_baike_dog','age',1);
		$c4 = $this->getChoiceCount('zt_baike_dog','age',2);
		$c5 = $this->getChoiceCount('zt_baike_dog','age',3);
		$c6 = $this->getChoiceCount('zt_baike_dog','age',4);
		$c7 = $this->getChoiceCount('zt_baike_dog','understanding',1);
		$c8 = $this->getChoiceCount('zt_baike_dog','understanding',2);
		$c9 = $this->getChoiceCount('zt_baike_dog','seen',1);
		$c10 = $this->getChoiceCount('zt_baike_dog','seen',2);
		$c11 = $this->getChoiceCount('zt_baike_dog','konw',1);
		$c12 = $this->getChoiceCount('zt_baike_dog','konw',2);
		$c13 = $this->getChoiceCount('zt_baike_dog','accept',1);
		$c14 = $this->getChoiceCount('zt_baike_dog','accept',2);
		$c15 = $this->getChoiceCount('zt_baike_dog','accept',3);
		$c16 = $this->getChoiceCount('zt_baike_dog','reason',1);
		$c17 = $this->getChoiceCount('zt_baike_dog','reason',2);
		$c18 = $this->getChoiceCount('zt_baike_dog','reason',3);
		$c19 = $this->getChoiceCount('zt_baike_dog','reason',4);
		
		vendor('excel.PHPExcel');
		$fileName = $this->fileName;
		$fileName = empty($fileName)?'dog_list'.date('Y-m-d',time()):$fileName;
		$PHPExcel = new PHPExcel();
		//填入表头
		$PHPExcel->getActiveSheet()->setCellValue('A1', '选项');
		$PHPExcel->getActiveSheet()->setCellValue('B1', '答案1');
		$PHPExcel->getActiveSheet()->setCellValue('C1', '答案2');
		$PHPExcel->getActiveSheet()->setCellValue('D1', '答案3');
		$PHPExcel->getActiveSheet()->setCellValue('E1', '答案4');
		//填入列表
		$ks = 0;
		$PHPExcel->getActiveSheet()->setCellValue('A2', '你的性别');
		$PHPExcel->getActiveSheet()->setCellValue('B2', '男('.$c1.')');
		$PHPExcel->getActiveSheet()->setCellValue('C2', '女('.$c2.')');
		$PHPExcel->getActiveSheet()->setCellValue('D2', '');
		$PHPExcel->getActiveSheet()->setCellValue('E2', '');
		$ks = 1;	
		$PHPExcel->getActiveSheet()->setCellValue('A3', '你的年龄');
		$PHPExcel->getActiveSheet()->setCellValue('B3', '18岁以下('.$c3.')');
		$PHPExcel->getActiveSheet()->setCellValue('C3', '19-40岁('.$c4.')');
		$PHPExcel->getActiveSheet()->setCellValue('D3', '41-60岁('.$c5.')');
		$PHPExcel->getActiveSheet()->setCellValue('E3', '60岁以上('.$c6.')');
		$ks = 2;
		$PHPExcel->getActiveSheet()->setCellValue('A4', '你是否了解导盲犬');
		$PHPExcel->getActiveSheet()->setCellValue('B4', '了解('.$c7.')');
		$PHPExcel->getActiveSheet()->setCellValue('C4', '不了解('.$c8.')');
		$PHPExcel->getActiveSheet()->setCellValue('D4', '');
		$PHPExcel->getActiveSheet()->setCellValue('E4', '');
		$ks = 3;
		$PHPExcel->getActiveSheet()->setCellValue('A5', '你是否在生活中见过导盲犬');
		$PHPExcel->getActiveSheet()->setCellValue('B5', '见过('.$c9.')');
		$PHPExcel->getActiveSheet()->setCellValue('C5', '没见过('.$c10.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D5', '');
		$PHPExcel->getActiveSheet()->setCellValue('E5', '');
		$ks = 4;
		$PHPExcel->getActiveSheet()->setCellValue('A6', '你知道导盲犬可以进出公共场合吗');
		$PHPExcel->getActiveSheet()->setCellValue('B6', '知道('.$c11.')');
		$PHPExcel->getActiveSheet()->setCellValue('C6', '不知道('.$c12.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D6', '');
		$PHPExcel->getActiveSheet()->setCellValue('E6', '');
		$ks = 5;
		$PHPExcel->getActiveSheet()->setCellValue('A7', '你是否接受导盲犬跟你同行');
		$PHPExcel->getActiveSheet()->setCellValue('B7', '能接受('.$c13.')');
		$PHPExcel->getActiveSheet()->setCellValue('C7', '不接受('.$c14.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D7', '看情况('.$c15.')');
		$PHPExcel->getActiveSheet()->setCellValue('E7', '');
		$ks = 6;
		$PHPExcel->getActiveSheet()->setCellValue('A8', '你不愿意与导盲犬同行和共处的理由是什么');
		$PHPExcel->getActiveSheet()->setCellValue('B8', '担心被导盲犬伤害('.$c16.')');
		$PHPExcel->getActiveSheet()->setCellValue('C8', '导盲犬容易带来各种细菌和疾病('.$c17.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D8', '单纯地害怕('.$c18.')');
		$PHPExcel->getActiveSheet()->setCellValue('E8', '其他('.$c19.')');
		$ks = 7;
		//保存为2003格式
		$objWriter = new PHPExcel_Writer_Excel5($PHPExcel);
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		
		//多浏览器下兼容中文标题
		$encoded_filename = urlencode($fileName);
		$ua = $_SERVER["HTTP_USER_AGENT"];
		if (preg_match("/MSIE/", $ua)) {
			header('Content-Disposition: attachment; filename="' . $encoded_filename . '.xls"');
		} else if (preg_match("/Firefox/", $ua)) {
			header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '.xls"');
		} else {
			header('Content-Disposition: attachment; filename="' . $fileName . '.xls"');
		}
		
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
	 }
	 
	//excel导出
	public function getExcleCat(){
		$c1 = $this->getChoiceCount('zt_bbs_cat','gender',1);
		$c2 = $this->getChoiceCount('zt_bbs_cat','gender',2);
		$c3 = $this->getChoiceCount('zt_bbs_cat','age',1);
		$c4 = $this->getChoiceCount('zt_bbs_cat','age',2);
		$c5 = $this->getChoiceCount('zt_bbs_cat','age',3);
		$c6 = $this->getChoiceCount('zt_bbs_cat','age',4);
		$c7 = $this->getChoiceCount('zt_bbs_cat','varieties',1);
		$c8 = $this->getChoiceCount('zt_bbs_cat','varieties',2);
		$c9 = $this->getChoiceCount('zt_bbs_cat','will',1);
		$c10 = $this->getChoiceCount('zt_bbs_cat','will',2);
		$c11 = $this->getChoiceCount('zt_bbs_cat','outdoor',1);
		$c12 = $this->getChoiceCount('zt_bbs_cat','outdoor',2);
		$c13 = $this->getChoiceCount('zt_bbs_cat','outdoor',3);
		$c14 = $this->getChoiceCount('zt_bbs_cat','outdoor',4);
		$c15 = $this->getChoiceCount('zt_bbs_cat','place',1);
		$c16 = $this->getChoiceCount('zt_bbs_cat','place',2);
		$c17 = $this->getChoiceCount('zt_bbs_cat','place',3);
		$c18 = $this->getChoiceCount('zt_bbs_cat','traffic',1);
		$c19 = $this->getChoiceCount('zt_bbs_cat','traffic',2);
		$c20 = $this->getChoiceCount('zt_bbs_cat','traffic',3);
		$c21 = $this->getChoiceCount('zt_bbs_cat','traffic',4);
		$c22 = $this->getChoiceCount('zt_bbs_cat','joinus',1);
		$c23 = $this->getChoiceCount('zt_bbs_cat','joinus',2);
		$c24 = $this->getChoiceCount('zt_bbs_cat','joinus',3);
		$c25 = $this->getChoiceCount('zt_bbs_cat','joinus',4);
		
		vendor('excel.PHPExcel');
		$fileName = $this->fileName;
		$fileName = empty($fileName)?'cat_list'.date('Y-m-d',time()):$fileName;
		$PHPExcel = new PHPExcel();
		//填入表头
		$PHPExcel->getActiveSheet()->setCellValue('A1', '选项');
		$PHPExcel->getActiveSheet()->setCellValue('B1', '答案1');
		$PHPExcel->getActiveSheet()->setCellValue('C1', '答案2');
		$PHPExcel->getActiveSheet()->setCellValue('D1', '答案3');
		$PHPExcel->getActiveSheet()->setCellValue('E1', '答案4');
		//填入列表
		$ks = 0;
		$PHPExcel->getActiveSheet()->setCellValue('A2', '你的性别');
		$PHPExcel->getActiveSheet()->setCellValue('B2', '男('.$c1.')');
		$PHPExcel->getActiveSheet()->setCellValue('C2', '女('.$c2.')');
		$PHPExcel->getActiveSheet()->setCellValue('D2', '');
		$PHPExcel->getActiveSheet()->setCellValue('E2', '');
		$ks = 1;	
		$PHPExcel->getActiveSheet()->setCellValue('A3', '你的年龄');
		$PHPExcel->getActiveSheet()->setCellValue('B3', '20岁以下('.$c3.')');
		$PHPExcel->getActiveSheet()->setCellValue('C3', '21-35岁('.$c4.')');
		$PHPExcel->getActiveSheet()->setCellValue('D3', '35-50岁('.$c5.')');
		$PHPExcel->getActiveSheet()->setCellValue('E3', '50岁以上('.$c6.')');
		$ks = 2;
		$PHPExcel->getActiveSheet()->setCellValue('A4', '你家猫咪是否是品种猫');
		$PHPExcel->getActiveSheet()->setCellValue('B4', '是('.$c7.')');
		$PHPExcel->getActiveSheet()->setCellValue('C4', '不是('.$c8.')');
		$PHPExcel->getActiveSheet()->setCellValue('D4', '');
		$PHPExcel->getActiveSheet()->setCellValue('E4', '');
		$ks = 3;
		$PHPExcel->getActiveSheet()->setCellValue('A5', '你是否愿意带猫咪出门');
		$PHPExcel->getActiveSheet()->setCellValue('B5', '愿意('.$c9.')');
		$PHPExcel->getActiveSheet()->setCellValue('C5', '不愿意('.$c10.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D5', '');
		$PHPExcel->getActiveSheet()->setCellValue('E5', '');
		$ks = 4;
		$PHPExcel->getActiveSheet()->setCellValue('A6', '如果不愿意带猫咪出门是因为什么');
		$PHPExcel->getActiveSheet()->setCellValue('B6', '猫咪担心不愿意出门('.$c11.')');
		$PHPExcel->getActiveSheet()->setCellValue('C6', '没有合适的交通工具('.$c12.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D6', '猫咪出门不好控制('.$c13.')');
		$PHPExcel->getActiveSheet()->setCellValue('E6', '没有合适的带猫场合('.$c14.')');
		$ks = 5;
		$PHPExcel->getActiveSheet()->setCellValue('A7', '如果一定要带猫咪出门你会选择去什么样的场合');
		$PHPExcel->getActiveSheet()->setCellValue('B7', '小型室内聚会场所(20人以上)('.$c15.')');
		$PHPExcel->getActiveSheet()->setCellValue('C7', '小型室内聚会场所(100人以上)('.$c16.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D7', '有草坪的公园('.$c17.')');
		$PHPExcel->getActiveSheet()->setCellValue('E7', '');
		$ks = 6;
		$PHPExcel->getActiveSheet()->setCellValue('A8', '一定要带猫咪出门你会选择什么样的交通工具');
		$PHPExcel->getActiveSheet()->setCellValue('B8', '出租('.$c18.')');
		$PHPExcel->getActiveSheet()->setCellValue('C8', '私家车('.$c19.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D8', '公交车('.$c20.')');
		$PHPExcel->getActiveSheet()->setCellValue('E8', '偷渡('.$c21.')');
		$ks = 7;
		$PHPExcel->getActiveSheet()->setCellValue('A9', '上海地区的猫主是否愿意参加我们下次的人民广场附近的猫聚');
		$PHPExcel->getActiveSheet()->setCellValue('B9', '愿意('.$c22.')');
		$PHPExcel->getActiveSheet()->setCellValue('C9', '不愿意('.$c23.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D9', '看时间('.$c24.')');
		$PHPExcel->getActiveSheet()->setCellValue('E9', '有礼物就参加('.$c25.')');
		$ks = 8;
		//保存为2003格式
		$objWriter = new PHPExcel_Writer_Excel5($PHPExcel);
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		
		//多浏览器下兼容中文标题
		$encoded_filename = urlencode($fileName);
		$ua = $_SERVER["HTTP_USER_AGENT"];
		if (preg_match("/MSIE/", $ua)) {
			header('Content-Disposition: attachment; filename="' . $encoded_filename . '.xls"');
		} else if (preg_match("/Firefox/", $ua)) {
			header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '.xls"');
		} else {
			header('Content-Disposition: attachment; filename="' . $fileName . '.xls"');
		}
		
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
	 }
	 
	 
	 //excel导出
	public function getExcleDogAndCat(){
		$c1 = $this->getChoiceCount('zt_dog_cat','have',1);
		$c2 = $this->getChoiceCount('zt_dog_cat','have',2);
		$c3 = $this->getChoiceCount('zt_dog_cat','sort',1);
		$c4 = $this->getChoiceCount('zt_dog_cat','sort',2);
		$c5 = $this->getChoiceCount('zt_dog_cat','time',1);
		$c6 = $this->getChoiceCount('zt_dog_cat','time',2);
		$c7 = $this->getChoiceCount('zt_dog_cat','time',3);
		$c8 = $this->getChoiceCount('zt_dog_cat','war',1);
		$c9 = $this->getChoiceCount('zt_dog_cat','war',2);
		$c10 = $this->getChoiceCount('zt_dog_cat','win',1);
		$c11 = $this->getChoiceCount('zt_dog_cat','win',2);
		$c12 = $this->getChoiceCount('zt_dog_cat','win',3);
		$c13 = $this->getChoiceCount('zt_dog_cat','habit',1);
		$c14 = $this->getChoiceCount('zt_dog_cat','habit',2);
		$c15 = $this->getChoiceCount('zt_dog_cat','habit',3);
		$c16 = $this->getChoiceCount('zt_dog_cat','send',1);
		$c17 = $this->getChoiceCount('zt_dog_cat','send',2);
		$c18 = $this->getChoiceCount('zt_dog_cat','send',3);
		$c19 = $this->getChoiceCount('zt_dog_cat','memory',1);
		$c20 = $this->getChoiceCount('zt_dog_cat','memory',2);
		$c21 = $this->getChoiceCount('zt_dog_cat','memory',3);
		
		vendor('excel.PHPExcel');
		$fileName = $this->fileName;
		$fileName = empty($fileName)?'dog_cat_list'.date('Y-m-d',time()):$fileName;
		$PHPExcel = new PHPExcel();
		//填入表头
		$PHPExcel->getActiveSheet()->setCellValue('A1', '选项');
		$PHPExcel->getActiveSheet()->setCellValue('B1', '答案1');
		$PHPExcel->getActiveSheet()->setCellValue('C1', '答案2');
		$PHPExcel->getActiveSheet()->setCellValue('D1', '答案3');
		$PHPExcel->getActiveSheet()->setCellValue('E1', '答案4');
		//填入列表
		$ks = 0;
		$PHPExcel->getActiveSheet()->setCellValue('A2', '你家是否有猫有狗');
		$PHPExcel->getActiveSheet()->setCellValue('B2', '猫狗一家其乐融融('.$c1.')');
		$PHPExcel->getActiveSheet()->setCellValue('C2', '一个物种已经很头疼了('.$c2.')');
		$PHPExcel->getActiveSheet()->setCellValue('D2', '');
		$PHPExcel->getActiveSheet()->setCellValue('E2', '');
		$ks = 1;	
		$PHPExcel->getActiveSheet()->setCellValue('A3', '猫狗到家的顺序是怎样的');
		$PHPExcel->getActiveSheet()->setCellValue('B3', '猫先到家('.$c3.')');
		$PHPExcel->getActiveSheet()->setCellValue('C3', '狗先到家('.$c4.')');
		$PHPExcel->getActiveSheet()->setCellValue('D3', '');
		$PHPExcel->getActiveSheet()->setCellValue('E3', '');
		$ks = 2;
		$PHPExcel->getActiveSheet()->setCellValue('A4', '后来者多久才适应新环境');
		$PHPExcel->getActiveSheet()->setCellValue('B4', '完全没有不适应('.$c5.')');
		$PHPExcel->getActiveSheet()->setCellValue('C4', '隔了很久才适应('.$c6.')');
		$PHPExcel->getActiveSheet()->setCellValue('D4', '至今还没有接受另一半的存在('.$c7.')');
		$PHPExcel->getActiveSheet()->setCellValue('E4', '');
		$ks = 3;
		$PHPExcel->getActiveSheet()->setCellValue('A5', '猫狗大战经常上演吗');
		$PHPExcel->getActiveSheet()->setCellValue('B5', '经常打的毛毛满天飞('.$c8.')');
		$PHPExcel->getActiveSheet()->setCellValue('C5', '猫狗和谐相处就是我们家('.$c9.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D5', '');
		$PHPExcel->getActiveSheet()->setCellValue('E5', '');
		$ks = 4;
		$PHPExcel->getActiveSheet()->setCellValue('A6', '猫狗大战最后谁获胜');
		$PHPExcel->getActiveSheet()->setCellValue('B6', '当之无愧猫获胜('.$c10.')');
		$PHPExcel->getActiveSheet()->setCellValue('C6', '狗狗才是小霸王('.$c11.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D6', '还没开打主人就要出门调('.$c12.')');
		$PHPExcel->getActiveSheet()->setCellValue('E6', '');
		$ks = 5;
		$PHPExcel->getActiveSheet()->setCellValue('A7', '猫咪和狗狗吃饭习惯是怎样的');
		$PHPExcel->getActiveSheet()->setCellValue('B7', '猫盆放高处，狗盆放低处('.$c13.')');
		$PHPExcel->getActiveSheet()->setCellValue('C7', '猫盆狗盆放一起('.$c14.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D7', '猫吃狗粮，狗吃猫粮('.$c15.')');
		$PHPExcel->getActiveSheet()->setCellValue('E7', '');
		$ks = 6;
		$PHPExcel->getActiveSheet()->setCellValue('A8', '是否考虑两只不和而考虑送走另外一只');
		$PHPExcel->getActiveSheet()->setCellValue('B8', '守得云开见月明，从来没考虑过('.$c16.')');
		$PHPExcel->getActiveSheet()->setCellValue('C8', '曾有那么考虑过，终究没送走('.$c17.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D8', '打的实在太厉害，不得已已经送走('.$c18.')');
		$PHPExcel->getActiveSheet()->setCellValue('E8', '');
		$ks = 7;
		$PHPExcel->getActiveSheet()->setCellValue('A9', '猫狗一起养是否给你带来了美好的回忆');
		$PHPExcel->getActiveSheet()->setCellValue('B9', '和谐相处，都是心头肉('.$c19.')');
		$PHPExcel->getActiveSheet()->setCellValue('C9', '如果有的选，还是一个物种好('.$c20.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D9', '虽然很辛苦，但是很乐意('.$c21.')');
		$PHPExcel->getActiveSheet()->setCellValue('E9', '');
		$ks = 8;
		//保存为2003格式
		$objWriter = new PHPExcel_Writer_Excel5($PHPExcel);
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		
		//多浏览器下兼容中文标题
		$encoded_filename = urlencode($fileName);
		$ua = $_SERVER["HTTP_USER_AGENT"];
		if (preg_match("/MSIE/", $ua)) {
			header('Content-Disposition: attachment; filename="' . $encoded_filename . '.xls"');
		} else if (preg_match("/Firefox/", $ua)) {
			header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '.xls"');
		} else {
			header('Content-Disposition: attachment; filename="' . $fileName . '.xls"');
		}
		
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
	 }
	 
	 //excel导出
	public function getExcleCatBehavior(){
		$c1 = $this->getChoiceCount2('zt_baike_cat_behavior','gender',1);
		$c2 = $this->getChoiceCount2('zt_baike_cat_behavior','gender',2);
		$c3 = $this->getChoiceCount2('zt_baike_cat_behavior','age',1);
		$c4 = $this->getChoiceCount2('zt_baike_cat_behavior','age',2);
		$c5 = $this->getChoiceCount2('zt_baike_cat_behavior','age',3);
		$c6 = $this->getChoiceCount2('zt_baike_cat_behavior','age',4);
		$c7 = $this->getChoiceCount2('zt_baike_cat_behavior','understanding',1);
		$c8 = $this->getChoiceCount2('zt_baike_cat_behavior','understanding',2);
		$c9 = $this->getChoiceCount2('zt_baike_cat_behavior','understanding',3);
		$c10 = $this->getChoiceCount2('zt_baike_cat_behavior','understanding',4);
		$c11 = $this->getChoiceCount2('zt_baike_cat_behavior','do',1);
		$c12 = $this->getChoiceCount2('zt_baike_cat_behavior','do',2);
		$c13 = $this->getChoiceCount2('zt_baike_cat_behavior','do',3);
		$c14 = $this->getChoiceCount2('zt_baike_cat_behavior','do',4);
		$c15 = $this->getChoiceCount2('zt_baike_cat_behavior','do',5);
		$c16 = $this->getChoiceCount2('zt_baike_cat_behavior','mean',1);
		$c17 = $this->getChoiceCount2('zt_baike_cat_behavior','mean',2);
		$c18 = $this->getChoiceCount2('zt_baike_cat_behavior','mean',3);
		$c19 = $this->getChoiceCount2('zt_baike_cat_behavior','mean',4);
		$c20 = $this->getChoiceCount2('zt_baike_cat_behavior','unusual',1);
		$c21 = $this->getChoiceCount2('zt_baike_cat_behavior','unusual',2);
		$c22 = $this->getChoiceCount2('zt_baike_cat_behavior','unusual',3);
		$c23 = $this->getChoiceCount2('zt_baike_cat_behavior','reason',1);
		$c24 = $this->getChoiceCount2('zt_baike_cat_behavior','reason',2);
		$c25 = $this->getChoiceCount2('zt_baike_cat_behavior','help',1);
		$c26 = $this->getChoiceCount2('zt_baike_cat_behavior','help',2);
		
		vendor('excel.PHPExcel');
		$fileName = $this->fileName;
		$fileName = empty($fileName)?'cat_behavior_list'.date('Y-m-d',time()):$fileName;
		$PHPExcel = new PHPExcel();
		//填入表头
		$PHPExcel->getActiveSheet()->setCellValue('A1', '选项');
		$PHPExcel->getActiveSheet()->setCellValue('B1', '答案1');
		$PHPExcel->getActiveSheet()->setCellValue('C1', '答案2');
		$PHPExcel->getActiveSheet()->setCellValue('D1', '答案3');
		$PHPExcel->getActiveSheet()->setCellValue('E1', '答案4');
		$PHPExcel->getActiveSheet()->setCellValue('F1', '答案5');
		//填入列表
		$ks = 0;
		$PHPExcel->getActiveSheet()->setCellValue('A2', '您的性别');
		$PHPExcel->getActiveSheet()->setCellValue('B2', '男('.$c1.')');
		$PHPExcel->getActiveSheet()->setCellValue('C2', '女('.$c2.')');
		$PHPExcel->getActiveSheet()->setCellValue('D2', '');
		$PHPExcel->getActiveSheet()->setCellValue('E2', '');
		$PHPExcel->getActiveSheet()->setCellValue('F2', '');
		$ks = 1;	
		$PHPExcel->getActiveSheet()->setCellValue('A3', '您的年龄');
		$PHPExcel->getActiveSheet()->setCellValue('B3', '18岁以下('.$c3.')');
		$PHPExcel->getActiveSheet()->setCellValue('C3', '19-40岁('.$c4.')');
		$PHPExcel->getActiveSheet()->setCellValue('D3', '41-60岁('.$c5.')');
		$PHPExcel->getActiveSheet()->setCellValue('E3', '60岁以上('.$c6.')');
		$PHPExcel->getActiveSheet()->setCellValue('F3', '');
		$ks = 2;
		$PHPExcel->getActiveSheet()->setCellValue('A4', '您觉得自己交接猫咪的各种行为吗');
		$PHPExcel->getActiveSheet()->setCellValue('B4', '不了解('.$c7.')');
		$PHPExcel->getActiveSheet()->setCellValue('C4', '了解一些('.$c8.')');
		$PHPExcel->getActiveSheet()->setCellValue('D4', '大部分都能懂('.$c9.')');
		$PHPExcel->getActiveSheet()->setCellValue('E4', '完全了解('.$c10.')');
		$PHPExcel->getActiveSheet()->setCellValue('F4', '');
		$ks = 3;
		$PHPExcel->getActiveSheet()->setCellValue('A5', '猫咪出现您不了解的行为时，您会');
		$PHPExcel->getActiveSheet()->setCellValue('B5', '置之不理('.$c11.')');
		$PHPExcel->getActiveSheet()->setCellValue('C5', '观察后续状态('.$c12.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D5', '询问猫友('.$c13.')');
		$PHPExcel->getActiveSheet()->setCellValue('E5', '询问宠物医生('.$c14.')');
		$PHPExcel->getActiveSheet()->setCellValue('F5', '上网查阅资料('.$c15.')');
		$ks = 4;
		$PHPExcel->getActiveSheet()->setCellValue('A6', '您是如何了解猫咪行为背后的含义');
		$PHPExcel->getActiveSheet()->setCellValue('B6', '网络('.$c16.')');
		$PHPExcel->getActiveSheet()->setCellValue('C6', '宠物医生('.$c17.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D6', '猫友('.$c18.')');
		$PHPExcel->getActiveSheet()->setCellValue('E6', '身边人的口口相传('.$c19.')');
		$PHPExcel->getActiveSheet()->setCellValue('F6', '');
		$ks = 5;
		$PHPExcel->getActiveSheet()->setCellValue('A7', '当猫咪出现异常行为时，您最先会');
		$PHPExcel->getActiveSheet()->setCellValue('B7', '询问宠物医生('.$c20.')');
		$PHPExcel->getActiveSheet()->setCellValue('C7', '询问猫友('.$c21.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D7', '观察一阵再说('.$c22.')');
		$PHPExcel->getActiveSheet()->setCellValue('E7', '');
		$PHPExcel->getActiveSheet()->setCellValue('F7', '');
		$ks = 6;
		$PHPExcel->getActiveSheet()->setCellValue('A8', '猫咪出现异常行为时，您会找出真正原因吗');
		$PHPExcel->getActiveSheet()->setCellValue('B8', '会('.$c23.')');
		$PHPExcel->getActiveSheet()->setCellValue('C8', '看情况('.$c24.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D8', '');
		$PHPExcel->getActiveSheet()->setCellValue('E8', '');
		$PHPExcel->getActiveSheet()->setCellValue('F8', '');
		$ks = 7;
		$PHPExcel->getActiveSheet()->setCellValue('A9', '您会帮助猫咪改变异常行为吗');
		$PHPExcel->getActiveSheet()->setCellValue('B9', '会('.$c25.')');
		$PHPExcel->getActiveSheet()->setCellValue('C9', '不影响正常生活就无所谓('.$c26.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D9', '');
		$PHPExcel->getActiveSheet()->setCellValue('E9', '');
		$PHPExcel->getActiveSheet()->setCellValue('F9', '');
		$ks = 8;
		//保存为2003格式
		$objWriter = new PHPExcel_Writer_Excel5($PHPExcel);
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		
		//多浏览器下兼容中文标题
		$encoded_filename = urlencode($fileName);
		$ua = $_SERVER["HTTP_USER_AGENT"];
		if (preg_match("/MSIE/", $ua)) {
			header('Content-Disposition: attachment; filename="' . $encoded_filename . '.xls"');
		} else if (preg_match("/Firefox/", $ua)) {
			header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '.xls"');
		} else {
			header('Content-Disposition: attachment; filename="' . $fileName . '.xls"');
		}
		
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
	 }
	 
	 //excel导出
	public function getExcleSterilization(){
		$c1 = $this->getChoiceCount('zt_pet_sterilization','style',1);
		$c2 = $this->getChoiceCount('zt_pet_sterilization','style',2);
		$c3 = $this->getChoiceCount('zt_pet_sterilization','style',3);
		$c4 = $this->getChoiceCount('zt_pet_sterilization','age',1);
		$c5 = $this->getChoiceCount('zt_pet_sterilization','age',2);
		$c6 = $this->getChoiceCount('zt_pet_sterilization','age',3);
		$c7 = $this->getChoiceCount('zt_pet_sterilization','gender',1);
		$c8 = $this->getChoiceCount('zt_pet_sterilization','gender',2);
		$c9 = $this->getChoiceCount('zt_pet_sterilization','understand',1);
		$c10 = $this->getChoiceCount('zt_pet_sterilization','understand',2);
		$c11 = $this->getChoiceCount('zt_pet_sterilization','understand',3);
		$c12 = $this->getChoiceCount('zt_pet_sterilization','sterilization',1);
		$c13 = $this->getChoiceCount('zt_pet_sterilization','sterilization',2);
		$c14 = $this->getChoiceCount('zt_pet_sterilization','suggest',1);
		$c15 = $this->getChoiceCount('zt_pet_sterilization','suggest',2);
		$c16 = $this->getChoiceCount('zt_pet_sterilization','will',1);
		$c17 = $this->getChoiceCount('zt_pet_sterilization','will',2);
		$c18 = $this->getChoiceCount('zt_pet_sterilization','will',3);
		$c19 = $this->getChoiceCount('zt_pet_sterilization','view',1);
		$c20 = $this->getChoiceCount('zt_pet_sterilization','view',2);
		$c21 = $this->getChoiceCount('zt_pet_sterilization','view',3);
		
		vendor('excel.PHPExcel');
		$fileName = $this->fileName;
		$fileName = empty($fileName)?'pet_sterilization'.date('Y-m-d',time()):$fileName;
		$PHPExcel = new PHPExcel();
		//填入表头
		$PHPExcel->getActiveSheet()->setCellValue('A1', '选项');
		$PHPExcel->getActiveSheet()->setCellValue('B1', '答案1');
		$PHPExcel->getActiveSheet()->setCellValue('C1', '答案2');
		$PHPExcel->getActiveSheet()->setCellValue('D1', '答案3');
		$PHPExcel->getActiveSheet()->setCellValue('E1', '答案4');
		//填入列表
		$ks = 0;
		$PHPExcel->getActiveSheet()->setCellValue('A2', '您爱宠的类型是');
		$PHPExcel->getActiveSheet()->setCellValue('B2', '喵星人('.$c1.')');
		$PHPExcel->getActiveSheet()->setCellValue('C2', '汪星人('.$c2.')');
		$PHPExcel->getActiveSheet()->setCellValue('D2', '其他萌宠('.$c3.')');
		$PHPExcel->getActiveSheet()->setCellValue('E2', '');
		$ks = 1;	
		$PHPExcel->getActiveSheet()->setCellValue('A3', '您爱宠的年龄是');
		$PHPExcel->getActiveSheet()->setCellValue('B3', '1-3岁('.$c4.')');
		$PHPExcel->getActiveSheet()->setCellValue('C3', '3-6岁('.$c5.')');
		$PHPExcel->getActiveSheet()->setCellValue('D3', '6岁及以上('.$c6.')');
		$PHPExcel->getActiveSheet()->setCellValue('E3', '');
		$ks = 2;
		$PHPExcel->getActiveSheet()->setCellValue('A4', '您爱宠的性别是');
		$PHPExcel->getActiveSheet()->setCellValue('B4', '公('.$c7.')');
		$PHPExcel->getActiveSheet()->setCellValue('C4', '母('.$c8.')');
		$PHPExcel->getActiveSheet()->setCellValue('D4', '');
		$PHPExcel->getActiveSheet()->setCellValue('E4', '');
		$ks = 3;
		$PHPExcel->getActiveSheet()->setCellValue('A5', '您了解过绝育手术吗');
		$PHPExcel->getActiveSheet()->setCellValue('B5', '从来没有('.$c9.')');
		$PHPExcel->getActiveSheet()->setCellValue('C5', '了解过，但是不全面('.$c10.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D5', '非常了解('.$c11.')');
		$PHPExcel->getActiveSheet()->setCellValue('E5', '');
		$ks = 4;
		$PHPExcel->getActiveSheet()->setCellValue('A6', '您的爱宠做过绝育手术吗');
		$PHPExcel->getActiveSheet()->setCellValue('B6', '有('.$c12.')');
		$PHPExcel->getActiveSheet()->setCellValue('C6', '没有('.$c13.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D6', '');
		$PHPExcel->getActiveSheet()->setCellValue('E6', '');
		$ks = 5;
		$PHPExcel->getActiveSheet()->setCellValue('A7', '您建议其他主人带着爱宠去做绝育手术吗');
		$PHPExcel->getActiveSheet()->setCellValue('B7', '爱宠回复很好，建议('.$c14.')');
		$PHPExcel->getActiveSheet()->setCellValue('C7', '效果不明显，不建议('.$c15.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D7', '');
		$PHPExcel->getActiveSheet()->setCellValue('E7', '');
		$ks = 6;
		$PHPExcel->getActiveSheet()->setCellValue('A8', '您会带着爱宠去做绝育手术吗');
		$PHPExcel->getActiveSheet()->setCellValue('B8', '了解的很详细，会去的('.$c16.')');
		$PHPExcel->getActiveSheet()->setCellValue('C8', '怕出现意外，不会去的('.$c17.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D8', '不赞成给宠物做绝育，剥夺了他们的权利('.$c18.')');
		$PHPExcel->getActiveSheet()->setCellValue('E8', '');
		$ks = 7;
		$PHPExcel->getActiveSheet()->setCellValue('A9', '对于宠物绝育手术，您的看法是');
		$PHPExcel->getActiveSheet()->setCellValue('B9', '太残忍，不能接受('.$c19.')');
		$PHPExcel->getActiveSheet()->setCellValue('C9', '对宠物健康有利，支持('.$c20.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D9', '其他，可文字说明('.$c21.')');
		$PHPExcel->getActiveSheet()->setCellValue('E9', '');
		$ks = 8;
		//保存为2003格式
		$objWriter = new PHPExcel_Writer_Excel5($PHPExcel);
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		
		//多浏览器下兼容中文标题
		$encoded_filename = urlencode($fileName);
		$ua = $_SERVER["HTTP_USER_AGENT"];
		if (preg_match("/MSIE/", $ua)) {
			header('Content-Disposition: attachment; filename="' . $encoded_filename . '.xls"');
		} else if (preg_match("/Firefox/", $ua)) {
			header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '.xls"');
		} else {
			header('Content-Disposition: attachment; filename="' . $fileName . '.xls"');
		}
		
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
	 }
	 
	//excel导出
	public function getExcleGui(){
		$c1 = $this->getChoiceCount2('zt_gui','gender',1);
		$c2 = $this->getChoiceCount2('zt_gui','gender',2);
		$c3 = $this->getChoiceCount2('zt_gui','age',1);
		$c4 = $this->getChoiceCount2('zt_gui','age',2);
		$c5 = $this->getChoiceCount2('zt_gui','age',3);
		$c6 = $this->getChoiceCount2('zt_gui','age',4);
		$c7 = $this->getChoiceCount2('zt_gui','will',1);
		$c8 = $this->getChoiceCount2('zt_gui','will',2);
		$c9 = $this->getChoiceCount2('zt_gui','have',1);
		$c10 = $this->getChoiceCount2('zt_gui','have',2);
		$c11 = $this->getChoiceCount2('zt_gui','have',3);
		$c12 = $this->getChoiceCount2('zt_gui','reason',1);
		$c13 = $this->getChoiceCount2('zt_gui','reason',2);
		$c14 = $this->getChoiceCount2('zt_gui','reason',3);
		$c15 = $this->getChoiceCount2('zt_gui','reason',4);
		$c16 = $this->getChoiceCount2('zt_gui','reason',5);
		$c17 = $this->getChoiceCount2('zt_gui','learn',1);
		$c18 = $this->getChoiceCount2('zt_gui','learn',2);
		$c19 = $this->getChoiceCount2('zt_gui','learn',3);
		$c20 = $this->getChoiceCount2('zt_gui','learn',4);
		$c21 = $this->getChoiceCount2('zt_gui','learn',5);
		$c22 = $this->getChoiceCount2('zt_gui','communication',1);
		$c23 = $this->getChoiceCount2('zt_gui','communication',2);
		$c24 = $this->getChoiceCount2('zt_gui','why',1);
		$c25 = $this->getChoiceCount2('zt_gui','why',2);
		$c26 = $this->getChoiceCount2('zt_gui','why',3);
		
		vendor('excel.PHPExcel');
		$fileName = $this->fileName;
		$fileName = empty($fileName)?'pet_gui'.date('Y-m-d',time()):$fileName;
		$PHPExcel = new PHPExcel();
		//填入表头
		$PHPExcel->getActiveSheet()->setCellValue('A1', '选项');
		$PHPExcel->getActiveSheet()->setCellValue('B1', '答案1');
		$PHPExcel->getActiveSheet()->setCellValue('C1', '答案2');
		$PHPExcel->getActiveSheet()->setCellValue('D1', '答案3');
		$PHPExcel->getActiveSheet()->setCellValue('E1', '答案4');
		$PHPExcel->getActiveSheet()->setCellValue('F1', '答案5');
		//填入列表
		$ks = 0;
		$PHPExcel->getActiveSheet()->setCellValue('A2', '您的性别是');
		$PHPExcel->getActiveSheet()->setCellValue('B2', '男('.$c1.')');
		$PHPExcel->getActiveSheet()->setCellValue('C2', '女('.$c2.')');
		$PHPExcel->getActiveSheet()->setCellValue('D2', '');
		$PHPExcel->getActiveSheet()->setCellValue('E2', '');
		$PHPExcel->getActiveSheet()->setCellValue('F2', '');
		$ks = 1;	
		$PHPExcel->getActiveSheet()->setCellValue('A3', '您的年龄是');
		$PHPExcel->getActiveSheet()->setCellValue('B3', '18岁以下('.$c3.')');
		$PHPExcel->getActiveSheet()->setCellValue('C3', '19-40岁('.$c4.')');
		$PHPExcel->getActiveSheet()->setCellValue('D3', '41-60岁('.$c5.')');
		$PHPExcel->getActiveSheet()->setCellValue('E3', '60岁以上('.$c6.')');
		$PHPExcel->getActiveSheet()->setCellValue('F3', '');
		$ks = 2;
		$PHPExcel->getActiveSheet()->setCellValue('A4', '您会选择龟做宠物吗');
		$PHPExcel->getActiveSheet()->setCellValue('B4', '会('.$c7.')');
		$PHPExcel->getActiveSheet()->setCellValue('C4', '不会('.$c8.')');
		$PHPExcel->getActiveSheet()->setCellValue('D4', '');
		$PHPExcel->getActiveSheet()->setCellValue('E4', '');
		$PHPExcel->getActiveSheet()->setCellValue('F4', '');
		$ks = 3;
		$PHPExcel->getActiveSheet()->setCellValue('A5', '您有饲养宠物龟的经验吗');
		$PHPExcel->getActiveSheet()->setCellValue('B5', '有，并且现在正在养('.$c9.')');
		$PHPExcel->getActiveSheet()->setCellValue('C5', '有过，但现在没有在养('.$c10.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D5', '没有('.$c11.')');
		$PHPExcel->getActiveSheet()->setCellValue('E5', '');
		$PHPExcel->getActiveSheet()->setCellValue('F5', '');
		$ks = 4;
		$PHPExcel->getActiveSheet()->setCellValue('A6', '不会饲养龟做宠物的理由是');
		$PHPExcel->getActiveSheet()->setCellValue('B6', '单纯地不喜欢('.$c12.')');
		$PHPExcel->getActiveSheet()->setCellValue('C6', '条件不允许('.$c13.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D6', '家人不同意('.$c14.')');
		$PHPExcel->getActiveSheet()->setCellValue('E6', '曾经养过('.$c15.')');
		$PHPExcel->getActiveSheet()->setCellValue('F6', '我会养宠物龟('.$c16.')');
		$ks = 5;
		$PHPExcel->getActiveSheet()->setCellValue('A7', '您通过哪种渠道学习宠物龟的相关知识的');
		$PHPExcel->getActiveSheet()->setCellValue('B7', '网络('.$c17.')');
		$PHPExcel->getActiveSheet()->setCellValue('C7', '宠物医生('.$c18.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D7', '龟友('.$c19.')');
		$PHPExcel->getActiveSheet()->setCellValue('E7', '看书('.$c20.')');
		$PHPExcel->getActiveSheet()->setCellValue('F7', '身边人的口口相传('.$c21.')');
		$ks = 6;
		$PHPExcel->getActiveSheet()->setCellValue('A8', '您是否会通过网络渠道（比如论坛等，即时通讯工具除外）跟龟友交流');
		$PHPExcel->getActiveSheet()->setCellValue('B8', '会（结束调查） ('.$c22.')');
		$PHPExcel->getActiveSheet()->setCellValue('C8', '不会（请回答第8题）('.$c23.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D8', '');
		$PHPExcel->getActiveSheet()->setCellValue('E8', '');
		$PHPExcel->getActiveSheet()->setCellValue('F8', '');
		$ks = 7;
		$PHPExcel->getActiveSheet()->setCellValue('A9', '为什么不会通过网络渠道与龟友交流');
		$PHPExcel->getActiveSheet()->setCellValue('B9', '人气不够('.$c24.')');
		$PHPExcel->getActiveSheet()->setCellValue('C9', '无法获得想要的信息('.$c25.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D9', '麻烦('.$c26.')');
		$PHPExcel->getActiveSheet()->setCellValue('E9', '');
		$PHPExcel->getActiveSheet()->setCellValue('F9', '');
		$ks = 8;
		//保存为2003格式
		$objWriter = new PHPExcel_Writer_Excel5($PHPExcel);
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		
		//多浏览器下兼容中文标题
		$encoded_filename = urlencode($fileName);
		$ua = $_SERVER["HTTP_USER_AGENT"];
		if (preg_match("/MSIE/", $ua)) {
			header('Content-Disposition: attachment; filename="' . $encoded_filename . '.xls"');
		} else if (preg_match("/Firefox/", $ua)) {
			header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '.xls"');
		} else {
			header('Content-Disposition: attachment; filename="' . $fileName . '.xls"');
		}
		
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
	 }
	 
	 //excel导出
	public function getExcleStyle(){
		$c1 = $this->getChoiceCount2('zt_sensitive','c1',1);
		$c2 = $this->getChoiceCount2('zt_sensitive','c1',2);
		$c3 = $this->getChoiceCount2('zt_sensitive','c1',3);
		$c4 = $this->getChoiceCount2('zt_sensitive','c1',4);
		$c5 = $this->getChoiceCount2('zt_sensitive','c2',1);
		$c6 = $this->getChoiceCount2('zt_sensitive','c2',2);
		$c7 = $this->getChoiceCount2('zt_sensitive','c2',3);
		$c8 = $this->getChoiceCount2('zt_sensitive','c3',1);
		$c9 = $this->getChoiceCount2('zt_sensitive','c3',2);
		$c10 = $this->getChoiceCount2('zt_sensitive','c3',3);
		$c11 = $this->getChoiceCount2('zt_sensitive','c4',1);
		$c12 = $this->getChoiceCount2('zt_sensitive','c4',2);
		$c13 = $this->getChoiceCount2('zt_sensitive','c4',3);
		$c14 = $this->getChoiceCount2('zt_sensitive','c4',4);
		$c15 = $this->getChoiceCount2('zt_sensitive','c5',1);
		$c16 = $this->getChoiceCount2('zt_sensitive','c5',2);
		$c17 = $this->getChoiceCount2('zt_sensitive','c5',3);
		$c18 = $this->getChoiceCount2('zt_sensitive','c5',4);
		$c19 = $this->getChoiceCount2('zt_sensitive','c6',1);
		$c20 = $this->getChoiceCount2('zt_sensitive','c6',2);
		$c21 = $this->getChoiceCount2('zt_sensitive','c6',3);
		$c22 = $this->getChoiceCount2('zt_sensitive','c7',1);
		$c23 = $this->getChoiceCount2('zt_sensitive','c7',2);
		
		vendor('excel.PHPExcel');
		$fileName = $this->fileName;
		$fileName = empty($fileName)?'pet_style'.date('Y-m-d',time()):$fileName;
		$PHPExcel = new PHPExcel();
		//填入表头
		$PHPExcel->getActiveSheet()->setCellValue('A1', '选项');
		$PHPExcel->getActiveSheet()->setCellValue('B1', '答案1');
		$PHPExcel->getActiveSheet()->setCellValue('C1', '答案2');
		$PHPExcel->getActiveSheet()->setCellValue('D1', '答案3');
		$PHPExcel->getActiveSheet()->setCellValue('E1', '答案4');
		//填入列表
		$ks = 0;
		$PHPExcel->getActiveSheet()->setCellValue('A2', '您一般隔多久给宠物洗澡');
		$PHPExcel->getActiveSheet()->setCellValue('B2', '一周1次('.$c1.')');
		$PHPExcel->getActiveSheet()->setCellValue('C2', '半个月1次('.$c2.')');
		$PHPExcel->getActiveSheet()->setCellValue('D2', '一个月1次('.$c3.')');
		$PHPExcel->getActiveSheet()->setCellValue('E2', '超过一个月('.$c4.')');
		$ks = 1;	
		$PHPExcel->getActiveSheet()->setCellValue('A3', '您一般去哪里给宠物做清洁护理');
		$PHPExcel->getActiveSheet()->setCellValue('B3', '只在宠物店('.$c5.')');
		$PHPExcel->getActiveSheet()->setCellValue('C3', '自己在家给宠物洗澡('.$c6.')');
		$PHPExcel->getActiveSheet()->setCellValue('D3', '宠物店、家里交替做('.$c7.')');
		$PHPExcel->getActiveSheet()->setCellValue('E3', '');
		$ks = 2;
		$PHPExcel->getActiveSheet()->setCellValue('A4', '您去宠物店的消费方式是');
		$PHPExcel->getActiveSheet()->setCellValue('B4', '办宠物店的会员卡('.$c8.')');
		$PHPExcel->getActiveSheet()->setCellValue('C4', '直接在宠物店消费，不办理会员卡('.$c9.')');
		$PHPExcel->getActiveSheet()->setCellValue('D4', '在网上团购服务('.$c10.')');
		$PHPExcel->getActiveSheet()->setCellValue('E4', '');
		$ks = 3;
		$PHPExcel->getActiveSheet()->setCellValue('A5', '您一般多久给宠物做一次美容造型');
		$PHPExcel->getActiveSheet()->setCellValue('B5', '1-2个月1次('.$c11.')');
		$PHPExcel->getActiveSheet()->setCellValue('C5', '半年一次('.$c12.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D5', '一年一次('.$c13.')');
		$PHPExcel->getActiveSheet()->setCellValue('E5', '从不做造型('.$c14.')');
		$ks = 4;
		$PHPExcel->getActiveSheet()->setCellValue('A6', '什么原因会让你选择换美容店');
		$PHPExcel->getActiveSheet()->setCellValue('B6', '服务态度('.$c15.')');
		$PHPExcel->getActiveSheet()->setCellValue('C6', '修剪水平('.$c16.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D6', '价格过高('.$c17.')');
		$PHPExcel->getActiveSheet()->setCellValue('E6', '其他('.$c18.')');
		$ks = 5;
		$PHPExcel->getActiveSheet()->setCellValue('A7', '您是否自己在家里尝试过给狗狗做美容造型');
		$PHPExcel->getActiveSheet()->setCellValue('B7', '有过，但是很快放弃了('.$c19.')');
		$PHPExcel->getActiveSheet()->setCellValue('C7', '一直都是自己动手的作品('.$c20.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D7', '从来不敢自己弄('.$c21.')');
		$PHPExcel->getActiveSheet()->setCellValue('E7', '');
		$ks = 6;
		$PHPExcel->getActiveSheet()->setCellValue('A8', '您会推荐给狗友自己觉得的比较好的宠物店吗');
		$PHPExcel->getActiveSheet()->setCellValue('B8', '会，好东西要大家分享 ('.$c22.')');
		$PHPExcel->getActiveSheet()->setCellValue('C8', '不会('.$c23.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D8', '');
		$PHPExcel->getActiveSheet()->setCellValue('E8', '');
		$ks = 7;
		//保存为2003格式
		$objWriter = new PHPExcel_Writer_Excel5($PHPExcel);
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		
		//多浏览器下兼容中文标题
		$encoded_filename = urlencode($fileName);
		$ua = $_SERVER["HTTP_USER_AGENT"];
		if (preg_match("/MSIE/", $ua)) {
			header('Content-Disposition: attachment; filename="' . $encoded_filename . '.xls"');
		} else if (preg_match("/Firefox/", $ua)) {
			header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '.xls"');
		} else {
			header('Content-Disposition: attachment; filename="' . $fileName . '.xls"');
		}
		
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
	 }
	 
	  //excel导出
	public function getExcleQuChong(){
		$c1 = $this->getChoiceCount3('zt_sensitive','c1',1,8);
		$c2 = $this->getChoiceCount3('zt_sensitive','c1',2,8);
		$c3 = $this->getChoiceCount3('zt_sensitive','c1',3,8);
		$c4 = $this->getChoiceCount3('zt_sensitive','c2',1,8);
		$c5 = $this->getChoiceCount3('zt_sensitive','c2',2,8);
		$c6 = $this->getChoiceCount3('zt_sensitive','c2',3,8);
		$c7 = $this->getChoiceCount3('zt_sensitive','c3',1,8);
		$c8 = $this->getChoiceCount3('zt_sensitive','c3',2,8);
		$c9 = $this->getChoiceCount3('zt_sensitive','c3',3,8);
		$c10 = $this->getChoiceCount3('zt_sensitive','c4',1,8);
		$c11 = $this->getChoiceCount3('zt_sensitive','c4',2,8);
		$c12 = $this->getChoiceCount3('zt_sensitive','c5',1,8);
		$c13 = $this->getChoiceCount3('zt_sensitive','c5',2,8);
		$c14 = $this->getChoiceCount3('zt_sensitive','c6',1,8);
		$c15 = $this->getChoiceCount3('zt_sensitive','c6',2,8);
		$c16 = $this->getChoiceCount3('zt_sensitive','c7',1,8);
		$c17 = $this->getChoiceCount3('zt_sensitive','c7',2,8);
		
		vendor('excel.PHPExcel');
		$fileName = $this->fileName;
		$fileName = empty($fileName)?'pet_quchong'.date('Y-m-d',time()):$fileName;
		$PHPExcel = new PHPExcel();
		//填入表头
		$PHPExcel->getActiveSheet()->setCellValue('A1', '选项');
		$PHPExcel->getActiveSheet()->setCellValue('B1', '答案1');
		$PHPExcel->getActiveSheet()->setCellValue('C1', '答案2');
		$PHPExcel->getActiveSheet()->setCellValue('D1', '答案3');
		$PHPExcel->getActiveSheet()->setCellValue('E1', '答案4');
		//填入列表
		$ks = 0;
		$PHPExcel->getActiveSheet()->setCellValue('A2', '您爱宠的类型是');
		$PHPExcel->getActiveSheet()->setCellValue('B2', '喵星人('.$c1.')');
		$PHPExcel->getActiveSheet()->setCellValue('C2', '汪星人('.$c2.')');
		$PHPExcel->getActiveSheet()->setCellValue('D2', '其他萌宠('.$c3.')');
		$PHPExcel->getActiveSheet()->setCellValue('E2', '');
		$ks = 1;	
		$PHPExcel->getActiveSheet()->setCellValue('A3', '您爱宠的年龄是');
		$PHPExcel->getActiveSheet()->setCellValue('B3', '3岁以下('.$c4.')');
		$PHPExcel->getActiveSheet()->setCellValue('C3', '3~6岁('.$c5.')');
		$PHPExcel->getActiveSheet()->setCellValue('D3', '6岁及以上('.$c6.')');
		$PHPExcel->getActiveSheet()->setCellValue('E3', '');
		$ks = 2;
		$PHPExcel->getActiveSheet()->setCellValue('A4', '您了解过寄生虫的危害性吗');
		$PHPExcel->getActiveSheet()->setCellValue('B4', '不了解('.$c7.')');
		$PHPExcel->getActiveSheet()->setCellValue('C4', '了解，但是不多('.$c8.')');
		$PHPExcel->getActiveSheet()->setCellValue('D4', '非常了解('.$c9.')');
		$PHPExcel->getActiveSheet()->setCellValue('E4', '');
		$ks = 3;
		$PHPExcel->getActiveSheet()->setCellValue('A5', '您为爱宠定期驱虫了吗');
		$PHPExcel->getActiveSheet()->setCellValue('B5', '是的，定期驱虫('.$c10.')');
		$PHPExcel->getActiveSheet()->setCellValue('C5', '有驱虫，但是时间很随机('.$c11.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D5', '');
		$PHPExcel->getActiveSheet()->setCellValue('E5', '');
		$ks = 4;
		$PHPExcel->getActiveSheet()->setCellValue('A6', '您的爱宠生过寄生虫病吗');
		$PHPExcel->getActiveSheet()->setCellValue('B6', '从来没有('.$c12.')');
		$PHPExcel->getActiveSheet()->setCellValue('C6', '有过，但是很快痊愈('.$c13.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D6', '');
		$PHPExcel->getActiveSheet()->setCellValue('E6', '');
		$ks = 5;
		$PHPExcel->getActiveSheet()->setCellValue('A7', '您为爱宠驱虫的时候会选择');
		$PHPExcel->getActiveSheet()->setCellValue('B7', '在家，自购驱虫药('.$c14.')');
		$PHPExcel->getActiveSheet()->setCellValue('C7', '去宠物医院('.$c15.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D7', '');
		$PHPExcel->getActiveSheet()->setCellValue('E7', '');
		$ks = 6;
		$PHPExcel->getActiveSheet()->setCellValue('A8', '对于春季驱虫，您的看法是');
		$PHPExcel->getActiveSheet()->setCellValue('B8', '很有必要，在计划之中 ('.$c16.')');
		$PHPExcel->getActiveSheet()->setCellValue('C8', '没有必要一定春季驱虫('.$c17.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D8', '');
		$PHPExcel->getActiveSheet()->setCellValue('E8', '');
		$ks = 7;
		//保存为2003格式
		$objWriter = new PHPExcel_Writer_Excel5($PHPExcel);
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		
		//多浏览器下兼容中文标题
		$encoded_filename = urlencode($fileName);
		$ua = $_SERVER["HTTP_USER_AGENT"];
		if (preg_match("/MSIE/", $ua)) {
			header('Content-Disposition: attachment; filename="' . $encoded_filename . '.xls"');
		} else if (preg_match("/Firefox/", $ua)) {
			header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '.xls"');
		} else {
			header('Content-Disposition: attachment; filename="' . $fileName . '.xls"');
		}
		
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
	 }
	 
	//excel导出
	public function getExcleMaoShaPeng(){
		$c1 = $this->getChoiceCount3('zt_sensitive','c1',1,9);
		$c2 = $this->getChoiceCount3('zt_sensitive','c1',2,9);
		$c3 = $this->getChoiceCount3('zt_sensitive','c2',1,9);
		$c4 = $this->getChoiceCount3('zt_sensitive','c2',2,9);
		$c5 = $this->getChoiceCount3('zt_sensitive','c2',3,9);
		$c6 = $this->getChoiceCount3('zt_sensitive','c2',4,9);
		$c7 = $this->getChoiceCount3('zt_sensitive','c3',1,9);
		$c8 = $this->getChoiceCount3('zt_sensitive','c3',2,9);
		$c9 = $this->getChoiceCount3('zt_sensitive','c3',3,9);
		$c10 = $this->getChoiceCount3('zt_sensitive','c4',1,9);
		$c11 = $this->getChoiceCount3('zt_sensitive','c4',2,9);
		$c12 = $this->getChoiceCount3('zt_sensitive','c4',3,9);
		$c13 = $this->getChoiceCount3('zt_sensitive','c5',1,9);
		$c14 = $this->getChoiceCount3('zt_sensitive','c5',2,9);
		$c15 = $this->getChoiceCount3('zt_sensitive','c5',3,9);
		$c16 = $this->getChoiceCount3('zt_sensitive','c5',4,9);
		$c17 = $this->getChoiceCount3('zt_sensitive','c5',5,9);
		$c18 = $this->getChoiceCount3('zt_sensitive','c6',1,9);
		$c19 = $this->getChoiceCount3('zt_sensitive','c6',2,9);
		$c20 = $this->getChoiceCount3('zt_sensitive','c7',1,9);
		$c21 = $this->getChoiceCount3('zt_sensitive','c7',2,9);
		$c22 = $this->getChoiceCount3('zt_sensitive','c7',3,9);
		$c23 = $this->getChoiceCount3('zt_sensitive','c7',4,9);
		$c24 = $this->getChoiceCount3('zt_sensitive','c7',5,9);
		$c25 = $this->getChoiceCount3('zt_sensitive','c8',1,9);
		$c26 = $this->getChoiceCount3('zt_sensitive','c8',2,9);
		$c27 = $this->getChoiceCount3('zt_sensitive','c8',3,9);
		$c28 = $this->getChoiceCount3('zt_sensitive','c9',1,9);
		$c29 = $this->getChoiceCount3('zt_sensitive','c9',2,9);
		$c30 = $this->getChoiceCount3('zt_sensitive','c9',3,9);
		$c31 = $this->getChoiceCount3('zt_sensitive','c9',4,9);
		$c32 = $this->getChoiceCount3('zt_sensitive','c9',5,9);
		$c33 = $this->getChoiceCount3('zt_sensitive','c10',1,9);
		$c34 = $this->getChoiceCount3('zt_sensitive','c10',2,9);
		$c35 = $this->getChoiceCount3('zt_sensitive','c10',3,9);
		
		vendor('excel.PHPExcel');
		$fileName = $this->fileName;
		$fileName = empty($fileName)?'pet_maoshapeng'.date('Y-m-d',time()):$fileName;
		$PHPExcel = new PHPExcel();
		//填入表头
		$PHPExcel->getActiveSheet()->setCellValue('A1', '选项');
		$PHPExcel->getActiveSheet()->setCellValue('B1', '答案1');
		$PHPExcel->getActiveSheet()->setCellValue('C1', '答案2');
		$PHPExcel->getActiveSheet()->setCellValue('D1', '答案3');
		$PHPExcel->getActiveSheet()->setCellValue('E1', '答案4');
		$PHPExcel->getActiveSheet()->setCellValue('F1', '答案5');
		//填入列表
		$ks = 0;
		$PHPExcel->getActiveSheet()->setCellValue('A2', '您的性别');
		$PHPExcel->getActiveSheet()->setCellValue('B2', '男('.$c1.')');
		$PHPExcel->getActiveSheet()->setCellValue('C2', '女('.$c2.')');
		$PHPExcel->getActiveSheet()->setCellValue('D2', '');
		$PHPExcel->getActiveSheet()->setCellValue('E2', '');
		$PHPExcel->getActiveSheet()->setCellValue('F2', '');
		$ks = 1;	
		$PHPExcel->getActiveSheet()->setCellValue('A3', '您的年龄');
		$PHPExcel->getActiveSheet()->setCellValue('B3', '18岁以下('.$c3.')');
		$PHPExcel->getActiveSheet()->setCellValue('C3', '19-40岁('.$c4.')');
		$PHPExcel->getActiveSheet()->setCellValue('D3', '41-60岁('.$c5.')');
		$PHPExcel->getActiveSheet()->setCellValue('E3', '60岁以上('.$c6.')');
		$PHPExcel->getActiveSheet()->setCellValue('F3', '');
		$ks = 2;
		$PHPExcel->getActiveSheet()->setCellValue('A4', '您有养猫咪吗');
		$PHPExcel->getActiveSheet()->setCellValue('B4', '有('.$c7.')');
		$PHPExcel->getActiveSheet()->setCellValue('C4', '准备养('.$c8.')');
		$PHPExcel->getActiveSheet()->setCellValue('D4', '没有('.$c9.')');
		$PHPExcel->getActiveSheet()->setCellValue('E4', '');
		$PHPExcel->getActiveSheet()->setCellValue('F4', '');
		$ks = 3;
		$PHPExcel->getActiveSheet()->setCellValue('A5', '您了解猫砂吗');
		$PHPExcel->getActiveSheet()->setCellValue('B5', '非常了解('.$c10.')');
		$PHPExcel->getActiveSheet()->setCellValue('C5', '不太了解('.$c11.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D5', '不了解('.$c12.')');
		$PHPExcel->getActiveSheet()->setCellValue('E5', '');
		$PHPExcel->getActiveSheet()->setCellValue('F5', '');
		$ks = 4;
		$PHPExcel->getActiveSheet()->setCellValue('A6', '您会选择给猫咪使用哪种猫砂');
		$PHPExcel->getActiveSheet()->setCellValue('B6', '凝结猫砂('.$c13.')');
		$PHPExcel->getActiveSheet()->setCellValue('C6', '水晶猫砂('.$c14.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D6', '木屑猫砂('.$c15.')');
		$PHPExcel->getActiveSheet()->setCellValue('E6', '纸屑猫砂('.$c16.')');
		$PHPExcel->getActiveSheet()->setCellValue('F6', '其他('.$c17.')');
		$ks = 5;
		$PHPExcel->getActiveSheet()->setCellValue('A7', '您有碰到过猫咪不在猫砂盆里排便的情况吗');
		$PHPExcel->getActiveSheet()->setCellValue('B7', '有（请回答第七题）('.$c18.')');
		$PHPExcel->getActiveSheet()->setCellValue('C7', '没有（直接跳至第八题）('.$c19.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D7', '');
		$PHPExcel->getActiveSheet()->setCellValue('E7', '');
		$PHPExcel->getActiveSheet()->setCellValue('F7', '');
		$ks = 6;
		$PHPExcel->getActiveSheet()->setCellValue('A8', '猫咪不在猫砂盆里排便时，一般会选择在哪些地方排便？');
		$PHPExcel->getActiveSheet()->setCellValue('B8', '床上('.$c20.')');
		$PHPExcel->getActiveSheet()->setCellValue('C8', '地毯('.$c21.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D8', '暗处角落('.$c22.')');
		$PHPExcel->getActiveSheet()->setCellValue('E8', '随意('.$c23.')');
		$PHPExcel->getActiveSheet()->setCellValue('F8', '其他('.$c24.')');
		$ks = 7;
		$PHPExcel->getActiveSheet()->setCellValue('A9', '您是否了解猫咪不使用猫砂盆的原因');
		$PHPExcel->getActiveSheet()->setCellValue('B9', '了解('.$c25.')');
		$PHPExcel->getActiveSheet()->setCellValue('C9', '不完全了解('.$c26.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D9', '完全不了解('.$c27.')');
		$PHPExcel->getActiveSheet()->setCellValue('E9', '');
		$PHPExcel->getActiveSheet()->setCellValue('F9', '');
		$ks = 8;
		$PHPExcel->getActiveSheet()->setCellValue('A10', '当猫咪不使用猫砂盆时，您会怎么办？');
		$PHPExcel->getActiveSheet()->setCellValue('B10', '教训一顿('.$c28.')');
		$PHPExcel->getActiveSheet()->setCellValue('C10', '更换猫砂('.$c29.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D10', '找出真正原因('.$c30.')');
		$PHPExcel->getActiveSheet()->setCellValue('E10', '继续观察('.$c31.')');
		$PHPExcel->getActiveSheet()->setCellValue('F10', '求助（猫友、网友、兽医等）('.$c32.')');
		$ks = 9;
		$PHPExcel->getActiveSheet()->setCellValue('A11', '当猫咪不使用猫砂盆时，您是否会怀疑猫咪生病了');
		$PHPExcel->getActiveSheet()->setCellValue('B11', '会('.$c33.')');
		$PHPExcel->getActiveSheet()->setCellValue('C11', '看情况('.$c34.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D11', '不会('.$c35.')');
		$PHPExcel->getActiveSheet()->setCellValue('E11', '');
		$PHPExcel->getActiveSheet()->setCellValue('F11', '');
		$ks = 10;
		//保存为2003格式
		$objWriter = new PHPExcel_Writer_Excel5($PHPExcel);
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		
		
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		
		//多浏览器下兼容中文标题
		$encoded_filename = urlencode($fileName);
		$ua = $_SERVER["HTTP_USER_AGENT"];
		if (preg_match("/MSIE/", $ua)) {
			header('Content-Disposition: attachment; filename="' . $encoded_filename . '.xls"');
		} else if (preg_match("/Firefox/", $ua)) {
			header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '.xls"');
		} else {
			header('Content-Disposition: attachment; filename="' . $fileName . '.xls"');
		}
		
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
	 }
	 
	 //excel导出
	public function getExcleChuYou(){
		$c1 = $this->getChoiceCount3('zt_sensitive','c1',1,10);
		$c2 = $this->getChoiceCount3('zt_sensitive','c1',2,10);
		$c3 = $this->getChoiceCount3('zt_sensitive','c1',3,10);
		$c4 = $this->getChoiceCount3('zt_sensitive','c2',1,10);
		$c5 = $this->getChoiceCount3('zt_sensitive','c2',2,10);
		$c6 = $this->getChoiceCount3('zt_sensitive','c3',1,10);
		$c7 = $this->getChoiceCount3('zt_sensitive','c3',2,10);
		$c8 = $this->getChoiceCount3('zt_sensitive','c3',3,10);
		$c9 = $this->getChoiceCount3('zt_sensitive','c4',1,10);
		$c10 = $this->getChoiceCount3('zt_sensitive','c4',2,10);
		$c11 = $this->getChoiceCount3('zt_sensitive','c4',3,10);
		$c12 = $this->getChoiceCount3('zt_sensitive','c5',1,10);
		$c13 = $this->getChoiceCount3('zt_sensitive','c5',2,10);
		$c14 = $this->getChoiceCount3('zt_sensitive','c6',1,10);
		$c15 = $this->getChoiceCount3('zt_sensitive','c6',2,10);
		$c16 = $this->getChoiceCount3('zt_sensitive','c6',3,10);
		$c17 = $this->getChoiceCount3('zt_sensitive','c7',1,10);
		$c18 = $this->getChoiceCount3('zt_sensitive','c7',2,10);
		$c19 = $this->getChoiceCount3('zt_sensitive','c7',3,10);
		$c20 = $this->getChoiceCount3('zt_sensitive','c7',4,10);
		
		vendor('excel.PHPExcel');
		$fileName = $this->fileName;
		$fileName = empty($fileName)?'pet_maoshapeng'.date('Y-m-d',time()):$fileName;
		$PHPExcel = new PHPExcel();
		//填入表头
		$PHPExcel->getActiveSheet()->setCellValue('A1', '选项');
		$PHPExcel->getActiveSheet()->setCellValue('B1', '答案1');
		$PHPExcel->getActiveSheet()->setCellValue('C1', '答案2');
		$PHPExcel->getActiveSheet()->setCellValue('D1', '答案3');
		$PHPExcel->getActiveSheet()->setCellValue('E1', '答案4');
		//填入列表
		$ks = 0;
		$PHPExcel->getActiveSheet()->setCellValue('A2', '出门踏青时你会选择带上宠物一起游玩吗');
		$PHPExcel->getActiveSheet()->setCellValue('B2', '会('.$c1.')');
		$PHPExcel->getActiveSheet()->setCellValue('C2', '不会('.$c2.')');
		$PHPExcel->getActiveSheet()->setCellValue('D2', '看情况('.$c3.')');
		$PHPExcel->getActiveSheet()->setCellValue('E2', '');
		$ks = 1;	
		$PHPExcel->getActiveSheet()->setCellValue('A3', '如果带宠物，你会选择何种出现方式');
		$PHPExcel->getActiveSheet()->setCellValue('B3', '自驾('.$c4.')');
		$PHPExcel->getActiveSheet()->setCellValue('C3', '公共交通偷渡('.$c5.')');
		$PHPExcel->getActiveSheet()->setCellValue('D3', '');
		$PHPExcel->getActiveSheet()->setCellValue('E3', '');
		$ks = 2;
		$PHPExcel->getActiveSheet()->setCellValue('A4', '带着宠物你通常会选择几日的行程');
		$PHPExcel->getActiveSheet()->setCellValue('B4', '1日('.$c6.')');
		$PHPExcel->getActiveSheet()->setCellValue('C4', '2日('.$c7.')');
		$PHPExcel->getActiveSheet()->setCellValue('D4', '3日以及以上('.$c8.')');
		$PHPExcel->getActiveSheet()->setCellValue('E4', '');
		$ks = 3;
		$PHPExcel->getActiveSheet()->setCellValue('A5', '哪些问题会让你选择不带宠物一起出门游玩');
		$PHPExcel->getActiveSheet()->setCellValue('B5', '景点不让带宠物('.$c9.')');
		$PHPExcel->getActiveSheet()->setCellValue('C5', '带宠物游玩太麻烦('.$c10.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D5', '其他('.$c11.')');
		$PHPExcel->getActiveSheet()->setCellValue('E5', '');
		$ks = 4;
		$PHPExcel->getActiveSheet()->setCellValue('A6', '如果条件允许，你是否会参加波奇网举办的线下宠物聚会');
		$PHPExcel->getActiveSheet()->setCellValue('B6', '会('.$c12.')');
		$PHPExcel->getActiveSheet()->setCellValue('C6', '不会('.$c13.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D6', '');
		$PHPExcel->getActiveSheet()->setCellValue('E6', '');
		$ks = 5;
		$PHPExcel->getActiveSheet()->setCellValue('A7', '你希望波奇网举办的线下聚会在什么地方');
		$PHPExcel->getActiveSheet()->setCellValue('B7', '浦东周边('.$c14.')');
		$PHPExcel->getActiveSheet()->setCellValue('C7', '浦西周边('.$c15.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D7', '出上海('.$c16.')');
		$PHPExcel->getActiveSheet()->setCellValue('E7', '');
		$ks = 6;
		$PHPExcel->getActiveSheet()->setCellValue('A8', '波奇网聚宠你更希望为宠物设置哪些项目');
		$PHPExcel->getActiveSheet()->setCellValue('B8', '游戏环节('.$c17.')');
		$PHPExcel->getActiveSheet()->setCellValue('C8', '专家当面咨询('.$c18.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D8', '宠物相亲('.$c19.')');
		$PHPExcel->getActiveSheet()->setCellValue('E8', '其他('.$c20.')');
		$ks = 7;
		
		//保存为2003格式
		$objWriter = new PHPExcel_Writer_Excel5($PHPExcel);
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		
		
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		
		//多浏览器下兼容中文标题
		$encoded_filename = urlencode($fileName);
		$ua = $_SERVER["HTTP_USER_AGENT"];
		if (preg_match("/MSIE/", $ua)) {
			header('Content-Disposition: attachment; filename="' . $encoded_filename . '.xls"');
		} else if (preg_match("/Firefox/", $ua)) {
			header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '.xls"');
		} else {
			header('Content-Disposition: attachment; filename="' . $fileName . '.xls"');
		}
		
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
	 }
	 
	 //excel导出
	public function getExclePetYangHu(){
		$c1 = $this->getChoiceCount3('zt_sensitive','c1',1,11);
		$c2 = $this->getChoiceCount3('zt_sensitive','c1',2,11);
		$c3 = $this->getChoiceCount3('zt_sensitive','c2',1,11);
		$c4 = $this->getChoiceCount3('zt_sensitive','c2',2,11);
		$c5 = $this->getChoiceCount3('zt_sensitive','c2',3,11);
		$c6 = $this->getChoiceCount3('zt_sensitive','c2',4,11);
		$c7 = $this->getChoiceCount3('zt_sensitive','c3',1,11);
		$c8 = $this->getChoiceCount3('zt_sensitive','c3',2,11);
		$c9 = $this->getChoiceCount3('zt_sensitive','c3',3,11);
		$c10 = $this->getChoiceCount3('zt_sensitive','c4',1,11);
		$c11 = $this->getChoiceCount3('zt_sensitive','c4',2,11);
		$c12 = $this->getChoiceCount3('zt_sensitive','c4',3,11);
		$c13 = $this->getChoiceCount3('zt_sensitive','c4',4,11);
		$c14 = $this->getChoiceCount3('zt_sensitive','c4',5,11);
		$c15 = $this->getChoiceCount3('zt_sensitive','c4',6,11);
		$c16 = $this->getChoiceCount3('zt_sensitive','c5',1,11);
		$c17 = $this->getChoiceCount3('zt_sensitive','c5',2,11);
		$c18 = $this->getChoiceCount3('zt_sensitive','c5',3,11);
		$c19 = $this->getChoiceCount3('zt_sensitive','c6',1,11);
		$c20 = $this->getChoiceCount3('zt_sensitive','c6',2,11);
		$c21 = $this->getChoiceCount3('zt_sensitive','c6',3,11);
		$c22 = $this->getChoiceCount3('zt_sensitive','c7',1,11);
		$c23 = $this->getChoiceCount3('zt_sensitive','c7',2,11);
		$c24 = $this->getChoiceCount3('zt_sensitive','c7',3,11);
		$c25 = $this->getChoiceCount3('zt_sensitive','c8',1,11);
		$c26 = $this->getChoiceCount3('zt_sensitive','c8',2,11);
		$c27 = $this->getChoiceCount3('zt_sensitive','c8',3,11);
		$c28 = $this->getChoiceCount3('zt_sensitive','c8',4,11);
		$c29 = $this->getChoiceCount3('zt_sensitive','c8',5,11);
		$c30 = $this->getChoiceCount3('zt_sensitive','c8',6,11);
		$c31 = $this->getChoiceCount3('zt_sensitive','c9',1,11);
		$c32 = $this->getChoiceCount3('zt_sensitive','c9',2,11);
		$c33 = $this->getChoiceCount3('zt_sensitive','c10',1,11);
		$c34 = $this->getChoiceCount3('zt_sensitive','c10',2,11);
		$c35 = $this->getChoiceCount3('zt_sensitive','c10',3,11);
		$c36 = $this->getChoiceCount3('zt_sensitive','c10',4,11);
		$c37 = $this->getChoiceCount3('zt_sensitive','c10',5,11);
		$c38 = $this->getChoiceCount3('zt_sensitive','c10',6,11);
		
		vendor('excel.PHPExcel');
		$fileName = $this->fileName;
		$fileName = empty($fileName)?'pet_yanghu'.date('Y-m-d',time()):$fileName;
		$PHPExcel = new PHPExcel();
		//填入表头
		$PHPExcel->getActiveSheet()->setCellValue('A1', '选项');
		$PHPExcel->getActiveSheet()->setCellValue('B1', '答案1');
		$PHPExcel->getActiveSheet()->setCellValue('C1', '答案2');
		$PHPExcel->getActiveSheet()->setCellValue('D1', '答案3');
		$PHPExcel->getActiveSheet()->setCellValue('E1', '答案4');
		$PHPExcel->getActiveSheet()->setCellValue('F1', '答案5');
		$PHPExcel->getActiveSheet()->setCellValue('G1', '答案6');
		//填入列表
		$ks = 0;
		$PHPExcel->getActiveSheet()->setCellValue('A2', '您的性别');
		$PHPExcel->getActiveSheet()->setCellValue('B2', '男('.$c1.')');
		$PHPExcel->getActiveSheet()->setCellValue('C2', '女('.$c2.')');
		$PHPExcel->getActiveSheet()->setCellValue('D2', '');
		$PHPExcel->getActiveSheet()->setCellValue('E2', '');
		$PHPExcel->getActiveSheet()->setCellValue('F2', '');
		$PHPExcel->getActiveSheet()->setCellValue('G2', '');
		$ks = 1;	
		$PHPExcel->getActiveSheet()->setCellValue('A3', '您的年龄');
		$PHPExcel->getActiveSheet()->setCellValue('B3', '18岁以下('.$c3.')');
		$PHPExcel->getActiveSheet()->setCellValue('C3', '19-40岁('.$c4.')');
		$PHPExcel->getActiveSheet()->setCellValue('D3', '41-60岁('.$c5.')');
		$PHPExcel->getActiveSheet()->setCellValue('E3', '60岁以上('.$c6.')');
		$PHPExcel->getActiveSheet()->setCellValue('F3', '');
		$PHPExcel->getActiveSheet()->setCellValue('G3', '');
		$ks = 2;
		$PHPExcel->getActiveSheet()->setCellValue('A4', '您有养宠物');
		$PHPExcel->getActiveSheet()->setCellValue('B4', '有('.$c7.')');
		$PHPExcel->getActiveSheet()->setCellValue('C4', '准备养('.$c8.')');
		$PHPExcel->getActiveSheet()->setCellValue('D4', '没有('.$c9.')');
		$PHPExcel->getActiveSheet()->setCellValue('E4', '');
		$PHPExcel->getActiveSheet()->setCellValue('F4', '');
		$PHPExcel->getActiveSheet()->setCellValue('G4', '');
		$ks = 3;
		$PHPExcel->getActiveSheet()->setCellValue('A5', '您养的宠物是');
		$PHPExcel->getActiveSheet()->setCellValue('B5', '狗狗('.$c10.')');
		$PHPExcel->getActiveSheet()->setCellValue('C5', '猫咪('.$c11.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D5', '兔兔('.$c12.')');
		$PHPExcel->getActiveSheet()->setCellValue('E5', '龟('.$c13.')');
		$PHPExcel->getActiveSheet()->setCellValue('F5', '观赏鱼('.$c14.')');
		$PHPExcel->getActiveSheet()->setCellValue('G5', '小宠('.$c15.')');
		$ks = 4;
		$PHPExcel->getActiveSheet()->setCellValue('A6', '您认为宠物春季养护是否重要');
		$PHPExcel->getActiveSheet()->setCellValue('B6', '非常重要('.$c16.')');
		$PHPExcel->getActiveSheet()->setCellValue('C6', '还好，没有特别重视('.$c17.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D6', '不重要('.$c18.')');
		$PHPExcel->getActiveSheet()->setCellValue('E6', '');
		$PHPExcel->getActiveSheet()->setCellValue('F6', '');
		$PHPExcel->getActiveSheet()->setCellValue('G6', '');
		$ks = 5;
		$PHPExcel->getActiveSheet()->setCellValue('A7', '您是否了解宠物在春天的特殊需求');
		$PHPExcel->getActiveSheet()->setCellValue('B7', '了解('.$c19.')');
		$PHPExcel->getActiveSheet()->setCellValue('C7', '不太了解('.$c20.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D7', '完全不了解('.$c21.')');
		$PHPExcel->getActiveSheet()->setCellValue('E7', '');
		$PHPExcel->getActiveSheet()->setCellValue('F7', '');
		$PHPExcel->getActiveSheet()->setCellValue('G7', '');
		$ks = 6;
		$PHPExcel->getActiveSheet()->setCellValue('A8', '您是否会针对春天的到来而改变宠物养护方法');
		$PHPExcel->getActiveSheet()->setCellValue('B8', '会('.$c22.')');
		$PHPExcel->getActiveSheet()->setCellValue('C8', '不会('.$c23.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D8', '看情况('.$c24.')');
		$PHPExcel->getActiveSheet()->setCellValue('E8', '');
		$PHPExcel->getActiveSheet()->setCellValue('F8', '');
		$PHPExcel->getActiveSheet()->setCellValue('G8', '');
		$ks = 7;
		$PHPExcel->getActiveSheet()->setCellValue('A9', '您认为在宠物春季养护工作中，哪方面最重要？');
		$PHPExcel->getActiveSheet()->setCellValue('B9', '驱虫('.$c25.')');
		$PHPExcel->getActiveSheet()->setCellValue('C9', '毛发护理('.$c26.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D9', '清洁工作('.$c27.')');
		$PHPExcel->getActiveSheet()->setCellValue('E9', '孕育下一代('.$c28.')');
		$PHPExcel->getActiveSheet()->setCellValue('F9', '疫病防控('.$c29.')');
		$PHPExcel->getActiveSheet()->setCellValue('G9', '其他('.$c30.')');
		$ks = 8;
		$PHPExcel->getActiveSheet()->setCellValue('A10', '您有没有为爱宠做相应的春季养护工作');
		$PHPExcel->getActiveSheet()->setCellValue('B10', '有('.$c31.')');
		$PHPExcel->getActiveSheet()->setCellValue('C10', '没有('.$c32.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D10', '');
		$PHPExcel->getActiveSheet()->setCellValue('E10', '');
		$PHPExcel->getActiveSheet()->setCellValue('F10', '');
		$PHPExcel->getActiveSheet()->setCellValue('G10', '');
		$ks = 9;
		$PHPExcel->getActiveSheet()->setCellValue('A11', '您为宠物做了哪些春季养护工作？');
		$PHPExcel->getActiveSheet()->setCellValue('B11', '驱虫('.$c33.')');
		$PHPExcel->getActiveSheet()->setCellValue('C11', '毛发护理('.$c34.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D11', '清洁工作('.$c35.')');
		$PHPExcel->getActiveSheet()->setCellValue('E11', '孕育下一代('.$c36.')');
		$PHPExcel->getActiveSheet()->setCellValue('F11', '疫病防控('.$c37.')');
		$PHPExcel->getActiveSheet()->setCellValue('G11', '其他('.$c38.')');
		$ks = 10;
		
		//保存为2003格式
		$objWriter = new PHPExcel_Writer_Excel5($PHPExcel);
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		
		
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		
		//多浏览器下兼容中文标题
		$encoded_filename = urlencode($fileName);
		$ua = $_SERVER["HTTP_USER_AGENT"];
		if (preg_match("/MSIE/", $ua)) {
			header('Content-Disposition: attachment; filename="' . $encoded_filename . '.xls"');
		} else if (preg_match("/Firefox/", $ua)) {
			header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '.xls"');
		} else {
			header('Content-Disposition: attachment; filename="' . $fileName . '.xls"');
		}
		
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
	 }
	 
	//excel导出
	public function getExcleKuangQuanBing(){
		$c1 = $this->getChoiceCount3('zt_sensitive','c1',1,12);
		$c2 = $this->getChoiceCount3('zt_sensitive','c1',2,12);
		$c3 = $this->getChoiceCount3('zt_sensitive','c2',1,12);
		$c4 = $this->getChoiceCount3('zt_sensitive','c2',2,12);
		$c5 = $this->getChoiceCount3('zt_sensitive','c2',3,12);
		$c6 = $this->getChoiceCount3('zt_sensitive','c2',4,12);
		$c7 = $this->getChoiceCount3('zt_sensitive','c3',1,12);
		$c8 = $this->getChoiceCount3('zt_sensitive','c3',2,12);
		$c9 = $this->getChoiceCount3('zt_sensitive','c3',3,12);
		$c10 = $this->getChoiceCount3('zt_sensitive','c4',1,12);
		$c11 = $this->getChoiceCount3('zt_sensitive','c4',2,12);
		$c12 = $this->getChoiceCount3('zt_sensitive','c5',1,12);
		$c13 = $this->getChoiceCount3('zt_sensitive','c5',2,12);
		$c14 = $this->getChoiceCount3('zt_sensitive','c5',3,12);
		$c15 = $this->getChoiceCount3('zt_sensitive','c5',4,12);
		$c16 = $this->getChoiceCount3('zt_sensitive','c5',5,12);
		$c17 = $this->getChoiceCount3('zt_sensitive','c6',1,12);
		$c18 = $this->getChoiceCount3('zt_sensitive','c6',2,12);
		$c19 = $this->getChoiceCount3('zt_sensitive','c6',3,12);
		$c20 = $this->getChoiceCount3('zt_sensitive','c6',4,12);
		$c21 = $this->getChoiceCount3('zt_sensitive','c7',1,12);
		$c22 = $this->getChoiceCount3('zt_sensitive','c7',2,12);
		$c23 = $this->getChoiceCount3('zt_sensitive','c7',3,12);
		$c24 = $this->getChoiceCount3('zt_sensitive','c7',4,12);
		
		vendor('excel.PHPExcel');
		$fileName = $this->fileName;
		$fileName = empty($fileName)?'pet_kqb'.date('Y-m-d',time()):$fileName;
		$PHPExcel = new PHPExcel();
		//填入表头
		$PHPExcel->getActiveSheet()->setCellValue('A1', '选项');
		$PHPExcel->getActiveSheet()->setCellValue('B1', '答案1');
		$PHPExcel->getActiveSheet()->setCellValue('C1', '答案2');
		$PHPExcel->getActiveSheet()->setCellValue('D1', '答案3');
		$PHPExcel->getActiveSheet()->setCellValue('E1', '答案4');
		$PHPExcel->getActiveSheet()->setCellValue('F1', '答案5');
		//填入列表
		$ks = 0;
		$PHPExcel->getActiveSheet()->setCellValue('A2', '您的性别');
		$PHPExcel->getActiveSheet()->setCellValue('B2', '男('.$c1.')');
		$PHPExcel->getActiveSheet()->setCellValue('C2', '女('.$c2.')');
		$PHPExcel->getActiveSheet()->setCellValue('D2', '');
		$PHPExcel->getActiveSheet()->setCellValue('E2', '');
		$PHPExcel->getActiveSheet()->setCellValue('F2', '');
		$ks = 1;	
		$PHPExcel->getActiveSheet()->setCellValue('A3', '您的年龄');
		$PHPExcel->getActiveSheet()->setCellValue('B3', '18岁以下('.$c3.')');
		$PHPExcel->getActiveSheet()->setCellValue('C3', '19-40岁('.$c4.')');
		$PHPExcel->getActiveSheet()->setCellValue('D3', '41-60岁('.$c5.')');
		$PHPExcel->getActiveSheet()->setCellValue('E3', '60岁以上('.$c6.')');
		$PHPExcel->getActiveSheet()->setCellValue('F3', '');
		$ks = 2;
		$PHPExcel->getActiveSheet()->setCellValue('A4', '您有养宠物');
		$PHPExcel->getActiveSheet()->setCellValue('B4', '有('.$c7.')');
		$PHPExcel->getActiveSheet()->setCellValue('C4', '准备养('.$c8.')');
		$PHPExcel->getActiveSheet()->setCellValue('D4', '没有('.$c9.')');
		$PHPExcel->getActiveSheet()->setCellValue('E4', '');
		$PHPExcel->getActiveSheet()->setCellValue('F4', '');
		$ks = 3;
		$PHPExcel->getActiveSheet()->setCellValue('A5', '是否被宠物抓咬过');
		$PHPExcel->getActiveSheet()->setCellValue('B5', '是('.$c10.')');
		$PHPExcel->getActiveSheet()->setCellValue('C5', '否('.$c11.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D5', '');
		$PHPExcel->getActiveSheet()->setCellValue('E5', '');
		$PHPExcel->getActiveSheet()->setCellValue('F5', '');
		$ks = 4;
		$PHPExcel->getActiveSheet()->setCellValue('A6', '如果被宠物抓咬伤，您会怎么处理？');
		$PHPExcel->getActiveSheet()->setCellValue('B6', '如果仅仅被轻微抓伤（未出血），仅仅用水冲洗或不做特意处理('.$c12.')');
		$PHPExcel->getActiveSheet()->setCellValue('C6', '如果被自家宠物抓、咬出血，不进行疫苗注射('.$c13.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D6', '如果被家养宠物抓、咬出血，不进行疫苗注射('.$c14.')');
		$PHPExcel->getActiveSheet()->setCellValue('E6', '如果被流浪动物抓、咬出血，则会进行疫苗注射('.$c15.')');
		$PHPExcel->getActiveSheet()->setCellValue('F6', '不管被什么动物抓、咬出血都会进行疫苗注射('.$c16.')');
		$ks = 5;
		$PHPExcel->getActiveSheet()->setCellValue('A7', '您有被抓咬伤过的经历吗？当时如何处理的？');
		$PHPExcel->getActiveSheet()->setCellValue('B7', '有，但情况不严重，没有注射疫苗('.$c17.')');
		$PHPExcel->getActiveSheet()->setCellValue('C7', '有，因为是家养宠物，所以没有注射疫苗('.$c18.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D7', '有，被伤后立即注射疫苗('.$c19.')');
		$PHPExcel->getActiveSheet()->setCellValue('E7', '没有被抓咬伤过('.$c20.')');
		$PHPExcel->getActiveSheet()->setCellValue('F7', '');
		$ks = 6;
		$PHPExcel->getActiveSheet()->setCellValue('A8', '你认为防治狂犬病最重要的方面是什么？');
		$PHPExcel->getActiveSheet()->setCellValue('B8', '政府部门统筹防控('.$c21.')');
		$PHPExcel->getActiveSheet()->setCellValue('C8', '动物饲养主注重狂犬病防疫('.$c22.')');	
		$PHPExcel->getActiveSheet()->setCellValue('D8', '动物饲养主看好自家宠物('.$c23.')');
		$PHPExcel->getActiveSheet()->setCellValue('E8', '加强市民狂犬病疫苗接种('.$c24.')');
		$PHPExcel->getActiveSheet()->setCellValue('F8', '');
		$ks = 7;
		
		//保存为2003格式
		$objWriter = new PHPExcel_Writer_Excel5($PHPExcel);
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		
		
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		
		//多浏览器下兼容中文标题
		$encoded_filename = urlencode($fileName);
		$ua = $_SERVER["HTTP_USER_AGENT"];
		if (preg_match("/MSIE/", $ua)) {
			header('Content-Disposition: attachment; filename="' . $encoded_filename . '.xls"');
		} else if (preg_match("/Firefox/", $ua)) {
			header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '.xls"');
		} else {
			header('Content-Disposition: attachment; filename="' . $fileName . '.xls"');
		}
		
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
	 }
	 
	 /*
	  * 狗狗自制美食 excel导出
	  */
	 public function getExcleGouGouZiZhiMeiShi(){
	 	
	 	$result['c1'] = $this->getChoiceCount3('zt_sensitive','c1',1,13);
	 	$result['c2'] = $this->getChoiceCount3('zt_sensitive','c1',2,13);
	 	$result['c3'] = $this->getChoiceCount3('zt_sensitive','c2',1,13);
	 	$result['c4'] = $this->getChoiceCount3('zt_sensitive','c2',2,13);
	 	$result['c5'] = $this->getChoiceCount3('zt_sensitive','c2',3,13);
	 	$result['c6'] = $this->getChoiceCount3('zt_sensitive','c2',4,13);
	 	$result['c7'] = $this->getChoiceCount3('zt_sensitive','c3',1,13);
	 	$result['c8'] = $this->getChoiceCount3('zt_sensitive','c3',2,13);
	 	$result['c9'] = $this->getChoiceCount3('zt_sensitive','c3',3,13);
	 	$result['c10'] = $this->getChoiceCount3('zt_sensitive','c4',1,13);
	 	$result['c11'] = $this->getChoiceCount3('zt_sensitive','c4',2,13);
	 	$result['c12'] = $this->getChoiceCount3('zt_sensitive','c5',1,13);
	 	$result['c13'] = $this->getChoiceCount3('zt_sensitive','c5',2,13);
	 	$result['c14'] = $this->getChoiceCount3('zt_sensitive','c5',3,13);
	 	$result['c15'] = $this->getChoiceCount3('zt_sensitive','c6',1,13);
	 	$result['c16'] = $this->getChoiceCount3('zt_sensitive','c6',2,13);
	 	$result['c17'] = $this->getChoiceCount3('zt_sensitive','c7',1,13);
	 	$result['c18'] = $this->getChoiceCount3('zt_sensitive','c7',2,13);
	 	$result['c19'] = $this->getChoiceCount3('zt_sensitive','c7',3,13);
	 	$result['c20'] = $this->getChoiceCount3('zt_sensitive','c7',4,13);
	 	$result['c21'] = $this->getChoiceCount3('zt_sensitive','c8',1,13);
	 	$result['c22'] = $this->getChoiceCount3('zt_sensitive','c8',2,13);
	 	$result['c23'] = $this->getChoiceCount3('zt_sensitive','c8',3,13);
	 	$result['c24'] = $this->getChoiceCount3('zt_sensitive','c8',4,13);
	 	$result['c25'] = $this->getChoiceCount3('zt_sensitive','c8',5,13);
	 	$result['c26'] = $this->getChoiceCount3('zt_sensitive','c8',6,13);
	 	$result['c27'] = $this->getChoiceCount3('zt_sensitive','c9',1,13);
	 	$result['c28'] = $this->getChoiceCount3('zt_sensitive','c9',2,13);
	 	$result['c29'] = $this->getChoiceCount3('zt_sensitive','c9',3,13);
	 	$result['c30'] = $this->getChoiceCount3('zt_sensitive','c9',4,13);
	 	$result['c31'] = $this->getChoiceCount3('zt_sensitive','c10',1,13);
	 	$result['c32'] = $this->getChoiceCount3('zt_sensitive','c10',2,13);
	 	$result['c33'] = $this->getChoiceCount3('zt_sensitive','c10',3,13);
	 	$result['c34'] = $this->getChoiceCount3('zt_sensitive','c10',4,13);
	 	$result['c35'] = $this->getChoiceCount3('zt_sensitive','c10',5,13);
	 	$result['c36'] = $this->getChoiceCount3('zt_sensitive','c11',1,13);
	 	$result['c37'] = $this->getChoiceCount3('zt_sensitive','c11',2,13);
	 	$result['c38'] = $this->getChoiceCount3('zt_sensitive','c11',3,13);
	 	$result['c39'] = $this->getChoiceCount3('zt_sensitive','c11',4,13);
	 	vendor('excel.PHPExcel');
	 	$fileName = $this->fileName;
	 	$fileName = empty($fileName)?'pet_kqb'.date('Y-m-d',time()):$fileName;
	 	$PHPExcel = new PHPExcel();
	 	//填入表头
	 	$PHPExcel->getActiveSheet()->setCellValue('A1', '选项');
	 	$PHPExcel->getActiveSheet()->setCellValue('B1', '答案1');
	 	$PHPExcel->getActiveSheet()->setCellValue('C1', '答案2');
	 	$PHPExcel->getActiveSheet()->setCellValue('D1', '答案3');
	 	$PHPExcel->getActiveSheet()->setCellValue('E1', '答案4');
	 	$PHPExcel->getActiveSheet()->setCellValue('F1', '答案5');
	 	$PHPExcel->getActiveSheet()->setCellValue('G1', '答案5');
	 	//填入列表
	 	$ks = 0;
	 	$PHPExcel->getActiveSheet()->setCellValue('A2', '您的性别');
	 	$PHPExcel->getActiveSheet()->setCellValue('B2', '男 ('.$result['c1'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('C2', '女 ('.$result['c2'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('D2', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('E2', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('F2', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('G2', '');
	 	$ks = 1;
	 	$PHPExcel->getActiveSheet()->setCellValue('A3', '您的年龄');
	 	$PHPExcel->getActiveSheet()->setCellValue('B3', '18岁以下 ('.$result['c3'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('C3', '19-40岁 ('.$result['c4'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('D3', '41-60岁 ('.$result['c5'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('E3', '60岁以上 ('.$result['c6'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('F3', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('G3', '');
	 	$ks = 2;
	 	$PHPExcel->getActiveSheet()->setCellValue('A4', '您有养宠物吗');
	 	$PHPExcel->getActiveSheet()->setCellValue('B4', '有 ('.$result['c7'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('C4', '准备养 ('.$result['c8'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('D4', '没有 ('.$result['c9'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('E4',  '');
	 	$PHPExcel->getActiveSheet()->setCellValue('F4', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('G4', '');
	 	$ks = 3;
	 	$PHPExcel->getActiveSheet()->setCellValue('A5', '您觉得自制狗粮能满足狗狗的营养需求吗');
	 	$PHPExcel->getActiveSheet()->setCellValue('B5', '能 ('.$result['c10'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('C5', '不能 ('.$result['c11'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('D5', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('E5', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('F5', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('G5', '');
	 	$ks = 4;
	 	$PHPExcel->getActiveSheet()->setCellValue('A6', '是否了解给狗狗自制狗粮的注意事项');
	 	$PHPExcel->getActiveSheet()->setCellValue('B6', '完全了解 ('.$result['c12'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('C6', '了解一些 ('.$result['c13'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('D6', '完全不了解 ('.$result['c14'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('E6',  '');
	 	$PHPExcel->getActiveSheet()->setCellValue('F6', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('G6', '');
	 	$ks = 5;
	 	$PHPExcel->getActiveSheet()->setCellValue('A7', '有没有自制狗粮的经验');
	 	$PHPExcel->getActiveSheet()->setCellValue('B7', '有 ('.$result['c15'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('C7', '没有 ('.$result['c16'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('D7', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('E7', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('F7', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('G7', '');
	 	$ks = 6;
	 	$PHPExcel->getActiveSheet()->setCellValue('A8', '您会完全给狗狗吃自制狗粮吗');
	 	$PHPExcel->getActiveSheet()->setCellValue('B8', '会 ('.$result['c17'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('C8', '不会，但以自制狗粮为主 ('.$result['c18'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('D8', '不会，只是偶尔自制狗粮 ('.$result['c19'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('E8',  '不给狗狗吃自制狗粮 ('.$result['c20'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('F8', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('G8', '');
	 	$ks = 7;
	 	$PHPExcel->getActiveSheet()->setCellValue('A9', '如何选择自制狗粮的食材');
	 	$PHPExcel->getActiveSheet()->setCellValue('B9', '先上网搜寻对狗狗有益的食物有哪些，然后再购买 ('.$result['c21'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('C9', '根据自己的生活经验进行选择 ('.$result['c22'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('D9', '与宠友交流后进行选择 ('.$result['c23'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('E9',  '利用自己多买的食材 ('.$result['c24'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('F9', '其他 ('.$result['c25'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('G9', '不会自制狗粮 ('.$result['c26'].')');
	 	$ks = 8;
	 	$PHPExcel->getActiveSheet()->setCellValue('A10', '自制狗粮时是否会放调味料');
	 	$PHPExcel->getActiveSheet()->setCellValue('B10', '完全不放 ('.$result['c27'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('C10', '稍微放一些 ('.$result['c28'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('D10', '跟自己平时做饭一样 ('.$result['c29'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('E10',  '不会自制狗粮 ('.$result['c30'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('F10', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('G10', '');
	 	$ks = 9;
	 	$PHPExcel->getActiveSheet()->setCellValue('A11', '您觉得自制狗粮的优点有哪些');
	 	$PHPExcel->getActiveSheet()->setCellValue('B11', '安全 ('.$result['c31'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('C11', '经济实惠 ('.$result['c32'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('D11', '营养均衡 ('.$result['c33'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('E11',  '狗狗爱吃 ('.$result['c34'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('F11', '其他 ('.$result['c35'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('G11', '');
	 	$ks = 10;
	 	$PHPExcel->getActiveSheet()->setCellValue('A12', '您觉得自制狗粮的缺点有哪些');
	 	$PHPExcel->getActiveSheet()->setCellValue('B12', '制作麻烦 ('.$result['c36'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('C12', '搭配不当会造成狗狗营养不良 ('.$result['c37'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('D12', '有可能会给狗狗吃到它们不能吃的食物 ('.$result['c38'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('E12',  '其他 ('.$result['c39'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('F12', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('G12', '');
	 	
	 	//保存为2003格式
	 	$objWriter = new PHPExcel_Writer_Excel5($PHPExcel);
	 	header("Pragma: public");
	 	header("Expires: 0");
	 	header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
	 	header("Content-Type:application/force-download");
	 	header("Content-Type:application/vnd.ms-execl");
	 	
	 	
	 	header("Content-Type:application/octet-stream");
	 	header("Content-Type:application/download");
	 	
	 	//多浏览器下兼容中文标题
	 	$encoded_filename = urlencode($fileName);
	 	$ua = $_SERVER["HTTP_USER_AGENT"];
	 	if (preg_match("/MSIE/", $ua)) {
	 		header('Content-Disposition: attachment; filename="' . $encoded_filename . '.xls"');
	 	} else if (preg_match("/Firefox/", $ua)) {
	 		header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '.xls"');
	 	} else {
	 		header('Content-Disposition: attachment; filename="' . $fileName . '.xls"');
	 	}
	 	
	 	header("Content-Transfer-Encoding:binary");
	 	$objWriter->save('php://output');
	 }
	 
	 
	 /*
	  * 猫咪美食 excel导出
	 */
	 public function getExcleMaoMiMeiShi(){
	 	 
		$result['c1'] = $this->getChoiceCount3('zt_sensitive','c1',1,14);
	 	$result['c2'] = $this->getChoiceCount3('zt_sensitive','c1',2,14);
	 	$result['c3'] = $this->getChoiceCount3('zt_sensitive','c2',1,14);
	 	$result['c4'] = $this->getChoiceCount3('zt_sensitive','c2',2,14);
	 	$result['c5'] = $this->getChoiceCount3('zt_sensitive','c2',3,14);
	 	$result['c6'] = $this->getChoiceCount3('zt_sensitive','c2',4,14);
	 	$result['c7'] = $this->getChoiceCount3('zt_sensitive','c3',1,14);
	 	$result['c8'] = $this->getChoiceCount3('zt_sensitive','c3',2,14);
	 	$result['c9'] = $this->getChoiceCount3('zt_sensitive','c3',3,14);
	 	$result['c10'] = $this->getChoiceCount3('zt_sensitive','c4',1,14);
	 	$result['c11'] = $this->getChoiceCount3('zt_sensitive','c4',2,14);
	 	$result['c12'] = $this->getChoiceCount3('zt_sensitive','c5',1,14);
	 	$result['c13'] = $this->getChoiceCount3('zt_sensitive','c5',2,14);
	 	$result['c14'] = $this->getChoiceCount3('zt_sensitive','c5',3,14);
	 	$result['c15'] = $this->getChoiceCount3('zt_sensitive','c6',1,14);
	 	$result['c16'] = $this->getChoiceCount3('zt_sensitive','c6',2,14);
	 	$result['c17'] = $this->getChoiceCount3('zt_sensitive','c7',1,14);
	 	$result['c18'] = $this->getChoiceCount3('zt_sensitive','c7',2,14);
	 	$result['c19'] = $this->getChoiceCount3('zt_sensitive','c7',3,14);
	 	$result['c20'] = $this->getChoiceCount3('zt_sensitive','c8',1,14);
	 	$result['c21'] = $this->getChoiceCount3('zt_sensitive','c8',2,14);
	 	$result['c22'] = $this->getChoiceCount3('zt_sensitive','c8',3,14);
	 	$result['c23'] = $this->getChoiceCount3('zt_sensitive','c8',4,14);
	 	$result['c24'] = $this->getChoiceCount3('zt_sensitive','c8',5,14);
	 	$result['c25'] = $this->getChoiceCount3('zt_sensitive','c9',1,14);
	 	$result['c26'] = $this->getChoiceCount3('zt_sensitive','c9',2,14);
	 	$result['c27'] = $this->getChoiceCount3('zt_sensitive','c9',3,14);
	 	$result['c28'] = $this->getChoiceCount3('zt_sensitive','c10',1,14);
	 	$result['c29'] = $this->getChoiceCount3('zt_sensitive','c10',2,14);
	 	$result['c30'] = $this->getChoiceCount3('zt_sensitive','c10',3,14);
	 	$result['c31'] = $this->getChoiceCount3('zt_sensitive','c10',4,14);
	 	$result['c32'] = $this->getChoiceCount3('zt_sensitive','c10',5,14);
	 	$result['c33'] = $this->getChoiceCount3('zt_sensitive','c11',1,14);
	 	$result['c34'] = $this->getChoiceCount3('zt_sensitive','c11',2,14);
	 	$result['c35'] = $this->getChoiceCount3('zt_sensitive','c11',3,14);
	 	$result['c36'] = $this->getChoiceCount3('zt_sensitive','c11',4,14);
	 	vendor('excel.PHPExcel');
	 	$fileName = $this->fileName;
	 	$fileName = empty($fileName)?'pet_kqb'.date('Y-m-d',time()):$fileName;
	 	$PHPExcel = new PHPExcel();
	 	//填入表头
	 	$PHPExcel->getActiveSheet()->setCellValue('A1', '选项');
	 	$PHPExcel->getActiveSheet()->setCellValue('B1', '答案1');
	 	$PHPExcel->getActiveSheet()->setCellValue('C1', '答案2');
	 	$PHPExcel->getActiveSheet()->setCellValue('D1', '答案3');
	 	$PHPExcel->getActiveSheet()->setCellValue('E1', '答案4');
	 	$PHPExcel->getActiveSheet()->setCellValue('F1', '答案5');
	 	//填入列表
	 	$ks = 0;
	 	$PHPExcel->getActiveSheet()->setCellValue('A2', '您的性别');
	 	$PHPExcel->getActiveSheet()->setCellValue('B2', '男 ('.$result['c1'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('C2', '女 ('.$result['c2'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('D2', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('E2', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('F2', '');
	 	$ks = 1;
	 	$PHPExcel->getActiveSheet()->setCellValue('A3', '您的年龄');
	 	$PHPExcel->getActiveSheet()->setCellValue('B3', '18岁以下 ('.$result['c3'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('C3', '19-40岁 ('.$result['c4'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('D3', '41-60岁 ('.$result['c5'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('E3', '60岁以上 ('.$result['c6'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('F3', '');
	 	$ks = 2;
	 	$PHPExcel->getActiveSheet()->setCellValue('A4', '您有养宠物吗');
	 	$PHPExcel->getActiveSheet()->setCellValue('B4', '有 ('.$result['c7'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('C4', '准备养 ('.$result['c8'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('D4', '没有 ('.$result['c9'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('E4',  '');
	 	$PHPExcel->getActiveSheet()->setCellValue('F4', '');
	 	$ks = 3;
	 	$PHPExcel->getActiveSheet()->setCellValue('A5', '您觉得自制猫粮能满足猫咪的营养需求吗');
	 	$PHPExcel->getActiveSheet()->setCellValue('B5', '能 ('.$result['c10'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('C5', '不能 ('.$result['c11'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('D5', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('E5', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('F5', '');
	 	$ks = 4;
	 	$PHPExcel->getActiveSheet()->setCellValue('A6', '是否了解给猫咪自制猫粮的注意事项');
	 	$PHPExcel->getActiveSheet()->setCellValue('B6', '完全了解 ('.$result['c12'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('C6', '了解一些 ('.$result['c13'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('D6', '完全不了解 ('.$result['c14'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('E6',  '');
	 	$PHPExcel->getActiveSheet()->setCellValue('F6', '');
	 	$ks = 5;
	 	$PHPExcel->getActiveSheet()->setCellValue('A7', '有没有自制猫粮的经验');
	 	$PHPExcel->getActiveSheet()->setCellValue('B7', '有 ('.$result['c15'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('C7', '没有 ('.$result['c16'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('D7', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('E7', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('F7', '');
	 	$ks = 6;
	 	$PHPExcel->getActiveSheet()->setCellValue('A8', '您会完全给猫咪吃自制猫粮吗');
	 	$PHPExcel->getActiveSheet()->setCellValue('B8', '会 ('.$result['c17'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('C8', '不会，但以自制猫粮为主  ('.$result['c18'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('D8', '不会，只是偶尔自制猫粮 ('.$result['c19'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('E8',  '');
	 	$PHPExcel->getActiveSheet()->setCellValue('F8', '');
	 	$ks = 7;
	 	$PHPExcel->getActiveSheet()->setCellValue('A9', '如何选择自制猫粮的食材');
	 	$PHPExcel->getActiveSheet()->setCellValue('B9', '先上网搜寻对猫咪有益的食物有哪些，然后再购买 ('.$result['c20'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('C9', '根据自己的生活经验进行选择 ('.$result['c21'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('D9', '与宠友交流后进行选择 ('.$result['c22'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('E9',  '利用自己多买的食材 ('.$result['c23'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('F9', '其他 ('.$result['c24'].')');
	 	$ks = 8;
	 	$PHPExcel->getActiveSheet()->setCellValue('A10', '自制猫粮时是否会放调味料');
	 	$PHPExcel->getActiveSheet()->setCellValue('B10', '完全不放 ('.$result['c25'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('C10', '稍微放一些 ('.$result['c26'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('D10', '跟自己平时做饭一样 ('.$result['c27'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('E10',  '');
	 	$PHPExcel->getActiveSheet()->setCellValue('F10', '');
	 	$ks = 9;
	 	$PHPExcel->getActiveSheet()->setCellValue('A11', '您觉得自制猫粮的优点有哪些');
	 	$PHPExcel->getActiveSheet()->setCellValue('B11', '安全 ('.$result['c28'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('C11', '经济实惠 ('.$result['c29'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('D11', '营养均衡 ('.$result['c30'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('E11',  '猫咪爱吃 ('.$result['c31'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('F11', '其他 ('.$result['c32'].')');
	 	$ks = 10;
	 	$PHPExcel->getActiveSheet()->setCellValue('A12', '您觉得自制狗粮的缺点有哪些');
	 	$PHPExcel->getActiveSheet()->setCellValue('B12', '制作麻烦 ('.$result['c33'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('C12', '搭配不当会造成猫咪营养不良 ('.$result['c34'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('D12', '有可能会给猫咪吃到它们不能吃的食物 ('.$result['c35'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('E12',  '其他 ('.$result['c36'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('F12', '');
	 	 
	 	//保存为2003格式
	 	$objWriter = new PHPExcel_Writer_Excel5($PHPExcel);
	 	header("Pragma: public");
	 	header("Expires: 0");
	 	header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
	 	header("Content-Type:application/force-download");
	 	header("Content-Type:application/vnd.ms-execl");
	 	 
	 	 
	 	header("Content-Type:application/octet-stream");
	 	header("Content-Type:application/download");
	 	 
	 	//多浏览器下兼容中文标题
	 	$encoded_filename = urlencode($fileName);
	 	$ua = $_SERVER["HTTP_USER_AGENT"];
	 	if (preg_match("/MSIE/", $ua)) {
	 		header('Content-Disposition: attachment; filename="' . $encoded_filename . '.xls"');
	 	} else if (preg_match("/Firefox/", $ua)) {
	 		header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '.xls"');
	 	} else {
	 		header('Content-Disposition: attachment; filename="' . $fileName . '.xls"');
	 	}
	 	 
	 	header("Content-Transfer-Encoding:binary");
	 	$objWriter->save('php://output');
	 }
	 
	 
	 
	 public function getExcleLiuLangTianshi(){
	 	
	 	$result['c1'] = $this->getChoiceCount3('zt_sensitive','c1',1,15);
	 	$result['c2'] = $this->getChoiceCount3('zt_sensitive','c1',2,15);
	 		
	 	$result['c3'] = $this->getChoiceCount3('zt_sensitive','c2',1,15);
	 	$result['c4'] = $this->getChoiceCount3('zt_sensitive','c2',2,15);
	 	$result['c5'] = $this->getChoiceCount3('zt_sensitive','c2',3,15);
	 	$result['c6'] = $this->getChoiceCount3('zt_sensitive','c2',4,15);
	 		
	 	$result['c7'] = $this->getChoiceCount3('zt_sensitive','c3',1,15);
	 	$result['c8'] = $this->getChoiceCount3('zt_sensitive','c3',2,15);
	 		
	 	$result['c9'] = $this->getChoiceCount3('zt_sensitive','c4',1,15);
	 	$result['c10'] = $this->getChoiceCount3('zt_sensitive','c4',2,15);
	 		
	 	$result['c11'] = $this->getChoiceCount3('zt_sensitive','c5',1,15);
	 	$result['c12'] = $this->getChoiceCount3('zt_sensitive','c5',2,15);
	 	$result['c13'] = $this->getChoiceCount3('zt_sensitive','c5',3,15);
	 	$result['c14'] = $this->getChoiceCount3('zt_sensitive','c5',4,15);
	 		
	 	$result['c15'] = $this->getChoiceCount3('zt_sensitive','c6',1,15);
	 	$result['c16'] = $this->getChoiceCount3('zt_sensitive','c6',2,15);
	 	$result['c17'] = $this->getChoiceCount3('zt_sensitive','c6',3,15);
	 		
	 	$result['c18'] = $this->getChoiceCount3('zt_sensitive','c7',1,15);
	 	$result['c19'] = $this->getChoiceCount3('zt_sensitive','c7',2,15);
	 		
	 	$result['c20'] = $this->getChoiceCount3('zt_sensitive','c8',1,15);
	 	$result['c21'] = $this->getChoiceCount3('zt_sensitive','c8',2,15);
	 		
	 	$result['c22'] = $this->getChoiceCount3('zt_sensitive','c9',1,15);
	 	$result['c23'] = $this->getChoiceCount3('zt_sensitive','c9',2,15);
	 	$result['c24'] = $this->getChoiceCount3('zt_sensitive','c9',3,15);
	 	$result['c25'] = $this->getChoiceCount3('zt_sensitive','c9',4,15);
	 	$result['c26'] = $this->getChoiceCount3('zt_sensitive','c9',5,15);
	 	$result['c27'] = $this->getChoiceCount3('zt_sensitive','c9',6,15);
	 	
	 	vendor('excel.PHPExcel');
	 	$fileName = $this->fileName;
	 	$fileName = empty($fileName)?'pet_kqb'.date('Y-m-d',time()):$fileName;
	 	$PHPExcel = new PHPExcel();
	 	//填入表头
	 	$PHPExcel->getActiveSheet()->setCellValue('A1', '选项');
	 	$PHPExcel->getActiveSheet()->setCellValue('B1', '答案1');
	 	$PHPExcel->getActiveSheet()->setCellValue('C1', '答案2');
	 	$PHPExcel->getActiveSheet()->setCellValue('D1', '答案3');
	 	$PHPExcel->getActiveSheet()->setCellValue('E1', '答案4');
	 	$PHPExcel->getActiveSheet()->setCellValue('F1', '答案5');
	 	$PHPExcel->getActiveSheet()->setCellValue('G1', '答案6');
	 	//填入列表
	 	$ks = 0;
	 	$PHPExcel->getActiveSheet()->setCellValue('A2', '您的性别');
	 	$PHPExcel->getActiveSheet()->setCellValue('B2', '男 ('.$result['c1'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('C2', '女 ('.$result['c2'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('D2', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('E2', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('F2', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('G2', '');
	 	$ks = 1;
	 	$PHPExcel->getActiveSheet()->setCellValue('A3', '您的年龄');
	 	$PHPExcel->getActiveSheet()->setCellValue('B3', '18岁以下 ('.$result['c3'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('C3', '19-40岁 ('.$result['c4'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('D3', '41-60岁 ('.$result['c5'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('E3', '60岁以上 ('.$result['c6'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('F3', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('G3', '');
	 	$ks = 2;
	 	$PHPExcel->getActiveSheet()->setCellValue('A4', '您有遇到过流浪动物吗');
	 	$PHPExcel->getActiveSheet()->setCellValue('B4', '有 ('.$result['c7'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('C4', '没有 ('.$result['c8'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('D4', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('E4', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('F4', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('G4', '');
	 	$ks = 3;
	 	$PHPExcel->getActiveSheet()->setCellValue('A5', '您是否怕流浪动物');
	 	$PHPExcel->getActiveSheet()->setCellValue('B5', '怕 ('.$result['c9'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('C5', '不怕 ('.$result['c10'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('D5', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('E5', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('F5', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('G5', '');
	 	$ks = 4;
	 	$PHPExcel->getActiveSheet()->setCellValue('A6', '害怕流浪动物的原因是什么');
	 	$PHPExcel->getActiveSheet()->setCellValue('B6', '被流浪动物误伤 ('.$result['c11'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('C6', '害怕感染寄生虫或其他疾病 ('.$result['c12'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('D6', '只是觉得流浪动物脏('.$result['c13'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('E6', '其他('.$result['c14'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('F6', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('G6', '');
	 	$ks = 5;
	 	$PHPExcel->getActiveSheet()->setCellValue('A7', '看到流浪动物是否会进行收养');
	 	$PHPExcel->getActiveSheet()->setCellValue('B7', '会 ('.$result['c15'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('C7', '看情况 ('.$result['c16'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('D7', '不会('.$result['c17'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('E7', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('F7', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('G7', '');
	 	$ks = 6;
	 	$PHPExcel->getActiveSheet()->setCellValue('A8', '有没有过领养流浪动物的经历');
	 	$PHPExcel->getActiveSheet()->setCellValue('B8', '有 ('.$result['c18'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('C8', '没有 ('.$result['c19'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('D8', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('E8', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('F8', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('G8', '');
	 	$ks = 7;
	 	$PHPExcel->getActiveSheet()->setCellValue('A9', '有没有过领养动物后因为其他原因送掉的经历');
	 	$PHPExcel->getActiveSheet()->setCellValue('B9', '有 ('.$result['c20'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('C9', '没有 ('.$result['c21'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('D9', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('E9', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('F9', '');
	 	$PHPExcel->getActiveSheet()->setCellValue('G9', '');
	 	$ks = 8;
	 	$PHPExcel->getActiveSheet()->setCellValue('A10', '您是否怕流浪动物');
	 	$PHPExcel->getActiveSheet()->setCellValue('B10', '流浪动物的长相 ('.$result['c22'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('C10', '家人或室友的意见 ('.$result['c23'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('D10', '居住空间的大小 ('.$result['c24'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('E10', '自己的经济状况 ('.$result['c25'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('F10', '我不会领养流浪动物 ('.$result['c26'].')');
	 	$PHPExcel->getActiveSheet()->setCellValue('G10', '其他 ('.$result['c27'].')');
	 	//保存为2003格式
	 	$objWriter = new PHPExcel_Writer_Excel5($PHPExcel);
	 	header("Pragma: public");
	 	header("Expires: 0");
	 	header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
	 	header("Content-Type:application/force-download");
	 	header("Content-Type:application/vnd.ms-execl");
	 	
	 	
	 	header("Content-Type:application/octet-stream");
	 	header("Content-Type:application/download");
	 	
	 	//多浏览器下兼容中文标题
	 	$encoded_filename = urlencode($fileName);
	 	$ua = $_SERVER["HTTP_USER_AGENT"];
	 	if (preg_match("/MSIE/", $ua)) {
	 		header('Content-Disposition: attachment; filename="' . $encoded_filename . '.xls"');
	 	} else if (preg_match("/Firefox/", $ua)) {
	 		header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '.xls"');
	 	} else {
	 		header('Content-Disposition: attachment; filename="' . $fileName . '.xls"');
	 	}
	 	
	 	header("Content-Transfer-Encoding:binary");
	 	$objWriter->save('php://output');
	 }
	 
	 
	 /**
	  * 生成狗狗训练EXCEL
	  * @param unknown $result  狗狗训练问卷的具体数据
	  */
	 public function getExcleGouGouXunlian($result){
	 	if($result){
	 		vendor('excel.PHPExcel');
	 		$fileName = $this->fileName;
	 		$fileName = empty($fileName)?'pet_kqb'.date('Y-m-d',time()):$fileName;
	 		$PHPExcel = new PHPExcel();
	 		//填入表头
	 		$PHPExcel->getActiveSheet()->setCellValue('A1', '选项');
	 		$PHPExcel->getActiveSheet()->setCellValue('B1', '答案1');
	 		$PHPExcel->getActiveSheet()->setCellValue('C1', '答案2');
	 		$PHPExcel->getActiveSheet()->setCellValue('D1', '答案3');
	 		$PHPExcel->getActiveSheet()->setCellValue('E1', '答案4');
	 		$PHPExcel->getActiveSheet()->setCellValue('F1', '答案5');
	 		//填入列表
	 		$ks = 0;
	 		$PHPExcel->getActiveSheet()->setCellValue('A2', '您的性别');
	 		$PHPExcel->getActiveSheet()->setCellValue('B2', '男 ('.$result['c1'].')');
	 		$PHPExcel->getActiveSheet()->setCellValue('C2', '女 ('.$result['c2'].')');
	 		$PHPExcel->getActiveSheet()->setCellValue('D2', '');
	 		$PHPExcel->getActiveSheet()->setCellValue('E2', '');
	 		$PHPExcel->getActiveSheet()->setCellValue('F2', '');
	 		$ks = 1;
	 		$PHPExcel->getActiveSheet()->setCellValue('A3', '您的年龄');
	 		$PHPExcel->getActiveSheet()->setCellValue('B3', '18岁以下 ('.$result['c3'].')');
	 		$PHPExcel->getActiveSheet()->setCellValue('C3', '19-40岁 ('.$result['c4'].')');
	 		$PHPExcel->getActiveSheet()->setCellValue('D3', '41-60岁 ('.$result['c5'].')');
	 		$PHPExcel->getActiveSheet()->setCellValue('E3', '60岁以上 ('.$result['c6'].')');
	 		$PHPExcel->getActiveSheet()->setCellValue('F3', '');
	 		$ks = 2;
	 		$PHPExcel->getActiveSheet()->setCellValue('A4', '您有养狗狗吗');
	 		$PHPExcel->getActiveSheet()->setCellValue('B4', '有 ('.$result['c7'].')');
	 		$PHPExcel->getActiveSheet()->setCellValue('C4', '准备养 ('.$result['c8'].')');
	 		$PHPExcel->getActiveSheet()->setCellValue('D4', '没有 ('.$result['c9'].')');
	 		$PHPExcel->getActiveSheet()->setCellValue('E4', '');
	 		$PHPExcel->getActiveSheet()->setCellValue('F4', '');
	 		$ks = 3;
	 		$PHPExcel->getActiveSheet()->setCellValue('A5', '您是否会对狗狗进行训练');
	 		$PHPExcel->getActiveSheet()->setCellValue('B5', '会 ('.$result['c10'].')');
	 		$PHPExcel->getActiveSheet()->setCellValue('C5', '不会 ('.$result['c11'].')');
	 		$PHPExcel->getActiveSheet()->setCellValue('D5', '');
	 		$PHPExcel->getActiveSheet()->setCellValue('E5', '');
	 		$PHPExcel->getActiveSheet()->setCellValue('F5', '');
	 		$ks = 4;
	 		$PHPExcel->getActiveSheet()->setCellValue('A6', '您对狗狗会进行什么样的训练');
	 		$PHPExcel->getActiveSheet()->setCellValue('B6', '基本生活技能训练（如定点排便、拒食陌生人给的食物等） ('.$result['c12'].')');
	 		$PHPExcel->getActiveSheet()->setCellValue('C6', '拓展训练（如让狗狗帮自己叼东西等） ('.$result['c13'].')');
	 		$PHPExcel->getActiveSheet()->setCellValue('D6', '明星训练（如装死、转圈跳舞等） ('.$result['c14'].')');
	 		$PHPExcel->getActiveSheet()->setCellValue('E6', '');
	 		$PHPExcel->getActiveSheet()->setCellValue('F6', '');
	 		$ks = 5;
	 		$PHPExcel->getActiveSheet()->setCellValue('A7', '您觉得训练狗狗是否困难');
	 		$PHPExcel->getActiveSheet()->setCellValue('B7', '困难 ('.$result['c15'].')');
	 		$PHPExcel->getActiveSheet()->setCellValue('C7', '一般 ('.$result['c16'].')');
	 		$PHPExcel->getActiveSheet()->setCellValue('D7', '容易 ('.$result['c17'].')');
	 		$PHPExcel->getActiveSheet()->setCellValue('E7', '');
	 		$PHPExcel->getActiveSheet()->setCellValue('F7', '');
	 		$ks = 6;
	 		$PHPExcel->getActiveSheet()->setCellValue('A8', '您觉得训练狗狗的过程中最令你头疼的是');
	 		$PHPExcel->getActiveSheet()->setCellValue('B8', '狗狗听不懂自己的话 ('.$result['c18'].')');
	 		$PHPExcel->getActiveSheet()->setCellValue('C8', '狗狗不按自己说的做 ('.$result['c19'].')');
	 		$PHPExcel->getActiveSheet()->setCellValue('D8', '狗狗很容易忘记学会的技能 ('.$result['c20'].')');
	 		$PHPExcel->getActiveSheet()->setCellValue('E8', '重复训练让自己很烦躁 ('.$result['c21'].')');
	 		$PHPExcel->getActiveSheet()->setCellValue('F8', '其他 ('.$result['c22'].')');
	 		$ks = 7;
	 		$PHPExcel->getActiveSheet()->setCellValue('A9', '您家狗狗目前的训练状况如何');
	 		$PHPExcel->getActiveSheet()->setCellValue('B9', '没开始训练 ('.$result['c23'].')');
	 		$PHPExcel->getActiveSheet()->setCellValue('C9', '刚开始训练，尚未掌握基本生活技能 ('.$result['c24'].')');
	 		$PHPExcel->getActiveSheet()->setCellValue('D9', '已经能掌握基本生活技能 ('.$result['c25'].')');
	 		$PHPExcel->getActiveSheet()->setCellValue('E9', '在掌握基本生活技能的基础上还学会了一些其他本领 ('.$result['c26'].')');
	 		$PHPExcel->getActiveSheet()->setCellValue('F9', '');
	 		$ks = 8;
	 		$PHPExcel->getActiveSheet()->setCellValue('A10', '您认为训练好狗狗的必备条件因素有哪些');
	 		$PHPExcel->getActiveSheet()->setCellValue('B10', '主人要有耐心 ('.$result['c27'].')');
	 		$PHPExcel->getActiveSheet()->setCellValue('C10', '狗狗要不停地重复训练 ('.$result['c28'].')');
	 		$PHPExcel->getActiveSheet()->setCellValue('D10', '狗狗天资聪颖 ('.$result['c29'].')');
	 		$PHPExcel->getActiveSheet()->setCellValue('E10', '主人的训练方法得当 ('.$result['c30'].')');
	 		$PHPExcel->getActiveSheet()->setCellValue('F10', '其他 ('.$result['c31'].')');
	 		//保存为2003格式
	 		$objWriter = new PHPExcel_Writer_Excel5($PHPExcel);
	 		header("Pragma: public");
	 		header("Expires: 0");
	 		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
	 		header("Content-Type:application/force-download");
	 		header("Content-Type:application/vnd.ms-execl");
	 		 
	 		 
	 		header("Content-Type:application/octet-stream");
	 		header("Content-Type:application/download");
	 		 
	 		//多浏览器下兼容中文标题
	 		$encoded_filename = urlencode($fileName);
	 		$ua = $_SERVER["HTTP_USER_AGENT"];
	 		if (preg_match("/MSIE/", $ua)) {
	 			header('Content-Disposition: attachment; filename="' . $encoded_filename . '.xls"');
	 		} else if (preg_match("/Firefox/", $ua)) {
	 			header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '.xls"');
	 		} else {
	 			header('Content-Disposition: attachment; filename="' . $fileName . '.xls"');
	 		}
	 		 
	 		header("Content-Transfer-Encoding:binary");
	 		$objWriter->save('php://output');
	 	}
	 	
	 }
	 
}
?>