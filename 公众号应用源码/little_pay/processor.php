<?php
/**
 * 微课程模块处理程序
 *
 * @author bendilaosiji
 * @url http://minbang.bendilaosiji.com/
 */
defined('IN_IA') or exit('Access Denied');

class Little_payModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$content = $this->message['content'];
		//这里定义此模块进行消息处理时的具体过程, 请查看微擎文档来编写你的代码
	}
}