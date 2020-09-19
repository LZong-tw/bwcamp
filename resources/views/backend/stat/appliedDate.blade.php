@extends('backend.master')
@section('content')
    <h2>統計 - 報名日期</h2>
    <div id='chart_div'></div>
    <script type='text/javascript'>
        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawChart);

        // Callback that creates and populates a data table,
        // instantiates the pie chart, passes in the data and
        // draws it.
        function drawChart() {
            // Create the data table.
            var data = new google.visualization.DataTable({!! $GChartData !!});
            // Set chart options
            var options = {
                'title':'報名日期統計，共 <?=$total?> 人',
                'hAxis': {
                    'title':'報名日期',
                    'titleTextStyle': {'bold':true,'italic':false},
                    'format':'M/d'
                },
                'vAxis': {
                    'title':'報名人數',
                    'titleTextStyle': {'bold':true,'italic':false},
                    'format':'decimal'
                },
                'width':900,
                'height':400,
                'legend':{'position':'none'},
                'annotations':{'alwaysOutside': true}
            };

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>

@endsection