<?php

namespace Tests\Feature\Admin;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testOnlyAdminCanVisitDashboard()
    {
        $this->signIn();

        $this->get('/en/root')
            ->assertForbidden();
    }

    public function testAdminCanSeeDashboard()
    {
        $this->withoutExceptionHandling();
        $this->signIn(null, [
            'role' => User::AdminRole
        ]);

        $this->get('/en/root')
            ->assertOk()
            ->assertViewIs('admin.dash')
            ->assertViewHas('user', auth()->user());
    }
}
