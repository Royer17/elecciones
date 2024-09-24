<?php

namespace sisVentas\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot() {

		View::composers(['sisVentas\Http\Controllers\Landing\GlobalController' => ['store.home.index', 'store.products.index', 'store.products.profile.index', 'store.checkout.check_out', 'store.checkout.shopping_cart', 'store.products.grid', 'store.about_us', 'store.completed', 'store.checkout.solicitude_detail'],
		]);

		View::composers(['sisVentas\Http\Controllers\GlobalController' => ['layouts.admin'],
		]);


	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		//
	}
}
