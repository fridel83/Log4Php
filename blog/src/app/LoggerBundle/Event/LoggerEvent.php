<?php

namespace app\LoggerBundle\Event;

use app\LoggerBundle\Controller\LoggerController;
use Symfony\Component\EventDispatcher\Event;

/**
 * Created by PhpStorm.
 * User: idriss
 * Date: 12/06/2016
 * Time: 12:19
 */
class LoggerEvent extends Event
{
    const NAME = 'app_logger.logger';
    const DEBUG = 'debug';
    const INFO = 'info';
    const WARN = 'warn';

    private $loggerParam;
    private $logger;
    private $level;
    private $arrayLog;



    public function __construct(ParamLogger $loggerParam)
    {
        $this->loggerParam = $loggerParam;
    }

    public function getLogger()
    {
        return $this->loggerParam->getLogger();
    }

    /**
     * @return ParamLogger
     */
    public function getLoggerParam()
    {
        return $this->loggerParam;
    }

    /**
     * @param ParamLogger $loggerParam
     */
    public function setLoggerParam($loggerParam)
    {
        $this->loggerParam = $loggerParam;
    }

    /**
     * @param mixed $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }
    
    public function initLoggerParams()
    {
        $return = array(
            'code' => $this->getLoggerParam()->getCode(),
            'corellationId' => $this->getLoggerParam()->getCorellationId(),
            'method' => $this->getLoggerParam()->getMethod(),
            'userId'=> $this->getLoggerParam()->getUserId(),
            'process'=> $this->getLoggerParam()->getProcess(),
            'responseTime'=> $this->getLoggerParam()->getResponseTime(),
            'st'=> $this->getLoggerParam()->getSt(),
            'status'=> $this->getLoggerParam()->getStatus(),
            'url'=> $this->getLoggerParam()->getUrl(),
            'hostname'=> $this->getLoggerParam()->getHostname(),
            'msg'=>$this->getLoggerParam()->getMsg()
        );
        $this->arrayLog = $return;
        
    }

    /**
     * @return mixed
     */
    public function getArrayLog()
    {
        return $this->arrayLog;
    }

    /**
     * @param mixed $arrayLog
     */
    public function setArrayLog($arrayLog)
    {
        $this->arrayLog = $arrayLog;
    }
}