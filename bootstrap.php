<?php
	ini_set('display_errors', 'Off');
	error_reporting(0);
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false ) {
		header('HTTP/1.1 403 Forbidden');
		exit('HTTP/1.1 403 Forbidden');
	}

	define('DS', DIRECTORY_SEPARATOR);
	define('WWW_ROOT', __DIR__ . DS);
	// 上传路径
	define('SAVE_PATH', sprintf('/uploads/%s/%s/%s/', date('Y'), date('m'), date('d')));
	define('UPLOAD_PATH', WWW_ROOT . SAVE_PATH);
	// 上传文件限制 5M
	define('UPLOAD_SIZE', 5 * 1024 * 1024);

	// 缩略图像素
	define('IMAGE_WIDTH', 640);
	define('IMAGE_HEIGHT', 640);

	require WWW_ROOT . 'vendor' . DS . 'autoload.php';
	// 异常处理
	function onError($errCode, $errMesg, $errFile, $errLine) {
		$error = $errCode . "\r\n";
		$error .= $errFile . ' --->>line:' . $errLine . "\r\n";
		$error .= $errMesg;
		wlog($error, 'error');
	}
	set_error_handler("onError");
	function wlog($obj, $type = 'debug') {
		$file = WWW_ROOT . 'data' . DS . 'logs' .DS . sprintf('%s.log', $type);
		file_put_contents($file, date('Y-m-d H:i:s') . "\r\n", FILE_APPEND);
		file_put_contents($file, var_export($obj, true), FILE_APPEND);
		file_put_contents($file, "\r\n", FILE_APPEND);
	}

	// 数据库连接
	try {
		dibi::connect(
			array(
				'driver' => 'mysql',
				'host' => '127.0.0.1',
				'username' => 'root',
				'password' => '',
				'database' => 'test'
			)
		);
	} catch (DibiException $e) {
		wlog($e->getMessage());
		exit('server error');
	}
