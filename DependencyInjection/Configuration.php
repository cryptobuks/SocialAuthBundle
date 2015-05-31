<?php

namespace AlexP\SocialAuthBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface {

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder() {
        $treeBuilder = new TreeBuilder();
        
        $types = ['vk', 'facebook', 'google_plus', 'linkedin', 'odnoklassniki'];
        
        $rootNode = $treeBuilder->root('alex_p_social_auth');
        
        $rootNode
                ->children()
                    ->arrayNode('enabled')
                        ->prototype('scalar')
                        ->isRequired()
                    ->end()
                ->end();
        
        foreach($types as $type) {
            $rootNode
                ->children()
                    ->arrayNode($type)
                        ->children()
                            ->scalarNode('client_id')->defaultNull()->end()
                            ->scalarNode('secret_key')->defaultNull()->end()
                            ->scalarNode('return_url')->defaultNull()->end()
                        ->end()
                    ->end()
                ->end();
        }

        return $treeBuilder;
    }

}
