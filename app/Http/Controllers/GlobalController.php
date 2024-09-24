<?php

namespace sisVentas\Http\Controllers;

use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Contracts\View\View;
use sisVentas\Entity;
use sisVentas\Http\Controllers\Controller;
use sisVentas\Order;

class GlobalController extends Controller {

	public function compose(View $view) {

		$entity_id = Auth::user()->entity_id;

		$entity = Entity::find($entity_id);

		//$now = Carbon::now();

		$orders_registrated = Order::whereOfficeId($entity->office_id)
			->where('status', 1)
			->where('parent_order_id', 0)
			->count();
		
		$orders_received = Order::whereOfficeId($entity->office_id)
			->where('status', 3)
			->where('parent_order_id', 0)
			->count();

		$orders_cc = Order::whereOfficeId($entity->office_id)
			->where('parent_order_id', '!=', 0)
			->count();

		$orders_finalized = Order::whereOfficeId($entity->office_id)
			->where('status', 4)
			->where('parent_order_id', 0)
			->count();

		$orders_derivated =  DB::table('orders')
				->orderBy('orders.id', 'desc')
				->join('details_order', 'orders.id', '=', 'details_order.order_id')
				->join('document_types', 'orders.document_type_id', '=', 'document_types.id')
				->join('entities', 'orders.entity_id', '=', 'entities.id')
				->leftJoin('offices', 'details_order.office_id', '=', 'offices.id')
				->join('document_statuses', 'orders.status', '=', 'document_statuses.id')
				->leftJoin('orders as parent_order', 'orders.parent_order_id', '=', 'parent_order.id')
				->leftJoin('offices as office_parent', 'parent_order.office_id', '=', 'office_parent.id')
				->where('orders.deleted_at', null)
				->where('details_order.last', 0)
				->whereIn('details_order.status', [2])
				->where('details_order.office_id_origen', $entity->office_id)
				->groupBy('orders.id')
				->get();


		$orders_derivated = count($orders_derivated);
		// foreach ($orders as $key => $order) {
		// 	$created_at = Carbon::parse($order->detail_by_date_desc->created_at);
		// 	$different_in_days = $created_at->diffInDays($now);

		// 	if ($different_in_days > 2) {
		// 		$quantity_requests_to_review++;
		// 	}

		// }
		$quantity_requests_to_review = 0;

		$view->with('quantity_requests_to_review', $quantity_requests_to_review)
			->with('orders_registrated', $orders_registrated)
			->with('orders_received', $orders_received)
			->with('orders_cc', $orders_cc)
			->with('orders_finalized', $orders_finalized)
			->with('orders_derivated', $orders_derivated);






	}
}
