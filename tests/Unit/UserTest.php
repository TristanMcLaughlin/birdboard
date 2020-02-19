<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testAUserHasProjects()
    {
        $user = factory('App\User')->create();

        $this->assertInstanceOf(Collection::class, $user->projects);
    }
}
