@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.admin-user.actions.index'))

@section('body')

<section id="basic-datatable">
    <div class="row">
       <div class="col-12">

          <div class="card">
             <div class="card-header border-bottom">
                <h4 class="card-title">{{$data['page_title']}}</h4>
                <div class="text-lg-right">
               <a class="btn btn-primary" href="{{url('admin/export')}}">Export to Excel</a>
                </div>
             </div>
            
             <div class="card-datatable p-2">
                <table class="table dynamic_table table-condensed">
                   <thead>
                      <tr>
                         <th>Sr No</th>
                         <th>User</th>
                         <th>Receipt</th>
                         <th>Amount</th>
                         <th>Status</th>
                         <th>Uploaded Date</th>
                         <th>Action</th>
                      </tr>
                   </thead>
                   <tbody>
                      @foreach($data['results'] as $key=>$row)
                      <tr>
                         <td>{{$key+1}}</td>
                         <td>{{$row->clientuser->first_name}} {{$row->clientuser->last_name}}</td>
                         <td><img src="{{$row->receipt_url}}" width="60" height="60"></td>
                         <td>{{$row->amount}}</td>
                         <td>
                            @if($row->status=="Pending")
                            <span class="">{{$row->status }}</span>
                              @elseif($row->status=="Approved")
                              <span class="">{{$row->status }}</span>
                             @endif
                         </td>
                         <td><?=date("d, m, Y",strtotime($row->created_at));?></td>
                         <td>
                            <div class="dropdown">
                               <button type="button" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical">
                                     <circle cx="12" cy="12" r="1"></circle>
                                     <circle cx="12" cy="5" r="1"></circle>
                                     <circle cx="12" cy="19" r="1"></circle>
                                  </svg>
                                 </button>
                           <div class="dropdown-menu">
                               @if($row->status=="Pending")
                               <a id="approved-status-btn" data-id={{$row->id}} data-user_id={{$row->user_id}} data-status="Approved" data-toggle="modal" data-target="#confirm-approve" data-toggle="modal" class="dropdown-item btnstatus" data-status="Approved" href="javascript:void(0);">
                                   <i data-feather="dollar-sign" class="mr-50"></i>
                                   <span>Approved</span>
                               </a>
                                @elseif($row->status=="Approved")
                                   <a id="pending-status-btn" data-id={{$row->id}} data-user_id={{$row->user_id}} data-toggle="modal" data-status="Pending"  data-target="#confirm-approve" data-toggle="modal" class="dropdown-item btnstatus" data-status="Pending" href="javascript:void(0);">
                                   <i data-feather="trending-up" class="mr-50"></i>
                                   <span>Pending</span>
                               </a>
                               @endif           
                                  <a href="{{url('admin/deletepayments/'.$row->id)}}" class="dropdown-item">
                                  <i data-feather="trash" class="mr-50"></i>
                                  <span>Delete</span>
                                  </a>
                               </div>

                            </div>
                         </td>
                      </tr>
                      @endforeach
                   </tbody>
                </table>
             </div>
          </div>
       </div>
    </div>
    <div class="modal fade" id="confirm-approve" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header bg-vimeo">
                  <h5 class="modal-title" id="exampleModalLabel">Confirm Update</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                  </button>
              </div>
              <div class="modal-body">
               Are you sure you want to update this ?
               <form action="{{ url('admin/updatestatus') }}" method="post">
                  {{ csrf_field() }}
                   <input class="form-control" name="id" type="hidden" value="">
                   <input class="form-control" name="user_id" type="hidden" value="">
                   <input type="hidden" name="status" value=""><br>
                   
                  <div class="modal-footer">
                     <button type="button" class="btn btn-secondary bg-gray-700 text-white" data-dismiss="modal">Close</button>
                     <button type="Submit" class="btn btn-success btn-pill text-white">Confrim</button>
                 </div>
               </form>            
              </div>
          </div>
      </div>
  </div>
 </section>
 
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
 $(document).ready(function(){
  $(document).on("click","#approved-status-btn",function(){
		var id = $(this).attr('data-id');
		$("input[name=id]").val(id);
        var user_id = $(this).attr('data-user_id');
		$("input[name=user_id]").val(user_id);
        var status = $(this).attr('data-status');
		$("input[name=status]").val(status);
	});
    $(document).on("click","#pending-status-btn",function(){
        var id = $(this).attr('data-id');
		$("input[name=id]").val(id);
        var user_id = $(this).attr('data-user_id');
		$("input[name=user_id]").val(user_id);
        var status = $(this).attr('data-status');
		$("input[name=status]").val(status);
	});
});
</script>
