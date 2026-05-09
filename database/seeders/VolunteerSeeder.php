<?php
namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Volunteer;
use App\Models\VolunteerShift;
use App\Models\ShiftRegistration;
use App\Models\User;
use Illuminate\Database\Seeder;

class VolunteerSeeder extends Seeder
{
    public function run(): void
    {
        $volunteerUsers = User::where('role', 'volunteer')->get();
        $campaigns      = Campaign::where('status', 'active')->take(4)->get();

        $skillSets = [
            ['teaching', 'arabic', 'child care'],
            ['driving', 'logistics', 'first aid'],
            ['medical', 'nursing', 'pharmacy'],
            ['social work', 'counseling', 'arabic'],
            ['photography', 'social media', 'design'],
        ];

        foreach ($volunteerUsers as $i => $user) {
            Volunteer::create([
                'user_id'      => $user->id,
                'phone'        => '01' . rand(0, 2) . str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT),
                'address'      => collect(['Cairo', 'Giza', 'Alexandria', 'Mansoura', 'Aswan'])->random() . ', Egypt',
                'date_of_birth'=> now()->subYears(rand(20, 45))->subDays(rand(0, 365)),
                'skills'       => $skillSets[$i % count($skillSets)],
                'bio'          => 'Passionate volunteer committed to making a positive difference in the community.',
                'status'       => 'approved',
                'total_hours'  => rand(4, 120),
            ]);
        }

        // Create shifts for each active campaign
        foreach ($campaigns as $campaign) {
            for ($s = 0; $s < 3; $s++) {
                $start = now()->addDays(rand(3, 30))->setHour(rand(8, 10))->setMinute(0)->setSecond(0);
                $shift = VolunteerShift::create([
                    'campaign_id'      => $campaign->id,
                    'title'            => collect([
                        'Field Distribution Day',
                        'Community Outreach',
                        'Logistics & Packing',
                        'Registration Desk',
                        'Medical Support',
                    ])->random(),
                    'description'      => 'Volunteers needed for ground operations. Training provided on the day.',
                    'location'         => $campaign->title . ' — Main Site',
                    'starts_at'        => $start,
                    'ends_at'          => $start->copy()->addHours(rand(4, 8)),
                    'max_volunteers'   => rand(5, 15),
                    'registered_count' => 0,
                ]);

                // Register some volunteers to shifts
                $volunteers = Volunteer::inRandomOrder()->take(rand(1, 3))->get();
                foreach ($volunteers as $vol) {
                    ShiftRegistration::firstOrCreate([
                        'volunteer_id'       => $vol->id,
                        'volunteer_shift_id' => $shift->id,
                    ], ['status' => 'registered']);

                    $shift->increment('registered_count');
                }
            }
        }
    }
}