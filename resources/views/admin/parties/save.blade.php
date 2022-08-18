@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.admin-user.actions.index'))

@section('body')

<div class="content-body">
   <section id="basic-input">
      <div class="card">
        <div class="card-header border-bottom">
            <h4 class="card-title">{{$data['page_title']}}</h4>
         </div>
         <div class="card-body">
            <form action="{{ url('admin/saveparties') }}" class="" id="form_submit" method="post" enctype="multipart/form-data">
               {{ csrf_field() }}
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>
                        Party Name
                        </label>
                        <input  class="form-control" name="party_name" type="text" value="{{(isset($data['results']->party_name) ? $data['results']->party_name : '')}}" required>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>
                        Upload Party Logo
                        </label>
                        <input type="file" class="form-control" id="file" placeholder="Choose image" accept=".jpg, .jpeg, .png" name="logo" required>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>
                        Created By
                        </label>
                        <input  class="form-control" name="created_by" type="text" value="{{(isset($data['results']->created_by) ? $data['results']->created_by : '')}}">
                     </div>
                  </div>
               </div>
               <input class="form-control" name="id" type="hidden" value="{{(isset($data['results']->id) ? $data['results']->id : '')}}">
               <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">Save Changes</button>
               <a href="{{url('admin/all-parties')}}" class="btn btn-secondary bg-gray">Back</a>
            </form>
         </div>
      </div>
   </section>
</div>
 
@endsection
