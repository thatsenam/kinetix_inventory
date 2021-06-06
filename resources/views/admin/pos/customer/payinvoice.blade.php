<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Reciept {{$get_customer->invoice_no}}</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="reciept-main col-12 m-auto">
                <div class="buttons">
                    <button onclick="window.print()" class="print-button"><span class="print-icon"></span></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="receipt-main col-print-12 col-12 m-auto">
                <div class="receipt-header">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="receipt-left">
                                <img class="img-responsive" alt="{{$GenSettings->site_name ?? ''}}" src="/images/theme/{{$GenSettings->logo_small ?? ''}}" style="width: 120px; border-radius: 25px;">
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="receipt-right">
                                <h3>{{$GenSettings->site_name?? ''}}.</h3>
                                <h5>{{$GenSettings->phone ?? ''}}<i class="fa fa-phone"></i></h5>
                                <h5>{{$GenSettings->email ?? ''}} <i class="fa fa-envelope-o"></i></h5>
                                <h5>{{$GenSettings->site_address ?? ''}} <i class="fa fa-location-arrow"></i></h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="receipt-header receipt-header-mid">
                    <div class="row">
                        <div class="col-xs-8 col-sm-8 col-md-8 text-left">
                            <div class="receipt-right">
                            @foreach($cust_details as $cust_detail)
                                <h4>{{$cust_detail->name}} <small>  |   Reciept Number : {{$get_customer->invoice_no ?? ''}}</small></h4>
                                <h5><b>Mobile :</b> {{$cust_detail->phone?? ''}}</h5>
                                <h5><b>Address :</b> {{$cust_detail->address?? ''}}</h5>
                            @endforeach
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="float-left">
                                <h1>Receipt</h1>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border: 1px solid gray">
                                <td class="col-md-9">{{$get_customer->description}}</td>
                                <td class="col-md-3">
                                    {{$get_customer->amount}}/-
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right">
                                    <p>
                                        <strong>Total Amount: </strong>
                                    </p>
{{--                                    <p>--}}
{{--                                        <strong>Late Fees: </strong>--}}
{{--                                    </p>--}}
                                </td>
                                <td>
                                    <p>
                                        <strong><i class="fa fa-inr"></i> {{$get_customer->amount??''}}/-</strong>
                                    </p>
{{--                                    <p>--}}
{{--                                        <strong><i class="fa fa-inr"></i> 0.00/-</strong>--}}
{{--                                    </p>--}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>
                                        <strong><i class="fa fa-inr"></i> Payment Type</strong>
                                    </p>
                                </td>
                                <td>
                                    <p>
                                        <strong><i class="fa fa-inr"></i> {{$get_customer->method??''}}</strong>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right"><h2><strong>Total: </strong></h2></td>
                                <td class="text-left text-danger"><h2><strong><i class="fa fa-inr"></i> {{$get_customer->amount??''}}/-</strong></h2></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="receipt-header receipt-header-mid receipt-footer">
                    <div class="row">
                        <div class="col-xs-8 col-sm-8 col-md-8 text-left">
                            <div class="receipt-right">
                                <p><b>Date :</b> <?php echo date('d/m/Y', strtotime($get_customer->date)); ?></p>
                                <h5 style="color: rgb(140, 140, 140);">Thank you for your business!</h5>
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="receipt-left">
                                <h1>Signature</h1>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>

        span{
            font-size: 180% /*px, cm, in, etc.*/;
        }
        p {
            font-size: 180% /*px, cm, in, etc.*/;
        }
        .text-danger strong {
    		color: #9f181c;
		}
		.receipt-main {
			background: #ffffff none repeat scroll 0 0;
			border-bottom: 12px solid #333333;
			border-top: 12px solid #9f181c;
			margin-top: 50px;
			margin-bottom: 50px;
			padding: 40px 30px !important;
			position: relative;
			box-shadow: 0 1px 21px #acacac;
			color: #333333;
			font-family: open sans;
		}
		.receipt-main p {
			color: #333333;
			font-family: open sans;
			line-height: 1.42857;
		}
		.receipt-footer h1 {
			font-size: 15px !important;
			font-weight: 400 !important;
		}
		.receipt-main::after {
			background: #414143 none repeat scroll 0 0;
			content: "";
			height: 5px;
			left: 0;
			position: absolute;
			right: 0;
			top: -13px;
		}
		.receipt-main thead {
			background: #414143 none repeat scroll 0 0;
		}
		.receipt-main thead th {
			color:#fff;
		}
		.receipt-right h5 {
			font-size: 16px;
			font-weight: bold;
			margin: 0 0 7px 0;
		}
		.receipt-right p {
			font-size: 12px;
			margin: 0px;
		}
		.receipt-right p i {
			text-align: center;
			width: 18px;
		}
		.receipt-main td {
			padding: 9px 20px !important;
		}
		.receipt-main th {
			padding: 13px 20px !important;
		}
		.receipt-main td {
			font-size: 13px;
			font-weight: initial !important;
		}
		.receipt-main td p:last-child {
			margin: 0;
			padding: 0;
		}
		.receipt-main td h2 {
			font-size: 20px;
			font-weight: 900;
			margin: 0;
			text-transform: uppercase;
		}
		.receipt-header-mid .receipt-left h1 {
			font-weight: 100;
            font-size: 35px;
			margin: 34px 0 0;
			text-align: right;
			text-transform: uppercase;
		}
		.receipt-header-mid {
			margin: 24px 0;
			overflow: hidden;
		}

		#container {
			background-color: #dcdcdc;
		}

        button.print-button {
            width: 65px;
            height: 65px;
        }
        span.print-icon, span.print-icon::before, span.print-icon::after, button.print-button:hover .print-icon::after {
            border: solid 4px #333;
        }
        span.print-icon::after {
            border-width: 2px;
        }

        button.print-button {
            position: relative;
            padding: 0;
            border: 0;

            border: none;
            background: transparent;
        }

        span.print-icon, span.print-icon::before, span.print-icon::after, button.print-button:hover .print-icon::after {
            box-sizing: border-box;
            background-color: #fff;
        }

        span.print-icon {
            position: relative;
            display: inline-block;
            padding: 0;
            margin-top: 20%;

            width: 60%;
            height: 35%;
            background: #fff;
            border-radius: 20% 20% 0 0;
        }

        span.print-icon::before {
            content: " ";
            position: absolute;
            bottom: 100%;
            left: 12%;
            right: 12%;
            height: 110%;

            transition: height .2s .15s;
        }

        span.print-icon::after {
            content: " ";
            position: absolute;
            top: 55%;
            left: 12%;
            right: 12%;
            height: 0%;
            background: #fff;
            background-repeat: no-repeat;
            background-size: 70% 90%;
            background-position: center;
            background-image: linear-gradient(
                to top,
                #fff 0, #fff 14%,
                #333 14%, #333 28%,
                #fff 28%, #fff 42%,
                #333 42%, #333 56%,
                #fff 56%, #fff 70%,
                #333 70%, #333 84%,
                #fff 84%, #fff 100%
            );

            transition: height .2s, border-width 0s .2s, width 0s .2s;
        }

        button.print-button:hover {
            cursor: pointer;
        }

        button.print-button:hover .print-icon::before {
            height:0px;
            transition: height .2s;
        }
        button.print-button:hover .print-icon::after {
            height:120%;
            transition: height .2s .15s, border-width 0s .16s;
        }
        .col-print-12{width:100%; float:left;}
        @media print {
            .hr{display: block}
            .col-print-12{width:100% !important; float:left;}
            .callout { display: none }
            .sidebar { display: none }
            .footer { display: none }
            .no-print { display: none }
            .buttons { display: none }
            .table td { border: 1px solid #dee2e6 }
            .table tr { border: 1px solid #dee2e6 }
            .table thead { border: 1px solid #dee2e6 }
            .receipt-main{ width: 100%; }
        }
    </style>
</body>
</html>
