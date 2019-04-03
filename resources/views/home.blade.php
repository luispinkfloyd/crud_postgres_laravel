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
		max-height:350px;
		margin:5px auto;
		white-space:nowrap;
	}
	.cartel-host{
		max-width:50%;
		margin:auto;
		margin-bottom:5px;
		padding:1px;
		border:#1F5B20 1px solid;
		border-radius:3px;
	}
	.div-paginacion{
		min-width:90%;
		margin:10px auto;
		text-align:right;
	}
	.borde{
		border:#555555 solid 1px;
	}
</style>
@endsection

@section('content')

@if(session()->get('mensaje_error'))

    <div class="container alert alert-danger">
        {{ session()->get('mensaje_error') }}
        <a href="{{ route('home') }}" class="close">&times;</a>  
    </div><br />
    
@endif

@if(isset($db_host) && isset($db_usuario))

	<div class="alert-success cartel-host" align="center">
    	<p><h4><b><small>Host:</small>{{$db_host}} <small>Usuario:</small>{{$db_usuario}} <a class="btn btn-info" href="{{ url('/') }}">Volver a seleccionar todo</a></b></h4></p>
    </div>
@endif

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
        
    @endif

@else

    @include('forms.form_host')
    
@endif

@endsection
