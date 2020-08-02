<?php

namespace Tests\Feature;

use App\Http\Controllers\UserProfileController;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Storage;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testGuestCanNotAccessProfileSection()
    {
        $this->get('/en/user/bkjsd4')
            ->assertRedirect(route('login'));
    }

    public function testUserCanNotSeeOtherProfile()
    {
        // $this->withoutExceptionHandling();
        $user = factory(User::class)->create();

        $this->signIn(factory(User::class)->create());

        $this->get(route('userDash', $user->enc_id))
            ->assertForbidden();
    }

    public function testAdminCanVisitUserProfile()
    {
        $user = factory(User::class)->create();

        $this->signIn(factory(User::class)->create([
            'role' => User::AdminRole
        ]));

        $this->get(route('userDash', $user->enc_id))
            ->assertOk();
    }

    public function testUserCanSeeHisProfile()
    {
        $user = $this->signIn();
        $this->get(route('userDash', $user->enc_id))
            ->assertOk()
            ->assertViewIs('user.dashbored')
            ->assertViewHas('user', $user);
    }

    public function testUserCanNotUploadInvalidFile()
    {
        Storage::fake('/storage/user_profile');

        $this->signIn();
        $uri = '/en/user//profile/image';
        $this->patch($uri, [])
            ->assertRedirect()
            ->assertSessionHasErrors('img');

        $img = UploadedFile::fake()->image('imgasd.gif');
        $this->patch($uri, compact('img'))
            ->assertRedirect()
            ->assertSessionHasErrors('img');

        $imgMaxSize = UploadedFile::fake()->image('wxcsd.jpg')->size(1100);
        $this->patch($uri, [
            'img' => $imgMaxSize
        ])
            ->assertRedirect()
            ->assertSessionHasErrors('img');
    }

    public function testUserCanNotUploadImageToOtherUserProfile()
    {
        Storage::fake('/storage/user_profile');

        $owner = $this->signIn();

        $this->signIn();
        $img = UploadedFile::fake()->image('wds.png');
        $this->patch('/en/user/' . $owner->enc_id . '/profile/image')
            ->assertForbidden();
    }

    public function testUserCanUploadImage()
    {
        $this->withoutExceptionHandling();
        $user = $this->signIn(null);
        Storage::fake('local');

        $img = UploadedFile::fake()->image('wasdxc.png');

        $res = $this->patch('/en/user//profile/image', compact('img'))
            ->assertOk()
            ->assertExactJson(['img' => 'storage/user_profile/' . $img->hashName()]);

        Storage::disk('local')->assertExists('public/user_profile/' . $img->hashName());

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'img' => 'storage/user_profile/' . $img->hashName()
        ]);
    }

    public function testUserChangImageWillDeleteOldOne()
    {
        $this->withoutExceptionHandling();
        $user = $this->signIn();
        Storage::fake('local');

        $img = UploadedFile::fake()->image('wasd.jpg');
        $img2 = UploadedFile::fake()->image('qqq.png');

        $res = $this->patch('/en/user//profile/image', compact('img'))
            ->assertOk()
            ->assertExactJson(['img' => 'storage/user_profile/' . $img->hashName()]);
            // dump($img2->hashName());

        $res = $this->patch('/en/user//profile/image', [
            'img' => $img2
        ])
            ->assertOk()
            ->assertExactJson(['img' => 'storage/user_profile/' . $img2->hashName()]);

        Storage::disk('local')->assertExists(UserProfileController::IMAGE_DIR . '/' . $img2->hashName());

        Storage::disk('local')->assertMissing(UserProfileController::IMAGE_DIR . '/' . $img->hashName());
    }
}
