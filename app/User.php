<?php
namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Notifications\ResetPassword;
use Hash;
use App\TenantProrrogaContrato;
use App\PropertySub;
use App\PropertiesFacturas;
use DB;
use App\User;
use Carbon\Carbon;

/**
 * Class User
 *
 * @package App
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
*/
class User extends Authenticatable
{
    use SoftDeletes, Notifiable;
    //use Notifiable;
    

    protected $fillable = ['name', 'email', 'phone','cedula','referencias','codeudor','tel_codeudor','email_codeudor','cc_codeudor','dir_codeudor','ref_codeudor','password', 'remember_token', 'invitation_token', 'property_id','property_sub_id','role_id','fecha_inicio_contrato','fecha_fin_contrato','estado','duracion_meses','duracion_contrato','created_at','estado','deleted_at'];
    
    protected $dates = [
        'fecha_inicio_contrato',
        'deleted_at',
        'fecha_fin_contrato',
       // 'created_at',
    ];
    
    
    /**
     * Hash password
     * @param $input
     */
    public function setPasswordAttribute($input)
    {
        if ($input)
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
    }
    
    
    public function role()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }
    
    public function topics() {
        return $this->hasMany(MessengerTopic::class, 'receiver_id')->orWhere('sender_id', $this->id);
    }

    public function inbox()
    {
        return $this->hasMany(MessengerTopic::class, 'receiver_id');
    }

    public function outbox()
    {
        return $this->hasMany(MessengerTopic::class, 'sender_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function subproperty()
    {
        //return $this->belongsTo(PropertySub::class);
        return $this->belongsTo('App\PropertySub', 'property_sub_id', 'id');
       // $resul=DB::table('properties_sub')->where('id','=',$id)->sum('valor_neto');
       // return ($resul);
    }

     public function unidad()
    {
        //return $this->belongsTo(PropertySub::class);
     return $this->belongsTo('App\PropertySub', 'id_tenant', 'id');
    }

     public function unidad2($id)
    {
       $unidad=PropertySub::find($id);
       return($unidad);
    }

      public function seguro_tenant($id)
    {
       $tenant=User::findOrFail($id);
       $id_unidad=$tenant->property_sub_id;
       $unidad=PropertySub::findOrFail($id_unidad);//->where('deleted_at',NULL);
       $seguro_unidad=PropertiesSeguros::where('id_property_sub',$unidad->id)
       ->pluck('estado')
       ->first();
       return($seguro_unidad);
    }

     public function sub()
      {
        return $this->hasOne('App\PropertySub', 'id', 'property_sub_id');
      }

       public function sub_2($id)
      {
         $resul=DB::table('properties_sub')->where('id','=',$id);
        return ($resul);
      }

       public function unidad_2_tenant($id)
      {
         $tenant=User::findOrFail($id);
         $resul=DB::table('properties_sub')->where('id','=',$tenant->property_sub_id);
        return ($resul);
      }
    
    public function facturas()
    {
        return $this->hasMany(PropertiesFacturas::class, 'id_tenant');
    }

        public function factura()
    {
        return $this->belongsTo('App\PropertiesFacturas', 'id', 'id_tenant');
    }

          public function valor_pagado_periodo($id, $fecha_inicio_informe, $fecha_fin_informe)
    {
        $tenant=User::find($id);
        $id_unidad=$tenant->property_sub_id;    
        $resul=DB::table('properties_pagos')->where('id_tenant','=',$tenant->id)->where('fecha_pago','>=',$fecha_inicio_informe)->where('fecha_pago','<=',$fecha_fin_informe)->where('deleted_at','=',NULL)->sum('valor');
         return ($resul);
    }

    public function facturas2($id)
    {
        //return $this->hasMany(PropertiesFacturas::class,'$id');
        $resul=DB::table('properties_facturas')->where('id_tenant','=',$id)->where('deleted_at','=',NULL)->sum('valor_neto');
        return ($resul);
    }

       public function facturasvencidas($id)
    {
        $facturasvencidas=DB::table('properties_facturas')->where('id_tenant','=',$id)->where('id_estado','=','1')->where('deleted_at','=',NULL)->sum('valor_neto');
        return ($facturasvencidas);
    }

       public function valorporpagar($id)
       {

        $facturacion=DB::table('properties_facturas')->where('id_tenant','=',$id)->where('deleted_at','=',NULL)->sum('valor_neto');
        $totalpagos=DB::table('properties_pagos')->where('id_tenant','=',$id)->sum('valor');
        $valorporpagar=$facturacion-$totalpagos;
         return ($valorporpagar);
       }

    public function facturas_pagadas($id)
    {
        //return $this->hasMany(PropertiesFacturas::class,'$id');
        $resul=DB::table('properties_pagos')->where('id_tenant','=',$id)->sum('valor');
        return ($resul);
    }

    public function sendPasswordResetNotification($token)
    {
       $this->notify(new ResetPassword($token));
    }

       public function valor_adeudado($id)
    {
     $fecha=date('Y-m-d');
   
     $id_inquilino=$id;
    
     $tenant=DB::table('users')->where('id','=',$id_inquilino)->get()->first();
     $id_tenant=$tenant->id;
     $valor_neto_vencido=DB::table('properties_facturas')->where('deleted_at','=', NULL)->where('id_tenant','=',$id_inquilino)->where('id_estado','=',1)->sum('valor_neto');  
     return ($valor_neto_vencido);
    }

      public function valor_total_comision_periodo($id, $fecha_inicio_informe, $fecha_fin_informe)
    {
        $admin=User::find($id);
        $id_propiedades=Property::where('user_id',$id)->where('deleted_at',NULL)->get();

        if(isset($id_propiedades)){

             $subtotal_ingresos_colhouse=0;

             foreach($id_propiedades as $resul){
                $facturas_pagadas=PropertiesPagos::where('id_property',$resul->id)->where('fecha_pago','>=',$fecha_inicio_informe)->where('fecha_pago','<=',$fecha_fin_informe)->where('deleted_at',NULL)->sum('valor');
                $subtotal_ingresos_colhouse+=($resul->propietarios2($resul->id)->porc_comision/100)*$facturas_pagadas;  }

         return ($subtotal_ingresos_colhouse);
        }
        else
        {
          return "sin definir";
        }
    }

      public function valor_total_comision_ingresos($id)
    {
        $admin=User::find($id);
        $id_propiedades=Property::where('user_id',$id)->where('deleted_at',NULL)->get();

        if(isset($id_propiedades)){

             $subtotal_ingresos_colhouse=0;

             foreach($id_propiedades as $resul){
                $facturas_pagadas=PropertiesPagos::where('id_property',$resul->id)->where('deleted_at',NULL)->sum('valor');

                $subtotal_ingresos_colhouse+=($resul->propietarios2($resul->id)->porc_comision/100)*$facturas_pagadas;
                //$subtotal_ingresos_colhouse+=$facturas_pagadas;
                //$subtotal_ingresos_colhouse+=($resul->propietarios2($resul->id)->porc_comision/100)*$facturas_pagadas;
            }

         return ($subtotal_ingresos_colhouse);
        }
        else
        {
          return "sin definir";
        }
    }

         public function valor_total_facturado_inmobiliaria($id)
    {
        $admin=User::find($id);
        $id_propiedades=Property::where('user_id',$id)->where('deleted_at',NULL)->get();

        if(isset($id_propiedades)){

             $subtotal_valor_total_facturas=0;

             foreach($id_propiedades as $resul){
                /*Facturas valor_total*/
                $facturas_valor_total=PropertiesFacturas::where('id_property',$resul->id)->where('deleted_at',NULL)->sum('valor_neto');
                $subtotal_valor_total_facturas+=$facturas_valor_total;
                /*Facturas valor_total*/
            }

         return ($subtotal_valor_total_facturas);
        }
        else
        {
          return "sin definir";
        }
    }

      public function valor_total_ingresos_inmobiliaria($id)
    {
        $admin=User::find($id);
        $id_propiedades=Property::where('user_id',$id)->where('deleted_at',NULL)->get();

        if(isset($id_propiedades)){

             $subtotal_ingresos_colhouse=0;

             foreach($id_propiedades as $resul){
                /*Facturas pagadas*/
                $facturas_pagadas=PropertiesPagos::where('id_property',$resul->id)->where('deleted_at',NULL)->sum('valor');
                $subtotal_ingresos_colhouse+=$facturas_pagadas;
                /*Facturas pagadas*/

            }

         return ($subtotal_ingresos_colhouse);
        }
        else
        {
          return "sin definir";
        }
    }

   

    public function prorroga_contrato($id)
    {
     $tenant=User::find($id);
     $prorroga=DB::table('tenant_contrato_prorroga')->where('id_tenant','=',$tenant->id)->where('deleted_at','=', NULL)->first();
     return ($prorroga);
    }

         public function valor_total_cartera($id)
    {
        $admin=User::find($id);
        $id_propiedades=Property::where('user_id',$id)->where('deleted_at',NULL)->get();

        if(isset($id_propiedades)){

             $subtotal_ingresos_colhouse=0;
             $subtotal_valor_total_facturas=0;
             $total_cartera=0;

             foreach($id_propiedades as $resul){
                /*Facturas pagadas*/
                $facturas_pagadas=PropertiesPagos::where('id_property',$resul->id)->where('deleted_at',NULL)->sum('valor');
                $subtotal_ingresos_colhouse+=$facturas_pagadas;
                /*Facturas pagadas*/

                /*Facturas valor_total*/
                $facturas_valor_total=PropertiesFacturas::where('id_property',$resul->id)->where('deleted_at',NULL)->sum('valor_neto');
                $subtotal_valor_total_facturas+=$facturas_valor_total;
                /*Facturas valor_total*/

                /*TotalCartera*/
                $total_cartera=$subtotal_valor_total_facturas - $subtotal_ingresos_colhouse;
                /*TotalCartera*/

                                            }

                return ($total_cartera);
            }
        else
        {
          return "sin definir";
        }
    }

    /*public function fechafincontrato_fechaactual($id)
    {
     $tenant=User::find($id);
     $fechafincontrato=$tenant->fecha_fin_contrato;
     $fechaendcontrato=date('Y-m-d',strtotime($fechafincontrato));
     $fechaactual=date('Y-m-d');

     $renovacion == 0;

     if ($fechaendcontrato == $fechaactual)
     {
        $renovacion == 1;
     }

     return ($renovacion);
    }*/

     public function fechafincontrato_fechaactual($id)
    {
        $desde = date('Y-m-d');
        $hasta = date('Y-m-d');
        return $this->hasMany('App\User', 'id_usuario', 'id')->whereBetween('fecha_fin_contrato',array($desde,$hasta));
    }

    public function unidad_tenant($id)
    {
        $tenant=User::findOrFail($id);
        $id_unidad=$tenant->property_sub_id;
        $unidad=PropertySub::findOrFail($id_unidad);
        return ($unidad);
    }


}
