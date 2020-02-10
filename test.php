<?php require_once 'menu.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<link rel="stylesheet" type="text/css" href="menu_reports.css">

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>




<h1 class='pageTitle'> <? TR( 'monthly report' ); ?> </h1>


<div style="width: 80%; height: 300px;">
<canvas id="lineChart"></canvas>
</div>
<div style="width: 80%; height: 300px;">
<canvas id="donutChart"></canvas>
</div>

<script>

var lineChartCtx = document.getElementById('lineChart').getContext('2d');
var lineChart = new Chart(lineChartCtx, {
    type: 'line',
    data: {
        labels:   [ "January", "February", "March", "April", "May", "June", "July"],
        datasets: [{
            label:           "a label",
            borderColor:     'rgb( 255, 220, 150 )',
            data:            [30, 10, 5, 2, 20, 30, 45],
        }]
    },
    options: {
            maintainAspectRatio: false,
    }
});


var donutChartCtx = document.getElementById('donutChart').getContext('2d');
var myDoughnutChart = new Chart( donutChartCtx, {
    type: 'doughnut',
    data: {
        labels:   [ "January", "February", "March", "April", "May", "June", "July"],
        datasets: [{
            label:           "a label",
            data:            [30, 10, 5, 2, 20, 30, 45],
        }]
    },
    options: {
            maintainAspectRatio: false,
    }
});

</script>


</html>
