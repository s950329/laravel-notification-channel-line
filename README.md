# LINE Laravel Notifications Channel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/leochien/laravel-notification-channel-line.svg?style=flat-square)](https://packagist.org/packages/leochien/laravel-notification-channel-line)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/leochien/laravel-notification-channel-line.svg?style=flat-square)](https://packagist.org/packages/leochien/laravel-notification-channel-line)

## Introduction

This package makes it easy to send notifications using [LINE](https://line.me/) with Laravel 5.6+. 

## Contents

- [LINE Laravel Notifications Channel](#line-laravel-notifications-channel)
  - [Introduction](#introduction)
  - [Contents](#contents)
  - [Installation](#installation)
    - [Setting up the LINE service](#setting-up-the-line-service)
  - [Usage](#usage)
    - [Text Message](#text-message)
      - [Available methods](#available-methods)
    - [Template Messages](#template-messages)
      - [Available methods](#available-methods-1)
    - [Flex Messages](#flex-messages)
      - [Available methods](#available-methods-2)
  - [Security](#security)
  - [Contributing](#contributing)
  - [Credits](#credits)
  - [License](#license)

## Installation

You can install the package via composer:

```shell script
$ composer require leochien/laravel-notification-channel-line
```

### Setting up the LINE service

In order to send message to LINE channels, you need to obtain [Messaging API overview](https://developers.line.biz/en/docs/messaging-api/overview/).

Add your LINE Message API Token to your `config/services.php`:

```php
// config/services.php
...
'line_message_api' => [
    'token' => env('LINE_MESSAGE_TOKEN', 'YOUR MESSAGE TOKEN HERE'),
],
...
```

## Usage

### Text Message

Reference: [Text Message](https://developers.line.biz/en/reference/messaging-api/#text-message)
You can use the channel in your `via()` method inside the notification:

```php
use Illuminate\Notifications\Notification;
use LeoChien\LaravelNotificationChannelLine\LineMessage;
use LeoChien\LaravelNotificationChannelLine\LineWebhookChannel;

class TaskCompleted extends Notification
{
    public function via($notifiable): array
    {
        return [
            'line',
        ];
    }

    public function toLine($notifiable): LineMessage
    {
        return LineMessage::create()
            ->to('line_user_id')
            ->from('message_api_token') // optional if set in config
            ->content('Test message');
    }
}
```

#### Available methods

`create()`: Initial a message instance.

`from()`: Optional. Sets the sender's access token. Default to the set in config.

`to()`: Required. Specifies the line user id to send the notification to.

`content()`: Required. Sets a content of the notification message. Only support plain text for now.

### Template Messages

Reference: [Template messages](https://developers.line.biz/en/reference/messaging-api/#template-messages)

Currently, only 'buttons' and 'confirm' template types are supported. We will update to support 'carousel' and 'image carousel' as soon as possible.

```php
use Illuminate\Notifications\Notification;
use LeoChien\LaravelNotificationChannelLine\LineTemplateMessage;
use LeoChien\LaravelNotificationChannelLine\LineWebhookChannel;

class TaskCompleted extends Notification
{
    public function via($notifiable): array
    {
        return [
            'line',
        ];
    }

    public function toLine($notifiable): LineMessage
    {
        return LineTemplateMessage::create()
            ->to('line_user_id')
            ->from('message_api_token') // optional if set in config
            ->type('buttons')
            ->thumbnailImageUrl('https://fakeimg.pl/350x200')
            ->text("This is the text of the message")
            ->actions([
                [
                    'type' => 'uri',
                    'label' => 'See more',
                    'uri' => 'https://example.com',
                ],
            ]);
    }
}
```

#### Available methods

`from()`: Sets the sender's access token.

`to()`: Specifies the line user id to send the notification to.

`type()`: Required. template type, allowed value: buttons, confirm, carousel, image_carousel.

`thumbnailImageUrl()`: Optional. Image URL (Max character limit: 2000). Protocol: HTTPS (TLS 1.2 or later). Image format: JPEG or PNG. Max width: 1024px. Max file size: 10 MB.

`imageAspectRatio()`: Optional. Aspect ratio of the image. Allowed values: rectangle(1.51:1), square(1:1). Default: rectangle.

`imageSize()`: Optional. Size of the image. Allowed Values: cover, contain. Default: cover.

`imageBackgroundColor()`: Optional. Background color of the image. Specify a RGB color value. Default: #FFFFFF (white)

`title()`: Optional. Message Title, max character limit: 40.

`text()`: Required. Message text, max character limit: 160 (no image or title), max character limit: 60 (message with an image or title).

`defaultAction()`: Optional. Action when image, title or text area is tapped.

`actions()`: Required. Action when tapped, max objects: 4.

### Flex Messages

Reference: [Flex messages](https://developers.line.biz/en/docs/messaging-api/using-flex-messages/)

Flex messages allow you to create richly laid out messages that can contain combinations of text, buttons, images, and more, arranged in a flexible layout.

```php
use Illuminate\Notifications\Notification;
use LeoChien\LaravelNotificationChannelLine\LineFlexMessage;
use LeoChien\LaravelNotificationChannelLine\LineWebhookChannel;

class TaskCompleted extends Notification
{
    public function via($notifiable): array
    {
        return [
            'line',
        ];
    }

    public function toLine($notifiable): LineMessage
    {
        return LineFlexMessage::create()
            ->to('line_user_id')
            ->from('message_api_token') // optional if set in config
            ->altText('This is a Flex Message')
            ->contents([
                'type' => 'bubble',
                'body' => [
                    'type' => 'box',
                    'layout' => 'vertical',
                    'contents' => [
                        [
                            'type' => 'text',
                            'text' => 'Hello, World!',
                            'weight' => 'bold',
                            'size' => 'xl'
                        ],
                        [
                            'type' => 'box',
                            'layout' => 'vertical',
                            'margin' => 'lg',
                            'spacing' => 'sm',
                            'contents' => [
                                [
                                    'type' => 'text',
                                    'text' => 'This is a Flex Message',
                                    'color' => '#aaaaaa',
                                    'size' => 'sm',
                                    'flex' => 1
                                ]
                            ]
                        ]
                    ]
                ],
                'footer' => [
                    'type' => 'box',
                    'layout' => 'vertical',
                    'spacing' => 'sm',
                    'contents' => [
                        [
                            'type' => 'button',
                            'style' => 'primary',
                            'action' => [
                                'type' => 'uri',
                                'label' => 'Go to Website',
                                'uri' => 'https://example.com'
                            ]
                        ]
                    ]
                ]
            ]);
    }
}
```

#### Available methods

`from()`: Optional. Sets the sender's access token. Default to the set in config.

`to()`: Required. Specifies the line user id to send the notification to.

`altText()`: Required. Alternative text for devices that don't support Flex Message.

`contents()`: Required. The JSON object that defines the layout and content of the Flex Message.

## Security

If you discover any security related issues, please email s950329@hotmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Leo Chien](https://github.com/s950329)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
