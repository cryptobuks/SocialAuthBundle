<?php

namespace AlexP\SocialAuthBundle\lib;

use \Symfony\Component\DependencyInjection\Container;
use AlexP\SocialAuthBundle\OAuth as OAuth;

/**
 * Social Media Authentication
 * 
 * @author Alex Pass <alexey.pass@gmail.com>
 */
class SocialAuth {
    
    public $vk;
    public $facebook;
    public $googlePlus;
    public $linkedin;
    public $odnoklassniki;
    public $mailru;
    
    protected $container = null;
    
    public function __construct(Container $container) { 
        $this->container = $container;
        
        $enabled = $container->getParameter('alex_p_social_auth.enabled');
        
        if(in_array('vk', $enabled)) $this->vk = new OAuth\Vk($container);
        if(in_array('facebook', $enabled)) $this->facebook = new OAuth\Facebook($container);
        if(in_array('google_plus', $enabled)) $this->googlePlus = new OAuth\GooglePlus($container);
        if(in_array('linkedin', $enabled)) $this->linkedin = new OAuth\Linkedin($container);
        if(in_array('odnoklassniki', $enabled)) $this->odnoklassniki = new OAuth\Odnoklassniki($container);
        if(in_array('mailru', $enabled)) $this->mailru = new OAuth\MailRu($container);
    }


}
?>