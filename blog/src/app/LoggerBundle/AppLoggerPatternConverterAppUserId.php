<?php
/**
 * Created by Idriss ELIGUENE <idriss.eliguene@gmail.com>.
 * User: idriss
 *
 */
namespace app\LoggerBundle;


use Log4php\LoggerLoggingEvent;
use Log4php\LoggerPatternConverter;

final class AppLoggerPatternConverterAppUserId extends LoggerPatternConverter {
	public function convert(LoggerLoggingEvent $event) {
		return $event->getAppOpeId();
	}
}

?>