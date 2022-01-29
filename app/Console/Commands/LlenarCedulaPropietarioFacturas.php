<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\PropertiesPropietarios;
use App\PropertiesFacturas;
use App\Http\Requests\Admin\UpdateUsersRequest;
use Carbon\Carbon;
use Mail;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Input;

class LlenarCedulaPropietarioFacturas extends Command
{
    /**
     * The name and signature of the console command >>> se llena el campo cedula_propietario en tabla properties_facturas.
     *
     * @var string
     */
    protected $signature = 'email:llenarcedulapropietariofacturas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Llenar Cedula Propietario en tabla facturas';

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
        $facturas = PropertiesFacturas::all();
        //$facturas = PropertiesFacturas::where('id_estado','1')->get();

       // $idempresa=$usuario->idEmpresa;
       // $usuarios=User::where('idEmpresa','=',$idempresa);

        foreach ($facturas as $factura) {
          if(empty($factura->cedula_propietario))
          {
            $property_id=$factura->id_property;
            $propietario=PropertiesPropietarios::where('id_property',$property_id)->first();
            $factura = PropertiesFacturas::findOrFail($factura->id);
            /*$tenant->property_id
            $tenant->property_sub_id
            $tenant->cedula_propietario
            $tenant_role_id
            $tenant->property_id*/

            $factura->cedula_propietario = $propietario->cedula;

            $factura->save();
          }
            }
    	//Mostrando el resultado
    	//$this->info('Recordatorio enviado!');
    }

}
