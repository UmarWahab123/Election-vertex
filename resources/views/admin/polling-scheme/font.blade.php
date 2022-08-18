<head>

    <meta charset="UTF-8">
    <meta content="text/html; charset=utf-8" http-equiv=Content-Type>
    <link rel="stylesheet" href="https://fonts.googleapis.com/earlyaccess/notonastaliqurdudraft.css">

    <style>
       
    body {
        font-family: 'Noto Nastaliq Urdu Draft', serif;
    }
    #i2soft-keyboard button span {
        font-size: 12px;
    }
    @font-face {
        font-family: '/css/Jameel Noori Nastaleeq Kasheeda.ttf';
    }
    @media print {
        .pagebreak {
            page-break-after: always;
            clear: both;
        }
    }

    </style>

</head>
<body>
	



@foreach($pp as $row)
<p>  
{{$row->polling_station_area_urdu}}
</p>
<p>
	{{$row->serial_no}}
	</p>
@endforeach

</body>
