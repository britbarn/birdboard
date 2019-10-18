<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectsTest extends TestCase
{
    //use refresh database to make sure that after the test, the databse will go baack to its original state
    use WithFaker, RefreshDatabase;

      /** @test */
    public function only_authenitacted_users_can_create_projects(){

        // $this->withoutExceptionHandling();

        //create a new test project from the factory
        $project = factory('App\Project')->create();

        //test the get route to make sure the project is viewable
        $this->get($project->path())->assertSee($project->title)->assertSee($project->description);
    }

    //test annotation is necessary for phpunit to pick it up
    /** @test */
    public function a_user_can_create_a_project()
    {
        //we want to see the exception
        // $this->withoutExceptionHandling();

        $this->actingAs(factory('App\User')->create());

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
