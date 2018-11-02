<?php

namespace Ecjia\App\Ucserver\Server;

use ecjia_api;
use Ecjia\App\Ucserver\Helper;

class ApiBase extends ecjia_api
{
    /**
     * 用户名检测失败
     */
    const UC_USER_CHECK_USERNAME_FAILED = -1;

    /**
     * 用户名被禁用
     */
    const UC_USER_USERNAME_BADWORD = -2;

    /**
     * 用户名已经存在
     */
    const UC_USER_USERNAME_EXISTS = -3;

    /**
     * E-mail格式无效
     */
    const UC_USER_EMAIL_FORMAT_ILLEGAL = -4;

    /**
     * E-mail限制使用
     */
    const UC_USER_EMAIL_ACCESS_ILLEGAL = -5;

    /**
     * E-mail已经存在
     */
    const UC_USER_EMAIL_EXISTS = -6;

    
    protected $input = [];
    
    protected $app = [];
    
    protected $authkey;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->initApp();
        
        $this->authkey = 'V9Tdb5w2Ucl8x9a31by45dg0pbQ5R8l4h7a508y7Xef6E6x5CaH878T2Ody6x0g7';
    }
    
    public function initInput($getagent = '')
    {
        $input = $this->request->input('input');
        if ($input) {
            $input = Helper::authcode($input, 'DECODE', $this->authkey);
            parse_str($input, $this->input);
            $this->input = Helper::daddslashes($this->input, true);
            $agent = $getagent ? $getagent : $this->input['agent'];
            
            if (($getagent && $getagent != $this->input['agent']) || 
                (!$getagent && md5($_SERVER['HTTP_USER_AGENT']) != $agent)) {
                    
                exit('Access denied for agent changed');
                
            } elseif($this->time - $this->input('time') > 3600) {
                
                exit('Authorization has expired');
                
            }
        }
        
        if (empty($this->input)) {
            
            exit('Invalid input');
            
        }
    }
    
    
    public function initApp()
    {
        $appid = intval($this->request->input('appid'));
        $appid && $this->app = $this->cache['apps'][$appid];
    }
    
    
    public function input($k) 
    {
        if ($k == 'uid') {
            if (is_array($this->input[$k])) {
                foreach ($this->input[$k] as $value) {
                    if(!preg_match("/^[0-9]+$/", $value)) {
                        return NULL;
                    }
                }
            } elseif (!preg_match("/^[0-9]+$/", $this->input[$k])) {
                return NULL;
            }
        }
        return isset($this->input[$k]) ? (is_array($this->input[$k]) ? $this->input[$k] : trim($this->input[$k])) : NULL;
    }
    
    
}

// end