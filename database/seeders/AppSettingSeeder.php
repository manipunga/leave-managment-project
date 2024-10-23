<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AppSetting::create([
            'total_leaves' => 15, // Default total allowed leaves
            'leave_calendar_start_date' => '2024-03-01', // Start date of the leave calendar year
        ]);
    }
}
