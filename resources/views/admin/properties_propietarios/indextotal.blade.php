@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <div class="panel panel-default" style="background:#fff1cd;border:4px solid #563e7c">
    <h3 class="page-title" style="padding:20px">Informe Consolidado por Propiedad</h3>
    </div>
    @can('document_create')
    <p>
       <!-- <a href="{{ route('admin.documents.create') }}" class="btn btn-success">@lang('global.app_add_new')</a>-->
        
    </p>
    @endcan

    <p>
        <ul class="list-inline">
            <li><a href="{{ route('admin.properties_propietarios.indextotal')}}" style="{{ request('show_deleted') == 1 ? '' : 'font-weight: 700' }}">

            @lang('global.app_all')</a></li> |
           <!-- <li><a href="{{ route('admin.properties_propietarios.indextotal')}}?show_deleted=1" style="{{ request('show_deleted') == 1 ? 'font-weight: 700' : '' }}">@lang('global.app_trash')</a></li>-->
        </ul>
    </p>
    

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="display table table-hover {{ count($property) > 0 ? 'datatable' : '' }} @can('property_delete') @if ( request('show_deleted') != 1 ) display @endif @endcan">
                <thead>
                    <tr>
                       <!-- @can('properties_facturas_delete')
                            @if ( request('show_deleted') != 1 )<th style="text-align:center;"><input type="checkbox" id="select-all" /></th>@endif
                        @endcan-->

                        <th>Propietario</th>
                        <th>Cedula</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Direccion</th>
                        <th>% Administracion</th>
                      
                       <!-- @if( request('show_deleted') == 1 )
                        <th>&nbsp;</th>
                        @else
                        <th>&nbsp;</th>
                        @endif-->
                    </tr>
                </thead>
                
                <tbody>
                    @if (isset($property))
                        @foreach ($property as $propiedad)
                            <!--<tr data-entry-id="{{ $propiedad->id }}">
                                @can('properties_facturas_delete')
                                    @if ( request('show_deleted') != 1 )
                                    <td></td>@endif
                                @endcan-->
                            <tr>
                                <td style="font-size:20px;font-weight:700"> {{$propiedad->propietarios2($propiedad->id)->nombre}}<br/>
                                <span style="font-size:16px;font-weight:500">Propiedad Principal: {{$propiedad->name or ''}}
                                </span>
                                <br/>
                                <a href="{{ route('admin.tenants.informe_inquilinos_consulta',[$propiedad->id]) }}" class="btn btn-primary" target="_blank">Ver informe
                                </a>
                                </td>
                                <td>
                                    {{$propiedad->propietarios2($propiedad->id)->cedula}}
                                </td>
                                <td>
                                    {{$propiedad->propietarios2($propiedad->id)->phone}}
                                </td>
                                <td>
                                    {{$propiedad->propietarios2($propiedad->id)->email}}
                                </td>
                                <td>
                                    {{$propiedad->propietarios2($propiedad->id)->direccion}}
                                </td>
                                <td>
                                    {{$propiedad->propietarios2($propiedad->id)->porc_comision}}
                                </td>
                                <!-- <td>
                                <a href="{{ route('admin.properties_propietarios.show',[$propiedad->propietarios2($propiedad->id)->id]) }}" class="btn btn-primary" target="_blank">Ver propietario
                                </a>
                                </td>-->
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
        @can('document_delete')
            @if ( request('show_deleted') != 1 ) window.route_mass_crud_entries_destroy = '{{ route('admin.documents.mass_destroy') }}'; @endif
        @endcan

    </script>
@endsection