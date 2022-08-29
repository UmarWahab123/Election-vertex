@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.admin-user.actions.index'))
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" />
<script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js" defer ></script>
@section('body')
<div class="card"><br>
 <section class="booking">
     <div class="container justify-content-center align-items-center">
         <div class="row">
             <div class="col-md-12">
                 <div class="booking-form booking-outer">
                     {{-- <h3 class="">Enter The Following Information To Apply For The Job .</h3> --}}
                     <form class="formdata" id="audits-search">
                         {{ csrf_field() }}
                         <div class="row">
                            <div class="col-md-2">
                                <div class="form-group m-form__group">
                                   <label>All Models</label>
                                   <select name="model" class="form-control" id="modelDropdown">
                                      <option value="">Select Model</option>
                                     @foreach($data['all-model'] as $key=>$value)
                                      <option value="{{$value}}">{{{$value}}}</option>
                                     @endforeach
                                   </select>
                                </div>
                             </div>
                             <div class="col-md-2">
                               <div class="form-group m-form__group">
                                   <label>All Users</label>
                                   <select name="user_id" class="form-control" id="userDropdown">
                                      <option value="">Select Users</option>
                                     @foreach($data['all-users'] as $key=>$value)
                                      <option value="{{$value->id}}">{{$value->first_name}} {{$value->last_name}}</option>
                                    @endforeach
                                   </select>
                                </div>
                             </div>
                             <div class="form-group col-md-2">
                                <label>Start Date</label>
                                <input type="date" id="start_date" name="start_date" class="form-control">
                                <span class="errortype text-danger d-none"></span>
                            </div>
                             <div class="form-group col-md-2">
                                <label>End Date</label>
                                <input type="date" id="end_date" name="end_date" class="form-control">
                            </div>
                            <div class="col-md-2">
                              <div class="form-group m-form__group">
                                 <label>Events</label>
                                 <select name="event" class="form-control">
                                    <option value="">Select Event</option>
                                    <option value="created">Created</option>
                                    <option value="updated">Updated</option>
                                    <option value="deleted">Deleted</option>
                                    <option value="restored">Restored</option>
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-2">
                            <div class="form-group m-form__group w-75"><br>
                              <label></label>
                              <button type="submit" class="text-white btn-search bg-blue form-control text-xl-right">Search</button>
                            </div>
                         </div>
                           </div>
                     </form>
                 </div>
             </div>
         </div>
     </div>
 </section>
 <div class="search-results">

</div>
</div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript">
 $(document).on('submit','#audits-search',function(e){
        e.preventDefault();
        var token = $('input[name=_token]').val();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        if(start_date > end_date){
          $(".errortype").html("Incorrect start date !");
          $(".errortype").removeClass('d-none');
          setTimeout(function(){
            $(".errortype").addClass('d-none');
         },5000);
          return false;
        }else{
          $(".errortype").addClass('d-none');
        }
        $(".btn-search").attr("disabled", true).html('Searching');
        var formdata=$('.formdata').serialize();
        $.ajax({
                type:"GET",
                headers: {'X-CSRF-TOKEN': token},
                url: "{{url('/admin/search')}}",
                dataType:"json",
                data:formdata,
                success:function(data)
                 {
                  console.log('response',data.response);
                  $('.search-results').html(data.response);
                  $('#searchModal').modal('show');
                  $(".btn-search").attr("disabled", false).html('Search');
                 }
            });
 
        });
        $(function(){
  $("#modelDropdown").select2();
  $("#userDropdown").select2();

 });
        $(document).ready(function() {
                /* marksscored is sorted in descending */
                $('#tableID').DataTable({
                    order: [[ 4, 'desc' ]]
                });  
            });
       
</script>