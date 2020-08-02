<?php

namespace Tests\Feature;

use App\Product;
use App\Rate;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RateControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testGuestCannotStoreRate()
    {
        $p = factory(Product::class)->create();

        $this->post('/rates', [])
            ->assertStatus(302);
    }

    public function testUserCannotStoreRateWithInvalidData()
    {
        $this->signIn();

        $p = factory(Product::class)->create();

        $this->postJson("/rates", [])
            ->assertStatus(422);
    }

    public function testUserCanStoreRate()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $p = factory(Product::class)->create();
        $r = factory(Rate::class)->make();
        $rate = is_int($r->rate) ? $r->rate + .0 : $r->rate;

        $this->post("/rates", [
            'rate' => $r->rate,
            'product_id' => $p->id,
            'message' => $r->message
        ])->assertOk();
            // ->assertJsonPath('rate', $rate);
    }

    public function testUserCannotUpdateOthersRate()
    {
        // $this->withoutExceptionHandling();
        $user = $this->signIn();

        $r = factory(Rate::class)->create([
            'user_id' => (factory(User::class)->create())->id
        ]);

        $this->patch('/rates/' . $r->id, [
            'rate' => 3,
        ])->assertStatus(403);
    }

    public function testUserCanUpdateRate()
    {
        $user = $this->signIn();

        $r = factory(Rate::class)->create([
            'user_id' => $user->id,
            'rate' => 1
        ]);

        $this->patch('/rates/' . $r->id, [
            'rate' => 3,
        ])->assertOk()
            ->assertJson(['updated' => true]);

        $this->assertDatabaseHas('rates', ['id' => $r->id, 'rate' => 3]);
    }

    public function testAdminOrSuperCanUpdateRate()
    {
        $this->withoutExceptionHandling();
        $user = $this->signIn(null, ['role' => User::AdminRole]);

        $r = factory(Rate::class)->create([
            'user_id' => (factory(User::class)->create())->id,
            'rate' => 1
        ]);

        $this->patch('/rates/' . $r->id, [
            'rate' => 3,
        ])->assertOk()
            ->assertJson(['updated' => true]);

        $this->actingAs(
            $this->signIn(null, ['role' => User::SuperRole])
        )->patch('/rates/' . $r->id, [
            'rate' => 3,
        ])->assertOk()
            ->assertJson(['updated' => true]);

        $this->assertDatabaseHas('rates', ['id' => $r->id, 'rate' => 3]);
    }

    public function testUserCannotDeleteOthersRate()
    {
        $user = $this->signIn();

        $r = factory(Rate::class)->create([
            'user_id' => (factory(User::class)->create())->id
        ]);

        $this->delete('/rates/' . $r->id)
            ->assertStatus(403);

        $this->assertDatabaseHas('rates', ['id' => $r->id]);
    }

    public function testUserCanDeleteRate()
    {
        $user = $this->signIn();

        $r = factory(Rate::class)->create([
            'user_id' => $user->id
        ]);

        $this->delete('/rates/' . $r->id)
            ->assertStatus(204);

        $this->assertDatabaseMissing('rates', ['id' => $r->id]);
    }

    public function testAdminOrSuperCanDeleteRate()
    {
        $user = $this->signIn(null, ['role' => User::AdminRole]);

        $r = factory(Rate::class)->create([
            'user_id' => (factory(User::class)->create())->id,
        ]);

        $this->delete('/rates/' . $r->id)
            ->assertStatus(204);

        $r = factory(Rate::class)->create([
            'user_id' => (factory(User::class)->create())->id,
        ]);

        $this->actingAs(
            $this->signIn(null, ['role' => User::SuperRole])
        )->delete('/rates/' . $r->id)
            ->assertStatus(204);

        $this->assertDatabaseMissing('rates', ['id' => $r->id]);
    }
}
