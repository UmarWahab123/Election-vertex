@extends('brackets/admin-ui::admin.layout.default')
@section('body')


<div class="container">
    <h1>Elecion Expert</h1>

    <h2>Details of Polling Station # 1721119</h2>

    <table id="customers3" class="table">
        <thead>
        <tr>
            <th>Name</th>
            <th>CNIC</th>
            <th>Address</th>
            <th>Phone</th>
        </tr>
        </thead>
        <tbody>
        @foreach($details as $value)
        <tr>
            <td>{{@($value->name)}}</td>
            <td>{{@($value->cnic)}}</td>
            <td>{{@($value->address)}}</td>
            <td>{{@($value->phone)}}</td>
        </tr>
        @endforeach

        </tbody>

    </table>
</div>


@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script type="text/javascript">
    $(document).ready(function() {
        $('#customers3 tfoot th').each( function () {
            var title = $(this).text();
            $(this).html( '<input type="text" placeholder="Search" />' );
        });
        var table = $('#customers3').DataTable({
            initComplete: function () {
                // Apply the search
                this.api().columns().every( function () {
                    var that = this;
                    $( 'input', this.footer() ).on( 'keyup change clear', function () {
                        if ( that.search() !== this.value ) {
                            that
                                .search( this.value )
                                .draw();
                        }
                    });
                });
            }
        });
    });
</script>
