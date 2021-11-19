<?php

namespace Framework\Encryption;

use \Framework\Application;

class XorEnc {
    public static function Encrypt($data)
    {
        $decrypt = preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data);
        if($decrypt) $data = base64_decode($data);
        $key = Application::$instance->get('xor')['key'];
        $data_len = strlen($data);
        $key_len = strlen($key);
        if($data_len <= $key_len) {
            return $decrypt ? ($data ^ $key) : base64_encode($data ^ $key);
        }
        for($x = 0; $x < $data_len; $x++)
        {
            $data[$x] = $data[$x] ^ $key[$x % $key_len];
        }
        return $decrypt ? $data : base64_encode($data);
    }
}