<?php

namespace App\Http\Controllers;

use App\Resume\Project;
use Illuminate\View\View;

class ProjectsController extends Controller
{
    public function show(Project $project): View
    {
        return view('projects.show', [
            'project' => $project,
        ]);
    }
}
