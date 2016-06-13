<?php
/**
 * Created by Idriss ELIGUENE <idriss.eliguene@gmail.com>.
 * User: idriss
 * Date: 05/06/2016
 * Time: 18:34
 */

namespace app\LoggerBundle;


use Exception;

class AppLogException extends \Exception
{
    /**
     * Tableau des exceptions :
     * 		Nom de l'exception => [code, message]
     * @var array $exceptions
     */
    public static $exceptions = array(
        'UNKNOWN_EXCEPTION' => array(1, "Exception non definie"),
        'INIT_EXCEPTION' => array(10, "Impossible d'initialiser le logger, une instance a deja ete creee"),
        'INIT_FIRST_EXCEPTION' => array(11, "Appeler la fonction init avant de recuperer une instance de Log"),
        'NO_PROCESS_NAME_EXCEPTION' => array(20, "Nom du process non renseigne"),
        'NO_LOG_FILENAME_EXCEPTION' => array(30, "Aucun fichier de log de sortie renseigne"),
        'LOG_NOT_STARTED_EXCEPTION' => array(40, "Appeler la fonction logStart avant d'utiliser le logger"),
        'NO_REPLACEMENT_STRING_EXCEPTION' => array(50, "Le fichier de log doit contenir '%s' dans son nom"),
        'NO_INI_CONFIGURATION_EXCEPTION' => array(60, "Le fichier ini et la section a analyser ne sont pas correctement definis"),
        'NO_CONFIGURATION_EXCEPTION' => array(70, "Aucune configuration disponible pour le logger"),
        'DIRECTORY_NOT_WRITABLE' => array(80, "Le r?pertoire de destination n'est pas accessible en ?criture")
    );



    /* (non-PHPdoc)
    * @see Exception::__construct()
    */
    public function __construct($event, Exception $previous = null, $messageIn =''){
        if(!isset(self::$exceptions[$event]))
            $event = 'UNKNOWN_EXCEPTION';

        $message = self::$exceptions[$event][1];
        $code =  self::$exceptions[$event][0];
        if($messageIn !='')
            $message .= $messageIn;
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}";
    }
}