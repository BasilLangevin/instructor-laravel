<?php

namespace BasilLangevin\InstructorLaravel\Concerns;

use BasilLangevin\InstructorLaravel\Collections\MessageThread;
use BasilLangevin\InstructorLaravel\Exceptions\SchemaValidationException;
use EchoLabs\Prism\Contracts\Message;
use EchoLabs\Prism\Structured\PendingRequest;
use EchoLabs\Prism\Structured\Response;
use Illuminate\Contracts\View\View;

/**
 * @property PendingRequest $request
 */
trait ManagesMessages
{
    /** @var MessageThread<int, Message> */
    protected MessageThread $messages;

    protected function initializeManagesMessages(): void
    {
        $this->messages = new MessageThread;
    }

    /**
     * Update the request messages on the underlying Prism instance.
     */
    protected function updateRequestMessages(): void
    {
        /** @var array<int, Message> */
        $messages = $this->messages->toArray();

        $this->request->withMessages($messages);
    }

    /**
     * @param  array<int, Message>  $messages
     */
    public function withMessages(array $messages): static
    {
        $this->messages = MessageThread::make($messages);

        $this->updateRequestMessages();

        return $this;
    }

    /**
     * Prism doesn't allow adding both a prompt and messages.
     * Because retries rely on adding messages, we need to
     * add a user message instead of adding the prompt.
     */
    public function withPrompt(string|View $prompt): static
    {
        $message = is_string($prompt) ? $prompt : $prompt->render();

        return $this->addUserMessage($message);
    }

    /**
     * Add a user message to the message list.
     */
    public function addUserMessage(string $message): static
    {
        $this->messages->addUserMessage($message);

        $this->updateRequestMessages();

        return $this;
    }

    /**
     * Add an assistant message to the message list.
     */
    public function addAssistantMessage(string $message): static
    {
        $this->messages->addAssistantMessage($message);

        $this->updateRequestMessages();

        return $this;
    }

    /**
     * Add messages to help the LLM provide a correct response after a schema validation exception occurs.
     */
    protected function addRetryMessages(Response $response, SchemaValidationException $e): void
    {
        $this->addAssistantMessage($response->text);

        $this->addUserMessage(
            __('instructor-laravel::translations.retry_message', ['errors' => $e->getMessage()])
        );
    }
}
