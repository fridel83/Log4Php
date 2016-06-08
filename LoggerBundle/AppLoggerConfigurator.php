<?php
/**
 * Created by Idriss ELIGUENE <idriss.eliguene@gmail.com>.
 * User: idriss
 * Date: 05/06/2016
 * Time: 16:22
 */

namespace app\LoggerBundle;

use app\LoggerBundle\AppLogException;
use app\LoggerBundle\AppLoggerPatternConverterHostname;

use Logger;
use LoggerAppender;
use LoggerAppenderDailyFile;
use LoggerAppenderEcho;
use LoggerAppenderFile;
use LoggerAppenderMail;
use LoggerAppenderSyslog;
use LoggerConfigurator;
use LoggerFilterLevelRange;
use LoggerHierarchy;
use LoggerLayoutPattern;
use LoggerLevel;
use LoggerLoggingEvent;
use Symfony\Component\DependencyInjection\ContainerAware;


class AppLoggerConfigurator extends ContainerAware implements LoggerConfigurator
{
    /**
     * Constantes des noms des champs des lignes de log.
     */
    const APP_METHOD = 'method';
    const APP_STATUS = 'status';
    const APP_CODE = 'code';
    const APP_RESPONSE_TIME = 'responseTime';
    const APP_URL = 'url';
    const APP_ST = 'st';
    const APP_CORREL_ID = 'correlId';
    const APP_OPE_ID = 'opeId';
    const APP_MSG = 'msg';
    const PURGE_APPL = 'gpca-purge';
    const STATUT = 'metier';

    /**
     * Constantes des noms des appenders disponibles.
     */
    const DATE_ROLLING_APPENDER = 'dateRollingAppender';
    const DEBUG_DATE_ROLLING_APPENDER = 'debugDateRollingAppender';
    const ECHO_APPENDER = 'echoAppender';
    const LIGHT_ECHO_APPENDER = 'lightEchoAppender';
    const FILE_APPENDER = 'fileAppender';
    const DEBUG_FILE_APPENDER = 'debugFileAppender';
    const SYSLOG_APPENDER = 'sysLogAppender';
    private $_st;
    private $env;
    private $ContainerAware;

    /**
     * Tableau de configuration des appenders.
     */
    private $appenders = array(
        self::DATE_ROLLING_APPENDER			=> array('enabled' => false),
        self::DEBUG_DATE_ROLLING_APPENDER	=> array('enabled' => false),
        self::ECHO_APPENDER					=> array('enabled' => false),
        self::LIGHT_ECHO_APPENDER			=> array('enabled' => false),
        self::FILE_APPENDER					=> array('enabled' => false),
        self::DEBUG_FILE_APPENDER			=> array('enabled' => false),
        self::SYSLOG_APPENDER			    => array('enabled' => true)
    );

    public function __construct($section)
    {
        $this->env = 'dev';
        if(is_array($section))
            $this->configureSection($section);
    }

    public function configureSection($section)
    {
        $root_path = "";
        if(isset($section['root_path'])){
            $root_path = $section['root_path'];
        }

        foreach($this->appenders as $appender => $value){
            $appender_lower = strtolower($appender);
            if(isset($section["appenders"][$appender_lower])){
                $properties = $section["appenders"][$appender_lower];
                if(isset($properties['enabled'])){
                    if($properties['enabled'] == 'true'){
                        // activation de l'appender
                        $this->appenders[$appender]['enabled'] = true;

                        // threshold
                        if(isset($properties['threshold'])){
                            if($properties['threshold'] != '')
                                $this->appenders[$appender]['threshold'] = $properties['threshold'];
                        }

                        // verification des fichiers
                        if($appender == 'sysLogAppender' || $appender == 'fileAppender' || $appender == 'debugFileAppender'
                            || $appender == 'dateRollingAppender' || $appender == 'debugDateRollingAppender'){
                            if(!isset($properties['file']))
                                throw new AppLogException('NO_LOG_FILENAME_EXCEPTION' , null, " Le fichier de log n'est pas correctement configuer");
                            else if($properties['file'] == '')
                                throw new AppLogException('NO_LOG_FILENAME_EXCEPTION', null, " Le fichier de log n'est pas correctement configuer");

                            if(isset($properties['ident']))
                                $this->appenders[self::SYSLOG_APPENDER]['ident'] = $properties['ident'];
                            if(isset($properties['facility']))
                                $this->appenders[self::SYSLOG_APPENDER]['facility'] = $properties['facility'];
                            if(isset($properties['option']))
                                $this->appenders[self::SYSLOG_APPENDER]['priority'] = $properties['priority'];
                            if(isset($properties['option']))
                                $this->appenders[self::SYSLOG_APPENDER]['option'] = $properties['option'];
                            // les fichiers tournant doivent avoir %s dans leur chaine
                            if($appender == 'dateRollingAppender' || $appender == 'debugDateRollingAppender'){
                                if(!preg_match('/%s/', $properties['file']))
                                    throw new AppLogException('NO_REPLACEMENT_STRING_EXCEPTION', null, " Le paramètre ".$properties['file'] ." n'est pas correctement configuer");

                                // verifions qu'on puisse ecrire dans le repertoire
                                $fileDir = dirname($root_path.$properties['file']);
                                if (is_writable($fileDir))
                                    $this->appenders[$appender]['file'] = $root_path.$properties['file'];
                                else
                                    throw new AppLogException('NO_REPLACEMENT_STRING_EXCEPTION',null, " Le repertoire $fileDir est innacessible");

                                // recuperation du pattern des dates
                                if(isset($properties['datePattern']))
                                    if($properties['datePattern'] != '')
                                        $this->appenders[$appender]['datePattern'] = $properties['datePattern'];
                            }
                            else
                                $this->appenders[$appender]['file'] = $root_path.$properties['file'];
                        }
                        // filtres
                        // min level
                        if(isset($properties['filter.minLevel']))
                            if($properties['filter.minLevel'] != '')
                                if(!is_null(LoggerLevel::toLevel($properties['filter.minLevel'])))
                                    $this->appenders[$appender]['filter']['minLevel'] = $properties['filter.minLevel'];

                        // max level
                        if(isset($properties['filter.maxLevel']))
                            if($properties['filter.maxLevel'] != '')
                                if(!is_null(LoggerLevel::toLevel($properties['filter.maxLevel'])))
                                    $this->appenders[$appender]['filter']['maxLevel'] = $properties['filter.maxLevel'];
                    }
                }
            }
        }
    }
    


    /**
     * Configures log4php based on the given configuration.
     *
     * All configurators implementations must implement this interface.
     *
     * @param LoggerHierarchy $hierarchy The hierarchy on which to perform
     *        the configuration.
     * @param mixed $input Either path to the config file or the
     *        configuration as an array.
     */
    public function configure(LoggerHierarchy $hierarchy, $input = null)
    {
        $layout = new AppLoggerLayoutPattern();
        $layout->setConversionPattern("%date{Y-m-d H:i:s,u} | %hostname | %appProcess | %level | %appMethod | %appStatus | %appCode | %appResponseTime | %appUrl | %appST | %appCorrelId | %appOpeId | %msg%newline");

        $layout->activateOptions();
        
        // Creation du logger
        if($input != null && $input instanceof Logger)
            $Logger = $input;
        else
        {
            $Logger = $hierarchy->getRootLogger();
        }

        // Ajout des appender fichier
        if($this->appenders[self::DATE_ROLLING_APPENDER]['enabled'] == true)
            $Logger->addAppender($this->configureDailyFileAppender($layout, $this->appenders[self::DATE_ROLLING_APPENDER]));

        if($this->appenders[self::DEBUG_DATE_ROLLING_APPENDER]['enabled'] == true)
            $Logger->addAppender($this->configureDailyDebugFileAppender($layout, $this->appenders[self::DEBUG_DATE_ROLLING_APPENDER]));

        if($this->appenders[self::FILE_APPENDER]['enabled'] == true)
            $Logger->addAppender($this->configureFileAppender($layout, $this->appenders[self::FILE_APPENDER]));

        if($this->appenders[self::DEBUG_FILE_APPENDER]['enabled'] == true)
            $Logger->addAppender($this->configureDebugFileAppender($layout, $this->appenders[self::DEBUG_FILE_APPENDER]));

        // Ajout de l'appender terminal
        if($this->appenders[self::ECHO_APPENDER]['enabled'] == true)
            $Logger->addAppender($this->configureEchoAppender($layout, $this->appenders[self::ECHO_APPENDER]));

        // Ajout de l'appender simple terminal
        // Utilise un layout plus simple, mieux adapte au terminal
        if($this->appenders[self::LIGHT_ECHO_APPENDER]['enabled'] == true)
            $Logger->addAppender($this->configureLightEchoAppender($this->appenders[self::LIGHT_ECHO_APPENDER]));
        // Utilise un layout plus simple, mieux adapte au terminal
        if($this->appenders[self::SYSLOG_APPENDER]['enabled'] == true)
            $Logger->addAppender($this->configureSyslogAppender($this->appenders[self::SYSLOG_APPENDER]));
    }

    /**
     * configureDailyFileAppender
     * Configure un appender de fichier pour le logger.
     * La tranche des niveaux de log est INFO -> CRITICAL.
     * @param LoggerLayoutPattern $layout
     * 		Layout des logs a utiliser.
     * @param array $input
     * 		Tableau de configuration de l'appender.
     * @return LoggerAppenderDailyFile
     * 		Retourn l'appender cree.
     */
    private function configureDailyFileAppender(LoggerLayoutPattern $layout, array $input){
        $appender = new LoggerAppenderDailyFile(self::DATE_ROLLING_APPENDER);
        $appender->setLayout($layout);

        if(isset($input['datePattern']))
            $appender->setDatePattern($input['datePattern']);

        $appender->setFile($input['file']);
        $appender->setAppend(true);

        if(isset($input['threshold']))
            $appender->setThreshold($input['threshold']);
        else
            $appender->setThreshold('INFO');

        // conf de filtre dans tous les cas
        $filter = new LoggerFilterLevelRange();

        if(isset($input['filter'])){
            // min level
            if(isset($input['filter']['minLevel']))
                $filter->setLevelMin(LoggerLevel::toLevel($input['filter']['minLevel']));
            else
                $filter->setLevelMin(LoggerLevel::INFO);

            // max level
            if(isset($input['filter']['maxLevel']))
                $filter->setLevelMax(LoggerLevel::toLevel($input['filter']['maxLevel']));
            else
                $filter->setLevelMax(LoggerLevel::FATAL);
        }
        // conf par defaut pour le filtre
        else{
            $filter->setLevelMin(LoggerLevel::INFO);
            $filter->setLevelMax(LoggerLevel::FATAL);
        }

        $appender->addFilter($filter);

        $appender->activateOptions();

        return $appender;
    }

    /**
     * configureFileAppender
     * Configure un appender de fichier pour le logger.
     * La tranche des niveaux de log est INFO -> CRITICAL.
     * @param LoggerLayoutPattern $layout
     * 		Layout des logs a utiliser.
     * @param array $input
     * 		Tableau de configuration de l'appender.
     * @return LoggerAppenderFile
     * 		Retourn l'appender cree.
     */
    private function configureFileAppender(LoggerLayoutPattern $layout, array $input){
        $appender = new LoggerAppenderFile(self::FILE_APPENDER);
        $appender->setLayout($layout);
        $appender->setFile($input['file']);
        $appender->setAppend(true);

        if(isset($input['threshold']))
            $appender->setThreshold($input['threshold']);
        else
            $appender->setThreshold('INFO');

        // conf de filtre definie
        $filter = new LoggerFilterLevelRange();

        if(isset($input['filter'])){
            // min level
            if(isset($input['filter']['minLevel']))
                $filter->setLevelMin(LoggerLevel::toLevel($input['filter']['minLevel']));
            else
                $filter->setLevelMin(LoggerLevel::INFO);

            // max level
            if(isset($input['filter']['maxLevel']))
                $filter->setLevelMax(LoggerLevel::toLevel($input['filter']['maxLevel']));
            else
                $filter->setLevelMax(LoggerLevel::FATAL);
        }
        // conf par defaut pour le filtre
        else{
            $filter->setLevelMin(LoggerLevel::INFO);
            $filter->setLevelMax(LoggerLevel::FATAL);
        }

        $appender->addFilter($filter);

        $appender->activateOptions();

        return $appender;
    }

    /**
     * configureDailyDebugFileAppender
     * Configure un appender de fichier pour le logger avec le level DEBUG.
     * @param LoggerLayoutPattern $layout
     * 		Layout des logs a utiliser.
     * @param array $input
     * 		Tableau de configuration de l'appender.
     * @return LoggerAppenderDailyFile
     * 		Retourn l'appender cree.
     */
    private function configureDailyDebugFileAppender(LoggerLayoutPattern $layout, array $input){
        $appender = new LoggerAppenderDailyFile(self::DEBUG_DATE_ROLLING_APPENDER);
        $appender->setLayout($layout);

        if(isset($input['datePattern']))
            $appender->setDatePattern($input['datePattern']);

        $appender->setFile($input['file']);
        $appender->setAppend(true);

        if(isset($input['threshold']))
            $appender->setThreshold($input['threshold']);
        else
            $appender->setThreshold('ALL');

        // filtre defini
        if(isset($input['filter'])){
            $this->addFilter($appender, $input['filter']);
        }

        $appender->activateOptions();

        return $appender;
    }

    /**
     * configureDebugFileAppender
     * Configure un appender de fichier pour le logger avec le level DEBUG.
     * @param LoggerLayoutPattern $layout
     * 		Layout des logs a utiliser.
     * @param array $input
     * 		Tableau de configuration de l'appender.
     * @return LoggerAppenderFile
     * 		Retourn l'appender cree.
     */
    private function configureDebugFileAppender(LoggerLayoutPattern $layout, array $input){
        $appender = new LoggerAppenderFile(self::DEBUG_FILE_APPENDER);
        $appender->setLayout($layout);
        $appender->setFile($input['file']);
        $appender->setAppend(true);

        if(isset($input['threshold']))
            $appender->setThreshold($input['threshold']);
        else
            $appender->setThreshold('ALL');

        // filtre defini
        if(isset($input['filter'])){
            $this->addFilter($appender, $input['filter']);
        }

        $appender->activateOptions();

        return $appender;
    }

    /**
     * configureConsoleAppender
     * Configure un appender de terminal pour le logger.
     * Cet appender utilisera le format commun repondant aux exigences PROD.
     * @param LoggerLayoutPattern $layout
     * 		Layout des logs a utiliser.
     * @param array $input
     * 		Tableau de configuration de l'appender.
     * @return LoggerAppenderEcho
     * 		Retourn l'appender cree.
     */
    private function configureEchoAppender(LoggerLayoutPattern $layout, array $input){
        $appender = new LoggerAppenderEcho(self::ECHO_APPENDER);
        $appender->setLayout($layout);

        if(isset($input['threshold']))
            $appender->setThreshold($input['threshold']);
        else
            $appender->setThreshold('ALL');

        // filtre defini
        if(isset($input['filter'])){
            $this->addFilter($appender, $input['filter']);
        }

        $appender->activateOptions();

        return $appender;
    }
    /**
     * configureSyslogAppender
     *
     * Configure un appender syslog pour le logger.
     * Cet appender utilisera le format commun repondant aux exigences PROD.
     * @param LoggerLayoutPattern $layout  Layout des logs a utiliser.
     * @param array $input                 Tableau de configuration de l'appender.
     *
     * @return LoggerAppenderSyslog        Retourn l'appender cree.
     */
    private function configureSyslogAppender( array $input){
        $layout = new AppLoggerLayoutPattern();
        $appli = $this->_st;
        $type = self::STATUT;
        $layout->setConversionPattern("$this->env $appli $type %level %pid %M(%F:%L) %msg%newline");

        $appender = new LoggerAppenderSyslog(self::SYSLOG_APPENDER);
        $appender->setLayout($layout);
        if (isset($input["ident"]))
            $appender->setIdent($input["ident"]);
        if (isset($input["option"]))
            $appender->setOption($input["option"]);
        if (isset($input["priority"]))
            $appender->setPriority($input["priority"]);
        if (isset($input["facility"]))
            $appender->setFacility($input["facility"]);

        $appender->activateOptions();
        if(isset($input['threshold']))
            $appender->setThreshold($input['threshold']);
        else
            $appender->setThreshold('ALL');
        // filtre defini
        if(isset($input['filter'])){
            $this->addFilter($appender, $input['filter']);
        }
        $layout->activateOptions();
        //$logger = Logger::getLogger(__CLASS__);
        //$event = new LoggerLoggingEvent(__CLASS__, $logger, LoggerLevel::getLevelError(), );
        //$chemin = $this->env.' '.self::SAV_APPL.' '.strtolower($input["priority"]).' ';
        //$appender->append($event, $chemin);

        return $appender;
    }

    /**
     * configureSimpleConsoleAppender
     * Configure un appender de terminal pour le logger.
     * Cet appender possede un layout plus adapte a l'affichage
     * dans un terminal.
     * Les informations suivantes ne sont pas affichees dans le log :
     * - hostname
     * - process
     * - location
     * @param array $input
     * 		Tableau de configuration de l'appender.
     * @return LoggerAppenderEcho
     * 		Retourn l'appender cree.
     */
    private function configureLightEchoAppender(array $input){
        $layout = new AppLoggerLayoutPattern();
        $layout->setConversionPattern("%date{Y-m-d H:i:s,u} | %pid | %M(%F:%L) | %level | %msg%newline");
        $layout->activateOptions();

        $appender = new LoggerAppenderEcho(self::LIGHT_ECHO_APPENDER);
        $appender->setLayout($layout);

        if(isset($input['threshold']))
            $appender->setThreshold($input['threshold']);
        else
            $appender->setThreshold('ALL');

        // filtre defini
        if(isset($input['filter'])){
            $this->addFilter($appender, $input['filter']);
        }

        $appender->activateOptions();

        return $appender;
    }

    /**
     * configureMailAppender
     * Configure un appender de mail.
     * @param LoggerLayoutPattern $layout
     * 		Layout des logs a utiliser.
     * @param array $input
     * 		Tableau de configuration de l'appender.
     * @return LoggerAppenderMail
     * 		Retourn l'appender cree.
     */
    private function configureMailAppender(LoggerLayoutPattern $layout, array $input){
        $appMail = new LoggerAppenderMail('appMail');
        $appMail->setLayout($layout);
        $appMail->se1tThreshold($input['threshold']);
        $appMail->setFrom($input['from']);
        $appMail->setTo($input['to']);
        $appMail->setSubject($input['subject']);
        $appMail->activateOptions();

        return $appMail;
    }

    /**
     * addFilter
     * @param LoggerAppender $appender
     * 		Appender sur lequel appliquer le filtre.
     * @param array $filterConf
     * 		Tableau de configuration du filtre.
     */
    private function addFilter(LoggerAppender $appender, array $filterConf){
        $filter = new LoggerFilterLevelRange();

        // min level
        if(isset($filterConf['minLevel']))
            $filter->setLevelMin(LoggerLevel::toLevel($filterConf['minLevel']));

        // max level
        if(isset($filterConf['maxLevel']))
            $filter->setLevelMax(LoggerLevel::toLevel($filterConf['maxLevel']));

        $appender->addFilter($filter);
    }



}