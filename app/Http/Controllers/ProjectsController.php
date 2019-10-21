<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;

class ProjectsController extends Controller
{
    public function index(){

    	//get all projects created by this current user
		$projects = auth()->user()->projects;

		//return them to the view
		return view('projects.index', compact('projects'));
    }

    public function store(){

    	//validate

    	$attributes = request()->validate([
            'title' => 'required',
             'description' => 'required'
          ]);

        // $attributes['owner_id'] = auth()->id();

		//persist

        auth()->user()->projects()->create($attributes);

        // Project::create($attributes);


		//redirect

		return redirect('/projects');
    }

    //auto inject the project from the GET URL
    public function show(Project $project){

    	//find or fail to make sure an exception is thrown
    	// $project = Project::findOrFail(request('project'));

        if (auth()->user()->isNot($project->owner)) {
            abort(403);
        }

    	return view('projects.show', compact('project'));
    }

    public function create(){

        return view('projects.create');

    }
}
