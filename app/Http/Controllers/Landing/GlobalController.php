<?php

namespace sisVentas\Http\Controllers\Landing;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use sisVentas\Company;

class GlobalController extends Controller {

	public function compose(View $view) {
		$company = Company::first();
		$image_not_available = '/store/img/Imagen_no_disponible.svg.png';

		$view->with('company', $company)->with('image_not_available', $image_not_available);

	}
}
