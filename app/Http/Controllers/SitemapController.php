<?php

namespace App\Http\Controllers;

use App\Resume\Project;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $pages = [
            ['home', []],
            ['resume', []],
            ...Project::all()->map(fn (Project $project) => ['projects.show', ['project' => $project->slug]]),
        ];

        return response()
            ->view('sitemap', ['pages' => $pages])
            ->header('Content-Type', 'application/xml');
    }
}
