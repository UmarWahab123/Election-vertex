<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<style>
    .ward_list div ul {
        width: 100%;
        padding: 0;
        list-style: none;
        display: flex;
        flex-wrap: wrap;
        margin: 40px 0px;
        text-align: center;
    }
    .ward_list div ul li {
        flex: 0 0 10%;
        line-height: 2.2em;
    }
    .ward_list {
        flex-flow: column;
        display: flex;
        width: 100%;
        align-items: center;
        margin: 0;
        border: 1px solid #222;
        border-radius: 8px;
        margin-top: 10px !important;
    }
    .summary span{
        margin: 0 10px;
    }
    .summary{
        align-items: center;
    }
    .d-flex {
        justify-content: center;
        padding: 8px;
    }
    h4.heading {
        padding-top: 15px;
        margin-bottom: 0;
    }
    span
</style>
<body>
<div class="container">
    <div class="row d-flex">
        <h1>{{$party}} Report</h1>
    </div>

    <div class="row d-flex summary">
        <h4>{{$party}}</h4>
        <span> get served for </span> <h4> {{$party_parchi_and_list_count}} </h4>
        <span> block codes from </span> <h4> {{$wards_count}} </h4> <span> wards. </span>
    </div>

    <div class="row d-flex summary">
        <span>Total Voter Proccesed :</span> <h4> {{$party_voters}}</h4>
    </div>

    <div class="ward_list">
        <div>
            <h4 class="heading">
                Wards List
            </h4>
        </div>
        <div style="width: 100%;">
            <ul>
                @foreach($party_wards as $ward)
                    <li>{{$ward}}</li>
                @endforeach
            </ul>
        </div>
    </div>


</div>

<div class="container mt-4">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">WARD</th>
            <th scope="col">BLOCK CODES</th>
            <th scope="col">Voters</th>
            <th scope="col">Total Voters</th>
        </tr>
        </thead>
        <tbody>
        @foreach($sectors as $ward => $detail)
            <tr>
                <th scope="row">{{$ward}}</th>
                <td>
                    @foreach($detail as $key => $value)
                        <span>{{@$value->block_code}}</span>
                        <br>
                    @endforeach
                </td>
                <td>
                    @foreach($detail as $key => $value)
                        <span>{{@$value->polling_data_count}}</span>
                        <br>
                    @endforeach
                </td>
                <td>
                    {{$detail['ward_voter']}}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

</body>

