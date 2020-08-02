<?php

namespace Tests\Feature;

use App\Mail\ContactUsMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendMailTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testNoOneCanSendMAilWithInvalidData()
    {
        $this->postJson('/mail', [])
            ->assertStatus(422);
    }

    public function testUserCanSeeOwnedEmailAndNameInForm()
    {
        $user = $this->signIn();

        $this->get('/en/contact')
            ->assertOk()
            ->assertSee($user->email)
            ->assertSee($user->name);
    }

    public function testGuestCanSendMail()
    {
        $this->withoutExceptionHandling();

        Mail::fake();

        Mail::assertNothingSent();
        Mail::assertNothingQueued();

        $mail = [
            'userName' => $this->faker->name,
            'userMail' => $this->faker->email,
            'userMessage' => $this->faker->paragraph
        ];

        $this->postJson('/mail', $mail)
            ->assertOk()
            ->assertJson(['sent' => true]);

        Mail::assertQueued(function (ContactUsMail $em) use ($mail) {
            return $em->userMail === $mail['userMail'];
        });

        Mail::assertQueued(ContactUsMail::class, function (ContactUsMail $email) use ($mail) {
            return $email->hasTo(config('mail.from.address'));
        });
    }

    public function testUserCanSendMail()
    {
        $this->testUserCanSeeOwnedEmailAndNameInForm();
        $this->testGuestCanSendMail();
    }
}
