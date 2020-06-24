<?php require_once 'menu.php'; ?>
<style>
#menuBar {
    overflow: hidden;
}
.highlight {
    padding: .2rem .6rem;
    font-size: 90%;
    color: #fff;
    background-color: #212529;
    border-radius: .2rem;
}
</style>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" 
integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">

<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" 
integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

<link href="custom.min.css" rel="stylesheet">

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" 
integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" 
integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js" crossorigin="anonymous"></script>

<div class="container">
    
    <div class="row">
        <div class="col mt-5">
            
            <h1 class="mb-3"> <? TR( 'monthly report' ); ?> </h1>

            <nav class="navbar navbar-light bg-light mb-5">
                <h2 class="mr-auto"><span class="selectedMonth"></span> <span class="selectedYear"></span> Statistics</h2>
                <div class="dropdown show mr-sm-2">
                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLinkYear" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="selectedYear"></span>
                    </a>

                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLinkYear">
                        <a class="dropdown-item" href="javascript:void(0);" onclick="loadYear(2016);">2016</a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="loadYear(2017);">2017</a>
                    </div>
                </div>
                <div class="dropdown show mr-sm-0">
                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLinkMonth" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="selectedMonth"></span>
                    </a>

                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLinkMonth">
                        <a class="dropdown-item" href="javascript:void(0);" onclick="loadMonth(1);">January</a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="loadMonth(2);">February</a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="loadMonth(3);">March</a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="loadMonth(4);">April</a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="loadMonth(5);">May</a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="loadMonth(6);">June</a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="loadMonth(7);">July</a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="loadMonth(8);">August</a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="loadMonth(9);">September</a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="loadMonth(10);">October</a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="loadMonth(11);">November</a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="loadMonth(12);">December</a>
                    </div>
                </div>
            </nav>    
        </div>
    </div>

    <div class="row">
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-file-text-o"></i></div>
                <div class="count" id="applicationsSpan">0</div>
                <h3>Applications</h3>
                <p>New student applications this month.</p>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-clock-o"></i></div>
                <div class="count">0 days</div>
                <h3>Average wait time</h3>
                <p>Time between application and first class.</p>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-pencil"></i></div>
                <div class="count" id="ttlClassesStartedSpan">0</div>

                <h3>Classes Started</h3>
                <p>Total classes started this month.</p>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-users"></i></div>
                <div class="count" id="ttlStudentsStartedSpan">0</div>

                <h3>Students Started</h3>
                <p>Total students that started class.</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-university "></i></div>
                <div class="count" id="avgStudyTimeWeekSpan">0</div>
                <h3>Study Days</h3>
                <p>Average study days per week</p>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-calendar-check-o"></i></div>
                <div class="count">0 days</div>
                <h3>Class duration</h3>
                <p>Number of days class takes to complete.</p>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-graduation-cap"></i></div>
                <div class="count" id="ttlClassesCompletedSpan">0</div>

                <h3>Classes Completed</h3>
                <p>Total classes completed this month.</p>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-graduation-cap"></i></div>
                <div class="count" id="ttlStudentsCompletedSpan">0</div>

                <h3>Students Completed</h3>
                <p>Total students that completed a class.</p>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3 col-xs-12 widget widget_tally_box">
            <div class="x_panel">
                <div class="x_content">

                    <div style="text-align: center; margin-bottom: 17px">
                        <span class="chart" data-percent="86">
                            <span class="percent" id="pcntNewOldStudentsSpan"></span>
                        </span>
                    </div>

                    <h3 class="name_title">New Student Rate</h3>

                    <div class="divider"></div>

                    <p>The percentage of students that are studying for the first time.</p>

                </div>
            </div>
        </div>

  <!--   <div class="col-md-3 col-xs-12 widget widget_tally_box">
            <div class="x_panel">
                <div class="x_content">

                    <div style="text-align: center; margin-bottom: 17px">
                        <span class="chart" data-percent="86">
                            <span class="percent"></span>
                        </span>
                    </div>

                    <h3 class="name_title">Retention Rate</h3>

                    <div class="divider"></div>

                    <p>The percentage of students that are continuing on in the program.</p>

                </div>
            </div>
        </div> -->


    </div>
</div>

<script>
function getData(report, id) {
    var url = "action_get_monthly_report.php";
    var year = getUrlParameter( 'year' );
    var month = getUrlParameter( 'month' );
    if ( report != '' )
        url += "?report=" + report;
    if ( year != '' ) 
      url += "&year=" + year;
    if ( month != '' )
      url += "&month=" + month;
    $.ajax({
      type: "get",
      url:  url,
      dataType: "json",
    })
    .done( function(data) {
        $("#"+id).html(data.total);
    })
    .fail( function(xhr, status, error){
        console.log( ': error!' + xhr.responseText );
    });
}
function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    var results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
};

function loadMonth(month) {
    var year = getUrlParameter( 'year' );
    var month = month;
    window.history.pushState('Object', 'Title', window.location.pathname+"?year="+year+"&month="+month);
    // window.location.href = window.location.pathname+"?year="+year+"&month="+month;
    $(".selectedYear").html(year);
    $(".selectedMonth").html(months[month-1]);
    
    loadData();
}

function loadYear(year) {
    var month = getUrlParameter( 'month' );
    var year = year;
    window.history.pushState('Object', 'Title', window.location.pathname+"?year="+year+"&month="+month);
    // window.location.href = window.location.pathname+"?year="+year+"&month="+month;
    $(".selectedYear").html(year);
    $(".selectedMonth").html(months[month-1]);

    loadData();
}

function loadData() {
    getData("applications", "applicationsSpan");
    getData("ttlclassesstarted", "ttlClassesStartedSpan");
    getData("ttlstudentscompleted", "ttlStudentsCompletedSpan");
    getData("ttlstudentsstarted", "ttlStudentsStartedSpan");
    getData("ttlclassescompleted", "ttlClassesCompletedSpan");
    getData("avgstudytimeweek", "avgStudyTimeWeekSpan");
    getData("pcntnewoldstudents", "pcntNewOldStudentsSpan");
}

loadData();

var month = getUrlParameter( 'month' );
var year = getUrlParameter( 'year' );

var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

$(".selectedYear").html(year);
$(".selectedMonth").html(months[month-1]);

</script>


</html>
