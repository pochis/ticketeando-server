<?php

use App\Helpers\Util;


if (! function_exists('getIp')) {
    /**
     * average of array total given.
     *
     * @param  array  $array
     * @return string
     */
    function getConnectedUserIp()
    {
        return Util::getConnectedUserIp();
    }
 }



