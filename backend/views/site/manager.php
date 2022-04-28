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

  })
  
 

JS;

    $this->registerJs( $jsRaschet, $position = yii\web\View::POS_END);
    ?>​