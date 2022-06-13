<?php

namespace Tests\Feature;

use App\Models\Field;
use App\Models\Subscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriberTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_a_subscriber()
    {
        $subscriber = Subscriber::factory()->make([
            'email' => 'test@gmail.com',
        ]);

        $response = $this->postJson('/api/subscribers', $subscriber->toArray());

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'email',
                'name',
                'state',
            ]);

        $this->assertDatabaseHas('subscribers', $subscriber->toArray());
    }

    public function test_it_cannot_create_a_subscriber_with_unconfirmed_domain()
    {
        $subscriber = Subscriber::factory()->make([
            'email' => 'invalid@domainqwerty.xyz'
        ]);

        $response = $this->postJson('/api/subscribers', $subscriber->toArray());

        $response->assertStatus(422);

        $this->assertDatabaseMissing('subscribers', $subscriber->toArray());
    }

    public function test_it_can_update_a_subscriber()
    {
        $subscriber = Subscriber::factory()->create([
            'state' => 'active'
        ]);

        $response = $this->putJson("/api/subscribers/{$subscriber->id}", [
            'email' => 'tester@domain.com',
            'name' => 'Tester',
            'state' => 'unconfirmed',
        ]);

        $response
            ->assertStatus(200)
            ->assertExactJson([
                'id' => $subscriber->id,
                'email' => 'tester@domain.com',
                'name' => 'Tester',
                'state' => 'unconfirmed',
            ]);

        $this->assertDatabaseMissing('subscribers', $subscriber->toArray());

        $this->assertDatabaseHas('subscribers', [
            'id' => $subscriber->id,
            'email' => 'tester@domain.com',
            'name' => 'Tester',
            'state' => 'unconfirmed',
        ]);
    }

    public function test_it_cannot_update_a_subscriber_with_unconfirmed_domain()
    {
        $subscriber = Subscriber::factory()->create([
            'email' => 'valid@gmail.com',
            'state' => 'active'
        ]);

        $response = $this->putJson("/api/subscribers/{$subscriber->id}", [
            'email' => 'invalid',
            'name' => 'Tester',
            'state' => 'active',
        ]);

        $response->assertStatus(422);

        $this->assertDatabaseHas('subscribers', $subscriber->toArray());
    }

    public function test_it_can_delete_a_subscriber()
    {
        $subscriber = Subscriber::factory()->create();

        $response = $this->deleteJson("/api/subscribers/{$subscriber->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('subscribers', [
            'id' => $subscriber->id,
        ]);
    }

    public function test_it_can_list_subscribers()
    {
        Subscriber::factory()->count(10)->create();

        $response = $this->getJson('/api/subscribers');

        $response
            ->assertStatus(200)
            ->assertJsonCount(10, '*');
    }

    public function test_it_can_get_a_subscriber()
    {
        $subscriber = Subscriber::factory()->create();

        $response = $this->getJson("/api/subscribers/{$subscriber->id}");

        $response
            ->assertStatus(200)
            ->assertExactJson($subscriber->toArray());
    }

    public function test_it_can_add_a_field()
    {
        $subscriber = Subscriber::factory()->create();

        $response = $this->postJson("/api/subscribers/{$subscriber->id}/fields", [
            'title' => 'Test Field',
            'type' => 'string',
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'subscriber_id',
                'title',
                'type',
            ]);

        $created = $response->getOriginalContent();

        $this->assertDatabaseHas('fields', $created);
    }

    public function test_it_cannot_add_a_field_with_invalid_type()
    {
        $subscriber = Subscriber::factory()->create();

        $response = $this->postJson("/api/subscribers/{$subscriber->id}/fields", [
            'title' => 'Test Field',
            'type' => 'invalidtype',
        ]);

        $response->assertStatus(422);
    }

    public function test_it_can_set_fields()
    {
        $subscriber = Subscriber::factory()->create();

        $fields = Field::factory()
            ->for($subscriber)
            ->count(3)
            ->create();

        $newFields = Field::factory()
            ->for($subscriber)
            ->count(3)
            ->make();

        $response = $this->putJson("/api/subscribers/{$subscriber->id}/fields", $newFields->toArray());

        $response
            ->assertStatus(200)
            ->assertJson($newFields->toArray());

        foreach ($fields as $field) {
            $this->assertDatabaseMissing('fields', $field->toArray());
        }
    }

    public function test_it_cannot_set_fields_with_an_invalid_field()
    {
        $subscriber = Subscriber::factory()->create();

        $fields = Field::factory()
            ->for($subscriber)
            ->count(3)
            ->create();

        $newFields = Field::factory()
            ->for($subscriber)
            ->count(3)
            ->make();

        $newFields[0]->type = 'invalidtype';

        $response = $this->putJson("/api/subscribers/{$subscriber->id}/fields", $newFields->toArray());

        $response->assertStatus(422);

        foreach ($fields as $field) {
            $this->assertDatabaseHas('fields', $field->toArray());
        }

        foreach ($newFields as $field) {
            $this->assertDatabaseMissing('fields', $field->toArray());
        }
    }
}
