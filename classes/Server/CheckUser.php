<?php

namespace Ecjia\App\Ucserver\Server;

use Ecjia\App\Ucserver\Models\UserModel;

class CheckUser
{
    protected $user;
    
    public function __construct(UserModel $user)
    {
        $this->user = $user;
    }

    /**
     * @param $email
     * @param string $username
     * @return int
     */
    public function checkEmail($email, $username = '') 
    {
        if (!$this->user->check_emailformat($email)) {
            return ApiBase::UC_USER_EMAIL_FORMAT_ILLEGAL;
        } elseif ($this->user->check_emailexists($email, $username)) {
            return ApiBase::UC_USER_EMAIL_EXISTS;
        } else {
            return 1;
        }
    }

    /**
     * @param $username
     * @return int
     */
    public function checkUserName($username) 
    {
        $username = addslashes(trim(stripslashes($username)));
        if (!$this->user->check_username($username)) {
            return ApiBase::UC_USER_CHECK_USERNAME_FAILED;
        } elseif ($this->user->check_usernameexists($username)) {
            return ApiBase::UC_USER_USERNAME_EXISTS;
        }

        return 1;
    }
    
    
}