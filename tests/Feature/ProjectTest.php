<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTest extends TestCase
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

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph
        ];

        $this->actingAs(factory('App\User')->create());

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

    public function testGuestsMayNotViewProjects ()
    {
        $attributes = factory('App\Project')->raw();
        
        $this->post('/projects', $attributes)->assertRedirect('login');
    }
    
    public function testGuestCannotViewASingleProject ()
    {
        $project = factory('App\Project')->create();

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
