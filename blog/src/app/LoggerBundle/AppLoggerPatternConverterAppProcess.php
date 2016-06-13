<?php
/**
 * Created by Idriss ELIGUENE <idriss.eliguene@gmail.com>.
 * User: idriss
 *
 */


namespace app\LoggerBundle;

use Log4php\LoggerLoggingEvent;
use Log4php\LoggerPatternConverter;

final class AppLoggerPatternConverterAppProcess extends LoggerPatternConverter {
	public function convert(LoggerLoggingEvent $event) {
		$data = '';

		$process = $event->getAppProcess();
		if($process != 'NA')
			$data .= $process.' |'.getmypid(). ' ';
		else
			$data .= $event->getLocationInformation()->getFileName().' | '.getmypid(). ' ';

		$sessId = session_id();
		if($sessId != '')
			$data .= '['.$sessId. '] ';

		return $data;
	}
}

?>