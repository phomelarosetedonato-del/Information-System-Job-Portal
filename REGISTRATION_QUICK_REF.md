# 🚀 Registration System - Quick Reference

## ✅ System Status: READY TO TEST

### Test URL
```
http://localhost/pwdnew_system/register
```

### Test Credentials (Use these for testing)

#### PWD User
```
Account Type: PWD Member
Name: Maria Santos
Email: maria.santos@test.com
Phone: 09171234567
Address: 45 Rizal St, Brgy. Lucap, Alaminos City, Pangasinan
Password: Maria2024@Test
Confirm: Maria2024@Test
✓ Terms of Service
✓ Privacy Policy
✓ reCAPTCHA (check the box)
```

#### Employer
```
Account Type: Employer
Name: ABC Company Inc
Email: hr@abccompany.test
Phone: 09181234567
Address: 789 Business Park, Brgy. Poblacion, Alaminos City
Password: Company2024@Secure
Confirm: Company2024@Secure
✓ Terms of Service
✓ Privacy Policy
✓ reCAPTCHA (check the box)
```

## 📋 Quick Test Checklist

### 1. Visual Check
- [ ] Page loads without errors
- [ ] reCAPTCHA widget visible
- [ ] Both user type cards (PWD/Employer) display
- [ ] All form fields present
- [ ] Password requirements list shows
- [ ] Terms checkboxes at bottom

### 2. Function Check
- [ ] Can select PWD or Employer
- [ ] Can type in all fields
- [ ] Password visibility toggle works
- [ ] Password requirements update in real-time
- [ ] reCAPTCHA can be checked
- [ ] Submit button enabled after completing form

### 3. Validation Check
- [ ] Try submitting without reCAPTCHA → Error
- [ ] Try weak password → Shows requirements
- [ ] Try invalid phone → Error message
- [ ] Try without accepting terms → Error
- [ ] Submit valid form → Success!

### 4. Database Check
```sql
-- After successful registration, run:
SELECT * FROM users ORDER BY created_at DESC LIMIT 1;
SELECT * FROM pwd_profiles ORDER BY created_at DESC LIMIT 1;
```

Expected:
- User record exists with normalized phone (+63...)
- PWD profile exists ONLY if registered as PWD
- Password is hashed (bcrypt)

## 🔧 Quick Fixes

### reCAPTCHA Not Showing?
```bash
php artisan config:clear
php artisan cache:clear
```
Then refresh browser (Ctrl+Shift+R)

### Database Error?
```bash
php artisan migrate:status
# If missing migrations:
php artisan migrate
```

### Validation Not Working?
```bash
php artisan optimize:clear
```

### Page Won't Load?
```bash
# Check if server is running
php artisan serve
# Then visit: http://127.0.0.1:8000/register
```

## 🎯 What Should Happen

### On Success
1. ✅ Success message appears
2. ✅ Redirects to `/dashboard`
3. ✅ Email verification sent
4. ✅ User record in database
5. ✅ PWD profile created (if PWD user)

### Common Errors (Expected)
- **"reCAPTCHA verification failed"** → Didn't check the box
- **"Password must be at least 12 characters"** → Too short
- **"This email is already registered"** → Email exists
- **"Please enter a valid phone number"** → Wrong format
- **"You must accept the Terms of Service"** → Checkbox not checked

## 📱 Phone Number Formats (All Valid)
- `09123456789` → Auto-converts to `+639123456789`
- `+639123456789` → Stays as is
- `639123456789` → Converts to `+639123456789`
- `912 345 6789` → Converts to `+639123456789`

## 🔒 Password Requirements
✓ At least 12 characters
✓ One uppercase letter (A-Z)
✓ One lowercase letter (a-z)
✓ One number (0-9)
✓ One special character (@$!%*?&)

### Valid Examples
- `SecurePass@2024`
- `Maria2024@Test`
- `Company#2024Pwd`

### Invalid Examples
- `short123` ❌ Too short
- `nouppercase123!` ❌ No uppercase
- `NOLOWERCASE123!` ❌ No lowercase
- `NoNumbers@#$` ❌ No numbers
- `NoSpecialChar123` ❌ No special char

## 🗄️ Database Quick Checks

### Count Registrations Today
```sql
SELECT COUNT(*) as registrations_today 
FROM users 
WHERE DATE(created_at) = CURDATE();
```

### List Recent Registrations
```sql
SELECT name, email, role, phone, created_at 
FROM users 
ORDER BY created_at DESC 
LIMIT 10;
```

### Check Phone Normalization
```sql
SELECT name, phone 
FROM users 
WHERE phone LIKE '+63%';
```

### PWD Profile Check
```sql
SELECT u.name, u.role, p.disability_type, p.profile_completed
FROM users u
LEFT JOIN pwd_profiles p ON u.id = p.user_id
WHERE u.role = 'pwd'
ORDER BY u.created_at DESC
LIMIT 10;
```

## 🚨 Troubleshooting

### Problem: "Class NoCaptcha not found"
```bash
composer dump-autoload
php artisan config:clear
```

### Problem: Page is blank
1. Check `storage/logs/laravel.log`
2. Enable debug: Set `APP_DEBUG=true` in `.env`
3. Clear all caches: `php artisan optimize:clear`

### Problem: Form submits but no redirect
1. Check JavaScript console (F12)
2. Verify routes: `php artisan route:list --name=register`
3. Check session config in `.env`

### Problem: reCAPTCHA says "Invalid site key"
Current keys are TEST KEYS - they should work!
If not working, in `.env` check:
```env
NOCAPTCHA_SITEKEY=6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI
NOCAPTCHA_SECRET=6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe
```

## 📞 Quick Commands

```bash
# Clear everything
php artisan optimize:clear

# Check routes
php artisan route:list | Select-String register

# Check database
php artisan migrate:status

# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();

# View latest user
php artisan tinker
>>> App\Models\User::latest()->first();

# Count users by role
php artisan tinker
>>> App\Models\User::groupBy('role')->selectRaw('role, count(*) as count')->get();
```

## ✨ Success Indicators

You'll know it's working when:
1. ✓ Page loads with reCAPTCHA visible
2. ✓ Password requirements update in real-time
3. ✓ Form validation shows appropriate errors
4. ✓ Success message displays after submit
5. ✓ Redirects to dashboard
6. ✓ Database shows new user record
7. ✓ Phone normalized to +63 format
8. ✓ PWD profile created (for PWD users)

## 🎯 Test Sequence

**Quick 5-Minute Test:**

1. Open: `http://localhost/pwdnew_system/register`
2. Select **PWD Member**
3. Fill: Name = "Test User", Email = "test@example.com", Phone = "09123456789"
4. Fill: Address = "123 Test St, Alaminos City"
5. Password = "TestPass@2024" (both fields)
6. Check reCAPTCHA box
7. Check Terms and Privacy boxes
8. Click **Create Secure Account**
9. Should see success message
10. Check database: `SELECT * FROM users WHERE email='test@example.com';`

**Expected Result:** New user created with phone `+639123456789` and PWD profile exists!

---

**Current Status**: ✅ READY TO TEST
**Last Updated**: November 19, 2025
**Test Keys**: Active (Google Test Keys)
**Database**: All migrations complete
