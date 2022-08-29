<?php

namespace App\Http\Services\AuthService;

interface LoginInterface {
    public function login($data) : array;
}
