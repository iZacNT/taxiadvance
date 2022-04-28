<?php
$this->title = 'DashBoard';
$this->params['breadcrumbs'] = [['label' => $this->title]];
$donutChatAll = $widGetData['donutChatAllCars']['allCars'];
$donutChatRepair = count($widGetData['donutChatAllCars']['inRepair']);
$statistic = $widGetData['statistic'];
$daysLabelsForBarChart = json_encode($widGetData['statisticCashByDay']['countDay']);
Yii::debug("Отоно");
Yii::debug($daysLabelsForBarChart);
$dayDataForBarChart = json_encode($widGetData['statisticCashByDay']['dayBarCart']);
$nightDataForBarChart = json_encode($widGetData['statisticCashByDay']['nightBarChart']);
$sumDataForBarChart = json_encode($widGetData['statisticCashByDay']['sumByDayBarChart']);
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <!-- Widget: user widget style 2 -->
            <div class="card card-widget widget-user-2 shadow-sm">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-warning">
                    <h2  style="font-size: 26px; font-weight: bold">Статистика по водителям</h2>
                </div>
                <div class="card-footer p-0">
                    <table class="table table-responsive">
                        <tr>
                            <th style="width: 80%">Наименование</th>
                            <th>День</th>
                            <th>Ночь</th>
                        </tr>
                        <?= $statistic['driversData'];?>
                    </table>
                </div>
            </div>
            <!-- /.widget-user -->
        </div>
        <div class="col-md-4">
            <!-- Widget: user widget style 2 -->
            <div class="card card-widget widget-user-2 shadow-sm">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-warning">
                    <h2 style="font-size: 26px; font-weight: bold">Статистика по Автомобилям</h2>
                </div>
                <div class="card-footer p-0">
                    <table class="table table-responsive">
                        <tr>
                            <th style="width: 80%">Наименование</th>
                            <th>День</th>
                            <th>Ночь</th>
                        </tr>
                        <?= $statistic['carsData'];?>
                    </table>
                </div>
            </div>
            <!-- /.widget-user -->
        </div>
        <?= $widGetData['cashRegistry'];?>

        <div class="col-md-12">

            <!-- BAR CHART -->
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Суммы парка по периодам за текущий месяц в рублях: День = <?= Yii::$app->formatter->asCurrency($widGetData['statisticCashByDay']['dayBarCartAmount']);?> | Ночь = <?= Yii::$app->formatter->asCurrency($widGetData['statisticCashByDay']['nightBarChartAmount']);?></h3>

                </div>
                <div class="card-body">
                    <div class="chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                        <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 405px;" class="chartjs-render-monitor" width="405" height="250"></canvas>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

        </div>

        <div class="col-md-12">

            <!-- BAR CHART -->
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Общая суммы парка за текущий месяц в рублях: <?= Yii::$app->formatter->asCurrency($widGetData['statisticCashByDay']['sumByDayBarChartAmount']);?></h3>

                </div>
                <div class="card-body">
                    <div class="chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                        <canvas id="barChartSumm" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 405px;" class="chartjs-render-monitor" width="405" height="250"></canvas>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

        </div>
    </div>



<?php
Yii::debug(gettype($daysLabelsForBarChart),__METHOD__);
$jsRaschet = <<< JS
let workCars = $donutChatAll-$donutChatRepair;
let inRepair = $donutChatRepair;
let daysLabelsForBarChart = $daysLabelsForBarChart;
  $(function () {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    //--------------
    //- AREA CHART -
    //--------------

     var areaChartData = {
      labels  : daysLabelsForBarChart,
      datasets: [
        {
          label               : 'Дневная',
          backgroundColor     : 'rgba(60,141,188,0.9)',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : $dayDataForBarChart
        },
        {
          label               : 'Ночная',
          backgroundColor     : 'rgba(210, 214, 222, 1)',
          borderColor         : 'rgba(210, 214, 222, 1)',
          pointRadius         : false,
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : $nightDataForBarChart
        },
      ]
    }
    
    var areaChartDataSum = {
      labels  : daysLabelsForBarChart,
      datasets: [
        {
          label               : 'Общая сумма',
          backgroundColor     : 'rgba(60,141,188,0.9)',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : $sumDataForBarChart
        },
      ]
    }
    

    var areaChartOptions = {
      maintainAspectRatio : false,
      responsive : true,
      legend: {
        display: false
      },
      scales: {
        xAxes: [{
          gridLines : {
            display : false,
          }
        }],
        yAxes: [{
          gridLines : {
            display : false,
          }
        }]
      }
    }
    
    //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas = $('#barChart').get(0).getContext('2d')
    var barChartData = $.extend(true, {}, areaChartData)
    var temp0 = areaChartData.datasets[0]
    var temp1 = areaChartData.datasets[1]
    barChartData.datasets[0] = temp1
    barChartData.datasets[1] = temp0

    var barChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      datasetFill             : false
    }

    new Chart(barChartCanvas, {
      type: 'bar',
      data: barChartData,
      options: barChartOptions
    })
    
    //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas = $('#barChartSumm').get(0).getContext('2d')
    var barChartData = $.extend(true, {}, areaChartDataSum)
    var temp0 = areaChartDataSum.datasets[0]
    // var temp1 = areaChartData.datasets[1]
    barChartData.datasets[0] = temp0
    // barChartData.datasets[1] = temp0

    var barChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      datasetFill             : false
    }

    new Chart(barChartCanvas, {
      type: 'bar',
      data: barChartData,
      options: barChartOptions
    })

  })
  
 

JS;

$this->registerJs( $jsRaschet, $position = yii\web\View::POS_END);
?>​