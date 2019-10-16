<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;

class ProjectsController extends Controller
{
    public function index(){
    	//get all projects
		$projects = Project::all();

		//return them to the view
		return view('projects.index', compact('projects'));
    }

    public function store(){

    	//validate

    	$attributes = request()->validate(['title' => 'required', 'description' => 'required']);

		//persist

		Project::create($attributes);

		//redirect

		return redirect('projects');
    }

    //auto inject the project from the GET URL
    public function show(Project $project){

    	//find or fail to make sure an exception is thrown
    	// $project = Project::findOrFail(request('project'));

    	return view('projects.show', compact('project'));
    }
}
