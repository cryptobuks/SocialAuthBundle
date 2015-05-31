<?php

namespace AlexP\SocialAuthBundle\OAuth;

use AlexP\SocialAuthBundle\lib\SocialInterace as SocialInterace;

class Vk extends OAuth implements SocialInterace {
    
    const AUTH_URL = 'http://oauth.vk.com/authorize';
    const TOKEN_URL = 'https://oauth.vk.com/access_token';
    
    private $client_id = '';
    private $secret_key = '';
    private $return_url = '';
    
    protected $container = null;
    
    public function __construct($container) { 
        $this->container = $container;
        
        $this->client_id = $this->container->getParameter('alex_p_social_auth.vk.client_id');
        $this->secret_key = $this->container->getParameter('alex_p_social_auth.vk.secret_key');
        $this->return_url = $this->container->getParameter('alex_p_social_auth.vk.return_url');
    }
    
    /**
     * Generate url for login with vk
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
     * Get vk data
     * 
     * @param string $code
     * @return array
     */
    public function getData($code) {
        $params = array(
            'client_id' => $this->client_id,
            'client_secret' => $this->secret_key,
            'code' => $code,
            'redirect_uri' => $this->return_url
        );

        $token = $this->getRemoteContent(self::TOKEN_URL, $params);

        if (isset($token['access_token'])) {
            $params = array(
                'uids'         => $token['user_id'],
                'fields'       => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big',
                'access_token' => $token['access_token']
            );

            $data = $this->getRemoteContent('https://api.vk.com/method/users.get', $params);

            if(isset($data['response'])) return $data['response'][0];
        }
    }
    
}