@extends('brackets/admin-ui::admin.layout.default')
@section('title', 'Dashboard')

<style type="text/css">
    .container {
        margin-top: 100px
    }
    .section-title {
        margin-bottom: 38px
    }
    .shadow {
        box-shadow: 0px 15px 39px 0px rgba(8, 18, 109, 0.1) !important
    }
    .icon-primary {
        color: #062caf
    }
    .icon-bg-circle {
        position: relative
    }
    .icon-bg-circle::before {
        z-index: 1;
        position: relative
    }
    .icon-bg-primary::after {
        background: #062caf !important
    }
    .icon-bg-circle::after {
        content: '';
        position: absolute;
        width: 68px;
        height: 68px;
        top: -35px;
        left: 15px;
        border-radius: 50%;
        background: inherit;
        opacity: .1
    }
    .icon-bg-yellow::after {
        background: #f6a622 !important
    }
    .icon-bg-purple::after {
        background: #7952f5
    }
    .icon-yellow {
        color: #f6a622
    }
    .icon-purple {
        color: #7952f5
    }
    .icon-cyan {
        color: #02d0a1
    }
    .icon-bg-cyan::after {
        background: #02d0a1
    }
    .fa-users::before{
        font-size: 50px;
    }
    .fa-list-ol::before{
        font-size: 50px;
    }
    .div-link{
        cursor: pointer;
        text-decoration: none;
        color: #23282c;
    }
    .div-link:hover{
        cursor: pointer;
        text-decoration: none;
        color: #23282c;
    }
</style>
@section('body')
    <div class="container" style="margin-top: 50px !important;">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2 class="section-title"> DASHBOARD</h2>
            </div>
            @can('admin.administrator')
                <div class="col-lg-3 col-sm-6 mb-4">
                    <a href="{{ url('admin/general-notices') }}" class="div-link">
                        <div class="card border-0 shadow rounded-xs pt-5">
                            <div class="" style="text-align-last: center;"> <i class="fa fa-users icon-lg icon-primary icon-bg-primary icon-bg-circle mb-3"></i>
                                <h4 class="mt-4 mb-3">General Notices <br>  </h4>
                            </div>

                        </div>
                    </a>
                </div>

                <div class="col-lg-3 col-sm-6 mb-4">
                    <a href="{{ url('admin/tags') }}" class="div-link">
                        <div class="card border-0 shadow rounded-xs pt-5">
                            <div class="" style="text-align-last: center;"> <i class="fa fa-users icon-lg icon-yellow icon-bg-yellow icon-bg-circle mb-3"></i>
                                <h4 class="mt-4 mb-3">Tags <br> </h4>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-sm-6 mb-4">
                    <a href="{{ url('admin/users/') }}" class="div-link">
                        <div class="card border-0 shadow rounded-xs pt-5">
                            <div class="" style="text-align-last: center;"> <i class="fa fa-users icon-lg icon-purple icon-bg-purple icon-bg-circle mb-3"></i>
                                <h4 class="mt-4 mb-3">Users <br>  </h4>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-sm-6 mb-4">
                    <a href="{{ url('admin/assets') }}" class="div-link">
                        <div class="card border-0 shadow rounded-xs pt-5">
                            <div class="" style="text-align-last: center;"> <i class="fa fa-users icon-lg icon-cyan icon-bg-cyan icon-bg-circle mb-3"></i>
                                <h4 class="mt-4 mb-3">Assets<br> </h4>
                            </div>
                        </div>
                    </a>
                </div>
            @endcan
            @cannot('admin.administrator')
                <div class="col-lg-12 col-sm-12 mb-4">
                    <a href="{{ url('admin/polling-stations/all-sectors') }}" class="div-link">
                        <div class="card border-0 shadow rounded-xs pt-5">
                            <div class="" style="text-align-last: center;"> <i class="fa fa-list-ol icon-lg icon-primary icon-bg-primary icon-bg-circle mb-3"></i>
                                <h4 class="mt-4 mb-3">All Sectors Details<br>  </h4>
                            </div>

                        </div>
                    </a>
                </div>

                <div class="col-lg-12 col-sm-12 mb-4">
                    <div class="card border-0 shadow rounded-xs pt-5">
                        <div class="" style="text-align-last: center;">
                            <h3>
                                Serach Sector To Get Report
                            </h3>
                            <form action="{{url('/admin/admin-users/search-sector')}}" method="post">
                                @csrf
                                <div class="row p-5">
                                    <div class="col-3"></div>
                                    <div class="col-6">
                                        <input type="text" id="sector" class="form-control" name="sector" placeholder="Enter Sector Here" required>
                                    </div>
                                    <div class="col-2">
                                        <input type="submit" class="btn btn-primary" value="Search">
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- <div class="col-md-12" style="text-align-last: center; height: 300px">
                            <h4>Graph is Loading</h4>
                            <img id="loading-image" src="https://cdn.dribbble.com/users/1186261/screenshots/3718681/_______.gif" width="300px"/>
                        </div> -->
                <div class="col-md-12" id="block_code_graph">
                    <canvas id="chart1"></canvas>

                </div>
                <div class="col-md-6">
                    @if (\Session::has('error'))
                        <div class="alert alert-danger">
                            <ul>
                                <li>{!! \Session::get('error') !!}</li>
                            </ul>
                        </div>
                    @endif
                    <form action="{{url('/admin/admin-users/search-blockCode')}}" method="post">
                        @csrf
                        <input type="text" id="block_code" class="" name="block_code" placeholder="Block Code" required>
                        <input type="submit" value="Search">
                    </form>
                </div>
                <div class="col-md-12"  style="text-align-last: center;">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th scope="col">Code Block</th>
                            <th scope="col">Records</th>
                            <th scope="col">Sector</th>
                            <th scope="col">Total Urls</th>
                            <th scope="col">Invalid Urls</th>
                            <th scope="col">Pending Urls</th>
                            <th scope="col">Complete Urls</th>
                            <th scope="col">Manually Entered</th>
                            <th scope="col">Duplicate Urls</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($block_codes as $item)
                            <tr>
                                <th scope="row">{{@$item->polling_station_number}}</th>
                                <td>{{@$item->polling_details_count}}</td>
                                @if(@$item->sector)
                                    <td>{{@$item->sector->sector}}</td>
                                @else
                                    <td>Not Found</td>
                                @endif
                                <td>{{@$item->firebase_urls_count}}</td>
                                <td>{{@$item->invalid_urls}}</td>
                                <td>{{@$item->pending_urls}}</td>
                                <td>{{@$item->complete_urls}}</td>
                                <td>{{@$item->manually_entered}}</td>
                                <td>{{@$item->duplicate_entery}}</td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                    {{ $block_codes->links() }}
                </div>
            @endcan

            @can('admin.vertex-demonstration')
                <div class="col-lg-12 col-sm-12 mb-4">
                    <div class="card border-0 shadow rounded-xs pt-5">
                        <div class="" style="text-align-last: center;">
                            <h3>
                                Serach Sector To Get Report
                            </h3>
                            <form action="{{url('/admin/admin-users/search-sector')}}" method="post">
                                @csrf
                                <div class="row p-5">
                                    <div class="col-3"></div>
                                    <div class="col-6">
                                        <input type="text" id="sector" class="form-control" name="sector" placeholder="Enter Sector Here" required>
                                    </div>
                                    <div class="col-2">
                                        <input type="submit" class="btn btn-primary" value="Search">
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            @endcan

        </div>
    </div>



@endsection('body')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    window.onload = function () {

       //var ctx = document.getElementById('chart1').getContext("2d");

        var data = {
            labels: [1721178,1721190,1721194,1721182,1721133,1721179,1721144,1721021,1721019,1721174,1721008,1721193,1721195,1721199,1721156,1721157,1721007,1721168,1721188,1721167,1721162,1721172,1721205,1721173,1721001,1721132,1721159,1721180,1721165,1721161,1721023,1721191,1721184,1721185,1721181,1721187,1721170,1721119,1721183,1721203,1721160,1721164,1721158,1721202,1721003,1721037,1721189,1721200,1721192,1721198,1721115,1721129,1721175,1721206,1721196,1721163,1721147,1721005,1721171,1721146,1721122,1721177,1721121,1721097,1721082,1721166,1721127,1721130,1721091,1721085,1721043,1721131,1721084,1721120,1721118,1721117,1721036,1721150,1721134,1721197,1721086,1721135,1721125,1721152,1721201,1721142,1721151,1721138,1721149,1721139,1721155,1721153,1721140,1721143,1721141,1721137,1721145,1721126,1721148,1721136,1721123,1721124,1721154,1721128,1712074,1712067,1712096,1712109,1712078,1712071,1712069,1712098,1712073,1712085,1712091,1712072,1712080,1712084,1712112,1712117,1712058,1712111,1712066,1712079,1712083,1712075,1712086,1712061,1712087,1712093,1712099,1712088,1712100,1760020,1750030,1750035,1760004,1770006,1760027,1750025,1770003,1760010,1760011,1750038,1750026,1770008,1760025,1750040,1750010,1750023,1750037,1750022,1750008,1760007,1750052,1750016,1770004,1760013,1760001,1750002,1750034,1750027,1760002,1750028,1750006,1760023,1750039,1750005,1750036,1750050,1750031,1750013,1760022,1750054,1750011,1760014,1760018,1750033,1750019,1770001,1750049,1750020,1750043,1770016,1750003,1760012,1750042,1750048,1770002,1760021,1750051,1750055,1750044,1750021,1750009,1760017,1750032,1760008,1750053,1750015,1760005,1760015,1760019,1760009,1750024,1750045,1770012,1750001,1750004,1760006,1750007,1750047,1750018,1770005,1712055,1712105,1712104,1712059,1712102,1712103,1712114,1712124,1712064,1712050,1712090,1712113,1712077,1712097,1712081,1712082,1712119,1712089,1660037,1712101,1712062,1712028,1712004,1712037,1712040,1712006,1712052,1712065,1712001,1712002,1712016,1712010,1712029,1712035,1712009,1712015,1712039,1712011,1712026,1712054,1712018,1712047,1712033,1712045,1712048,1712034,1712022,1712122,1712123,1712108,1712036,1712017,1712021,1712041,1712107,1712014,1712023,1712168,1712013,1712031,1712106,1712057,213116,1712019,1712038,1712032,1712012,1712025,1712005,1712024,1712030,1712027,1712116,1712003,1712008,1712042,1712051,1712053,1740014,1740024,1740008,1740013,1740021,1740028,1740026,1740022,1740018,1740012,1740009,1740020,1740029,1740011,1740005,1740002,1740019,1740023,1740025,188110704,6213,81018,1660021,1660014,1660048,1660016,1660053,1660047,1660002,1660011,113173,1660043,1660050,1660052,1660013,1660003,1660024,1660045,1660020,1660028,1660010,1660025,1660027,1660026,1660017,1660041,1660005,1660004,1660019,1660049,1660042,1660006,1660023,1660022,1660015,1660018,1660044,1660046,1660051,1660061,1660001,1660057,1660030,1660032,1660040,1660033,1660038,1660035,1660039,1660034,1660029,1660036,1660060,1660056,1660054,1660055,181100801,181100805,1854002,1854006,1854019,1854022,1854030,1854010,1854027,0,1712115,1854011,1854021,1854023,1881100801,18811704,4170002,4170007,4179003,4179006,4179008,4191002,4201001,4201002,4390001,4390003,4390007,4101002,4101003,4101009,4101010,4351002,4260001,4270001,4270002,4270005,4340001,4340002,4351001,4081002,4081004,4081006,4081010,4090002,4090003,4090012,4091002,4250001,4250002,4250003,4090013,4120003,4120004,4130001,4249001,4401001,4401004,4401005,4401006,4401007,4401010,4140001,4151005,4151006,4211001,4290002,4290004,4290005,4290006,4220001,4220002,4240001,4430003,4430004,4430005,4450001,4450004,2220001,2200002,2200003,2200006,2220002,2220005,2220006,1430001,1430003,1430004,1430005,1430007,1430008,1430009,1430010,1430011,1430012,1430014,1430015,1430016,1430017,1430018,1430020,1430021,2660001,2660002,1971003,1971007,1971008,1971009,1971010,1971011,1971012,1971013,1971014,1971015,1971016,1971017,1971018,1971020,1971021,1971022,1971023,1971024,1971025,1971026,1971027,1971028,1971029,1971031,1971032,1971033,1971034,1971036,1980005,1971037,1980006,1980007,1980008,1980009,1980010,1980011,1980012,1980013,1980016,1980017,1980018,1980019,1980020,1980022,1980023,1980024,1980025,1980026,1980028,1980030,1980032,1990001,1990002,1990003,1990005,1990006,1990007,1990008,1990010,1990013,1990015,1990016,1990017,1990018,1990019,1990020,1990021,1990022,1990023,1990025,1990026,1990027,1990028,1990029,1990030,1990031,2000002,2040001,2040002,2040003,2040004,2040005,2040006,2040007,160008,1391001,1391004,1420001,1460001,1460003,1470001,1480001,1494002,1494004,1494006,1494009,1494010,1494011,1494012,1494013,1494014,1494017,1500002,1500003,1500004,1500005,1500006,1500007,1500008,1510001,1510002,1520001,1520003,1530002,1530004,1530006,1540001,1540002,1540003,1600001,1600002,1600004,1600005,1600006,1600007,1610001,1610002,1890003,1890004,1890005,1901001,1901002,1910003,1910005,1920002,1930001,1930004,1951001,1960001,2010001,2010002,2010003,2010004,2010005,2010006,2010007,2010009,2010010,2020001,2020003,2030001,2030002,2030003,2050001,2050002,2050004,2050005,2050006,2060001,2060002,2060003,2060004,2060005,2060006,2070002,2080001,2080004,2080005,2080006,2080007,2080012,2080016,2080018,2080019,2080020,2080021,2090001,2090002,2090003,2090005,2101002,2101004,2101005,2101006,2101007,2110001,2121004,2121017,2121020,2121030,2150001,2270001,2270002,2311001,2311003,2311006,2320001,2330002,2330003,2341002,2350003,2370001,2370002,2391002,2391003,2391004,2391005,2391009,2391011,2391012,2400001,2400002,2400005,2420001,2420002,2420003,2420004,2420005,2431001,2431002,2431003,2460002,2460003,2521001,2521002,2521003,3220002,3280001,3320001,3320002,3320003,3320004,3320005,3340001,3360001,3410002,3470005,3480001,3550001,3581006,3581008,3592001,3592002,3592003,3592014,3592015,3630002,3640001,3730001,3730003,3760001,3860002,3920001,3930001,3970001,3980001,4000001,4000002,4000003,4000004,1321001,1321003,1321004,1321005,1321006,1321007,1321008,1321009,1321010,1321011,1321014,1321015,1321016,1321017,1321018,1321019,1321020,1321021,1321023,1340001,1340002,1340003,1340004,1340005,1340006,1340008,1340009,1340010,1340011,1340012,1340013,1340014,1340015,1350002,1350003,1350004,1350006,1350009,1350007,1350010,1350013,1360001,1350011,1350012,1360004,1360002,1360005,1360003,1360006,1360007,1360008,1360009,1370002,1340007,1380001,1380002,1380003,1380005,1380006,2630001,4179005,4191007,4201003,4081009,4101007,4090016,4110002,4411003,4270004,4370001,4290001,4450003,2220003,4170004,18110081,1430022,1430019,1380004,1780200,1780201,1780202,1780203,1780204,1780205,1780207,1780208,1780209,1780210,1780211,1780212,1780213,1780214,1780215,1780216,1780217,1780218,1780219,1780220,1780221,1780222,1780223,1780224,1780225,1780227,1780226,1780228,1780229,1780230,1780231,1780232,1780233,1780234,1780235,1780237,1780238,1780240,1780241,1780242,1780244,1780245,1780246,1780247,1780248,1780249,1780251,1780252,1780253,1780254,1780255,1780256,1780257,1780258,1780259,1780260,1780261,1780262,1780263,1780264,1780265,1780266,1780267,1780268,1780270,1780271,1780272,1780273,1780274,1780275,1780276,1780277,1780278,1780280,1780279,1780281,1780282,1780283,1780284,1780285,1780287,1780288,1780289,1780290,1780291,1780292,1780294,1780293,1780295,1780296,1780297,1780298,1780299,1780300,1780002,1780003,1780006,1780007,1780008,1780009,1780010,1780011,1780012,1780013,1780014,1780016,1780015,1780017,1780018,1780020,1780021,1780023,1780026,1780024,1780025,1780027,1780028,1780029,1780030,1780031,1780032,1780033,1780034,1780035,1780037,1780039,1780040,1780041,1780048,1780050,1780051,1780049,1780052,1780061,1780063,1780064,1780068,1780090,1780066,1780091,1780092,1780099,1780022,1780098,1780400,1780402,1780403,1780405,1780406,1780407,1780408,1780409,1780410,1780411,1780413,1780414,1780412,1780415,1780417,1780418,1780419,1780420,1780421,1780422,1780423,1780424,1780425,1780427,1780428,1780429,1780430,1780431,1780432,1780433,1780434,1780435,1780436,1780437,1780438,1780439,1780440,1780441,1780442,1780444,1780445,1780446,1780447,1780448,1780449,1780450,1780101,1780102,1780103,1780104,1780105,1780107,1780108,1780109,1780110,1780111,1780112,1780113,1780114,1780115,1780116,1780117,1780118,1780119,1780120,1780121,1780122,1780123,1780124,1780125,1780126,1780127,1780128,1780130,1780131,1780132,1780133,1780134,1780135,1780136,1780137,1780138,1780139,1780140,1780141,1780142,1780143,1780144,1780145,1780146,1780147,1780148,1780150,1780151,1780152,1780153,1780154,1780155,1780156,1780158,1780157,1780159,1780160,1780162,1780161,1780163,1780164,1780166,1780167,1780165,1780168,1780170,1780172,1780173,1780174,1780175,1780176,1780177,1780178,1780179,1780180,1780181,1780182,1780183,1780184,1780185,1780186,1780187,1780188,1730188,1780190,1780191,1780192,1780193,1780194,1780195,1780196,1780197,1780198,1780199,1780301,1780302,1780303,1780304,1780305,1780306,1780308,1780307,1780309,1780310,1780311,1780312,1780313,1780314,1780315,1780316,1780317,1780318,1780319,1780320,1780321,1780322,1780323,1780325,1780326,1780324,1780327,1780328,1780329,1780330,1780331,1780333,1780334,1780335,1780336,1780337,1780338,1780339,1780340,1780341,1780342,1780343,1780344,1780345,1780346,1780348,1780349,1780350,1780351,1780352,1780353,1780355,1780356,1780357,1780358,1780359,1780360,1780361,1780362,1780363,1780364,1780365,1780366,1780367,1780368,1780369,1780370,1780371,1780372,1780373,1780374,1780375,1780376,1780377,1780378,1780380,1780381,1780382,1780383,1780384,1780385,1780386,1780387,1780389,1780390,1780391,1780392,1780393,1780394,1780395,1780396,1780397,1780398,1780001,1780005,1780043,1780044,1780045,1780046,1780047,1780053,1780054,1780055,1780056,1780057,1780058,1780059,1780060,1780062,1780065,1780067,1780069,1780070,1780071,1780073,1780074,1780075,1780076,1780078,1780079,1780077,1780080,1780081,1780082,1780083,1780084,1780085,1780086,1780087,1780088,1780089,1780093,1780094,1780095,1780096,1780097,1780169,1780171,1780106,1780189,1780354,1780404,1780206,null,1780347],

            datasets: [
                {
                    label: "Block Code Details",
                    fill: false,
                    // lineTension: 0.1,
                    backgroundColor: "rgba(75,192,192,0.4)",
                    borderColor: "rgba(75,192,192,1)",
                    // borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderColor: "rgba(75,192,192,1)",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(75,192,192,1)",
                    pointHoverBorderColor: "rgba(220,220,220,1)",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: [22,2,3,2,2,6,8,2,1,2,5,1,1,3,5,5,2,2,5,2,7,2,12,1,4,20,2,3,8,23,2,2,4,3,3,1,13,18,2,12,2,3,5,2,5,2,8,4,2,6,2,5,2,3,6,2,5,5,18,14,2,7,3,3,6,2,11,2,1,2,1,11,2,2,9,9,2,2,2,11,3,3,18,2,25,2,2,2,3,3,2,2,2,9,5,6,2,7,28,19,2,2,2,4,3,2,2,7,2,5,14,4,17,21,10,31,4,17,15,11,7,4,3,6,26,9,5,1,20,7,2,22,29,1,5,3,2,2,1,2,2,2,2,5,2,1,1,2,4,4,12,2,3,1,1,2,1,1,2,2,2,2,1,9,2,1,4,5,0,2,4,2,1,1,4,2,2,2,10,2,1,2,1,1,4,4,1,1,1,1,1,1,1,2,2,1,2,2,2,4,2,1,1,2,9,1,2,3,7,2,3,2,2,2,2,9,3,2,3,2,3,13,2,2,8,5,1,2,4,6,12,11,4,22,25,2,2,3,26,18,10,20,4,2,2,41,2,4,4,2,2,3,1,4,7,26,2,8,3,2,5,4,33,12,3,4,5,2,4,5,2,1,7,2,4,2,0,2,2,1,3,1,2,2,2,2,2,4,3,3,2,5,18,2,2,25,2,2,2,1,2,2,2,2,1,2,2,11,16,2,1,140,0,0,20,7,2,17,2,5,41,3,0,2,2,5,2,11,15,2,2,2,2,14,2,12,2,2,2,12,26,3,2,2,2,4,7,2,3,2,2,1,48,13,6,3,2,3,4,29,3,2,10,8,2,5,5,3,38,36,10,6,8,18,3,10,6,0,2,6,6,12,0,0,2,3,3,2,3,2,2,3,2,2,3,2,2,3,2,0,3,2,2,2,3,2,3,3,3,2,2,3,2,2,3,5,3,2,1,2,3,3,3,6,3,3,2,2,4,3,2,2,3,3,3,2,3,3,3,2,3,3,2,3,3,0,2,3,2,5,2,2,4,3,3,22,3,3,3,9,3,3,3,6,2,3,6,2,4,2,2,2,2,3,3,3,3,2,2,3,3,3,3,3,3,2,2,2,2,2,3,3,2,3,3,2,2,2,2,2,2,3,3,2,2,2,2,2,3,3,2,2,3,2,2,2,2,2,2,2,2,2,3,3,3,3,3,3,3,2,2,2,2,2,3,2,2,2,2,0,2,2,2,2,2,2,2,3,3,3,2,2,0,2,2,3,2,2,3,2,3,2,2,2,2,3,3,3,3,2,2,3,2,2,2,2,2,2,2,2,2,3,3,2,3,2,2,2,3,2,3,5,2,2,2,2,3,2,3,2,2,3,2,0,2,2,2,2,2,2,3,3,2,2,2,2,2,2,2,3,2,2,2,2,3,3,2,4,2,3,2,2,2,2,2,2,2,2,2,2,2,2,2,3,2,2,2,3,2,2,2,3,3,3,2,2,3,3,3,3,3,3,3,2,2,2,2,2,0,3,2,2,3,3,2,2,2,2,2,2,2,3,0,0,2,3,2,2,2,2,2,2,2,2,3,3,3,3,2,3,3,3,3,2,3,3,2,2,2,2,3,2,2,3,3,2,2,3,3,2,3,3,3,3,2,3,2,3,3,3,3,2,3,3,6,3,3,4,3,3,5,3,3,9,3,2,3,2,2,3,3,3,3,8,5,8,3,5,2,2,2,2,2,2,3,2,2,3,0,2,12,2,12,3,2,4,3,3,3,3,3,5,3,2,0,3,3,3,2,2,2,2,2,2,2,2,2,2,2,3,2,2,2,2,2,0,2,2,2,6,8,2,3,10,6,3,3,4,5,8,3,3,3,9,5,10,3,3,3,3,3,4,5,4,3,7,4,3,8,3,3,3,5,3,4,5,4,3,8,6,5,5,5,5,15,3,3,3,4,3,3,3,3,7,6,14,35,5,5,3,3,2,28,6,20,6,10,13,6,3,9,36,20,14,47,39,4,8,10,15,15,21,32,0,16,23,18,8,7,8,35,8,21,12,17,51,7,3,3,3,3,4,8,11,25,5,24,5,20,21,13,0,4,19,13,2,3,3,12,2,6,3,6,18,30,31,48,32,12,5,8,3,20,28,41,3,20,6,6,31,15,5,3,8,5,9,0,6,8,5,5,6,35,10,22,3,8,5,13,4,3,7,12,66,0,22,10,3,8,41,12,3,8,21,9,3,16,12,5,3,13,8,38,5,4,6,3,4,4,3,6,10,14,3,5,3,5,8,5,17,13,10,13,13,37,32,3,3,6,3,3,3,3,3,3,3,10,11,7,3,13,77,0,4,8,5,3,3,9,17,12,17,28,26,6,4,3,4,3,10,13,15,11,11,11,28,8,6,3,19,3,4,3,3,3,3,4,9,3,3,6,6,8,4,26,3,4,3,4,5,6,5,0,7,10,13,7,4,13,4,3,3,8,37,33,4,3,13,15,5,3,7,25,8,11,6,15,25,8,12,3,4,5,13,4,20,4,5,3,29,9,7,6,5,12,5,3,5,8,7,3,2,3,3,6,6,3,3,3,6,5,6,13,4,3,8,18,55,37,71,5,21,5,3,3,11,4,3,3,3,3,3,3,3,5,3,3,8,3,3,3,6,5,5,5,6,3,7,5,3,2,3,3,7,4,3,24,18,71,12,3,4,16,28,17,12,8,20,11,11,11,4,13,23,11,8,11,3,13,12,20,4,4,4,23,7,15,6,3,19,13,14,11,10,15,5,2,5,21,3,18,5,5,4,3,3,0,3],
                    spanGaps: false,
                }
            ]
        };

        var options = {
            responsive: true,
            title: {
                display: true,
                position: "top",
                // text: 'anything',
                fontSize: 18,
                fontColor: "#111"
            },
            tooltips: {
                enabled: true,
                mode: 'single',
                callbacks: {
                    label: function(tooltipItems, data) {

                        var multistringText = [];
                        multistringText.push(`Total: ${[tooltipItems.yLabel]}`);

                        const complete = [21,2,3,2,2,6,8,2,1,2,5,1,1,3,5,5,2,2,5,2,7,2,12,1,4,20,2,3,8,23,2,2,4,3,3,1,13,18,2,12,2,3,5,2,5,2,8,4,2,6,2,5,2,3,6,2,5,5,18,14,2,7,3,3,6,2,11,2,1,2,1,11,2,2,9,9,2,2,2,11,3,3,18,2,25,2,2,2,3,3,2,2,2,9,5,6,2,7,28,19,2,2,2,4,3,2,2,7,2,5,14,4,17,21,10,31,4,17,15,11,7,4,3,6,26,9,5,1,20,7,2,22,29,1,5,3,2,2,1,2,2,2,2,5,2,1,1,2,4,4,12,2,3,1,1,2,1,1,2,2,2,2,1,9,2,1,4,5,0,2,4,2,1,1,4,2,2,2,10,2,1,2,1,1,4,4,1,1,1,1,1,1,1,2,2,1,2,2,2,4,2,1,1,2,9,1,2,3,7,2,3,2,2,2,2,9,3,2,3,2,3,13,2,2,8,5,1,2,4,6,12,11,4,22,25,2,2,3,26,18,10,20,4,2,2,41,2,4,4,2,2,3,1,4,7,26,2,8,3,2,5,4,33,12,3,4,5,2,4,5,2,1,7,2,4,2,0,2,2,1,3,1,2,2,2,2,2,4,3,3,2,5,18,2,2,24,2,2,2,1,2,2,2,2,1,2,2,10,16,2,1,65,0,0,20,7,2,17,2,5,41,3,0,2,2,5,2,11,15,2,2,2,2,14,2,12,2,2,2,12,26,3,2,2,2,4,7,2,3,2,2,1,48,13,6,2,2,3,4,28,3,2,10,8,2,5,5,3,38,36,10,6,8,18,3,10,6,0,2,6,6,12,0,0,2,3,3,2,3,2,2,3,2,2,3,2,2,3,2,0,3,2,2,2,3,2,3,3,3,2,2,3,2,2,3,5,3,2,1,2,3,3,3,6,3,3,2,2,4,3,2,2,3,3,3,2,3,3,3,2,3,3,2,3,3,0,2,3,2,5,2,2,4,3,3,22,3,3,3,9,3,3,3,6,2,3,6,2,4,2,2,2,2,3,3,3,3,2,2,3,3,3,3,3,3,2,2,2,2,2,3,3,2,3,3,2,2,2,2,2,2,3,3,2,2,2,2,2,3,3,2,2,3,2,2,2,2,2,2,2,2,2,3,3,3,3,3,3,3,2,2,2,2,2,3,2,2,2,2,0,2,2,2,2,2,2,2,3,3,3,2,2,0,2,2,3,2,2,3,2,3,2,2,2,2,3,3,3,3,2,2,3,2,2,2,2,2,2,2,2,2,3,3,2,3,2,2,2,3,2,3,5,2,2,2,2,3,2,3,2,2,3,2,0,2,2,2,2,2,2,3,3,2,2,2,2,2,2,2,3,2,2,2,2,3,3,2,4,2,3,2,2,2,2,2,2,2,2,2,2,2,2,2,3,2,2,2,3,2,2,2,3,3,3,2,2,3,3,3,3,3,3,3,2,2,2,2,2,0,3,2,2,3,3,2,2,2,2,2,2,2,3,0,0,2,3,2,2,2,2,2,2,2,2,3,3,3,3,2,3,3,3,3,2,3,3,2,2,2,2,3,2,2,3,3,2,2,3,3,2,3,3,3,3,2,3,2,3,3,3,3,2,3,3,6,3,3,4,3,3,5,3,3,9,3,2,3,2,2,3,3,3,3,8,5,8,3,5,2,2,2,2,2,2,3,2,2,3,0,2,12,2,12,3,2,4,3,3,3,3,3,5,3,2,0,3,3,3,2,2,2,2,2,2,2,2,2,2,2,3,2,2,2,2,2,0,2,2,2,6,8,2,3,10,6,3,3,4,5,8,3,3,3,9,5,10,3,3,3,3,3,4,5,4,3,7,4,3,8,3,3,3,5,3,4,5,4,3,8,6,5,5,5,5,15,3,3,3,4,3,3,3,3,7,6,14,35,5,5,3,3,2,28,6,20,6,10,13,6,3,9,36,20,14,47,39,4,8,10,15,15,21,32,0,16,23,18,8,7,8,35,8,21,12,16,51,7,3,3,3,3,4,8,11,25,5,24,5,20,21,13,0,4,19,13,2,3,3,0,2,6,3,6,0,30,31,48,32,12,5,8,3,20,28,41,3,20,6,6,31,15,5,3,8,5,9,0,6,8,5,5,6,35,10,22,3,8,5,13,4,3,7,12,66,0,22,10,3,8,41,12,3,8,21,9,3,16,12,5,3,13,8,38,5,4,6,3,4,4,3,6,10,14,3,5,3,5,8,5,17,13,10,13,13,37,32,3,3,6,3,3,3,3,3,3,3,10,11,7,3,13,77,0,4,8,5,3,3,9,17,12,17,28,26,6,4,3,4,3,10,13,15,11,11,11,28,8,6,3,19,3,4,3,3,3,3,4,9,3,3,6,6,8,4,26,3,4,3,4,5,6,5,0,7,10,13,7,4,13,4,3,3,8,37,33,4,3,13,15,5,3,7,25,8,11,6,15,25,8,12,3,4,5,13,4,20,4,5,3,29,9,7,6,5,12,5,3,5,8,7,3,2,3,3,6,6,3,3,3,6,5,6,13,4,3,8,18,55,37,71,5,21,5,3,3,11,4,3,3,3,3,3,3,3,5,3,3,8,3,3,3,6,5,5,5,6,3,7,5,3,2,3,3,7,4,3,24,9,71,12,3,4,16,28,17,12,8,20,11,11,11,4,13,23,11,8,11,3,13,12,20,4,4,4,23,7,15,6,3,19,13,14,11,10,15,5,2,5,21,3,18,5,5,4,3,3,0,3];
                        multistringText.push(`Complete: ${complete[tooltipItems.index]}`);

                        const invalid = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,13,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                        multistringText.push(`Invalid: ${invalid[tooltipItems.index]}`);

                        const pendind = [1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                        multistringText.push(`Pendind: ${pendind[tooltipItems.index]}`);

                        const manually_entered = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,7,0,0,0,0,18,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                        multistringText.push(`Manually Entered: ${manually_entered[tooltipItems.index]}`);

                        return multistringText;
                    }
                }
            },
            legend: {
                display: true,
                position: "bottom",
                labels: {
                    fontColor: "#333",
                    fontSize: 16
                }
            },
            scales:{
                yAxes:[{
                    ticks:{
                        min:0

                    }
                }]

            }
        };

        // var myLineChart = new Chart(ctx, {
        //     type: 'line',
        //     data: data,
        //     options: options
        // });
    }

</script>

{{--<script type="text/javascript">--}}

{{--    $(document).ready(function() {--}}
{{--        $('#block_code_graph').html('');--}}
{{--        $.ajax({--}}
{{--            url: "/admin/admin-users/block-code-graph",--}}
{{--            beforeSend: function() {--}}
{{--                $("#loading-image").show();--}}
{{--            },--}}
{{--            success: function (response) {--}}
{{--                console.log(response)--}}
{{--                $('#block_code_graph').html(response);--}}
{{--            },--}}
{{--            complete: function(){--}}
{{--                $('#loading-image').hide();--}}
{{--            },--}}
{{--        });--}}
{{--    });--}}

{{--</script>--}}

