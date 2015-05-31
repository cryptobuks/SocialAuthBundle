<?php

namespace AlexP\SocialAuthBundle\OAuth;

use AlexP\SocialAuthBundle\lib\SocialInterace as SocialInterace;

class Linkedin extends OAuth implements SocialInterace {
    
    const AUTH_URL = 'https://www.linkedin.com/uas/oauth2/authorization';
    const TOKEN_URL = 'https://www.linkedin.com/uas/oauth2/accessToken';

    private $client_id = '';
    private $secret_key = '';
    private $return_url = '';
    
    protected $container = null;
    
    public function __construct($container) { 
        $this->container = $container;
        
        $this->client_id = $this->container->getParameter('alex_p_social_auth.linkedin.client_id');
        $this->secret_key = $this->container->getParameter('alex_p_social_auth.linkedin.secret_key');
        $this->return_url = $this->container->getParameter('alex_p_social_auth.linkedin.return_url');
    }
    
    /**
     * Generate url for login with Linkedin
     * 
     * @return string
     */
    public function getUrl() {
        $params = array(
            'client_id'     => $this->client_id,
            'redirect_uri'  => $this->return_url,
            'response_type' => 'code',
            'scope'         => 'r_basicprofile',
            'state'         => rand(1000000, 9999999)
        );
        
        $url = $this->__getUrl(self::AUTH_URL, $params);
        
        return $url;
    }
    
    /**
     * Get Linkedin data
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

        $token = $this->sendPost(self::TOKEN_URL, $params);
        
        if(!empty($token['access_token'])) {
            $params = [
                'oauth2_access_token' => $token['access_token'],
                'format' => 'json'
            ];
            
            $data = $this->getRemoteContent('https://api.linkedin.com/v1/people/~', $params);
            
            if(!empty($data['id'])) return $data;
        }
    }
    
}