<?php

namespace BasilLangevin\InstructorLaravel\Tests\Support;

use EchoLabs\Prism\Contracts\Message;
use EchoLabs\Prism\Enums\FinishReason;
use EchoLabs\Prism\Prism;
use EchoLabs\Prism\Structured\Request as StructuredRequest;
use EchoLabs\Prism\Testing\PrismFake;
use EchoLabs\Prism\ValueObjects\ProviderResponse;
use EchoLabs\Prism\ValueObjects\ResponseMeta;
use EchoLabs\Prism\ValueObjects\Usage;
use Illuminate\Support\Collection;

/**
 * @phpstan-type ResponseObject array<string, mixed>|ProviderResponse
 * @phpstan-type ResponseArray array<ResponseObject>
 */
class InstructorFake
{
    /**
     * @var Collection<int, ProviderResponse>
     */
    protected Collection $responses;

    protected PrismFake $prismFake;

    /**
     * @param  ResponseArray|ResponseObject  $responses
     */
    public function __construct(array|ProviderResponse $responses)
    {
        $this->responses = $this->buildResponses($responses);

        $this->prismFake = Prism::fake($this->responses->all());
    }

    /**
     * @param  ResponseArray|ResponseObject  $responses
     * @return Collection<int, ProviderResponse>
     */
    protected function buildResponses(array|ProviderResponse $responses): Collection
    {
        if ($this->isSingleResponse($responses)) {
            $responses = [$responses];
        }

        /** @var ResponseArray $responses */

        return collect($responses)
            ->map(function (array|ProviderResponse $response) {
                return $this->buildResponse($response);
            });
    }

    /**
     * @param  ResponseArray|ResponseObject  $responses
     */
    protected function isSingleResponse(array|ProviderResponse $responses): bool
    {
        if ($responses instanceof ProviderResponse) {
            return false;
        }

        return collect($responses)
            ->keys()
            ->every(fn (mixed $key) => is_string($key));
    }

    /**
     * @param  ResponseObject  $response
     */
    protected function buildResponse(array|ProviderResponse $response): ProviderResponse
    {
        if ($response instanceof ProviderResponse) {
            return $response;
        }

        return new ProviderResponse(
            text: json_encode($response) ?: '',
            toolCalls: [],
            usage: new Usage(10, 20),
            finishReason: FinishReason::Stop,
            responseMeta: new ResponseMeta('fake-1', 'fake-model')
        );
    }

    /**
     * Returns the first response that was made.
     */
    public function response(int $index = 0): ?ProviderResponse
    {
        return $this->responses()->get($index);
    }

    /**
     * Returns all the responses that were made.
     *
     * @return Collection<int, ProviderResponse>
     */
    public function responses(): Collection
    {
        return $this->responses;
    }

    /**
     * Returns a single request that was made.
     */
    public function request(int $index = 0): ?StructuredRequest
    {
        return $this->requests()->get($index);
    }

    /**
     * Returns all the requests that were made.
     *
     * @return Collection<int, StructuredRequest>
     */
    public function requests(): Collection
    {
        $return = [];

        $this->prismFake->assertRequest(function (array $requests) use (&$return) {
            /** @var StructuredRequest[] $requests */
            $return = $requests;
        });

        return collect($return);
    }

    /**
     * Returns the messages from the request at the given index.
     *
     * @return Collection<int, Message>
     */
    public function messages(int $index = 0): Collection
    {
        $request = $this->request($index);

        if (is_null($request)) {
            /** @var Collection<int, Message> */
            return collect();
        }

        return collect($request->messages);
    }

    /**
     * Returns a single message from a specific request.
     *
     * If a single index is provided, a message from the first request will be returned.
     * If multiple indexes are provided, the first index will select the request and
     * the second index will select the message from that request.
     *
     * Eg:
     * $fake->message(0) // First message from the first request
     * $fake->message(0, 1) // Second message from the first request
     * $fake->message(1, 0) // First message from the second request
     */
    public function message(int $index = 0, ?int $messageIndex = null): ?Message
    {
        if (is_null($messageIndex)) {
            $messageIndex = $index;
            $index = 0;
        }

        return $this->messages($index)->get($messageIndex);
    }
}
