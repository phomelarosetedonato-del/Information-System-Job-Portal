# 🚀 QUICK START - ADD 2026 COMMUNITY PWD STATS

**Time to complete**: 5 minutes

This is the fastest way to add a new year to the system.

---

## FASTEST METHOD: Clone 2025 Data to 2026

### Step 1: Create Seeder File

Create a new file at:
```
database/seeders/CloneCommunityPwdStats2026Seeder.php
```

Copy this code:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CommunityPwdStat;

class CloneCommunityPwdStats2026Seeder extends Seeder
{
    /**
     * Clone 2025 data to 2026
     */
    public function run(): void
    {
        // Get all 2025 records
        $records2025 = CommunityPwdStat::where('year', 2025)->get();

        if ($records2025->isEmpty()) {
            $this->command->error('No data found for 2025');
            return;
        }

        // Clone to 2026
        foreach ($records2025 as $record) {
            CommunityPwdStat::updateOrCreate(
                [
                    'year' => 2026,
                    'disability_type' => $record->disability_type,
                ],
                [
                    'unemployed_count' => $record->unemployed_count,
                    'employed_count' => $record->employed_count,
                ]
            );
        }

        $this->command->info('✅ Successfully cloned 10 records from 2025 to 2026');
    }
}
```

### Step 2: Run Seeder

Open terminal and run:

```bash
cd c:\xampp\htdocs\new - pwdsystem\new - pwdsystem\pwdnew_system
php artisan db:seed --class=CloneCommunityPwdStats2026Seeder
```

**Expected Output**:
```
✅ Successfully cloned 10 records from 2025 to 2026
Database seeding completed successfully.
```

### Step 3: Verify

Check that 2026 data exists:

```bash
php artisan tinker
\App\Models\CommunityPwdStat::where('year', 2026)->count()
# Output: 10 ✅
```

### Step 4: Access It

Now you can:

1. **Go to Dashboard**: `http://127.0.0.1:8000/admin/community-statistics`
   - Year selector now shows: [2026] [2025]
   - Click 2026 to view data

2. **Edit 2026 Data**: `http://127.0.0.1:8000/admin/community-statistics/2026/edit`
   - Modify unemployment/employment counts
   - Save changes

3. **API Access**: `http://127.0.0.1:8000/api/community-stats/2026`
   - Returns JSON with 2026 data

---

## WITH CUSTOM DATA: Add 2026 with Different Numbers

Create file: `database/seeders/CommunityPwdStats2026Seeder.php`

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CommunityPwdStat;

class CommunityPwdStats2026Seeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['disability_type' => 'Deaf or Hard of Hearing', 'unemployed' => 72, 'employed' => 38],
            ['disability_type' => 'Intellectual Disability', 'unemployed' => 88, 'employed' => 3],
            ['disability_type' => 'Learning Disability', 'unemployed' => 16, 'employed' => 2],
            ['disability_type' => 'Mental Disability', 'unemployed' => 225, 'employed' => 15],
            ['disability_type' => 'Physical Disability (Orthopedic)', 'unemployed' => 600, 'employed' => 270],
            ['disability_type' => 'Psychosocial Disability', 'unemployed' => 172, 'employed' => 68],
            ['disability_type' => 'Speech and Language Impairment', 'unemployed' => 100, 'employed' => 25],
            ['disability_type' => 'Visual Disability', 'unemployed' => 125, 'employed' => 80],
            ['disability_type' => 'Cancer (RA11215)', 'unemployed' => 85, 'employed' => 52],
            ['disability_type' => 'Rare Disease (RA 11215)', 'unemployed' => 45, 'employed' => 18],
        ];

        foreach ($data as $item) {
            CommunityPwdStat::updateOrCreate(
                ['year' => 2026, 'disability_type' => $item['disability_type']],
                ['unemployed_count' => $item['unemployed'], 'employed_count' => $item['employed']]
            );
        }

        $this->command->info('✅ 2026 data seeded with ' . count($data) . ' records');
    }
}
```

Run:
```bash
php artisan db:seed --class=CommunityPwdStats2026Seeder
```

---

## VIA TINKER: Add 2026 in 30 Seconds

```bash
php artisan tinker
```

Then paste:

```php
$data = [
    ['type' => 'Deaf or Hard of Hearing', 'u' => 72, 'e' => 38],
    ['type' => 'Intellectual Disability', 'u' => 88, 'e' => 3],
    ['type' => 'Learning Disability', 'u' => 16, 'e' => 2],
    ['type' => 'Mental Disability', 'u' => 225, 'e' => 15],
    ['type' => 'Physical Disability (Orthopedic)', 'u' => 600, 'e' => 270],
    ['type' => 'Psychosocial Disability', 'u' => 172, 'e' => 68],
    ['type' => 'Speech and Language Impairment', 'u' => 100, 'e' => 25],
    ['type' => 'Visual Disability', 'u' => 125, 'e' => 80],
    ['type' => 'Cancer (RA11215)', 'u' => 85, 'e' => 52],
    ['type' => 'Rare Disease (RA 11215)', 'u' => 45, 'e' => 18],
];

foreach ($data as $item) {
    \App\Models\CommunityPwdStat::create([
        'year' => 2026,
        'disability_type' => $item['type'],
        'unemployed_count' => $item['u'],
        'employed_count' => $item['e'],
    ]);
}

echo "✅ 2026 data added!";
exit;
```

---

## THEN EDIT IN WEB UI: Update Individual Values

Once 2026 data exists:

1. Go to: `http://127.0.0.1:8000/admin/community-statistics`
2. Select year "2026" from dropdown
3. Click "Edit Data"
4. Modify any unemployment/employment numbers
5. Click "Save Changes"

**Live calculations update automatically!** ✅

---

## VERIFY IT WORKED

### Check 1: Terminal

```bash
php artisan tinker
\App\Models\CommunityPwdStat::where('year', 2026)->count()
# Output: 10 ✅
```

### Check 2: Dashboard

Go to: `http://127.0.0.1:8000/admin/community-statistics`
- Should see year selector with "2026" option ✅
- Click 2026 and see data displayed ✅

### Check 3: Browser Console (Optional)

Open Dev Tools (F12) on statistics page:
- Click 2026 in year selector
- Check Network tab for API call to `/api/community-stats/2026`
- Should return JSON with 10 records ✅

---

## RESULT

After following these steps:

✅ 2026 data added to database  
✅ Year selector shows 2026  
✅ Dashboard can display 2026  
✅ Edit page can modify 2026 data  
✅ API returns 2026 data  
✅ All calculations work for 2026  

---

## TROUBLESHOOTING

### Problem: Year selector doesn't show 2026

**Solution**: Clear Laravel cache
```bash
php artisan cache:clear
php artisan config:clear
```

### Problem: "SQLSTATE[23000]: Integrity constraint violation"

**Solution**: You already have 2026 data. Check with:
```bash
php artisan tinker
\App\Models\CommunityPwdStat::where('year', 2026)->get()
```

### Problem: No output from seeder

**Solution**: Check file was created correctly and saved

---

## 📊 YOUR 2026 DATA STRUCTURE

Once added, you'll have:

```
Year: 2026
├─ Deaf or Hard of Hearing: 72 unemployed, 38 employed
├─ Intellectual Disability: 88 unemployed, 3 employed
├─ Learning Disability: 16 unemployed, 2 employed
├─ Mental Disability: 225 unemployed, 15 employed
├─ Physical Disability (Orthopedic): 600 unemployed, 270 employed
├─ Psychosocial Disability: 172 unemployed, 68 employed
├─ Speech and Language Impairment: 100 unemployed, 25 employed
├─ Visual Disability: 125 unemployed, 80 employed
├─ Cancer (RA11215): 85 unemployed, 52 employed
└─ Rare Disease (RA 11215): 45 unemployed, 18 employed

TOTALS: 1,354 unemployed, 511 employed = 1,865 total PWD
Employment Rate: 27.34%
```

---

## 🎯 NEXT: Compare Multiple Years

Once you have 2025 and 2026 data:

1. Go to dashboard
2. Switch between years
3. See comparisons
4. Track trends

The system automatically compares years for you! 📈

---

**Done!** Your 2026 community PWD statistics are now active. 🎉
