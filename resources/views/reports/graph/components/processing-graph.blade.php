<div id="chartContainer" style="height: 300px; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>
    window.onload = function () {
        var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        exportEnabled: true,
        theme: "light2", // "light1", "light2", "dark1", "dark2"
        title:{
            text: "Processing Reports Data Set"
        },
        axisY: {
            title: "Depth (in meters)",
            suffix: " m",
            reversed: true
        },
        axisX2: {
            tickThickness: 0,
            labelAngle: 0
        },
        data: [{
            type: "column",
            axisXType: "secondary",
            yValueFormatString: "#,##0 meters",
              indexLabelFontSize: 16,
            dataPoints:  <?php echo json_encode($processingDataset, JSON_NUMERIC_CHECK); ?>
        }]
    });
    chart.render();
     }
    </script>