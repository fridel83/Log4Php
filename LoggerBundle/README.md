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
    new FS\Log4PhpBundle\FSLog4PhpBundle(),
    // ...
);
Add Bundle to autoload

# app/autoload.php

$loader->registerNamespaces(array(
    // ...
    'FS' => __DIR__.'/../vendor/bundles',
    // ...
));

AnnotationRegistry::registerFile(__DIR__.'/../vendor/log4php/src/main/php/Logger.php');
Usage
Use Log4Php without Symfony2 see Log4php Quickstart

app/config.yml

fs_log4_php:
  viewer:
    view_all_logs: false
    log_files: [ %kernel.root_dir%/logs/info.log, %kernel.root_dir%/logs/debug.log ]
  appenders:
    default:
      class: LoggerAppenderFile
      layout:
        class: LoggerLayoutPattern
      params:
        file: %kernel.root_dir%/logs/default.log
  rootLogger:
    level: DEBUG
    appenders: [ default ]
Sample config for a simple file-logger. Config for other logger-types see the wiki.

Symfony-Profiler
The Bundle has it's own part in the profiler. This section is named "All Logs".

In the YAML-configuration you can configure which log-files should be read. There are two options:

- view_all_logs: [true|false] the datacollector reads all log-file in the default log-directory.
- log_files: array if view_all_logs is true, this array will be overwritten, otherwise this array contains
the log-file which should be read.
Costum Appenders
You can register your own services as an appender.

    <service id="myapp" class="Acme\DemoBundle\MyService">
        <tag name="logger.appender" id="costum.appender" />
    </service>
The service you want to register, needs the tag logger.appender and the option id. The option id represent the name of your appender. If you want to register the appender for a concrete logger (excepting root-logger), you can add the option logger.

    <service id="myapp" class="Acme\DemoBundle\MyService">
        <tag name="logger.appender" id="costum.appender" logger="my.logger" />
    </service>
Your Service has to extends the \LoggerAppender class from Log4Php.

If the logger is not found, the root logger will be used. How to setup your own logger, take a look at the cookbook or the documentation of Log4Php.