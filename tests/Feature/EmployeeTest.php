<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_employee()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $company = Company::factory()->create();

        $this->actingAs($admin);

        $response = $this->postJson('/api/employees', [
            'first_name' => 'Thiago',
            'last_name' => 'Zamprognio',
            'company_id' => $company->id,
            'email' => 'Thiago@teste.com',
            'phone' => '+1202-555-0198',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('employees', [
            'first_name' => 'Thiago',
            'last_name' => 'Zamprognio',
            'email' => 'Thiago@teste.com',
        ]);
    }

    public function test_employee_requires_first_and_last_name()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $company = Company::factory()->create();

        $this->actingAs($admin);

        $response = $this->postJson('/api/employees', [
            'company_id' => $company->id,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['first_name', 'last_name']);
    }

    public function test_admin_can_list_employees_with_pagination()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $company = Company::factory()->create();

        \App\Models\Employee::factory()->count(15)->create([
            'company_id' => $company->id,
        ]);

        $this->actingAs($admin);

        $response = $this->getJson('/api/employees');

        $response->assertStatus(200);
        $this->assertCount(10, $response->json('data'));
    }

    public function test_admin_can_view_single_employee()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $employee = \App\Models\Employee::factory()->create();

        $this->actingAs($admin);

        $response = $this->getJson("/api/employees/{$employee->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $employee->id,
                    'first_name' => $employee->first_name,
                ]
            ]);
    }

    public function test_admin_can_update_employee()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $employee = \App\Models\Employee::factory()->create();

        $this->actingAs($admin);

        $response = $this->putJson("/api/employees/{$employee->id}", [
            'first_name' => 'Updated',
            'last_name' => 'Name',
            'company_id' => $employee->company_id,
            'email' => 'updated@email.com',
            'phone' => '+1202-555-0198',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'first_name' => 'Updated',
        ]);
    }

    public function test_admin_can_delete_employee()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $employee = \App\Models\Employee::factory()->create();

        $this->actingAs($admin);

        $response = $this->deleteJson("/api/employees/{$employee->id}");

        $response->assertStatus(200);
        $response->assertJson(['message' => __('messages.employee_deleted')]);
        
        $this->assertSoftDeleted('employees', ['id' => $employee->id]);
    }

    public function test_invalid_email_is_rejected_for_employee()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $company = \App\Models\Company::factory()->create();

        $this->actingAs($admin);

        $response = $this->postJson('/api/employees', [
            'first_name' => 'Invalid',
            'last_name' => 'Email',
            'company_id' => $company->id,
            'email' => 'not-an-email',
            'phone' => '+123456789',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_non_admin_cannot_access_employee_routes()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $company = \App\Models\Company::factory()->create();

        $this->actingAs($user);

        $response = $this->postJson('/api/employees', [
            'first_name' => 'Blocked',
            'last_name' => 'User',
            'company_id' => $company->id,
            'email' => 'blocked@user.com',
            'phone' => '0000',
        ]);

        $response->assertStatus(403);
    }
}