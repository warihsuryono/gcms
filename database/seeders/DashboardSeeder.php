<?php

namespace Database\Seeders;

use App\Models\Dashboard;
use Illuminate\Database\Seeder;

class DashboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Dashboard::create(['logo' => 'logo.png', 'tagline' => 'Welcome to Our Dashboard', 'running_text_1' => 'Running Text 1', 'running_text_2' => 'Running Text 2', 'running_text_3' => 'Running Text 3', 'running_text_4' => 'Running Text 4', 'background' => 'background.jpg', 'widget_1' => 'Widget 1', 'widget_1_top' => 50, 'widget_1_left' => 100, 'widget_2' => 'Widget 2', 'widget_2_top' => 150, 'widget_2_left' => 200, 'widget_3' => 'Widget 3', 'widget_3_top' => 250, 'widget_3_left' => 300]);
    }
}
