<?php

namespace AlexP\SocialAuthBundle\OAuth;

use AlexP\SocialAuthBundle\lib\SocialInterace as SocialInterace;

class Instagram extends OAuth implements SocialInterace {
    
    const AUTH_URL = 'https://instagram.com/oauth/authorize';
    const TOKEN_URL = 'https://api.instagram.com/oauth/access_token';

    private $client_id = '';
    private $secret_key = '';
    private $return_url = '';
    
    protected $container = null;
    
    public function __construct($container) { 
        $this->container = $container;
        
        $this->client_id = $this->container->getParameter('alex_p_social_auth.instagram.client_id');
        $this->secret_key = $this->container->getParameter('alex_p_social_auth.instagram.secret_key');
        $this->return_url = $this->container->getParameter('alex_p_social_auth.instagram.return_url');
    }
    
    /**
     * Generate url for login with instagram
     * 
     * @return string
     */
    public function getUrl() {
        $params = array(
            'client_id'     => $this->client_id,
            'redirect_uri'  => $this->return_url,
            'response_type' => 'code'
        );
        
        $url = $this->__getUrl(self::AUTH_URL, $params);
        
        return $url;
    }
    
    /**
     * Get instagram data
     * 
     * @param string $code
     * @return array
     */
    public function getData($code) {
        $params = array(
            'client_id'     => $this->client_id,
            'redirect_uri'  => $this->return_url,
            'client_secret' => $this->secret_key,
            'code'          => $code,
            'grant_type'    => 'authorization_code'
        );

        $getContent = $this->sendPost(self::TOKEN_URL, $params);
        
        if(!empty($getContent) && isset($getContent['user'])) {
            return $getContent['user'];
        }
    }
    
}