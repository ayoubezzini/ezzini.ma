<?php

namespace App\Http\Controllers;

use App\Resume\Project;
use Illuminate\View\View;

class ResumeController extends Controller
{
    public function show(): View
    {
        return view('resume', [
            'projects' => Project::all(),
        ]);
    }
}
