@extends('brackets/admin-ui::admin.layout.default')
@section('title', 'Block Code Report')
<style>
    #blockcodeContainer {
        margin: 0;
        padding: 0;
    }

    .blockcode_inr_wrapper {
        padding: 10px;
        display: flex;
        flex-wrap: wrap;
    }

    .bc_top_heading {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: flex-start;
    }

    .bcdetail_box {
        display: flex;
        flex-wrap: wrap;
        width: 45%;
        margin: 0 auto;
    }

    .bc_col1 {
        flex: 1 1 48%;
        padding: 10px;
    }

    .bc_row1 {
        padding: 20px;
        background: #2F89FC;
        color: #fff;
        border-radius: 10PX;
        TEXT-ALIGN: center;
    }

    .bc_row1 h3 {
        margin: 0;
        font-size: 16px;
    }

    .bc_row2 {
        PADDING: 20PX;
        text-align: center;
        border: 1px solid gray;
        border-radius: 10px;
        background: #fff;
    }

    .bc_col2 {
        flex: 1 1 48%;
        padding: 10px;
    }

    .bc_heading {
        padding: 20px;
    }

    .bc_card {
        width: 200px;
        margin: 10px;
    }

    .column_boxes {
        display: flex;
        flex-wrap: wrap;
    }
    @media screen and (max-width: 600px) {
        .bc_card {
            width: 100%;
        }
        .bcdetail_box {
            width: 100%;
        }
    }
</style>
@section('body')
    <div id="blockcodeContainer">
        <div class="blockcode_inr_wrapper">
            <div class="bc_top_heading">
                <h2>BLOCK CODE : {{$election_sector->block_code}}</h2> <span>Sector: {{$election_sector->sector}}</span>
            </div>

            <div class="bc_top_heading">
                <a target="_blank" class="btn btn-warning mr-2" href="https://vertex.plabesk.com/voterDetailEntryRecheck/{{$election_sector->block_code}}">
                    Re-Check
                </a>
                <a target="_blank" class="btn btn-facebook mr-2" href="https://vertex.plabesk.com/voterDetailEntry/{{$election_sector->block_code}}">
                    Serial/Family # Entry
                </a>
            </div>

            <div class="bcdetail_box">
                <div class="bc_col1">
                    <div class="bc_heading">IMAGES DETAILS</div>
                    <div class="column_boxes">
                        <div class="bc_card">
                            <div class="bc_row1">
                                <h3>Images In DB</h3>
                            </div>
                            <div class="bc_row2"><span>{{count($firebase_urls)}}</span></div>
                        </div>
                        <div class="bc_card">
                            <div class="bc_row1">
                                <h3>Images Invalid</h3>
                            </div>
                            <div class="bc_row2"><span>{{$response['invalid_pages']}}</span></div>
                        </div>
                        <div class="bc_card">
                            <div class="bc_row1">
                                <h3>Images Duplicate</h3>
                            </div>
                            <div class="bc_row2"><span>{{$response['duplicate_pages']}}</span></div>
                        </div>
                        <div class="bc_card">
                            <div class="bc_row1">
                                <h3>Missing Block Code</h3>
                            </div>
                            <div class="bc_row2">
                                <span>{{$response['missing_blockcode_pages']}}</span></div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="bcdetail_box">
                <div class="bc_col2">
                    <div class="bc_heading">VOTER DETAILS</div>
                    <div class="column_boxes">
                        <div class="bc_card">
                            <div class="bc_row1">
                                <h3>Male Voters</h3>
                            </div>
                            <div class="bc_row2">
                                <span>{{$response['male']}} / {{$election_sector->male_vote}}</span>
                                <div><span>Male Duplicate : {{$dis['male_duplicate']}}</span></div>

                                <div><span><b>Male Serial Break</b></span></div>
                                @foreach($dis['male_serial_break'] as $row)
                                    <span>start: {{$row['start']}}</span>
                                    <span>end: {{$row['end']}}</span>
                                    <span>diff: {{$row['difference']}} </span>

                                @endforeach
                            </div>

                        </div>
                        <div class="bc_card">
                            <div class="bc_row1">
                                <h3>Female Voters</h3>
                            </div>
                            <div class="bc_row2">
                                <span>{{$response['female']}} / {{$election_sector->female_vote}}</span>
                                <div><span>FeMale Duplicate : {{$dis['female_duplicate']}}</span></div>
                                <div><span><b>Female Serial Break</b></span></div>
                                @foreach($dis['femail_serial_break'] as $row)
                                    <span>start: {{$row['start']}}</span>
                                    <span>end: {{$row['end']}}</span>
                                    <span>diff: {{$row['difference']}} </span>


                                @endforeach
                            </div>
                        </div>
                        <div class="bc_card">
                            <div class="bc_row1">
                                <h3>Total Voters</h3>
                            </div>
                            <div class="bc_row2"><span>{{$response['male'] + $response['female']}} / {{$election_sector->total_vote}}</span></div>
                        </div>
                        <div class="bc_card">
                            <div class="bc_row1">
                                <h3>24 Records Page</h3>
                            </div>
                            <div class="bc_row2"><span>{{$response['page_24']}}</span></div>
                        </div>
                        <div class="bc_card">
                            <div class="bc_row1">
                                <h3>28 Records Page</h3>
                            </div>
                            <div class="bc_row2"><span>{{$response['page_28']}}</span></div>
                        </div>
                        <div class="bc_card">
                            <div class="bc_row1">
                                <h3>Other Records Page</h3>
                            </div>
                            <div class="bc_row2"><span>{{$response['other']}}</span></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection('body')
