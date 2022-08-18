@extends('brackets/admin-ui::admin.layout.default')
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>
<style>
    body {
        padding: 0;
        margin: 0;
        background-color: #e7eaf4;
        font-family: "Gilroy", sans-serif;
        height: 100%;
    }
    .main {
        display: flex;
        justify-content: space-between;
        border: 1px solid darkgrey;
        border-radius: 5px;
        background-color: white;
        text-align: center;
        padding: 15px 30px 15px 10px ;
        margin-left: 0px !important;
    }
    section{
        padding: 30px 50px;
        display: flex !important;
        justify-content: center;
    }

    .input-group{
        margin-bottom: 20px;
        justify-content: end;
    }
    h3{
        font-size: 18px;
        font-weight: bold;
    }
    .leger {
        display: flex;
        flex-direction: column;
    }
    /*.main-leger{*/
    /*    width:70%;*/
    /*}*/
    nav.flex.items-center.justify-between .flex.justify-between.flex-1.sm\:hidden {
        padding: 20px;
        margin-left: 690px;
    }
    .column {
        width: 15%;
        padding: 4px;
    }
</style>

@section('body')
{{--    @dd($customers);--}}
    <section>
      <div class="main-leger">
        <div class="input-group">
            <label for="business_name">Select Customer: </label>
            @if(!empty($customers) && $customers->count())

            <select name="customer_id" id="customer_id" class="form-select select-box form-select-sm" aria-label=".form-select-sm example">
                <option value="">Select Customer</option>
                @foreach($customers as $customer)
                    <option value="{{$customer->id}}">{{$customer->name}}</option>
                @endforeach
            </select>
            @endif
            <button id="search-button" type="button" class="btn btn-primary">
                <i class="fas fa-search"></i>
            </button>
        </div>

          <div class="leger">
{{--        <div>--}}
            <div class="main">

                <div class="column">
                   <h3>Reference No.</h3>
                </div>
                <div class="column">
                    <h3> Customer</h3>
                </div>
                <div class="column">
                    <h3> Advance</h3>
                </div>
                <div class="column">
                    <h3> Ballance Type</h3>
                </div>
                <div class="column">
                    <h3> Credit</h3>
                </div>
                <div class="column">
                    <h3> Debit</h3>
                </div>
                <div class="column">
                    <h3> Balance</h3>
                    <p></p>
                </div>
            </div>
{{--        </div>--}}
              <div id="showcustomerdata">
        </div>
              {!! $customers->links() !!}

          </div>
      </div>
    </section>
@endsection

<script type='text/javascript' >
    $(document).ready(function() {
        $('#customer_id').change(function () {
            let customer_id = $(this).val();

            fieldClear();
            console.log(customer_id);
            $.ajax({
                url: "{{'/admin/customers/show-customer-payments/'}}"+customer_id,
                type: 'get',
                success: function(customers){
                    console.log(customers);
                    customers.forEach(customer =>{
                        console.log(customer)
                        var cre=customer.credit;
                        var credit= numberWithCommas(cre);

                        var deb=customer.debit;
                        var debit= numberWithCommas(deb);

                        var balan =customer.balance;
                        var balance= numberWithCommas(balan);


                    var data = `<div class="main"><div class="column"><h3><a href="{{url('/admin/customers/generate-custom-invoice/${customer.id}')}}">${customer.invoice_no}</a></h3></div>
                    <div class="column"><h3>${customer.name}</h3></div>
                    <div class="column"><h3>${customer.advance_case}</h3></div>
                    <div class="column"><h3>${customer.balance_type}</h3></div>
                    <div class="column"><h3><img src="{{asset('images/uparrow.png')}}" width="30" height="50">
                   ${credit}
                   </h3></div>
                    <div class="column"><h3><img src="{{asset('images/downarrow.png')}}"width="30" height="50">${debit}</h3></div>
                    <div class="column"><h3>${balance}</h3></div></div>`;
                        $('#showcustomerdata').append(data);
                    });
                }
            });
        });
    });
    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    function fieldClear()
    {
        $('#showcustomerdata').text('');
    }
</script>
