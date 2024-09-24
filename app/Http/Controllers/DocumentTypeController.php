<?php

namespace sisVentas\Http\Controllers;

use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use sisVentas\Company;
use sisVentas\DocumentType;
use sisVentas\Http\Requests\DocumentTypeFormRequest;
use sisVentas\Office;
use sisVentas\Order;
use Auth;

class DocumentTypeController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}
	public function index(Request $request) {
		if ($request) {
			$query = trim($request->get('searchText'));
			$document_types = DB::table('document_types')->where('name', 'LIKE', '%' . $query . '%')
				->orderBy('id', 'desc')
				->paginate(10);

			return view('almacen.document_type.index', ["document_types" => $document_types, "searchText" => $query]);
		}
	}
	public function create() {
		return view("almacen.document_type.create");
	}
	public function store(DocumentTypeFormRequest $request) {
		$data = $request->all();
		
		$document_type = new DocumentType;
		$document_type->fill($data);
		$document_type->status = 1;
		$document_type->save();

		return Redirect::to('admin/tipos-de-documento');
	}

	// public function show($id) {
	// 	return view("almacen.categoria.show", ["categoria" => Categoria::findOrFail($id)]);
	// }

	public function edit($id) {

		return view("almacen.document_type.edit", ["document_type" => DocumentType::findOrFail($id)]);
	}

	public function update(DocumentTypeFormRequest $request, $id) {
		$data = $request->all();

		$document_type = DocumentType::findOrFail($id);
		$document_type->fill($data);
		$document_type->save();
		return Redirect::to('admin/tipos-de-documento');
	}

	public function destroy($id) {
		$document_type = DocumentType::findOrFail($id);
		$document_type->delete();
		return Redirect::to('admin/tipos-de-documento');
	}

	public function get_document_type_code(Request $request)
	{
		$current_office_id = $request->office_id;
		$destination_office_id = $request->destination_office_id;
		$document_type_id = $request->document_type_id;

		$user = Auth::user();
		$office = Office::find($current_office_id);

		$offices = Office::where('id', '!=', $destination_office_id)
			->get();

		if (!$document_type_id) {
			return ["code" => "", 'multiple' => 0, "offices" => $offices];
		}

		$destination_office = Office::find($destination_office_id);

		$document_type = DocumentType::with(['office' => function($query) use($current_office_id) {
			$query->where('office_id', $current_office_id);
		}])
			->find($document_type_id);


		$year = Carbon::now()->format('Y');

		$last_order = Order::whereOfficeIdOrigen($current_office_id)
			->whereDocumentTypeId($document_type_id)
			->whereParentOrderId(0)
			->orderBy('id', 'DESC')
			->first();

		$slug_office = str_slug($office->sigla);
		$slug_document_type = str_slug($document_type->name);

		$document_type_name_length = strlen($slug_document_type);

		$company = Company::first();

		if (empty($last_order)) {

			if ($document_type->is_multiple) {

				if ($document_type->office) {

					$start_with = (int)$document_type->office->start_with - 1;

					return ["code" => "{$slug_document_type} Nº {$this->get_next_number_formatted($start_with)}-{$year}-{$user->sigla}-{$office->sigla}/{$company->first_part_code}", 'multiple' => 1];
				}

				return ["code" => "{$slug_document_type} Nº {$this->get_next_number_formatted(0)}-{$year}-{$user->sigla}-{$office->sigla}/{$company->first_part_code}", 'multiple' => 1];

				//return ["code" => "", 'multiple' => $document_type->is_multiple];
			}

			if (!$destination_office_id) {
				return ["code" => "", 'multiple' => $document_type->is_multiple, "offices" => $offices];
			}

			if ($document_type->office) {

				$start_with = (int)$document_type->office->start_with - 1;

				return ["code" => "{$slug_document_type} Nº {$this->get_next_number_formatted($start_with)}-{$year}-{$user->sigla}-{$office->sigla}/{$destination_office->sigla}/{$company->first_part_code}", 'multiple' => $document_type->is_multiple, "offices" => $offices];
			}

			return ["code" => "{$slug_document_type} Nº 0001-{$year}-{$user->sigla}-{$office->sigla}/{$destination_office->sigla}/{$company->first_part_code}", 'multiple' => $document_type->is_multiple, "offices" => $offices];
		}

		$last_internal_code_arr = explode('-', $last_order->internal_code);
		$current_number_correlative = $last_order->internal_code_correlative;
		
		if ($document_type->is_multiple) {
			if ($last_internal_code_arr[2] != $year) {
				$current_number_correlative = 0;
			}
		} else {
			if ($last_internal_code_arr[1] != $year) {
				$current_number_correlative = 0;
			}
		}
		
		 //else {
		// 	$first_index = $last_internal_code_arr[0];
		// 	$old_number = substr($first_index, $document_type_name_length);
		// 	$next_number = (int)$old_number + 1;
		// }		
		if ($document_type->is_multiple) {

			return ["code" => "{$slug_document_type} Nº {$this->get_next_number_formatted($current_number_correlative)}-{$year}-{$user->sigla}-{$office->sigla}/{$company->first_part_code}", 'multiple' => 1];

			//return ["code" => "", 'multiple' => $document_type->is_multiple];
		}

		if (!$destination_office_id) {
			return ["code" => "", 'multiple' => $document_type->is_multiple, "offices" => $offices];
		}

		return ["code" => "{$slug_document_type} Nº {$this->get_next_number_formatted($current_number_correlative)}-{$year}-{$user->sigla}-{$office->sigla}/{$destination_office->sigla}/{$company->first_part_code}", 'multiple' => $document_type->is_multiple, "offices" => $offices];
	}

	public function get_next_number_formatted($number)
	{	
		$number = (int)$number + 1;

		$number_of_characters = strlen($number);
		$total_length = 4;

		if ($number_of_characters >= $total_length) {
			return $number;
		} else {
			$left = $total_length - $number_of_characters;
			return str_repeat("0", $left)."{$number}";

		}
	}
}
