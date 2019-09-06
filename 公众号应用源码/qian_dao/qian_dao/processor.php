<?php
/**
 * 小红书图片模块处理程序
 *
 * @author Mob624688285204
 * @url http://bbs.we7.cc
 */
defined('IN_IA') or exit('Access Denied');
require_once(dirname(__FILE__)."/site.php");
class Zhe_jiangModuleProcessor extends WeModuleProcessor {
	
	public function respond() {
			
			$content = $this->message['content'];
			$index=new Zhe_jiangModuleSite();
			$index->doMobileIndex($content,$this->message['from']);
			// $fh= file_get_contents($content);
			// preg_match_all('/,"url":"([\s\S]{10,150}?ss1\.jpg)","width"/', $fh, $mat);
			// for($i=0;$i<count($mat[1]);$i++){
			// 	$mat[1][$i]=str_replace('\u002F', '/', $mat[1][$i]);
			// }
			// $arr=$mat[1];
			// $arr=array_merge(array_unique($arr));
			// for ($i=0; $i<count($arr); $i++){
				
			// 	$img_file = file_get_contents('http:'.$arr[$i]);
			// 	file_put_contents(IA_ROOT.'/attachment/'.$i.'.jpg', $img_file);	

			// 	$account_api = WeAccount::create();
			// 	$result = $account_api->uploadMedia(IA_ROOT.'/attachment/'.$i.'.jpg', 'image');
			// 	// sleep(5);
			// 	if($result){
			// 		$total[$i]=$result['media_id'];
			// 	}
				
				    
			// }
			// $account_api2 = WeAccount::create();
			// 	$message = array(
			// 		'touser' => $this->createMoblieUrl('index'),
			// 		'msgtype' => 'text',
			// 		'text' => array('content' => $this->message['from'])
			// 		);
				
			// 	$status = $account_api2->sendCustomNotice($message);
			//return $this->respText($total[1]);


			// for ($i=0; $i<count($arr); $i++){
				
			// 	$img_file = file_get_contents('http:'.$arr[$i]);
			// 	file_put_contents(IA_ROOT.'/attachment/'.$i.'.jpg', $img_file);	

			// 	$account_api = WeAccount::create();
			// 	$result = $account_api->uploadMedia(IA_ROOT.'/attachment/'.$i.'.jpg', 'image');
			// 	sleep(5);
			// 	$account_api2 = WeAccount::create();
			// 	$message = array(
			// 		'touser' => $this->message['from'],
			// 		'msgtype' => 'image',
			// 		'image' => array('media_id' =>$result['media_id']) //微信素材media_id，微擎中微信上传组件可以得到此值
			// 	);
				
			// 		$status= $account_api2->sendCustomNotice($message);
			// 		 if (is_error($status)) {
			// 		    	message('发送失败，原因为' . $status['message']);
			// 		    }else{
					    	
			// 				 continue;
			// 		    }
			// 		sleep(5);
				
				    
			// }
				


			
			
	}

	
}