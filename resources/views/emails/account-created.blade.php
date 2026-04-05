<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ __('messages.account_created_subject') }}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Fira Sans', 'Droid Sans', 'Helvetica Neue', Arial, sans-serif; background: #f6f7f9; color: #111827; }
        .container { max-width: 640px; margin: 24px auto; background: #ffffff; border-radius: 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.06); overflow: hidden; }
        .header { padding: 20px 24px; background: #111827; color: #ffffff; }
        .title { margin: 0; font-size: 20px; font-weight: 600; }
        .content { padding: 24px; }
        .lead { margin: 0 0 16px; font-size: 16px; line-height: 1.5; }
        .button { display: inline-block; margin: 24px 0; padding: 12px 24px; background: #3b82f6; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: 500; }
        .footer { padding: 16px 24px; font-size: 12px; color: #6b7280; background: #f9fafb; }
    </style>
    <!--[if mso]>
    <style>
        table { border-collapse: collapse; }
        .container { width: 640px; }
    </style>
    <![endif]-->
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="title">{{ __('messages.account_created_subject') }}</h1>
        </div>
        <div class="content">
            <p class="lead">{{ __('messages.mail_greeting', ['name' => $user->name ?? 'there']) }}</p>
            <p class="lead">{{ __('messages.account_created_welcome') }}</p>
            <p class="lead">{{ __('messages.account_created_reset_instruction') }}</p>
            <a href="{{ $resetUrl }}" class="button">{{ __('messages.account_created_reset_password') }}</a>
            <p class="lead" style="font-size: 12px; color: #6b7280;">{{ __('messages.account_created_link_expires') }}</p>
        </div>
        <div class="footer">
            <p>{{ config('app.name') }} &copy; {{ date('Y') }}</p>
        </div>
    </div>
</body>
</html>
