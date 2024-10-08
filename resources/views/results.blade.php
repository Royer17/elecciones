@extends ('layouts.admin')
@section ('contenido')

    <h2>Resultados de los comicios estudiantiles</h2>
    <hr>

    @foreach ($results as $result)
    <section class="mb-5">
      <p class="h2 text-center">{{ $result['nivel_text'] }} - Alcaldía <span class="text-muted">({{ $result['total_students'] }} estudiantes)</span></p>
      <p>Estudiantes que votaron: {{ $result['total_students_voted'] }}</p>
      <p>Estudiantes que no votaron: {{ $result['total_students'] - $result['total_students_voted'] }}</p>
      <p>Porcentaje: {{ ($result['total_students_voted'] / $result['total_students']) * 100 }}%</p>
      
      <div class="row m-3">
        @foreach ($result['candidates_results'] as $candidate)
        <div class="col-md-6 border">
            <p>Candidato: <b>{{ $candidate['candidate'] }}</b></p>
            <p>Votos: {{ $candidate['votes'] }}</p>
            <p>Porcentaje: {{ $candidate['percentage'] }}%</p>
        </div>
        @endforeach
      </div>

      <div class="chart">
        <input type="hidden" name="values" value="{{ json_encode($result['values']) }}">
        <input type="hidden" name="labels" value="{{ json_encode($result['labels']) }}">
        <canvas id="electionChart{{ $result['nivel'] }}" style="height:230px"></canvas>    
      </div>

    </section>

    @endforeach

    

@push ('scripts')

<script src="{{asset('/js/Chart.js')}}"></script>
<script type="text/javascript">
 /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    var areaChartData = {
      labels  : ['Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago'],
      images: ['/img/logo.png', '/img/logo.png'],
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
  var electionChart1Canvas = $('#electionChart1').get(0).getContext('2d');

  $labels1 = JSON.parse($('#electionChart1').parent().find('input[name="labels"]').val());
  $values1 = JSON.parse($('#electionChart1').parent().find('input[name="values"]').val());
  // This will get the first returned node in the jQuery collection.
  var electionChart1       = new Chart(electionChart1Canvas);

  var electionChart1Data = {
    //labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
    labels  : $labels1,

    datasets: [
      {
        label               : 'Primaria',
        fillColor           : 'rgb(210, 214, 222)',
        strokeColor         : 'rgb(210, 214, 222)',
        pointColor          : 'rgb(210, 214, 222)',
        pointStrokeColor    : '#c1c7d1',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgb(220,220,220)',
        //data                : [65, 59, 80, 81, 56, 55, 40]
        data                : $values1

      }
    ]
  };
  
  var chartOptions = {
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

  electionChart1.Bar(electionChart1Data, chartOptions);


  var electionChart2Canvas = $('#electionChart2').get(0).getContext('2d');

  $labels2 = JSON.parse($('#electionChart2').parent().find('input[name="labels"]').val());
  $values2 = JSON.parse($('#electionChart2').parent().find('input[name="values"]').val());
  // This will get the first returned node in the jQuery collection.
  var electionChart2       = new Chart(electionChart2Canvas);

  var electionChart2Data = {
    //labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
    labels  : $labels2,

    datasets: [
      {
        label               : 'Secundaria',
        fillColor           : 'rgb(210, 214, 222)',
        strokeColor         : 'rgb(210, 214, 222)',
        pointColor          : 'rgb(210, 214, 222)',
        pointStrokeColor    : '#c1c7d1',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgb(220,220,220)',
        //data                : [65, 59, 80, 81, 56, 55, 40]
        data                : $values2

      }
    ]
  };

  electionChart2.Bar(electionChart2Data, chartOptions);


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
