<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function index()
    {
        $user = session()->get('user'); // Ambil data user dari session

        if (!$user) {
            return redirect()->to('/auth/google')->with('error', 'Silakan login dulu.');
        }

        return view('dashboard', ['user' => $user]);
    }
}
