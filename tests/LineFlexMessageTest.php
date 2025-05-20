<?php

namespace LeoChien\LaravelNotificationChannelLine\Test;

use LeoChien\LaravelNotificationChannelLine\LineFlexMessage;
use PHPUnit\Framework\TestCase;

class LineFlexMessageTest extends TestCase
{
    /** @test */
    public function it_can_create_a_flex_message()
    {
        $message = LineFlexMessage::create()
            ->to('line_user_id')
            ->from('message_api_token')
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
                        ],
                    ],
                ],
            ]);

        $this->assertEquals('line_user_id', $message->getTo());
        $this->assertEquals('message_api_token', $message->getFrom());
        $this->assertEquals('This is a Flex Message', $message->getAltText());
        $this->assertEquals([
            'type' => 'bubble',
            'body' => [
                'type' => 'box',
                'layout' => 'vertical',
                'contents' => [
                    [
                        'type' => 'text',
                        'text' => 'Hello, World!',
                    ],
                ],
            ],
        ], $message->getContents());
    }

    /** @test */
    public function it_can_convert_to_array()
    {
        $message = LineFlexMessage::create()
            ->to('line_user_id')
            ->from('message_api_token')
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
                        ],
                    ],
                ],
            ]);

        $array = $message->toArray();

        $this->assertEquals([
            'messages' => [
                [
                    'type' => 'flex',
                    'altText' => 'This is a Flex Message',
                    'contents' => [
                        'type' => 'bubble',
                        'body' => [
                            'type' => 'box',
                            'layout' => 'vertical',
                            'contents' => [
                                [
                                    'type' => 'text',
                                    'text' => 'Hello, World!',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $array);
    }
} 