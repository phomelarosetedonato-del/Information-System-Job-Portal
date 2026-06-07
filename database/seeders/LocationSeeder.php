<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            'Alaminos City',
            'Dagupan City',
            'San Carlos City',
            'Urdaneta City',
            'Lingayen',
            'Manaoag',
            'Bolinao',
            'Anda',
            'Mangaldan',
            'Sual',
            'Bani',
            'Agno',
            'Infanta',
            'Bugallon',
            'Labrador',
            'Other',
        ];

        foreach ($locations as $loc) {
            DB::table('locations')->updateOrInsert(
                ['name' => $loc],
                ['name' => $loc, 'is_active' => true]
            );
        }

        if ($this->command) {
            $this->command->info('Locations seeded successfully');
        }
    }
}
