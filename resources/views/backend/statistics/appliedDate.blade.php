@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->abbreviation }} 統計資料：報名日期</h2>
    <div id='chart_div'></div>
    <div id='chart_div1'></div>
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
                'title':'報名日期統計，共 {{ $total }} 人',
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
                'width':1000,
                'height':400,
                'legend':{'position':'none'},
                'annotations':{'alwaysOutside': true}
            };

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
            chart.draw(data, options);

            var data1 = new google.visualization.DataTable({!! $GChartData1 !!});
            // Set chart options
            var options1 = {
                'title':'報名日期統計，共 {{ $total }} 人',
                'hAxis': {
                    'title':'報名日期',
                    'titleTextStyle': {'bold':true,'italic':false},
                    'format':'M/d'
                },
                'vAxis': {
                    'title':'累計人數',
                    'titleTextStyle': {'bold':true,'italic':false},
                    'format':'decimal'
                },
                'width':1000,
                'height':400,
                'legend':{'position':'none'},
                'annotations':{'alwaysOutside': true}
            };

            // Instantiate and draw our chart, passing in some options.
            var chart1 = new google.visualization.ColumnChart(document.getElementById('chart_div1'));
            chart1.draw(data1, options1);
        }
    </script>

@endsection