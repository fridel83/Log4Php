<?php
/**
 * Created by Idriss ELIGUENE <idriss.eliguene@gmail.com>.
 * User: idriss
 *
 */
namespace app\LoggerBundle;


use LoggerLayoutPattern;

final class AppLoggerLayoutPattern extends LoggerLayoutPattern {
	public function __construct() {
		parent::__construct();
		$this->converterMap['hostname'] = __NAMESPACE__.'\AppLoggerPatternConverterHostname';
		$this->converterMap['appProcess'] = __NAMESPACE__.'\AppLoggerPatternConverterAppProcess';
		$this->converterMap['appMethod'] = __NAMESPACE__.'\AppLoggerPatternConverterAppMethod';
		$this->converterMap['appStatus'] = __NAMESPACE__.'\AppLoggerPatternConverterAppStatus';
		$this->converterMap['appCode'] = __NAMESPACE__.'\AppLoggerPatternConverterAppCode';
		$this->converterMap['appResponseTime'] = __NAMESPACE__.'\AppLoggerPatternConverterAppResponseTime';
		$this->converterMap['appUrl'] = __NAMESPACE__.'\AppLoggerPatternConverterAppUrl';
		$this->converterMap['appST'] = __NAMESPACE__.'\AppLoggerPatternConverterAppST';
		$this->converterMap['appCorrelId'] = __NAMESPACE__.'\AppLoggerPatternConverterAppCorrelId';
		$this->converterMap['appUserId'] = __NAMESPACE__.'\AppLoggerPatternConverterAppUserId';
	}
}

?>