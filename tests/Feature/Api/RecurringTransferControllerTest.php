<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\RecurringTransfer\StoreRecurringTransferController;
use App\Models\User;
use App\Models\Wallet;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

test('save a new recurring transfer', function () {
    $user = User::factory()
        ->has(Wallet::factory()->richChillGuy())
        ->create();

    $recipient = User::factory()
        ->has(Wallet::factory())
        ->create();

    actingAs($user);

    postJson(action(StoreRecurringTransferController::class), [
        'recipient_email' => $recipient->email,
        'amount' => 100,
        'reason' => 'Your weekly wages',
        'frequency' => 7,
    ])
        ->assertNoContent(201);

    expect($recipient->refresh()->wallet->balance)->toBe(100);

    assertDatabaseHas('wallet_transfers', [
        'amount' => 100,
        'source_id' => $user->wallet->id,
        'target_id' => $recipient->wallet->id,
    ]);

    $transferId = \App\Models\WalletTransfer::where([
        'amount' => 100,
        'source_id' => $user->wallet->id,
        'target_id' => $recipient->wallet->id,
    ])->value('id');

    assertDatabaseHas('recurring_transfers', [
        'amount' => 100,
        'sender_id' => $user->id,
        'recipient_id' => $recipient->id,
        'frequency' => 7,
        'start_date' => null,
        'end_date' => null,
        'last_transfer_id' => $transferId,
    ]);

//    assertDatabaseCount('wallet_transactions', 3);
});
