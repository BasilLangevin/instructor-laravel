<?php

use BasilLangevin\InstructorLaravel\Exceptions\SchemaValidationException;
use BasilLangevin\InstructorLaravel\Facades\Instructor as InstructorFacade;
use BasilLangevin\InstructorLaravel\Instructor;
use BasilLangevin\InstructorLaravel\SchemaAdapter;
use BasilLangevin\InstructorLaravel\Tests\Support\Collections\BirdCollection;
use BasilLangevin\InstructorLaravel\Tests\Support\Data\BirdData;
use BasilLangevin\LaravelDataJsonSchemas\Facades\JsonSchema;
use EchoLabs\Prism\Enums\Provider;
use EchoLabs\Prism\ValueObjects\Messages\AssistantMessage;
use EchoLabs\Prism\ValueObjects\Messages\UserMessage;
use Illuminate\Support\Collection;

covers(Instructor::class);

it('can be created via its facade')
    ->expect(fn () => InstructorFacade::make())
    ->toBeInstanceOf(Instructor::class);

it('can pass method calls to the underlying Prism instance', function () {
    $fake = InstructorFacade::fake(['species' => 'Northern Flicker']);

    $instructor = InstructorFacade::make()->withSchema(BirdData::class);

    expect(method_exists($instructor, 'withSystemPrompt'))->toBeFalse();

    $instructor->withSystemPrompt('What species is this bird?')->generate();

    expect($fake->request()->systemPrompt)->toBe('What species is this bird?');
});

it('returns the Instructor method when a Prism method returns itself', function () {
    $instructor = InstructorFacade::make();

    expect(method_exists($instructor, 'withSystemPrompt'))->toBeFalse();

    $result = $instructor->withSystemPrompt('What species is this bird?');

    expect($result)->toBe($instructor);
});

it('returns the method return value when a Prism method does not return itself', function () {
    $instructor = InstructorFacade::make();

    expect(method_exists($instructor, 'providerMeta'))->toBeFalse();

    $result = $instructor->providerMeta(Provider::OpenAI);

    expect($result)->not->toBe($instructor);
});

it('can set the schema to a Data class', function () {
    $fake = InstructorFacade::fake(['species' => 'Red-breasted Nuthatch']);

    InstructorFacade::make()->withSchema(BirdData::class)->generate();

    expect($fake->request()->schema)
        ->toBeInstanceOf(SchemaAdapter::class)
        ->toArray()
        ->toEqual(JsonSchema::toArray(BirdData::class));
});

it('can set the schema to a collection of a Data class', function () {
    $fake = InstructorFacade::fake([
        [
            ['species' => 'Red-breasted Nuthatch'],
            ['species' => 'Eastern Bluebird'],
        ],
    ]);

    InstructorFacade::make()
        ->withCollectionSchema(BirdData::class)
        ->withoutRetries()
        ->generate();

    expect($fake->request()->schema)
        ->toBeInstanceOf(SchemaAdapter::class)
        ->toArray()
        ->toEqual(JsonSchema::collectToArray(BirdData::class));
});

describe('generate', function () {
    it('can generate a Data object for the provided schema from a single response', function () {
        InstructorFacade::fake(['species' => 'Barred Owl']);

        $result = InstructorFacade::make()
            ->withSchema(BirdData::class)
            ->withoutRetries()
            ->generate();

        expect($result)->toBeInstanceOf(BirdData::class);
        expect($result->species)->toBe('Barred Owl');
    });

    it('can generate a collection of Data objects for the provided schema from a single response', function () {
        InstructorFacade::fake([
            [
                ['species' => 'Barred Owl'],
                ['species' => 'Eastern Bluebird'],
            ],
        ]);

        $result = InstructorFacade::make()
            ->withCollectionSchema(BirdData::class)
            ->withoutRetries()
            ->generate();

        expect($result)->toBeInstanceOf(Collection::class)
            ->toHaveCount(2)
            ->each->toBeInstanceOf(BirdData::class);

        expect($result->first())->species->toBe('Barred Owl');
        expect($result->last())->species->toBe('Eastern Bluebird');
    });

    it('can generate a custom collection of Data objects for the provided schema from a single response', function () {
        InstructorFacade::fake([
            [
                ['species' => 'Barred Owl'],
                ['species' => 'Eastern Bluebird'],
            ],
        ]);

        $result = InstructorFacade::make()
            ->withCollectionSchema(BirdData::class, BirdCollection::class)
            ->withoutRetries()
            ->generate();

        expect($result)->toBeInstanceOf(BirdCollection::class)
            ->toHaveCount(2)
            ->each->toBeInstanceOf(BirdData::class);

        expect($result->first())->species->toBe('Barred Owl');
        expect($result->last())->species->toBe('Eastern Bluebird');
    });

    it('validates the response against the schema', function () {
        InstructorFacade::fake(['weight_in_grams' => 457]);

        InstructorFacade::make()
            ->withSchema(BirdData::class)
            ->withoutRetries()
            ->generate();
    })->throws(SchemaValidationException::class);

    it('retries a request when a response is invalid', function () {
        $fake = InstructorFacade::fake([
            ['weight_in_grams' => 457],
            ['species' => 'Varied Thrush'],
        ]);

        $result = InstructorFacade::make()
            ->withSchema(BirdData::class)
            ->withRetries(1)
            ->generate();

        expect($result)->toBeInstanceOf(BirdData::class);
        expect($result->species)->toBe('Varied Thrush');

        expect($fake->requests())->toHaveCount(2);
        expect($fake->messages(1))->toHaveCount(2);

        expect($fake->message(1, 0))->toBeInstanceOf(AssistantMessage::class)
            ->content->toBe(json_encode(['weight_in_grams' => 457]));

        expect($fake->message(1, 1))->toBeInstanceOf(UserMessage::class)
            ->text()->toBe(
                __('instructor-laravel::translations.retry_message', ['errors' => '{"/":["The required properties (species) are missing","Additional object properties are not allowed: weight_in_grams"]}'])
            );
    });
});
