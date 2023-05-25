@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->abbreviation }} 統計資料：有興趣活動</h2>
    <!--Div that will hold the column chart-->
    <table class='columns'>
        <tr>
            <td><div id='piechart_div' style='border: 1px solid #ccc'></div></td>
        </tr>
        <tr>
            <td><div id='barchart_div' style='border: 1px solid #ccc'></div></td>
        </tr>
    </table>
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
            var piechart_options = {'title':'有興趣活動統計，共 {{ $rows }} 人填表，{{ $total }} 項',
                            'hAxis': {
                            'title':'報名人數',
                            'titleTextStyle': {'bold':true,'ilatic':false},
                            },
                            'vAxis': {
                            'title':'有興趣活動',
                            'titleTextStyle': {'bold':true,'ilatic':false},
                            },
                            'width':450,
                            'height':500,
                            'legend':{'position':'none'},
                            'annotations':{'alwaysOutside': true},
                            'pieSliceText': 'label'
                            };
    
            var barchart_options = piechart_options;
    
            // Instantiate and draw our chart, passing in some options.
            var piechart = new google.visualization.PieChart(document.getElementById('piechart_div'));
            piechart.draw(data, piechart_options);
    
            // Instantiate and draw our chart, passing in some options.
            var barchart = new google.visualization.BarChart(document.getElementById('barchart_div'));
            barchart.draw(data, barchart_options);
        }
      </script>

@endsection