@extends('layouts.app')

@section('style')
<style>
	.form-general{
		width:90%;
		border:#B7CBFB 1px solid;
		margin:auto;
		border-radius:5px;
	}
	.form-host-row{
		margin:5px;
	}
	.form-host-col-submit{
		margin-top:31px;
	}
	.form-select-row{
		margin: 13px auto auto auto;
	}
	.tabla-resultados{
		max-width:90%;
		max-height:550px;
		margin:5px auto;
		white-space:nowrap;
	}
	.cartel-host{
		max-width:50%;
		margin:auto;
		margin-bottom:2px;
		padding:2px;
		border:#1F5B20 1px solid;
		border-radius:3px;
        max-height: 50px;
	}
	.cartel-error{
		max-width:50%;
		margin:auto;
		margin-bottom:5px;
		padding:1px;
	}
	.div-paginacion{
		min-width: width auto;
		margin-top:10px auto;
	}
	.borde{
		border:#888888 solid 1px;
	}
    .borde-top{
		border:#888888 solid 1px;
        border-top: none;
        border-radius: 0px 0px 5px 5px;
	}
    .borde-bottom{
		border:#888888 solid 1px;
        border-bottom: none;
        border-radius: 5px 5px 0px 0px;
	}
    .borde-top-bottom{
		border:#888888 solid 1px;
        border-radius: 5px 5px 5px 5px;
	}
    .table-size{
        max-width: 1000px;
    }
</style>
@endsection

@section('content')

@if(session()->get('mensaje_error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="max-width:600px;margin:5px auto 10px auto" align="center">
      <strong>{{ session()->get('mensaje_error') }}</strong>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
@endif

@if(session()->get('ok'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="max-width:600px;margin:5px auto 10px auto" align="center">
      <strong>{{ session()->get('ok') }}</strong>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
@endif

@if(isset($db_host) && isset($db_usuario))

	<div class="alert-success cartel-host" align="center">
        <div class="row">
            <div class="col-7 mt-1" align="right">
                <h5><b><small>Host:</small>{{$db_host}} <small>Usuario:</small>{{$db_usuario}}</b></h5>
            </div>
            <div class="col" align="left">
                <a class="btn btn-sm btn-info" href="{{ url('/') }}">Volver a seleccionar todo</a>
            </div>
        </div>
    </div>

@endif

@if(session()->get('buscador_string_view'))

    @include('forms.form_busqueda_string')
    @if(isset($resultados))
        @include('tablas.tabla_resultados_buscador_string')
    @endif
    <div class="container" style="margin-top: 20px; text-align:center">
        <a class="btn btn-sm btn-info" href="{{ url('/') }}">Volver a seleccionar todo</a>
    </div>

@else


    @if(isset($bases))

        @include('forms.form_database')

    @elseif(isset($database) && !isset($schema))

        @include('forms.form_schema')

    @elseif(isset($schema))

        @include('forms.form_tabla')

        @if(isset($registros))

            @include('tablas.tabla_registros')
            @include('modals.modal_create')
            @include('modals.modal_delete')
            @include('modals.modal_edit')

        @endif

    @else

        @include('forms.form_host')

    @endif

@endif

@endsection
