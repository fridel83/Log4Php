<?php

/**
 * Created by PhpStorm.
 * User: idriss
 * Date: 12/06/2016
 * Time: 13:02
 */

namespace app\LoggerBundle\Event;

use app\LoggerBundle\Controller\LoggerController;

class ParamLogger
{
    static $paramLog = array(
        'code',
        'corellationId',
        'method',
        'userId',
        'process',
        'responseTime',
        'st',
        'status',
        'url',
        'hostname'
    );
    private $code = 'NA';
    private $corellationId = 'NA';
    private $method = 'NA';
    private $userId = 'NA';
    private $process = 'NA';
    private $responseTime = 'NA';
    private $st = 'NA';
    private $status = 'NA';
    private $url = 'NA';
    private $hostname = 'NA';
    private $msg = 'NA';
    private $logger;

    public function __construct(LoggerController $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return mixed
     */
    public function getCorellationId()
    {
        return $this->corellationId;
    }

    /**
     * @param mixed $corellationId
     */
    public function setCorellationId($corellationId)
    {
        $this->corellationId = $corellationId;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getProcess()
    {
        return $this->process;
    }

    /**
     * @param mixed $process
     */
    public function setProcess($process)
    {
        $this->process = $process;
    }

    /**
     * @return mixed
     */
    public function getResponseTime()
    {
        return $this->responseTime;
    }

    /**
     * @param mixed $responseTime
     */
    public function setResponseTime($responseTime)
    {
        $this->responseTime = $responseTime;
    }

    /**
     * @return mixed
     */
    public function getSt()
    {
        return $this->st;
    }

    /**
     * @param mixed $st
     */
    public function setSt($st)
    {
        $this->st = $st;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * @param mixed $hostname
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getMsg()
    {
        return $this->msg;
    }

    /**
     * @param mixed $msg
     */
    public function setMsg($msg)
    {
        $this->msg = $msg;
    }

    /**
     * @return mixed
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param mixed $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

}