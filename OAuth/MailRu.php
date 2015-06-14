<?php

namespace AlexP\SocialAuthBundle\OAuth;

use AlexP\SocialAuthBundle\lib\SocialInterace as SocialInterace;

class MailRu extends OAuth implements SocialInterace {
    
    const AUTH_URL = 'https://connect.mail.ru/oauth/authorize';
    const TOKEN_URL = 'https://connect.mail.ru/oauth/token';

    private $client_id = '';
    private $secret_key = '';
    private $return_url = '';
    
    protected $container = null;
    
    public function __construct($container) { 
        $this->container = $container;
        
        $this->client_id = $this->container->getParameter('alex_p_social_auth.mailru.client_id');
        $this->secret_key = $this->container->getParameter('alex_p_social_auth.mailru.secret_key');
        $this->return_url = $this->container->getParameter('alex_p_social_auth.mailru.return_url');
    }
    
    /**
     * Generate url for login with facebook
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
            'code'          => $code,
            'grant_type'    => 'authorization_code'
        );

        $getContent = $this->getRemoteContent(self::TOKEN_URL, $params);
        
        if(!empty($getContent)) {
            $user = null;

            parse_str($getContent, $user);

            if(count($user) > 0 && isset($user['access_token'])) {
                $sign = md5('app_id=' . $this->client_id . 'method=users.getInfosecure=1session_key={' . $user['access_token'] . '}{' . $this->secret_key . '}');
                
                $params = array(
                    'method'       => 'users.getInfo',
                    'secure'       => '1',
                    'app_id'       => $this->client_id,
                    'session_key'  => $user['access_token'],
                    'sig'          => $sign
                );

                $data = $this->getRemoteContent('http://www.appsmail.ru/platform/api', $params);
                
                if(isset($data['0']) && isset($data['0']['email'])) return $data['0'];
            }
        }
    }
    
}