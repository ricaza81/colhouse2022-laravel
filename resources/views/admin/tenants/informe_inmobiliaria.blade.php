@extends('layouts.app')

@section('content')
    <h3 class="page-title">Informe Inmobiliaria</h3>
    @can('property_create')
    <p>
     <!--   <a href="{{ route('admin.tenants.create') }}" class="btn btn-success">@lang('global.app_add_new')</a>-->
        
    </p>
    @endcan

     <p>
        <ul class="list-inline">
           

         <!--   <li><a href="{{ route('admin.tenants.index') }}" style="{{ request('show_deleted') == 1 ? '' : 'font-weight: 700' }}">@lang('global.app_all')</a></li> |
           
            <li><a href="{{ route('admin.tenants.index') }}?show_deleted=1" style="{{ request('show_deleted') == 1 ? 'font-weight: 700' : '' }}">@lang('global.app_trash')</a></li>-->

        </ul>
    </p>

    <div class="panel panel-default">
       

        <div class="row">
             <div class="col-md-1"></div>
         
        <div class="col-md-10">
            @if ($msj != NULL)
            <div class="alert alert-success" style="color:#008d4c;margin-top:20px"> {{$msj}}</div>
           
            @endif
             <div class="panel-heading" style="margin-top: 31px;">
            <h4 class="page-title" style="font-weight:700;letter-spacing:-1px;"><b>Generar Informe</b></h4>
        </div>

           <div class="col-md-3 form-group">
          
                   
                      {!! Form::open(['method' => 'POST', 'route' => ['admin.tenants.informe_inmobiliaria_consulta_fechas']]) !!}
                   
                   
                        {!! Form::label('fecha_inicio_informe', 'Desde *', ['class' => 'control-label']) !!}
                                        {!! Form::date('fecha_inicio_informe', old('fecha_inicio_informe'), ['class' => 'form-control', 'placeholder' => 'Fecha inicio', 'required' => '']) !!}
                                        <p class="help-block"></p>
                                        @if($errors->has('fecha_inicio_informe'))
                                            <p class="help-block">
                                                {{ $errors->first('fecha_inicio_informe') }}
                                            </p>
                                        @endif
                  </div>
                  <div class="col-md-3 form-group">
                          {!! Form::label('fecha_fin_informe', 'Hasta *', ['class' => 'control-label']) !!}
                        {!! Form::date('fecha_fin_informe', old('fecha_fin_informe'), ['class' => 'form-control', 'placeholder' => 'Fecha final informe', 'required' => '']) !!}
                        <p class="help-block"></p>
                            @if($errors->has('fecha_fin_informe'))
                            <p class="help-block">
                            {{ $errors->first('fecha_fin_informe') }}
                            </p>
                            @endif

                       
                    </div>
                     <div class="col-md-3 form-group" style="margin-top: 7px;"><br/>
                      {!! Form::submit(trans('Consultar'), ['class' => 'btn btn-success']) !!}
                    {!! Form::close() !!}
                    </div>
            </div>
        </div>
                
    <div class="row">
         <div class="col-md-1"></div>

        <div class="col-md-10">
            <div class="title" style="color:#000">
                @if ($fecha_inicio_informe != NULL)
                <h3>Período consultado: {{date('d/F/Y', strtotime($fecha_inicio_informe)) }}- {{date('d/F/Y', strtotime($fecha_fin_informe))}}</h3>
                @else
                <h1>Por favor selecciona un período</h1>
                @endif

           <div class="col-md-7" style="background:#fff;">
            Consolidado
            @if($fecha_inicio_informe != NULL)
             <h4>Período consultado: {{date('d/F/Y', strtotime($fecha_inicio_informe)) }}- {{date('d/F/Y', strtotime($fecha_fin_informe))}}</h4>
            @else
            <h4>Por favor selecciona un período</h4>
            @endif
        </div>
      
        <div class="panel-body table-responsive" style="background:#fff;">
            <div class="row">
              <table class="table table-bordered table-striped">
        <thead>
             <tr>
                <th>Ingresos por arrendamiento</th>
                 <td>${{number_format($ingresos)}}
                </td>
            </tr>
            <tr>
                <th>Ingresos Netos ColHouse</th>
                 <td>${{ number_format($usuarioadmin->valor_total_comision_periodo($usuarioadmin->id, $fecha_inicio_informe, $fecha_fin_informe)), 0, ',', '.' }}
                </td>
            </tr>
    </thead>
    </table>
     @if ($fecha_inicio_informe != NULL)
               

                  {!! Form::open(['method' => 'POST', 'route' => ['admin.tenants.imprimir_informe']
                  ]) !!}
                   
                   
                     <input type="hidden" name="fecha_inicio_informe" value="<?=$fecha_inicio_informe;?>" id="fecha_inicio_informe">
                       <input type="hidden" name="fecha_fin_informe" value="<?=$fecha_fin_informe;?>" id="fecha_fin_informe">
                   
                <!-- 
                   {!! Form::submit(trans('Descargar PDF'), [
                   'class' => 'btn btn-success',
                   'style' => 'margin-top: 20px;'
                   ]) !!}
                    {!! Form::close() !!}-->
                @else
                <h4></h4>
                @endif
           @if ($fecha_inicio_informe != NULL)
               

                  {!! Form::open(['method' => 'POST', 'route' => ['admin.tenants.crearEXCELview']
                  ]) !!}
                   
                    
                     <input type="hidden" name="fecha_inicio_informe" value="<?=$fecha_inicio_informe;?>" id="fecha_inicio_informe">
                       <input type="hidden" name="fecha_fin_informe" value="<?=$fecha_fin_informe;?>" id="fecha_fin_informe">
                 
               <!--    {!! Form::submit(trans('Descargar Excel'), [
                   'class' => 'btn btn-success',
                   'style' => 'margin-top: 20px;margin-left: 39px;'
                   ]) !!}
                    {!! Form::close() !!}-->
                @else
                <h4></h4>
                @endif

         @if ($fecha_inicio_informe != NULL)
               

                  {!! Form::open(['method' => 'POST', 'route' => ['admin.tenants.enviar_informe']
                  ]) !!}
                   
                 
                     <input type="hidden" name="fecha_inicio_informe" value="<?=$fecha_inicio_informe;?>" id="fecha_inicio_informe">
                     <input type="hidden" name="fecha_fin_informe" value="<?=$fecha_fin_informe;?>" id="fecha_fin_informe">

                  
                 
                 
                    {!! Form::close() !!}
                @else
                <h4></h4>
                @endif

</div>
</div>


  <!--Ingresos por propiedad global-->

<div class="row">
    <div class="col-md-12">

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($propiedad) > 0 ? 'datatable' : '' }}">
                <thead>
                    <tr>
                        <th>@lang('global.tenants.fields.property')</th>
                        <th>Direccion</th>
                        <th>Propietario</th>
                        <th>Ingresos arrendamiento</th>
                        <th>% Administración</th>
                        <th>Ingresos ColHouse</th>
                    </tr>
                </thead>
                
                <tbody>
                    @if (count($propiedad) > 0)
                        @foreach ($propiedad as $propiedad)
                        
                            <tr data-entry-id="{{ $propiedad->id }}">
                            <td field-key='property'>{{ $propiedad->name or '' }}
                            </td>
                            <td field-key='property'>{{ $propiedad->address or '' }}
                            </td>
                            <td field-key='propietario'>
                                      <a href="{{ route('admin.properties_propietarios.index',[$propiedad->id])}}" data-toggle="tooltip" title="Consultar" style="color:#000" target="_blank">  
                                        {{ $propiedad->propietarios2($propiedad->id)->nombre }}
                                      </a>
                                      <br/>
                                    
                            </td>
                            
                               <td>
                                ${{ number_format($propiedad->facturas_pagadas_propiedad_fechas($propiedad->id,$fecha_inicio_informe,$fecha_fin_informe)), 0, ',', '.' }}
                               </td>

                                <td>
                                @if(isset($propiedad->propietarios2($propiedad->id)->porc_comision))
                                {{ $propiedad->propietarios2($propiedad->id)->porc_comision }}%
                                @endif
                               </td>

                                <td>
                                @if(isset($propiedad->propietarios2($propiedad->id)->porc_comision))
                                ${{ number_format($propiedad->facturas_pagadas_propiedad_fechas($propiedad->id,$fecha_inicio_informe,$fecha_fin_informe)*$propiedad->propietarios2($propiedad->id)->porc_comision/100), 0, ',', '.' }}
                                @endif
                               </td>
                            

                              
                            </tr>
                       
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8">@lang('global.app_no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>
            </table>
             </div>
            </div>
            </div>
 <!--Ingresos por propiedad global--> 
        


<!--
        <div class="col-md-5" style="background:#fff;">
            Deducciones
             @if ($fecha_inicio_informe != NULL)
                <h5>Período consultado: {{date('d/F/Y', strtotime($fecha_inicio_informe)) }}- {{date('d/F/Y', strtotime($fecha_fin_informe))}}</h5>
                @else
                <h4>Por favor selecciona un período</h4>
                @endif
           <div class="panel-body table-responsive" style="background:#fff;">
            <div class="row">
         

              <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                          <th>Valor</th>
                          <th>Fecha</th>
                    </tr>
                </thead>

              <tbody>
               @if (isset($deducciones_detalles))
                        @foreach ($deducciones_detalles as $deduccion)
                          <tr data-entry-id="{{ $deduccion->id }}">
                             <td field-key='name'>${{number_format($deduccion->valor)}}<br/>
                                {{$deduccion->observaciones}}
                             </td>
                              <td field-key='name'>{{date('d/F/Y', strtotime($deduccion->fecha_deduccion))}}</td>
                            
                          </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="8">@lang('global.app_no_entries_in_table')</td>
                        </tr>
                    @endif
             </tbody>
             </table>
        </div>
    </div>
</div>
-->  

     
</div>
</div>

        
            
           
           
       
@stop

<style>
.m-status {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    padding: 7px 12px;
    font-weight: 600;
    font-size: 13px;
    line-height: 1;
    font-family: proxima-nova,Avenir,sans-serif;
    text-align: center;
    color: #fff;
    text-transform: lowercase;
    font-style: normal;
    letter-spacing: 0;
    white-space: nowrap;
    background-color: #627d98;
    border-radius: 100px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    color: #b44d12;
    background-color: #fff3c4;
}
</style>

@section('javascript') 
  <!--  <script>
        @can('property_delete')
            @if ( request('show_deleted') != 1 ) window.route_mass_crud_entries_destroy = '{{ route('admin.tenants.mass_destroy') }}'; @endif
        @endcan

    </script> -->
@endsection

