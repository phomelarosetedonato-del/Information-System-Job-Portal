# Resume System - Quick Start Testing Guide

## 🚀 Quick Setup Verification

### 1. Check Database
```sql
-- Verify resumes table exists
SHOW TABLES LIKE 'resumes';

-- Check table structure
DESCRIBE resumes;

-- Check for any existing resumes
SELECT COUNT(*) FROM resumes;
```

### 2. Check Routes
```bash
php artisan route:list --path=resumes
```

Expected output: 10 routes
- GET /resumes (index)
- GET /resumes/create (create)
- POST /resumes (store)
- GET /resumes/{resume} (show)
- GET /resumes/{resume}/edit (edit)
- PUT /resumes/{resume} (update)
- DELETE /resumes/{resume} (destroy)
- POST /resumes/{resume}/toggle-publish (togglePublish)
- DELETE /resumes/{resume}/document (deleteDocument)
- GET /resumes/{resume}/download (download)

### 3. Clear Cache
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 🧪 Testing Steps

### Test 1: Create Resume (10 minutes)

1. **Navigate to Create Page**
   - Login as job seeker
   - Go to: `http://localhost/pwdnew_system/public/resumes/create`
   - OR click "Create Resume" from modal/dashboard

2. **Fill Required Fields (to reach 80%)**
   ```
   Personal Info:
   - Surname: Cruz
   - First Name: Juan
   - Date of Birth: 1995-01-15
   - Sex: Male
   - Mobile: +639123456789
   - Email: juan.cruz@email.com
   - Province: Pangasinan
   - Complete Address: 123 Main St, Dagupan City
   
   Professional:
   - Professional Summary: "Experienced customer service representative with 5 years in BPO industry..."
   
   Education:
   - Educational Attainment: Bachelor's Degree
   - Course: BS Business Administration
   
   Work Experience (click "Add Work Experience"):
   - Company: ABC Company Inc.
   - Position: Customer Service Representative
   - Start Date: 2018-06-01
   - End Date: 2023-12-31
   - Description: "Handled customer inquiries and complaints..."
   
   Skills (click "Add Skill"):
   - Skill Name: Customer Service
   - Level: Advanced
   
   Profile Photo:
   - Upload any JPG image (< 2MB)
   
   Application Letter:
   - "Dear Hiring Manager, I am writing to express my interest..."
   ```

3. **Check Completion Progress**
   - Watch progress bar update as you fill fields
   - Should reach 100% after completing all above
   - Badge should turn green at 80%+

4. **Save & Publish**
   - Click "Save & Publish" button
   - Should redirect to resume list page
   - Should see success message

**Expected Result:** ✅ Resume created successfully with "Published" status

### Test 2: View Resume List (2 minutes)

1. **Navigate to Index**
   - Go to: `http://localhost/pwdnew_system/public/resumes`
   - OR click "My Resumes" from navigation

2. **Verify Display**
   - Should see resume card with:
     - Profile photo
     - Full name
     - Province and age
     - Completion ring (circular progress)
     - Status badge (Published/Draft)
     - Completion percentage
     - View count (0 initially)
     - Last updated timestamp

3. **Check Actions**
   - Should see buttons:
     - View
     - Edit
     - Unpublish (if published)
     - Download
     - Delete

**Expected Result:** ✅ Resume displays correctly with all metadata

### Test 3: View Resume Details (3 minutes)

1. **Click "View" Button**
   - Click "View" on your resume card
   - Should navigate to show page

2. **Verify Sections**
   - Header: Photo, name, contact info
   - Personal Information
   - Professional Summary
   - Career Objective (if filled)
   - Education
   - Work Experience
   - Skills & Competencies
   - Application Letter

3. **Check Action Bar**
   - Back to Resumes
   - Status badges (Published, Completion %)
   - Edit button
   - Unpublish button
   - Print button
   - Download PDF button

4. **Test Print**
   - Click Print button
   - Print preview should show clean document
   - Action buttons should be hidden

**Expected Result:** ✅ Professional-looking resume display

### Test 4: Edit Resume (5 minutes)

1. **Click "Edit" Button**
   - From show page or index page
   - Should load edit form with all data pre-filled

2. **Verify Pre-populated Data**
   - All text fields should have existing values
   - Profile photo should display
   - Dropdowns should have correct selections
   - Dynamic sections (work experience, skills) should be loaded

3. **Modify Data**
   - Change professional summary
   - Add another work experience entry
   - Add another skill
   - Change visibility setting

4. **Save Changes**
   - Click "Save Changes"
   - Should redirect to show page
   - Should see updated data

**Expected Result:** ✅ Resume updated successfully

### Test 5: File Uploads (5 minutes)

1. **Test Profile Photo**
   - Create/Edit resume
   - Upload photo (JPG, < 2MB)
   - Should see preview immediately
   - Save and verify photo displays on show page

2. **Test Personal Documents**
   - Upload PDF file (< 5MB)
   - Can select multiple files
   - Save and check documents section

3. **Test Supporting Documents**
   - Upload another PDF
   - Save and verify

4. **Test File Size Limits**
   - Try uploading image > 2MB
   - Should show validation error
   - Try uploading PDF > 5MB
   - Should show validation error

**Expected Result:** ✅ Files upload correctly with validation

### Test 6: Publishing Logic (3 minutes)

1. **Create Incomplete Resume (< 80%)**
   - Fill only name, DOB, sex (about 40%)
   - Try to click "Save & Publish"
   - Should show alert: "You need to complete at least 80%..."

2. **Complete to 80%+**
   - Fill required fields to reach 80%
   - "Save & Publish" should now work

3. **Toggle Publish/Unpublish**
   - From show page, click "Unpublish"
   - Status should change to "Draft"
   - Click "Publish" again
   - Status should change to "Published"

**Expected Result:** ✅ Publishing logic enforces 80% rule

### Test 7: Delete Resume (2 minutes)

1. **Click Delete Button**
   - From index page
   - Should show confirmation modal

2. **Confirm Deletion**
   - Click "Delete Resume"
   - Should remove from list
   - Associated files should be deleted from storage

**Expected Result:** ✅ Resume deleted successfully

### Test 8: Mobile Responsiveness (5 minutes)

1. **Open in Mobile View**
   - Press F12 in browser
   - Click device toolbar
   - Select iPhone or Android device

2. **Test Create Form**
   - All fields should stack vertically
   - Buttons should be full-width
   - Photo preview should resize
   - Progress bar should stick to top

3. **Test Resume Cards**
   - Should stack in single column
   - Photo and info should center
   - Buttons should wrap properly

4. **Test Resume Show Page**
   - Should be readable on small screen
   - Sections should flow naturally
   - Print button should work

**Expected Result:** ✅ All pages responsive on mobile

## 🐛 Troubleshooting

### Issue: "Route not found"
**Solution:**
```bash
php artisan route:clear
php artisan config:clear
```

### Issue: "Storage path not found"
**Solution:**
```bash
php artisan storage:link
```

### Issue: "File upload fails"
**Check:**
- php.ini settings: upload_max_filesize, post_max_size
- Directory permissions: storage/app/public/resumes/
```bash
mkdir -p storage/app/public/resumes/photos
mkdir -p storage/app/public/resumes/documents
mkdir -p storage/app/public/resumes/supporting
```

### Issue: "Profile photo not displaying"
**Solution:**
```bash
php artisan storage:link
```
Then check: `public/storage/resumes/photos/` exists

### Issue: "Completion percentage not updating"
**Check:**
- JavaScript console for errors
- Ensure all required fields have `name` attributes
- Verify updateCompletion() function is called on input events

### Issue: "Can't publish resume"
**Check:**
- Completion percentage >= 80%
- Check console for JavaScript alert
- Verify canBePublished() method in Resume model

## ✅ Success Criteria

After completing all tests, you should have:
- ✅ Created at least one complete resume
- ✅ Viewed resume in professional format
- ✅ Edited and updated resume
- ✅ Uploaded profile photo and documents
- ✅ Published and unpublished resume
- ✅ Deleted a resume
- ✅ Verified mobile responsiveness
- ✅ No PHP errors in logs
- ✅ No JavaScript errors in console

## 📊 Performance Checks

### Database Queries
```sql
-- Check resume counts by user
SELECT user_id, COUNT(*) as resume_count 
FROM resumes 
GROUP BY user_id;

-- Check published resumes
SELECT COUNT(*) FROM resumes WHERE is_published = 1;

-- Check completion statistics
SELECT 
    AVG(completion_percentage) as avg_completion,
    MIN(completion_percentage) as min_completion,
    MAX(completion_percentage) as max_completion
FROM resumes;

-- Check most viewed resumes
SELECT full_name, views_count 
FROM resumes 
ORDER BY views_count DESC 
LIMIT 10;
```

### File Storage Check
```bash
# Check uploaded files
ls -la storage/app/public/resumes/photos/
ls -la storage/app/public/resumes/documents/
ls -la storage/app/public/resumes/supporting/
```

## 🎯 Next Steps

After successful testing:

1. **Integrate with Job Application**
   - Add "Apply with Resume" feature to job listings
   - Pre-fill application forms with resume data

2. **Employer Features**
   - Resume search for employers
   - Filter by skills, experience, location
   - Resume bookmarking

3. **PDF Download**
   - Implement actual PDF generation
   - Use DomPDF or similar library
   - Custom templates

4. **Analytics**
   - Track resume views over time
   - Show application success rate
   - Provide improvement suggestions

5. **Resume Templates**
   - Add multiple design templates
   - Allow users to switch templates
   - Preview before applying

---

**Testing Time:** ~35 minutes for complete coverage  
**Status:** Ready for production testing  
**Last Updated:** November 19, 2025
