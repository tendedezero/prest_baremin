<?php
/**
  * You are allowed to use this API in your web application.
 *
 * Copyright (C) 2018 by customweb GmbH
 *
 * This program is licenced under the customweb software licence. With the
 * purchase or the installation of the software in your application you
 * accept the licence agreement. The allowed usage is outlined in the
 * customweb software licence which can be found under
 * http://www.sellxed.com/en/software-license-agreement
 *
 * Any modification or distribution is strictly forbidden. The license
 * grants you the installation in one application. For multiuse you will need
 * to purchase further licences at http://www.sellxed.com/shop.
 *
 * See the customweb software licence agreement for more details.
 *
 */

require_once 'Customweb/Core/Logger/IListener.php';
require_once 'Customweb/Core/ILogger.php';


class SagePayCw_LoggingListener implements Customweb_Core_Logger_IListener {
	
	private static $levelMap = array(
		'error' => 3, //PrestashopLogger Level Error
		'info' => 1, //PrestashopLogger Level Informative only
		'debug' => 1, //PrestashopLogger Level Informative only, as debug not available
		
	);

	public function addLogEntry($loggerName, $level, $message, Exception $e = null, $object = null){
		if(!$this->isLevelActive($level)){
			return;
		}

		$content = '[' . $level . '] ' . $loggerName . ': ' . $message;

		if ($e !== null) {
			$content .= "\n";
			$content .= $e->getMessage();
			$content .= "\n";
			$content .= $e->getTraceAsString();
		}

		if ($object !== null) {
			ob_start();
			var_dump($object);
			$content .= "\n";
			$content .= ob_get_contents();
			ob_end_clean();
		}

		PrestaShopLogger::addLog($content, self::$levelMap[$level]);
	}

	private function isLevelActive($level){
		switch (SagePayCw::getInstance()->getConfigurationValue('log_level')) {
			case 'debug':
				return true;
			case 'info':
				if ($level == Customweb_Core_ILogger::LEVEL_DEBUG) {
					return false;
				}
				return true;
			case 'error':
				if ($level == Customweb_Core_ILogger::LEVEL_ERROR) {
					return true;
				}
				return false;
			default:
				return false;
		}
	}

}