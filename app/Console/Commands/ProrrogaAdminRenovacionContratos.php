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

    protected $description = 'Actualización de Fechas de Contratos';


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
         //$fecha_actual=date('Y/m/d');
         $fecha_actual=date('Y-m-d');
         $usuarios_fin_contrato=User::where('fecha_fin_contrato',$fecha_actual)->where('estado',1)->where('deleted_at','=',NULL)->where('role_id','!=',1)->get();
         //dd($usuarios_fin_contrato);
         //dd($fecha_actual);
         $asunto = 'Actualización de Fechas de Contrato (informe automático)';
         if(isset($usuarios_fin_contrato)){
            foreach($usuarios_fin_contrato as $usuario){
               
               //fechas-numerodias
                $fecha_fin_contrato=$usuario->fecha_fin_contrato;
                $duracion_contrato_dias=$usuario->duracion_meses*30;
               //fechas-numerodias
               //CreacionNuevaProrroga
                $prorroga = new TenantProrrogaContrato;
                $prorroga->id_tenant = $usuario->id;
                $prorroga->duracion = $usuario->duracion_meses;
                $prorroga->fecha_inicio_prorroga = $fecha_fin_contrato;
                $fecha_fin_prorroga1= strtotime ( '+'.$duracion_contrato_dias.'day',strtotime( $fecha_fin_contrato));
                $fecha_fin_prorroga = date ( 'Y-m-d' , $fecha_fin_prorroga1);
                $prorroga->fecha_fin_prorroga=$fecha_fin_prorroga;
                $prorroga->save();
               //CreacionNuevaProrroga
            
               $data = array(
                            'usuarios' => $usuarios_fin_contrato,
                        );
           
                 
               Mail::send('correo.plantilla_prorroga_real_contratos_reporte', $data, function ($message) use ($asunto,$usuarios) {
                    $message->to('ricaza81@gmail.com')
                            ->cc('laurazambranoduran.abogada@gmail.com')
                            ->subject($asunto);
                        });
                    }
            }
    	//Mostrando el resultado
    	$this->info('¡Prórrogas de Contratos Actualizadas!');
    }
}
