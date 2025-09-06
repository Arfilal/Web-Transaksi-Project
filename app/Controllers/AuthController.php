<?php

namespace App\Controllers;

use Google_Client;
use Google_Service_Oauth2;

class AuthController extends BaseController
{
    private function getClient()
    {
        $client = new Google_Client();
        $client->setClientId(getenv('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(getenv('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(getenv('GOOGLE_REDIRECT_URI'));

        $client->addScope("https://www.googleapis.com/auth/userinfo.email");
        $client->addScope("https://www.googleapis.com/auth/userinfo.profile");

        return $client;
    }

    public function login()
    {
        // tampilkan halaman login manual + tombol google
        return view('login');
    }

    public function redirectToGoogle()
    {
        $client = $this->getClient();
        return redirect()->to($client->createAuthUrl());
    }

    public function handleGoogleCallback()
    {
        $client = $this->getClient();

        if ($this->request->getVar('code')) {
            $token = $client->fetchAccessTokenWithAuthCode($this->request->getVar('code'));

            if (!isset($token["error"])) {
                $client->setAccessToken($token['access_token']);
                $googleService = new Google_Service_Oauth2($client);
                $googleUser = $googleService->userinfo->get();

                $data = [
                    'id_google' => $googleUser->id,
                    'nama'      => $googleUser->name,
                    'email'     => $googleUser->email,
                    'avatar'    => $googleUser->picture,
                ];

                session()->set('user', $data);

                return redirect()->to('/dashboard');
            }
        }

        return redirect()->to('/login')->with('error', 'Gagal login dengan Google');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda berhasil logout.');
    }
}
