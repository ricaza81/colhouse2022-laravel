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

class NotificacionCodeudoresInquilinoMora extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:notificacioncodeudormora';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notificación Codeudor Arrendatario en Mora';

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
        $asunto = 'Arrendatario en mora';
        $fecha = date('Y-m-d');
        $dia_fecha = date('d');
        $dia_reporte = 9;
        $facturas = PropertiesFacturas::where('id_estado','1')->where('deleted_at','=',NULL)->orderBy('id_tenant','ASC')->get();

        if($dia_reporte == $dia_fecha)
        foreach ($facturas as $factura)
        {
            $id_tenant=$factura->id_tenant;
            $tenant=User::findOrfail($id_tenant);
            $facturas_vencidas_tenant=PropertiesFacturas::where('id_estado','1')->where('id_tenant',$id_tenant)->where('deleted_at','=',NULL)->orderBy('id_tenant','ASC')->get();
            $codeudor=$tenant->codeudor;
            //$email_codeudor=$tenant->email_codeudor;
            $email_codeudor='ricaza81@gmail.com';
        

        $data = array(
                        'facturas' => $facturas_vencidas_tenant,
                        'tenant' => $tenant->name,
                        'codeudor' => $codeudor,
                     );

        Mail::send('correo.plantilla_codeudor_inquilino_mora_reporte', $data, function ($message) use ($asunto,$fecha,$facturas,$tenant,$codeudor)
                            {
                            $message->to('ricaza81@gmail.com')
                                    //->cc('laurazambranoduran.abogada@gmail.com')
                                    ->subject($asunto);
                            }
                        );
        }
        //Mostrando el resultado
        $this->info('Codeudor notificado!');
    }
}
