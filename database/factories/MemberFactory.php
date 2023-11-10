<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Member>
 */
class MemberFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'id'	=> Str::uuid(),
			'name'	=> $this->faker->name(),
			'phone'	=> $this->faker->phoneNumber(),
			'bio'	=> $this->faker->address(),
			'actived'=> '0',
			'user_id'=> 1,
		];
	}
}
