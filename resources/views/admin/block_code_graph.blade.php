<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<canvas id="chart1"></canvas>
<script>
    window.onload = function () {

        var ctx = document.getElementById('chart1').getContext("2d");

        var data = {
            labels: <?php echo $dp_blockCode; ?>,

            datasets: [
                {
                    label: "Block Code Details",
                    fill: false,
                    lineTension: 0.1,
                    backgroundColor: "rgba(75,192,192,0.4)",
                    borderColor: "rgba(75,192,192,1)",
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderColor: "rgba(75,192,192,1)",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(75,192,192,1)",
                    pointHoverBorderColor: "rgba(220,220,220,1)",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: <?php echo $total_urls; ?>,
                    spanGaps: false,
                }
            ]
        };

        var options = {
            responsive: true,
            title: {
                display: true,
                position: "top",
                // text: 'anything',
                fontSize: 18,
                fontColor: "#111"
            },
            tooltips: {
                enabled: true,
                mode: 'single',
                callbacks: {
                    label: function(tooltipItems, data) {

                        var multistringText = [];
                        multistringText.push(`Total: ${[tooltipItems.yLabel]}`);

                        const complete = <?php echo $complete_urls ?>;
                        multistringText.push(`Complete: ${complete[tooltipItems.index]}`);

                        const invalid = <?php echo $invalid_urls ?>;
                        multistringText.push(`Invalid: ${invalid[tooltipItems.index]}`);

                        const pendind = <?php echo $pending_urls ?>;
                        multistringText.push(`Pendind: ${pendind[tooltipItems.index]}`);

                        const manually_entered = <?php echo $manually_entered ?>;
                        multistringText.push(`Manually Entered: ${manually_entered[tooltipItems.index]}`);

                        return multistringText;
                    }
                }
            },
            legend: {
                display: true,
                position: "bottom",
                labels: {
                    fontColor: "#333",
                    fontSize: 16
                }
            },
            scales:{
                yAxes:[{
                    ticks:{
                        min:0

                    }
                }]

            }
        };

        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: data,
            options: options
        });
    }

</script>
