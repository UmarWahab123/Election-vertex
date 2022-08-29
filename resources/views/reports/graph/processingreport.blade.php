@extends('brackets/admin-ui::admin.layout.default')
@section('title', trans('admin.admin-user.actions.index'))
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

@section('body')
<div class="card"><br>
<form id="filter-form" class="ml-5 mt-3">
  {{ csrf_field() }}
<div class="row g-3 align-items-center">
    <div class="col-auto">
      <label for="start-date" class="col-form-label">Start Date :</label>
    </div>
    <div class="col-auto">
      <input type="date" name="start_date" id="start_date" class="form-control" aria-describedby="passwordHelpInline">
    </div>
    <span class="errortype text-danger d-none"></span>
    <div class="col-auto">
        <label for="end-date" class="col-form-label">End Date :</label>
      </div>
    <div class="col-auto">
        <input type="date" name="end_date" id="end_date" class="form-control" aria-describedby="passwordHelpInline">
      </div>
      <div class="col-auto">
        <button type="button" class="text-white bg-blue ml-3 form-control text-xl-right filter-reports-btn">Filter Reports</button>
      </div>
  </div>
</form><hr>
<div id="chartContainer" style="height: 400px; width: 100%;"></div>
</div>
@endsection
<script>
 $(document).ready(function(){
  // var start_date = $('#start_date').val();
  // var end_date = $('#end_date').val();
  // if(start_date=="" && end_date==""){
  //   const d = new Date();
  //   var start_date = d.getFullYear() + "/" + (d.getMonth()+1) + "/" + d.getDate();
  //   var end_date =d.getFullYear() + "/" + (d.getMonth()+1) + "/" + d.getDate();
  // }
  filterReports(start_date, end_date);
    $('.filter-reports-btn').on('click', function(e){
            e.preventDefault();
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            if(start_date > end_date){
              $(".errortype").html("Incorrect start date !");
              $(".errortype").removeClass('d-none');
              setTimeout(function(){
                $(".errortype").addClass('d-none');
            },2000);
              return false;
            }else{
              $(".errortype").addClass('d-none');
            }
            filterReports(start_date,end_date);
        });
    });
    function filterReports(start_date, end_date){
      var token = $('input[name=_token]').val();
       var url = "{{ url('admin/reports/filterProcessing') }}/"+start_date+"/"+end_date;
       $.ajax({
            headers:{'X-CSRF-TOKEN': token},
            type: 'GET',
            url: url,
            dataType:"json",
            success: function(data){
              // console.log('filterdata', data.response);
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
                dataPoints:data.response
            }]
        });
        chart.render();
          
            }
        });
    }
</script>