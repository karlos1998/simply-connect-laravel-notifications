<?php

namespace Karlos3098\SimplyConnectLaravelNotifications\Messages;

class SimplyConnectMessage
{
    private string $text;

    public function getText(): string
    {
        return $this->text;
    }

    public function text(string $text): static
    {
        $this->text = $text;
        return $this;
    }

    // Add other message functionalities if needed
}
