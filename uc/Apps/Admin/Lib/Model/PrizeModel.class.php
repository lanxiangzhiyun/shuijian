<?php
/**
 * 奖品管理Model
 *
 * 
 * 
 */
class PrizeModel extends Model{
	
	protected $tableName='market_prize';
	
	//获得奖品列表
	public function getPrizeList(){
		$result = $this->select();
		return $result;
	}

	//获得奖品详情信息
	public function getPrizeInfo($id){
		$info = $this->where('id='.$id)->find();
		return $info;
	}

	//获取抽奖日志记录
	public function getPrizeLogList($param){

		$result = M('market_prize_log pl')->join("LEFT JOIN market_prize p ON pl.pid=p.id")->field('pl.id,pl.create_time,p.title,p.prize')->where($param['where'])->page($param['page'])->limit($param['limit'])->order($param['order'])->select();
		
		return $result;
	}
	
	//获取抽奖日志记录总数
	public function getPrizeLogCount($param){
		$con = M('market_prize_log pl')->join("LEFT JOIN market_prize p ON pl.pid=p.id")->where($param['where'])->count();
		return $con;
	}
}

?>