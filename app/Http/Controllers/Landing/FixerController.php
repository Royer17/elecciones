<?php

namespace sisVentas\Http\Controllers\Landing;

use Illuminate\Routing\Controller;
use sisVentas\Articulo;
use sisVentas\Categoria;
use DB;
use Carbon\Carbon;

class FixerController extends Controller {

	public function fix_product() {
		$products = Articulo::all();

		foreach ($products as $key => $product) {
			$product->slug = str_slug($product->nombre);
			$product->price = rand(10, 20);
			$product->save();
		}
		return;

	}

	public function fix_category() {

		$categories = Categoria::all();

		foreach ($categories as $key => $category) {
			$category->slug = str_slug($category->nombre);
			$category->save();
		}
		return;

	}

	public function delete_old_records()
	{
		DB::table('order_multiple_document')
			->where('id', '<=', 37)
			->update(['deleted_at' => Carbon::now()->format('Y-m-d H:i:s')]);
			
		DB::table('order_order')
			->where('id', '<=', 229)
			->update(['deleted_at' => Carbon::now()->format('Y-m-d H:i:s')]);

		return "ok";

	}

	
}
