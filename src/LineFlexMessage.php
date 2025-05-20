<?php

namespace LeoChien\LaravelNotificationChannelLine;

class LineFlexMessage extends LineMessage
{
    /** @var array Flex Message contents. */
    protected $contents;

    /** @var string Alternative text for devices that don't support Flex Message. */
    protected $altText;

    /**
     * Create a new instance of LineFlexMessage.
     *
     * @return static
     */
    public static function create(string $content = ''): self
    {
        return new static();
    }

    /**
     * Create a new instance of LineFlexMessage.
     */
    public function __construct(array $contents = [])
    {
        parent::__construct('');
        $this->contents($contents);
    }

    /**
     * Set the contents of the Flex Message.
     *
     * @return $this
     */
    public function contents(array $contents): self
    {
        $this->contents = $contents;

        return $this;
    }

    /**
     * Get the contents of the Flex Message.
     */
    public function getContents(): array
    {
        return $this->contents;
    }

    /**
     * Set the alternative text of the Flex Message.
     *
     * @return $this
     */
    public function altText(string $altText): self
    {
        $this->altText = $altText;

        return $this;
    }

    /**
     * Get the alternative text of the Flex Message.
     */
    public function getAltText(): ?string
    {
        return $this->altText;
    }

    /**
     * Get an array representation of the LineFlexMessage.
     */
    public function toArray(): array
    {
        return array_filter([
            'messages' => [
                [
                    'type' => 'flex',
                    'altText' => $this->altText,
                    'contents' => $this->contents,
                ],
            ],
        ]);
    }
} 