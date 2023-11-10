<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
	public function redirectToProvider()
	{
		return Socialite::driver('google')->redirect();
	}
	
	public function handleProviderCallback(\Request $request)
	{
		try {
			$user_google    = Socialite::driver('google')->user();
			$user           = User::where('email', $user_google->getEmail())->first();
			if ($user != null) :
				\auth()->login($user, true);
				return redirect()->route('l.news', Session::get('mark-page'));
			else :
				$create = User::Create([
					'email'				=> $user_google->getEmail(),
					'name'				=> $user_google->getName(),
					'avatar'			=> $user_google->getAvatar(),
					'password'			=> 0,
					'third_party'		=> 'google',
					'role'				=> 'reader-2',
					'email_verified_at' => now()
				]);
		
				\auth()->login($create, true);
				return redirect()->route('l.news', Session::get('mark-page'));
			endif;

		} catch (\Exception $e) {
			return redirect()->back();
		}
	}
}
