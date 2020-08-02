<?php

namespace Tests\Feature;

use App\Address;
use App\Http\Resources\AddressResource;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddressControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testGuestCannotAddAddressWithInvalidData()
    {
        $this->postJson('/address')
            ->assertStatus(422)
            ->assertJsonPath('errors.firstName', ['The order.firstName field is required.']);
    }

    public function testUserCannotAddAddressWithInvalidData()
    {
        $this->signIn();
        $this->testGuestCannotAddAddressWithInvalidData();
    }

    public function testGuestCanAddAddress()
    {
        $this->withoutExceptionHandling();
        $address = factory(Address::class)->raw();

        $this->postJson('/address', $address + [
            'userMail' => $this->faker->email
        ])
            ->assertOk()
            ->assertJsonPath('id', 1);
    }

    public function testUserCanAddAddress(?User $user = null)
    {
        $this->signIn($user);
        $this->testGuestCanAddAddress();
    }

    public function testGuestDoesnotHaveAddressList()
    {
        $this->get('/user/5/addresses')
            ->assertStatus(302);
    }

    public function testUserHaveAddressList()
    {
        $user = factory(User::class)->create();
        $this->testUserCanAddAddress($user);

        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id
        ]);

        $address = Address::whereUserId($user->id)->get();

        $this->get('/user/' . $user->id . '/addresses')
            ->assertOk()
            ->assertJsonPath('ads', $address->toArray());
    }

    public function testGuestCanCheckforAddressValidationWithoutSavingIt()
    {
        $address = factory(Address::class)->raw();

        $this->postJson('/address', $address + [
            'userMail' => $this->faker->email,
            'check' => true
        ])
            ->assertOk()
            ->assertJson($address);

        $this->assertDatabaseMissing('addresses', ['address' => $address['address']]);
    }

    public function testUserCanCheckForAddress()
    {
        $this->signIn();
        $this->testGuestCanCheckforAddressValidationWithoutSavingIt();
    }

    public function testGuestCanNotUpdateAddress()
    {
        $this->patch('/address/5')
            ->assertRedirect('/en/login');
    }

    public function testUserCanUpdateAddress(?Address $address = null)
    {
        $this->withoutExceptionHandling();
        $user = $this->signIn();

        $postCode = 53624;

        $address = $address ?? factory(Address::class)->create([
            'user_id' => $user->id
        ]);

        $address->postCode = $postCode;

        $res = $this->patchJson(
            '/address/' . $address->id,
            $address->toArray() + ['userMail' => $this->faker->email]
        )
            ->assertOk()
            ->assertJson(['updated' => true]);

        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'postCode' => $postCode
        ]);
    }

    public function testUserCanNotUpdateOtherUserAddress()
    {
        $user = $this->signIn();
        $address = factory(Address::class)->create();

        $res = $this->patchJson(
            '/address/' . $address->id,
            $address->toArray() + ['userMail' => $this->faker->email]
        )->assertForbidden();
    }

    public function testOnlyUserCanDeleteHisAddress()
    {
        // $this->withoutExceptionHandling();
        $user = $this->signIn();
        $address = factory(Address::class)->create();

        $this->signIn();
        $res = $this->deleteJson(
            '/address/' . $address->id
        )->assertForbidden();
    }

    public function testUserCanDeleteAddress()
    {
        // $this->withoutExceptionHandling();
        $user = $this->signIn();
        $address = $user->addresses()->create(
            factory(Address::class)->raw()
        );
        $this->assertDatabaseHas('addresses', ['id' => $address->id]);

        $this->delete('/address/' . $address->id)
            ->assertStatus(204);

        $this->assertDatabaseMissing('addresses', ['id' => $address->id]);
    }

    public function testItHaveResource()
    {
        $address = factory(Address::class)->create([
            'firstName' => 'asd',
        ]);
        $address->email = 'asdsad';

        $res = (new AddressResource($address))->toJson();

        $this->assertJson($res);
        $obj = json_decode($res);
        $this->assertEquals('asd', $obj->first_name);
        $this->assertNotNull($obj->email);
    }
}
