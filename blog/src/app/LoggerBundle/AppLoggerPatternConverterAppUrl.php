<?php
namespace app\LoggerBundle;

/**
 * Created by Idriss ELIGUENE <idriss.eliguene@gmail.com>.
 * User: idriss
 *
 */

use LoggerLoggingEvent;
use LoggerPatternConverter;

final class AppLoggerPatternConverterAppUrl extends LoggerPatternConverter {
	public function convert(LoggerLoggingEvent $event) {
		return $event->getAppUrl();
	}
}

?>