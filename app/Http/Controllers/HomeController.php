<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function openRegister()
    {
        if (auth()->user()->email != env('ADMINISTRATOR_EMAIL'))
            return response()->json(['error' => 'You are not administrator!']);
        $setting = Setting::where('key', '=', 'register')->first();
        $setting->value = true;
        $setting->save();

        return response()->json(['success' => 'Registration has been successfully opened']);
    }

    public function closeRegister()
    {
        if (auth()->user()->email != env('ADMINISTRATOR_EMAIL'))
            return response()->json(['error' => 'You are not administrator!']);
        $setting = Setting::where('key', '=', 'register')->first();
        $setting->value = false;
        $setting->save();

        return response()->json(['success' => 'Registration has been successfully closed']);
    }
}
