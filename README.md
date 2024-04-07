<h3><center>Simply Connect Laravel Notifications</center></h3>

<br>

<h4>A library dedicated to Laravel 10/11 introducing integration with <a href="https://simply-connect.ovh">Simply Connect</a> for the built-in notification class in Laravel</h4>

<hr>

In the words of introduction.
The Simply Connect application is also my project, the aim of which is to send SMS from YOUR devices. All you need to do is create an account on the platform, find an old Android phone in the drawer, install the .apk and that's it.
Detailed information can be found <a href="https://simply-connect.ovh">on the portal</a>

<hr>

Install:
```php
composer require karlos3098/simply-connect-laravel-notifications
```

```php
php artisan vendor:publish --provider="Karlos3098\SimplyConnectLaravelNotifications\SimplyConnectServiceProvider" --tag=config
```

<br>

Then add the API key from the simply-connect.ovh platform and the ID of the device from which messages will be sent to the .env
```
SIMPLY_CONNECT_API_KEY=API-KEY-HERE
SIMPLY_CONNECT_DEFAULT_DEVICE_ID=DEVICE-ID-HERE
```

<hr>


If you want to send an SMS message, create a new notification class in the same way as for sending emails, but additionally declare the toSimplyConnect method. it is also recommended to use the SimplyConnectNotification interface

In this way, the message will be sent to the number that should appear in the "phone_number" field in a given model.
```php
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Karlos3098\SimplyConnectLaravelNotifications\Interfaces\SimplyConnectNotification;
use Karlos3098\SimplyConnectLaravelNotifications\Services\SimplyConnectMessage;

class TestNotification extends Notification implements SimplyConnectNotification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['simply-connect', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)->line('Example Email');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [

        ];
    }

    public function toSimplyConnect(object $notifiable): SimplyConnectMessage
    {
        return (new SimplyConnectMessage)
            ->text("Example SMS message");
    }
}

```

<hr>

If you need a more universal method to implement a phone number, use the HasDifferentPhoneNumberForSimplyConnect extension in your model and then add the routeNotificationForSimplyConnect method.
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Karlos3098\SimplyConnectLaravelNotifications\Interfaces\HasDifferentPhoneNumberForSimplyConnect;

class User extends Authenticatable implements HasDifferentPhoneNumberForSimplyConnect
{
    use HasFactory, Notifiable;

    public function routeNotificationForSimplyConnect(Notification $notification): string
    {
        return $this->phone_number; //or whatever you want
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

```


You can also enter the phone number directly in the notification if you need it.
```php
public function toSimplyConnect(object $notifiable): SimplyConnectMessage
{
    return (new SimplyConnectMessage)
        ->phoneNumber("+48123456789")
        ->text("Example SMS message");
}
```

If you want to add multiple phone numbers, this is also possible.

```php
public function toSimplyConnect(object $notifiable): SimplyConnectMessage
{
    return (new SimplyConnectMessage)
        ->phoneNumber("+48123456789", "+48222222222", /* ... */)
        ->text("Example SMS message");
}
```

For convenience, you can also use the line() and breakLine() methods to make your SMS more readable.
```php
public function toSimplyConnect(object $notifiable): SimplyConnectMessage
{
    return (new SimplyConnectMessage)
        ->phoneNumber("+48123456789")
        ->line("Line 1")
        ->line("Line 2")
        ->breakLine()
        ->line("Line by blank line");
}
```

<hr>

The message you send will be added to the queue, but you can download its ID, based on which you will later be able to check its status.
```php
public function toSimplyConnect(object $notifiable): SimplyConnectMessage
{
    return (new SimplyConnectMessage)
        ->callback(function(int $messageId) {
            //...
        })
        ->text("Example SMS message");
}
```

Example application in a controller.
Detailed information about what is in the message object can be found in the <a href="https://panel.simply-connect.ovh/api/documentation">Simply Connect API Docs</a>
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Karlos3098\SimplyConnectLaravelNotifications\Facades\MessageDetails;

class MessageController extends Controller
{
    public function show(int $messageId): JsonResponse
    {
        $message = MessageDetails::getMessageById($messageId);
        return response()->json($message);
    }
}

```

<hr>

You can use a different device id and a different API key directly in the notification. If you use a different API key, remember that if you need to retrieve message details later, you must do so with the appropriate key.
```php
public function toSimplyConnect(object $notifiable): SimplyConnectMessage
{
    return (new SimplyConnectMessage)
        ->device(123123)
        ->token("other Bearer API token")
        ->text("Example SMS message");
}
```
```php
$message = MessageDetails::setBearerToken("other Bearer API token")->getMessageById($messageId);
```

<hr>

It may also happen that the telephone number, device ID or whatever you provided is incorrect and the form data will be rejected. In this case, an exception will be thrown as in the example below. $array contains a list of form errors.
```php
try {
    \App\Models\User::first()->notify(new \App\Notifications\TestNotification());
} catch (\Karlos3098\SimplyConnectLaravelNotifications\Exceptions\CouldNotSendNotification $e) {
    $message = $e->getMessage();
    $array = $e->getErrors();
    //...
}
```

