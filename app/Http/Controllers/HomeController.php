<?php

namespace App\Http\Controllers;

use App\Resume\Project;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'projects' => Project::all(),
        ]);
    }
}
