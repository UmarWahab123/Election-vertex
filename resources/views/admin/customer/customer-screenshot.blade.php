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

        .main {
            display: flex;
            justify-content: space-evenly;
            border-radius: 9px;
            text-align: center;
            margin-left: 0px !important;
            margin-top: 20px;
            padding: 7px;

        }

        @media(max-width:800px) {
            .large {
                width: 80% !important;
            }
        }

        @media(max-width:500px) {
            .large {
                width: 100% !important;
            }

            section {
                padding: 30px 20px !important;
            }
        }

        .large {
            width: 50%;
        }

        section {
            padding: 30px 50px;
            display: flex !important;
            justify-content: center;
        }

        .next {
            display: flex;
            justify-content: space-around;
            border: 2px solid #3a3b3d;
            border-radius: 9px;
            padding: 7px;
        }

        .inner {
            display: flex;
            justify-content: space-around;
            text-align: center;
        }

        .end {
            display: flex;
            justify-content: space-around;
        }

        .down {
            border: 2px solid #3a3b3d;
            border-radius: 9px;
            padding: 7px;
        }

        hr {
            width: 90%;
        }
    </style>
</head>
@section('body')
{{--@dd($invoiceinfo);--}}
    <div class="container" style="border: 1px solid skyblue; background: white; border-radius: 10px">
        <div class="row">

            <div class="col-md-12">
                <h2 style="text-align: center; color: cornflowerblue">Screenshots</h2>
                <br>
                <div class="table-responsive">

{{--                    <input type="number" class="form-control" placeholder="Search By phone" id="myInput" onkeyup="myFunction()" />--}}

                    <br>
                    <table id="mytable" class="table table-bordred table-striped">
                        <thead>
                        <th><input type="checkbox" id="checkall" /></th>
                        <th>REFERRENCE NO</th>
{{--                        <th>REFERRENCE ID</th>--}}
                        <th>PAYMENT SCREENSHOT</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="checkbox" class="checkthis" /></td>
                                <td>{{$invoiceinfo->invoice_no}}</td>
{{--                                <td>{{$invoiceinfo->reference_no}}</td>--}}
                                <td><a href="{{$payments->price_screenshot}}" target="_blank"><img src="{{$payments->price_screenshot}}" width="200px" height="300px"></a></td>
                            </tr>
                        </tbody>

                    </table>

                </div>
            </div>
        </div>

@endsection
