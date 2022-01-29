<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\PropertiesPropietarios;
use App\PropertiesFacturas;
use App\Property;
use App\Http\Requests\Admin\UpdateUsersRequest;
use Carbon\Carbon;
use Mail;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Input;

class LlenarCedulaPropiedades extends Command
{
    /**
     * The name and signature of the console command >>> se llena el campo cedula_propietario en tabla properties_facturas.
     *
     * @var string
     */
    protected $signature = 'email:llenarcedulapropiedades';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Llenar Cedula Propiedades en tabla property';

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
        $propiedades = Property::all();
        //$facturas = PropertiesFacturas::where('id_estado','1')->get();

       // $idempresa=$usuario->idEmpresa;
       // $usuarios=User::where('idEmpresa','=',$idempresa);

        foreach ($propiedades as $propiedad) {
          if(empty($propiedad->cedula_propietario) || $propiedad->cedula_propietario==1 )
          {
            $property_id=$propiedad->id;
            $propietario=PropertiesPropietarios::where('id_property',$property_id)->first();
            $propiedad = Property::findOrFail($propiedad->id);
            /*$tenant->property_id
            $tenant->property_sub_id
            $tenant->cedula_propietario
            $tenant_role_id
            $tenant->property_id*/

            $propiedad->cedula_propietario = $propietario->cedula;

            $propiedad->save();
          }
            }
    	//Mostrando el resultado
    	//$this->info('Recordatorio enviado!');
    }

}
