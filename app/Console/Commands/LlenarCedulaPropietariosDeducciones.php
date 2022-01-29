<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\PropietariosDeducciones;
use App\PropertiesPropietarios;
use Carbon\Carbon;
use Mail;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Input;

class LlenarCedulaPropietariosDeducciones extends Command
{
    /**
     * The name and signature of the console command >>> se llena el campo cedula_propietario en tabla properties_facturas.
     *
     * @var string
     */
    protected $signature = 'email:llenarcedulapropietariosdeducciones';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Llenar Cedula Propietarios Deducciones en tabla properties_propietarios_deducciones';

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
        $deducciones = PropietariosDeducciones::all();
        //$facturas = PropertiesFacturas::where('id_estado','1')->get();

       // $idempresa=$usuario->idEmpresa;
       // $usuarios=User::where('idEmpresa','=',$idempresa);

        foreach ($deducciones as $deduccion) {
          if(empty($deduccion->cedula_propietario) || $deduccion->cedula_propietario==1 )
          {
            $propietario=PropertiesPropietarios::where('id',$deduccion->id_propietario)->first();
            $deduccion = PropietariosDeducciones::findOrFail($deduccion->id);
            $deduccion->cedula_propietario = $propietario->cedula;
            $deduccion->save();
          }
            }
    	//Mostrando el resultado
    	//$this->info('Recordatorio enviado!');
    }

}
