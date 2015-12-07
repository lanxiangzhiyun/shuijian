<?php
class StatisticsAction extends ExtendAction{
	
	public function index(){
		$this->display('index');
	}
	
	//结果
	public function result(){
		$StatisticsModel = D('Statistics');
		
		$result = array();
		$id = $_GET['id'];
		$this->assign('id',$id);
		
		if($id == 1){
			$result['c1'] = $StatisticsModel->getChoiceCount('zt_baike_dog','gender',1);
			$result['c2'] = $StatisticsModel->getChoiceCount('zt_baike_dog','gender',2);
			$result['c3'] = $StatisticsModel->getChoiceCount('zt_baike_dog','age',1);
			$result['c4'] = $StatisticsModel->getChoiceCount('zt_baike_dog','age',2);
			$result['c5'] = $StatisticsModel->getChoiceCount('zt_baike_dog','age',3);
			$result['c6'] = $StatisticsModel->getChoiceCount('zt_baike_dog','age',4);
			$result['c7'] = $StatisticsModel->getChoiceCount('zt_baike_dog','understanding',1);
			$result['c8'] = $StatisticsModel->getChoiceCount('zt_baike_dog','understanding',2);
			$result['c9'] = $StatisticsModel->getChoiceCount('zt_baike_dog','seen',1);
			$result['c10'] = $StatisticsModel->getChoiceCount('zt_baike_dog','seen',2);
			$result['c11'] = $StatisticsModel->getChoiceCount('zt_baike_dog','konw',1);
			$result['c12'] = $StatisticsModel->getChoiceCount('zt_baike_dog','konw',2);
			$result['c13'] = $StatisticsModel->getChoiceCount('zt_baike_dog','accept',1);
			$result['c14'] = $StatisticsModel->getChoiceCount('zt_baike_dog','accept',2);
			$result['c15'] = $StatisticsModel->getChoiceCount('zt_baike_dog','accept',3);
			$result['c16'] = $StatisticsModel->getChoiceCount('zt_baike_dog','reason',1);
			$result['c17'] = $StatisticsModel->getChoiceCount('zt_baike_dog','reason',2);
			$result['c18'] = $StatisticsModel->getChoiceCount('zt_baike_dog','reason',3);
			$result['c19'] = $StatisticsModel->getChoiceCount('zt_baike_dog','reason',4);
			
			if($_GET['act'] == 'export'){
          		$StatisticsModel->getExcle();
				die;
			}
			
			$this->assign('result',$result);
		}elseif($id == 2){
			$result['c1'] = $StatisticsModel->getChoiceCount('zt_bbs_cat','gender',1);
			$result['c2'] = $StatisticsModel->getChoiceCount('zt_bbs_cat','gender',2);
			$result['c3'] = $StatisticsModel->getChoiceCount('zt_bbs_cat','age',1);
			$result['c4'] = $StatisticsModel->getChoiceCount('zt_bbs_cat','age',2);
			$result['c5'] = $StatisticsModel->getChoiceCount('zt_bbs_cat','age',3);
			$result['c6'] = $StatisticsModel->getChoiceCount('zt_bbs_cat','age',4);
			$result['c7'] = $StatisticsModel->getChoiceCount('zt_bbs_cat','varieties',1);
			$result['c8'] = $StatisticsModel->getChoiceCount('zt_bbs_cat','varieties',2);
			$result['c9'] = $StatisticsModel->getChoiceCount('zt_bbs_cat','will',1);
			$result['c10'] = $StatisticsModel->getChoiceCount('zt_bbs_cat','will',2);
			$result['c11'] = $StatisticsModel->getChoiceCount('zt_bbs_cat','outdoor',1);
			$result['c12'] = $StatisticsModel->getChoiceCount('zt_bbs_cat','outdoor',2);
			$result['c13'] = $StatisticsModel->getChoiceCount('zt_bbs_cat','outdoor',3);
			$result['c14'] = $StatisticsModel->getChoiceCount('zt_bbs_cat','outdoor',4);
			$result['c15'] = $StatisticsModel->getChoiceCount('zt_bbs_cat','place',1);
			$result['c16'] = $StatisticsModel->getChoiceCount('zt_bbs_cat','place',2);
			$result['c17'] = $StatisticsModel->getChoiceCount('zt_bbs_cat','place',3);
			$result['c18'] = $StatisticsModel->getChoiceCount('zt_bbs_cat','traffic',1);
			$result['c19'] = $StatisticsModel->getChoiceCount('zt_bbs_cat','traffic',2);
			$result['c20'] = $StatisticsModel->getChoiceCount('zt_bbs_cat','traffic',3);
			$result['c21'] = $StatisticsModel->getChoiceCount('zt_bbs_cat','traffic',4);
			$result['c22'] = $StatisticsModel->getChoiceCount('zt_bbs_cat','joinus',1);
			$result['c23'] = $StatisticsModel->getChoiceCount('zt_bbs_cat','joinus',2);
			$result['c24'] = $StatisticsModel->getChoiceCount('zt_bbs_cat','joinus',3);
			$result['c25'] = $StatisticsModel->getChoiceCount('zt_bbs_cat','joinus',4);
			
			if($_GET['act'] == 'export'){
				$StatisticsModel->getExcleCat();
				die;
			}
			
			$this->assign('result',$result);
		}elseif($id == 3){
			$result['c1'] = $StatisticsModel->getChoiceCount('zt_dog_cat','have',1);
			$result['c2'] = $StatisticsModel->getChoiceCount('zt_dog_cat','have',2);
			$result['c3'] = $StatisticsModel->getChoiceCount('zt_dog_cat','sort',1);
			$result['c4'] = $StatisticsModel->getChoiceCount('zt_dog_cat','sort',2);
			$result['c5'] = $StatisticsModel->getChoiceCount('zt_dog_cat','time',1);
			$result['c6'] = $StatisticsModel->getChoiceCount('zt_dog_cat','time',2);
			$result['c7'] = $StatisticsModel->getChoiceCount('zt_dog_cat','time',3);
			$result['c8'] = $StatisticsModel->getChoiceCount('zt_dog_cat','war',1);
			$result['c9'] = $StatisticsModel->getChoiceCount('zt_dog_cat','war',2);
			$result['c10'] = $StatisticsModel->getChoiceCount('zt_dog_cat','win',1);
			$result['c11'] = $StatisticsModel->getChoiceCount('zt_dog_cat','win',2);
			$result['c12'] = $StatisticsModel->getChoiceCount('zt_dog_cat','win',3);
			$result['c13'] = $StatisticsModel->getChoiceCount('zt_dog_cat','habit',1);
			$result['c14'] = $StatisticsModel->getChoiceCount('zt_dog_cat','habit',2);
			$result['c15'] = $StatisticsModel->getChoiceCount('zt_dog_cat','habit',3);
			$result['c16'] = $StatisticsModel->getChoiceCount('zt_dog_cat','send',1);
			$result['c17'] = $StatisticsModel->getChoiceCount('zt_dog_cat','send',2);
			$result['c18'] = $StatisticsModel->getChoiceCount('zt_dog_cat','send',3);
			$result['c19'] = $StatisticsModel->getChoiceCount('zt_dog_cat','memory',1);
			$result['c20'] = $StatisticsModel->getChoiceCount('zt_dog_cat','memory',2);
			$result['c21'] = $StatisticsModel->getChoiceCount('zt_dog_cat','memory',3);
			
			if($_GET['act'] == 'export'){
				$StatisticsModel->getExcleDogAndCat();
				die;
			}
			
			$this->assign('result',$result);
		}elseif($id == 4){
			$result['c1'] = $StatisticsModel->getChoiceCount2('zt_baike_cat_behavior','gender',1);
			$result['c2'] = $StatisticsModel->getChoiceCount2('zt_baike_cat_behavior','gender',2);
			$result['c3'] = $StatisticsModel->getChoiceCount2('zt_baike_cat_behavior','age',1);
			$result['c4'] = $StatisticsModel->getChoiceCount2('zt_baike_cat_behavior','age',2);
			$result['c5'] = $StatisticsModel->getChoiceCount2('zt_baike_cat_behavior','age',3);
			$result['c6'] = $StatisticsModel->getChoiceCount2('zt_baike_cat_behavior','age',4);
			$result['c7'] = $StatisticsModel->getChoiceCount2('zt_baike_cat_behavior','understanding',1);
			$result['c8'] = $StatisticsModel->getChoiceCount2('zt_baike_cat_behavior','understanding',2);
			$result['c9'] = $StatisticsModel->getChoiceCount2('zt_baike_cat_behavior','understanding',3);
			$result['c10'] = $StatisticsModel->getChoiceCount2('zt_baike_cat_behavior','understanding',4);
			$result['c11'] = $StatisticsModel->getChoiceCount2('zt_baike_cat_behavior','do',1);
			$result['c12'] = $StatisticsModel->getChoiceCount2('zt_baike_cat_behavior','do',2);
			$result['c13'] = $StatisticsModel->getChoiceCount2('zt_baike_cat_behavior','do',3);
			$result['c14'] = $StatisticsModel->getChoiceCount2('zt_baike_cat_behavior','do',4);
			$result['c15'] = $StatisticsModel->getChoiceCount2('zt_baike_cat_behavior','do',5);
			$result['c16'] = $StatisticsModel->getChoiceCount2('zt_baike_cat_behavior','mean',1);
			$result['c17'] = $StatisticsModel->getChoiceCount2('zt_baike_cat_behavior','mean',2);
			$result['c18'] = $StatisticsModel->getChoiceCount2('zt_baike_cat_behavior','mean',3);
			$result['c19'] = $StatisticsModel->getChoiceCount2('zt_baike_cat_behavior','mean',4);
			$result['c20'] = $StatisticsModel->getChoiceCount2('zt_baike_cat_behavior','unusual',1);
			$result['c21'] = $StatisticsModel->getChoiceCount2('zt_baike_cat_behavior','unusual',2);
			$result['c22'] = $StatisticsModel->getChoiceCount2('zt_baike_cat_behavior','unusual',3);
			$result['c23'] = $StatisticsModel->getChoiceCount2('zt_baike_cat_behavior','reason',1);
			$result['c24'] = $StatisticsModel->getChoiceCount2('zt_baike_cat_behavior','reason',2);
			$result['c25'] = $StatisticsModel->getChoiceCount2('zt_baike_cat_behavior','help',1);
			$result['c26'] = $StatisticsModel->getChoiceCount2('zt_baike_cat_behavior','help',2);
			
			if($_GET['act'] == 'export'){
				$StatisticsModel->getExcleCatBehavior();
				die;
			}
			
			$this->assign('result',$result);
		}elseif($id == 5){
			$result['c1'] = $StatisticsModel->getChoiceCount('zt_pet_sterilization','style',1);
			$result['c2'] = $StatisticsModel->getChoiceCount('zt_pet_sterilization','style',2);
			$result['c3'] = $StatisticsModel->getChoiceCount('zt_pet_sterilization','style',3);
			$result['c4'] = $StatisticsModel->getChoiceCount('zt_pet_sterilization','age',1);
			$result['c5'] = $StatisticsModel->getChoiceCount('zt_pet_sterilization','age',2);
			$result['c6'] = $StatisticsModel->getChoiceCount('zt_pet_sterilization','age',3);
			$result['c7'] = $StatisticsModel->getChoiceCount('zt_pet_sterilization','gender',1);
			$result['c8'] = $StatisticsModel->getChoiceCount('zt_pet_sterilization','gender',2);
			$result['c9'] = $StatisticsModel->getChoiceCount('zt_pet_sterilization','understand',1);
			$result['c10'] = $StatisticsModel->getChoiceCount('zt_pet_sterilization','understand',2);
			$result['c11'] = $StatisticsModel->getChoiceCount('zt_pet_sterilization','understand',3);
			$result['c12'] = $StatisticsModel->getChoiceCount('zt_pet_sterilization','sterilization',1);
			$result['c13'] = $StatisticsModel->getChoiceCount('zt_pet_sterilization','sterilization',2);
			$result['c14'] = $StatisticsModel->getChoiceCount('zt_pet_sterilization','suggest',1);
			$result['c15'] = $StatisticsModel->getChoiceCount('zt_pet_sterilization','suggest',2);
			$result['c16'] = $StatisticsModel->getChoiceCount('zt_pet_sterilization','will',1);
			$result['c17'] = $StatisticsModel->getChoiceCount('zt_pet_sterilization','will',2);
			$result['c18'] = $StatisticsModel->getChoiceCount('zt_pet_sterilization','will',3);
			$result['c19'] = $StatisticsModel->getChoiceCount('zt_pet_sterilization','view',1);
			$result['c20'] = $StatisticsModel->getChoiceCount('zt_pet_sterilization','view',2);
			$result['c21'] = $StatisticsModel->getChoiceCount('zt_pet_sterilization','view',3);
			
			if($_GET['act'] == 'export'){
				$StatisticsModel->getExcleSterilization();
				die;
			}
			
			$this->assign('result',$result);
		}elseif($id == 6){
			$result['c1'] = $StatisticsModel->getChoiceCount2('zt_gui','gender',1);
			$result['c2'] = $StatisticsModel->getChoiceCount2('zt_gui','gender',2);
			$result['c3'] = $StatisticsModel->getChoiceCount2('zt_gui','age',1);
			$result['c4'] = $StatisticsModel->getChoiceCount2('zt_gui','age',2);
			$result['c5'] = $StatisticsModel->getChoiceCount2('zt_gui','age',3);
			$result['c6'] = $StatisticsModel->getChoiceCount2('zt_gui','age',4);
			$result['c7'] = $StatisticsModel->getChoiceCount2('zt_gui','will',1);
			$result['c8'] = $StatisticsModel->getChoiceCount2('zt_gui','will',2);
			$result['c9'] = $StatisticsModel->getChoiceCount2('zt_gui','have',1);
			$result['c10'] = $StatisticsModel->getChoiceCount2('zt_gui','have',2);
			$result['c11'] = $StatisticsModel->getChoiceCount2('zt_gui','have',3);
			$result['c12'] = $StatisticsModel->getChoiceCount2('zt_gui','reason',1);
			$result['c13'] = $StatisticsModel->getChoiceCount2('zt_gui','reason',2);
			$result['c14'] = $StatisticsModel->getChoiceCount2('zt_gui','reason',3);
			$result['c15'] = $StatisticsModel->getChoiceCount2('zt_gui','reason',4);
			$result['c16'] = $StatisticsModel->getChoiceCount2('zt_gui','reason',5);
			$result['c17'] = $StatisticsModel->getChoiceCount2('zt_gui','learn',1);
			$result['c18'] = $StatisticsModel->getChoiceCount2('zt_gui','learn',2);
			$result['c19'] = $StatisticsModel->getChoiceCount2('zt_gui','learn',3);
			$result['c20'] = $StatisticsModel->getChoiceCount2('zt_gui','learn',4);
			$result['c21'] = $StatisticsModel->getChoiceCount2('zt_gui','learn',5);
			$result['c22'] = $StatisticsModel->getChoiceCount2('zt_gui','communication',1);
			$result['c23'] = $StatisticsModel->getChoiceCount2('zt_gui','communication',2);
			$result['c24'] = $StatisticsModel->getChoiceCount2('zt_gui','why',1);
			$result['c25'] = $StatisticsModel->getChoiceCount2('zt_gui','why',2);
			$result['c26'] = $StatisticsModel->getChoiceCount2('zt_gui','why',3);
			
			if($_GET['act'] == 'export'){
				$StatisticsModel->getExcleGui();
				die;
			}
			
			$this->assign('result',$result);
		}elseif($id == 7){
			$result['c1'] = $StatisticsModel->getChoiceCount2('zt_sensitive','c1',1);
			$result['c2'] = $StatisticsModel->getChoiceCount2('zt_sensitive','c1',2);
			$result['c3'] = $StatisticsModel->getChoiceCount2('zt_sensitive','c1',3);
			$result['c4'] = $StatisticsModel->getChoiceCount2('zt_sensitive','c1',4);
			$result['c5'] = $StatisticsModel->getChoiceCount2('zt_sensitive','c2',1);
			$result['c6'] = $StatisticsModel->getChoiceCount2('zt_sensitive','c2',2);
			$result['c7'] = $StatisticsModel->getChoiceCount2('zt_sensitive','c2',3);
			$result['c8'] = $StatisticsModel->getChoiceCount2('zt_sensitive','c3',1);
			$result['c9'] = $StatisticsModel->getChoiceCount2('zt_sensitive','c3',2);
			$result['c10'] = $StatisticsModel->getChoiceCount2('zt_sensitive','c3',3);
			$result['c11'] = $StatisticsModel->getChoiceCount2('zt_sensitive','c4',1);
			$result['c12'] = $StatisticsModel->getChoiceCount2('zt_sensitive','c4',2);
			$result['c13'] = $StatisticsModel->getChoiceCount2('zt_sensitive','c4',3);
			$result['c14'] = $StatisticsModel->getChoiceCount2('zt_sensitive','c4',4);
			$result['c15'] = $StatisticsModel->getChoiceCount2('zt_sensitive','c5',1);
			$result['c16'] = $StatisticsModel->getChoiceCount2('zt_sensitive','c5',2);
			$result['c17'] = $StatisticsModel->getChoiceCount2('zt_sensitive','c5',3);
			$result['c18'] = $StatisticsModel->getChoiceCount2('zt_sensitive','c5',4);
			$result['c19'] = $StatisticsModel->getChoiceCount2('zt_sensitive','c6',1);
			$result['c20'] = $StatisticsModel->getChoiceCount2('zt_sensitive','c6',2);
			$result['c21'] = $StatisticsModel->getChoiceCount2('zt_sensitive','c6',3);
			$result['c22'] = $StatisticsModel->getChoiceCount2('zt_sensitive','c7',1);
			$result['c23'] = $StatisticsModel->getChoiceCount2('zt_sensitive','c7',2);
			
			if($_GET['act'] == 'export'){
				$StatisticsModel->getExcleStyle();
				die;
			}
			
			$this->assign('result',$result);
		}elseif($id == 8){
			$result['c1'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c1',1,8);
			$result['c2'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c1',2,8);
			$result['c3'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c1',3,8);
			$result['c4'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',1,8);
			$result['c5'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',2,8);
			$result['c6'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',3,8);
			$result['c7'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c3',1,8);
			$result['c8'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c3',2,8);
			$result['c9'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c3',3,8);
			$result['c10'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c4',1,8);
			$result['c11'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c4',2,8);
			$result['c12'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c5',1,8);
			$result['c13'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c5',2,8);
			$result['c14'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c6',1,8);
			$result['c15'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c6',2,8);
			$result['c16'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c7',1,8);
			$result['c17'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c7',2,8);
			
			if($_GET['act'] == 'export'){
				$StatisticsModel->getExcleQuChong();
				die;
			}
			
			$this->assign('result',$result);
		}elseif($id == 9){
			$result['c1'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c1',1,9);
			$result['c2'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c1',2,9);
			$result['c3'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',1,9);
			$result['c4'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',2,9);
			$result['c5'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',3,9);
			$result['c6'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',4,9);
			$result['c7'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c3',1,9);
			$result['c8'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c3',2,9);
			$result['c9'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c3',3,9);
			$result['c10'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c4',1,9);
			$result['c11'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c4',2,9);
			$result['c12'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c4',3,9);
			$result['c13'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c5',1,9);
			$result['c14'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c5',2,9);
			$result['c15'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c5',3,9);
			$result['c16'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c5',4,9);
			$result['c17'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c5',5,9);
			$result['c18'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c6',1,9);
			$result['c19'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c6',2,9);
			$result['c20'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c7',1,9);
			$result['c21'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c7',2,9);
			$result['c22'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c7',3,9);
			$result['c23'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c7',4,9);
			$result['c24'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c7',5,9);
			$result['c25'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c8',1,9);
			$result['c26'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c8',2,9);
			$result['c27'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c8',3,9);
			$result['c28'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c9',1,9);
			$result['c29'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c9',2,9);
			$result['c30'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c9',3,9);
			$result['c31'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c9',4,9);
			$result['c32'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c9',5,9);
			$result['c33'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c10',1,9);
			$result['c34'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c10',2,9);
			$result['c35'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c10',3,9);
			
			if($_GET['act'] == 'export'){
				$StatisticsModel->getExcleMaoShaPeng();
				die;
			}
			
			$this->assign('result',$result);
		}elseif($id == 10){
			$result['c1'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c1',1,10);
			$result['c2'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c1',2,10);
			$result['c3'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c1',3,10);
			$result['c4'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',1,10);
			$result['c5'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',2,10);
			$result['c6'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c3',1,10);
			$result['c7'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c3',2,10);
			$result['c8'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c3',3,10);
			$result['c9'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c4',1,10);
			$result['c10'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c4',2,10);
			$result['c11'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c4',3,10);
			$result['c12'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c5',1,10);
			$result['c13'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c5',2,10);
			$result['c14'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c6',1,10);
			$result['c15'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c6',2,10);
			$result['c16'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c6',3,10);
			$result['c17'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c7',1,10);
			$result['c18'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c7',2,10);
			$result['c19'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c7',3,10);
			$result['c20'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c7',4,10);
			
			if($_GET['act'] == 'export'){
				$StatisticsModel->getExcleChuYou();
				die;
			}
			
			$this->assign('result',$result);
		}elseif($id == 11){
			$result['c1'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c1',1,11);
			$result['c2'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c1',2,11);
			$result['c3'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',1,11);
			$result['c4'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',2,11);
			$result['c5'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',3,11);
			$result['c6'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',4,11);
			$result['c7'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c3',1,11);
			$result['c8'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c3',2,11);
			$result['c9'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c3',3,11);
			$result['c10'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c4',1,11);
			$result['c11'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c4',2,11);
			$result['c12'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c4',3,11);
			$result['c13'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c4',4,11);
			$result['c14'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c4',5,11);
			$result['c15'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c4',6,11);
			$result['c16'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c5',1,11);
			$result['c17'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c5',2,11);
			$result['c18'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c5',3,11);
			$result['c19'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c6',1,11);
			$result['c20'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c6',2,11);
			$result['c21'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c6',3,11);
			$result['c22'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c7',1,11);
			$result['c23'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c7',2,11);
			$result['c24'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c7',3,11);
			$result['c25'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c8',1,11);
			$result['c26'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c8',2,11);
			$result['c27'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c8',3,11);
			$result['c28'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c8',4,11);
			$result['c29'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c8',5,11);
			$result['c30'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c8',6,11);
			$result['c31'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c9',1,11);
			$result['c32'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c9',2,11);
			$result['c33'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c10',1,11);
			$result['c34'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c10',2,11);
			$result['c35'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c10',3,11);
			$result['c36'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c10',4,11);
			$result['c37'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c10',5,11);
			$result['c38'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c10',6,11);
			
			if($_GET['act'] == 'export'){
				$StatisticsModel->getExclePetYangHu();
				die;
			}
			
			$this->assign('result',$result);
		}elseif($id == 12){
			$result['c1'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c1',1,12);
			$result['c2'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c1',2,12);
			$result['c3'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',1,12);
			$result['c4'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',2,12);
			$result['c5'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',3,12);
			$result['c6'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',4,12);
			$result['c7'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c3',1,12);
			$result['c8'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c3',2,12);
			$result['c9'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c3',3,12);
			$result['c10'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c4',1,12);
			$result['c11'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c4',2,12);
			$result['c12'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c5',1,12);
			$result['c13'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c5',2,12);
			$result['c14'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c5',3,12);
			$result['c15'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c5',4,12);
			$result['c16'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c5',5,12);
			$result['c17'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c6',1,12);
			$result['c18'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c6',2,12);
			$result['c19'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c6',3,12);
			$result['c20'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c6',4,12);
			$result['c21'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c7',1,12);
			$result['c22'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c7',2,12);
			$result['c23'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c7',3,12);
			$result['c24'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c7',4,12);
			
			if($_GET['act'] == 'export'){
				$StatisticsModel->getExcleKuangQuanBing();
				die;
			}
		}elseif($id == 13){
			
			$result['c1'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c1',1,13);
			$result['c2'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c1',2,13);
			$result['c3'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',1,13);
			$result['c4'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',2,13);
			$result['c5'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',3,13);
			$result['c6'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',4,13);
			$result['c7'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c3',1,13);
			$result['c8'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c3',2,13);
			$result['c9'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c3',3,13);
			$result['c10'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c4',1,13);
			$result['c11'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c4',2,13);
			$result['c12'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c5',1,13);
			$result['c13'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c5',2,13);
			$result['c14'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c5',3,13);
			$result['c15'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c6',1,13);
			$result['c16'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c6',2,13);
			$result['c17'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c7',1,13);
			$result['c18'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c7',2,13);
			$result['c19'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c7',3,13);
			$result['c20'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c7',4,13);
			$result['c21'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c8',1,13);
			$result['c22'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c8',2,13);
			$result['c23'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c8',3,13);
			$result['c24'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c8',4,13);
			$result['c25'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c8',5,13);
			$result['c26'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c8',6,13);
			$result['c27'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c9',1,13);
			$result['c28'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c9',2,13);
			$result['c29'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c9',3,13);
			$result['c30'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c9',4,13);
			$result['c31'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c10',1,13);
			$result['c32'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c10',2,13);
			$result['c33'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c10',3,13);
			$result['c34'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c10',4,13);
			$result['c35'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c10',5,13);
			$result['c36'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c11',1,13);
			$result['c37'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c11',2,13);
			$result['c38'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c11',3,13);
			$result['c39'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c11',4,13);
			
			if($_GET['act'] == 'export'){
				$StatisticsModel->getExcleGouGouZiZhiMeiShi();
				die;
			}
		}elseif($id == 14){
			
			$result['c1'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c1',1,14);
			$result['c2'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c1',2,14);
			$result['c3'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',1,14);
			$result['c4'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',2,14);
			$result['c5'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',3,14);
			$result['c6'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',4,14);
			$result['c7'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c3',1,14);
			$result['c8'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c3',2,14);
			$result['c9'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c3',3,14);
			$result['c10'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c4',1,14);
			$result['c11'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c4',2,14);
			$result['c12'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c5',1,14);
			$result['c13'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c5',2,14);
			$result['c14'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c5',3,14);
			$result['c15'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c6',1,14);
			$result['c16'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c6',2,14);
			$result['c17'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c7',1,14);
			$result['c18'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c7',2,14);
			$result['c19'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c7',3,14);
			$result['c20'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c8',1,14);
			$result['c21'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c8',2,14);
			$result['c22'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c8',3,14);
			$result['c23'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c8',4,14);
			$result['c24'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c8',5,14);
			$result['c25'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c9',1,14);
			$result['c26'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c9',2,14);
			$result['c27'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c9',3,14);
			$result['c28'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c10',1,14);
			$result['c29'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c10',2,14);
			$result['c30'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c10',3,14);
			$result['c31'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c10',4,14);
			$result['c32'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c10',5,14);
			$result['c33'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c11',1,14);
			$result['c34'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c11',2,14);
			$result['c35'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c11',3,14);
			$result['c36'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c11',4,14);
			
			if($_GET['act'] == 'export'){
				$StatisticsModel->getExcleMaoMiMeiShi();
				die;
			}
		}elseif($id == 15){
			
			$result['c1'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c1',1,15);
			$result['c2'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c1',2,15);
			
			$result['c3'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',1,15);
			$result['c4'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',2,15);
			$result['c5'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',3,15);
			$result['c6'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',4,15);
			
			$result['c7'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c3',1,15);
			$result['c8'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c3',2,15);
			
			$result['c9'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c4',1,15);
			$result['c10'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c4',2,15);
			
			$result['c11'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c5',1,15);
			$result['c12'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c5',2,15);
			$result['c13'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c5',3,15);
			$result['c14'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c5',4,15);
			
			$result['c15'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c6',1,15);
			$result['c16'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c6',2,15);
			$result['c17'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c6',3,15);
			
			$result['c18'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c7',1,15);
			$result['c19'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c7',2,15);
			
			$result['c20'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c8',1,15);
			$result['c21'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c8',2,15);
			
			$result['c22'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c9',1,15);
			$result['c23'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c9',2,15);
			$result['c24'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c9',3,15);
			$result['c25'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c9',4,15);
			$result['c26'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c9',5,15);
			$result['c27'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c9',6,15);
			
			
			if($_GET['act'] == 'export'){
				$StatisticsModel->getExcleLiuLangTianshi();
				die;
			}
		}elseif($id == 16){
			


			$result['c1'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c1',1,16);
			$result['c2'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c1',2,16);
				
			$result['c3'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',1,16);
			$result['c4'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',2,16);
			$result['c5'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',3,16);
			$result['c6'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c2',4,16);
				
			$result['c7'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c3',1,16);
			$result['c8'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c3',2,16);
			$result['c9'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c3',3,16);
			
			$result['c10'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c4',1,16);
			$result['c11'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c4',2,16);
			
			
			$result['c12'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c5',1,16);
			$result['c13'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c5',2,16);
			$result['c14'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c5',3,16);
				
			$result['c15'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c6',1,16);
			$result['c16'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c6',2,16);
			$result['c17'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c6',3,16);
				
			$result['c18'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c7',1,16);
			$result['c19'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c7',2,16);
			$result['c20'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c7',3,16);
			$result['c21'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c7',4,16);
			$result['c22'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c7',5,16);
			
			$result['c23'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c8',1,16);
			$result['c24'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c8',2,16);
			$result['c25'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c8',3,16);
			$result['c26'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c8',4,16);
			
			$result['c27'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c9',1,16);
			$result['c28'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c9',2,16);
			$result['c29'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c9',3,16);
			$result['c30'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c9',4,16);
			$result['c31'] = $StatisticsModel->getChoiceCount3('zt_sensitive','c9',5,16);
			
			if($_GET['act'] == 'export'){
				$StatisticsModel->getExcleGouGouXunlian($result);
				die;
			}
			
	}
		$this->assign('result',$result);
		$this->display('result');
	}
}
?>