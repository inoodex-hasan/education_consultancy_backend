<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Student;
use App\Models\Application;
use App\Models\Payment;
use App\Models\Commission;
use App\Models\Setting;
use App\Models\Country;
use App\Models\University;
use App\Models\Course;
use App\Models\CourseIntake;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Bypass authorization
        \Illuminate\Support\Facades\Gate::before(function () {
            return true;
        });

        // Setup common data
        $this->country = Country::create(['name' => 'United Kingdom', 'status' => 1]);
        $this->university = University::create([
            'country_id' => $this->country->id,
            'name' => 'Oxford University',
            'status' => 1
        ]);
        $this->course = Course::create([
            'university_id' => $this->university->id,
            'name' => 'Computer Science',
            'status' => 1
        ]);
        $this->intake = CourseIntake::create([
            'course_id' => $this->course->id,
            'intake_name' => 'September 2026',
            'status' => 1
        ]);
    }

    public function test_commissions_are_created_when_payment_is_completed()
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password')]);
        $marketing = User::create(['name' => 'Marketing', 'email' => 'm@example.com', 'password' => bcrypt('password')]);
        $consultant = User::create(['name' => 'Consultant', 'email' => 'c@example.com', 'password' => bcrypt('password')]);
        $appOfficer = User::create(['name' => 'App Officer', 'email' => 'ao@example.com', 'password' => bcrypt('password')]);
        $accountant = User::create(['name' => 'Accountant', 'email' => 'acc@example.com', 'password' => bcrypt('password')]);

        Setting::create(['key' => 'commission_marketing_percent', 'value' => '5']);
        Setting::create(['key' => 'commission_consultant_percent', 'value' => '3']);
        Setting::create(['key' => 'commission_application_percent', 'value' => '2']);
        Setting::create(['key' => 'commission_accountant_percent', 'value' => '1.5']);

        $student = Student::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '01712345678',
            'assigned_marketing_id' => $marketing->id,
            'assigned_consultant_id' => $consultant->id,
            'assigned_application_id' => $appOfficer->id,
        ]);

        $application = Application::create([
            'student_id' => $student->id,
            'university_id' => $this->university->id,
            'course_id' => $this->course->id,
            'course_intake_id' => $this->intake->id,
            // 'application_id' is generated in booted()
            'total_fee' => 1000,
            'created_by' => $admin->id,
        ]);

        // Act: Record a completed payment
        $response = $this->actingAs($accountant)->post(route('admin.payments.store'), [
            'application_id' => $application->id,
            'amount' => 1000,
            'payment_type' => 'final',
            'payment_status' => 'completed',
            'payment_date' => now()->toDateString(),
        ]);

        $response->assertStatus(302);

        // Assert: Check if 4 commissions were created
        $this->assertEquals(4, Commission::count());

        $this->assertDatabaseHas('commissions', [
            'user_id' => $marketing->id,
            'role' => 'marketing',
            'amount' => 50.00,
        ]);

        $this->assertDatabaseHas('commissions', [
            'user_id' => $accountant->id,
            'role' => 'accountant',
            'amount' => 15.00,
        ]);
    }

    public function test_commissions_are_not_created_when_payment_is_pending()
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password')]);
        $marketing = User::create(['name' => 'Marketing', 'email' => 'm@example.com', 'password' => bcrypt('password')]);

        Setting::create(['key' => 'commission_marketing_percent', 'value' => '5']);

        $student = Student::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '01812345678',
            'assigned_marketing_id' => $marketing->id,
        ]);

        $application = Application::create([
            'student_id' => $student->id,
            'university_id' => $this->university->id,
            'course_id' => $this->course->id,
            'course_intake_id' => $this->intake->id,
            'total_fee' => 1000,
            'created_by' => $admin->id,
        ]);

        // Act: Record a pending payment
        $this->actingAs($admin)->post(route('admin.payments.store'), [
            'application_id' => $application->id,
            'amount' => 1000,
            'payment_type' => 'partial',
            'payment_status' => 'pending',
            'payment_date' => now()->toDateString(),
        ]);

        // Assert: No commissions should be created
        $this->assertEquals(0, Commission::count());
    }
}
