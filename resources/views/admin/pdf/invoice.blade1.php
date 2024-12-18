<!DOCTYPE html>
<html>
<head>
    <title>{{Config::get('yourdata.company_name')}}</title>

    <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">
</head>
<body>
    <div class="login-box">

      <div class="login-logo" style="width: 100% !important; text-align: center !important; line-height: 0.5 !important;">
            <img src="{{ asset('/assets') }}/images/Logo_la-grange.jpg" height="95" weight="256" alt="">
       <!-- Bio Tout Court-->

            

      </div>

      <!-- /.login-logo -->

      <div class="card">

        <div class="card-body" style="width: 100% !important; text-align: right !important; margin: 0 30px 0 30px !important; line-height: 0.5 !important;">
                <div style="width: 50% !important; float: left !important; text-align: left !important; vertical-align: bottom !important;">
                    <p>Facture n°: {{$invoice_no}}</p>
                    <p>TVA intracommunautaire: @if($getDetail->tva != NULL) {{$getDetail->tva}} @endif</p>
                    <p>SIRET: @if($getDetail->siret != NULL) {{$getDetail->siret}} @endif</p>
                    <p>{{$today}}</p>
                </div>

                <div style="width: 50% !important; float: left !important; text-align: left !important;">
                    <p style="color: blue !important;">{{$fullname}}</p>
                    <p style="color: black !important;">E-mail: {{$email}}</p>
                    <p style="color: black !important;">Téléphoner: {{$phone}}</p>
                </div>

        </div>
        




        <div class="card-body" style="width: 100% !important; text-align: left !important; margin:120px 30px 0 30px !important;">
                <hr>
        </div>

        <div class="card-body" style="width: 100% !important; text-align: left !important; margin: 30px 30px 0 30px !important; line-height: 0.5 !important;">
                <table style="width: 100% !important; text-align: center !important;">
                    <tr style="width: 100% !important; text-align: center !important; color: #4d0000 !important; line-height: 2.0 !important;">
                       <td><b>Dated</b></td>
                       <td><b>Description</b></td>
                       <td><b>Montant</b></td> 
                    </tr>



                    <tr style="width: 100% !important; text-align: center !important; line-height: 0.5 !important;">
                       <td>{{$payment_date}}</td>
                       <td>{!!$show_description!!}</td>
                       <td>{{$slot_price}} €</td> 
                    </tr>

                    <tr style="width: 100% !important; text-align: center !important; line-height: 2.0 !important;">
                       <td>{{$payment_date}}</td>
                       <td>TVA ({{$tax}}%)</td>
                       <td>{{$this_tax}} €</td> 
                    </tr>

                    <!-- <tr style="width: 100% !important; text-align: center !important; line-height: 2.0 !important;">
                       <td colspan="3"><hr></td>
                       <td></td>
                       <td></td> 
                    </tr> -->

                    <tr style="width: 100% !important; background-color: #ccccb3 !important; text-align: center !important; line-height: 2.0 !important; margin-top: 20px !important;">
                       <td colspan="2">Prix total:</td> 
                       <td> {{$slot_price_with_tax}} €</td>
                    </tr>
                </table>
        </div>

        

      </div>

</div>
<footer style="margin-top: 500px !important; width: 100% !important; color:  #4d4d33 !important; text-align: center !important;">
<small>{!!$pdf_footer!!}</small>
</footer>
</body>
</html>