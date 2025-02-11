<?php

namespace BasilLangevin\InstructorLaravel\Collections;

use EchoLabs\Prism\Contracts\Message;
use EchoLabs\Prism\ValueObjects\Messages\AssistantMessage;
use EchoLabs\Prism\ValueObjects\Messages\UserMessage;
use Illuminate\Support\Collection;

/**
 * @extends Collection<int, Message>
 */
class MessageThread extends Collection
{
    public function addUserMessage(string $message): static
    {
        $this->push(new UserMessage($message));

        return $this;
    }

    public function user(string $message): static
    {
        return $this->addUserMessage($message);
    }

    public function addAssistantMessage(string $message): static
    {
        $this->push(new AssistantMessage($message));

        return $this;
    }

    public function assistant(string $message): static
    {
        return $this->addAssistantMessage($message);
    }
}
