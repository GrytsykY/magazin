<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ResetController extends Controller
{
    public function reset()
    {
        Artisan::call('migrate:fresh --seed');
        session()->flash('success','Проект сброшен в начальное состояние');
        //session()->flush('success');
        return redirect()->route('home');
    }
}
