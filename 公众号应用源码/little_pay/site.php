<?php
/**
 * 微课程模块微站定义
 *
 * @author bendilaosiji
 * @url http://minbang.bendilaosiji.com/
 */
defined('IN_IA') or exit('Access Denied');

class Little_payModuleSite extends WeModuleSite {


  	public function doMobileCode(){
    	global $_W,$_GPC;
      	$userinfo=mc_oauth_userinfo($_W['uniacid']); 
      	
      	/*
      	require IA_ROOT . '/addons/little_pay/phpqrcode/qrlib.php';
      	$url="http://edu.51cto.com";
	    QRcode::png($url);*/
           /* 
		$url="http://edu.51cto.com";
      	//创建一张二维码
     	require IA_ROOT . '/addons/little_pay/phpqrcode/qrlib.php';
      	$value = $url;
        $errorCorrectionLevel = "L";//容错级别  
        $matrixPointSize = "8"; //生成图片大小
       	$margin="1";  //边距
        QRcode::png($value, IA_ROOT.'/addons/little_pay/haibao/images/'.$userinfo['openid'].'.png', $errorCorrectionLevel, $matrixPointSize,$margin);

		*/
      	//1把文字添加到图片
      	
      	
$dst_path =  IA_ROOT."/addons/little_pay/haibao/lldog.jpg"; 

      //二维码图片
      $src_path =  IA_ROOT."/addons/little_pay/haibao/images/opqp80q1XnInacQ7cniTcFX65ngg.png";
      


//创建图片的实例
$dst = imagecreatefromstring(file_get_contents($dst_path));
$code=imagecreatefromstring(file_get_contents($src_path));
//获取背景图片的宽高
list($dst_w, $dst_h) = getimagesize($dst_path);
list($code_w, $code_h) = getimagesize($src_path);
      
//在背景图片上添加图片
      //将水印图片复制到目标图片上，最后个参数50是设置透明度，这里实现半透明效果
imagecopymerge($dst, $code, 622, 800, 0, 0, $code_w, $code_h, 100);
      
      
      
//打上文字
$font = "../addons/little_pay/yahei.ttf"; 
$black = imagecolorallocate($dst, 44, 33, 120);//字体颜色

//用户昵称
imagefttext($dst, 28, 0, 426, 1030, $black, $font, "XXXXX我要写字体啦");

//输出图片
list($dst_w, $dst_h, $dst_type) = getimagesize($dst_path);
switch ($dst_type) {
    case 1://GIF
        header('Content-Type: image/gif');
        imagegif($dst);
        break;
    case 2://JPG
        header('Content-Type: image/jpeg');
    	imagejpeg($dst);
       //imagejpeg($dst, IA_ROOT."/addons/little_pay/haibao/"."888888888888888888888".".jpg");
    	
        break;
    case 3://PNG
        header('Content-Type: image/png');
        imagepng($dst);
        break;
    default:
        break;
}
imagedestroy($dst);
      
      
      
      
      
      
      
      
      	//2把图片添加到图片上
      

    }
  
  	public function doMobileX(){
    	global $_W,$_GPC;
      	require IA_ROOT . '/addons/little_pay/phpqrcode/qrlib.php';
	   QRcode::png('PHP QR Code :)');
     
    }
  
  	public function doMobileGundong(){
    	global $_W,$_GPC;
      
        include $this->template('gundong');
    }
  
  
  	public function doMobileJiazai(){
    	global $_W,$_GPC;
      
        include $this->template('xiala');
    }

  
   public function doMobileSendtpl(){
   global $_W,$_GPC;
     
     $data = array(
    'first' => array(
        'value' => "51cto订单成功！",
        'color' => '#ff510'
    ),
    'keyword1' => array(
        'value' => '1008',
        'color' => '#ff510'
    ),
    'keyword2' => array(
        'value' => '黄焖鸡米饭',
        'color' => '#ff510'
    ),
    'keyword3' => array(
        'value' => '388元',
        'color' => '#ff510'
    ),
    'remark' => array(
        'value' => "欢迎您再次订购" ,
        'color' => '#ff510'
    ),
);
$userinfo=mc_oauth_userinfo($_W['uniacid']);   

$account_api = WeAccount::create();
$result = $account_api->sendTplNotice($userinfo['openid'], 'GqqCO3-KS5pZOR1EYfnG7j6o2kcD39nqoSd77pQK0J0', $data,$url = "https://minbang.bendilaosiji.com/app/index.php?i=2&c=entry&do=index&m=little_pay", $topcolor = '#FF683F');
print_r($result);
   	
   }
  
  	public function doMobileZhifu(){
    	global $_W,$_GPC;
      	//获取用户要充值的金额数
    $fee = floatval(1);
    if($fee <= 0) {
        message('支付错误, 金额小于0');
    }
    // 一些业务代码。
    //构造支付请求中的参数
    $params = array(
        'tid' => $this->getordernums(),      //充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
        'ordersn' => $this->getordernums(),  //收银台中显示的订单号
        'title' => '购买XX课程',          //收银台中显示的标题
        'fee' => $fee,      //收银台中显示需要支付的金额,只能大于 0
      
    );
    //调用pay方法
    $this->pay($params);
    }
  
  
  
  
  
  
  
  	
	public function doMobileIndex() {
		
       //首页
      	global $_W, $_GPC;
    	
      	$coursedata = pdo_getall('little_pay_course');
      	include $this->template('index');
      
	}
  public function getordernums(){
      	$danhao = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
      	return $danhao.mt_rand(1000, 9999);
    }
  
  
  	//详情页面
  	public function doMobiledetail(){
    	global $_W,$_GPC;
      	$coursedata = pdo_get('little_pay_course',array('id'=>$_GPC['sid']));
    	include $this->template('detail');  
    }
  	
  	//创建订单页面
  	public function doMobilecreateorder(){
    
    	global $_W,$_GPC;
      	$course= pdo_get('little_pay_course',array('id'=>$_GPC['sid']));
      		$userinfo=mc_oauth_userinfo($_W['uniacid']);  //必须在微信浏览器打开
      	//创建订单
      	 $user_data = array(
              'ordernum' => $this->getordernums(),
              'userid' =>$userinfo['openid'],
            	'nicheng'=>$userinfo['nickname'],
            	'productnum'=>$course['id'],
            	'price'=>$course['price'],
            	'status'=>0, //状态
               
                'createtime'=>time(),
               
          );
          $result = pdo_insert('little_pay_order', $user_data);
      		if (!empty($result)) {
    	  $uid = pdo_insertid();
          $order=pdo_get('little_pay_order',array('id'=>$uid));
      }else{
            message('该商品已下架',$this->createMobileUrl('index'),'error');
            }

              include $this->template('orderdetail');  
          }
  
  	//发起支付页面
  	public function doMobileTopay(){
    
    	global $_W,$_GPC;
      	
      	 $toorder = pdo_get('little_pay_order', array('ordernum' => $_GPC['ordernum']));
			//1 判断订单号是否存在 , 2 判断订单状态 0 1 
      	echo json_encode(array('status'=>'success','data'=>array('fee'=>$toorder['price'],'ordertid'=>$toorder['ordernum'])));
      
      
    	
    }
  
  
    
  	public function payResult($params) {
    //一些业务代码
    //根据参数params中的result来判断支付是否成功
    if ($params['result'] == 'success' && $params['from'] == 'notify') {
       	load()->func('logging');
        //记录文本日志
        logging_run($params);
			$user_data = array(
                'status' =>1,
               'updatetime'=>time(),
            );
            $result = pdo_update('little_pay_order', $user_data, array('ordernum' => $params['tid']));

    }
    //因为支付完成通知有两种方式 notify，return,notify为后台通知,return为前台通知，需要给用户展示提示信息
    //return做为通知是不稳定的，用户很可能直接关闭页面，所以状态变更以notify为准
    //如果消息是用户直接返回（非通知），则提示一个付款成功
    //如果是JS版的支付此处的跳转则没有意义
    if ($params['from'] == 'return') {
        if ($params['result'] == 'success') {
            message('支付成功！', '../../app/' . url('mc/home'), 'success');
        } else {
            message('支付失败！', '../../app/' . url('mc/home'), 'error');
        }
    }
}
  
  
  
  
  
  
  
  
  
  
  
  
  	public function doMobileOrder(){
    echo json_encode(array('status'=>'success','data'=>array('fee'=>1,'ordertid'=>$this->getordernums())));
    }

  	public function doMobileCeshi(){
    	global $_W,$_GPC;
      	//获取用户要充值的金额数
	$fee = floatval(1);
	if($fee <= 0) {
		message('支付错误, 金额小于0');
	}
	// 一些业务代码。
	//构造支付请求中的参数
	$params = array(
		'tid' => $this->getordernums(),      //充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
		'ordersn' => $this->getordernums(),  //收银台中显示的订单号
		'title' => '系统充值余额',          //收银台中显示的标题
		'fee' => $fee,      //收银台中显示需要支付的金额,只能大于 0
		
	);
	//调用pay方法
	$this->pay($params);
    }

  //课程管理
	public function doWebCourses() {
		global $_W,$_GPC;
      	//$coursedata=pdo_getall('little_pay_course');
      	 $sql="select * from ".tablename('little_pay_course');	
                $sources=pdo_fetchall($sql);
                //分页开始
                $total=count($sources);
                $pageindex=max($_GPC['page'],1);
                $pagesize=10;
                $pager=pagination($total,$pageindex,$pagesize);
                $p=($pageindex-1)*10;
                $sql.=" order by id desc limit ".$p." , ".$pagesize;
                $coursedata=pdo_fetchall($sql);
      
      
      	include $this->template('courses');
	}
  
  	public function doWebupdatecourse(){
    global $_W,$_GPC;
      
      if($_POST){
        	 $user_data = array(
              'title' => $_GPC['title'],
              'category' =>$_GPC['category'],
            	'type'=>$_GPC['type'],
            	'hosturl'=>$_W['attachurl'],
            	'cover'=>$_GPC['cover'],
            	'content'=>$_GPC['content'],
                'price'=>1,
                'createtime'=>time(),
                'ispay'=>1,
          );
        
          $result = pdo_update('little_pay_course', $user_data ,['id'=>$_GPC['courseid']]);
          if (!empty($result)) {
             
              message('更新课程成功',$this->createWebUrl('courses'),'success');
          }else{
          	message('更新课程失败',$this->createWebUrl('courses'),'error');
          }
        	
        }
      
      
      
      
      $coursedata = pdo_get('little_pay_course', array('id' => $_GPC['id']));
      	if($coursedata){
        		
        	}else{
        	message('您要编辑的课程不存在',$this->createWebUrl('courses'),'error');
        }
		
      	
      
      //获取所有分类
      	$categorys = pdo_getall('little_pay_category');
      include $this->template('updatecourse');
    	
    }
  
  	public function doWebAddcourse(){
    
    	global $_W,$_GPC;
      	
      	if($_POST){
        	  $user_data = array(
              'title' => $_GPC['title'],
              'category' =>$_GPC['category'],
            	'type'=>$_GPC['type'],
            	'hosturl'=>$_W['attachurl'],
            	'cover'=>$_GPC['cover'],
            	'content'=>$_GPC['content'],
                'price'=>1,
                'createtime'=>time(),
                'ispay'=>1,
          );
          $result = pdo_insert('little_pay_course', $user_data);
          if (!empty($result)) {
             
              message('添加文章成功',$this->createWebUrl('courses'),'success');
          }else{
          	message('添加失败',$this->createWebUrl('courses'),'error');
          }
        }
      
      	//获取所有分类
      	$categorys = pdo_getall('little_pay_category');
      
      	include $this->template('addcourse');
    }
  
	public function doWebCategorys() {
		global $_W,$_GPC;
      	if($_GPC['action']=='del'){
        	$cid=$_GPC['cid'];
          	//删除用户名为mizhou2的记录
            $result = pdo_delete('little_pay_category', array('id' => $cid));
            if (!empty($result)) {
                message('删除成功',$this->createWebUrl('Categorys'),'success');
            }else{
            	message('删除失败',$this->createWebUrl('Categorys'),'error');
            }
        }
      
      	$categorys = pdo_getall('little_pay_category');
      	
      	include $this->template('category');
      
      	
	}
  	//添加分类方法
  	public function doWebAddcategory(){
    	global $_W,$_GPC;
      
      	
      	if($_W['ispost']){
        	
          $user_data = array(
              'name' => $_GPC['name'],
              'cover' =>$_GPC['cover'],
            	'hosturl'=>$_W['attachurl'],
            	'price'=>23,
            	'sort'=>2,
            	'createtime'=>time(),
          );
          $result = pdo_insert('little_pay_category', $user_data);
          if (!empty($result)) {
              $uid = pdo_insertid();
              message('添加用户成功，UID为' . $uid);
          }
          
          
          
        }
      
      	include $this->template('addcategory');
    }
  
  
  	//修改分类
  	public function doWebUpdatecategory(){
    	global $_W,$_GPC;
      	$category = pdo_get('little_pay_category', array('id' => $_GPC['cid']));
      	if($category){
        		
        	}else{
        	message('您要编辑的分类不存在',$this->createWebUrl('Categorys'),'error');
        }
		
      	if($_POST){
        	 $user_data = array(
              'name' => $_GPC['name'],
              'cover' =>$_GPC['cover'],
            	'hosturl'=>$_W['attachurl'],
            	'price'=>23,
            	'sort'=>2,
            	'createtime'=>time(),
          );
          $result = pdo_update('little_pay_category', $user_data,array('id'=>$_GPC['cid']));
          if (!empty($result)) {
             
             message('更新成功',$this->createWebUrl('Categorys'),'success');
          }else{
          	message('更新失败',$this->createWebUrl('Categorys'),'error');
          }
        	
        }
      
      
      	include $this->template('updcategory');
    }	
  
	public function doWebOrders() {
		//这个操作被定义用来呈现 管理中心导航菜单
      	include $this->template('index');  //后台   template/index.html 
	}
	public function doWebUsers() {
		//这个操作被定义用来呈现 管理中心导航菜单
	}

}