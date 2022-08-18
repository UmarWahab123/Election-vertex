
<h1>Elecion Expert</h1>
<div class="voter-class">
    <div class="main-voter-class">
        <div style="display: flex; justify-content: space-between;">
            <div>
                <table style="  display: table-cell; vertical-align: top; border: 1px solid black; padding: 10px; text-align: center;">
                    <thead>
                    <tr>
                        <td>Name</td>
                        <td>CNIC</td>
                        <td>Mobile</td>
                        <td>Address</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($pdfdata as $key => $value)
                        <tr>
                            <td>{{@($value['name'])}}</td>
                            <td>{{@($value['idcard'])}}</td>
                            <td>{{@($value['Mobile'])}}</td>
                            <td>{{@($value['address'])}}</td>
                            <td>{{@($value['block'])}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
    </div>

    </div>
</div>
