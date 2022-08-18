<!DOCTYPE html>
<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>


        $(document).ready(function(){
            // var count = 0;
            // setInterval(function() {
            //     var t = count++;
            //     console.log(t);
            //     window.open('https://vertex.plabesk.com/api/cron_getFamilyAndSerialNo?token=$2y$10$X6Y0dBD072OdaOlEUMh4ue.6osLcDqs7Knjh191LRlA69vcsBJT72','_blank');
            // },3000);
            setInterval(ajax, 3000);


        });

        var count = 1;
        function ajax(){
            $.ajax({
                type:"get",
                // url:"https://vertex.plabesk.com/api/cron_cloudinary_api?token=$2y$10$X6Y0dBD072OdaOlEUMh4ue.6osLcDqs7Knjh191LRlA69vcsBJT72",
                // url:"https://vertex.plabesk.com/api/generate_blockcode_report?token=$2y$10$X6Y0dBD072OdaOlEUMh4ue.6osLcDqs7Knjh191LRlA69vcsBJT72",
                url:"https://vertex.plabesk.com/api/download_pdf_cron",
                // url:"https://vertex.plabesk.com/api/cron_getFamilyAndSerialNo?token=$2y$10$X6Y0dBD072OdaOlEUMh4ue.6osLcDqs7Knjh191LRlA69vcsBJT72",
                datatype:"json",
                success:function(data)
                {
                    var total = count++;
                    $('#div1').text(total);
                    console.log(total);
                    console.log(data);
                    //do something with response data
                }
            });
        }
    </script>
</head>
<body>

<div >
    <center>
    <h1 id="div1"> </h1>
    </center>
</div>


</body>
</html>
