@extends('brackets/admin-ui::admin.layout.default')
@section('title', 'Dashboard')

<style type="text/css">
    .container {
        margin-top: 100px
    }
    .section-title {
        margin-bottom: 38px
    }
    .btn{
        margin-right: 2px !important;
    }
    select#party {
        width: 250px;
        padding: 6px;
        border: 1px solid darkcyan;
        border-radius: 8px;
    }
</style>
@section('body')
    <div class="container" style="margin-top: 50px !important;">

        <div class="row">
            <div class="col-lg-12 text-center">
                <h2 class="section-title">{{$sector}} - Sector Details</h2>
            </div>

            <div class="col-lg-12 text-center">
                <div class="col-lg-12 text-center">
                    <h5 class="section-title"> Missing Serial No : {{$total_null_sr}}</h5>
                </div>

                <div class="col-lg-12 text-center">
                    <h5 class="section-title"> Missing Family No : {{$total_null_fm}}</h5>
                </div>

                <div class="col-lg-12 text-center">
                    <h5 class="section-title"> Total Record : {{$total_record}}</h5>
                </div>

                <div class="col-lg-12 text-center">
                    <select name="party" id="party">
                        @foreach($parties as $party)
                            <option value="{{$party}}">{{$party}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex justify-content-center m-5">
                    @if(Auth::user()->id == 34)

                                        <div class="btn btn-facebook btn_download_multiple_pdfs">Download All Voter lists</div>
                                            <div class="btn btn-vine btn_download_voter_parchi">Download All Voter Parchi</div>
                        @endif
                        <div class="btn btn-twitter btn_apply_nums_to_all">Apply phone numbers to all links</div>
                    <div class="btn btn-vimeo btn_put_pic_slice">Put Picture Slice to All</div>
                </div>

            </div>


            {{--            <div class="col-md-6">--}}
            {{--                @if (\Session::has('error'))--}}
            {{--                    <div class="alert alert-danger">--}}
            {{--                        <ul>--}}
            {{--                            <li>{!! \Session::get('error') !!}</li>--}}
            {{--                        </ul>--}}
            {{--                    </div>--}}
            {{--                @endif--}}
            {{--                <form action="{{url('/admin/admin-users/search-blockCode')}}" method="post">--}}
            {{--                    @csrf--}}
            {{--                    <input type="text" id="block_code" class="" name="block_code" placeholder="Block Code" required>--}}
            {{--                    <input type="submit" value="Search">--}}
            {{--                </form>--}}
            {{--            </div>--}}

            <div class="col-md-12"  style="text-align-last: center;">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">Code Block</th>
                        <th scope="col">Records</th>
                        <th scope="col">Total</th>
                        <th scope="col">Sector</th>
                        <th scope="col">Total Urls</th>
                        <th scope="col">Invalid Urls</th>
                        <th scope="col">Pending Urls</th>
                        <th scope="col">Complete Urls</th>
                        <th scope="col">Manually Entered</th>
                        <th scope="col">Duplicate Urls</th>
                        <th scope="col">Missing Serial No</th>
                        <th scope="col">Missing Family No</th>
                        @if(Auth::user()->id == 34)

                        <th scope="col">Download PDF</th>
                        <th scope="col">Download Parchi</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($block_codes as $item)

                        @if(@$item->polling_details_count != @$item->sector->total_vote)
                            <tr style="background-color: #ffabab">
                                <th scope="row"><a href="{{url('voterDetailEntry/'.@$item->polling_station_number)}}" target="_blank" class="block_code" data-block_code="{{@$item->polling_station_number}}"> {{@$item->polling_station_number}}</a></th>
                                <td>{{@$item->polling_details_count}}</td>
                                <td>{{@$item->sector->total_vote}}</td>
                                @if(@$item->sector)
                                    <td>{{@$item->sector->sector}}</td>
                                @else
                                    <td>Not Found</td>
                                @endif

                                <td>{{@$item->firebase_urls_count}}</td>
                                <td>
                                    <a href="/admin/firebase-urls/invalid/?ps_no={{@$item->polling_station_number}}">
                                        {{@$item->invalid_urls}}
                                    </a>
                                </td>
                                <td>{{@$item->pending_urls}}</td>
                                <td>{{@$item->complete_urls}}</td>
                                <td>{{@$item->manually_entered}}</td>
                                <td>{{@$item->duplicate_entery}}</td>
                                <td>{{@$item->null_serial_count}}</td>
                                <td>{{@$item->null_family_count}}</td>
                                    @if(Auth::user()->id == 34)
                                <td>
                                    @if(@$item->null_serial_count == 0 )
                                        <a href="/api/get-pdf-view/{{@$item->polling_station_number}}" target="_blank" class="download-pdf-link">Download PDF</a>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn-download-parchi" href="https://vertex.plabesk.com/admin/election/voter-parchi-view/{{$item->polling_station_number}}?party=PMLN">Download Parchi</a>
                                </td>
                                      @endif
                            </tr>

                        @else
                            <tr>
                                <th scope="row"><a href="{{url('voterDetailEntry/'.@$item->polling_station_number)}}" target="_blank" class="block_code" data-block_code="{{@$item->polling_station_number}}"> {{@$item->polling_station_number}}</a></th>
                                <td>{{@$item->polling_details_count}}</td>
                                <td>{{@$item->sector->total_vote}}</td>
                                @if(@$item->sector)
                                    <td>{{@$item->sector->sector}}</td>
                                @else
                                    <td>Not Found</td>
                                @endif

                                <td>{{@$item->firebase_urls_count}}</td>
                                <td>
                                    <a href="/admin/firebase-urls/invalid/?ps_no={{@$item->polling_station_number}}">
                                        {{@$item->invalid_urls}}
                                    </a>
                                </td>
                                <td>{{@$item->pending_urls}}</td>
                                <td>{{@$item->complete_urls}}</td>
                                <td>{{@$item->manually_entered}}</td>
                                <td>{{@$item->duplicate_entery}}</td>
                                <td>{{@$item->null_serial_count}}</td>
                                <td>{{@$item->null_family_count}}</td>
                                @if(Auth::user()->id == 34)
                                @cannot('admin-politics-imageuplaod')
                                <td>
                                    @if(@$item->null_serial_count == 0 )
                                        <a href="/api/get-pdf-view/{{@$item->polling_station_number}}" target="_blank" class="download-pdf-link">Download PDF</a>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn-download-parchi" href="https://vertex.plabesk.com/admin/election/voter-parchi-view/{{$item->polling_station_number}}?party=PMLN">Download Parchi</a>
                                </td>
                                    @endif
                                @endcannot
                            </tr>
                        @endif
                    @endforeach
                    </tbody>

                </table>
                {{ $block_codes->links() }}
            </div>

        </div>
        @endsection('body')


        <script>

            document.addEventListener('DOMContentLoaded', () => {

                document.querySelector('#party').addEventListener('input' , e => {
                    var party = e.target.value;
                    Array.from(document.querySelectorAll('.btn-download-parchi')).map(i => {
                        i.href = i.href.substring(0, i.href.indexOf('?') !== -1 ? i.href.indexOf('?') : i.href.length ) + "?party=" + party;
                    })
                })

                document.querySelector('.btn_download_multiple_pdfs').addEventListener('click', e => {
                    const links = document.querySelectorAll('.download-pdf-link');
                    let ids = [];
                    // console.log(links)
                    if(links) {
                        links.forEach(link => {
                            if(!ids.includes(link)) {
                                ids.push(link);
                                setTimeout(() => {
                                    link.click();
                                }, 10)
                            }
                            // window.open(link.href, "_blank");
                        })
                    }
                })

                document.querySelector('.btn_apply_nums_to_all').addEventListener('click', e => {
                    const links = document.querySelectorAll('.block_code');
                    let ids = [];
                    // console.log(links)
                    if(links) {
                        links.forEach(link => {
                            const code = link.dataset.block_code;
                            if(!ids.includes(code)) {
                                ids.push(code);
                                setTimeout(() => {
                                    window.open(`https://vertex.plabesk.com/api/get_phone_number/${code}`, "_blank")
                                }, 10)
                            }
                            // window.open(link.href, "_blank");
                        })
                    }
                })

                document.querySelector('.btn_download_voter_parchi').addEventListener('click', e => {
                    const links = document.querySelectorAll('.block_code');
                    var party = document.querySelector('#party').value;
                    let ids = [];
                    // console.log(links)
                    if(links) {
                        links.forEach(link => {
                            const code = link.dataset.block_code;
                            if(!ids.includes(code)) {
                                ids.push(code);
                                setTimeout(() => {
                                    window.open(`https://vertex.plabesk.com/admin/election/voter-parchi-view/${code}?party=${party}`, "_blank")
                                    // window.open(`https://vertex.plabesk.com/admin/data-sets/get-record-DHA-lahore/${code}`, "_blank")
                                }, 10)
                            }
                            // window.open(link.href, "_blank");
                        })
                    }
                })

                document.querySelector('.btn_put_pic_slice').addEventListener('click', e => {
                    const links = document.querySelectorAll('.block_code');
                    let ids = [];
                    // console.log(links)
                    if(links) {
                        links.forEach(link => {
                            const code = link.dataset.block_code;
                            if(!ids.includes(code)) {
                                ids.push(code);
                                setTimeout(() => {
                                    window.open(`https://vertex.plabesk.com/api/cut_slice_from_pic/${code}`, "_blank")
                                }, 10)
                            }
                            // window.open(link.href, "_blank");
                        })
                    }
                })
            })
        </script>
