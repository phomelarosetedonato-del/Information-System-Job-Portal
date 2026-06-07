# 📋 COMMUNITY PWD STATISTICS - HOW TO ADD A NEW YEAR

**Document Date**: December 5, 2025

This guide shows all the methods to add a new year (e.g., 2026) to the Community PWD Statistics system.

---

## 🎯 Quick Reference

There are **3 main methods** to add a new year:

1. **Via Web UI** (Easiest - No coding required)
2. **Via Edit Page Form** (Modify existing interface)
3. **Via Database Seeder** (Programmatic - Recommended for bulk imports)

---

## Method 1: ✅ Via Web UI - Add Year Data Manually

**Best for**: Small updates or manual data entry

### Steps:

1. **Access the Statistics Page**
   - Go to: `http://127.0.0.1:8000/admin/community-statistics`
   - Login as admin

2. **Year Not Visible Yet?**
   - The year selector currently shows available years from database
   - To add 2026 data, you need to create the records first

3. **Create Records for 2026**
   - The system uses the year selector which auto-populates from database
   - You can manually insert records via the edit page for a similar year

4. **Quick Solution - Clone 2025 Data to 2026**
   - See **Method 3** for bulk operations

---

## Method 2: ✅ Via Edit Page Form

**Best for**: Modifying existing data or adding records manually

### Current Edit Page Features:

**URL**: `http://127.0.0.1:8000/admin/community-statistics/{year}/edit`

**What You Can Do**:
- ✅ Edit unemployed/employed counts
- ✅ See real-time calculations
- ✅ Export data to CSV
- ✅ Import data from CSV

### To Add 2026 Data:

**Option A: If 2026 records already exist in database**
1. Navigate to: `/admin/community-statistics/2026/edit`
2. Edit the employment counts for each disability type
3. Click "Save Changes"
4. Data updates in real-time

**Option B: Create 2026 records first (see Method 3)**

### CSV Import Feature on Edit Page:

The edit page includes CSV import:
1. Go to any year's edit page
2. Scroll to "Import from CSV" section
3. Select CSV file with format:
   ```
   disability_type,unemployed_count,employed_count
   Deaf or Hard of Hearing,67,34
   Mental Disability,217,12
   ...
   ```
4. Click "Import"
5. Data loads into form
6. Click "Save Changes"

---

## Method 3: ✅ Via Database Seeder - RECOMMENDED

**Best for**: Adding new years with data or bulk operations

### Step-by-Step Guide:

### Option A: Create a New Seeder for 2026

**1. Create Seeder File**

Create a new file: `database/seeders/CommunityPwdStats2026Seeder.php`

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
            [
                'year' => 2026,
                'disability_type' => 'Deaf or Hard of Hearing',
                'unemployed_count' => 70,      // Your 2026 data
                'employed_count' => 36,        // Your 2026 data
            ],
            [
                'year' => 2026,
                'disability_type' => 'Intellectual Disability',
                'unemployed_count' => 86,
                'employed_count' => 2,
            ],
            // ... Add all 10 disability types
        ];

        foreach ($data as $record) {
            CommunityPwdStat::updateOrCreate(
                [
                    'year' => $record['year'],
                    'disability_type' => $record['disability_type'],
                ],
                [
                    'unemployed_count' => $record['unemployed_count'],
                    'employed_count' => $record['employed_count'],
                ]
            );
        }
    }
}
```

**2. Run the Seeder**

```bash
php artisan db:seed --class=CommunityPwdStats2026Seeder
```

**Result**: All 10 disability types added for 2026 ✅

---

### Option B: Modify Existing Seeder to Support Multiple Years

Edit: `database/seeders/CommunityPwdStatsSeeder.php`

Change from:
```php
'year' => 2025,
```

To:
```php
'year' => 2026,  // Or make it dynamic
```

Then run:
```bash
php artisan db:seed --class=CommunityPwdStatsSeeder
```

---

### Option C: Clone 2025 Data to 2026

Create a seeder to copy existing data:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CommunityPwdStat;

class CloneCommunityPwdStatsSeeder extends Seeder
{
    public function run(): void
    {
        // Get all 2025 records
        $records2025 = CommunityPwdStat::where('year', 2025)->get();

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

        echo "✅ Data cloned from 2025 to 2026\n";
    }
}
```

Run with:
```bash
php artisan db:seed --class=CloneCommunityPwdStatsSeeder
```

---

## Method 4: ✅ Via PHP Artisan Tinker (Quick Testing)

**Best for**: Quick testing or one-off additions

### Command:

```bash
php artisan tinker
```

### Then type:

```php
$disabilities = [
    'Deaf or Hard of Hearing',
    'Intellectual Disability',
    'Learning Disability',
    'Mental Disability',
    'Physical Disability (Orthopedic)',
    'Psychosocial Disability',
    'Speech and Language Impairment',
    'Visual Disability',
    'Cancer (RA11215)',
    'Rare Disease (RA 11215)'
];

foreach ($disabilities as $type) {
    \App\Models\CommunityPwdStat::create([
        'year' => 2026,
        'disability_type' => $type,
        'unemployed_count' => 100,  // Your value
        'employed_count' => 50,      // Your value
    ]);
}

// Verify
\App\Models\CommunityPwdStat::where('year', 2026)->count();
```

---

## Method 5: ✅ Via Artisan Command (Advanced)

Create a custom artisan command:

Create: `app/Console/Commands/AddCommunityPwdStatsYear.php`

```php
<?php

namespace App\Console\Commands;

use App\Models\CommunityPwdStat;
use Illuminate\Console\Command;

class AddCommunityPwdStatsYear extends Command
{
    protected $signature = 'community:add-year {year} {--clone-from=}';
    protected $description = 'Add a new year to community PWD statistics';

    public function handle()
    {
        $year = $this->argument('year');
        $cloneFrom = $this->option('clone-from');

        if ($cloneFrom) {
            // Clone from existing year
            $records = CommunityPwdStat::where('year', $cloneFrom)->get();
            foreach ($records as $record) {
                CommunityPwdStat::updateOrCreate(
                    ['year' => $year, 'disability_type' => $record->disability_type],
                    ['unemployed_count' => $record->unemployed_count, 'employed_count' => $record->employed_count]
                );
            }
            $this->info("✅ Cloned data from $cloneFrom to $year");
        } else {
            $this->info("No data provided. Use --clone-from={year} to clone from existing year");
        }
    }
}
```

Run with:
```bash
php artisan community:add-year 2026 --clone-from=2025
```

---

## 📊 Database Structure Reference

### Table: community_pwd_stats

```sql
CREATE TABLE community_pwd_stats (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    year INT NOT NULL,
    disability_type VARCHAR(255) NOT NULL,
    unemployed_count INT DEFAULT 0,
    employed_count INT DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    UNIQUE KEY (year, disability_type),
    INDEX (year)
);
```

### Key Points:
- ✅ `year` field stores the year (2025, 2026, etc.)
- ✅ `disability_type` is the category
- ✅ `unemployed_count` and `employed_count` are the metrics
- ✅ Unique constraint on (year + disability_type) prevents duplicates
- ✅ Year index speeds up queries

---

## 🔄 System Automatically Recognizes New Years

**Important**: Once you add records for a new year (2026), the system automatically:

1. ✅ Shows 2026 in the year selector dropdown
2. ✅ Calculates totals for 2026
3. ✅ Dashboard updates to show 2026 data
4. ✅ All reports include 2026

### Example:

When you add 2026 data, this happens:

**Before Adding 2026**:
- Year Selector: [2025]
- Dashboard: Shows 2025 data

**After Adding 2026**:
- Year Selector: [2026] [2025]  ← 2026 appears automatically!
- Dashboard: Can switch to 2026 data
- Reports: Include 2026

---

## 📋 10 Disability Types to Include

Every year must have records for all 10 types:

1. Deaf or Hard of Hearing
2. Intellectual Disability
3. Learning Disability
4. Mental Disability
5. Physical Disability (Orthopedic)
6. Psychosocial Disability
7. Speech and Language Impairment
8. Visual Disability
9. Cancer (RA11215)
10. Rare Disease (RA 11215)

---

## ✅ Verification Checklist

After adding 2026 data, verify:

- [ ] 10 records created for 2026
- [ ] Year selector shows 2026
- [ ] Can navigate to `/admin/community-statistics/2026/edit`
- [ ] Edit page loads all 10 disability types
- [ ] Dashboard shows 2026 option
- [ ] Data calculates correctly
- [ ] No duplicate records

**Check with**:
```bash
php artisan tinker
\App\Models\CommunityPwdStat::where('year', 2026)->count()
# Should return: 10
```

---

## 🎯 Recommended Method

**For Adding a Single Year**: Use **Method 3 - Option C (Clone)**

```bash
# 1. Create the seeder (copy code from Option C above)
# 2. Run it
php artisan db:seed --class=CloneCommunityPwdStatsSeeder
# 3. Verify
php artisan tinker
\App\Models\CommunityPwdStat::where('year', 2026)->count()
```

**Advantages**:
- ✅ Quick and simple
- ✅ Prevents missing disability types
- ✅ Maintains data consistency
- ✅ Can then edit individual values in UI

---

## 🔗 Related URLs

Once 2026 data is added, access it at:

- **Main Page**: `http://127.0.0.1:8000/admin/community-statistics`
  - Year selector shows 2026
  - Can switch between years

- **Edit Page**: `http://127.0.0.1:8000/admin/community-statistics/2026/edit`
  - Edit all 10 records
  - Real-time totals update
  - CSV import/export available

- **API Endpoint**: `http://127.0.0.1:8000/api/community-stats/2026`
  - Returns JSON with all 2026 data
  - Used by frontend for calculations

---

## 📝 Summary

| Method | Difficulty | Best For | Time |
|--------|-----------|----------|------|
| UI Manual | Easy | Individual updates | Medium |
| Edit Page | Easy | Modify & CSV | Medium |
| Seeder (Clone) | Easy | Quick year addition | Fast ⭐ |
| Tinker | Medium | Testing | Fast |
| Custom Command | Hard | Automation | Varies |

**Recommended**: Use **Seeder Clone Method** for new years.

---

**Next Steps**: Choose your method and follow the steps above. The system will automatically recognize the new year!
