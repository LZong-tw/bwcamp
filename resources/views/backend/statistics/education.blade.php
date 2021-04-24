@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->abbreviation }} 統計資料：就讀學程</h2>
    <!--Div that will hold the column chart-->
    <table class='columns'>
        <tr>
            <td><div id='piechart_div' style='border: 1px solid #ccc'></div></td>
        </tr>
        <tr>
            <td><div id='barchart_div' style='border: 1px solid #ccc'></div></td>
        </tr>
    </table>
    @if($campFullData->table == "hcamp")
        <h4>男</h4>
        <table class='columns'>
            <tr>
                <td><div id='piechart_divM' style='border: 1px solid #ccc'></div></td>
            </tr>
            <tr>
                <td><div id='barchart_divM' style='border: 1px solid #ccc'></div></td>
            </tr>
        </table>
        <h4>女</h4>
        <table class='columns'>
            <tr>
                <td><div id='piechart_divF' style='border: 1px solid #ccc'></div></td>
            </tr>
            <tr>
                <td><div id='barchart_divF' style='border: 1px solid #ccc'></div></td>
            </tr>
        </table>
    @endif
    <script type='text/javascript'>
        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawChart);

        // Callback that creates and populates a data table,
        // instantiates the pie chart, passes in the data and
        // draws it.
        function drawChart() {

            // Create the data table.
            var data = new google.visualization.DataTable({!! $GChartDataAll !!});
            @if($campFullData->table == "hcamp")
                var dataM = new google.visualization.DataTable({!! $GChartDataM !!});
                var dataF = new google.visualization.DataTable({!! $GChartDataF !!});
            @endif
    
            // Set chart options
            var piechart_options = {'title':'就讀學程統計，共 {{ $total }} 人',
                            'hAxis': {
                            'title':'報名人數',
                            'titleTextStyle': {'bold':true,'ilatic':false},
                            },
                            'vAxis': {
                            'title':'學程',
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

            @if($campFullData->table == "hcamp")
                var piechartM = new google.visualization.PieChart(document.getElementById('piechart_divM'));
                piechartM.draw(dataM, piechart_options);
                var barchartM = new google.visualization.BarChart(document.getElementById('barchart_divM'));
                barchartM.draw(dataM, barchart_options);
                var piechartF = new google.visualization.PieChart(document.getElementById('piechart_divF'));
                piechartF.draw(dataF, piechart_options);
                var barchartF = new google.visualization.BarChart(document.getElementById('barchart_divF'));
                barchartF.draw(dataF, barchart_options);
            @endif
        }
      </script>

@endsection