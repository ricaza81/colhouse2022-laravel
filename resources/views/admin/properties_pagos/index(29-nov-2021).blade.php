@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('global.pagos.title')</h3>
    @can('document_create')
    <p>
        <a href="#" class="btn btn-success" data-toggle="tooltip" title="Crealo desde la unidad" style="color:#fff;padding:10px">@lang('global.app_add_new')</a>
        
    </p>
    @endcan

    <p>
        <ul class="list-inline">
          
        </ul>
    </p>
    

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_list')
        </div>

        <div class="panel-body table-responsive">
           <table id="lista_facturas" class="table table-bordered table-striped {{ count($facturas) > 0 ? 'datatable' : '' }} @can('properties_facturas_delete') @if ( request('show_deleted') != 1 ) dt-select @endif @endcan">
                <thead>
                    <tr>
                        @can('properties_facturas_delete')
                            @if ( request('show_deleted') != 1 )<th style="text-align:center;"><input type="checkbox" id="select-all" /></th>@endif
                        @endcan

                        <th># Factura</th>
                        <th>@lang('global.facturas.fields.property')</th>
                        <th>@lang('global.facturas.fields.unidad')</th>
                        <th>@lang('global.facturas.fields.tenant')</th>
                        <th style="text-align:center;">Período:</th>
                        <th>Estado</th>
                        <th style="text-align:center;">Seguro</th>
                        @if( request('show_deleted') == 1 )
                        <th>&nbsp;</th>
                        @else
                        <th>&nbsp;</th>
                        @endif
                    </tr>
                </thead>
                
                <tbody>
                    @if (count($facturas) > 0)
                        @foreach ($facturas as $factura)
                            <tr data-entry-id="{{ $factura->id }}">
                                @can('properties_facturas_delete')
                                    @if ( request('show_deleted') != 1 )<td></td>@endif
                                @endcan

                                <td field-key='user'>COLH-FT-{{ $factura->id or '' }}

                                    @if ($factura->pago2($factura->id)-($factura->valor_neto)!=0)
                                        <a href="{{ route('admin.properties_pagos.create',[$factura->id]) }}" class="btn btn-xs btn-success">Recibir pago</a>
                                         <a href="{{ route('admin.properties_facturas.show',[$factura->id]) }}" class="btn btn-xs btn-info">Ver Factura</a>
                                    @else
                                      <a href="{{ route('admin.properties_facturas.show',[$factura->id]) }}" class="btn btn-xs btn-info">Ver Factura</a>
                                     
@if(isset($factura->pago_valor->id))
  <a href="{{ route('admin.properties_pagos.imprimirpago',[$factura->pago_valor->id])}}" class="btn btn-xs btn-primary" target="_blank" style="margin-left:3px">Ver Pago en PDF</a>
                                </div>
@endif
                                    @endif


                                </td>
                                <td field-key='property'>{{ $factura->property->name or '' }}</td>
                                <td field-key='property_sub'>
                                    <a href="{{route('admin.properties_sub.show',[$factura->property_sub->id])}}">
                                        {{ $factura->property_sub->nombre or '' }}
                                    </a>
                                </td>
                                <td field-key='user'>
                                    @if ($factura->tenant_name($factura->id) != NULL )
                                    <a href="{{route('admin.tenants.show',[$factura->tenant_id($factura->id)])}}" target="_blank"> {{ $factura->tenant_name($factura->id)}}
                                    </a>
                                    @endif
                                </td>
                                <td data-sort="{{strtotime($factura->fecha_inicio)}}" field-key='fecha_inicio'>
                                    Inicio: {{date('d/F/Y', strtotime($factura->fecha_inicio)) }}
                                    Vencimiento: {{ date('d/F/Y', strtotime($factura->fecha_corte))}}
                                    Valor: ${{ number_format($factura->valor_neto), 0, ',', '.' }}
                                      @if ($factura->id_estado=='1')
                                     @if ($factura->fecha_corte < Now())
                                    <br/><span class="label label-danger" style="text-align:center;padding:10px;font-size:12px;color:#ffffff">Vencida
                                    </span>
                                    @endif
                                    @endif
                                    
        </td>
                               
                                <td field-key='name'style="font-size:15px;font-weight: 700">
                                       @if ($factura->pago2($factura->id)-($factura->valor_neto)==0)
                                    <span class="label label-success" style="text-align:center;padding:10px;font-size:15px;color:#ffffff">Al dia
                                    </span>
                                     @if($factura->id_estado=='2')
                                   
                                       @if(isset($factura->pago_valor->id))
                                          <span class="label label-warning" style="text-align:center;padding:10px;font-size:12px">
                                         <a target="_self" href="{{ route('admin.properties_pagos.show',[$factura->pago_valor->id])}}"data-toggle="tooltip" title="Ver Pago" style="color:#fff;padding:0px">
                                            Ver pago
                                        </a><br/>
                                    </span>
                                          <span>
                                          Saldo: ${{ number_format($factura->valor_neto-$factura->pago2($factura->id)), 0, ',', '.' }}
                                        </span>
                                        @endif
                                    </span>
                                    @endif
                                    @endif
                                     @if ($factura->pago2($factura->id)-($factura->valor_neto)!=0)
                                    <span class="label label-danger" style="text-align:center;padding:10px;font-size:15px;color:#ffffff">En mora
                                    </span>
                                     <span>
                                          Saldo: ${{ number_format($factura->valor_neto-$factura->pago2($factura->id)), 0, ',', '.' }}
                                        </span>
                                    @endif
                                </td>
                                 <td field-key='name'style="font-size:15px;font-weight: 700">
                                       @if($factura->property_sub->numero_seguros_unidad($factura->id_property_sub) > 0 )
                                           <a href="{{ route('admin.properties_sub.show',[$factura->property_sub->id]) }}">
                                            <span class="m-status" style="background:#5cb85c;color:#fff;float:left;">
                                            Si
                                            </span>
                                            </a>
                                         @else
                                            <a href="{{ route('admin.properties_sub.show',[$factura->property_sub->id]) }}">
                                            <span class="m-status" style="background:#d7564a;color:#fff;float:left;">
                                            No
                                            </span>
                                            </a>
@endif

                                 </td>
                                @if( request('show_deleted') == 1 )
                                <td>
                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'POST',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['admin.documents.restore', $document->id])) !!}
                                    {!! Form::submit(trans('global.app_restore'), array('class' => 'btn btn-xs btn-success')) !!}
                                    {!! Form::close() !!}
                                                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['admin.documents.perma_del', $factura->id])) !!}
                                    {!! Form::submit(trans('global.app_permadel'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                                                </td>
                                @else
                                <td>
                                    @can('properties_facturas_edit')
                                    <a target="_self" href="{{ route('admin.properties_facturas.show',[$factura->id]) }}" class="btn btn-xs btn-primary">Ver factura</a>
                                    
                                  
                                    <a target="_self" href="{{ route('admin.properties_facturas.edit',[$factura->id]) }}" class="btn btn-xs btn-info">Editar Factura</a>
                                    @endcan
                                    @can('properties_facturas_delete')
{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['admin.properties_facturas.destroy', $factura->id])) !!}
                                    {!! Form::submit(trans('global.app_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                    @endcan
                                </td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9">@lang('global.app_no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>



@stop

@section('javascript') 
    <script>
        @can('properties_pagos_delete')
            @if ( request('show_deleted') != 1 ) window.route_mass_crud_entries_destroy = '{{ route('admin.properties_pagos.mass_destroy') }}'; @endif
        @endcan

    </script>

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
@endsection