<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\RecurringTransfer;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final class SaveRecurringTransfer
{
    public function __construct(
        private PerformWalletTransfer $performWalletTransferAction,
    ) {}

    public function execute(
        User $sender,
        User $recipient,
        int $amount,
        int $frequency,
        string $reason,
        ?\DateTimeInterface $startDate = null,
        ?\DateTimeInterface $endDate = null,
    ): RecurringTransfer {
        return DB::transaction(function () use ($sender, $recipient, $amount, $frequency, $reason, $startDate, $endDate) {
            /** @var RecurringTransfer $recurringTransfer */
            $recurringTransfer = RecurringTransfer::create([
                'sender_id' => $sender->id,
                'recipient_id' => $recipient->id,
                'amount' => $amount,
                'frequency' => $frequency,
                'reason' => $reason,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);

            /**
             * If start date is not specified, we trigger a wallet transfer instantly. Otherwise, transfer will be
             * triggerred by a CRON task.
             */
            if (null === $startDate) {
                $walletTransfer = $this->performWalletTransferAction->execute($sender, $recipient, $amount, $reason);

                $recurringTransfer->lastTransfer()->save($walletTransfer);
            }

            return $recurringTransfer;
        });
    }
}
