<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Config;
use Session;
use Cache;
use DB;
use App\Base;

class AjaxController extends Controller
{

	public function ajax_columna(Request $request){

		$db_usuario = $request->session()->get('db_usuario');

        $db_host = $request->session()->get('db_host');

        $charset_def = $request->session()->get('charset_def');

		$database = Cache::get('database');

		$schema = Cache::get('schema');

		Config::set('database.connections.pgsql_variable', array(
                    'driver'    => 'pgsql',
                    'host'      => $db_host,
                    'database'  => $database,
                    'username'  => $db_usuario,
                    'password'  => $request->session()->get('db_contrasenia'),
                    'charset'   => $charset_def,
                    'collation' => 'utf8_unicode_ci',
                    'prefix'    => '',
                    'schema'    => $schema,
					));

		$conexion = DB::connection('pgsql_variable');

		$tabla_selected = Cache::get('tabla_selected');

		$columna = $request->columna;

		$sql="select distinct($columna) as columna from $tabla_selected order by 1;";

		$columna_valores = $conexion->select($sql);

		foreach($columna_valores as $columna_valor) {
            $columna_valores_array[] = (array) $columna_valor->columna;
        }

		return response()->json($columna_valores_array);

	}

    public function ajax_host(Request $request){

		$base = Base::where('servidor',$request->servidor_bases_selected)->get();
        if(count($base) == 1){
            return $base;
        }else{
            return false;
        }

	}


}
