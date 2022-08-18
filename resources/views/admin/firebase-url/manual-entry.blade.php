@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.firebase-url.actions.index'))
<style>
    .hidden{
        display: none !important;
    }
</style>
@section('body')

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i>
                    Missing Block Codes From <b>{{$count}}</b> Pages
                    <a class="btn btn-primary btn-spinner btn-sm pull-right m-b-0 mr-2"
                       href="{{ url('admin/firebase-urls/remaining') }}" role="button"><i class="fa fa-plus"></i>&nbsp;
                        Remaining</a>
                </div>
                <div class="card-body">
                    <div class="card-block">

                        <table id="table_data" class="table table-hover table-listing">
                            <thead>
                            <tr>

                                <th> ID </th>
                                <th> Image </th>
                                {{--                                <th> Add Block Code </th>--}}
                                {{--                                <th> Action </th>--}}

                            </tr>

                            </thead>

                            <tbody>
                            @foreach ($data as $key => $item)
                                <tr id="{{@$key}}">
                                    <td>{{ @$key }}</td>
                                    <td style="justify-content: flex-start !important;">
                                        <a data-toggle="modal" data-target="#myModal" data-pic="{{ $item[0]->url }}" data-id="{{@$key}}" class="dataModal">
                                            <img src="{{ $item[0]->url }}" alt="" width="200" height="350" loading="lazy">
                                        </a>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal" id="myModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header flex-column justify-content-between align-items-center ">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Block Code </h4>
                </div>
                <div class="modal-body">
                    <div style="
                        width: 100%;
                        height: 550px;
                        overflow: scroll;
                    ">
                        <img id="pic" width="150%" src="" alt="" loading="lazy">
                    </div>


                    <div id="form" style="
                                border: 1px solid gray;
                                padding: 10px;
                                text-align-last: center;
                            ">
                        <form class="col add-polling">
                            @csrf

                            <input type="hidden" name="url_id" value="" id="url_id">
                            <input type="text" name="polling_number" id="polling_number" placeholder="Enter Block Code" required>
                            <br>
                            <input id="save-btn" type="submit"
                                   class="btn btn-sm btn-spinner btn-info mt-2"
                                   value="Save Block Code" style="color: white;">
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script>
    $(document).ready( function (){
        $( '.add-polling' ).submit(function(e) {
            e.preventDefault();

            var dataString = new FormData(this);

            var row = $(this).serializeArray();
            var row_id = row[1]['value'];

            $.ajax({
                contentType: false,
                processData: false,
                cache: false,
                type: "POST",
                url: "/admin/firebase-urls/save-polling-number",
                data: dataString,
                success: function (res) {


                    console.log(res)
                    if (res['message'] === 'ok'){
                        $('#'+row_id).fadeOut(1000);
                    }else
                    {

                    }

                },
                complete:function(data){
                    // Hide image container
                    $("#myModal").modal('hide');
                    $('#save-btn').css({ 'pointer-events' : ''});
                }

            });

        });

        $('.dataModal').click(function (e){
            var picLink = $(this).attr("data-pic");
            var url_id = $(this).attr("data-id");

            $('#pic').attr('src', picLink);
            $('#url_id').val(url_id);
        });

    } );
</script>

