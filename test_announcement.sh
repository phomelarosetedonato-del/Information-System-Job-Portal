#!/bin/bash

# Test Announcement Creation

echo "Testing Announcement Creation..."
echo ""

# First get the CSRF token
echo "Step 1: Getting CSRF token from /admin/announcements/create..."
CSRF_TOKEN=$(curl -s http://127.0.0.1:8000/admin/announcements/create | grep '_token' | head -1 | grep -oP 'value="\K[^"]+')
echo "CSRF Token: $CSRF_TOKEN"
echo ""

# Get cookies
COOKIES=$(mktemp)

echo "Step 2: Submitting announcement form..."
curl -s -c "$COOKIES" -b "$COOKIES" \
  -X POST http://127.0.0.1:8000/admin/announcements \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "_token=$CSRF_TOKEN&title=Test Announcement&content=This is a test announcement content.&is_active=1" \
  -w "\nHTTP Status: %{http_code}\nRedirect Location: %{redirect_url}\n" \
  -L

echo ""
echo "Step 3: Checking database for announcement..."
php -r "
\$db = new PDO('sqlite:database/database.sqlite');
\$stmt = \$db->query('SELECT COUNT(*) as count FROM announcements');
\$row = \$stmt->fetch(PDO::FETCH_ASSOC);
echo 'Total announcements: ' . \$row['count'] . \"\n\";
"

rm -f "$COOKIES"
