<?php

namespace Karlos3098\SimplyConnectLaravelNotifications\Services;

class SimplyConnectMessage
{
    private string $text = '';

    private int $deviceId;

    private string $token;

    private array $phoneNumbers = [];

    private bool $hasManyPhoneNumbers = false;

    private $callback = null;

    public function __construct()
    {
        $defaultDeviceId = config('simply_connect.default_device_id');
        if ($defaultDeviceId) {
            $this->deviceId = $defaultDeviceId;
        }

        $token = config('simply_connect.api_key');
        if ($token) {
            $this->token = $token;
        }
    }

    //getters
    public function getText(): string
    {
        return $this->text;
    }

    public function getDeviceId(): int
    {
        return $this->deviceId;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumbers[0] ?? null;
    }

    public function hasManyPhoneNumbers(): bool
    {
        return $this->hasManyPhoneNumbers;
    }

    public function getPhoneNumbers(): array
    {
        return $this->phoneNumbers;
    }

    public function getCallback(): ?callable
    {
        return $this->callback;
    }

    //setters
    public function device($deviceId): static
    {
        $this->deviceId = $deviceId;

        return $this;
    }

    public function text(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function token(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function phoneNumber(string ...$phoneNumbers): static
    {
        $this->phoneNumbers = array_merge($this->phoneNumbers, $phoneNumbers);

        $this->hasManyPhoneNumbers = count($this->phoneNumbers) > 1;

        return $this;
    }

    public function line(string $line): static
    {
        if (! empty($this->text)) {
            $this->breakLine();
        }

        $this->text .= $line;

        return $this;
    }

    public function breakLine(): static
    {
        $this->text .= "\n";

        return $this;
    }

    public function callback(callable $callback): static
    {
        $this->callback = $callback;

        return $this;
    }
}
