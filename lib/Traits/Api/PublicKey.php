<?php

namespace Chip\Traits\Api;

trait PublicKey
{
    /** @return string */
    public function getPublicKey(): string
    {
        $response = $this->request('GET', 'public_key/');
        $arr = is_object($response) ? (array) $response : $response;

        return is_array($arr) && isset($arr['public_key']) ? $arr['public_key'] : (string) $response;
    }
}
