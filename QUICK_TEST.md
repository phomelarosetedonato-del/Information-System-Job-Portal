# Quick Test Checklist

## Before Testing
- [ ] Logged in as admin (check URL shows /admin/dashboard)
- [ ] PHP server running (check http://127.0.0.1:8000/admin/announcements loads)

## During Test
1. Go to: http://127.0.0.1:8000/admin/announcements/create
2. Fill in:
   - Title: "Test Announcement November 24"
   - Content: "This is a test to check if announcements are being saved."
3. Check "Make this announcement active"
4. Click "Create Announcement" button
5. Click "Create & Notify Users" in modal

## What to Look For

### ✅ SUCCESS (Should See)
- [ ] Stay on /admin/announcements page
- [ ] See "Announcement created successfully..." message
- [ ] Your announcement appears in the list
- [ ] Title matches what you entered

### ❌ FAIL (Might See)
- [ ] Redirected to home page /
- [ ] Redirected to login /login
- [ ] See error message on create page
- [ ] Stay on create page (nothing happens)

## Immediately After Test

### Check Logs
```powershell
cd "C:\xampp\htdocs\new - pwdsystem\pwdnew_system"
Get-Content "storage/logs/laravel.log" -Tail 200 | Select-String "REQUEST START" -Context 100
```

**Copy everything from REQUEST START to ═══ marker**

### Check Database
```powershell
cd "C:\xampp\htdocs\new - pwdsystem\pwdnew_system"  
php test_announcements_complete.php
```

**Look for:**
- Total announcements: ___
- Total notifications: ___

## If SUCCESS
✅ Announcement created
✅ Notifications may or may not be sent (check notification count)
**Next:** Test if PWD user sees the notification in dashboard

## If FAILURE
❌ Announcement NOT created
**Action:** Find which STEP failed in logs:
- STEP 1 - Validation failed
- STEP 2 - Data prep failed
- STEP 3 - Database save failed
- STEP 4 - Notification failed (but should still create)
- STEP 5/6 - Redirect failed

**Then:** Report the error message from that STEP

---

**Status:** Ready to test ✓
