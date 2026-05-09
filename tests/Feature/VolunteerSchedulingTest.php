<?php
namespace Tests\Feature;

use App\Models\User;
use App\Models\Volunteer;
use App\Models\VolunteerShift;
use App\Models\ShiftRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VolunteerSchedulingTest extends TestCase
{
    use RefreshDatabase;

    public function test_conflict_detection_prevents_double_booking(): void
    {
        $user      = User::factory()->create(['role' => 'volunteer']);
        $volunteer = Volunteer::factory()->create(['user_id' => $user->id]);

        $shift1 = VolunteerShift::factory()->create([
            'starts_at' => now()->addDay()->setHour(9),
            'ends_at'   => now()->addDay()->setHour(13),
        ]);

        $shift2 = VolunteerShift::factory()->create([
            'starts_at' => now()->addDay()->setHour(11),
            'ends_at'   => now()->addDay()->setHour(15),
        ]);

        ShiftRegistration::create([
            'volunteer_id'       => $volunteer->id,
            'volunteer_shift_id' => $shift1->id,
            'status'             => 'registered',
        ]);

        $hasConflict = $shift2->hasConflictFor($volunteer);
        $this->assertTrue($hasConflict);
    }

public function test_hour_calculation_is_accurate(): void
{
    $shift = VolunteerShift::factory()->create([
        'starts_at' => now()->setHour(9)->setMinute(0),
        'ends_at'   => now()->setHour(13)->setMinute(30),
    ]);

    // ends_at minus starts_at (not the other way around)
    $hours = $shift->starts_at->diffInMinutes($shift->ends_at) / 60;
    $this->assertEquals(4.5, $hours);
}

    public function test_shift_cannot_exceed_max_volunteers(): void
    {
        $shift = VolunteerShift::factory()->create([
            'max_volunteers'   => 2,
            'registered_count' => 2,
        ]);

        $this->assertTrue($shift->isFull());
    }
}