<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RecurringTransfer extends Model
{
    protected $fillable = [
        'sender_id', 'recipient_id', 'amount', 'reason', 'frequency', 'startDate', 'endDate',
    ];

    /**
     * @return HasOne<WalletTransfer>
     */
    public function lastTransfer(): HasOne
    {
        return $this->hasOne(WalletTransfer::class, 'id', 'last_transfer_id');
    }
}
