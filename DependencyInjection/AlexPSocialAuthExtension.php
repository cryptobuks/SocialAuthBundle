<?php

namespace AlexP\SocialAuthBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * 
 */
class AlexPSocialAuthExtension extends Extension {

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container) {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
        
        $container->setParameter('alex_p_social_auth.enabled', $config['enabled']);
        
        $types = ['vk', 'facebook', 'google_plus', 'linkedin', 'odnoklassniki', 'mailru', 'yandex'];
        $fields = ['client_id', 'secret_key', 'return_url'];
        
        foreach($types as $type) {
            foreach($fields as $field) {
                if(!empty($config[$type]) && !empty($config[$type][$field])) {
                    $container->setParameter('alex_p_social_auth.' . $type . '.' . $field, $config[$type][$field]);
                }
            }
        }
    }

}
