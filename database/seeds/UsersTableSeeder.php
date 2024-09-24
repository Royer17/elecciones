<?php

use Illuminate\Database\Seeder;
use sisVentas\User;

class UsersTableSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		User::create([
			'name' => 'Admin',
			'email' => 'admin@admin.com',
			'password' => bcrypt('123456'),
		]);
	}
}