<?php

namespace App\Controllers;

use App\Models\UserModel;
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

        // Tambahkan scope untuk Google Drive
        $client->addScope("https://www.googleapis.com/auth/userinfo.email");
        $client->addScope("https://www.googleapis.com/auth/userinfo.profile");
        $client->addScope("https://www.googleapis.com/auth/drive.file"); // Scope baru

        // Memastikan refresh token diberikan saat otorisasi pertama kali
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

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
        $userModel = new UserModel();

        if ($this->request->getVar('code')) {
            $token = $client->fetchAccessTokenWithAuthCode($this->request->getVar('code'));

            if (!isset($token["error"])) {
                $client->setAccessToken($token['access_token']);
                $googleService = new Google_Service_Oauth2($client);
                $googleUser = $googleService->userinfo->get();

                // Cek apakah pengguna sudah ada di database
                $user = $userModel->where('email', $googleUser->email)->first();

                // Data yang akan disimpan/diperbarui
                $userData = [
                    'nama'  => $googleUser->name,
                    'email' => $googleUser->email,
                ];

                // Tambahkan refresh token jika ada
                if (isset($token['refresh_token'])) {
                    $userData['google_refresh_token'] = $token['refresh_token'];
                }

                if ($user) {
                    // Pengguna sudah ada, update data mereka
                    $userModel->update($user['id'], $userData);
                    $userId = $user['id'];
                } else {
                    // Pengguna baru, tambahkan ke database
                    $userId = $userModel->insert($userData);
                }

                // Siapkan data sesi
                $sessionData = [
                    'id'     => $userId,
                    'nama'   => $googleUser->name,
                    'email'  => $googleUser->email,
                    'avatar' => $googleUser->picture,
                    'isLoggedIn' => true
                ];

                session()->set('user', $sessionData);
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