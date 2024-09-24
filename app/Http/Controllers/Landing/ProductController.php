<?php

namespace sisVentas\Http\Controllers\Landing;

use DB;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use sisVentas\Articulo;
use sisVentas\Categoria;

class ProductController extends Controller {

	public function cart_detail(Request $request) {

		$ids = $request->ids;
		$cart_array = [];

		if ($ids != 'null') {

			$valores = (array) json_decode($ids);
			foreach ($valores as $key => $value) {

				$product = Articulo::find($key);

				$cart_array[] = array(
					'id' => $product->idarticulo,
					'image' => $product->imagen,
					'name' => $product->nombre,
					'slug' => $product->slug,
					'price' => $product->price,
					'quantity' => $value,
				);
			}
		}

		return $cart_array;

	}

	public function cart_total(Request $request) {

		$ids = $request->ids;
		$total = 0;

		if ($ids != 'null') {
			$valores = (array) json_decode($ids);
			//$ids_array = array_keys($valores);
			//$total = Articulo::whereIn('idarticulo', $ids_array)->sum('price');
			foreach ($valores as $key => $value) {
				$product = Articulo::find($key);
				$total += $value * $product->price;
			}

		}
		return $total;

	}

	public function cart_summary(Request $request) {

		$ids = $request->ids;
		$cart_array = [];
		$total = 0;

		if ($ids != 'null') {
			$ids_array = explode(',', $ids);

			$valores = (array) json_decode($ids);

			foreach ($valores as $key => $value) {
				$product = Articulo::find($key);

				$cart_array[] = array(
					'name' => $product->nombre,
					'price' => $product->price,
					'quantity' => $value,
				);

				$total += $value * $product->price;
			}
		}

		return ['cart' => $cart_array, 'total' => $total];

	}

	public function all_paginated(Request $request) {

		if ($request->ajax()) {

			$products = Articulo::orderBy('idarticulo', 'DESC')
				->whereEstado('Activo');

			if ($request->category_slug) {
				$category = Categoria::whereSlug($request->category_slug)->first();
				$products = $products->where('idcategoria', $category->idcategoria);
			}

			if ($request->q) {
				$text_to_search = $request->q;

				$products = $products->where(function ($query) use ($text_to_search) {
					$query->where('nombre', 'like', "%$text_to_search%")
						->orWhere('descripcion', 'like', "%$text_to_search%");
				});
			}
			$products = $products->paginate($request->per_page);

			return view("store.products.grid", compact('products'))->render();
		}

	}

	public function search(Request $request) {
		$q = $request->nameProduct;

		$result = DB::table('articulo')
			->select(['idarticulo as idProducto', 'nombre as producto', 'slug'])
			->where('estado', "Activo")
			->where('stock', '>', 0)
			->where(function ($query) use ($q) {
				$query->where('nombre', 'like', "%$q%");
				//->orWhere('code', 'like', "%$q%");
			})
			->get();

		return $result;
	}
}
