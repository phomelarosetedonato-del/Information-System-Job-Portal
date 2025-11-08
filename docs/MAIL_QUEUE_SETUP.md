Mail & Queue production setup

This document shows recommended steps to configure mail sending and queue workers for production for this Laravel app.

1) Choose Mailer

- Common options: SMTP (Gmail, your provider), Mailgun, SendGrid, Amazon SES.
- Example .env (SMTP):

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@example.com
MAIL_FROM_NAME="PWD System"

- Example .env (Mailgun):

MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-mailgun-domain
MAILGUN_SECRET=your-mailgun-key
MAIL_FROM_ADDRESS=no-reply@example.com
MAIL_FROM_NAME="PWD System"

After editing .env, clear config cache:

php artisan config:cache

2) Queue driver

- Recommended for production: redis (fast) or database (simpler).
- Set in .env:

QUEUE_CONNECTION=redis
# or
# QUEUE_CONNECTION=database

If you choose database driver:

php artisan queue:table
php artisan migrate

3) Running queue workers

- On Linux (recommended): use Supervisor to manage workers.

Example Supervisor config (/etc/supervisor/conf.d/pwd_queue.conf):

[program:pwd_queue]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/your/project/artisan queue:work redis --sleep=3 --tries=3 --timeout=90
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/log/pwd_queue.log
stopwaitsecs=3600

After creating the file:

sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start pwd_queue:*

- On Windows: run the queue worker as a scheduled task or use a supervisor-like service such as NSSM to run php artisan queue:work at startup. Example command for PowerShell:

php artisan queue:work redis --sleep=3 --tries=3 --timeout=90

Run it inside a persistent service or scheduled task to restart on reboot.

4) Use queues for Notifications

- Ensure Notification classes implement ShouldQueue (queued notifications) and use Queueable. Some notification classes already implement ShouldQueue. We updated employer verification notifications to be queued.

- Verify queue connection: in .env set QUEUE_CONNECTION and run a local worker to test:

php artisan queue:work

- To test queuing in local environment, set QUEUE_CONNECTION=database and run the worker in a terminal.

5) Database notifications

- Notifications can be stored in the database using the 'database' channel. Ensure notifications table exists (Laravel creates 'notifications' by default). If not:

php artisan notifications:table
php artisan migrate

6) Additional production tips

- Use Redis for both cache and queue for performance.
- Use Supervisor and multiple worker processes based on expected throughput.
- Monitor queue length using Horizon (if using Redis) or custom dashboards.
- Ensure MAIL_FROM_ADDRESS is a domain you control and SPF/DKIM are configured for deliverability.

7) Quick smoke test

- After configuring .env and starting queue worker run:

php artisan tinker
$user = \App\Models\User::find(1);
$user->notify(new \App\Notifications\EmployerVerificationApproved('Test note'));

- Check logs or inbox. If using 'database' channel, confirm new record in 'notifications' table.

8) Troubleshooting

- "Emails not sent": check queue worker logs, laravel.log, and mail provider dashboard.
- "Queue worker exits": check supervisor logs and set higher --timeout if long jobs.

If you want, I can:
- Add Supervisor example directly to repo (template file).
- Create a small helper artisan command to send a test notification.
- Wire up Laravel Horizon (if you prefer a UI for Redis queues).

