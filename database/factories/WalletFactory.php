<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Wallet>
 */
class WalletFactory extends Factory
{
    public function definition(): array
    {
        return [
            'balance' => 0,
        ];
    }

    public function balance(int $balance): static
    {
        return $this->state(fn (array $attributes) => [
            'balance' => $balance,
        ]);
    }

    public function richChillGuy(): static
    {
        return $this
            ->state(fn (array $attributes) => [
                'balance' => 1_000_000,
            ])
            ->has(
                WalletTransaction::factory()
                    ->amount(1_000_000)
                    ->credit()
                    ->reason('Just a rich chill guy'),
                'transactions'
            );
    }

    public function poorGuy(): static
    {
        return $this
            ->state(fn (array $attributes) => [
                'balance' => 100,
            ])
            ->has(
                WalletTransaction::factory()
                    ->amount(100)
                    ->credit()
                    ->reason('Welcome !'),
                'transactions'
            );
    }
}
