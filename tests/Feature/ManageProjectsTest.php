<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAUserCanCreateAProject ()
    {
        $this->withoutExceptionHandling();

        $this->actingAs(factory('App\User')->create());


        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph
        ];

        $this->get('projects/create')->assertStatus(200);

        $this->post('/projects', $attributes)->assertRedirect('/projects');

        $this->assertDatabaseHas('projects', $attributes);

        $this->get('/projects')->assertSee($attributes['title']);
    }

    /**
     * Ensure projects are validated
     *
     * @return void
     */
    public function testAProjectRequiresATitle ()
    {
        $this->actingAs(factory('App\User')->create());

        $attributes = factory('App\Project')->raw(['title' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    public function testAProjectRequiresADescription ()
    {
        $this->actingAs(factory('App\User')->create());

        $attributes = factory('App\Project')->raw(['description' => '']);
        
        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }

    public function testGuestsMayNotInteractWithProjects ()
    {
        $project = factory('App\Project')->create();
        
        $this->post('/projects', $project->toArray())->assertRedirect('login');
        $this->get('/projects')->assertRedirect('login');
        $this->get($project->path())->assertRedirect('login');
    }
    
    public function testAUserCanViewAProject () 
    {
        $this->withoutExceptionHandling();

        $this->be(factory('App\User')->create());

        $project = factory('App\Project')->create([
            'owner_id' => auth()->id()
        ]);
        
        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    public function testAUserCannotViewOthersProjects () 
    {
        $this->be(factory('App\User')->create());

        $otherUser = factory('App\User')->create();

        $project = factory('App\Project')->create([
            'owner_id' => $otherUser->id
        ]);

        $this->get($project->path())
            ->assertStatus(403);
    }
}
