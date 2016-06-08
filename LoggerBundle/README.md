Log4PhpBundle
This is an  Log4Php Bundle ofr your fonctionnal logs in your Symfony2-Project.


Installation

 add Log4php in your composer.json

 "require": {
        "php": ">=5.3.9",
        "symfony/symfony": "2.7.*",
        "doctrine/orm": "^2.4.8",
        "doctrine/doctrine-bundle": "~1.4",
        "symfony/assetic-bundle": "~2.3",
        "symfony/swiftmailer-bundle": "~2.3",
        "symfony/monolog-bundle": "~2.4",
        "sensio/distribution-bundle": "~4.0",
        "sensio/framework-extra-bundle": "^3.0.2",
        "incenteev/composer-parameter-handler": "~2.0",
        "nmalo/fos-user-bundle" : "2.0.0",
        "apache/log4php": "2.3.0"
    },

    add log4php namespace in the same file

    "autoload": {
            "psr-4": {
                "": "src/",
                "Log4php\\": "vendor/apache/log4php/src/main/php/"
            }

 install this componement with the composer.phar insatll


Register bundle in AppKernel.php

# app/AppKernel.php

$bundles = array(
    // ...
    new app\LoggerBundle\appLoggerBundle(),
    // ...
);
Add Bundle to autoload

# app/autoload.php

$loader->registerNamespaces(array(
    // ...
    'LOGGER' => __DIR__.'/../vendor/bundles',
    // ...
));

AnnotationRegistry::registerFile(__DIR__.'/../vendor/log4php/src/main/php/Logger.php');
Usage
Use Log4Php without Symfony2 see Log4php Quickstart

app/config.yml

parameters:
    locale:fr 
    logger_dir_path: app

app_logger:
    loggers:
        app:
            root_path: "%kernel.logs_dir%/%logger_dir_path%/"
            appenders:
                dateRollingAppender :
                    enabled: "true"
                    file : logger_app_%s.log
                    datePattern : Y-m-d
                debugDateRollingAppender :
                    enabled: "true"
                    file : debug_logger_app_%s.log
                    datePattern : Y-m-d

create the app repository inside app/log/ dir in the main project directory



If the logger is not found, the root logger will be used. How to setup your own logger, take a look at the cookbook or the documentation of Log4Php.