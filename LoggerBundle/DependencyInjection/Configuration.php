<?php

namespace app\LoggerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ArrayNode;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\ScalarNode;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('app_logger');

        $rootNode
            ->children()
                 ->arrayNode('loggers')
                    ->children()
                      ->arrayNode('app')
                        ->children()
                           ->scalarNode('root_path')->end()
                           ->arrayNode('appenders')
                               ->children()
                                  ->arrayNode('dateRollingAppender')
                                    ->children()
                                        ->scalarNode('enabled')->isRequired()->cannotBeEmpty()->end()
                                        ->scalarNode('file')->isRequired()->cannotBeEmpty()->end()
                                        ->scalarNode('datePattern')->isRequired()->cannotBeEmpty()->end()
                                     ->end()
                                  ->end()
                                    ->arrayNode('debugDateRollingAppender')
                                    ->children()
                                    ->scalarNode('enabled')->isRequired()->cannotBeEmpty()->end()
                                    ->scalarNode('file')->isRequired()->cannotBeEmpty()->end()
                                    ->scalarNode('datePattern')->isRequired()->cannotBeEmpty()->end()
                                    ->end()
                                    ->end()
                           ->end()
                         ->end()
                       ->end()
            ->end()
        ;

        return $treeBuilder;
    }
    
    
    public function getTreeBuilderArray()
    {
        $value = Yaml::parse(file_get_contents(__DIR__.'/../../../app/config/config.yml'));
        if(isset($value["app_logger"]) && isset($value["app_logger"]["loggers"]));
        $conf = $value["app_logger"];
        return $conf;
    }


}
