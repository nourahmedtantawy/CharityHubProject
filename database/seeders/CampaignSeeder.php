<?php
namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CampaignSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        $campaigns = [
            [
                'title'       => 'Clean Water for Sinai Villages',
                'category'    => 'health',
                'goal_amount' => 150000,
                'raised_amount'=> 87500,
                'status'      => 'active',
                'deadline'    => now()->addDays(45),
                'description' => 'Providing clean drinking water access to 12 remote villages in South Sinai through solar-powered pumping stations.',
                'content'     => '<h2>About This Campaign</h2><p>Over 8,000 residents in remote Sinai villages lack access to clean water. This campaign funds solar-powered water pumping stations that will serve communities for decades.</p><h2>How Funds Are Used</h2><ul><li>40% — Solar pumping equipment</li><li>30% — Pipeline installation</li><li>20% — Storage tanks</li><li>10% — Community training</li></ul>',
            ],
            [
                'title'       => 'School Supplies for Upper Egypt',
                'category'    => 'education',
                'goal_amount' => 80000,
                'raised_amount'=> 62000,
                'status'      => 'active',
                'deadline'    => now()->addDays(20),
                'description' => 'Distributing school bags, books, and stationery to 2,000 underprivileged students in Aswan and Luxor.',
                'content'     => '<h2>Impact</h2><p>Education is the foundation of change. This campaign supplies complete school kits to children whose families cannot afford basic supplies, ensuring no child misses school due to poverty.</p>',
            ],
            [
                'title'       => 'Orphan Sponsorship — Cairo',
                'category'    => 'orphans',
                'goal_amount' => 200000,
                'raised_amount'=> 134000,
                'status'      => 'active',
                'deadline'    => now()->addDays(60),
                'description' => 'Monthly sponsorship covering food, education, medical care, and psychological support for 100 orphans in Cairo.',
                'content'     => '<h2>What We Provide</h2><p>Each sponsored orphan receives monthly food baskets, school enrollment support, medical checkups, and recreational activities. Your donation directly transforms a child\'s life.</p>',
            ],
            [
                'title'       => 'Winter Blankets Campaign',
                'category'    => 'shelter',
                'goal_amount' => 50000,
                'raised_amount'=> 50000,
                'status'      => 'completed',
                'deadline'    => now()->subDays(5),
                'description' => 'Distributed 5,000 winter blankets and warm clothing to families in need across Delta governorates.',
                'content'     => '<h2>Campaign Complete!</h2><p>Thanks to 847 donors, we successfully distributed winter supplies to 5,000 families before the cold season. This campaign is now closed.</p>',
            ],
            [
                'title'       => 'Medical Convoy — Fayoum',
                'category'    => 'health',
                'goal_amount' => 120000,
                'raised_amount'=> 45000,
                'status'      => 'active',
                'deadline'    => now()->addDays(35),
                'description' => 'Funding free medical examinations, medicines, and specialist consultations for 3,000 low-income patients.',
                'content'     => '<h2>Medical Access for All</h2><p>Thousands in Fayoum governorate cannot afford specialist care. This convoy brings cardiologists, ophthalmologists, dentists, and pediatricians directly to underserved communities.</p>',
            ],
            [
                'title'       => 'Reforestation — Nile Delta',
                'category'    => 'environment',
                'goal_amount' => 90000,
                'raised_amount'=> 28000,
                'status'      => 'active',
                'deadline'    => now()->addDays(90),
                'description' => 'Planting 50,000 native trees across degraded land in the Nile Delta to combat desertification.',
                'content'     => '<h2>Greening Egypt</h2><p>The Nile Delta faces serious desertification threats. Every 100 EGP plants 5 trees and trains local farmers in sustainable land management.</p>',
            ],
            [
                'title'       => 'Food Baskets — Ramadan 2026',
                'category'    => 'food',
                'goal_amount' => 300000,
                'raised_amount'=> 198000,
                'status'      => 'active',
                'deadline'    => now()->addDays(15),
                'description' => 'Delivering Ramadan food baskets containing 20kg of essential goods to 6,000 needy families.',
                'content'     => '<h2>Feed a Family This Ramadan</h2><p>Each basket feeds a family of 5 for the entire holy month. Items include rice, oil, sugar, lentils, pasta, dates, and tomato paste.</p>',
            ],
            [
                'title'       => 'Disaster Relief — Flash Floods',
                'category'    => 'disaster',
                'goal_amount' => 500000,
                'raised_amount'=> 312000,
                'status'      => 'active',
                'deadline'    => now()->addDays(10),
                'description' => 'Emergency relief for 800 families displaced by flash floods in Red Sea governorate.',
                'content'     => '<h2>Urgent Help Needed</h2><p>Families lost everything. Funds provide emergency shelter, food, clean water, and medical care for flood survivors while permanent housing solutions are arranged.</p>',
            ],
        ];

        foreach ($campaigns as $data) {
            Campaign::create([
                'title'        => $data['title'],
                'slug'         => Str::slug($data['title']),
                'description'  => $data['description'],
                'content'      => $data['content'],
                'category'     => $data['category'],
                'goal_amount'  => $data['goal_amount'],
                'raised_amount'=> $data['raised_amount'],
                'currency'     => 'EGP',
                'deadline'     => $data['deadline'],
                'status'       => $data['status'],
                'created_by'   => $admin->id,
                'meta_title'   => $data['title'] . ' | CharityHub',
                'meta_description' => $data['description'],
            ]);
        }
    }
}