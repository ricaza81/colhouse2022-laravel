@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')
@section('content')
    <div class="panel panel-default" style="background:#cbe5fe;border:4px solid #563e7c">
    <h3 class="page-title" style="padding:20px">Informe Consolidado por Propietario</h3>
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
            <div class="row">
                    <div class="col-md-4">
                        Total propietarios únicos:<br/>
                        <span>{{$propietarios_unicos_total}}</span><br/>
                        Total propietarios:<br/>
                        <span>{{$propietarios->count()}}</span>
                    </div>
                    <div class="col-md-4">
                        Propietarios de varias propiedades:<br/>
                        @if ($propietarios_multi > 0)
                        <span>{{$propietarios_multi}}</span>
                        @endif   
                    </div>
            </div>
        </div>
        <div class="panel-body table-responsive">
            <table class="display table table-hover">
                <thead>
                    <tr>
                        <th>Propietario</th>
                        <th>Propiedades</th>
                        <th>Cedula</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Direccion Propiedad</th>
                        <th>% Administracion</th>
                    </tr>
                </thead>                
                <tbody>                  
                        @foreach ($propietarios as $propietario)
                            <tr>
                                <td>
                                     <span>{{$propietario->nombre}}, </span>
                                      @foreach ($propietario->propiedades_propietario($propietario->id) as $propiedad)
                                     <span>{{$propiedad->name}}</span>
                                     <br/>
                                     <a href="{{ route('admin.tenants.informe_inquilinos_consulta_propietario',[$propietario->cedula]) }}" class="btn btn-primary" target="_blank">
                                        Ver informe consolidado propietario
                                    </a>
                                    @endforeach
                                </td>                              
                                <td>                                    
                                    @foreach($propietario->propiedades_propietario_cedula($propietario->cedula) as $propiedad)
                                 
                                            <ul class="list-unstyled">
                                                {{$propiedad->name}}
                                            </ul>
                                 
                                    @endforeach
                                </td>

                                <td>
                                    {{$propietario->cedula}}
                                </td>

                                <td>
                                    {{$propietario->phone}}
                                </td>

                                <td>
                                    {{$propietario->email}}
                                </td>

                                <td>
                                    <span>{{$propiedad->address}}</span>
                                </td>

                                <td>
                                    {{$propietario->porc_comision}}
                                </td>
                            </tr>                        
                        @endforeach                 
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