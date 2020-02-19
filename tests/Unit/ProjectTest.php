<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * testAProjectExists
     *
     * @return void
     */
    public function testProjectHasAPath (): void
    {
        $project = factory('App\Project')->create();
        
        $this->assertEquals('/projects/' . $project->id, $project->path());
    }

    public function testProjectBelongsToAnOwner(): void
    {
        $project = factory('App\Project')->create();
        $this->assertInstanceOf('App\User', $project->user);
    }
}
