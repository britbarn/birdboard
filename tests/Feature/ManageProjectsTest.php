<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageProjectsTest extends TestCase
{
    //use refresh database to make sure that after the test, the databse will go baack to its original state
    use WithFaker, RefreshDatabase;

      /** @test */
    public function guests_cannot_manage_projects(){

        // $this->withoutExceptionHandling();

        //create a new test project from the factory
        $project = factory('App\Project')->create();

        //try to persist project to database
        $this->post('/projects', $project->toArray())->assertRedirect('login');

        //try to access dashboard
        $this->get('/projects')->assertRedirect('login');

        //try to create new project
        $this->get('/projects/create')->assertRedirect('login');

        //try to access specific project
        $this->get($project->path())->assertRedirect('login');
    }

    //test annotation is necessary for phpunit to pick it up
    /** @test */
    public function a_user_can_create_a_project()
    {
        //we want to see the exception
        // $this->withoutExceptionHandling();

        $this->actingAs(factory('App\User')->create());

        $this->get('/projects/create')->assertStatus(200);

        $attributes = [
            'title' => $this->faker->sentence,

            'description' => $this->faker->paragraph
        ];

        //make sure that posting the attributes works, and that it redirects
        $this->post('/projects', $attributes)->assertRedirect('/projects');


        //make sure that database table projects has the attributes from post in it
        $this->assertDatabaseHas('projects', $attributes);

        //we can also verify that the GET request returns the new project
        $this->get('/projects')->assertSee($attributes['title']);
    }

    /** @test */
    public function a_user_can_view_their_project()
    {
        $this->be(factory('App\User')->create());

        // $this->withoutExceptionHandling();

        $project = factory('App\Project')->create(['owner_id' => auth()->id()]);

        // dd($project);

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    /** @test */
    public function an_authenticated_user_cannot_view_the_projects_of_others()
    {
        $this->be(factory('App\User')->create());

        // $this->withoutExceptionHandling();

        $project = factory('App\Project')->create();

        // dd($project);

        $this->get($project->path())->assertStatus(403);
            
    }

    /** @test */
    public function a_project_requires_a_title(){

        $this->actingAs(factory('App\User')->create());

        //raw creates attributes as an array
        $attributes = factory('App\Project')->raw(['title' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

     /** @test */
    public function a_project_requires_a_description(){

        $this->actingAs(factory('App\User')->create());

        $attributes = factory('App\Project')->raw(['description' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }

     /** @test */
     //this will fail with errors if the project has no owner id
    public function a_project_requires_an_owner(){

        // $this->withoutExceptionHandling();

        $attributes = factory('App\Project')->raw();

        // $this->post('/projects', $attributes)->assertSessionHasErrors('owner_id');

        $this->post('/projects', $attributes)->assertRedirect('login');
    }

}
