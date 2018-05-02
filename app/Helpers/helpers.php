<?php

use App\Helpers\Util;


if (! function_exists('getIp')) {
    /**
     * retrieve real user ip
     *
     * @param  array  $array
     * @return string
     */
    function getConnectedUserIp()
    {
        return Util::getConnectedUserIp();
    }
 }



