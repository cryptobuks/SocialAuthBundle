<?php

namespace AlexP\SocialAuthBundle\OAuth;

class OAuth {
    
    protected function sendPost($url, $params) {
        $curl = curl_init();
        
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->__convertParams($params));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($curl);
        
        return $this->__returnRequest($result);
    }
    
    protected function getRemoteContent($url, $params) {
        $result = file_get_contents($url . '?' . $this->__convertParams($params));
        
        if(!empty($result)) {
            return $this->__returnRequest($result);
        }
    }

    protected function __convertParams($params) {
        return urldecode(http_build_query($params));
    }

    private function __returnRequest($data) {
        $decode = json_decode($data, true);
        
        return json_last_error() == JSON_ERROR_NONE ? $decode : $data;
    }
    
    public function __getUrl($url, $params = []) {
        $result = $url . '?' . $this->__convertParams($params);
        
        return $result;
    }

}