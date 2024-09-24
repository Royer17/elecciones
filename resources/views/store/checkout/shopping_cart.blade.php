@extends('store.layouts.index')
@section('content')
<body style="">
	<div id="header" class="fixed-top"></div>

	@foreach($orders as $order)
  <div id="result_ct" class="container mt-1 small" style="margin-bottom: 50px;">
		<div id="20190011135391" class="card mt-2">
			  <div class="card-header font-bold h6" style="text-transform:uppercase">Código de Registro :  {{ $order['code'] }}</div>
			  <div class="card-body">

					<div class="text-mutted mb-2">{{ $company['name'] }}</div>
					<h4 class="font-bold text-uppercase mb-1">{{ $order['internal_code'] }} {{ $order['code'] }}</h4>
					<h5 class="text_user_detail mb-3">Remitente:&nbsp;
						@if($order['office_id_origen'])
						<b>{{ $order['entity']['office'] ? $order['entity']['office']['name'] : "" }}</b>
						@endif

						<label class="line mx-2"></label>{{ $order['entity']['name'] }} {{ $order['entity']['paternal_surname'] }} {{ $order['entity']['maternal_surname'] }}</h5>

					<div class="table-responsive mb-4">
					  <table class="table_detail">
							<tr>
								<td class="title_table">Fecha:</td>
								<td>{{ $order['created_at']->format('d/m/Y') }}</td>
								<td class="title_table">Folios:</td>
								<td>{{ $order['folios'] }}&nbsp;folio(s)</td>
							</tr>
							<tr>
								<td class="title_table">Asunto:</td>
								<td colspan="3">“{{ $order['subject'] }}”</td>
							</tr>
							@if($order['attached_file'])
							<tr>
								<td class="title_table">Observaciones:</td>
								<td>{{ $order['notes'] }}</td>
								<td class="title_table">Archivo adjuntado:</td>
								<td> <a href="{{ $order['attached_file'] }}" target="_blank" class="btn btn-info btn-sm">Ver Archivo</a> </td>
							</tr>
							@else
							<tr>
								<td class="title_table">Observaciones:</td>
								<td colspan="3">{{ $order['notes'] }}</td>
								<td class="title_table">Archivo adjuntado:</td>
								<td>No se adjuntó archivo</td>
							</tr>
							@endif
						</table>
					</div>

					<div class="table-responsive table_solicitud">
						<table class="table table-striped table-bordered table-condensed table-hover table-sm">
							<thead class="thead-dark">
								<tr>
									<th>#</th>
									<th>Hoja de Ruta</th>
									<th>Dependencia Origen</th>
									<th>Dependencia Destino</th>
									<!-- <th>Documento</th> -->
									<th>Estado</th>
									<th>Observaciones</th>
									<th>Fecha de Emisión</th>
									<th>Fecha de Recepción</th>
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
											<td id="th_2468827" scope="row">{{ $order['code'] }}</td>
											@if($key == 0)
											<td id="th_2468827" scope="row">{{ $order['parent'] ? $order['parent']['office']['name'] : 'WEB' }}</</td>
											@else
											<td id="th_2468827" scope="row">{{ $order['details'][$key-1]['office']['name'] }}</td>
											@endif
											<td id="th_2468827" scope="row">{{ $detail['office']['name'] }}</td>

											{{-- <td>
												@if($detail['attached_file'] != "")
													<a href="{{ $detail['attached_file'] }}">File</a>
												@endif
											</td> --}}
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
									<td id="th_2468827" scope="row">{{ $order['code'] }}</td>
									@if($key == 0)
									<td id="th_2468827" scope="row">{{ $order['parent'] ? $order['parent']['office']['name'] : 'WEB' }}</</td>
									@else
									<td id="th_2468827" scope="row">{{ $order['details'][$key-1]['office']['name'] }}</td>
									@endif
									<td id="th_2468827" scope="row">{{ $detail['office']['name'] }}</td>

									{{-- <td>
										@if($detail['attached_file'] != "")
											<a href="{{ $detail['attached_file'] }}">File</a>
										@endif
									</td> --}}
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
					<!-- <div class="text-right mt-4">
						<a href="/" class="btn btn-outline-primary">Regresar</a>
					</div> -->

			  </div>

		</div>
	</div>
	@endforeach

	@foreach($orders_related as $order_related)
		@php
			$order = $order_related['parent_order'];
		@endphp
	  	<div id="result_ct" class="container mt-1 small" style="margin-bottom: 50px;">
			<div id="20190011135391" class="card mt-2">
				  <div class="card-header font-bold h6" style="text-transform:uppercase">Código de Registro :  {{ $order['code'] }}</div>
				  <div class="card-body">
				  	<div class="text-mutted">{{ $company['name'] }}</div>
						<div class="text-mutted"><small></small></div>
						<h4 class="font-bold text-uppercase mb-2">{{ $order['internal_code'] }} {{ $order['code'] }}</h4>
						<p class="mb-0" style="color: #000; line-height: .9;">
							<span class="card-text">{{ $order['office']['name'] }}</span><br>
							<span class="card-text font-italic">JEFE DE OFICINA</span>
						</p>
						<div class="text_user_detail py-2">
							<label class="line"></label>
							<span class="name_detail">{{ $order['entity']['name'] }} {{ $order['entity']['paternal_surname'] }} {{ $order['entity']['maternal_surname'] }}</span>
						</div>

						<div class="table-responsive mb-4">
						  <table class="table_detail">
								<tr>
									<td class="title_table">Fecha:</td>
									<td>{{ $order['created_at']->format('d/m/Y') }}</td>
									<td class="title_table">Folios:</td>
									<td>{{ $order['folios'] }}&nbsp;folio(s)</td>
								</tr>
								<tr>
									<td class="title_table">Asunto:</td>
									<td colspan="3">“{{ $order['subject'] }}”</td>
								</tr>
								@if($order['attached_file'])
								<tr>
									<td class="title_table">Tipo de documento:</td>
									<td>{{ $order['document_type']['name'] }}</td>
									<td class="title_table">Archivo adjuntado:</td>
									<td> <a href="{{ $order['attached_file'] }}" target="_blank" class="btn btn-info btn-sm">Ver Archivo</a> </td>
								</tr>
								@else
								<tr>
									<td class="title_table">Tipo de documento:</td>
									<td colspan="3">{{ $order['document_type']['name'] }}</td>
									<td class="title_table">Archivo adjuntado:</td>
									<td>No se adjuntó archivo</td>
								</tr>
								@endif
								<tr>
									<td class="title_table">Observaciones:</td>
									<td colspan="3">{{ $order['notes'] }}</td>
								</tr>
						  </table>
						</div>

						<div class="table-responsive table_solicitud">
							<table class="table table-striped table-bordered table-condensed table-hover table-sm">
								<thead class="thead-dark">
									<tr>
										<th>#</th>
										<th>Hoja de Ruta</th>
										<th>Dependencia Origen</th>
										<th>Dependencia Destino</th>
										<!-- <th>Documento</th> -->
										<th>Estado</th>
										<th>Observaciones</th>
										<th>Fecha de Emisión</th>
										<th>Fecha de Recepción</th>
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
												<td id="th_2468827" scope="row">{{ $order['code'] }}</td>
												@if($key == 0)
												<td id="th_2468827" scope="row">{{ $order['parent'] ? $order['parent']['office']['name'] : 'WEB' }}</</td>
												@else
												<td id="th_2468827" scope="row">{{ $order['details'][$key-1]['office']['name'] }}</td>
												@endif
												<td id="th_2468827" scope="row">{{ $detail['office']['name'] }}</td>

												{{-- <td>
													@if($detail['attached_file'] != "")
														<a href="{{ $detail['attached_file'] }}">File</a>
													@endif
												</td> --}}
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
										<td id="th_2468827" scope="row">{{ $order['code'] }}</td>
										@if($key == 0)
										<td id="th_2468827" scope="row">{{ $order['parent'] ? $order['parent']['office']['name'] : 'WEB' }}</</td>
										@else
										<td id="th_2468827" scope="row">{{ $order['details'][$key-1]['office']['name'] }}</td>
										@endif
										<td id="th_2468827" scope="row">{{ $detail['office']['name'] }}</td>

										{{-- <td>
											@if($detail['attached_file'] != "")
												<a href="{{ $detail['attached_file'] }}">File</a>
											@endif
										</td> --}}
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
						<!-- <div class="text-right mt-4">
							<a href="/" class="btn btn-outline-primary">Regresar</a>
						</div> -->

				  </div>

			</div>
		</div>
	@endforeach

</body>
@stop
@section('plugins-js')
<script type="text/javascript">
	$(document).ready(function(){
			$("#barra_detalle").addClass("py-2 justify-content-end");
			$("#barra_detalle").html("<div class='col-auto'><a href='/' class='btn btn-warning'><b>Regresar</b></a></div>");
	});
</script>
@stop
