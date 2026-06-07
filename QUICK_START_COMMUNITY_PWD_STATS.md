# Community PWD Statistics - Quick Start Guide

## 🚀 Get Started in 2 Minutes

### Access the System
1. Log in as admin
2. Go to sidebar: **Community PWD Stats** (under User Management)
3. You're in! 🎉

### View Dashboard
- **Current Year:** 2025 (automatically selected)
- **4 Cards Show:**
  - Total Unemployed PWD: 1,455
  - Total Employed PWD: 513
  - Total PWD: 1,968
  - Employment Rate: 26.06%
- **Table Shows:** All 10 disability types with breakdown

### Edit Data (Live Calculations!)
1. Click **"Edit Data"** button
2. In the form, update any number (e.g., change 67 → 70)
3. **Watch:** All totals update instantly as you type
4. Click **"Save Changes"** when done
5. See confirmation: "Community statistics updated successfully"

### Export to Excel
1. Click **"Export CSV"** button
2. File downloads: `community-pwd-stats-2025.csv`
3. Open in Excel, Google Sheets, or any spreadsheet app

### Import from Excel
1. Click **"Edit Data"**
2. Scroll to "Import from CSV" section
3. Select CSV file from computer
4. Click **"Import"**
5. Form auto-fills with CSV data
6. Click **"Save Changes"** to update database

---

## 📊 The 10 Disability Types

| # | Type | Current Unemployed | Current Employed |
|---|------|-------------------|------------------|
| 1 | Deaf or Hard of Hearing | 67 | 34 |
| 2 | Intellectual Disability | 84 | 1 |
| 3 | Learning Disability | 14 | 1 |
| 4 | Mental Disability | 217 | 12 |
| 5 | Physical Disability (Orthopedic) | 581 | 252 |
| 6 | Psychosocial Disability | 164 | 62 |
| 7 | Speech and Language Impairment | 92 | 21 |
| 8 | Visual Disability | 118 | 71 |
| 9 | Cancer (RA11215) | 78 | 46 |
| 10 | Rare Disease (RA 11215) | 40 | 13 |

---

## 🎯 Dashboard Widget

When you view the **Admin Dashboard**, you'll see:

**Community PWD Stats Card** (blue, with chart icon)
- Shows: 1,968 total PWD
- Shows: 1,455 unemployed
- Click arrow → Goes to full stats page

**Quick Action Card** (under "Quick Actions")
- Shows: "Community Stats - Manage PWD statistics"
- Click → Goes to full stats page

---

## 💡 Tips & Tricks

### Live Calculation Demo
1. Go to "Edit Data"
2. Change Unemployed: 67 → 100
3. Change Employed: 34 → 50
4. **See instantly:**
   - That row Total: 150
   - That row Rate: 33.3%
   - Overall Total PWD increases
   - Overall Employment Rate updates

### Multi-Year Support
- Use year dropdown to switch years
- Each year stored separately
- No data conflict between years
- One record per disability type per year

### CSV Format
**To import, your CSV needs this format:**
```
Disability Type,Unemployed,Employed
Deaf or Hard of Hearing,67,34
Physical Disability (Orthopedic),581,252
...
```

---

## ❓ FAQ

**Q: Can I change historical data (2024, 2023)?**  
A: Yes, but you'll need to have data for that year first. Use import to add new years.

**Q: What if I enter wrong data?**  
A: You can edit anytime. Just go back to "Edit Data" and correct it.

**Q: Will this affect qualified applicants?**  
A: No, this is community context data. Separate system.

**Q: How are employment rates calculated?**  
A: (Employed ÷ Total PWD) × 100  
Example: (252 ÷ 833) × 100 = 30.2%

**Q: Can non-admins edit this?**  
A: No, only admins can access this system.

---

## 📱 Where to Find It

**On Admin Dashboard:**
- Widget card: Shows community stats summary
- Quick Actions: "Community Stats" card
- Sidebar: "Community PWD Stats" link

**Direct URL:**
- View: `/admin/community-statistics`
- Edit: `/admin/community-statistics/2025/edit`
- Export: `/admin/community-statistics/2025/export-csv`

---

## ✅ What's Working

✅ View dashboard with all 10 disability types  
✅ Live calculations as you type  
✅ Save changes to database  
✅ Export to CSV for backup  
✅ Import CSV to update bulk data  
✅ Dashboard widget showing context  
✅ Admin only - secure access  

---

## 🔍 Verify It's Working

**In Database (10 records):**
```
Deaf or Hard of Hearing - 67 unemployed, 34 employed
Intellectual Disability - 84 unemployed, 1 employed
Learning Disability - 14 unemployed, 1 employed
Mental Disability - 217 unemployed, 12 employed
Physical Disability (Orthopedic) - 581 unemployed, 252 employed
Psychosocial Disability - 164 unemployed, 62 employed
Speech and Language Impairment - 92 unemployed, 21 employed
Visual Disability - 118 unemployed, 71 employed
Cancer (RA11215) - 78 unemployed, 46 employed
Rare Disease (RA 11215) - 40 unemployed, 13 employed

TOTALS: 1,968 total PWD (1,455 unemployed, 513 employed)
```

---

**You're all set! Enjoy managing community PWD statistics! 🎉**
