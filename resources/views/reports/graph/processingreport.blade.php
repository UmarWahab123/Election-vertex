@extends('brackets/admin-ui::admin.layout.default')
@section('title', trans('admin.admin-user.actions.index'))
@section('body')
<form id="filter-form">
<div class="row g-3 align-items-center">
    <div class="col-auto">
      <label for="start-date" class="col-form-label">Start Date :</label>
    </div>
    <div class="col-auto">
      <input type="date" name="start_date" id="start_date" class="form-control" aria-describedby="passwordHelpInline">
    </div>
    <div class="col-auto">
        <label for="end-date" class="col-form-label">End Date :</label>
      </div>
    <div class="col-auto">
        <input type="date" name="end_date" id="end_date" class="form-control" aria-describedby="passwordHelpInline">
      </div>
      <div class="col-auto">
        <button type="button" class="btn btn-primary filter-reports-btn">Filter Reports</button>
      </div>
  </div>
</form><br>
<div id="chartContainer2" style="height: 300px; width: 100%;"></div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
 $(document).ready(function(){
  var start_date = $('#start_date').val();
  var end_date = $('#end_date').val();
  filterReports(start_date, end_date);
    $('.filter-reports-btn').on('click', function(e){
            e.preventDefault();
            filterReports(start_date,end_date);
        });
    });
    function filterReports(start_date, end_date){
      var start_date = $('#start_date').val();
       var end_date = $('#end_date').val();
       var token = $('input[name=_token]').val();
       var formdata=$('#filter-form').serialize();
       if(start_date == '' && end_date == ''){
        var d = new Date(); 
        var start_date =d.getFullYear() + "/" + (d.getMonth()+1) + "/" + d.getDate();
        var end_date =d.getFullYear() + "/" + (d.getMonth()+1) + "/" + d.getDate();
       }
        var url = "{{ url('admin/reports/filterProcessing') }}/"+start_date+"/"+end_date;
       console.log('url',url);
       $.ajax({
            headers:{'X-CSRF-TOKEN': token},
            type: 'GET',
            url: url,
            data: formdata,
            success: function(data){
              console.log('filterdata', data.response);
             $('#chartContainer2').html(data);
             $('#filter-form').trigger('reset');

            }
        });
    }
</script>