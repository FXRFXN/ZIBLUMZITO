<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de ventas</title> 
    <link rel="stylesheet" href="{{public_path('css/custom_pdf.css')}}">
    <link rel="stylesheet" href="{{public_path('css/custom_page.css')}}">
   
</head>
<body>



    <section class="header" style="top: 287px;">
    <table cellpadding="0" cellpadding="0" width="100%">
    
           
        
        <td width="70%" class="text-center text-company" style="center-aling: top; padding-top:0px">
        @if($reportType ==0)    
         <span style="font-size: 30px"><strong>Reporte de ventas del dia</strong></span>
        @else
        <span style="font-size: 30px"><strong>Reporte de ventas por fechas</strong></span>
   
        @endif
        <br>
        @if($reportType !=0)
        <span style="font-size: 30px"><strong>Fecha de consulta:{{$dateFrom}} al {{$dateTo}}</strong></span>
        @else
        <span style="font-size: 16px"><strong>Fecha de consulta:{{ \Carbon\Carbon::now()->format('d-m-Y')}} </strong></span>
        @endif
        <br>
        <span style="font-size: 30px">Usuario: {{$user}}</span>
        </td>
        </tr>


    </table>
    </section>

    <section style="margin-top:50px">
        <table cellpadding="5" cellspacing="20" class="table-items" width="100%">

            <thead>
                <tr>
                    <th width="50%">Folio</th>
                    <th width="50%">Importe</th>
                    <th width="40%">Items</th>
                    <th width="50%">Estado</th>
                    <th>Usuario</th>
                    <th width="50%">Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $item)
                <tr>
                    <td width="10%" alling="center ">{{$item->id}}</td>
                    <td alling="center">${{number_format ($item->total,2)}}</td>
                    <td alling="center">{{$item->items}}</td>
                    <td alling="center">{{$item->status}}</td>
                    <td alling="center">{{$item->user}}</td>
                    <td alling="center">{{$item->created_at}}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-center">
                        <span><b>TOTALES</b></span>
                    </td>
                    <td colspan="1"  class="text-center">
                        <span><strong>${{number_format($data->sum('total'),2)}}</strong></span>

                        

                    </td>
                    <td class="text-center">
                        {{$data->sum('items')}}
                    </td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
        </table>
    </section>

    <section class="footer">
        <table cellpadding="0" cellspacing="0" class="table-items" width="100%">
            <tr>
                <td width="20%">
                    <span>ZIBLUM v1</span>
                </td>
              
                <td class="text-center" width="20%"></td>
                pagina <span class="pagenum"></span>
            </tr>
        </table>

    </section>
    
</body>
</html>