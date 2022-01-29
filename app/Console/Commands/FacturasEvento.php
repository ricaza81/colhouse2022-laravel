<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\PropertySub;
use App\PropertiesFacturas;
use App\PropertiesPagos;
use Carbon\Carbon;
use Mail;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use DB;

class FacturasEvento extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:facturasevento';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creación de Factura';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        //* * * * * php /path/to/artisan schedule:run 1>> /dev/null 2>&1
        $unidades = PropertySub::all();
        $fecha=date('Y-m-d');
        $mes=date('m');

        foreach ($unidades as $unidad)    {
             if ($unidad->cuenta_inquilinos2($unidad->id) > 0) {
                $factura=new PropertiesFacturas;
                $factura->id_property = $unidad->id_property;
                $factura->id_property_sub = $unidad->id;
                $factura->id_tenant = $unidad->id_tenant;
                $factura->id_estado = 1;
                $factura->fuente = 'cronjob';
                //$factura->fecha_inicio = '2021-'.$mes.'-01';
                $factura->fecha_inicio = '2022-01-01';
                //$factura->fecha_corte = '2021-'.$mes.'-01';
                $factura->fecha_corte = '2022-01-01';
                if($unidad->canon_unidad($unidad->id) != null) {
                    $factura->valor = $unidad->canon_unidad($unidad->id)->nuevo_canon;
                    $factura->valor_neto = $factura->valor;
                } else {
                    $factura->valor = $unidad->renta;
                    $factura->valor_neto = $factura->valor;
                }
                $factura->adicionales = 0;
                $factura->deducciones = 0;                
               
                $factura->save();
                
                $id_tenant=$factura->tenant->id;
                $tenant=User::findOrFail($id_tenant);
                $fecha_fin_contrato=$tenant->fecha_fin_contrato;

                if($tenant->prorroga_contrato($tenant->id) != null) {
                  $fecha_limite_renovacion1= strtotime ( '-90 day' , strtotime ( $tenant->prorroga_contrato($tenant->id)->fecha_fin_prorroga));
                  $fecha_limite_renovacion = date ( 'Y-m-d' , $fecha_limite_renovacion1 );
                } else {

                $fecha_fin_contrato=$tenant->fecha_fin_contrato;
                $fecha_limite_renovacion1= strtotime ( '-90 day' , strtotime ( $fecha_fin_contrato));
                $fecha_limite_renovacion = date ( 'Y-m-d' , $fecha_limite_renovacion1 );
                }
            }
            

         $data = array(
         'factura'                  => $factura,
         'id_tenant'                => $id_tenant,
         'tenant'                   => $tenant,
         'fecha_fin_contrato'       => $fecha_fin_contrato,
         'fecha_limite_renovacion'  => $fecha_limite_renovacion,
        );

        $pdf = \PDF::loadView('admin.properties_facturas.facturapdf',compact('factura','fecha_fin_contrato','fecha_limite_renovacion'));

        Mail::send('correo.plantilla_factura', $data, function ($message)
            use ($factura,$fecha_fin_contrato,$fecha_limite_renovacion,$pdf,$tenant) {
        $message->from('ricaza81@gmail.com', 'ColHouse');
        $message->to($tenant->email)
                ->cc('mcooper81@gmail.com')
        ->subject('Nueva factura creada')
        ->attachData($pdf->output(), "Factura COL-FT-".$factura->id.".pdf");
        });

        }
   
    
//}
         $facturas = PropertiesFacturas::where('id_estado','1')->where('deleted_at','=',NULL)->where('fecha_inicio','2022-01-01')->orderBy('id_tenant','ASC')->get();
           // $email_tenant = 'ricaza81@gmail.com';
                $asunto = 'Consolidado facturas creadas (nuevo período)';
          // foreach($facturas as $factura){
              
               $fecha=date('Y-m-d');
                $data = array(
                          //  'nombres' => $factura->tenant->name,
                          //  'email_tenant' => $factura->tenant->email,
                          //  'id_factura' => $factura->id,
                          //  'fecha' =>   $fecha,
                          //  'valor' =>   $factura->valor_neto,
                            'facturas' => $facturas,
                        );
            

                Mail::send('correo.plantilla_facturas_automaticas_nuevo_periodo', $data, function ($message) use ($asunto,$fecha,$facturas) {
                    //$message->from('crm@aplicatics.com', 'CRM Aplicatics');
                    $message->to('ricaza81@gmail.com')
                            ->cc('laurazambranoduran.abogada@gmail.com')
                            ->subject($asunto);  
                    //$message->to($destinatario)->subject($asunto);  
                                                                 });
    
            $this->info('Facturas creadas automaticamente');
       
    }
}