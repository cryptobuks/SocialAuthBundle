<?php

namespace AlexP\SocialAuthBundle\OAuth;

use AlexP\SocialAuthBundle\lib\SocialInterace as SocialInterace;

class Odnoklassniki extends OAuth implements SocialInterace {
    
    const AUTH_URL = 'http://www.odnoklassniki.ru/oauth/authorize';
    const TOKEN_URL = 'http://api.odnoklassniki.ru/oauth/token.do';

    private $client_id = '';
    private $public_key = '';
    private $secret_key = '';
    private $return_url = '';
    
    protected $container = null;
    
    public function __construct($container) { 
        $this->container = $container;
        
        $this->client_id = $this->container->getParameter('alex_p_social_auth.odnoklassniki.client_id');
        $this->secret_key = $this->container->getParameter('alex_p_social_auth.odnoklassniki.secret_key');
        $this->return_url = $this->container->getParameter('alex_p_social_auth.odnoklassniki.return_url');
    }
    
    /**
     * Generate url for login with odnoklassniki
     * 
     * @return string
     */
    public function getUrl() {
        $params = array(
            'client_id'     => $this->client_id,
            'response_type' => 'code',
            'redirect_uri'  => $this->return_url
        );
        
        $url = $this->__getUrl(self::AUTH_URL, $params);
        
        return $url;
    }
    
    /**
     * Get odnoklassniki data
     * 
     * @param string $code
     * @return array
     */
    public function getData($code) {
        $params = array(
            'code' => $code,
            'redirect_uri' => $this->return_url,
            'grant_type' => 'authorization_code',
            'client_id' => $this->client_id,
            'secret_key' => $this->secret_key
        );

        $tokenInfo = $this->sendPost(self::TOKEN_URL, $params);
        
        if(isset($tokenInfo['access_token']) && isset($this->public_key)) {
            $secretToken = md5($tokenInfo['access_token'] . $this->secret_key);
            
            $sign = md5('application_key=' . $this->public_key . 'format=jsonmethod=users.getCurrentUser' . $secretToken);

            $params = array(
                'method'          => 'users.getCurrentUser',
                'access_token'    => $tokenInfo['access_token'],
                'application_key' => $this->public_key,
                'format'          => 'json',
                'sig'             => $sign
            );
            
            $data = $this->getRemoteContent('http://api.odnoklassniki.ru/fb.do', $params);
            
            if(isset($data['uid'])) return $data;
        }
    }
    
}