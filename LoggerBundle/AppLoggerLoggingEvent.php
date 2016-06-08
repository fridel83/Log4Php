<?php
/**
 * Created by Idriss ELIGUENE <idriss.eliguene@gmail.com>.
 * User: idriss
 *
 */

namespace app\LoggerBundle;


use LoggerLoggingEvent;

class AppLoggerLoggingEvent extends LoggerLoggingEvent
{
    private $process = 'NA';
    private $method = 'NA';
    private $status = 'NA';
    private $code = 'NA';
    private $responseTime = 'NA';
    private $url = 'NA';
    private $st = 'NA';
    private $correlId = 'NA';
    private $opeId = 'NA';

    public function setAppProperties(array $properties){
        if(isset($properties['process']))
            $this->process = $properties['process'];

        if(isset($properties['method']))
            $this->method = $properties['method'];

        if(isset($properties['status']))
            $this->status = $properties['status'];

        if(isset($properties['code']))
            $this->code = $properties['code'];

        if(isset($properties['responseTime']))
            $this->responseTime = $properties['responseTime'];

        if(isset($properties['url']))
            $this->url = $properties['url'];

        if(isset($properties['st']))
            $this->st = $properties['st'];

        if(isset($properties['correlId']))
            $this->correlId = $properties['correlId'];

        if(isset($properties['opeId']))
            $this->opeId = $properties['opeId'];
    }

    public function getAppProcess(){
        return $this->process;
    }

    public function getAppMethod(){
        return $this->method;
    }

    public function getAppStatus(){
        return $this->status;
    }

    public function getAppCode(){
        return $this->code;
    }

    public function getAppResponseTime(){
        return $this->responseTime;
    }

    public function getAppUrl(){
        return $this->url;
    }

    public function getAppST(){
        return $this->st;
    }

    public function getAppCorrelId(){
        return $this->correlId;
    }

    public function getAppOpeId(){
        return $this->opeId;
    }

}