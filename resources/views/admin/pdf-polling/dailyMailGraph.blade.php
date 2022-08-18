
@extends('brackets/admin-ui::admin.layout.default')

@section('body')
    {{--    @dd($dailysearchReport);--}}
    <div id="dailysearch" style="height: 300px; width: 100%;"></div>
@endsection

<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

<script type="text/javascript">
    window.onload = function () {
        var chart = new CanvasJS.Chart("dailysearch", {
            title: {
                text: 'Daily Search Report'
            },
            axisX: {
                // interval: 2,
                // intervalType: "day"
            },
            data: [
                {
                    type: "line",
                    dataPoints: <?php echo json_encode($dailyMailReport,
                        JSON_NUMERIC_CHECK); ?>
                }
            ]
        });
        chart.render();

    }
</script>


