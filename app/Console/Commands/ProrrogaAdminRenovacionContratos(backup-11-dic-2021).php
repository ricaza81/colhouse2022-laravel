<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\TenantProrrogaContrato;
use App\PropertiesFacturas;
use Carbon\Carbon;
use Mail;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Input;

class ProrrogaAdminRenovacionContratos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:prorrogarenovacioncontratos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualización de Fechas de Contrato';

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
                   
         $usuarios = User::where('estado',1)->where('deleted_at','=',NULL)->where('role_id','!=',1)->get();
           // $email_tenant = 'ricaza81@gmail.com';
         $fecha_actual=date('Y-m-d');
         $asunto = 'Actualización de Fechas de Contrato (informe automático)';

         //dd($fecha_actual);

             
           foreach($usuarios as $usuario){
             if($usuario->fecha_fin_contrato == $fecha_actual ) {
             //if(sizeof($usuario->fechafincontrato_fechaactual($usuario->id)) {
              
               //dd($usuario->fecha_fin_contrato);
               //$fecha_fin_contrato=$usuario->fecha_fin_contrato;
               $fecha_limite_renovacion1=strtotime('-90 day',strtotime($fecha_fin_contrato));
               $fecha_limite_renovacion =date('Y-m-d',$fecha_limite_renovacion1);
               $duracion_contrato=$usuario->duracion_meses;
               $duracion_contrato_dias=$duracion_contrato*30;

               /*Carbon*/
               //$fecha_start1=Carbon::createFromFormat('d-m-Y', $fecha_limite_renovacion1)->format('d-m-Y');
               //$fecha_start=Carbon::parse($fecha_start1);
               $fecha_start=$fecha_limite_renovacion;    
               //$fecha_end=Carbon::now();
               //$difference = $fecha_start->diffInDays($fecha_end);
               /*Carbon*/
               
               /*Test*/
               //$fecha_start=$fecha_limite_renovacion1;               
               $fecha_end=date ( 'Y-m-d' );
               $fecha_end1=Carbon::parse($fecha_end);

               //$difference == 0;
               //$difference = Carbon::parse($fecha_fin_contrato)->diffInDays($fecha_end1);
               //$difference = Carbon::parse($fecha_limite_renovacion)->diffInDays($fecha_end1);
               /*Test*/

               //if($difference == 0) {
               if($usuario->fecha_fin_contrato == $fecha_actual) {
               //if($difference == NULL) {

               //CreacionNuevaProrroga
                $prorroga=new TenantProrrogaContrato;
                $prorroga->id_tenant = $usuario->id;
                $prorroga->duracion = $duracion_contrato;
                
                /*Carbon*/
                //$prorroga->fecha_inicio_prorroga = Carbon::now();
                $prorroga->fecha_inicio_prorroga =$usuario->fecha_fin_contrato;
                $fecha_inicio_prorroga=$usuario->fecha_fin_contrato;
                $fecha_fin_prorroga1= strtotime ( '+'.$duracion_contrato_dias.'day',strtotime($fecha_inicio_prorroga));
                $fecha_fin_prorroga = date ( 'Y-m-d' , $fecha_fin_prorroga1);
                //$prorroga->fecha_fin_prorroga = strtotime ( $duracion_contrato_dias , strtotime ( $fecha_inicio_prorroga));
                //$prorroga->fecha_fin_prorroga = strtotime ( '+180 day' , strtotime ( $fecha_inicio_prorroga));
                $prorroga->fecha_fin_prorroga=$fecha_fin_prorroga;
                /*Carbon*/

                /*Test*/
                //$prorroga->fecha_inicio_prorroga =$usuario->fecha_fin_contrato;
                //$prorroga->fecha_fin_prorroga =date ( 'Y-m-d' );
                /*Test*/

                $prorroga->save();
               //CreacionNuevaProrroga
            }
        }
               $data = array(
                         //   'difference' => $difference,
                            'usuarios' => $usuarios,
                        );

           //}
      }
                Mail::send('correo.plantilla_prorroga_real_contratos_reporte', $data, function ($message) use ($asunto,$usuarios) {
                    //$message->from('crm@aplicatics.com', 'CRM Aplicatics');
                    $message->to('ricaza81@gmail.com')
                            ->cc('laurazambranoduran.abogada@gmail.com')
                            ->subject($asunto);  
                    //$message->to($destinatario)->subject($asunto);  
                                                                 });

    	//Mostrando el resultado
    	$this->info('Recordatorio enviado!');
    }

}
