
<?php
/**
 * gold_eggs模块微站定义
 *
 * @author bendilaosiji
 * @url 
 */
defined('IN_IA') or exit('Access Denied');
require_once(dirname(__FILE__)."/pay.php");
class Gold_eggsModuleSite extends WeModuleSite {

	public function doMobileTest(){
		$telRegex = "/^(13[0-9]|14[579]|15[0-3,5-9]|16[6]|17[0135678]|18[0-9]|19[89])\d{8}$/";
		$tel='1381381381323';
	    //var_dump(preg_match($telRegex,$tel)); 
		
		$account_api = WeAccount::create();
		
        $token = $account_api->getAccessToken();
      	$userinfo=mc_oauth_userinfo($_W['uniacid']); 
       $url="https://api.weixin.qq.com/cgi-bin/user/info?access_token={$token}&openid={$userinfo['openid']}&lang=zh_CN";
        $response = ihttp_get($url);
		
      	$json2Array = json_decode($response['content'],true);  
        print_r($json2Array['subscribe']);
		
		
	}

	public function doMobileIndex() {
		//这个操作被定义用来呈现 功能封面
		global $_W,$_GPC;

		$userinfo=mc_oauth_userinfo($_W['uniacid']); 
		if(empty($userinfo)){
			message('请在微信打开',$this->createMobileUrl('index'),'error');
		}
		
		if($_GPC['method']=='getdata'){
			$datas=null;
			//1判断用户是否关注公众号
			$conf_data=pdo_get('gold_eggs_configs',['id'=>1]);
			$account_api = WeAccount::create();
		
			$token = $account_api->getAccessToken();
			$userinfo=mc_oauth_userinfo($_W['uniacid']); 
			$url="https://api.weixin.qq.com/cgi-bin/user/info?access_token={$token}&openid={$userinfo['openid']}&lang=zh_CN";
			$response = ihttp_get($url);
			$json2Array = json_decode($response['content'],true);  
            if($json2Array['subscribe']==1){
			//2判断手机号码是否正确
				$telRegex = "/^(13[0-9]|14[579]|15[0-3,5-9]|16[6]|17[0135678]|18[0-9]|19[89])\d{8}$/";
				$tel=$_GPC['tel'];
				if(preg_match($telRegex,$tel)==1){
					//3判断该微信号和手机号码是否参加过活动
					$touser=pdo_get('gold_eggs_users',['openid'=>$userinfo['openid']]);
					$totel=pdo_get('gold_eggs_users',['tel'=>$tel]);
					if($touser||$totel){
						$datas=['status'=>0,'msg'=>'感谢支持,您已经参过活动了'];
					}else{
						$user_data = array(
							'openid' => $userinfo['openid'],
							'nickname' => $userinfo['nickname'],
							'headingimg' => $userinfo['headimgurl'],
							'sex' => $userinfo['sex'],
							'tel'=>$tel,
							'country' => $userinfo['country'],
							'province' => $userinfo['province'],
							'city' => $userinfo['city'],
							'regtime' => time(),
						);
						$result = pdo_insert('gold_eggs_users', $user_data);
						//4判断金额是否足够一元
						
						if(($conf_data['totalamount']-1)>=0){
							//5给用户支付到零钱
							//更uid等于2的用户的用户名
							$cfg_data = array(
								'totalamount' => $conf_data['totalamount']-1,
							);
							$result = pdo_update('gold_eggs_configs', $cfg_data, array('id' => 1));
							
							
							$ordernum=$this->getordernums();
							$weixinpay=new Pay();
							$partner_trade_no=$ordernum;
							
							$bonus=1;
							$res=$weixinpay->weixin_transfer_money($userinfo['openid'],'',$bonus*100,$partner_trade_no,'抽奖红包');
							
						
							//验证支付结果
							if($res['result_code']=="SUCCESS"){
								//6创建订单
								$order_data = array(
									'partner_trade_no' => $ordernum,
									'payment_no' => $res['payment_no'],
									'price' => 1,
									'status' => 1,
									'error' => '提现成功',
								);
								pdo_insert('gold_eggs_orders', $order_data);
								
								//发送模板消息
								$tpl_data = array(
								'first' => array(
									'value' => "砸金蛋红包提现成功！",
									'color' => '#ff510'
								),
								'orderProductPrice' => array(
									'value' => '1元',
									'color' => '#ff510'
								),
								'orderProductName' => array(
									'value' => '参加砸金蛋',
									'color' => '#ff510'
								),
								'orderAddress' => array(
									'value' => $ordernum,
									'color' => '#ff510'
								),
								'orderName' => array(
									'value' => "2018" ,
									'color' => '#ff510'
								),
							);
							
							$indexurl=$_W['siteroot'].'app/'.$this->createMobileUrl('index');
							$url=$indexurl;
							$account_api = WeAccount::create();
							$account_api->sendTplNotice($userinfo['openid'], 'GqqCO3-KS5pZOR1EYfnG7j6o2kcD39nqoSd77pQK0J0', $tpl_data,$url);
								
							}else{
								//6创建订单
								$order_data = array(
									'partner_trade_no' => $ordernum,
									'payment_no' => '',
									'price' => 1,
									'status' => 0,
									'error' => $res['err_code_des'],
								);
								pdo_insert('gold_eggs_orders', $order_data);
							}
							
						$datas=['status'=>1,'msg'=>'恭喜,获得1元红包'];
							
						}else{
							$datas=['status'=>0,'msg'=>'活动已结束'];
						}
					
					}
				
				}else{
					$datas=['status'=>0,'msg'=>'请填写正确的手机号码'];
				}
					
			}else{
				$datas=['status'=>0,'msg'=>'请先关注公众号'.$conf_data['gongzhonghao']];
			}
			
			
			
			
			return json_encode($datas);
		}
		
		$conf_data=pdo_get('gold_eggs_configs',['id'=>1]);
		include $this->template('jindan/index');
	}
	
	public function doWebConfs() {
		//这个操作被定义用来呈现 管理中心导航菜单
      	global $_W,$_GPC;
		if($_W['ispost']){
				//更新数据
				$conf_data = array(
					'id'=>1,
					'title' => $_GPC['title'],
					'backimg' => $_GPC['backimg'],
					'topimg' => $_GPC['topimg'],
					'middleimg' => $_GPC['middleimg'],
					'bottomimg' => $_GPC['bottomimg'],
					'codeimg' => $_GPC['codeimg'],
					'gongzhonghao'=> $_GPC['gongzhonghao'],
					'totalamount' => $_GPC['money'],
				);
				$result = pdo_insert('gold_eggs_configs', $conf_data,true);
				if (!empty($result)) {
					
					message('更新成功');
				}
		}
		
		$confs=pdo_get('gold_eggs_configs',['id'=>1]);
		include $this->template('confs');
	}
		public function doWebUsers() {
		//用户信息
		global $_W,$_GPC;
		$users = pdo_getall('gold_eggs_users');
		include $this->template('users');
	}
		public function doWebOrders() {
		//订单信息
		global $_W,$_GPC;
				$sql="select * from ".tablename('gold_eggs_orders');	
                $sources=pdo_fetchall($sql);
                //分页开始
                $total=count($sources);
                $pageindex=max($_GPC['page'],1);
                $pagesize=6;
                $pager=pagination($total,$pageindex,$pagesize);
                $p=($pageindex-1)*6;
                $sql.=" order by id desc limit ".$p." , ".$pagesize;
                $orderdata=pdo_fetchall($sql);
		include $this->template('orders');
		
	}
	
	//创建订单号
	public function getordernums(){
      	$danhao = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
      	return $danhao.mt_rand(1000, 9999);
    }
	

}