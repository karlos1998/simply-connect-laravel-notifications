<?php

namespace Karlos3098\SimplyConnectLaravelNotifications\Services;

use Illuminate\Support\Facades\Http;

class MessageService
{
    private string $token;

    public function __construct()
    {
        $this->setBearerToken(config('simply_connect.api_key'));
    }

    public function setBearerToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getBearerToken(): string
    {
        return $this->token;
    }

    public function getMessageById(int $messageId): array
    {
        $response = Http::withToken($this->getBearerToken())
            ->baseUrl(config('simply_connect.service_path'))
            ->accept('application/json')
            ->get("/api/messages/$messageId");

        return $response->json();
    }
}
