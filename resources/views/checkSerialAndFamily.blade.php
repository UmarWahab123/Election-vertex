
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://fonts.googleapis.com/earlyaccess/notonastaliqurdudraft.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <style>
        .container {
            width: 80% !important;
        }
        td {
            min-width: 88px;
        }
    </style>
</head>
{{--{{dd($polling_details)}}--}}

<div class="container">
    <h3 id="total_display"></h3>
    <hr>
    <h3 id="found_display"></h3>
    <hr>
    <h3 id="miss_display"></h3>
    <hr>
    <div class="row">
        <table class="table">
            <thead>
            <tr>
                <th>id</th>
                <th>serial #</th>
{{--                <th>new serial#</th>--}}
                <th>family #</th>
{{--                <th>new family#</th>--}}
                <th>image</th>
            </tr>
            </thead>
            <tbody>

            @foreach($polling_details as $key => $line)
                <p style = "display: none">{{$total++}}</p>
                <tr>
                    <td>{{$line->id}}</td>
                    @if($line->serial_no != null)
                        <p style = "display: none">{{$found++}}</p>
                        <td>{{$line->serial_no}}</td>
                    @else
                        <td>xxx</td>
                    @endif

                    @if($line->serial_no != null)
                        <td>{{$line->family_no}}</td>
                    @else
                        <td>xxx</td>
                    @endif

{{--                    <td>{{ @explode('_' , $line->family_no)[1] }}</td>--}}
{{--                    <td>{{ @explode('_' , $line->family_no)[0] }}</td>--}}
{{--                    <td>{{ @explode('_' , $line->family_no)[2] }}</td>--}}
                    <td>
                        <img src="{{ $line->pic_slice }}" alt="" width="100%">
                    </td>
                </tr>
            @endforeach


{{--            @if( ($line->serial_no == @explode('_' , $line->family_no)[1]) && (@explode('_' , $line->family_no)[0] == @explode('_' , $line->family_no)[2]) )--}}
{{--                    <tr>--}}
{{--                        <td>{{$line->id}}</td>--}}
{{--                        @if($line->serial_no != null)--}}
{{--                            <td>{{$line->serial_no}}</td>--}}
{{--                        @else--}}
{{--                            <td>xxx</td>--}}
{{--                        @endif--}}

{{--                        <td>{{ @explode('_' , $line->family_no)[1] }}</td>--}}
{{--                        <td>{{ @explode('_' , $line->family_no)[0] }}</td>--}}
{{--                        <td>{{ @explode('_' , $line->family_no)[2] }}</td>--}}
{{--                        <td>{{ $line->pic_slice }}</td>--}}
{{--                    </tr>--}}
{{--                @else--}}
{{--                    <p style = "display: none">{{$diff++}}</p>--}}
{{--                    <tr style="background-color: #ffa2a2">--}}
{{--                        <td>{{$line->id}}</td>--}}
{{--                        @if($line->serial_no != null)--}}
{{--                            <td>OS # {{$line->serial_no}}</td>--}}
{{--                        @else--}}
{{--                            <td>OS # xxx</td>--}}
{{--                        @endif--}}

{{--                        <td>NS # {{ @explode('_' , $line->family_no)[1] }}</td>--}}
{{--                        <td>OF # {{ @explode('_' , $line->family_no)[0] }}</td>--}}
{{--                        <td>NF # {{ @explode('_' , $line->family_no)[2] }}</td>--}}
{{--                        <td>--}}
{{--                            <img src="{{ $line->pic_slice }}" alt="" width="100%">--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                @endif--}}
{{--            @endforeach--}}
            </tbody>
        </table>
        <h3 id="total" style="display: none">{{$total}}</h3>
        <h3 id="found" style="display: none">{{$found}}</h3>
    </div>
</div>

<script type="application/javascript">
    window.onload = function() {
        var total = document.getElementById('total').innerHTML;
        var found = document.getElementById('found').innerHTML;
        var miss = parseInt(total) - parseInt(found);
        document.getElementById('total_display').innerHTML = 'Total : ' + total;
        document.getElementById('found_display').innerHTML = 'Found : ' + found;
        document.getElementById('miss_display').innerHTML = 'Missing : ' + miss;
    };
</script>
