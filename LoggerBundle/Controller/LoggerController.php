<?php

namespace app\LoggerBundle\Controller;

use app\LoggerBundle\AppLoggerConfigurator;
use app\LoggerBundle\AppLoggerLoggingEvent;
use Logger;
use LoggerLevel;
use LoggerLoggingEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\DependencyInjection\ContainerAwareHttpKernel;
use Symfony\Component\Yaml\Yaml;


class LoggerController extends Controller
{
   
    private $logger;

    private $mycontainer;

    /**
     * Identifiant de corrélation du logger
     * @var correlationId $correlationId
     */
    private $correlationId = null;

    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }


    public function __construct(  $http_kernel)
    {
        $this->mycontainer = $http_kernel->getContainer();
        $this->correlationId = uniqid("", true);
        $logger = Logger::getHierarchy()->getLogger('app');
        $params=$this->getLoggerParam();
        $configurator = new AppLoggerConfigurator($params["app"]);
        $configurator->configure(Logger::getHierarchy(), $logger);
        $this->logger = Logger::getHierarchy()->getLogger('app');
    }


    private function getLoggerParam()
    {
        $param_root_dir = $this->mycontainer->getParameter("kernel.root_dir");
        $value = Yaml::parse(file_get_contents($param_root_dir.'/config/config.yml'));
        if(isset($value["app_logger"]) && isset($value["app_logger"]["loggers"]));
        $conf = $value["app_logger"]["loggers"];
        return $conf;
    }

    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * start
     * Insere une ligne de log de debut de process.
     */
    public function start(){
        $this->logger->info("Debut du process");
    }

    /**
     * trace
     * Insere un evenement de niveau TRACE.
     * @param array $data
     * 		Tableau des elements de l'evenement.
     */
    public function trace(array $data){
        $this->log(LoggerLevel::getLevelTrace(), $data);
    }

    /**
     * debug
     * Insere un evenement de niveau DEBUG.
     * @param array $data
     * 		Tableau des elements de l'evenement.
     */
    public function debug(array $data){
        $this->log(LoggerLevel::getLevelDebug(), $data);
    }

    /**
     * info
     * Insere un evenement de niveau INFO.
     * @param array $data
     * 		Tableau des elements de l'evenement.
     */
    public function info(array $data){
        $this->log(LoggerLevel::getLevelInfo(), $data);
    }

    /**
     * warn
     * Insere un evenement de niveau WARN.
     * @param array $data
     * 		Tableau des elements de l'evenement.
     */
    public function warn(array $data){
        $this->log(LoggerLevel::getLevelWarn(), $data);
    }

    /**
     * error
     * Insere un evenement de niveau ERROR.
     * @param array $data
     * 		Tableau des elements de l'evenement.
     */
    public function error(array $data){
        $this->log(LoggerLevel::getLevelError(), $data);
    }

    /**
     * critical
     * Insere un evenement de niveau CRITICAL.
     * @param array $data
     * 		Tableau des elements de l'evenement.
     */
    public function critical(array $data){
        $this->log(LoggerLevel::getLevelCritical(), $data);
    }

    /**
     * fatal
     * Insere un evenement de niveau FATAL.
     * @param array $data
     * 		Tableau des elements de l'evenement.
     */
    public function fatal(array $data){
        $this->log(LoggerLevel::getLevelFatal(), $data);
    }

    /**
     * log
     * Construit l'evenement a logger et remplace les retours
     * chariots et tabulations.
     * @param LoggerLevel $level
     * 		Niveau de log de l'evenement.
     * @param array $data
     * 		Tableau des elements de l'evenement.
     **/
    private function log(LoggerLevel $level, array $data)
    {
        //unitcité du format de date (FR et ALL)
        if(isset($data['responseTime']))
        {
            $chaine = explode(',', $data['responseTime']);
            if(is_array($chaine) && count($chaine) > 0 )
            {
                $data['responseTime'] = $chaine[0].'.'.$chaine[1];
            }
        }
        $data["correlId"] = $this->correlationId;
        $message = $data[AppLoggerConfigurator::APP_MSG];
        $event = new AppLoggerLoggingEvent('Logger', $this->logger, $level, $message, null, null);
        $event->setAppProperties($data);
        $this->logger->logEvent($event);
    }

    /**
     * @return mixed
     */
    public function getMycontainer()
    {
        return $this->mycontainer;
    }

    /**
     * @param mixed $mycontainer
     */
    public function setMycontainer($mycontainer)
    {
        $this->mycontainer = $mycontainer;
    }


}

