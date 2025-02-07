<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\RecurringTransfer;

use App\Actions\SaveRecurringTransfer;
use App\Http\Requests\Api\V1\StoreRecurringTransferRequest;

final class StoreRecurringTransferController
{
    public function __invoke(StoreRecurringTransferRequest $request, SaveRecurringTransfer $action)
    {
        $sender = $request->user();
        $recipient = $request->getRecipient();

        $action->execute(
            $sender,
            $recipient,
            $request->input('amount'),
            $request->input('frequency'),
            $request->input('reason'),
            $request->input('start_date'),
            $request->input('end_date'),
        );

        return response()->noContent(201);
    }
}
