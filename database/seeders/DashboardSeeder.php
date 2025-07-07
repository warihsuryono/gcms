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
        Dashboard::create(['logo' => 'dashboard_images/01JWZMDAWSHK906GKDRKPJ94VW.jpg', 'tagline' => 'Welcome to your second home by the sea', 'running_text_1' => 'SEDAYU INDO GOLF - EXPERIENCE THE BRAND NEW GOLF COURSE AT JAKARTA', 'running_text_2' => 'Running Text 2', 'running_text_3' => 'Running Text 3', 'running_text_4' => 'Running Text 4', 'background' => 'dashboard_images/01JWZMDAYDQTJ2KS0CX1QJJY7G.jpg', 'widget_1' => 'dashboard_images/01JXA12FK6TE5MPFPFJ20Y46C2.jpg', 'widget_1_top' => 280, 'widget_1_left' => 1000, 'widget_2' => 'Widget 2', 'widget_2_top' => 150, 'widget_2_left' => 200, 'widget_3' => 'Widget 3', 'widget_3_top' => 250, 'widget_3_left' => 300]);
    }
}
