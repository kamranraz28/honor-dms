<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    protected $users;

    const ROLE_ROUTES = [
        500  => 'admin.dashboard',
        400  => 'topmanagement.dashboard',
        300  => 'midmanagement.dashboard',
        200  => 'retailer.dashboard',
        150  => 'sales.dashboard',
        100  => 'distributor.dashboard',
        10   => 'tso.dashboard',
        5    => 'service.dashboard',
        6    => 'warehouse.dashboard',
        7    => 'accounts.dashboard',
        1000 => 'huawei.dashboard',
        2000 => 'serviceManagement.dashboard',
    ];

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function attemptLogin($login, $password)
    {
        foreach (self::ROLE_ROUTES as $level => $route) {

            $credentials = [
                'active'   => true,
                'level'    => $level,
                'password' => $password,
            ];

            // roles using officeid
            if (in_array($level, [200,150,100,10,6])) {
                $credentials['officeid'] = $login;
            } else {
                $credentials['email'] = $login;
            }

            if (Auth::attempt($credentials)) {
                return $route;
            }
        }

        return false;
    }
}
