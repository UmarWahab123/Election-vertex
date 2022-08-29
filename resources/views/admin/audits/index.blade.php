
<section id="basic-datatable">
    <div class="row">
       <div class="col-12">
          <div class="card">
             <div class="card-datatable p-2">
               @if(count($data['audits'])>0)
                <table class="table" id="tableID">
                   <thead>
                      <tr>
                         <th>Sr No</th>
                         <th>User Name</th>
                         <th>Events</th>
                         <th>Model</th>
                         <th>URL</th>
                         <th>IP address</th>
                         <th>User Agents</th>
                      </tr>
                   </thead>
                   <tbody>
                      @foreach($data['audits'] as $key=>$row)
                      <tr>
                         <td>{{$key+1}}</td>
                         <td>{{isset($row->auditusers->first_name)? $row->auditusers->first_name:''}} {{isset($row->auditusers->last_name)? $row->auditusers->last_name:''}}</td>
                         <td>{{$row->event}}</td>
                         <td>{{$row->auditable_type }}</td>
                         <td>{{$row->url}}</td>
                         <td>{{$row->ip_address}}</td>
                         <td>{{$row->user_agent}}</td>
                      </tr>
                      @endforeach              
                   </tbody>
                </table>
             @else
             <h3 class="text-center mt-4">No Search Data Found !</h3>
             @endif
             </div>
          </div>
       </div>
    </div>
 </section>
<script type="text/javascript">
$(document).ready(function() {
                /* marksscored is sorted in descending */
                $('#tableID').DataTable({
                    order: [[ 4, 'desc' ]]
                });  
            });
</script>