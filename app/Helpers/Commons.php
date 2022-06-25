<?php 

namespace App\Helpers;

class Commons {

    public static function getMainDataArray():array
    {
        $data = array();
        $data['searchURL'] = url('search');
        return $data;
    }
}