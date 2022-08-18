@extends('brackets/admin-ui::admin.layout.default')

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style>
        body{
            margin-top:20px;
            background:#eee;
        }

        .invoice {
            background: #fff;
            padding: 20px
        }

        .invoice-company {
            font-size: 20px
        }

        .invoice-header {
            margin: 0 -20px;
            background: #f0f3f4;
            padding: 20px
        }

        .invoice-date,
        .invoice-from,
        .invoice-to {
            display: table-cell;
            width: 1%
        }

        .invoice-from,
        .invoice-to {
            padding-right: 20px
        }

        .invoice-date .date,
        .invoice-from strong,
        .invoice-to strong {
            font-size: 16px;
            font-weight: 600
        }

        .invoice-date {
            text-align: right;
            padding-left: 20px
        }

        .invoice-price {
            background: #f0f3f4;
            display: table;
            width: 100%
        }

        .invoice-price .invoice-price-left,
        .invoice-price .invoice-price-right {
            display: table-cell;
            padding: 20px;
            font-size: 20px;
            font-weight: 600;
            width: 75%;
            position: relative;
            vertical-align: middle
        }

        .invoice-price .invoice-price-left .sub-price {
            display: table-cell;
            vertical-align: middle;
            padding: 0 20px
        }

        .invoice-price small {
            font-size: 12px;
            font-weight: 400;
            display: block
        }

        .invoice-price .invoice-price-row {
            display: table;
            float: left
        }

        .invoice-price .invoice-price-right {
            width: 25%;
            background: #2d353c;
            color: #fff;
            font-size: 28px;
            text-align: right;
            vertical-align: bottom;
            font-weight: 300
        }

        .invoice-price .invoice-price-right small {
            display: block;
            opacity: .6;
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 12px
        }

        .invoice-footer {
            border-top: 1px solid #ddd;
            padding-top: 10px;
            font-size: 10px
        }

        .invoice-note {
            color: #999;
            margin-top: 80px;
            font-size: 85%
        }

        .invoice>div:not(.invoice-footer) {
            margin-bottom: 20px
        }

        .btn.btn-white, .btn.btn-white.disabled, .btn.btn-white.disabled:focus, .btn.btn-white.disabled:hover, .btn.btn-white[disabled], .btn.btn-white[disabled]:focus, .btn.btn-white[disabled]:hover {
            color: #2d353c;
            background: #fff;
            border-color: #d9dfe3;
        }
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>new</title>

</head>
@section('body')
    {{--        @dd($invoiceinfo);--}}
    <?php
    //    $invoicedata =json_decode($invoiceinfo);
    //    dd($invoicedata);
    ?>
    <section>
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
        <div class="container" id="container">
            <div class="col-md-12">
                <div class="invoice">
                    <!-- begin invoice-company -->
                    <div class="invoice-company text-inverse f-w-600">
            <span class="pull-right hidden-print">
            <a href="javascript:;" class="btn btn-success" onclick="window.print()" class="btn btn-sm btn-white m-b-10 p-l-5"><i class="fa fa-print t-plus-1 fa-fw fa-lg"></i> Print</a>
            <a class="btn btn-primary" href="{{url('/admin/customers/pay-voice-price/'.$invoiceinfo->invoice_no)}}" class="btn btn-sm btn-white m-b-10 p-l-5"><i class="fa  t-plus-1 fa-fw fa-lg"></i> Pay Now</a>
            </span>
                        Election Expert
                    </div>
                    <!-- end invoice-company -->
                    <!-- begin invoice-header -->
                    <div class="invoice-header">
                        <div class="invoice-from">
                            <small>From</small>
                            <address class="m-t-5 m-b-5">
                                <strong class="text-inverse">{{$customerName->name}}</strong><br>
                            </address>
                        </div>
                        <div class="invoice-to">
                            <small>to</small>
                            <address class="m-t-5 m-b-5">
                                <strong class="text-inverse">Election Expert</strong><br>
                                DHA Phase 5<br>
                                Lahore, 75500<br>
                                Phone: +92-302-604-6328<br>
                            </address>
                        </div>
                        <div class="invoice-date">
                            <small>Invoice / To Be Paid</small>
                            <div class="date text-inverse m-t-5">{{$invoiceinfo->created_at}}</div>
                            <div class="invoice-detail">
                                <b>Invoice No:</b> {{$invoiceinfo->invoice_no}}<br>
                                Services Product
                            </div>
                        </div>
                    </div>
                    <!-- end invoice-header -->
                    <!-- begin invoice-content -->
                    <div class="invoice-content">
                    @php $order =json_decode($invoiceinfo->order_meta,true); @endphp
                        <!-- begin table-responsive -->
                        <div class="table-responsive">
                            <table class="table table-invoice">
                                <thead>
                                <tr>
                                    <th>Items</th>
                                    <th class="text-center" width="10%">RATE</th>
                                    <th class="text-right" width="20%">LINE TOTAL</th>
                                </tr>
                                </thead>
                                <tbody>

                                <tr>
                                    <td>
                                        @if($order['voter_list'] != null)<span class="text-inverse"><p>Voter List</p></span><br>@endif
                                        @if($order['voter_parchi'] != null) <span class="text-inverse"><p>Voter parachi</p></span><br>@endif
                                        @if($order['desktop_app'] != null)<span class="text-inverse"><p>Desktop app</p></span><br>@endif
                                        @if($order['portal'] != null)<span class="text-inverse"><p>Portal</p></span><br>@endif
                                    </td>
                                    <td class="text-center">
                                        @if($order['voter_list'] != null)<span class="text-inverse"><p>3PKR/per</p></span><br>@endif
                                        @if($order['voter_parchi'] != null) <span class="text-inverse"><p>3PKR/per</p></span><br>@endif
                                        @if($order['desktop_app'] != null)<span class="text-inverse"><p>3PKR/per</p></span><br>@endif
                                        @if($order['portal'] != null)<span class="text-inverse"><p>3PKR/per</p></span><br>@endif
                                    </td>
                                    <?php $portal = 0;$voterlist =0;$desktopapp=0;$voterparchi=0; ?>
                                    <td class="text-right">
                                        @if($order['voter_list'] != null)
                                            <?php
                                            $voterlist =($invoiceinfo->total_voter)*3;
                                            $voterlist_amount =  number_format($voterlist,2);
                                            ?>
                                                <span class="text-inverse"><p>{{$voterlist_amount}}</p></span><br>
                                        @endif
                                        @if($order['voter_parchi'] != null)
                                            <?php
                                            $voterparchi  = ($invoiceinfo->total_voter)*3;
                                            $voterparchi_amount = number_format($voterparchi,2);
                                            ?>
                                                <span class="text-inverse"><p>{{$voterparchi_amount}}</p></span><br>
                                        @endif
                                        @if($order['desktop_app'] != null)
                                            <?php
                                            $desktopapp=($invoiceinfo->total_voter)*3;
                                            $desktopapp_amount =   number_format($desktopapp ,2);
                                            ?>
                                                <span class="text-inverse"><p> {{$desktopapp_amount}}</p></span><br>
                                        @endif
                                        @if($order['portal'] != null)
                                            <?php
                                            $portal=($order['portal'])*3;
                                            $portal_amount =  number_format($portal,2);
                                            ?>
                                                <span class="text-inverse"><p> {{$portal_amount}}</p></span><br>
                                        @endif
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- end table-responsive -->
                        <!-- begin invoice-price -->
                        <div class="invoice-price">
                            <div class="invoice-price-left">

                            </div>
                            <?php
                            $grandtotal =  $voterlist+$voterparchi+$desktopapp+$portal;
                            $totalAmount = number_format($grandtotal,2);
                            ?>
                            <div class="invoice-price-right">
                                <small>TOTAL</small> <span class="f-w-600">{{$totalAmount}} Rs.</span>
                            </div>
                        </div>
                        <!-- end invoice-price -->
                    </div>
                    <!-- end invoice-content -->
                    <!-- begin invoice-note -->
                    <div class="invoice-note">
                        * Make all cheques payable to [Your Company Name]<br>
                        * Payment is due within 30 days<br>
                        * If you have any questions concerning this invoice, contact  [Name, Phone Number, Email]
                    </div>
                    <!-- end invoice-note -->
                    <!-- begin invoice-footer -->
                    <div class="invoice-footer">
                        <p class="text-center m-b-5 f-w-600">
                            THANK YOU FOR YOUR BUSINESS
                        </p>
                        <p class="text-center">
                            <span class="m-r-10"><i class="fa fa-fw fa-lg fa-globe"></i> matiasgallipoli.com</span>
                            <span class="m-r-10"><i class="fa fa-fw fa-lg fa-phone-volume"></i> T:016-18192302</span>
                            <span class="m-r-10"><i class="fa fa-fw fa-lg fa-envelope"></i> rtiemps@gmail.com</span>
                        </p>
                    </div>
                    <!-- end invoice-footer -->
                </div>
            </div>
        </div>
    </section>

@endsection

<script scr="text/javascript">

    function printDiv(container) {
        alert('ss')
        var printContents = document.getElementById(container).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
