@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.admin-user.actions.index'))
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
@section('body')
<div class="card"><br>
 <section class="booking">
     <div class="container justify-content-center align-items-center">
         <div class="row">
             <div class="col-md-12">
                 <div class="booking-form booking-outer">
                     {{-- <h3 class="">Enter The Following Information To Apply For The Job .</h3> --}}
                     <form class="formdata" id="getBlockcode">
                         {{ csrf_field() }}
                         <div class="row">
                            <div class="col-md-4">
                                <div class="form-group m-form__group">
                                   <label>All Sector</label>
                                   <select name="sector" class="form-control sector" id="modelDropdown">
                                      <option value="">Select Sector</option>
                                      @foreach($sectors as $key=>$value)
                                      <option value="{{$value}}">{{{$value}}}</option>
                                     @endforeach
                                   </select>
                                </div>
                             </div>
                             <div class="col-md-4">
                               <div class="form-group m-form__group">
                                   <label>Related BlockCode</label>
                                   <select name="blockcode" class="form-control select-blockcode" id="userDropdown">
                                    
                                   
                                   </select>
                                </div>
                             </div>
                           <div class="col-md-2">
                            <div class="form-group m-form__group w-75"><br>
                            <a class="btn btn-primary export-blockcode text-white">Export Blockcode</a>
                            </div>
                         </div>
                           </div>
                     </form>
                 </div>
             </div>
         </div>
     </div>
 </section>
</div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript">
$(function(){
  $("#modelDropdown").select2();
  $("#userDropdown").select2();
 });
$(document).ready(function() {
    $(document).on('change','.sector',function(){
     var sectorName = $(this).val();
     $.ajax({
              type:"get",
              url: "{{url('/admin/filter-blockcode')}}/"+sectorName,
              dataType: "json",
              success:function(data)
              { 
                // console.log('blockcode',data.response);
                $('.select-blockcode').html(data.response); //to write the respone in the blockcode drop  
              }
          });
    });
    $(document).on('click','.export-blockcode',function(){
      var blockCode = $('.select-blockcode').find('option:selected').val();
     $.ajax({
              type:"get",
              url: "{{url('/admin/export-blockcode')}}/"+blockCode,
              success:function(data)
              { 
                window.location.href ='/admin/export-blockcode/'+blockCode;
              }
          });
    });
});
</script>