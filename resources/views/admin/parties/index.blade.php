@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.admin-user.actions.index'))

@section('body')

<section id="basic-datatable">
    <div class="row">
       <div class="col-12">
          <div class="card">
             <div class="card-header border-bottom">
                <h4 class="card-title">{{$data['page_title']}}</h4>
                <a class="btn btn-primary" href="{{url('admin/add-parties')}}">Add Party</a>
             </div>
             <div class="card-datatable p-2">
                <table class="table dynamic_table table-condensed">
                   <thead>
                      <tr>
                         <th>Sr No</th>
                         <th>Party Name</th>
                         <th>Party Logo</th>
                         <th>Created By</th>
                         <th>Action</th>
                      </tr>
                   </thead>
                   <tbody>
                      @foreach($data['results'] as $key=>$row)
                      <tr>
                         <td> {{$key+1}}</td>
                         <td>{{$row->party_name}}</td>
                         <td><img src="{{url('public')}}/{{$row->party_image_url}}" width="60" height="60"></td>
                         <td>{{$row->created_by}}</td>
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
                                  <a class="dropdown-item" href="{{url('admin/add-parties/'.$row->id)}}">
                                  <i data-feather="edit-2" class="mr-50"></i>
                                  <span>Edit</span>
                                  </a>
                                  <a href="{{url('admin/deleteparty/'.$row->id)}}" class="dropdown-item">
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
 </section>
@endsection
