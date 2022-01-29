<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Property;
use App\PropertiesFacturas;
use App\PropertiesSeguros;
use Hash;
use DB;

/**
 * Class Role
 *
 * @package App
 * @property string $estado
*/
class PropertiesPropietarios extends Model
{

    use SoftDeletes;

    protected $table = 'properties_propietarios';

    protected $fillable = ['id_property','nombre','cedula','phone','email','direccion','porc_comision','observaciones','cedula'];

    public function factura()
      {       
        return $this->belongsTo('App\PropertiesFacturas', 'id_factura', 'id');    
      }

    public function propiedad()
      {       
        return $this->belongsTo('App\Property', 'id_property', 'id');    
      }

     public function unidad_seguro()
      {       
        return $this->belongsTo('App\PropertySub', 'id_property_sub', 'id');    
      }

     public function tenant()
      {       
        return $this->belongsTo('App\User', 'id_tenant', 'id');    
      }

        public function seguros_unidad()
      {
       
      
      return $this->belongsTo('App\PropertiesSeguros', 'id', 'id_property');

      }

      public function propiedad2()
      {
       return $this->hasOne('App\PropertiesPropietarios', 'id_property', 'id');
   
      }

      public function propiedad3()
    {
        return $this->belongsToMany(Properties::class, 'id_property');
    }

       public function propiedad_unica($id)
      {
       
        $id_propietario=$id;
        $propietario=PropertiesPropietarios::findOrFail($id_propietario);
        $resul=DB::table('properties')->where('deleted_at',NULL)->where('id','=',$propietario->id_property)->first();
        return ($resul);
      
      }

        public function valor_deducciones($id)
  
{
     $fecha=date('Y-m-d');
     $propiedad=DB::table('properties')->where('id_property','=',$id)->get()->first();
   
     $id_propietario=DB::table('properties_propietarios')->where('id_property','=',$propiedad->id)->get()->first();
    
     //$tenant=DB::table('users')->where('id','=',$id_inquilino)->get()->first();
     //$id_tenant=$tenant->id;
     $valor_deducciones=DB::table('properties_propietarios_deducciones')->where('deleted_at','=', NULL)->where('id_propietario','=',$id_propietario->id)->sum('valor');  
     return ($valor_deducciones);
}
    public function numero_propiedades_propietario($id)
      {
       
        $id_propietario=$id;
        $propietario=PropertiesPropietarios::findOrFail($id_propietario);
        $cc_propietario=$propietario->cedula;
        $resul=DB::table('properties_propietarios')->where('cedula',$cc_propietario)->where('deleted_at',NULL)->get();
        return count($resul);
      
      }

       public function propiedades_propietario($id)
      {
       
        $id_propietario=$id;
        $propietario=PropertiesPropietarios::findOrFail($id_propietario);
        $resul=DB::table('properties')->where('id',$propietario->id_property)->where('deleted_at',NULL)->get();//
        //$resul=DB::table('properties')->where('id',$propietario->id_property)->where('deleted_at',NULL)->groupBy('name')->get();
        return ($resul);
      
      }

        public function propiedades_propietario_cedula($id)
      {
       
        $cedula_propietario=$id;
        $resul=DB::table('properties')->where('cedula_propietario',$cedula_propietario)->where('deleted_at',NULL)->get();
        return ($resul);
      
      }

       public function detalles_propietario($id)
      {
         //$propietario=PropertiesPropietarios::findOrFail($id);
         $nombre_propietario=DB::table('properties_propietarios')->where('id','=',$id)->get()->first();
          return ($nombre_propietario);

      }

         public function inquilinos_propietario($id)
      {
        $id_propietario=$id;
        $propietario=PropertiesPropietarios::findOrFail($id_propietario);
        $resul=DB::table('users')->where('deleted_at',NULL)->where('property_id','=',$propietario->id_property)->where('role_id',3)->get();
        return ($resul);      
      }

        public function id_inquilinos_propietario($id)
      {
        $id_propietario=$id;
        $propietario=PropertiesPropietarios::findOrFail($id_propietario);
        $cedula=$propietario->cedula;
        $tenants=DB::table('users')->where('deleted_at',NULL)->where('property_id','=',$propietario->id_property)->where('role_id',3)->pluck('id');
        //return ($tenants);
        $facturas=DB::table('properties_facturas')->where('id_property','=',$propietario->id_property)->where('fecha_inicio','>=','2021-12-01')->where('id_estado','<>',2)->where('deleted_at','=',NULL)->get();
        $pagos=DB::table('properties_pagos')->where('id_property','=',$propietario->id_property)->where('fecha_pago','>=','2021-12-01')->where('deleted_at','=',NULL)->get();
        //dd($facturas);

        foreach ($pagos as $pago) {
          $tenant=$pago->id_tenant;
          $id_property=$pago->id_property;
          $propiedad=Property::findOrFail($id_property);
          //dd($propiedad);
          $propietario=PropertiesPropietarios::where('id_property',$propiedad->id)->first()->pluck('cedula');
          //dd($propietario);
          $propietarios=PropertiesPropietarios::where('cedula',$propietario)->get();

          //$facturas=DB::table('properties_facturas')->where('id_tenant','=',$tenant)->where('deleted_at','=',NULL)->pluck('id');
          //dd($facturas);
            //foreach ($facturas as $factura) {
             // $tenant=$factura;
          //    dd($factura);
          //     return ($tenant);
          //return ($facturas);
          // return ($propietarios);
            }
          //dd($facturas);
        //$totalpagos=DB::table('properties_pagos')->where('id_tenant','=',$resul)->sum('valor');*/
        //$tenant=$facturacion-$totalpagos;
         //return ($tenants);
        //return ($facturas);
        //return ($pagos);
        //return ($propietarios);
        //}

        // dd($facturas);
        //return ($tenants);
        //return ($facturas);
        return ($cedula);

      }

        public function inquilinos_propietario_valor_pagado_periodo($id, $fecha_inicio_informe, $fecha_fin_informe)
      {
        $id_propietario=$id;
        $propietario=PropertiesPropietarios::findOrFail($id_propietario);
        $tenants=DB::table('users')->where('deleted_at',NULL)->where('property_id','=',$propietario->id_property)->where('role_id',3)->get();       
        return($tenants);
        }
        
       /*foreach ($tenants as $tenant)
       {
        $tenant=User::find($tenant->id);
        $id_unidad=$tenant->property_sub_id;
        $resul=DB::table('properties_pagos')->where('id_tenant','=',$tenant->id)->where('fecha_pago','>=',$fecha_inicio_informe)->where('fecha_pago','<=',$fecha_fin_informe)->where('deleted_at','=',NULL)->sum('valor');
        return ($resul);
        
       }
       //return ($resul);*/
   //   }

        public function unidades_propietario($id)
      {
       
        $id_propietario=$id;
        $propietario=PropertiesPropietarios::findOrFail($id_propietario);
        $resul=DB::table('properties')->where('id',$propietario->id_property)->where('deleted_at',NULL)->get();
       $unidades=DB::table('properties_sub')->where('id_property',$propietario->id_property)->where('deleted_at',NULL)->get();
       $unidades_ocupadas=$unidades;
        return ($unidades_ocupadas);
      
      }

    /*     public function unidades_propietario_aseguradas($id)
      {
       
        $id_propietario=$id;
        $propietario=PropertiesPropietarios::findOrFail($id_propietario);
        $resul=DB::table('properties')->where('id',$propietario->id_property)->where('deleted_at',NULL)->get();
       $seguros=DB::table('properties_sub')->where('id_property',$propietario->id_property)->where('deleted_at',NULL)->where('estado_seguro',1)->pluck('id');

      


          return ($seguros);
      }

        public function unidades_propietario_noaseguradas($id)
      {
       
        $id_propietario=$id;
        $propietario=PropertiesPropietarios::findOrFail($id_propietario);
        $resul=DB::table('properties')->where('id',$propietario->id_property)->where('deleted_at',NULL)->get();
       $noseguros=DB::table('properties_seguros')->where('id_property',$propietario->id_property)->where('deleted_at',NULL)->where('estado',2)->pluck('id');

       foreach ($noseguros as $noseguro) {
          //dd($seguro);
       }


          return ($noseguros);
      }


        public function unidades_propietario_vaciasaseguradas($id)
      {
       
        $id_propietario=$id;
        $propietario=PropertiesPropietarios::findOrFail($id_propietario);
        $resul=DB::table('properties')->where('id',$propietario->id_property)->where('deleted_at',NULL)->get();
       $seguros_vacios=DB::table('properties_seguros')->where('id_property', null)->where('deleted_at',NULL)->where('estado',1)->pluck('id');

        foreach ($seguros_vacios as $vacioseguro) {
          //dd($seguro);
       }
   
        return ($seguros_vacios);

      }*/

       public function facturas_pagadas_propietario($id)
    {
        //return $this->hasMany(PropertiesFacturas::class,'$id');
        dd($id);

        $propietarios=PropertiesPropietarios::where('cedula',$id)->pluck('id_property');
        dd($propietarios);
        $resul=DB::table('properties_pagos')->where('id_property','=',$propietarios)->where('deleted_at','=',NULL)->sum('valor');
        return ($resul);
    }

    public function propietario_multi($id) {
       $propietario_multi=DB::table('properties_propietarios')->where('cedula',$id)->get();
       return ($propietario_multi);
    }

    
    
}
