<?php

namespace AlexP\SocialAuthBundle\OAuth;

use AlexP\SocialAuthBundle\lib\SocialInterace as SocialInterace;

class GooglePlus extends OAuth implements SocialInterace {
    
    const AUTH_URL = 'https://accounts.google.com/o/oauth2/auth';
    const TOKEN_URL = 'https://accounts.google.com/o/oauth2/token';
    
    private $client_id = '';
    private $secret_key = '';
    private $return_url = '';
    
    protected $container = null;
    
    public function __construct($container) { 
        $this->container = $container;
        
        $this->client_id = $this->container->getParameter('alex_p_social_auth.google_plus.client_id');
        $this->secret_key = $this->container->getParameter('alex_p_social_auth.google_plus.secret_key');
        $this->return_url = $this->container->getParameter('alex_p_social_auth.google_plus.return_url');
    }
    
    /**
     * Generate url for login with google plus
     * 
     * @return string
     */
    public function getUrl() {
        $params = array(
            'redirect_uri'  => $this->return_url,
            'response_type' => 'code',
            'client_id'     => $this->client_id,
            'scope'         => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile'
        );

        $url = $this->__getUrl(self::AUTH_URL, $params);
        
        return $url;
    }
    
    /**
     * Get google plus data
     * 
     * @param string $code
     * @return array
     */
    public function getData($code) {
        $result = false;
	 
        $params = array(
            'client_id'     => $this->client_id,
            'client_secret' => $this->secret_key,
            'redirect_uri'  => $this->return_url,
            'grant_type'    => 'authorization_code',
            'code'          => $code
        );

        $googleToken = $this->sendPost(self::TOKEN_URL, $params);

        if(!empty($googleToken['access_token'])) {
            $params['access_token'] = $googleToken['access_token'];

            $data = $this->getRemoteContent('https://www.googleapis.com/oauth2/v1/userinfo', $params);

            if(!empty($data['email'])) return $data;
        }
    }
    
}