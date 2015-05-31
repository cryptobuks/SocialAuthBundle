<?php

/**
 * @author Alex Pass <alexey.pass@gmail.com>
 */

namespace AlexP\SocialAuthBundle\lib;

interface SocialInterace {
    
    public function getUrl();
    public function getData($code);
    
}