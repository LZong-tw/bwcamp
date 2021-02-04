@extends('backend.master')
@section('content')
    <h2>{{ $campFullData->abbreviation }} 報到統計</h2>
    <!--Div that will hold the column chart-->
    <table class='columns'>
        <tr>
            <td><div id='piechart_div' style='border: 1px solid #ccc'></div></td>
        </tr>
        <tr>
            <td><div id='barchart_div' style='border: 1px solid #ccc'></div></td>
        </tr>
        <tr>
            <td><h3>各梯次統計</h3></td>
        </tr>
        @foreach ($batches as $batch)
            <tr>
                <td>
                    <h4>{{ $batch->name }} 梯</h4>
                    <div id='piechart_div{{ $batch->id }}' style='border: 1px solid #ccc'></div>
                </td>
            </tr>
            <tr>
                <td><div id='barchart_div{{ $batch->id }}' style='border: 1px solid #ccc'></div></td>
            </tr>
        @endforeach
    </table>
    <script type='text/javascript'>
        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawChart);
        @foreach ($batches as $batch)
            google.charts.setOnLoadCallback(drawChart{{ $batch->id }});
        @endforeach

        // Callback that creates and populates a data table,
        // instantiates the pie chart, passes in the data and
        // draws it.
        function drawChart() {

            // Create the data table.
            var data = new google.visualization.DataTable({!! $GChartData !!});
    
            // Set chart options
            var piechart_options = {'title':'報到統計，共 {{ $total }} 人',
                            'hAxis': {
                            'title':'報名人數',
                            'titleTextStyle': {'bold':true,'ilatic':false},
                            },
                            'vAxis': {
                            'title':'日期',
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

        @foreach ($batches as $batch)
            function drawChart{{ $batch->id }}() {

                // Create the data table.
                var data = new google.visualization.DataTable({!! $batch->GChartData !!});

                // Set chart options
                var piechart_options = {'title':'報到統計，共 {{ $batch->total }} 人',
                                'hAxis': {
                                'title':'報名人數',
                                'titleTextStyle': {'bold':true,'ilatic':false},
                                },
                                'vAxis': {
                                'title':'日期',
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
                var piechart = new google.visualization.PieChart(document.getElementById('piechart_div{{ $batch->id }}'));
                piechart.draw(data, piechart_options);

                // Instantiate and draw our chart, passing in some options.
                var barchart = new google.visualization.BarChart(document.getElementById('barchart_div{{ $batch->id }}'));
                barchart.draw(data, barchart_options);
            }
        @endforeach
      </script>

@endsection