<?php
/**
 * Created by Idriss ELIGUENE <idriss.eliguene@gmail.com>.
 * User: idriss
 *
 */
namespace app\LoggerBundle;


use LoggerLoggingEvent;
use LoggerPatternConverter;

final class AppLoggerPatternConverterAppResponseTime extends LoggerPatternConverter {
	public function convert(LoggerLoggingEvent $event) {
		return $event->getAppResponseTime();
	}
}

?>