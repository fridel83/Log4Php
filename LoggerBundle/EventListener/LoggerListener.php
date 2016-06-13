<?php
namespace app\LoggerBundle\EventListener;
use app\LoggerBundle\Event\LoggerEvent;

/**
 * Created by PhpStorm.
 * User: idriss
 * Date: 12/06/2016
 * Time: 13:19
 */
class LoggerListener
{
    public function onLoggerEvent(LoggerEvent $event)
    {
        var_dump($event);
        $logger = $event->getLogger();
        $paramToLog = $event->getArrayLog();
        $level=$event->getLevel();
        switch ($level)
        {
            case LoggerEvent::DEBUG :
                $logger->debub($paramToLog);
                break;
            case LoggerEvent::INFO :
                $logger->info($paramToLog);
                break;
            case LoggerEvent::WARN :
                $logger->warn($paramToLog);
                break;
        }
        $event->stopPropagation();
    }
}