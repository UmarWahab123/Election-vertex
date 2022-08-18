@extends('brackets/admin-ui::admin.layout.default')

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>new</title>
    <style>
        body {
            font-family: 'Gilroy', sans-serif;
            padding: 0;
            margin: 0;
            background-color: #e7eaf4;
        }
        section {
            padding: 30px 50px;
            display: flex !important;
            justify-content: center;
        }

        hr {
            width: 90%;
        }
    </style>
</head>
@section('body')

{{--    {{dd($totalinvoice)}}--}}

    <div class="container" style="border: 1px solid skyblue; background: white; border-radius: 10px">
        <div class="row">
            <div class="col-md-12">
                <h2 style="text-align: center; color: cornflowerblue">Invoice Tables</h2>
                <br>
                <div class="table-responsive" style="padding-bottom: 20px">
                    <input type="number" class="form-control" placeholder="Search By Invoice No" id="myInput" onkeyup="myFunction()" />
                    <br>
                    <table id="mytable" class="table table-bordred table-striped">
                        <thead>
                        <th><input type="checkbox" id="checkall" /></th>
                        <th>ID</th>
                        <th>ORDER ID</th>
                        <th>INVOICE ID</th>
                        <th>CUSTOMER</th>
                        <th>DESCRIPTION</th>
                        <th>STATUS</th>
                        <th>Generate Invoice</th>
                        <th>Pay Now</th>
                        </thead>
                        <tbody>
                        @if(!empty($totalinvoice) && $totalinvoice->count())
                            @foreach($totalinvoice as $info)
                            <tr>
                                <td><input type="checkbox" class="checkthis" /></td>
                                <td>{{$info->id}}</td>
                                <td>{{$info->order_by}}</td>
                                <td>{{$info->invoice_no}}</td>
                                <td>{{$info->name}}</td>
                                <td>
                                    <h5>Order Details:</h5>

                                    @php $order =json_decode($info->order_meta); @endphp

{{--                                        @dd($order);--}}
                                    {{$order->ward}} , {{$order->sector}},
                                    {{$order->party}} , {{$order->portal}},

                                </td>
                                <td>{{$info->status}}</td>

                                <td>
                                    <a class="btn btn-primary" href="{{url('/admin/customers/generate-invoice/'.$info->invoice_no)}}">Generate Invoice</a>
                                </td>
                                <td>
                                    <a class="btn btn-primary" href="{{url('/admin/customers/pay-voice-price/'.$info->invoice_no)}}">Pay Now</a>
                                </td>

                            </tr>

                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    {!! $totalinvoice->links() !!}
                </div>
            </div>
        </div>
    </div>

@endsection

    <script>
        function myFunction() {
            // alert('asdjhg')
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("mytable");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[3];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
