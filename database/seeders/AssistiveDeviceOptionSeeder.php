<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AssistiveDeviceOption;

class AssistiveDeviceOptionSeeder extends Seeder
{
    public function run()
    {
        $options = [
            'Wheelchair',
            'Cane',
            'Crutches',
            'Eyeglasses or corrective lenses',
            'Hearing aid',
            'Screen reader software (e.g., NVDA, JAWS)',
            'Screen magnifier software',
            'Speech-to-text or text-to-speech tools',
            'Ergonomic chair',
            'Ergonomic keyboard or mouse',
        ];
        foreach ($options as $name) {
            AssistiveDeviceOption::firstOrCreate(['name' => $name], ['active' => true]);
        }
    }
}
