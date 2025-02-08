<?php

namespace BasilLangevin\InstructorLaravel\Tests\Support;

use EchoLabs\Prism\Enums\Provider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ResponseBuilder
{
    protected Provider $provider = Provider::DeepSeek;

    /**
     * Set the provider for the response builder.
     */
    public function provider(Provider $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Get the provider for the response builder.
     */
    public function getProvider(): Provider
    {
        return $this->provider;
    }

    /**
     * Setup a fake response that returns the given JSON.
     */
    public function fake(string|array $json): void
    {
        Http::fake(['*' => Http::response($this->buildResponse($json))]);
    }

    /**
     * Build a response for the current provider.
     */
    protected function buildResponse(string|array $json): array
    {
        return match ($this->provider) {
            Provider::DeepSeek => $this->buildDeepSeekResponse($json),
            default => throw new \Exception('Provider not supported by test response builder'),
        };
    }

    /**
     * Build a fake response for the DeepSeek provider.
     */
    protected function buildDeepSeekResponse(string|array $json): array
    {
        return [
            'id' => Str::uuid(),
            'object' => 'chat.completion',
            'created' => 1739041064,
            'model' => 'deepseek-chat',
            'choices' => [
                [
                    'index' => 0,
                    'message' => [
                        'role' => 'assistant',
                        'content' => $this->encodeContent($json),
                    ],
                    'logprobs' => null,
                    'finish_reason' => 'stop',
                ],
            ],
            'usage' => [
                'prompt_tokens' => 94,
                'completion_tokens' => 11,
                'total_tokens' => 105,
                'prompt_tokens_details' => [
                    'cached_tokens' => 64,
                ],
                'prompt_cache_hit_tokens' => 64,
                'prompt_cache_miss_tokens' => 30,
            ],
            'system_fingerprint' => 'fp_3a5770e1b4',
        ];
    }

    /**
     * Encode the content of the response to JSON.
     */
    protected function encodeContent(string|array $json): string
    {
        if (is_string($json)) {
            return $json;
        }

        return json_encode($json);
    }
}
