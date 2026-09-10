<?php

namespace App\Services;

use App\Exceptions\SocialLoginException;
use App\Models\OauthProvider;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class SocialAuthService
{
    public function findOrCreateUser(string $provider, SocialiteUser $socialUser): User
    {
        // Step 1: এই provider+provider_id দিয়ে আগে login করেছে কিনা check
        $oauthAccount = OauthProvider::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if ($oauthAccount) {
            $oauthAccount->update([
                'avatar' => $socialUser->getAvatar(),
                'access_token' => $socialUser->token,
                'refresh_token' => $socialUser->refreshToken ?? null,
            ]);
            return $oauthAccount->user;
        }

        // Step 2: এই email দিয়ে অন্য কোনো account (email/password বা অন্য provider) আছে কিনা
        $existingUser = User::where('email', $socialUser->getEmail())->first();

        if ($existingUser) {
            // merge/auto-login করবো না — security এর জন্য block
            $existingProvider = $existingUser->oauthProviders()->value('provider');
            throw SocialLoginException::emailAlreadyRegistered($socialUser->getEmail(), $existingProvider);
        }

        // Step 3: একদম নতুন user + oauth record — একসাথে transaction এ
        return DB::transaction(function () use ($provider, $socialUser) {
            $user = User::create([
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'email' => $socialUser->getEmail(),
                'password' => null,
                'email_verified_at' => now(), // google/facebook already verified email দেয়
            ]);

            $user->oauthProviders()->create([
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
                'access_token' => $socialUser->token,
                'refresh_token' => $socialUser->refreshToken ?? null,
            ]);

            return $user;
        });
    }
}