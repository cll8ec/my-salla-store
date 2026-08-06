<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\Identity\Enums\RoleCode;
use App\Domains\Identity\Models\User;
use App\Shared\Enums\Locale;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'salla_customer_id' => null,
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->unique()->e164PhoneNumber(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'avatar_url' => null,
            'locale' => $this->faker->randomElement([Locale::Arabic->value, Locale::English->value]),
            'preferences' => null,
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static { return $this->state(fn () => ['email_verified_at' => null]); }
    public function superAdmin(): static { return $this->afterCreating(fn (User $u) => $u->assignRole(RoleCode::SuperAdmin)); }
    public function admin(): static { return $this->afterCreating(fn (User $u) => $u->assignRole(RoleCode::Admin)); }
    public function manager(): static { return $this->afterCreating(fn (User $u) => $u->assignRole(RoleCode::Manager)); }
    public function editor(): static { return $this->afterCreating(fn (User $u) => $u->assignRole(RoleCode::Editor)); }
    public function reviewer(): static { return $this->afterCreating(fn (User $u) => $u->assignRole(RoleCode::Reviewer)); }
    public function customer(): static { return $this->afterCreating(fn (User $u) => $u->assignRole(RoleCode::Customer)); }

    public function withTwoFactor(): static
    {
        return $this->state(fn () => [
            'two_factor_secret' => 'test-secret',
            'two_factor_recovery_codes' => json_encode(['code1', 'code2']),
            'two_factor_confirmed_at' => now(),
        ]);
    }
}
