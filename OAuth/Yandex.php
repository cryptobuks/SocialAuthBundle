<?php

namespace AlexP\SocialAuthBundle\OAuth;

use AlexP\SocialAuthBundle\lib\SocialInterace as SocialInterace;

class Yandex extends OAuth implements SocialInterace {
    
    const AUTH_URL = 'https://oauth.yandex.ru/authorize';
    const TOKEN_URL = 'https://oauth.yandex.ru/token';

    private $client_id = '';
    private $secret_key = '';
    private $return_url = '';
    
    protected $container = null;
    
    public function __construct($container) { 
        $this->container = $container;
        
        $this->client_id = $this->container->getParameter('alex_p_social_auth.yandex.client_id');
        $this->secret_key = $this->container->getParameter('alex_p_social_auth.yandex.secret_key');
        $this->return_url = $this->container->getParameter('alex_p_social_auth.yandex.return_url');
    }
    
    /**
     * Generate url for login with facebook
     * 
     * @return string
     */
    public function getUrl() {
        $params = array(
            'client_id'     => $this->client_id,
            'display'       => 'popup',
            'response_type' => 'code'
        );
        
        $url = $this->__getUrl(self::AUTH_URL, $params);
        
        return $url;
    }
    
    /**
     * Get facebook data
     * 
     * @param string $code
     * @return array
     */
    public function getData($code) {
        $params = array(
            'client_id'     => $this->client_id,
            'grant_type'    => 'authorization_code',
            'client_secret' => $this->secret_key,
            'code'          => $code
        );

        $getContent = $this->getRemoteContent(self::TOKEN_URL, $params);
        
        if(!empty($getContent)) {
            $user = null;

            parse_str($getContent, $user);

            if(count($user) > 0 && isset($user['access_token'])) {
                $params = array(
                    'format'       => 'json',
                    'oauth_token'  => $user['access_token']
                );

                $data = $this->getRemoteContent('https://login.yandex.ru/info', $params);
                
                if(isset($data['id'])) return $data;
            }
        }
    }
    
}