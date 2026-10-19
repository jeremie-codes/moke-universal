<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Setting;
use App\Models\Skill;
use App\Models\Experience;
use App\Models\Partenaire;

class PortfolioController extends Controller
{
    public function index()
    {
        $settings = Setting::first();
        $projects = Project::with(['media'])->where('is_published', true)->paginate(12);
        $experiences = Experience::with(['media'])->where('is_active', true)->get();
        $skills = Skill::where('is_active', true)->get();
        $teams = Partenaire::get();
        
        return view('portfolio.index', compact('settings', 'projects', 'experiences', 'skills', 'teams'));
    }

    public function show(Project $project)
    {

        abort_unless($project->is_published, 404);
        $project->load(['media']);

        return view('portfolio.show', compact('project'));
    }
}
