<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Repositories\SettingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class AuthController extends Controller
{
    protected $authService;
    protected $settings;

    public function __construct(
        AuthService $authService,
        SettingRepository $settings
    ) {
        $this->authService = $authService;
        $this->settings = $settings;
    }

    public function loginView()
    {
        $settings = $this->settings->first();

        session([
            'favicon' => $settings->favicon,
            'logo'    => $settings->logo,
        ]);

        date_default_timezone_set($settings->timezone);

        return view('extra.login', compact('settings'));
    }

    public function LoginViewStore(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        $route = $this->authService->attemptLogin(
            $request->email,
            $request->password
        );

        if (! $route) {
            return redirect()->back()
                ->withErrors('Username or password invalid');
        }

        return redirect()->route($route);
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();

        return redirect()->route('auth.login');
    }
}
