
    <h1>Elecion Expert</h1>
    <div class="voter-class" id="showResult" style="display: none;">
    <div class="main-voter-class">
        <div style="display: flex; justify-content: space-between;">
            <div class="voter-detail">
                <h4>Name: </h4> <p>{{@($pdfdata->name)}}</p>
                <h4>CNIC: </h4><p>{{@($pdfdata->idcard)}}</p>
                <h4>Mobile: </h4><p>{{@($pdfdata->Mobile)}}</p>
                <h4>Address</h4><p>{{@($pdfdata->address)}}</p>
                <h4>Polling Station</h4><p>{{@($pdfdata->polling)}}</p>
            </div>
        </div>

    </div>

</div>
