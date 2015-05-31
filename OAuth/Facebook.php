<?php

namespace AlexP\SocialAuthBundle\OAuth;

use AlexP\SocialAuthBundle\lib\SocialInterace as SocialInterace;

class Facebook extends OAuth implements SocialInterace {
    
    const AUTH_URL = 'https://www.facebook.com/dialog/oauth';
    const TOKEN_URL = 'https://graph.facebook.com/oauth/access_token';

    private $client_id = '';
    private $secret_key = '';
    private $return_url = '';
    
    protected $container = null;
    
    public function __construct($container) { 
        $this->container = $container;
        
        $this->client_id = $this->container->getParameter('alex_p_social_auth.facebook.client_id');
        $this->secret_key = $this->container->getParameter('alex_p_social_auth.facebook.secret_key');
        $this->return_url = $this->container->getParameter('alex_p_social_auth.facebook.return_url');
    }
    
    /**
     * Generate url for login with facebook
     * 
     * @return string
     */
    public function getUrl() {
        $params = array(
            'client_id'     => $this->client_id,
            'redirect_uri'  => $this->return_url,
            'response_type' => 'code',
            'scope'         => 'email,user_birthday'
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
            'redirect_uri'  => $this->return_url,
            'client_secret' => $this->secret_key,
            'code'          => $code
        );

        $getContent = $this->getRemoteContent(self::TOKEN_URL, $params);
        
        if(!empty($getContent)) {
            $fbUser = null;

            parse_str($getContent, $fbUser);

            if(count($fbUser) > 0 && isset($fbUser['access_token'])) {
                $params = array('access_token' => $fbUser['access_token']);

                $data = $this->getRemoteContent('https://graph.facebook.com/me', $params);
                
                if(isset($data['email'])) return $data;
            }
        }
    }
    
}