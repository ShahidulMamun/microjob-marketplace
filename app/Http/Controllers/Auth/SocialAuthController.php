<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\SocialLoginException;
use App\Http\Controllers\Controller;
use App\Services\SocialAuthService;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    private const ALLOWED_PROVIDERS = ['google', 'facebook'];

    public function __construct(private SocialAuthService $socialAuthService) {}

    public function redirect(string $provider)
    {
        abort_unless(in_array($provider, self::ALLOWED_PROVIDERS), 404);

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider)
    {
        abort_unless(in_array($provider, self::ALLOWED_PROVIDERS), 404);

        try {
            $socialUser = Socialite::driver($provider)->user();
            $user = $this->socialAuthService->findOrCreateUser($provider, $socialUser);

            Auth::login($user, true);
            request()->session()->regenerate();

            return redirect()->route('user.dashboard');

        } catch (SocialLoginException $e) {
            return redirect('/login')->withErrors(['email' => $e->getMessage()]);
        } catch (\Exception $e) {
            report($e);
            return redirect('/login')->withErrors(['email' => 'Social login এ সমস্যা হয়েছে, আবার চেষ্টা করো।']);
        }
    }
}