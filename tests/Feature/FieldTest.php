<?php

namespace Tests\Feature;

use App\Models\Field;
use App\Models\Subscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FieldTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_update_a_field()
    {
        $field = Field::factory()->create([
            'title' => 'Field',
            'type' => 'boolean',
        ]);

        $response = $this->putJson("/api/fields/{$field->id}", [
            'title' => 'Updated Field',
            'type' => 'string',
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'subscriber_id',
                'title',
                'type',
            ]);

        $updated = $response->getOriginalContent();

        $this->assertDatabaseHas('fields', $updated);
    }

    public function test_it_can_delete_a_field()
    {
        $field = Field::factory()->create();

        $response = $this->deleteJson("/api/fields/{$field->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('fields', $field->toArray());
    }

    public function test_it_can_get_a_field()
    {
        $field = Field::factory()->create();

        $response = $this->getJson("/api/fields/{$field->id}");

        $response
            ->assertStatus(200)
            ->assertExactJson($field->toArray());

        $this->assertDatabaseHas('fields', $field->toArray());
    }
}
