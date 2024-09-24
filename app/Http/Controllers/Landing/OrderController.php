<?php

namespace sisVentas\Http\Controllers\Landing;

use Auth;
use Carbon\Carbon;
use DB;
use Fpdf;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Date\Date;
use Mail;
use sisVentas\Categoria;
use sisVentas\Company;
use sisVentas\DetailOrder;
use sisVentas\DocumentType;
use sisVentas\Entity;
use sisVentas\Feriado;
use sisVentas\Http\Requests\LoggedSolicitudeRequest;
use sisVentas\Http\Requests\OrderRequest;
use sisVentas\Http\Requests\RequestPayment;
use sisVentas\Mail\SendMailOrder;
use sisVentas\Office;
use sisVentas\Order;
use sisVentas\OrderOrder;
use sisVentas\Payment;
use sisVentas\Profession;
use sisVentas\Venta;

class OrderController extends Controller {

	public function store(OrderRequest $request) {


		//Validación de sábados, domingos y feriados.
		$now = Carbon::now();

		$day_string = Date::parse($now)->format('l');

		if ($day_string == "sábado") {
			return response()->json(['title' => 'Día no laborable', 'message' => "El día {$day_string} es un día no laborable.", 'type' => 'day'], 400);
		}

		if ($day_string == "domingo") {
			return response()->json(['title' => 'Día no laborable', 'message' => "El día {$day_string} es un día no laborable.", 'type' => 'day'], 400);
		}


		$feriados = Feriado::whereMonthDay($now->format('m-d'))
			->whereAnual(1)
			->get();


		if (count($feriados)) {
			return response()->json(['title' => 'Día no laborable', 'message' => "El día {$feriados[0]->date_string} es un día fijado no laborable.", 'type' => 'day'], 400);
		}

		$feriados2 = Feriado::whereDate('date', '=', $now->format('Y-m-d'))
			->whereAnual(0)
			->get();

		if (count($feriados2)) {

			$date_parsed = Carbon::parse($feriados2[0]->date)->format('d/m/Y');
			return response()->json(['title' => 'Día no laborable', 'message' => "El día {$date_parsed} es un día fijado no laborable.", 'type' => 'day'], 400);
		}

		//

		$data = $request->all();
		
		if (!$data['number']) {
			$data['number'] = "S/N";
		}

		if (!$data['folios']) {
			$data['folios'] = 1;
		}

		$today = Carbon::now();

		$year = $today->format('Y');

		$last_order = DB::table('orders')
			->orderBy('id', 'DESC')
			->where('parent_order_id', 0)
			->first();

		$next_number = 1;
		if (!empty($last_order)) {
			$old_year = substr($last_order->code, 0, 4);

			$next_number = 1;
			
			if ($old_year  == $year) {
				$number_extracted = substr($last_order->code, 4);
				$next_number = (int)$number_extracted + 1;
			}

		}

		if ($data['type_document'] == 2) {
			$data['name'] = $data['business_name'];
			$data['identity_document'] = $data['ruc'];

		}

		$entity = Entity::whereIdentityDocument($data['identity_document'])->first();

		if (!$entity) {
			$entity = new Entity();
			$entity->code = "-";
			$entity->profession_id = 0;
			$entity->type = 1;
			$entity->office_id = 0;
			$entity->status = 1;
		}

		$entity->fill($data);
		$entity->save();

		$new_order = new Order();
		$new_order->fill($data);

		$now = Carbon::now();

		$new_order->entity_id = $entity->id;
		$new_order->office_id = 60;
		$new_order->status = 1;
		$new_order->year = $now->format('Y');
		$new_order->date = $now->format('Y-m-d H:i:s');

		$unique_string = time().time();

		if (Input::hasFile('attached_file')) {
			$file = Input::file('attached_file');

		 //    Storage::disk('google')->put($file->getClientOriginalName(), fopen($file, 'r+'));
   //          $url = Storage::disk('google')->url($file->getClientOriginalName());
			// $new_order->attached_file = $url;
			$file->move(public_path().'/archivos/tramites/', $unique_string.$file->getClientOriginalName());
			$path = '/archivos/tramites/'.$unique_string.$file->getClientOriginalName();
			$new_order->attached_file = $path;
		}

		$new_order->save();

		$number_of_characters = strlen($next_number);
		$total_length = 7;

		if ($number_of_characters >= 7) {
			$code = $next_number;
		} else {
			$left = $total_length - $number_of_characters;
			$code = str_repeat("0", $left)."{$next_number}";

		}

		$main_office = Office::find(1);
		
		$new_order->code = "{$new_order->year}{$code}";
		$new_order->save();

		$new_detail = new DetailOrder();
		$new_detail->order_id = $new_order->id;
		$new_detail->status = 1;
		$new_detail->office_id_origen = 60;
		$new_detail->office_id = 60;
		$new_detail->last = true;
		$new_detail->observations = "";
		$new_detail->save();

		// //Send email
		// $company = $this->companyRepository->firstCompany();
		// $data['logo'] = $company->logotype_image;
		// $data['message'] = $data['message'];
		// $data['date'] = Date::parse($newInscription->created_at)->format('d-F-Y');
		// $data['companyName'] = $company->company_name;
		// $data['firstname'] = $data['fullname'];

		// if ($data['course'] != 0) {
		// 	$cycle = $this->cycleRepository->find($data['course']);
		// 	$data['course'] = $cycle->name_without_html;
		// } else {
		// 	$data['course'] = "No ha Seleccionado un curso";
		// }
		// //return $data;
		// Mail::to($data['email'])->send(new PreRegistration($data, $company->email));
		$company = Company::first();

		$logo = $company->logo;
		$company_name = $company->name;
		$firstname = "Luis";
		$dni_ruc = "7214334";
		$course = "dwada";
		$date = "19/09/2020";
		$city = "Tacna";
		$email = "my@gmail.com";
		$phone = "993943";
		$payment_way_id = "1";
		$amount = "10";
		$account_name = "mi cuenta";


		$date = Carbon::now()->format('Y-m-d H:i:s');

		DB::table('order_order')->insert(
    		[	
    			'order_id' => $new_order->id,
    			'parent_order_id' => 0,
    			'last_order_id' => $new_order->id,
    			'created_at' => $date,
    			'updated_at' => $date,
    		]
		);

		// Mail::send('emails.notification_entity', ['logo' => $logo, 'company_name' => $company_name, 'firstname' => $firstname, 'dni_ruc' => $dni_ruc, 'course' => $course, 'date' => $date, 'city' => $city, 'email' => $email, 'phone' => $phone, 'phone', 'payment_way_id' => $payment_way_id, 'amount' => $amount, 'account_name' => $amount], function ($m) use ($entity, $company) {
		// 	$m->from($company->email, $company->name);

		// 	$m->to($entity->email, $entity->name . " " . $entity->paternal_surname . " " . $entity->maternal_surname)->subject('Solicitud registrada');
		// });


		return response()->json(['title' => 'Operación Exitosa', 'message' => 'Se ha registrado correctamente su solicitud', 'id' => $new_order->id], 201);
	}

	public function import_students(Request $request)
	{

		DB::beginTransaction();

		try {

			$identity_documents = $request->identity_document;

			$nivel_arr_values = array("PRIMARIA" => 1, "SECUNDARIA" => 2);
			$grade_arr_values = array("PRIMERO" => 1, "SEGUNDO" => 2, "TERCERO" => 3, "CUARTO" => 4, "QUINTO" => 5, "SEXTO" => 6);


			$nivel = $request->nivel;
			$grade = $request->grade;
			$section = $request->section;
			$identity_document_type = $request->identity_document_type;
			$paternal_surname = $request->paternal_surname;
			$maternal_surname = $request->maternal_surname;
			$name = $request->name;
			$gender = $request->gender;
			$birthday = $request->birthday;
			$age = $request->age;

			foreach ($identity_documents as $key => $identity_document) {

				if ($identity_document) {
					#existe el sku -actualizar
					$entity = Entity::whereIdentityDocument($identity_document)
						->first();

					if ($entity) {
						#Filling!
						$entity->name = $name[$key];
						$entity->paternal_surname = $paternal_surname[$key];
						$entity->maternal_surname = $maternal_surname[$key];
						$entity->save();

					} else {
						#sku no existe
						#no exist el sku - nuevo
							#Si existe , el product enviado es un hijo
							$entity = new Entity();
							$entity->identity_document = $identity_document;
							$entity->name = $name[$key];
							$entity->paternal_surname = $paternal_surname[$key];
							$entity->maternal_surname = $maternal_surname[$key];
							$entity->type_document = $identity_document_type[$key] == "DNI" ? 1 : 0;
							$entity->sigla = $gender[$key];
							$entity->birthday = Carbon::createFromFormat('d/m/Y', $birthday[$key])->format('Y-m-d');
							$entity->profession_id = 0;
							$entity->office_id = 0;
							$entity->status = 1;
							$entity->save();

							$new_order = new Order();
							$new_order->internal_code = "###";
							$new_order->internal_code_correlative = "###";

							$user_id = Auth::user()->id;

							$new_order->entity_id = $entity->id;
							$new_order->user_id = Auth::user()->id;
							$new_order->office_id_origen = 0;
							$new_order->order_type_id = $nivel_arr_values[$nivel[$key]];
							$new_order->tupa_id = $grade_arr_values[$grade[$key]];
							$new_order->subject = $section[$key];
							$new_order->office_id = 0;
							$new_order->status = 1;
							$new_order->notes = "";
							$new_order->year = Carbon::now()->format('Y');

							$new_order->save();

					}

				}

			}


	    	DB::commit();
			return response()->json(['title' => 'Operación Exitosa', 'message' => 'Se ha importado correctamente a los estudiantes.'], 201);

	    // all good
		} catch (\Exception $e) {
		    DB::rollback();
		    return response()->json(['title' => 'Error', 'message' => $e->getMessage(), 'office_ids' => $request->office_ids], 400);
		    // something went wrong
		}

	}

	public function store_logged_solicitude(LoggedSolicitudeRequest $request)
	{

		DB::beginTransaction();

		try {
			$data = $request->all();
			$data['number'] = "";

			$today = Carbon::now();
			$date = Carbon::now()->format('Y-m-d H:i:s');

			// $last_order = DB::table('orders')
			// 	->orderBy('id', 'DESC')
			// 	->where('parent_order_id', 0)
			// 	->where('code', '!=', "")
			// 	->first();

			// if ($request->date) {
			// 	$date = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d')." ".$request->time;
			// }

			$data['date'] = $date;

			$entity_logged = Entity::with('office')
				->find(Auth::user()->entity_id);


			$entity = Entity::whereIdentityDocument($data['identity_document'])
				->first();

			if (!empty($entity)) {
				$entity->name = $data['name'];
				$entity->paternal_surname = $data['paternal_surname'];
				$entity->maternal_surname = $data['maternal_surname'];
				$entity->save();
				$entity_id = $entity->id;
			} else {
				$entity = new Entity();
				$entity->identity_document = $data['identity_document'];
				$entity->name = $data['name'];
				$entity->paternal_surname = $data['paternal_surname'];
				$entity->maternal_surname = $data['maternal_surname'];
				$entity->type_document = 1;
				$entity->profession_id = 0;
				$entity->office_id = 0;
				$entity->status = 1;
				$entity->save();
				$entity_id = $entity->id;
			}


			if ($data['identity_document_parent']) {

				$exist_parent = Profession::whereCode($data['identity_document_parent'])
					->first();

				if (!empty($exist_parent)) {
					$exist_parent->name = $data['name_parent'];
					$exist_parent->sigla = $data['paternal_surname_parent'];
					$exist_parent->maternal_surname = $data['maternal_surname_parent'];
					$exist_parent->save();
					$parent_id = $exist_parent->id;
				} else {
					$new_parent = new Profession();
					$new_parent->code = $data['identity_document_parent'];
					$new_parent->name = $data['name_parent'];
					$new_parent->sigla = $data['paternal_surname_parent'];
					$new_parent->maternal_surname = $data['maternal_surname_parent'];
					$new_parent->save();

					$parent_id = $new_parent->id;

				}

				$entity->profession_id = $parent_id;
				$entity->save();
			}

			$new_order = new Order();
			$new_order->internal_code = "###";
			$new_order->internal_code_correlative = "###";

			$user_id = Auth::user()->id;

			$new_order->entity_id = $entity_id;
			$new_order->user_id = Auth::user()->id;
			$new_order->office_id_origen = 0;
			$new_order->order_type_id = $data['order_type_id'];
			$new_order->tupa_id = $data['tupa_id'];
			$new_order->subject = $data['subject'];
			$new_order->office_id = 0;
			$new_order->status = 1;
			$new_order->notes = "";
			$new_order->year = $data['year'];

			$new_order->save();

			$code_formatted = $this->getNumberFormatted($new_order->id);

			$new_order->code = $new_order->year."-".$code_formatted;
			$new_order->save();
			
			$office_ids = $request->office_ids;


			foreach ($office_ids as $oi => $office_id) {

				$office = Office::find($office_id);

				if ($data['payed'][$oi] == 1) {
					$new_detail = new DetailOrder();
					$new_detail->order_id = $new_order->id;
					$new_detail->status = 0;
					$new_detail->office_id_origen = 1;
					$new_detail->office_id = $office_id;
					$new_detail->observations = $data['amount'][$oi];
					$new_detail->last = false;
					$new_detail->user_id = $user_id;
					$new_detail->save();
				} else {
					// $new_detail = new DetailOrder();
					// $new_detail->order_id = $new_order->id;
					// $new_detail->status = 0;
					// $new_detail->office_id_origen = $office->section;
					// $new_detail->office_id = $office_id;
					// $new_detail->observations = $data['amount'][$oi];
					// $new_detail->last = false;
					// $new_detail->save();
				}
			}
			// //CC
			// $offices_arr = [];

			// if ($request->offices_arr != "" && $request->offices_arr != "null") {
			// 	$offices_arr = explode(',', $request->offices_arr);
			// }
			
			// foreach ($offices_arr as $key => $office_id) {
			// 	//if ($office_id != $new_order->office_id) {
			// 		$other_order = $new_order->replicate();
			// 		$other_order->code = $new_order->code."--CC".$office_id;
			// 		//$other_order->internal_code = "";
			// 		$other_order->office_id = $office_id;
			// 		$other_order->status = 5;
			// 		$other_order->parent_order_id = $new_order->id;
			// 		$other_order->save();

			// 		$new_detail_order = new DetailOrder();
			// 		$new_detail_order->office_id_origen = $entity_logged->office_id;
			// 		$new_detail_order->order_id = $other_order->id;
			// 		$new_detail_order->status = 5;
			// 		$new_detail_order->office_id = $other_order->office_id;
			// 		$new_detail_order->observations = "";
			// 		$new_detail_order->last = true;
			// 		$new_detail_order->user_id = $user_id;
			// 		$new_detail_order->save();
			// 	//}
			// }


			// if ($document_type->is_multiple) {

			// 	$offices_id_arr = explode(',', $request->multiple_offices_id);

			// 	foreach ($offices_id_arr as $key => $office_id_) {

			// 			$other_order = $new_order->replicate();
			// 			$other_order->code = $this->get_next_order_code();
			// 			//$other_order->code = "";
			// 			//$other_order->internal_code = $this->get_internal_code($entity_logged->office_id, $request->document_type_id);
			// 			//$other_order->internal_code = "";
			// 			$other_order->office_id = $office_id_;
			// 			$other_order->status = 1;
			// 			$other_order->multiple = 1;
			// 			$other_order->save();

			// 			$new_detail_order = new DetailOrder();
			// 			$new_detail_order->office_id_origen = $entity_logged->office_id;
			// 			$new_detail_order->order_id = $other_order->id;
			// 			$new_detail_order->status = 1;
			// 			$new_detail_order->office_id = $office_id_;
			// 			$new_detail_order->observations = "Documento múltiple derivado a varias oficinas.";
			// 			$new_detail_order->last = true;
			// 			$new_detail_order->user_id = $user_id;
			// 			$new_detail_order->save();
						
			// 			DB::table('order_multiple_document')->insert(
			// 	    		[	
			// 	    			//'order_id' => $new_order->id,
			// 	    			'parent_order_id' => $new_order->id,
			// 	    			'order_id' => $other_order->id,
			// 	    			'created_at' => $date,
			// 	    			'updated_at' => $date,
			// 	    		]
			// 			);

			// 			DB::table('order_order')->insert(
			// 	    		[	
			// 	    			'order_id' => $other_order->id,
			// 	    			'parent_order_id' => 0,
			// 	    			'last_order_id' => $other_order->id,
			// 	    			'created_at' => $date,
			// 	    			'updated_at' => $date,
			// 	    		]
			// 			);
			// 	}

			// 	return response()->json(['title' => 'Operación Exitosa', 'message' => 'Se ha registrado correctamente su solicitud'], 201);

			// }

			// DB::table('order_order')->insert(
			// 	[	
			// 		'order_id' => $new_order->id,
			// 		'parent_order_id' => 0,
			// 		'last_order_id' => $new_order->id,
			// 		'created_at' => $date,
			// 		'updated_at' => $date,
			// 	]
			// );
	    	DB::commit();
			return response()->json(['title' => 'Operación Exitosa', 'message' => 'Se ha registrado correctamente el estudiante.'], 201);

	    // all good
		} catch (\Exception $e) {
		    DB::rollback();
		    return response()->json(['title' => 'Error', 'message' => $e->getMessage(), 'office_ids' => $request->office_ids], 400);
		    // something went wrong
		}
	}


	public function store_payment(RequestPayment $request)
	{

		DB::beginTransaction();

		try {
			$data = $request->all();

			$today = Carbon::now();
			$date = Carbon::now()->format('Y-m-d H:i:s');

			$entity_logged = Entity::with('office')
				->find(Auth::user()->entity_id);

			$detail_ids = $request->detail_ids;
			$amount_payed = $request->amount;

			$new_payment = new Payment();

			$new_payment->entity_id = $data['entity_id'];
			$new_payment->order_id = $data['order_id'];
			$new_payment->code = "##";
			$new_payment->save();

			$code_formatted = $this->getNumberFormatted($new_payment->id);
			$new_payment->code = $today->format('Y')."-".$code_formatted;
			$new_payment->save();

			$total = 0;
			foreach ($detail_ids as $oi => $detail_id) {

				//$office = Office::find($office_id);
				$detail = DetailOrder::find($detail_id);
				$amount_original = (float)$detail->observations;
				
				if ((float)$amount_payed[$oi] < $amount_original) {
					$total += $amount_payed[$oi];
					$detail->status = 1;
					$detail->observations = $amount_payed[$oi];
					$detail->payment_id = $new_payment->id;
					$detail->save();
					
					$new_detail = $detail->replicate();
					$new_detail->status = 0;
					$new_detail->payment_id = 0;
					$new_detail->observations = $amount_original - (float)$amount_payed[$oi];
					$new_detail->save();

				} else {
					$total += (float)$detail->observations;
					$detail->status = 1;
					$detail->payment_id = $new_payment->id;
					$detail->save();

				}

				

			}
			$new_payment->total = $total;
			$new_payment->save();

	    	DB::commit();
			return response()->json(['title' => 'Operación Exitosa', 'message' => 'Se ha registrado los pagos.', 'id' => $new_payment->id], 201);

	    // all good
		} catch (\Exception $e) {
		    DB::rollback();
		    return response()->json(['title' => 'Error', 'message' => $e->getMessage(), 'detail_ids' => $request->detail_ids], 400);
		    // something went wrong
		}
	}


	public function getNumberFormatted($value)
	{
		$quantity_of_digits = 8;

		$length_value = strlen($value);

		$missing_zeros = $quantity_of_digits - $length_value;

		return str_repeat("0", $missing_zeros).$value;

	}


	public function update_logged_solicitude(LoggedSolicitudeRequest $request, $order_id)
	{

		DB::beginTransaction();

		try {
			$data = $request->all();

			$user_id = Auth::user()->id;

			$entity_logged = Entity::with('office')
				->find(Auth::user()->entity_id);

			$entity = Entity::whereIdentityDocument($data['identity_document'])
				->first();

			if (!empty($entity)) {
				$entity->name = $data['name'];
				$entity->paternal_surname = $data['paternal_surname'];
				$entity->maternal_surname = $data['maternal_surname'];
				$entity->save();
				$entity_id = $entity->id;
			} else {
				$entity = new Entity();
				$entity->identity_document = $data['identity_document'];
				$entity->name = $data['name'];
				$entity->paternal_surname = $data['paternal_surname'];
				$entity->maternal_surname = $data['maternal_surname'];
				$entity->type_document = 1;
				$entity->profession_id = 0;
				$entity->office_id = 0;
				$entity->status = 1;
				$entity->save();
				$entity_id = $entity->id;
			}


			if ($data['identity_document_parent']) {

				$exist_parent = Profession::whereCode($data['identity_document_parent'])
					->first();

				if (!empty($exist_parent)) {
					$exist_parent->name = $data['name_parent'];
					$exist_parent->sigla = $data['paternal_surname_parent'];
					$exist_parent->maternal_surname = $data['maternal_surname_parent'];
					$exist_parent->save();
					$parent_id = $exist_parent->id;
				} else {
					$new_parent = new Profession();
					$new_parent->code = $data['identity_document_parent'];
					$new_parent->name = $data['name_parent'];
					$new_parent->sigla = $data['paternal_surname_parent'];
					$new_parent->maternal_surname = $data['maternal_surname_parent'];
					$new_parent->save();

					$parent_id = $new_parent->id;

				}

				$entity->profession_id = $parent_id;
				$entity->save();
			}

			$order = Order::find($order_id);

			$order->order_type_id = $data['order_type_id'];
			$order->tupa_id = $data['tupa_id'];
			$order->subject = $data['subject'];
			$order->save();

			$office_ids = $request->office_ids;
			$detail_ids = $request->detail_indexes;

			foreach ($office_ids as $oi => $office_id) {

				//$office = Office::find($office_id);
				if ($detail_ids[$oi] == 0) {
					if ($data['payed'][$oi] == 1) {
						$new_detail = new DetailOrder();
						$new_detail->order_id = $order->id;
						$new_detail->status = 0;
						$new_detail->office_id_origen = 1;
						$new_detail->office_id = $office_id;
						$new_detail->observations = $data['amount'][$oi];
						$new_detail->last = false;
						$new_detail->user_id = $user_id;
						$new_detail->save();
					}
				} else {
					$detail = DetailOrder::find($detail_ids[$oi]);

					if ($data['payed'][$oi] == 1) {
						$detail->observations = $data['amount'][$oi];
						$detail->save();
					} else {
						$detail->delete();
					}
				}


			}
			
	    	DB::commit();
			return response()->json(['title' => 'Operación Exitosa', 'message' => 'Se ha actulizado correctamente el registro.'], 201);

	    // all good
		} catch (\Exception $e) {
		    DB::rollback();
		    return response()->json(['title' => 'Error', 'message' => $e->getMessage(), 'ids' => $request->detail_indexes], 400);
		    // something went wrong
		}
	}


	// public function store_logged_solicitude(LoggedSolicitudeRequest $request)
	// {
	// 	$data = $request->except('date', 'time');
	// 	$data['number'] = "";

	// 	if (!$data['folios']) {
	// 		$data['folios'] = 1;
	// 	}

	// 	$today = Carbon::now();
	// 	$year = $today->format('Y');

	// 	//throw new \Exception("Error Processing Request", 1);

	// 	$date = Carbon::now()->format('Y-m-d H:i:s');

	// 	$document_type = DocumentType::find($request->document_type_id);

	// 	if ($document_type->is_multiple) {
	// 		$data['office_id'] = 0;
	// 	}

	// 	$last_order = DB::table('orders')
	// 		->orderBy('id', 'DESC')
	// 		->where('parent_order_id', 0)
	// 		//->whereMultiple(0)
	// 		->where('code', '!=', "")
	// 		->first();

	// 	$next_number = 1;
	// 	if (!empty($last_order)) {
	// 		$old_year = substr($last_order->code, 0, 4);

	// 		$next_number = 1;
			
	// 		if ($old_year  == $year) {
	// 			$number_extracted = substr($last_order->code, 4);
	// 			$next_number = (int)$number_extracted + 1;
	// 		}
	// 	}

	// 	// if ($request->date) {
	// 	// 	$date = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d')." ".$request->time;
	// 	// }

	// 	$data['date'] = $date;

	// 	$entity_logged = Entity::with('office')
	// 		->find(Auth::user()->entity_id);

	// 	// $exist_entity = Entity::whereIdentityDocument($data['identity_document'])
	// 	// 	->first();

	// 	// if (!empty($exist_entity)) {
	// 	// 	$exist_entity->fill($data);
	// 	// 	$exist_entity->save();
	// 	// 	$entity_id = $exist_entity->id;
	// 	// } else {
	// 	// 	$new_entity = new Entity();
	// 	// 	$new_entity->fill($data);
	// 	// 	$new_entity->type_document = 1;
	// 	// 	$new_entity->save();
	// 	// 	$entity_id = $new_entity->id;
	// 	// }

	// 	$internal_code_arr = $this->get_internal_codev2($entity_logged->office_id, $request->document_type_id, $data['office_id']);

	// 	$new_order = new Order();
	// 	$new_order->internal_code = $internal_code_arr['code'];
	// 	$new_order->internal_code_correlative = $internal_code_arr['correlative'];

	// 	$new_order->fill($data);

	// 	$user_id = Auth::user()->id;

	// 	$new_order->entity_id = $entity_logged->id;
	// 	$new_order->user_id = Auth::user()->id;
	// 	$new_order->office_id_origen = $entity_logged->office_id;
	// 	$new_order->office_id = $data['office_id'];
	// 	$new_order->status = 1;
	// 	$new_order->notes = $data['observations'];
	// 	$new_order->year = Carbon::now()->format('Y');

	// 	$unique_string = time().time();

	// 	if (Input::hasFile('attached_file')) {
	// 		$file = Input::file('attached_file');
	// 		$file->move(public_path() . '/archivos/tramites/', $unique_string.$file->getClientOriginalName());
	// 		$path = '/archivos/tramites/'.$unique_string.$file->getClientOriginalName();
	// 		$new_order->attached_file = $path;
	// 	}

	// 	$new_order->save();

	// 	if ($request->term != "" && $request->term != 0) {
	// 		$term_end = Carbon::now()->addDays($request->term)->format('Y-m-d H:i:s');
	// 		$new_order->term_end = $term_end;
	// 		$new_order->save();
	// 	}

	// 	$number_of_characters = strlen($next_number);
	// 	$total_length = 7;

	// 	if ($number_of_characters >= 7) {
	// 		$code = $next_number;
	// 	} else {
	// 		$left = $total_length - $number_of_characters;
	// 		$code = str_repeat("0", $left)."{$next_number}";
	// 	}

	// 	$new_order->code = "{$new_order->year}{$code}";
	// 	$new_order->save();

	// 	$new_detail = new DetailOrder();
	// 	$new_detail->order_id = $new_order->id;
	// 	$new_detail->status = 1;
	// 	$new_detail->office_id_origen = $entity_logged->office_id;
	// 	$new_detail->office_id = $data['office_id'];
	// 	$new_detail->observations = "Documento interno generado";
	// 	$new_detail->last = true;
	// 	$new_detail->save();


	// 	//CC
	// 	$offices_arr = [];

	// 	if ($request->offices_arr != "" && $request->offices_arr != "null") {
	// 		$offices_arr = explode(',', $request->offices_arr);
	// 	}
		
	// 	foreach ($offices_arr as $key => $office_id) {
	// 		//if ($office_id != $new_order->office_id) {
	// 			$other_order = $new_order->replicate();
	// 			$other_order->code = $new_order->code."--CC".$office_id;
	// 			//$other_order->internal_code = "";
	// 			$other_order->office_id = $office_id;
	// 			$other_order->status = 5;
	// 			$other_order->parent_order_id = $new_order->id;
	// 			$other_order->save();

	// 			$new_detail_order = new DetailOrder();
	// 			$new_detail_order->office_id_origen = $entity_logged->office_id;
	// 			$new_detail_order->order_id = $other_order->id;
	// 			$new_detail_order->status = 5;
	// 			$new_detail_order->office_id = $other_order->office_id;
	// 			$new_detail_order->observations = "";
	// 			$new_detail_order->last = true;
	// 			$new_detail_order->user_id = $user_id;
	// 			$new_detail_order->save();
	// 		//}
	// 	}


	// 	if ($document_type->is_multiple) {

	// 		$offices_id_arr = explode(',', $request->multiple_offices_id);

	// 		foreach ($offices_id_arr as $key => $office_id_) {

	// 				$other_order = $new_order->replicate();
	// 				$other_order->code = $this->get_next_order_code();
	// 				//$other_order->code = "";
	// 				//$other_order->internal_code = $this->get_internal_code($entity_logged->office_id, $request->document_type_id);
	// 				//$other_order->internal_code = "";
	// 				$other_order->office_id = $office_id_;
	// 				$other_order->status = 1;
	// 				$other_order->multiple = 1;
	// 				$other_order->save();

	// 				$new_detail_order = new DetailOrder();
	// 				$new_detail_order->office_id_origen = $entity_logged->office_id;
	// 				$new_detail_order->order_id = $other_order->id;
	// 				$new_detail_order->status = 1;
	// 				$new_detail_order->office_id = $office_id_;
	// 				$new_detail_order->observations = "Documento múltiple derivado a varias oficinas.";
	// 				$new_detail_order->last = true;
	// 				$new_detail_order->user_id = $user_id;
	// 				$new_detail_order->save();
					
	// 				DB::table('order_multiple_document')->insert(
	// 		    		[	
	// 		    			//'order_id' => $new_order->id,
	// 		    			'parent_order_id' => $new_order->id,
	// 		    			'order_id' => $other_order->id,
	// 		    			'created_at' => $date,
	// 		    			'updated_at' => $date,
	// 		    		]
	// 				);

	// 				DB::table('order_order')->insert(
	// 		    		[	
	// 		    			'order_id' => $other_order->id,
	// 		    			'parent_order_id' => 0,
	// 		    			'last_order_id' => $other_order->id,
	// 		    			'created_at' => $date,
	// 		    			'updated_at' => $date,
	// 		    		]
	// 				);
	// 		}

	// 		return response()->json(['title' => 'Operación Exitosa', 'message' => 'Se ha registrado correctamente su solicitud'], 201);

	// 	}

	// 	DB::table('order_order')->insert(
    // 		[	
    // 			'order_id' => $new_order->id,
    // 			'parent_order_id' => 0,
    // 			'last_order_id' => $new_order->id,
    // 			'created_at' => $date,
    // 			'updated_at' => $date,
    // 		]
	// 	);

	// 	return response()->json(['title' => 'Operación Exitosa', 'message' => 'Se ha registrado correctamente su solicitud'], 201);
	// }

	public function get_next_order_code()
	{
		$today = Carbon::now();
		$year = $today->format('Y');

		$last_order = DB::table('orders')
			->orderBy('id', 'DESC')
			->where('parent_order_id', 0)
			->where('code', '!=', "")
			//->whereMultiple(0)
			->first();

		$next_number = 1;
		if (!empty($last_order)) {
			$old_year = substr($last_order->code, 0, 4);

			$next_number = 1;
			
			if ($old_year == $year) {
				$number_extracted = substr($last_order->code, 4);
				$next_number = (int)$number_extracted + 1;
			}
		}

		$number_of_characters = strlen($next_number);
		$total_length = 7;

		if ($number_of_characters >= 7) {
			$code = $next_number;
		} else {
			$left = $total_length - $number_of_characters;
			$code = str_repeat("0", $left)."{$next_number}";
		}

		return "{$year}{$code}";
	}

	public function get_internal_codev2($current_office_id, $document_type_id, $destination_office_id)
	{
		$user = Auth::user();
		$office = Office::find($current_office_id);
		$destination_office = Office::find($destination_office_id);

		$document_type = DocumentType::with(['office' => function($query) use($current_office_id) {
			$query->where('office_id', $current_office_id);
		}])
			->find($document_type_id);

		$destination_office_sigla = "";
		if (!empty($destination_office) && $document_type->is_multiple == false) {
			$destination_office_sigla = "/{$destination_office->sigla}";
		}

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
			if ($document_type->office) {

				$start_with = (int)$document_type->office->start_with - 1;
				$next_number = $this->get_next_number_formatted($start_with);

				return ["code" => "{$slug_document_type} Nº {$next_number}-{$year}-{$user->sigla}-{$office->sigla}{$destination_office_sigla}/{$company->first_part_code}", 'correlative' => $next_number];
			}

			return ["code" => "{$slug_document_type} Nº 0001-{$year}-{$user->sigla}-{$office->sigla}{$destination_office_sigla}/{$company->first_part_code}", 'correlative' => 1];
		}
		
		//$last_internal_code_arr = explode('-', $last_order->internal_code);
		$last_internal_code_arr = explode('-', $last_order->internal_code);
		$current_number_correlative  = $last_order->internal_code_correlative;

		if ($document_type->is_multiple) {
			if ($last_internal_code_arr[2] != $year) {
				$current_number_correlative = 0;
			}
		} else {
			if ($last_internal_code_arr[1] != $year) {
				$current_number_correlative = 0;
			}
		}

		// if ($last_internal_code_arr[1] != $year) {
		// 	$current_number_correlative = 0;
		// }

		// if (end($last_internal_code_arr) != $year) {
		// 	$next_number = 1;
		// } else {
		// 	$first_index = $last_internal_code_arr[0];
		// 	$old_number = substr($first_index, $document_type_name_length);
		// 	$next_number = (int)$old_number + 1;
		// }
		$next_number = $this->get_next_number_formatted($current_number_correlative);

		return ["code" => "{$slug_document_type} Nº {$next_number}-{$year}-{$user->sigla}-{$office->sigla}{$destination_office_sigla}/{$company->first_part_code}", 'correlative' => $next_number];

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



	public function get_internal_code($current_office_id, $document_type_id, $destination_office_id)
	{

		$office = Office::find($current_office_id);

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

			if ($document_type->office) {
				return "{$slug_document_type}{$document_type->office->start_with}-{$slug_office}-{$company->first_part_code}/{$company->second_part_code}-${year}";
			}

			return "{$slug_document_type}1-{$slug_office}-{$company->first_part_code}/{$company->second_part_code}-${year}";
		}

		$last_internal_code_arr = explode('-', $last_order->internal_code);

		if (end($last_internal_code_arr) != $year) {
			$next_number = 1;
		} else {
			$first_index = $last_internal_code_arr[0];
			$old_number = substr($first_index, $document_type_name_length);
			$next_number = (int)$old_number + 1;
		}		

		return "{$slug_document_type}{$next_number}-{$slug_office}-{$company->first_part_code}/{$company->second_part_code}-${year}";

	}

	public function store_response_solicitude(LoggedSolicitudeRequest $request)
	{
		$data = $request->except('date', 'time');
		$data['number'] = "";

		if (!$data['folios']) {
			$data['folios'] = 1;
		}

		$today = Carbon::now();
		$year = $today->format('Y');
		$parent_order_id = $request->parent_order_id;

		$document_type = DocumentType::find($request->document_type_id);

		$offices_id_arr = explode(',', $request->multiple_offices_id);

		if ($document_type->is_multiple) {
			$data['office_id'] = 0;
		}

		$date = Carbon::now()->format('Y-m-d H:i:s');

		$last_order = DB::table('orders')
			->orderBy('id', 'DESC')
			->where('parent_order_id', 0)
			->where('code', '!=', "")
			//->whereMultiple(0)
			->first();

		$next_number = 1;
		if (!empty($last_order)) {
			$old_year = substr($last_order->code, 0, 4);

			$next_number = 1;
			
			if ($old_year  == $year) {
				$number_extracted = substr($last_order->code, 4);
				$next_number = (int)$number_extracted + 1;
			}
		}

		$data['date'] = $date;

		$entity_logged = Entity::with('office')
			->find(Auth::user()->entity_id);
			
		$new_order = new Order();
		$new_order->fill($data);

		$user_id = Auth::user()->id;
		
	
		$new_order->entity_id = $entity_logged->id;
		$new_order->user_id = Auth::user()->id;
		$new_order->office_id_origen = $entity_logged->office_id;
		$new_order->office_id = $data['office_id'];
		$new_order->status = 1;
		$new_order->notes = $data['observations'];
		$new_order->year = Carbon::now()->format('Y');

		$unique_string = time().time();

		if (Input::hasFile('attached_file')) {
			$file = Input::file('attached_file');
			$file->move(public_path() . '/archivos/tramites/', $unique_string.$file->getClientOriginalName());
			$path = '/archivos/tramites/'.$unique_string.$file->getClientOriginalName();
			$new_order->attached_file = $path;
		}

		$internal_code_arr = $this->get_internal_codev2($entity_logged->office_id, $request->document_type_id, $data['office_id']);
		$new_order->internal_code = $internal_code_arr['code'];
		$new_order->internal_code_correlative = $internal_code_arr['correlative'];
		$new_order->save();

		//$new_order->save();

		if ($request->term != "" && $request->term != 0) {
			$term_end = Carbon::now()->addDays($request->term)->format('Y-m-d H:i:s');
			$new_order->term_end = $term_end;
			$new_order->save();
		}

		$number_of_characters = strlen($next_number);
		$total_length = 7;

		if ($number_of_characters >= 7) {
			$code = $next_number;
		} else {
			$left = $total_length - $number_of_characters;
			$code = str_repeat("0", $left)."{$next_number}";
		}

		$new_order->code = "{$new_order->year}{$code}";
		$new_order->save();

		$new_detail = new DetailOrder();
		$new_detail->order_id = $new_order->id;
		$new_detail->status = 1;
		$new_detail->office_id_origen = $entity_logged->office_id;
		$new_detail->office_id = $data['office_id'];
		$new_detail->observations = "Documento interno generado";
		$new_detail->last = true;
		$new_detail->user_id = Auth::user()->id;
		$new_detail->save();


		$codes_arr = [];
		$codes_arr[] = $new_order->code;


		//CC
		$offices_arr = [];

		if ($request->offices_cc_arr != "" && $request->offices_cc_arr != "null") {
			$offices_arr = explode(',', $request->offices_cc_arr);
		}

		foreach ($offices_arr as $key => $office_id) {
			//if ($office_id != $new_order->office_id) {
				$other_order = $new_order->replicate();
				$other_order->code = $new_order->code."--CC".$office_id;
				//$other_order->internal_code = "";
				$other_order->office_id = $office_id;
				$other_order->status = 5;
				$other_order->parent_order_id = $new_order->id;
				$other_order->save();

				$new_detail_order = new DetailOrder();
				$new_detail_order->office_id_origen = $entity_logged->office_id;
				$new_detail_order->order_id = $other_order->id;
				$new_detail_order->status = 5;
				$new_detail_order->office_id = $other_order->office_id;
				$new_detail_order->observations = "";
				$new_detail_order->last = true;
				$new_detail_order->user_id = $user_id;
				$new_detail_order->save();
			//}
		}
		
		//multiple
		if ($document_type->is_multiple) {

			$offices_id_arr = explode(',', $request->multiple_offices_id);

			$new_order->office_id = $offices_id_arr[0];
			//$new_order->multiple = 1;
			//$internal_code_arr = $this->get_internal_codev2($entity_logged->office_id, $request->document_type_id, $offices_id_arr[0]);
			//$new_order->internal_code = $internal_code_arr['code'];
			//$new_order->internal_code_correlative = $internal_code_arr['correlative'];
			$new_order->save();

			$new_detail->office_id = $offices_id_arr[0];
			$new_detail->save();

			$upper_orders = OrderOrder::whereLastOrderId($parent_order_id)
				->get();

			DB::table('order_order')
				->where('last_order_id', $parent_order_id)
				->update(['last_order_id' => $new_order->id]);

			DB::table('order_order')->insert(
	    		[	
	    			'order_id' => $new_order->id,
	    			'parent_order_id' => $parent_order_id,
	    			'last_order_id' => $new_order->id,
	    			'created_at' => $date,
	    			'updated_at' => $date,
	    		]
			);

			DB::table('order_multiple_document')->insert(
	    		[	
	    			//'order_id' => $new_order->id,
	    			'parent_order_id' => $parent_order_id,
	    			'order_id' => $new_order->id,
	    			'created_at' => $date,
	    			'updated_at' => $date,
	    		]
			);

			foreach ($offices_id_arr as $key => $office_id_) {

					if ($key == 0) {
						continue;
					}

					$other_order = $new_order->replicate();
					$other_order->code = $this->get_next_order_code();
					//$other_order->code = "";
					//$other_order->internal_code = $this->get_internal_code($entity_logged->office_id, $request->document_type_id);
					//$other_order->internal_code = "";
					$other_order->office_id = $office_id_;
					$other_order->status = 1;
					//$other_order->multiple = 1;
					$other_order->save();

					$codes_arr[] = $other_order->code;

					$new_detail_order = new DetailOrder();
					$new_detail_order->office_id_origen = $entity_logged->office_id;
					$new_detail_order->order_id = $other_order->id;
					$new_detail_order->status = 1;
					$new_detail_order->office_id = $office_id_;
					$new_detail_order->observations = "Documento múltiple derivado a varias oficinas.";
					$new_detail_order->last = true;
					$new_detail_order->user_id = $user_id;
					$new_detail_order->save();

					foreach ($upper_orders as $key => $upp_order) {
						$duplicate = $upp_order->replicate();
						$duplicate->last_order_id = $other_order->id;
						$duplicate->save();
					}

					// DB::table('order_order')
					// 	->where('last_order_id', $parent_order_id)
					// 	->update(['last_order_id' => $new_order->id]);
					DB::table('order_order')->insert(
			    		[	
			    			'order_id' => $other_order->id,
			    			'parent_order_id' => $parent_order_id,
			    			'last_order_id' => $other_order->id,
			    			'created_at' => $date,
			    			'updated_at' => $date,
			    		]
					);
						
					DB::table('order_multiple_document')->insert(
			    		[	
			    			//'order_id' => $new_order->id,
			    			'parent_order_id' => $parent_order_id,
			    			'order_id' => $other_order->id,
			    			'created_at' => $date,
			    			'updated_at' => $date,
			    		]
					);
					//duplicar todo el recorrido del doc padre en order_order por cada oficina.


					// DB::table('order_order')->insert(
			  //   		[	
			  //   			'order_id' => $other_order->id,
			  //   			'parent_order_id' => 0,
			  //   			'last_order_id' => $other_order->id,
			  //   			'created_at' => $date,
			  //   			'updated_at' => $date,
			  //   		]
					// );
			}

			DB::table('orders')
				->where('id', $parent_order_id)
				->update(['office_id' => 0, 'status' => 7, 'updated_at' => $date]);

			DB::table('details_order')
				->where('order_id', $parent_order_id)
				->update(['last' => 0]);

			$codes_string = implode(', ', $codes_arr);

			$new_detail = new DetailOrder();
			$new_detail->order_id = $parent_order_id;
			$new_detail->status = 7;
			$new_detail->office_id_origen = $entity_logged->office_id;
			$new_detail->office_id = 0;
			$new_detail->observations = "Respondido con {$new_order->internal_code}({$codes_string})";
			$new_detail->last = true;
			$new_detail->user_id = Auth::user()->id;
			$new_detail->save();


			return response()->json(['title' => 'Operación Exitosa', 'message' => 'Se ha registrado correctamente su solicitud'], 201);
		}

		// $offices_arr = [];

		// if ($request->offices_arr != "" && $request->offices_arr != "null") {
		// 	$offices_arr = explode(',', $request->offices_arr);
		// }

		// foreach ($offices_arr as $key => $office_id) {
		// 	if ($office_id != $new_order->office_id) {
		// 		$other_order = $new_order->replicate();
		// 		$other_order->code = $new_order->code."--COPY".$office_id;
		// 		$other_order->office_id = $office_id;
		// 		$other_order->status = 5;
		// 		$other_order->parent_order_id = $new_order->id;
		// 		$other_order->save();

		// 		$new_detail_order = new DetailOrder();
		// 		$new_detail_order->office_id_origen = $entity_logged->office_id;
		// 		$new_detail_order->order_id = $other_order->id;
		// 		$new_detail_order->status = 5;
		// 		$new_detail_order->office_id = $other_order->office_id;
		// 		$new_detail_order->observations = "";
		// 		$new_detail_order->last = true;
		// 		$new_detail_order->user_id = $user_id;
		// 		$new_detail_order->save();
		// 	}
		// }

		DB::table('order_order')
			->where('last_order_id', $parent_order_id)
			->update(['last_order_id' => $new_order->id]);

		DB::table('order_order')->insert(
    		[	
    			'order_id' => $new_order->id,
    			'parent_order_id' => $parent_order_id,
    			'last_order_id' => $new_order->id,
    			'created_at' => $date,
    			'updated_at' => $date,
    		]
		);

		DB::table('orders')
			->where('id', $parent_order_id)
			->update(['office_id' => $data['office_id'], 'status' => 7, 'updated_at' => $date]);

		DB::table('details_order')
			->where('order_id', $parent_order_id)
			->update(['last' => 0]);

		$new_detail = new DetailOrder();
		$new_detail->order_id = $parent_order_id;
		$new_detail->status = 7;
		$new_detail->office_id_origen = $entity_logged->office_id;
		$new_detail->office_id = $data['office_id'];
		$new_detail->observations = "Respondido con {$new_order->internal_code}({$new_order->code})";
		$new_detail->last = true;
		$new_detail->user_id = Auth::user()->id;
		$new_detail->save();

		return response()->json(['title' => 'Operación Exitosa', 'message' => 'Se ha registrado correctamente su solicitud'], 201);
	}


	public function view_order($id) {
		$order = Venta::with('products')
			->with('persona')
			->find($id);

		$company = Company::first();

		return view('store.order.detail_view', compact('order', 'company'));
	}

	public function confirm($id) {
		$order = Venta::with('products')
			->with('persona')
			->find($id);

		foreach ($order->products as $key => $product) {
			$product->stock = $product->stock - $product->pivot->cantidad;
			$product->save();
		}

		$order->estado = "2";
		$order->save();
		return;
	}

	public function search(Request $request) {

				$identity_document = $request->identity_document;
				$document = $request->document;

				$orders = DB::table('orders')
					->join('entities', 'orders.entity_id', '=', 'entities.id')
					// ->where(function ($query) use($identity_document) {
		   //             $query->where('orders.code', $identity_document)
		   //                   ->orWhere('entities.identity_document', $identity_document);
		   //         	})
		           	->where('orders.code', $identity_document)
					->where('entities.identity_document', $document)
		           	//->where('orders.year', $year)
		           	->select(['orders.id as id'])
		           	->where('orders.deleted_at', null)
		           	//->where('entities.deleted_at', null)
		           	->get();
				// $orders = DB::table('orders')
				// 	->join('entities', 'orders.entity_id', '=', 'entities.id')
				// 	->where(function ($query) use($identity_document) {
		  //              $query->where('orders.code', $identity_document)
		  //                    ->orWhere('entities.identity_document', $identity_document);
		  //          	})
		  //          	->select(['orders.id as id'])
		  //          	->where('orders.deleted_at', null)
		  //          	->where('entities.deleted_at', null)
		  //          	->get();

				if (empty($orders)) {
					return response()->json(['title' => 'Aviso', 'message' => 'No se ha encontrado el documento.'], 400);
				}

				$ids = [];

				foreach ($orders as $key => $order) {
					$ids[] = $order->id;
				}

				return response()->json(['ids' => $ids], 200);

        try {
            //$llaveSecreta = "6Lf4sWMaAAAAAOO_17d8yKno6LFrD4m_jLRWds2u";
            //publickey 6Lf4sWMaAAAAACucNiVRqmvIJfMKmINEru9glEVX
        	
            //$llaveSecreta = "6LfyY0ggAAAAAOPNZ7aBwx5q9xOM_F-JLkBK6MCI";
            $llaveSecreta = "6LedaUggAAAAAJVje4AU5W1aMWlLlLGVxffwgZb4";

            $ip = $_SERVER['REMOTE_ADDR'];
            $captcha = $request->input('recaptcha');
            $respuesta = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$llaveSecreta&response=$captcha&remoteip=$ip");
            $aux = json_decode($respuesta,true);

            if ($aux['success'] == true)
            {
				$identity_document = $request->identity_document;
				$year = $request->year;

				$orders = DB::table('orders')
					//->join('entities', 'orders.entity_id', '=', 'entities.id')
					// ->where(function ($query) use($identity_document) {
		   //             $query->where('orders.code', $identity_document)
		   //                   ->orWhere('entities.identity_document', $identity_document);
		   //         	})
		           	->where('orders.code', $identity_document)
		           	//->where('orders.year', $year)
		           	->select(['orders.id as id'])
		           	->where('orders.deleted_at', null)
		           	//->where('entities.deleted_at', null)
		           	->get();
				// $orders = DB::table('orders')
				// 	->join('entities', 'orders.entity_id', '=', 'entities.id')
				// 	->where(function ($query) use($identity_document) {
		  //              $query->where('orders.code', $identity_document)
		  //                    ->orWhere('entities.identity_document', $identity_document);
		  //          	})
		  //          	->select(['orders.id as id'])
		  //          	->where('orders.deleted_at', null)
		  //          	->where('entities.deleted_at', null)
		  //          	->get();

				if (empty($orders)) {
					return response()->json(['title' => 'Aviso', 'message' => 'No se ha encontrado el documento'], 400);
				}

				$ids = [];

				foreach ($orders as $key => $order) {
					$ids[] = $order->id;
				}

				return response()->json(['ids' => $ids], 200);
            }
            else{
            	return response()->json(['title' => 'Error', 'message' => 'Actualice la página. El reCAPTCHA ya fue utilizado.'], 400);
            }

        } catch (Exception $e) {
            return response()->json(['title' => 'Error', 'message' => 'Error procesando el captcha'], 400);
        }
	}


	public function search_student_by_identity_document(Request $request) {

		$identity_document = $request->dni;
		$year = $request->year;
		
		$entity = DB::table('entities')
	       	->where('identity_document', $identity_document)
	       	->where('deleted_at', null)
	       	->first();

		if (!$entity) {
			return ['entity' => [], 'profession' => []];
			//return response()->json(['title' => 'Aviso', 'message' => 'No se ha encontrado el documento.'], 400);
		}

		$profession = DB::table('professions')
			->where('id', $entity->profession_id)
			->first();

		return ['entity' => $entity, 'profession' => $profession];
	}
	public function search_student(Request $request) {

		$identity_document = $request->dni;
		$year = $request->year;


		$entity = DB::table('entities')
	       	->where('id', $identity_document)
	       	->where('deleted_at', null)
	       	->first();

		if (!$entity) {
			return ['entity' => [], 'order' => []];
			//return response()->json(['title' => 'Aviso', 'message' => 'No se ha encontrado el documento.'], 400);
		}

		$order = Order::whereEntityId($entity->id)
			//->with('debt_details.office')
			//->with(['debt_details' => function($query){
				//$query->orderBy('office_id')
					//->with('office');
			//}])
			->where('year', $year)
			->whereStatus(1)
			->first();

		if (!count((array)$order)) {
			return response()->json(['message' => "Estudiante no matriculado."], 400);
		}

		$order_details = DB::table('details_order')
			->where('details_order.order_id', $order->id)
			->join('offices', 'details_order.office_id', '=', 'offices.id')
			->where('details_order.deleted_at', NULL)
			->where('details_order.status', 0)
			->orderBy('offices.upper_office_id')
			->get(['details_order.id', 'details_order.office_id', 'offices.name as office_name', 'details_order.observations']);

		return ['entity' => $entity, 'order' => $order, 'order_details' => $order_details];
	}

	public function search_all_students(Request $request) {

		$identity_document = $request->nameProduct;

		$entity = DB::table('entities')
	       	->where('identity_document', 'LIKE', "%$identity_document%")
	       	->where('status', 1)
	       	->where('deleted_at', null)
	       	->orWhere('name', 'LIKE', "%$identity_document%")
	       	->where('status', 1)
	       	->where('deleted_at', null)
	       	->orWhere('paternal_surname', 'LIKE', "%$identity_document%")
	       	->where('status', 1)
	       	->where('deleted_at', null)
	       	->orWhere('maternal_surname', 'LIKE', "%$identity_document%")
	       	->where('status', 1)
	       	->where('deleted_at', null)
	       	->get(array('id', DB::raw('CONCAT(identity_document,"-", name," ", paternal_surname, " ", maternal_surname) as name')));

	   	return $entity;
	}

	public function search_parent($identity_document) {

		$parent = DB::table('professions')
	       	->where('code', $identity_document)
	       	->where('deleted_at', null)
	       	->first();
			return ['success' => false];

		if (!$parent) {
			return ['success' => false];
			//return response()->json(['title' => 'Aviso', 'message' => 'No se ha encontrado el documento.'], 400);
		}

		return ['parent' => $parent, 'success' => true];
	}

	public function details_document_view(Request $request) {
		$products = [];
		$total = 0;

		$order_id = $request->order_id;
		$ids = explode(',', $order_id);
		$id = $ids[0];
		
		$last_order = OrderOrder::whereOrderId($id)
			->get();

		if (empty($last_order)) {
			$orders = [];
			$orders_related = [];
			
			return view('store.checkout.shopping_cart', compact('orders', 'search_button', 'orders_related'));
		}

		$last_order_id = $last_order[0]->last_order_id;
		
		$orders = Order::where('id', $last_order_id)
			->with('document_type')
			->with('entity.office')
			->with('office')
			->with(['details' => function ($query) {
				$query->with('state')
					->with('office.entity');
			}])
			->orderBy('created_at', 'DESC')
			->get();

		$orders_related = OrderOrder::whereLastOrderId($last_order_id)
			->where('parent_order_id', '!=', 0)
			->with(['parent_order' => function($query){
				$query->with(['details' => function($query){
					$query->with('office')
						->with('office_origen')
						->with('state');
					$query->with('user.entity');
				}])	
				->with(['children' => function($query){
					$query->with('office');
					$query->with(['details' => function($query){
						$query->with('user.entity')
							->with('state')
							->with('office_origen');
							$query->with('office');
					}]);	
				}])
				->with('entity')
					->with('tupa');
			}])
			->orderBy('id', 'DESC')
			->get();

		// $orders = Order::where('id', $id)
		// 	->with('document_type')
		// 	->with('entity')
		// 	->with('office')
		// 	->with(['details' => function ($query) {
		// 		$query->with('state')
		// 			->with('office.entity');
		// 	}])
		// 	->orderBy('created_at', 'DESC')
		// 	->get();

		// $categories = Categoria::whereCondicion(1)
		// 	->select(['idcategoria', 'nombre as name', 'slug'])
		// 	->get();


		$search_button = true;
		return view('store.checkout.shopping_cart', compact('orders', 'search_button', 'orders_related'));
	}

	public function request_completed(Request $request) {

		$order_id = $request->order_id;

		$order = Order::select(['id', 'code', 'entity_id', 'date', 'subject', 'folios'])
			->with('entity')
			->find($order_id);

		// $categories = Categoria::whereCondicion(1)
		// 	->select(['idcategoria', 'nombre as name', 'slug'])
		// 	->get();

		$search_button = true;

		return view('store.completed', compact('order', 'search_button'));
	}

	public function request_constancia(Request $request)
	{

		$order_id = $request->index;

		$order = Order::select(['id', 'code', 'entity_id', 'date', 'subject', 'folios'])
			->with('entity')
			->find($order_id);

		$name = "-";
		$dni = "-";
		$business_name = "-";
		$ruc = "-";

		if ($order->entity->type_document == 1) {
			$name = "{$order->entity->name} {$order->entity->paternal_surname} {$order->entity->maternal_surname}";
			$dni = $order->entity->identity_document;
		}

		if ($order->entity->type_document == 2) {
			$ruc = $order->entity->identity_document;
			$business_name = $order->entity->name;
		}


		$pdf = app('Fpdf');
        $pdf->AddPage();

		$pdf->AddFont('Calibri','','calibri.php');
        $pdf->AddFont('Calibri-Bold','','calibri_b.php');
        $pdf->AddFont('Calibri-Italic','','calibri_i.php');
        $pdf->AddFont('Calibri-BoldItalic','','calibri_bi.php');
        $normal_space = 4.5;
        $norma_font_size = 10;
        $space_between_sections = 9;
        $pdf->Rect(5, 5, 200, 287, 'D');

        $pdf->Image('assets/cabeceras/muni_pachia.png', 13 , 10, 60);
       // $pdf->Image(asset('assets/cabeceras/unjbg-escudo.png'), 170 , 5, 25);

        $pdf->Ln();

        $pdf->SetY(15);

        $pdf->SetFont('Calibri', '', 20);
        $pdf->Cell(0, 8, utf8_decode('Constancia de recepción de solicitud'), 0, "", "R");
        $pdf->Ln();

        $pdf->SetFont('Calibri', '', 12);
        $pdf->Cell(0, 8, utf8_decode('Mesa de partes virtual de la Municipalidad Distrital de Pachía'), 0, "", "R");
        $pdf->Ln();

        // $pdf->SetFont('Calibri', '', 9);
        // $pdf->cell(4);
        // $pdf->cell(30, 10, utf8_decode("Avenida Arias Araguez S/N Tacna - Pachía"), 0, "", "L");

        // $pdf->SetX(98);
        // $pdf->cell(10, 10, utf8_decode("Tacna - Perú"), 0, "", "C");

        // $pdf->SetX(162);

        // $pdf->cell(30, 10, utf8_decode("Teléfono: 968 360 022"), 0, "", "R");
        // $pdf->Ln(8);
        $pdf->SetY(35);
        $pdf->SetTextColor(211, 211, 211);
        $pdf->cell(190, 1, "", "B", "", "C");

        //$pdf->Ln();
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetY(40-2);
        $pdf->SetFont('Calibri-Bold', '', 13);
        $pdf->Cell(0, 8, utf8_decode('Código'), 0, "", "C");
        $pdf->Ln();
        $pdf->SetFont('Calibri', '', 20);
        $pdf->SetTextColor(240, 128, 128);
        $pdf->Cell(0, 8, utf8_decode($order->code), 0, "", "C");

        $pdf->SetY(70-3);

        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Calibri-Bold', '', 12);
        //$pdf->Cell(0, 5, utf8_decode('##'), 0, "", "L", true);
        $pdf->Ln(3);

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Calibri-Bold', '', 13);

        $pdf->Cell(90, 8, utf8_decode('DNI'), 0, "", "C");
        $pdf->Cell(90, 8, utf8_decode('Solicitante'), 0, "", "C");

        $pdf->Ln();
        $pdf->SetFont('Calibri', '', 13);
		$pdf->Cell(90, 8, utf8_decode($dni), 0, "", "C");
        $pdf->Cell(90, 8, utf8_decode($name), 0, "", "C");

        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('Calibri-Bold', '', 13);
        $pdf->Cell(90, 8, utf8_decode('RUC'), 0, "", "C");
        $pdf->Cell(90, 8, utf8_decode('Razón Social'), 0, "", "C");

		$pdf->Ln();
        $pdf->SetFont('Calibri', '', 13);
		$pdf->Cell(90, 8, utf8_decode($ruc), 0, "", "C");
        $pdf->Cell(90, 8, utf8_decode($business_name), 0, "", "C");

        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('Calibri-Bold', '', 13);
        $pdf->Cell(180, 8, utf8_decode('Asunto'), 0, "", "C");
		$pdf->Ln();
		$pdf->SetFont('Calibri', '', 13);
		$pdf->Cell(180, 8, utf8_decode($order->subject), 0, "", "C");

        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('Calibri-Bold', '', 13);
        $pdf->Cell(90, 8, utf8_decode('Fecha'), 0, "", "C");
        $pdf->Cell(90, 8, utf8_decode('Nº exp. a reconsiderar'), 0, "", "C");

		$pdf->Ln();
        $pdf->SetFont('Calibri', '', 13);
		$pdf->Cell(90, 8, utf8_decode(Carbon::parse($order->date)->format('d/m/Y H:i:s')), 0, "", "C");
        $pdf->Cell(90, 8, utf8_decode('-'), 0, "", "C");

        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();

		$pdf->SetFont('Calibri-Bold', '', 13);
        $pdf->Cell(90, 8, utf8_decode('Nro. folios'), 0, "", "C");
        $pdf->Cell(90, 8, utf8_decode('Cant. anexos'), 0, "", "C");

        $pdf->Ln();
        $pdf->SetFont('Calibri', '', 13);
		$pdf->Cell(90, 8, utf8_decode($order->folios), 0, "", "C");
        $pdf->Cell(90, 8, utf8_decode('0'), 0, "", "C");

        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();

        $pdf->SetFont('Calibri', '', 13);
		$pdf->MultiCell(180, 8, utf8_decode('El presente documento es una constancia de haber presentado una solicitud en la mesa de partes virtual de la Municipalidad Distrital de Pachía. Se procederá a su revisión y la asignación de número de expediente una vez sea aceptada.'), 0, "L", 0);

        $pdf->Output();


	}


		public function get_request_completed(Request $request) {


			return redirect('/');
			//$order_id = $request->order_id;
			//$order = Order::select(['id', 'code'])->first();

		// $order = Order::select(['id', 'code'])
		// 	->first();

			// $order = DB::table('orders')
			// 	->first();

			$order = Order::select(['id', 'code', 'entity_id', 'date', 'subject', 'folios'])
				->with('entity')
				->find(98);

			//$order = $order[0];

			// $categories = Categoria::whereCondicion(1)
			// 	->select(['idcategoria', 'nombre as name', 'slug'])
			// 	->get();

			$search_button = true;

			return view('store.completed', compact('order', 'search_button'));
	}

	public function request_completed_email(Request $request)
	{
		$to_email = $request->email;
		$company = Company::first();
		$from_email = $company->email;
		$order = Order::with('entity')
			->find($request->order_id);

		$data = [
			'company' => $company->name,
			'subject' => 'Trámite documentario virtual',
			'code' => $request->order_code,
			'entity' => $order->entity->name." ".$order->entity->paternal_surname." ".$order->entity->maternal_surname,
			'identity_document' => $order->entity->identity_document,
		];

		//Mail::to($to_email)->send(new SendMailOrder($data, $from_email));
		Mail::send('emails.order', ['company' => $company->name, 'subject' => 'Trámite documentario virtual', 'code' => $request->order_code, 'entity' => $order->entity->name." ".$order->entity->paternal_surname." ".$order->entity->maternal_surname, 'identity_document' => $order->entity->identity_document], function ($m) use ($from_email, $data, $to_email) {
			$m->from($from_email, $data['company']);
			$m->to($to_email, "Entidad")->subject('Solicitud registrada');
		});


		return response()->json(['title' => 'Operación Exitosa', 'message' => 'Se ha  enviado correctamente el mensaje.'], 200);
	}

	public function update_status($order_id, Request $request)
	{	
		$order = Order::find($order_id);

		if ($request->status == 2) {
			$concepts_payed = DetailOrder::whereOrderId($order_id)
				->whereStatus(1)
				->get();

			if (count($concepts_payed)) {
				return response()->json(['title' => 'Error', 'message' => 'No se puede anular ya que existen pagos hechos.'], 400);

			}
		}

		$order->status = $request->status;
		$order->save();

		return response()->json(['title' => 'Operación Exitosa', 'message' => 'Se ha  cambiado el estado de la matrícula correctamente.'], 200);

	}

}
