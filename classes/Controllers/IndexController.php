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

        $data = $response->getOriginalContent();
        $error_code = array_get($data, 'status.error_code');

        if (in_array($error_code, [
            'url_param_not_exists',
            'api_not_exists',
            'api_not_handle',
            'api_not_instanceof',
        ])) {
            $error_desc = array_get($data, 'status.error_desc');
            $response->setOriginalContent($error_desc);
        }

        royalcms()->instance('response', $response);
        return $response;
    }
    

}
