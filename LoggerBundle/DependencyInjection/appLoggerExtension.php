<?php

namespace app\LoggerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class appLoggerExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if(isset($config['loggers']) && is_array($config['loggers']))
        {
            foreach ($config['loggers'] as $key => $value)
            {
                if (is_array($value) && count($value) > 0)
                {
                    if(isset($value['root_path']) && $value['root_path'] != "")
                    {
                        $container->setParameter('app_logger.'.$key.'.root_path', $value['root_path']);
                    }
                    if(is_array($value['appenders']) && count($value['appenders']) > 0)
                    {
                        foreach ($value['appenders'] as $cle => $valeur)
                        {
                            $container->setParameter('app_logger.'.$key.'.appenders.'.$cle, $valeur);
                        }
                    }
                }

            }
        }

        //$container->setParameter('app_logger.path', $config['logger_dir']['path']);
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

    }

    /*
    static function getLoggerConf(ContainerBuilder $container)
    {
        $file=$container->getParameter('kernel.root_dir');
        $value = Yaml::parse(file_get_contents($file.'/config/config.yml'));
        var_dump($value);
    }
    */
}
