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

class LlenarCedulaInquilinos extends Command
{
    /**
     * The name and signature of the console command >>> se llena el campo cedula_propietario en tabla users.
     *
     * @var string
     */
    protected $signature = 'email:llenarcedulainquilinos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Llenar Cedula Propietario';

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
        $usuarios = User::all();
        //$facturas = PropertiesFacturas::where('id_estado','1')->get();

       // $idempresa=$usuario->idEmpresa;
       // $usuarios=User::where('idEmpresa','=',$idempresa);

        foreach ($usuarios as $usuario) {
          if(empty($usuario->cedula_propietario))
          {
            $property_id=$usuario->property_id;
            $propietario=PropertiesPropietarios::where('id_property',$property_id)->first();
            $tenant = User::findOrFail($usuario->id);
            /*$tenant->property_id
            $tenant->property_sub_id
            $tenant->cedula_propietario
            $tenant_role_id
            $tenant->property_id*/

            $tenant->cedula_propietario = $propietario->cedula;

            $tenant->save();

            //$tenant->update(all());



          }
            }
        

    	//Mostrando el resultado
    	//$this->info('Recordatorio enviado!');
    }

}
