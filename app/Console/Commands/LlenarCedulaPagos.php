<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\PropertiesPropietarios;
use App\PropertiesFacturas;
use App\Property;
use App\PropertiesPagos;
use App\Http\Requests\Admin\UpdateUsersRequest;
use Carbon\Carbon;
use Mail;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Input;

class LlenarCedulaPagos extends Command
{
    /**
     * The name and signature of the console command >>> se llena el campo cedula_propietario en tabla properties_facturas.
     *
     * @var string
     */
    protected $signature = 'email:llenarcedulapagos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Llenar Cedula Pagos en tabla properties_pagos';

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
        $pagos = PropertiesPagos::all();
        //$facturas = PropertiesFacturas::where('id_estado','1')->get();

       // $idempresa=$usuario->idEmpresa;
       // $usuarios=User::where('idEmpresa','=',$idempresa);

        foreach ($pagos as $pago) {
          if(empty($pago->cedula_propietario) || $pago->cedula_propietario==1 )
          {
            $property_id=$pago->id_property;
            $propietario=PropertiesPropietarios::where('id_property',$property_id)->first();
            $pago = PropertiesPagos::findOrFail($pago->id);
            $pago->cedula_propietario = $propietario->cedula;
            $pago->save();
          }
            }
    	//Mostrando el resultado
    	//$this->info('Recordatorio enviado!');
    }

}
