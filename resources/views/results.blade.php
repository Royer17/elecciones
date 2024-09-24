@extends ('layouts.admin')
@section ('contenido')
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h3 class="font-bold">Resultados de los comicios estudiantiles</h3>
            @if (count($errors)>0)
            <div class="alert alert-danger">
                <ul>
                @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
                </ul>
            </div>
            @endif


          <div class="row">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Resumen de Ventas</h3>
                  <div class="box-tools pull-right" style="display: flex; align-items: center;">
                    <span>Periodo:&nbsp;</span>
                    <input type="text" value="" class="daterange-form form-control" id="myrange_date"  />
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-12">
                      <!-- <p class="text-center">
                        <strong>Sales: 1 Jan, 2014 - 30 Jul, 2014</strong>
                      </p> -->

                      <div class="chart">
                        <!-- Sales Chart Canvas -->
                        <canvas id="salesChart" style="height: 180px;"></canvas>
                      </div>
                      <!-- /.chart-responsive -->
                    </div>
                    <!-- /.col -->
                  </div>
                  <!-- /.row -->
                </div>
                <!-- ./box-body -->
              </div>
              <!-- /.box -->
            </div>
            <!-- /.col -->
          </div>




          <hr>
          <p>Gráfico de barras</p>

          <canvas id="barChart" style="height:230px"></canvas>

          <hr>

            {!!Form::model($company,['method'=>'POST','route'=>['company.update'], 'files'=>'true'])!!}
            {{Form::token()}}

            <div class="form-group mb-1">
                <label for="nombre" class="etiqueta">Nombre de la Institución</label>
                <input type="text" name="name" class="form-control" value="{{$company->name}}" placeholder="Nombre">
            </div>

            <div class="form-group mb-1">
                <label for="nombre" class="etiqueta">Logo</label>
                <input type="file" name="logo" class="form-control" value="" placeholder="LOGOTYPE">
                <br>
                @if (($company->logo)!="")
                    <img src="{{asset($company->logo)}}" height="300px">
                @endif
            </div>

            <h4 class="font-bold mt-4">Editar Datos del Presidente de APAFA</h4>

            <div class="form-group mb-1 d-none">
                <label for="nombre" class="etiqueta">Descripción</label>
                <textarea name="description" class="form-control" placeholder="Acerca de nosotros">{{$company->description}}</textarea>
            </div>

            <div class="form-group mb-1">
                <label for="nombre" class="etiqueta">Nombres y apellidos</label>
                <input type="text" name="video_url" class="form-control" value="{{$company->video_url}}" placeholder="Nombre completo">
            </div>

            {{-- <div class="form-group mb-1">
                <label for="nombre" class="etiqueta">Logo</label>
                <input type="text" name="logo" class="form-control" value="{{$company->logo}}" placeholder="Logo">
            </div> --}}

            <div class="form-group mb-1">
                <label for="nombre" class="etiqueta">DNI</label>
                <input type="text" name="address" class="form-control" value="{{$company->address}}" placeholder="Documento de identidad">
            </div>

            <div class="form-group mb-1">
                <label for="nombre" class="etiqueta">Correo electrónico</label>
                <input type="text" name="email" class="form-control" value="{{$company->email}}" placeholder="Email">
            </div>

            <div class="form-group mb-1">
                <label for="nombre" class="etiqueta">Celular</label>
                <input type="text" name="phone_1" class="form-control" value="{{$company->phone_1}}" placeholder="Celular">
            </div>
            <hr>

            <span class="font-bold h6">Soporte y mantenimiento</span>
<table>
  <thead>

  </thead>
  <tbody>
    <tr>
      <td>
        <code>Nombre del técnico de soporte</code>
      </td>
      <td><span class="h4">Luis Pérez</span></td>
    </tr>
    <tr>
      <td>
        <code>Dirección del centro de soporte</code>
      </td>
      <td><span class="h4">Calana</span></td>
    </tr>
    <tr>
      <td>
        <code>Celular</code>
      </td>
      <td><span class="h4">9459459459</span></td>
    </tr>
  </tbody>
</table>

            <!-- <div class="form-group mb-1">
                <label for="nombre" class="etiqueta">Código de formato de trámite</label>

                <div class="row align-items-center">
                    <div class="col-md-auto pr-md-1">
                        <span>informe1-TICS-</span>
                    </div>
                    <div class="col-md-3 px-md-0">
                        <input type="text" name="first_part_code" class="form-control" value="{{ $company->first_part_code }}" placeholder="">
                    </div>
                    <div class="col-md-auto px-md-1">
                        <span>/</span>
                    </div>
                    <div class="col-md-2 px-md-0">
                        <input type="text" name="second_part_code" class="form-control" value="{{ $company->second_part_code }}" placeholder="">
                    </div>
                    <div class="col-md-auto pl-md-1">
                        <span>-2022</span>
                    </div>

                </div>

            </div> -->

            <div class="form-group mb-1 d-none">
                <label for="nombre" class="etiqueta">Teléfono 2</label>
                <input type="text" name="phone_2" class="form-control" value="{{$company->phone_2}}" placeholder="Teléfono 2">
            </div>

            <div class="form-group mb-1 d-none">
                <label for="nombre" class="etiqueta">Celular 1</label>
                <input type="text" name="cellphone_1" class="form-control" value="{{$company->cellphone_1}}" placeholder="Celular 1">
            </div>

            <div class="form-group mb-1 d-none">
                <label for="nombre" class="etiqueta">Celular 2</label>
                <input type="text" name="cellphone_2" class="form-control" value="{{$company->cellphone_2}}" placeholder="Celular 2">
            </div>

            <div class="form-group mb-1 d-none">
                <label for="nombre" class="etiqueta">Whatsapp</label>
                <input type="text" name="whatsapp" class="form-control" value="{{$company->whatsapp}}" placeholder="Whatsapp">
            </div>
            {{--
            <div class="form-group mb-1">
                <label for="nombre" class="etiqueta">YAPE QR</label>
                <input type="file" name="yape_qr" class="form-control" value="" placeholder="YAPE QR">
                <br>
                @if (($company->yape_qr)!="")
                    <img src="{{asset($company->yape_qr)}}" height="300px" width="300px">
                @endif
            </div>
            --}}
            <div class="form-group mb-1 d-none">
                <label for="nombre" class="etiqueta">Imágen(870x430)</label>
                <input type="file" name="image" class="form-control" value="" placeholder="Imágen">
                <br>
                @if (($company->image)!="")
                    <img src="{{asset($company->image)}}" width="100%">
                @endif
            </div>

            <div class="form-group mt-4">
                <button class="btn btn-primary" type="submit" title="Guardar">Guardar</button>
                <button class="btn btn-danger" type="reset" title="Remover todo lo escrito">Cancelar</button>
            </div>

            {!!Form::close()!!}

        </div>
    </div>
@push ('scripts')

<script src="{{asset('/js/Chart.js')}}"></script>
<script type="text/javascript">
 /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    var areaChartData = {
      labels  : ['Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago'],
      datasets: [
        {
          label               : 'Masa grasa',
          fillColor           : 'rgba(63,134,203,1)',
          strokeColor         : 'rgba(63,134,203,1)',
          pointColor          : 'rgba(63,134,203,1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(63,134,203,1)',
          data                : [38, 37.6, 34.4, 32.8, 29.3, 55, 40]
        },
        {
          label               : 'Circ cintura',
          fillColor           : '#a6bcdf',
          strokeColor         : '#a6bcdf',
          pointColor          : '#a6bcdf',
          pointStrokeColor    : '#a6bcdf',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: '#a6bcdf',
          data                : [0, 121, 40, 43, 32, 27, 90]
        }
      ]
    }
    //-------------
    //- BAR CHART -
    //-------------
var barChartCanvas                   = document.getElementById("barChart").getContext('2d');       
    var barChart                         = new Chart(barChartCanvas);
    var barChartData                     = areaChartData
    barChartData.datasets[1].fillColor   = '#a6bcdf'
    barChartData.datasets[1].strokeColor = '#a6bcdf'
    barChartData.datasets[1].pointColor  = '#a6bcdf'
    var barChartOptions                  = {
      //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
      scaleBeginAtZero        : true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines      : true,
      //String - Colour of the grid lines
      scaleGridLineColor      : 'rgba(0,0,0,.05)',
      //Number - Width of the grid lines
      scaleGridLineWidth      : 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines: true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines  : true,
      //Boolean - If there is a stroke on each bar
      barShowStroke           : true,
      //Number - Pixel width of the bar stroke
      barStrokeWidth          : 2,
      //Number - Spacing between each of the X value sets
      barValueSpacing         : 5,
      //Number - Spacing between data sets within X values
      barDatasetSpacing       : 1,
      //String - A legend template
      legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
      //Boolean - whether to make the chart responsive
      responsive              : true,
      maintainAspectRatio     : true
    }

    barChartOptions.datasetFill = false
    barChart.Bar(barChartData, barChartOptions)

</script>
<script>
    $('#liGeneracionInterna').addClass("treeview active");
    $('#liResults').addClass("active");

  /* ChartJS
   * -------
   * Here we will create a few charts using ChartJS
   */

  // -----------------------
  // - MONTHLY SALES CHART -
  // -----------------------

  // Get context with jQuery - using jQuery's .get() method.
  var salesChartCanvas = $('#salesChart').get(0).getContext('2d');
  // This will get the first returned node in the jQuery collection.
  var salesChart       = new Chart(salesChartCanvas);

  var salesChartData = {
    labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
    datasets: [
      {
        label               : 'Electronics',
        fillColor           : 'rgb(210, 214, 222)',
        strokeColor         : 'rgb(210, 214, 222)',
        pointColor          : 'rgb(210, 214, 222)',
        pointStrokeColor    : '#c1c7d1',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgb(220,220,220)',
        data                : [65, 59, 80, 81, 56, 55, 40]
      },
      {
        label               : 'Digital Goods',
        fillColor           : 'rgba(60,141,188,0.9)',
        strokeColor         : 'rgba(60,141,188,0.8)',
        pointColor          : '#3b8bba',
        pointStrokeColor    : 'rgba(60,141,188,1)',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgba(60,141,188,1)',
        data                : [28, 48, 40, 19, 86, 27, 90]
      }
    ]
  };

    var salesChartData2 = {
    labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
    datasets: [
      {
        label               : 'Electronics',
        fillColor           : 'rgb(210, 214, 222)',
        strokeColor         : 'rgb(210, 214, 222)',
        pointColor          : 'rgb(210, 214, 222)',
        pointStrokeColor    : '#c1c7d1',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgb(220,220,220)',
        data                : [40, 60, 30, 81, 56, 55, 40]
      },
      {
        label               : 'Digital Goods',
        fillColor           : 'rgba(60,141,188,0.9)',
        strokeColor         : 'rgba(60,141,188,0.8)',
        pointColor          : '#3b8bba',
        pointStrokeColor    : 'rgba(60,141,188,1)',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgba(60,141,188,1)',
        data                : [28, 48, 40, 19, 86, 27, 90]
      }
    ]
  };

  var salesChartOptions = {
    // Boolean - If we should show the scale at all
    showScale               : true,
    // Boolean - Whether grid lines are shown across the chart
    scaleShowGridLines      : false,
    // String - Colour of the grid lines
    scaleGridLineColor      : 'rgba(0,0,0,.05)',
    // Number - Width of the grid lines
    scaleGridLineWidth      : 1,
    // Boolean - Whether to show horizontal lines (except X axis)
    scaleShowHorizontalLines: true,
    // Boolean - Whether to show vertical lines (except Y axis)
    scaleShowVerticalLines  : true,
    // Boolean - Whether the line is curved between points
    bezierCurve             : true,
    // Number - Tension of the bezier curve between points
    bezierCurveTension      : 0.3,
    // Boolean - Whether to show a dot for each point
    pointDot                : false,
    // Number - Radius of each point dot in pixels
    pointDotRadius          : 4,
    // Number - Pixel width of point dot stroke
    pointDotStrokeWidth     : 1,
    // Number - amount extra to add to the radius to cater for hit detection outside the drawn point
    pointHitDetectionRadius : 20,
    // Boolean - Whether to show a stroke for datasets
    datasetStroke           : true,
    // Number - Pixel width of dataset stroke
    datasetStrokeWidth      : 2,
    // Boolean - Whether to fill the dataset with a color
    datasetFill             : true,
    // String - A legend template
    legendTemplate          : '<ul class=\'<%=name.toLowerCase()%>-legend\'><% for (var i=0; i<datasets.length; i++){%><li><span style=\'background-color:<%=datasets[i].lineColor%>\'></span><%=datasets[i].label%></li><%}%></ul>',
    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio     : true,
    // Boolean - whether to make the chart responsive to window resizing
    responsive              : true
  };

  // Create the line chart
  salesChart.Bar(salesChartData, salesChartOptions);


  // ---------------------------
  // - END MONTHLY SALES CHART -
  // ---------------------------

  // -------------
  // - PIE CHART -
  // -------------
  // Get context with jQuery - using jQuery's .get() method.

  var pieChartCanvas = $('#pieChart').get(0).getContext('2d');
  var pieChart       = new Chart(pieChartCanvas);
  var PieData        = [
    {
      value    : 700,
      color    : '#f56954',
      highlight: '#f56954',
      label    : 'Chrome'
    },
    {
      value    : 500,
      color    : '#00a65a',
      highlight: '#00a65a',
      label    : 'IE'
    },
    {
      value    : 400,
      color    : '#f39c12',
      highlight: '#f39c12',
      label    : 'FireFox'
    },
    {
      value    : 600,
      color    : '#00c0ef',
      highlight: '#00c0ef',
      label    : 'Safari'
    },
    {
      value    : 300,
      color    : '#3c8dbc',
      highlight: '#3c8dbc',
      label    : 'Opera'
    },
    {
      value    : 100,
      color    : '#d2d6de',
      highlight: '#d2d6de',
      label    : 'Navigator'
    }
  ];
  var pieOptions     = {
    // Boolean - Whether we should show a stroke on each segment
    segmentShowStroke    : true,
    // String - The colour of each segment stroke
    segmentStrokeColor   : '#fff',
    // Number - The width of each segment stroke
    segmentStrokeWidth   : 1,
    // Number - The percentage of the chart that we cut out of the middle
    percentageInnerCutout: 50, // This is 0 for Pie charts
    // Number - Amount of animation steps
    animationSteps       : 100,
    // String - Animation easing effect
    animationEasing      : 'easeOutBounce',
    // Boolean - Whether we animate the rotation of the Doughnut
    animateRotate        : true,
    // Boolean - Whether we animate scaling the Doughnut from the centre
    animateScale         : false,
    // Boolean - whether to make the chart responsive to window resizing
    responsive           : true,
    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio  : false,
    // String - A legend template
    legendTemplate       : '<ul class=\'<%=name.toLowerCase()%>-legend\'><% for (var i=0; i<segments.length; i++){%><li><span style=\'background-color:<%=segments[i].fillColor%>\'></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>',
    // String - A tooltip template
    tooltipTemplate      : '<%=value %> <%=label%> users'
  };
  // Create pie or douhnut chart
  // You can switch between pie and douhnut using the method below.
  pieChart.Doughnut(PieData, pieOptions);
  // -----------------
  // - END PIE CHART -
  // -----------------

  /* jVector Maps
   * ------------
   * Create a world map with markers
   */
  $('#world-map-markers').vectorMap({
    map              : 'world_mill_en',
    normalizeFunction: 'polynomial',
    hoverOpacity     : 0.7,
    hoverColor       : false,
    backgroundColor  : 'transparent',
    regionStyle      : {
      initial      : {
        fill            : 'rgba(210, 214, 222, 1)',
        'fill-opacity'  : 1,
        stroke          : 'none',
        'stroke-width'  : 0,
        'stroke-opacity': 1
      },
      hover        : {
        'fill-opacity': 0.7,
        cursor        : 'pointer'
      },
      selected     : {
        fill: 'yellow'
      },
      selectedHover: {}
    },
    markerStyle      : {
      initial: {
        fill  : '#00a65a',
        stroke: '#111'
      }
    },
    markers          : [
      { latLng: [41.90, 12.45], name: 'Vatican City' },
      { latLng: [43.73, 7.41], name: 'Monaco' },
      { latLng: [-0.52, 166.93], name: 'Nauru' },
      { latLng: [-8.51, 179.21], name: 'Tuvalu' },
      { latLng: [43.93, 12.46], name: 'San Marino' },
      { latLng: [47.14, 9.52], name: 'Liechtenstein' },
      { latLng: [7.11, 171.06], name: 'Marshall Islands' },
      { latLng: [17.3, -62.73], name: 'Saint Kitts and Nevis' },
      { latLng: [3.2, 73.22], name: 'Maldives' },
      { latLng: [35.88, 14.5], name: 'Malta' },
      { latLng: [12.05, -61.75], name: 'Grenada' },
      { latLng: [13.16, -61.23], name: 'Saint Vincent and the Grenadines' },
      { latLng: [13.16, -59.55], name: 'Barbados' },
      { latLng: [17.11, -61.85], name: 'Antigua and Barbuda' },
      { latLng: [-4.61, 55.45], name: 'Seychelles' },
      { latLng: [7.35, 134.46], name: 'Palau' },
      { latLng: [42.5, 1.51], name: 'Andorra' },
      { latLng: [14.01, -60.98], name: 'Saint Lucia' },
      { latLng: [6.91, 158.18], name: 'Federated States of Micronesia' },
      { latLng: [1.3, 103.8], name: 'Singapore' },
      { latLng: [1.46, 173.03], name: 'Kiribati' },
      { latLng: [-21.13, -175.2], name: 'Tonga' },
      { latLng: [15.3, -61.38], name: 'Dominica' },
      { latLng: [-20.2, 57.5], name: 'Mauritius' },
      { latLng: [26.02, 50.55], name: 'Bahrain' },
      { latLng: [0.33, 6.73], name: 'São Tomé and Príncipe' }
    ]
  });

  /* SPARKLINE CHARTS
   * ----------------
   * Create a inline charts with spark line
   */

  // -----------------
  // - SPARKLINE BAR -
  // -----------------
  $('.sparkbar').each(function () {
    var $this = $(this);
    $this.sparkline('html', {
      type    : 'bar',
      height  : $this.data('height') ? $this.data('height') : '30',
      barColor: $this.data('color')
    });
  });

  // -----------------
  // - SPARKLINE PIE -
  // -----------------
  $('.sparkpie').each(function () {
    var $this = $(this);
    $this.sparkline('html', {
      type       : 'pie',
      height     : $this.data('height') ? $this.data('height') : '90',
      sliceColors: $this.data('color')
    });
  });

  // ------------------
  // - SPARKLINE LINE -
  // ------------------
  $('.sparkline').each(function () {
    var $this = $(this);
    $this.sparkline('html', {
      type     : 'line',
      height   : $this.data('height') ? $this.data('height') : '90',
      width    : '100%',
      lineColor: $this.data('linecolor'),
      fillColor: $this.data('fillcolor'),
      spotColor: $this.data('spotcolor')
    });
  });

</script>
@endpush
@endsection
