<?php
namespace app\LoggerBundle;

/**
 * Created by Idriss ELIGUENE <idriss.eliguene@gmail.com>.
 * User: idriss
 *
 */

use Log4php\LoggerLoggingEvent;
use Log4php\LoggerPatternConverter;

final class AppLoggerPatternConverterAppUrl extends LoggerPatternConverter {
	public function convert(LoggerLoggingEvent $event) {
		return $event->getAppUrl();
	}
}

?>