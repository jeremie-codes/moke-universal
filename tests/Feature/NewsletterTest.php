<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsletterTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_visitor_can_subscribe_to_the_newsletter(): void
    {
        $this->post(route('newsletter.store'), ['email' => 'lecteur@example.com'])
            ->assertRedirect(route('home'))
            ->assertSessionHas('newsletter_success');

        $this->assertDatabaseHas('newsletters', [
            'email' => 'lecteur@example.com',
        ]);
    }

    public function test_an_email_can_only_be_subscribed_once(): void
    {
        $this->post(route('newsletter.store'), ['email' => 'lecteur@example.com']);

        $this->from(route('home'))
            ->post(route('newsletter.store'), ['email' => 'lecteur@example.com'])
            ->assertRedirect(route('home'))
            ->assertSessionHasErrors('email');
    }
}
