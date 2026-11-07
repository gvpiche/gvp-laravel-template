<?php

use Illuminate\Filesystem\AwsS3V3Adapter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Laravel\Ai\AnonymousAgent;
use Laravel\Ai\Contracts\ConversationStore;
use Laravel\Ai\Messages\UserMessage;

uses(RefreshDatabase::class);

test('the AI SDK can resolve its provider and prompt an agent without credentials', function () {
    AnonymousAgent::fake(['The template is ready.']);

    $agent = new AnonymousAgent('You are a helpful assistant.', [], []);
    $response = $agent->prompt('Is the template ready?');

    expect($response->text)->toBe('The template is ready.');

    AnonymousAgent::assertPrompted('Is the template ready?');
});

test('the AI SDK can store conversation messages using the published migrations', function () {
    $store = app(ConversationStore::class);
    $conversationId = $store->storeConversation(null, null, 'Template conversation');
    $store->storeUserMessage($conversationId, null, null, AnonymousAgent::class, new UserMessage('Hello'));

    expect($store->getLatestConversationMessages($conversationId, 10)->first()->content)->toBe('Hello');
});

test('the S3 filesystem can be created from the configured disk', function () {
    $disk = Storage::build([
        ...config('filesystems.disks.s3'),
        'key' => 'test-key',
        'secret' => 'test-secret',
        'region' => 'us-east-1',
        'bucket' => 'template-test',
    ]);

    expect($disk)->toBeInstanceOf(AwsS3V3Adapter::class)
        ->and($disk->url('example.txt'))->toBe('https://template-test.s3.amazonaws.com/example.txt');
});
