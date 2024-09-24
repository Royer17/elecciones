@extends('store.layouts.index')
@section('content')
<body style="">
	<div id="header" class="fixed-top">
	</div>

	@foreach($orders as $order)
    <div id="result_ct" class="container mt-1 small" style="margin-bottom: 50px;">
		<div id="20190011135391" class="card mt-2">
		  <div class="card-header font-bold h6" style="text-transform:uppercase">Código de Registro :  {{ $order['code'] }}</div>
		  <div class="card-body">
		  	<div class="text-mutted">{{ $company['name'] }}</div>
				<!-- /////////////////// -->
				<div class="text-mutted"><small></small></div>
				<h4 class="font-bold text-uppercase mb-2">{{ $order['document_type']['name'] }} {{ $order['number'] }} - MP{{ $order['code'] }}</h4>
				<p class="mb-0" style="color: #000; line-height: .9;">
					<span class="card-text">{{ $order['office']['name'] }}</span><br>
					<span class="card-text font-italic">JEFE DE OFICINA</span>
				</p>
				<div class="text_user_detail py-2">
					<label class="line"></label>
					<span class="name_detail">{{ $order['entity']['name'] }} {{ $order['entity']['paternal_surname'] }} {{ $order['entity']['maternal_surname'] }}</span>
				</div>
				<!-- /////////////////// -->

			<blockquote class="blockquote mb-1">
				<div class="card-text"> {{ $order['entity']['name'] }} {{ $order['entity']['paternal_surname'] }} {{ $order['entity']['maternal_surname'] }} </div>
			  <div class="card-text "> {{ $order['document_type']['name'] }} {{ $order['number'] }}</div>

			  <div class="card-text small" style="">{{ $order['office']['name'] }}</div>

			  <div class="card-text font-italic" style="margin-top: -7px; font-size: 65%;">JEFE DE OFICINA </div>
			  <footer class="blockquote-footer" style="margin-top: -5px; "><small></small></footer>
			</blockquote>

		    <div class="row">
		    	<div class="text-muted col-2">{{ $order['created_at']->format('d/m/Y') }}</div>
		    	<div class="text-muted col-2">{{ $order['folios'] }}&nbsp;folio(s)</div>
		    </div>
			<div class="mb-1" style="padding: 3px 5px 3px 10px; position: relative;">
				<span class="" style="font-family: 'Georgia', 'Apple Symbols', serif; font-size: 1.5em; position: absolute; left: 0px; top: 0px;">“</span>
				<div class="d-inline-block" style="padding-right: 10px; position: relative;">
					{{ $order['subject'] }}
					<span class="" style="font-family: 'Georgia', 'Apple Symbols', serif; font-size: 1.5em; position: absolute; right: 0px; top: 0px;">”</span>
				</div>
			</div>

			<div class="card-text text-muted" style="border: 1px #ddd dashed; padding: 3px 7px;">{{ $order['notes'] }}</div>

	  		@if($order['attached_file'])
			  	<div class="">
			  		<label>Archivo adjuntado</label>
			  		<a href="{{ $order['attached_file'] }}" target="_blank">File</a>
			  	</div>
		  	@endif
		  </div>
		  <table class="table">
			<thead>
			<tr>
				<th scope="col" style="width: 50px;">#</th>
				<th scope="col" style="width: 175px;">Hoja de Ruta</th>
				<th scope="col" style="width: 175px;">Dependencia Origen</th>
				<th scope="col" style="width: 175px;">Dependencia Destino</th>
				<th scope="col" style="width: 175px;">Documento</th>
				<th scope="col" style="width: 175px;">Estado</th>
				<th scope="col" style="width: 175px;">Observaciones</th>
				<th scope="col">Fecha de Emisión</th>
				<th scope="col">Fecha de Recepción</th>
			</tr>
			</thead>
			<tbody>
			@php
				$generated_status_quantity = 0;
			@endphp

			@foreach($order['details'] as $key => $detail)
				@if($detail['status'] == 1)
					@php
						$generated_status_quantity++;
					@endphp

					@if($generated_status_quantity < 2)
						<tr id="tr_2468827" class="">
							<td id="th_2468827" scope="row">{{ $key+1 }}</td>
							<td id="th_2468827" scope="row">{{ $order['code'] }} - {{ $order['year'] }}</td>
							@if($key == 0)
							<td id="th_2468827" scope="row">{{ $order['parent'] ? $order['parent']['office']['name'] : 'WEB' }}</</td>
							@else
							<td id="th_2468827" scope="row">{{ $order['details'][$key-1]['office']['name'] }}</td>
							@endif
							<td id="th_2468827" scope="row">{{ $detail['office']['name'] }}</td>

							<td>
								@if($detail['attached_file'] != "")
									<a href="{{ $detail['attached_file'] }}">File</a>
								@endif
							</td>
							<td>{{ $detail['state']['name'] }}</td>
							<td>{{ $detail['observations'] }}</td>

							<td>
								@if($detail['status'] == 1)
									<div>{{ $detail['created_at']->format('d/m/Y H:i:s') }} </div>
								@endif
								@if($detail['status'] == 3)
									-
								@endif

								@if($detail['status'] == 2)
									<div>{{ $detail['created_at']->format('d/m/Y H:i:s') }} </div>
								@endif

								@if($detail['status'] == 4)
									-
								@endif

							</td>
							<td>
								@if($detail['status'] == 1)
									-
								@endif

								@if($detail['status'] == 3)
									<div>{{ $detail['created_at']->format('d/m/Y H:i:s') }} </div>
								@endif

								@if($detail['status'] == 2)
									-
								@endif

								@if($detail['status'] == 4)
									<div>{{ $detail['created_at']->format('d/m/Y H:i:s') }} </div>
								@endif
							</td>
						</tr>


					@endif


				@else
				<tr id="tr_2468827" class="">
					<td id="th_2468827" scope="row">{{ $key+1 }}</td>
					<td id="th_2468827" scope="row">{{ $order['code'] }} - {{ $order['year'] }}</td>
					@if($key == 0)
					<td id="th_2468827" scope="row">{{ $order['parent'] ? $order['parent']['office']['name'] : 'WEB' }}</</td>
					@else
					<td id="th_2468827" scope="row">{{ $order['details'][$key-1]['office']['name'] }}</td>
					@endif
					<td id="th_2468827" scope="row">{{ $detail['office']['name'] }}</td>

					<td>
						@if($detail['attached_file'] != "")
							<a href="{{ $detail['attached_file'] }}">File</a>
						@endif
					</td>
					<td>{{ $detail['state']['name'] }}</td>
					<td>{{ $detail['observations'] }}</td>
					<td>
						@if($detail['status'] == 1)
							<div>{{ $detail['created_at']->format('d/m/Y H:i:s') }} </div>
						@endif
						@if($detail['status'] == 3)
							-
						@endif

						@if($detail['status'] == 2)
							<div>{{ $detail['created_at']->format('d/m/Y H:i:s') }} </div>
						@endif

						@if($detail['status'] == 4)
							-
						@endif

					</td>
					<td>
						@if($detail['status'] == 1)
							-
						@endif

						@if($detail['status'] == 3)
							<div>{{ $detail['created_at']->format('d/m/Y H:i:s') }} </div>
						@endif

						@if($detail['status'] == 2)
							-
						@endif

						@if($detail['status'] == 4)
							<div>{{ $detail['created_at']->format('d/m/Y H:i:s') }} </div>
						@endif
					</td>
				</tr>
				@endif
			@endforeach
			</tbody>
	  	</table>
	</div>
	</div>
	@endforeach
</body>
@stop
