<?php
namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\ImpactReport;
use App\Models\Beneficiary;
use Illuminate\Database\Seeder;

class ImpactReportSeeder extends Seeder
{
    public function run(): void
    {
        $completed = Campaign::where('status', 'completed')->first();
        if (!$completed) return;

        $report = ImpactReport::create([
            'campaign_id'         => $completed->id,
            'title'               => 'Winter Blankets — Final Impact Report',
            'summary'             => '5,000 families received warm blankets and clothing across 8 Delta governorates.',
            'content'             => '<h2>Mission Accomplished</h2><p>Thanks to 847 donors who contributed over 50,000 EGP, our team of 32 volunteers delivered winter supplies to families in need before temperatures dropped.</p><h2>By the Numbers</h2><ul><li>5,000 blankets distributed</li><li>2,300 winter jackets</li><li>8 governorates reached</li><li>32 volunteers deployed</li><li>847 donors contributed</li></ul>',
            'beneficiaries_count' => 5000,
            'report_date'         => now()->subDays(3),
            'is_published'        => true,
        ]);

        // Beneficiary locations across Egypt
        $locations = [
            ['name' => 'Kafr El-Sheikh families', 'location_name' => 'Kafr El-Sheikh',   'lat' => 31.1107, 'lng' => 30.9388],
            ['name' => 'Dakahlia beneficiaries',  'location_name' => 'Mansoura',          'lat' => 31.0364, 'lng' => 31.3807],
            ['name' => 'Gharbia distribution',    'location_name' => 'Tanta',             'lat' => 30.7865, 'lng' => 31.0004],
            ['name' => 'Beheira families',         'location_name' => 'Damanhur',         'lat' => 31.0344, 'lng' => 30.4680],
            ['name' => 'Sharqia beneficiaries',   'location_name' => 'Zagazig',           'lat' => 30.5877, 'lng' => 31.5021],
        ];

        foreach ($locations as $loc) {
            Beneficiary::create([
                'impact_report_id' => $report->id,
                'name'             => $loc['name'],
                'location_name'    => $loc['location_name'],
                'latitude'         => $loc['lat'],
                'longitude'        => $loc['lng'],
                'description'      => 'Families received blankets and winter clothing packages.',
            ]);
        }
    }
}