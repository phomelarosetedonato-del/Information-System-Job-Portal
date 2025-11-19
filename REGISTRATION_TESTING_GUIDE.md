# Registration System Testing Guide

## ✅ System Status

### Configuration Verified
- ✅ **RegisterController**: All security features implemented
- ✅ **Database Migrations**: All migrations run successfully
- ✅ **reCAPTCHA Package**: Installed (anhskohbo/no-captcha v3.7.0)
- ✅ **Environment Variables**: Configured with test keys
- ✅ **View Files**: Updated with NoCaptcha helper

## 🔧 Configuration Details

### 1. Environment Variables (.env)
```env
# reCAPTCHA Configuration (Google reCAPTCHA v2)
RECAPTCHA_ENABLED=true
RECAPTCHA_SITE_KEY=6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI
RECAPTCHA_SECRET_KEY=6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe
NOCAPTCHA_SITEKEY=6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI
NOCAPTCHA_SECRET=6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe
```

**Note**: These are Google's **TEST KEYS** that always pass validation. Replace with your production keys from [Google reCAPTCHA](https://www.google.com/recaptcha/admin/create) for live deployment.

### 2. Database Tables

#### Users Table Columns:
- ✅ `id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`
- ✅ `role` (pwd/employer/admin)
- ✅ `phone`, `address`
- ✅ `registration_ip`, `registration_user_agent`
- ✅ `is_active`
- ✅ `password_meets_current_standards`, `last_password_changed_at`
- ✅ `failed_login_attempts`, `account_locked_until`
- ✅ `last_security_activity`
- ✅ `created_at`, `updated_at`

#### PWD Profiles Table Columns:
- ✅ `id`, `user_id`
- ✅ `disability_type`, `disability_severity`
- ✅ `assistive_devices`, `accessibility_needs`
- ✅ `skills`, `qualifications`
- ✅ `phone`, `address`
- ✅ `birthdate`, `gender`
- ✅ `special_needs`
- ✅ `profile_completed`, `is_employed`
- ✅ `created_at`, `updated_at`

## 🧪 Testing Procedures

### Test Case 1: PWD Registration (Basic)
1. Navigate to: `http://localhost/pwdnew_system/register`
2. Select **PWD Member** radio button
3. Fill in the form:
   - **Full Name**: John Doe Cruz
   - **Email**: john.cruz@example.com
   - **Phone**: 09123456789 or +639123456789
   - **Address**: 123 Main St, Brgy. Test, Alaminos City, Pangasinan
   - **Password**: SecurePass@2024
   - **Confirm Password**: SecurePass@2024
4. Complete reCAPTCHA (check the box)
5. Check both Terms of Service and Privacy Policy
6. Click **Create Secure Account**

**Expected Results**:
- ✅ User record created in `users` table with role='pwd'
- ✅ PWD profile created in `pwd_profiles` table
- ✅ Phone number normalized to +63 format
- ✅ Registration IP and user agent logged
- ✅ Redirect to dashboard with success message
- ✅ Email verification notification sent

### Test Case 2: Employer Registration
Same as Test Case 1, but select **Employer** radio button.

**Expected Results**:
- ✅ User record created with role='employer'
- ✅ NO PWD profile created (only for PWD users)
- ✅ All security fields populated

### Test Case 3: Validation Testing

#### Test Invalid Phone Numbers:
- ❌ `123` - Too short
- ❌ `abcd1234567` - Contains letters
- ❌ `11111111111` - Repeated digits
- ✅ `09123456789` - Valid format
- ✅ `+639123456789` - Valid international format

#### Test Invalid Passwords:
- ❌ `short` - Too short (< 12 chars)
- ❌ `alllowercase123` - No uppercase
- ❌ `ALLUPPERCASE123` - No lowercase
- ❌ `NoNumbers!@#` - No numbers
- ❌ `NoSpecialChar123` - No special chars
- ❌ `Password@123` - Too common
- ❌ `abc123DEF@#$` - Sequential characters
- ✅ `SecurePass@2024` - Meets all requirements

#### Test Invalid Emails:
- ❌ `test@tempmail.com` - Disposable email
- ❌ `fake@example.com` - Suspicious pattern
- ❌ `admin@test.com` - Suspicious pattern
- ✅ `user@gmail.com` - Valid email

#### Test Invalid Names:
- ❌ `J` - Too short
- ❌ `aaaaa` - Repeated characters
- ❌ `123 Name` - Contains numbers
- ✅ `Juan Dela Cruz` - Valid name

### Test Case 4: Security Features

#### Rate Limiting:
1. Try to register 6 times within 1 minute
2. **Expected**: 6th attempt should be blocked with "Too many attempts" message

#### Bot Protection:
1. Submit form without completing reCAPTCHA
2. **Expected**: Error message "reCAPTCHA verification failed"

#### SQL Injection Prevention:
1. Try entering: `'; DROP TABLE users; --` in any field
2. **Expected**: Input sanitized, no SQL executed

#### XSS Prevention:
1. Try entering: `<script>alert('XSS')</script>` in name field
2. **Expected**: Tags stripped, safe storage

## 🔍 Database Verification Queries

### Check Registered Users
```sql
SELECT id, name, email, role, phone, registration_ip, is_active, created_at 
FROM users 
ORDER BY created_at DESC 
LIMIT 10;
```

### Check PWD Profiles
```sql
SELECT p.id, p.user_id, u.name, p.disability_type, p.profile_completed
FROM pwd_profiles p
JOIN users u ON p.user_id = u.id
ORDER BY p.created_at DESC
LIMIT 10;
```

### Check Phone Normalization
```sql
SELECT name, phone 
FROM users 
WHERE phone LIKE '+63%' OR phone LIKE '09%';
```

### Check Security Logging
```sql
SELECT u.name, u.email, u.registration_ip, u.registration_user_agent, u.created_at
FROM users u
WHERE u.created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
ORDER BY u.created_at DESC;
```

## 🐛 Common Issues & Solutions

### Issue 1: reCAPTCHA Not Showing
**Solution**: 
- Check `.env` has `RECAPTCHA_ENABLED=true`
- Run: `php artisan config:clear`
- Verify internet connection (reCAPTCHA loads from Google)

### Issue 2: "Class NoCaptcha not found"
**Solution**:
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### Issue 3: Database Connection Error
**Solution**: 
- Check `.env` database credentials
- Ensure MySQL/MariaDB is running
- Test connection: `php artisan migrate:status`

### Issue 4: Phone Number Validation Failing
**Solution**:
- Philippine format required: `09XXXXXXXXX` or `+639XXXXXXXXX`
- System auto-converts `09` prefix to `+63`
- Must start with `9` after country code

### Issue 5: Password Requirements Not Updating
**Solution**:
- Clear browser cache
- Hard refresh: `Ctrl + Shift + R` (Windows) or `Cmd + Shift + R` (Mac)
- Check JavaScript console for errors

## 📊 Success Metrics

After successful registration, verify:

1. **User Record**:
   - ✅ Correct role assigned
   - ✅ Password hashed (bcrypt)
   - ✅ Email unverified initially
   - ✅ is_active = 1
   - ✅ Registration IP logged

2. **PWD Profile** (for PWD users):
   - ✅ Profile created automatically
   - ✅ Linked to user via user_id
   - ✅ disability_type = "Not Specified"
   - ✅ profile_completed = 0

3. **Security**:
   - ✅ No plain text passwords
   - ✅ Sanitized inputs stored
   - ✅ Normalized phone numbers
   - ✅ Timestamps accurate

## 🚀 Production Deployment Checklist

Before going live:

1. **Get Production reCAPTCHA Keys**:
   - Visit: https://www.google.com/recaptcha/admin/create
   - Choose reCAPTCHA v2 (Checkbox)
   - Add your domain
   - Replace test keys in `.env`

2. **Update Environment**:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   RECAPTCHA_ENABLED=true
   RECAPTCHA_SITE_KEY=your_production_site_key
   RECAPTCHA_SECRET_KEY=your_production_secret_key
   ```

3. **Security Hardening**:
   - Enable SSL/HTTPS
   - Set secure session cookies
   - Configure proper CORS headers
   - Enable rate limiting on production

4. **Email Configuration**:
   - Configure mail driver (SMTP, Mailgun, etc.)
   - Test email verification sends

5. **Monitoring**:
   - Set up error logging
   - Monitor registration attempts
   - Track suspicious activity

## 📝 Manual Testing Checklist

- [ ] PWD registration creates user + profile
- [ ] Employer registration creates user only
- [ ] Phone normalization works (09 → +63)
- [ ] reCAPTCHA blocks submission without check
- [ ] Password requirements enforced
- [ ] Email uniqueness validated
- [ ] Terms/Privacy checkboxes required
- [ ] Success message displays
- [ ] Redirect to dashboard works
- [ ] Email verification sent
- [ ] Database records correct
- [ ] No PHP errors in logs
- [ ] No JavaScript console errors
- [ ] Mobile responsive layout
- [ ] Accessibility features work

## 🔐 Security Test Commands

### Test Rate Limiting
```bash
# Send multiple requests quickly
for i in {1..10}; do curl -X POST http://localhost/pwdnew_system/register; done
```

### Check Security Logs
```bash
tail -f storage/logs/laravel.log | grep "registration"
tail -f storage/logs/security.log | grep "SUSPICIOUS"
```

### Verify Password Hashing
```php
php artisan tinker
>>> $user = App\Models\User::latest()->first();
>>> $user->password; // Should show hashed password (bcrypt)
```

## ✨ Features Implemented

1. **Enhanced Validation**:
   - Strong password requirements (12+ chars, mixed case, numbers, special chars)
   - Phone number format validation (Philippine mobile)
   - Disposable email blocking
   - Suspicious pattern detection

2. **Security Features**:
   - reCAPTCHA bot protection
   - Rate limiting (5 attempts per 24 hours)
   - IP address logging
   - User agent tracking
   - SQL injection prevention
   - XSS attack prevention

3. **User Experience**:
   - Real-time password strength checking
   - Password visibility toggle
   - Auto phone number formatting
   - Clear error messages
   - Accessible design (WCAG compliant)

4. **Data Protection**:
   - Input sanitization
   - Secure password hashing (bcrypt)
   - Email verification requirement
   - Privacy policy compliance

## 📞 Support

If you encounter issues:
1. Check error logs: `storage/logs/laravel.log`
2. Verify database connection
3. Clear all caches: `php artisan optimize:clear`
4. Check browser console for JavaScript errors
5. Verify PHP version (requires PHP 8.2+)

---

**Last Updated**: November 19, 2025
**System Version**: Laravel 12.x
**reCAPTCHA Version**: v2 (Checkbox)
