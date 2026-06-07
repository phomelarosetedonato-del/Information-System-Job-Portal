<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkArrangementOption;

class WorkArrangementOptionSeeder extends Seeder
{
    public function run()
    {
        $options = [
            'On-site work',
            'Work-from-home / remote',
            'Hybrid (mix of remote and office)',
            'Field work',
            'Office-based work',
            'Flexible reporting schedule',
        ];
        foreach ($options as $name) {
            WorkArrangementOption::firstOrCreate(['name' => $name], ['active' => true]);
        }
    }
}
