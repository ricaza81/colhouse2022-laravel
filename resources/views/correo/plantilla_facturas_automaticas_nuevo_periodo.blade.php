<!DOCTYPE html>
<html lang="en">

<link rel="stylesheet" href="https://www.agronielsen.com/encampo/public/css/sistemalaravel.css">

<head>
    <meta charset="UTF-8">
    <title>Notificaci&oacute;n de Eventos</title>
   <style>

   .titulo {
    color: #1e80b6;
    padding-top: 20px;
    padding-bottom: 10px;
    padding-left: 20px;
    padding-right: 20px;
    }

    .body{
     background-color: #fff;    
    }


    .div_contenido{
    color: #1e80b6;
    padding-top: 20px;
    padding-bottom: 10px;
    padding-left: 20px;
    padding-right: 20px;
    background-color: #ffffff !important;
   }
   
   .bg-aqua, .callout.callout-info, .alert-info, .label-info, .modal-info .modal-body {
    background-color: #00c0ef !important;
}

   </style>

</head> 

<body class="body">
<hr>

 <img src="{{url('/css/imagenes/logo-colhouse.png')}}" alt="ColHouse" width="270"  style="border:0; margin:0; padding:0; display:block;" editable label="image-1">

<div class="label label-warning"></b></div>
<br>
<div class="label label-warning">A continuaci&oacute;n encontrar&aacute; el detalle de las facturas creadas automáticamente por el sistema para el nuevo periodo de 2022.</div>
<br>
<br>

<div align="center" style="background: #4267b2; color:#fff;
    line-height: 6px;
    padding: 20px 20px 20px 20px;border-radius: 8px 8px 8px 8px;width: 90%;margin: auto;"><h2>Informe facturas creadas: Total facturas creadas >>> {{count($facturas)}}</h2><br/>
    <span>Cada factura ha sido enviada al email de cada inquilino</span>
</div>

    <table align="center"
    style="margin-top: 20px;
    padding: 20px 20px 20px 20px;
    border: 2px solid #fff;
    vertical-align: baseline;
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #4CAF50;
    color: white;
    border-radius: 8px 8px 8px 8px;
    margin-bottom: 20px;"
    width="80%">
    <thead>
        <tr>
             <th align="center" scope="col" style="width:20px;padding:20px 20px 20px 20px;border-bottom:3px solid #fff"># Factura</th>
            <th align="center" scope="col" style="width:20px;padding:20px 20px 20px 20px;border-bottom:3px solid #fff">Arrendatario</th>
            <th align="center" scope="col" style="width:20px;padding:20px 20px 20px 20px;border-bottom:3px solid #fff">Valor</th>
            <th align="center" scope="col" style="width:20px;padding:20px 20px 20px 20px;border-bottom:3px solid #fff">Fecha vencimiento</th>

        </tr>

        <?php
            foreach ($facturas as $factura){ ?>
            <tr role="row" class="odd"  >

                <td align="center" style="width:20px;padding:20px 20px 20px 20px;border-bottom:1px solid #4CAF50;background-color: #fff;color:#4CAF50;">COL-FT-{{ $factura->id or '' }}</td>
                <td align="center" style="width:20px;padding:20px 20px 20px 20px;border-bottom:1px solid #4CAF50;background-color: #fff;color:#4CAF50;"><a href="{{url('/admin/tenants/'.$factura->tenant->id)}}">{{ $factura->tenant->name or '' }}</a><br/>
                    <span>email: {{ $factura->tenant->email or '' }}</span>
                </td>
                <td align="center" style="width:20px;padding:20px 20px 20px 20px;border-bottom:1px solid #4CAF50;background-color: #fff;color:#4CAF50;">${{number_format($factura->valor_neto) }}</td>
                 <td align="center" style="width:20px;padding:20px 20px 20px 20px;border-bottom:1px solid #4CAF50;background-color: #fff;color:#4CAF50;">{{ $factura->fecha_corte or '' }}</td>

            </tr>
  </thead>       
        <?php
            }
        ?>

    </table>

</div>

<br/>
<hr>
<div class=".div_contenido" ><br/><b>https://www.colhouse.com.co</b></div>
    
</body>
</html>