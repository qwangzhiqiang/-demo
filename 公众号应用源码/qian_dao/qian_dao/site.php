<?php
/**
 * 现场签到红包模块微站定义
 *
 * @author hyl666666
 * @url 
 */
defined('IN_IA') or exit('Access Denied');
require_once(dirname(__FILE__)."/phpexcel/Classes/PHPExcel.php");
require_once(dirname(__FILE__)."/pay.php");
class Qian_daoModuleSite extends WeModuleSite {

	public function doMobileIndex() {
		//这个操作被定义用来呈现 功能封面
		global $_GPC, $_W;
		// $_W['page']['footer']=$this->footer;
		$setup=pdo_get('qian_dao_setup', array('uniacid' => $_W["uniacid"]));
		load()->model('mc');
		$userinfo=mc_oauth_userinfo($_W['uniacid']);
		$user=pdo_get('qian_dao_user',array('openid'=>$userinfo['openid'],'uniacid' => $_W["uniacid"]));
		if($user){
			include $this->template('home');
		}else{
			include $this->template('index');
		}
		
		
		
	}
	public function doMobileSuccess() {
		//这个操作被定义用来呈现 功能封面
		global $_GPC, $_W;
		// $_W['page']['footer']=$this->footer;
		$setup=pdo_get('qian_dao_setup', array('uniacid' => $_W["uniacid"]));
		if((time()<strtotime($setup['start']))||(time()>strtotime($setup['end']))){
			return json_encode(array('status'=>'outtime'));
		}
		load()->model('mc');
		$userinfo=mc_oauth_userinfo($_W['uniacid']);
		$user=pdo_get('qian_dao_user',array('openid'=>$userinfo['openid'],'uniacid' => $_W["uniacid"]));
		if($user){
			return json_encode(array('status'=>'hassuccess'));
		}

		if($userinfo){
			if($_GPC['tel']&&$_GPC['username']){
				$bonus=rand($setup['min'],$setup['max']);
				if(($bonus+$setup['hasgrant'])>$setup['total']){
					return json_encode(array('status'=>'nomon'));
				}
				$data=array(
					'username'=>$_GPC['username'],
					'tel'     =>$_GPC['tel'],
					'uniacid' =>$_W["uniacid"],
					'openid'  => $userinfo['openid'],
					'bonus'=>$bonus
					);
				pdo_update('qian_dao_setup', array('hasgrant'=>$bonus+$setup['hasgrant']), array('setupid' => $setup['setupid']));
				$result = pdo_insert('qian_dao_user', $data);
				$weixinpay=new Pay();
				$partner_trade_no=date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
				$res=$weixinpay->weixin_transfer_money($userinfo['openid'],'',$bonus*100,$partner_trade_no,'签到红包');
				// var_dump($res);
				if ((!empty($result))&&($res['result_code']=='SUCCESS')) {
					return json_encode(array('status'=>'success','bonus'=>$bonus));
				}
			}
		}else{
			return json_encode(array('status'=>'noweixin'));
		}
		
		
	}
	

	public function doWebSetup() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_GPC, $_W;
		$setup=pdo_get('qian_dao_setup', array('uniacid' => $_W["uniacid"]));

		load()->func('tpl');
		include $this->template('setup');
		// var_dump($_POST);
		if($_GPC['action']=='save'){
			$data=array(
					'bgimg'=>$_GPC['bgimg'],
					'max'     =>$_GPC['max'],
					'uniacid' =>$_W["uniacid"],
					'min'=>$_GPC['min'],
					'title'=>$_GPC['title'],
					'total'=>$_GPC['total'],
					'start'     =>$_GPC['daterange']['start'],
					'end'     =>$_GPC['daterange']['end'],
					);
			if($setup){
				$result = pdo_update('qian_dao_setup', $data, array('setupid' => $setup['setupid']));
				if (!empty($result)) {
					message('更新成功');
				}
			}else{
				$result = pdo_insert('qian_dao_setup', $data);	
				if (!empty($result)) {
					message('更新成功');
				}	
			}
			
		}
	}
	public function doWebUser() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_GPC, $_W;
		$user=pdo_getall('qian_dao_user', array('uniacid' => $_W["uniacid"]));
		load()->func('tpl');
		include $this->template('user');

	}
	public function doWebExcel() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_GPC, $_W;
		$excel = new PHPExcel();
		$letter = array('A','B','C','D');
		$tableheader = array('序号','姓名','电话','红包金额');
		for($i = 0;$i < count($tableheader);$i++) {
			$excel->getActiveSheet()->setCellValue("$letter[$i]1","$tableheader[$i]");
		}

		$user=pdo_getall('qian_dao_user', array('uniacid' => $_W["uniacid"]), array('userid','username','tel','bonus'));

		for ($i = 2;$i <= count($user) + 1;$i++) {
			$j = 0;
			foreach ($user[$i - 2] as $key=>$value) {
			$excel->getActiveSheet()->setCellValue("$letter[$j]$i","$value");
			$j++;
			}
		}
		$write = new PHPExcel_Writer_Excel5($excel);
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");;
		header('Content-Disposition:attachment;filename="testdata.xls"');
		header("Content-Transfer-Encoding:binary");
		$result=$write->save('php://output');
		var_dump($result);
		

	}


}