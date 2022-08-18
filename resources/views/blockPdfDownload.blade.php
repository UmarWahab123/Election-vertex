<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vertex Expert</title>
    <style>
        .boxer {
            display: flex !important;
            flex-direction:row;
            border-collapse: collapse !important;
            width: 100% !important;
            padding: 10px !important;
        }

        .boxer .box-row {
            display: flex !important;


        }

        .boxer .box-row:first-child {
            font-weight: bold !important;
        }

        .boxer .box {
            display: table-cell !important;
            border: 1px solid black !important;
            padding: 20px !important;
            text-align: center !important;
        }

        .boxImage {
            display: table-cell !important;
            border: 1px solid black !important;
            text-align: center !important;
        }
        .center {
            text-align: center !important;
        }
    </style>
</head>

<body>
<div class="center"><h2>Election Experts</h2></div>
<div class="center"> <h3>Block code : {{$block}}</h3></div>
<section class="boxer">
    <table class="boxer">
        <thead>
        <tr class="box">
            <td class="box">Serial No</td>
            <td class="box">Family No</td>
            <td class="box">Name</td>
            <td class="box">Age</td>
            <td class="box">CNIC</td>
            <td class="box">Mobile</td>
            <td class="box">Address</td>
        </tr>
        </thead>

        <tbody>

        @foreach($pdfdata as $value)
            @if($recordType == 'ALL')
                <tr class="box">
                    <td class="box">@if($value->serial_no){{@($value->serial_no)}}@else - @endif</td>
                    <td class="box">@if($value->family_no) {{@($value->family_no)}}@else - @endif</td>
                    <td class="box">@if($value->name){{@($value->name)}}@else - @endif </td>
                    <td class="box">@if($value->age){{@($value->age)}}@else - @endif</td>
                    <td class="box">{{@($value->cnic)}}</td>
                    <td class="box">@if($value->mobile_number)0{{@($value->mobile_number)}}@else - @endif</td>
                    <td class="box" style="font-size: 14px;">@if($value->address){{@($value->address)}}@else -  @endif</td>
                </tr>
                <tr class="boxImage">
                    <td class="boxImage" colspan="7">
                        @if(@$value->pic_slice)
                            <img src="{{@($value->pic_slice)}}" width="100%" style="object-fit: cover">
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endif
            @if($recordType == 'SELECTED')
                @if($value->mobile_number && $value->address && $value ->name != NULL)
                    <tr class="box">
                        <td class="box">@if($value->serial_no){{@($value->serial_no)}}@else - @endif</td>
                        <td class="box">@if($value->family_no) {{@($value->family_no)}}@else - @endif</td>
                        <td class="box">@if($value->name){{@($value->name)}}@else - @endif </td>
                        <td class="box">@if($value->age){{@($value->age)}}@else - @endif</td>
                        <td class="box">{{@($value->cnic)}}</td>
                        <td class="box">@if($value->mobile_number)0{{@($value->mobile_number)}}@else - @endif</td>
                        <td class="box">@if($value->address){{@($value->address)}}@else -  @endif</td>
                    </tr>
                    <tr class="boxImage">
                        <td class="boxImage" colspan="7">
                            @if(@$value->pic_slice)
                                <img src="{{@($value->pic_slice)}}" width="100%" style="object-fit: cover">
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endif
            @endif

        @endforeach
        </tbody>
    </table>
</section>

</body>

</html>
