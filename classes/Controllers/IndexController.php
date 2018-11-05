<?php

namespace Ecjia\App\Ucserver\Controllers;

use Ecjia\App\Ucserver\Server\ApiManager;
use RC_Package;

class IndexController
{

    public function __construct()
    {

        RC_Package::package('app::ucenter')->loadConfig('release');
    }

    
    public function init()
    {
        $request = royalcms('request');
        $response = with(new ApiManager($request))->handleRequest();

        royalcms()->instance('response', $response);
        return $response;
    }
    

}
