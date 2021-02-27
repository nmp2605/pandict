<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ViewController extends Controller
{
    public function __invoke(): View
    {
        return view('main');
    }
}
