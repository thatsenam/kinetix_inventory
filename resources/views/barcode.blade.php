<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Preview</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</head>
<body>
            <div class="container">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                Business Name: <input type="checkbox" id="myBusiness" onclick="toggleBusiness()">
                            </div>
                            <div class="col-md-3">
                                Display Title: <input type="checkbox" id="myTitle" onclick="toggleTitle()">
                            </div>
                            <div class="col-md-3">
                                Display Price: <input type="checkbox" id="myPrice" onclick="togglePrice()">
                            </div>
                            <div class="form-group col-md-3 row">
                                <label for="mySelect" class="col-sm-6 col-form-label">Number of Label</label>
                                <div class="col-sm-6">
                                    <input type="number" name="nm" id="mySelect" value="20" class="form-control" style="max-width:70px;">
                                </div>
                            </div>
                        </div>
                        <div class="text-center" id=
                        "gg"><a onclick="window.print()" target="_blank" class="btn btn-primary" target="_blank">Print</a></div>
                    </div>
                </div>
                <div class="barcodes mt-4">
                    <div class="row">
                        @foreach($products as $product)
                        <div class="col-md-3" id="repeatDiv">
                            <p class="text-center" id="business" style="margin-bottom: 0;"><b>BeautyShop</b></p>
                            <p class="text-center" id="title" style="margin-bottom: 0;">Name: {{$product->product_name}}</p>
                            <p class="text-center" id="price" style="margin-bottom: 0;">Price: {{$product->after_pprice}}</p>
                            <div class="text-center">{!!'<img style="max-width:100%; height: 0.4in" src="data:image/png;base64,' . DNS1D::getBarcodePNG($product->barcode, 'C39',3,33,array(43,43,43), true) . '" alt="barcode"   />'!!}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
<style>
    @media print {
        .card {display: none;}
        #gg {display: none;}
    }
</style>
    <script>
        jQuery("#mySelect").change(function() {
        var value = jQuery(this).val();
        var $repeatDiv = jQuery("#repeatDiv");
        
        for(var i = 0; i < value; i++) {
            $repeatDiv.after($repeatDiv.clone().attr("id", "repeatDiv" + new Date().getTime()));
        }
        });

        function toggleTitle() {
            var title = document.getElementById("myTitle").checked;
            if (title) {
                document.getElementById("title").style.display = "none";
            } else {
                document.getElementById("title").style.display = "block";
            }
        }
        
        function toggleBusiness() {
            var title = document.getElementById("myBusiness").checked;
            if (title) {
                document.getElementById("business").style.display = "none";
            } else {
                document.getElementById("business").style.display = "block";
            }
        }

        function togglePrice() {
            var price = document.getElementById("myPrice").checked;
            if (price) {
                document.getElementById("price").style.display = "none";
            } else {
                document.getElementById("price").style.display = "block";
            }
        }
    </script>
</body>
</html>

