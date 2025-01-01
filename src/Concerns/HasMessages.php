<?php

namespace BasilLangevin\Instructor\Concerns;

use BasilLangevin\Instructor\Enums\Role;
use Closure;
use Illuminate\Support\Collection;

trait HasMessages
{
    use ResolvesClosures;

    /**
     * The system message to add to the chat.
     */
    protected string|Closure|null $systemMessage = null;

    /**
     * The messages in the chat.
     *
     * @var array<int, array{role: string|Role, content: string|Closure}>|Closure
     */
    protected array|Closure $messages = [];

    /**
     * Add a system message to the chat.
     */
    public function system(string|Closure $message): self
    {
        $this->systemMessage = $message;

        return $this;
    }

    /**
     * Get the system message, resolving any closures.
     */
    public function resolveSystemMessage(): string
    {
        if ($this->systemMessage instanceof Closure) {
            return $this->resolve($this->systemMessage);
        }

        return $this->systemMessage;
    }

    /**
     * Add a message to the chat.
     */
    public function message(string|Closure $message, string|Role $role = Role::User): self
    {
        $role = $role instanceof Role ? $role : Role::from($role);

        if ($role === Role::System) {
            return $this->system($message);
        }

        if (! is_array($this->messages)) {
            throw new \Exception('Individual messages cannot be added when the messages are set as a closure.');
        }

        $this->messages[] = ['role' => $role, 'content' => $message];

        return $this;
    }

    /**
     * Set the messages in the chat.
     */
    public function messages(array|Closure $messages): self
    {
        if ($messages instanceof Closure) {
            $this->messages = $messages;

            return $this;
        }

        foreach ($messages as $message) {
            $this->message($message['content'], $message['role']);
        }

        return $this;
    }

    /**
     * Resolve a message, resolving any closures.
     */
    protected function resolveMessage(array $message): array
    {
        return [
            'role' => $message['role'] instanceof Role
                ? $message['role']
                : Role::from($message['role']),
            'content' => $this->resolve($message['content']),
        ];
    }

    /**
     * Get the messages, resolving any closures.
     */
    public function resolveMessages(): Collection
    {
        return collect($this->resolve($this->messages))
            ->map(fn ($message) => $this->resolveMessage($message));
    }

    /**
     * Alias for user().
     */
    public function prompt(string $prompt): self
    {
        return $this->user($prompt);
    }

    /**
     * Add a user message to the chat.
     */
    public function user(string $message): self
    {
        return $this->message($message, Role::User);
    }

    /**
     * Add an assistant message to the chat.
     */
    public function assistant(string $message): self
    {
        return $this->message($message, Role::Assistant);
    }
}
