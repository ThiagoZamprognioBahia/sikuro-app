<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_company_with_valid_logo()
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->actingAs($admin);

        $response = $this->postJson('/api/companies', [
            'name' => 'Test Company',
            'email' => 'company@test.com',
            'website' => 'https://company.com',
            'logo_path' => UploadedFile::fake()->image('logo.jpg', 150, 150),
        ]);

        $response->assertStatus(201);

        $response->assertJson([
            'data' => [
                'name' => 'Test Company',
                'email' => 'company@test.com',
                'website' => 'https://company.com',
            ]
        ]);

        $this->assertDatabaseHas('companies', [
            'name' => 'Test Company',
            'email' => 'company@test.com',
        ]);
    }

    public function test_logo_must_be_at_least_100x100()
    {
        Storage::fake('public');

        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin);

        $response = $this->postJson('/api/companies', [
            'name' => 'Test Company',
            'email' => 'company@test.com',
            'website' => 'https://company.com',
            'logo_path' => UploadedFile::fake()->image('small.jpg', 50, 50),
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['logo_path']);
    }

    public function test_company_name_is_required()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin);

        $response = $this->postJson('/api/companies', [
            'email' => 'company@test.com',
            'website' => 'https://company.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_admin_can_list_companies_with_pagination()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);

        \App\Models\Company::factory()->count(15)->create();

        $response = $this->getJson('/api/companies');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
            'links',
            'meta',
        ]);

        $this->assertCount(10, $response->json('data'));
    }

    public function test_admin_can_view_single_company()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $company = \App\Models\Company::factory()->create();

        $this->actingAs($admin);

        $response = $this->getJson("/api/companies/{$company->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $company->id,
                    'name' => $company->name,
                ]
            ]);
    }

   public function test_admin_can_update_company()
    {
        Storage::fake('public');

        $admin = User::factory()->create(['is_admin' => true]);
        $company = \App\Models\Company::factory()->create();

        $this->actingAs($admin);

        $response = $this->putJson("/api/companies/{$company->id}", [
            'name' => 'Updated Company',
            'email' => 'updated@test.com',
            'website' => 'https://updated.com',
            'logo_path' => UploadedFile::fake()->image('new_logo.jpg', 150, 150),
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'data' => [
                        'name' => 'Updated Company',
                        'email' => 'updated@test.com',
                    ]
                ]);

        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => 'Updated Company',
        ]);
    }

    public function test_admin_can_delete_company()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $company = \App\Models\Company::factory()->create();

        $this->actingAs($admin);

        $response = $this->deleteJson("/api/companies/{$company->id}");

        $response->assertStatus(200);
        $response->assertJson(['message' => __('messages.company_deleted')]);

        $this->assertSoftDeleted('companies', ['id' => $company->id]);
    }

    public function test_website_field_must_be_valid_url()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin);

        $response = $this->postJson('/api/companies', [
            'name' => 'Test Company',
            'email' => 'company@test.com',
            'website' => 'invalid-url',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['website']);
    }

    public function test_non_admin_cannot_access_company_routes()
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user);

        $response = $this->postJson('/api/companies', [
            'name' => 'Blocked Co',
            'email' => 'no@access.com',
            'website' => 'https://blocked.com',
        ]);

        $response->assertStatus(403);
    }
}