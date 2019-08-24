<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Config;
use App\Exports\ExcelExport;
use Maatwebsite\Excel\Facades\Excel;

use Session;

use ReflectionClass;

class HomeController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function index()
    {
		
		//Primero retorna la vista con el formulario para conectarse al host
		return view('home');
		
    }
	
	public function host(Request $request){
		
		
		//Intenta hacer la conexión (en caso de fallar, retorna al home y muestra el mensaje de error)
		try
		{
			
			
			//Guardo el host, usuario y contraseña definidos en el form_host para hacer la conexión, en variables de sesión; mientras dure la sesión y no se modifiquen, la conexión siempre se va a realizar con estos valores
			$request->session()->put('db_host',$request->db_host);
			
			$request->session()->put('db_usuario',$request->db_usuario);
			
			$request->session()->put('db_contrasenia',$request->db_contrasenia);
			
			//Traigo los valores de la conexión para manejarlos como variantes directamente (menos la contraseña)
			$db_usuario = $request->session()->get('db_usuario');
		
			$db_host = $request->session()->get('db_host');
			
			//Genero el modelo de la conexión pgsql_variable con los valores definidos, y realizo la conexión
			Config::set('database.connections.pgsql_variable', array(
				'driver'    => 'pgsql',
				'host'      => $db_host,
				'database'  => 'postgres',
				'username'  => $db_usuario,
				'password'  => $request->session()->get('db_contrasenia'),
				'charset'   => 'utf8',
				'collation' => 'utf8_unicode_ci',
				'prefix'    => '',
				'schema'    => 'public',
			));
			
			$conexion = DB::connection('pgsql_variable');
			
			//Hago la consulta para traer las bases de datos que haya en el host
			$sql="select pg_database.datname
						  from pg_database
						 where pg_database.datname not in ('template0','template1')
					  order by pg_database.datname;";
			
			$bases = $conexion->select($sql);
			
			//Retorno al home con los datos de la consulta
			return view('home',['bases' => $bases,'db_usuario' => $db_usuario,'db_host' => $db_host]);
			
		}
		catch (\Exception $e) {
			
			//En caso de error retorno al home con el mensaje del error
			$mensaje_error = $e->getMessage();
			
			return redirect('home')->withInput()->with('mensaje_error',$mensaje_error);
			
		}
		
	}
	
	public function database(Request $request)
    {
        
		//Verifico que los input session hechos en el método anterior sigan seteados
		if($request->session()->get('db_usuario') !== NULL && $request->session()->get('db_host') !== NULL){
		
			//Traigo los inputs session y la base de datos seleccionada en el form_database (todos los datos para armar la conexión, a partir de acá, se manejan por get)
			$database = $request->database;
			
			$db_usuario = $request->session()->get('db_usuario');
			
			$db_host = $request->session()->get('db_host');
			
			Config::set('database.connections.pgsql_variable', array(
				'driver'    => 'pgsql',
				'host'      => $db_host,
				'database'  => $database,
				'username'  => $db_usuario,
				'password'  => $request->session()->get('db_contrasenia'),
				'charset'   => 'utf8',
				'collation' => 'utf8_unicode_ci',
				'prefix'    => '',
				'schema'    => 'public',
			));
			
			//Realizo la conexión
			$conexion = DB::connection('pgsql_variable');
			
			//Consulto los schemas disponibles de la base de datos seleccionada
			$sql="select schema_name
						from information_schema.schemata
					   where not schema_name ilike 'pg%'
						 and schema_name <> 'information_schema'
						 and catalog_name = '".$database."'
					order by schema_name;";
			
			$schemas = $conexion->select($sql);
			
			//Consulto la codificación de la base y la almaceno en un input session para usarla en futuras consultas (hasta acá, siempre se usa la codificación UTF8)
			$sql_charset = 'SHOW SERVER_ENCODING';
			
			$charset_registro = $conexion->select($sql_charset);
			
			$charset = $charset_registro[0]->server_encoding;
			
			$request->session()->put('charset_def',$charset);
			
			//Retorno al home con los datos de las consultas
			return view('home',['database' => $database,'schemas' => $schemas,'db_usuario' => $db_usuario,'db_host' => $db_host]);
		
		}else{
			
			//En caso que los input session no sigan seteados, redirecciono al home inicial
			return redirect('home');
			
		}		
			
    }
	
	public function schema(Request $request)
    {
        
		//Verifico que los input session hechos en el primer método sigan seteados
		if($request->session()->get('db_usuario') !== NULL && $request->session()->get('db_host') !== NULL){
				
			//Traigo los inputs session y la base de datos seleccionada más el schema seleccionado en el form_schema
			$database = $request->database;
			
			$schema = $request->schema;
			
			$db_usuario = $request->session()->get('db_usuario');
			
			$db_host = $request->session()->get('db_host');
			
			$charset_def = $request->session()->get('charset_def');
			
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
			
			//Realizo la conexión	
			$conexion = DB::connection('pgsql_variable');
			
			//Consulto las tablas disponibles en el schema seleccionado
			$sql="select table_name
							from information_schema.tables 
						   where table_schema = '".$schema."'
						order by table_name;";
			
			$tablas = $conexion->select($sql);
			
			//Retorno al home con los datos de las consultas
			return view('home',['database' => $database,'schema' => $schema,'tablas' => $tablas,'db_usuario' => $db_usuario,'db_host' => $db_host]);
			
		}else{
			
			//En caso que los input session no sigan seteados, redirecciono al home inicial
			return redirect('home');
			
		}
		
    }
	
	public function tabla(Request $request)
    {
		
		try
		{
			
			if($request->tabla_selected === NULL){ return back()->withInput();}
			
			
			
			if($request->session()->get('db_usuario') !== NULL && $request->session()->get('db_host') !== NULL){
			
				
				
				ini_set('memory_limit', -1);
				
				$database = $request->database;
				
				$schema = $request->schema;
				
				$db_usuario = $request->session()->get('db_usuario');
				
				$db_host = $request->session()->get('db_host');
				
				$charset_def = $request->session()->get('charset_def');
				
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
				
				if(isset($request->where1) && isset($request->caracteres_raros)){
				
					$function = 'f_limpiar_acentos_'.$db_usuario.'_'.$database.'_'.$schema;
				
				}else{
					
					$function = '';
					
				}
				
				if($charset_def != 'UTF8'){
				
					$originales = utf8_decode('ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ');
						
					$modificadas = utf8_decode('aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr');
				
				}else{
					
					$originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
						
					$modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
					
				}
				
				if(isset($request->where1) && isset($request->caracteres_raros)){
					
					if($request->comparador1 === 'ilike'){
					
						$conexion->unprepared("CREATE OR REPLACE FUNCTION ".$function."(text) RETURNS text AS \$BODY$ SELECT translate($1,'".$originales."','".$modificadas."'); \$BODY$ LANGUAGE sql IMMUTABLE STRICT COST 100");
						
					}
					
				}
				
				$sql="select table_name
								from information_schema.tables 
							   where table_schema = '".$schema."'
							order by table_name;";
				
				$tablas = $conexion->select($sql);
				
				$tabla_selected = $request->tabla_selected;
				
				$registros = $conexion->table($tabla_selected);
				
				$comparador1 = NULL;
					
				$columna_selected1 = NULL;
				
				$where1 = NULL;
				
				$sql="select column_name
							,is_nullable as required
							,character_maximum_length as max_char
							,data_type as type
							,data_type||coalesce('('||character_maximum_length::text||')','') as data_type
						from INFORMATION_SCHEMA.columns col 
					   where table_name = '".$tabla_selected."'
						 and table_schema = '".$schema."'
					order by col.ordinal_position";
				
				$columnas = $conexion->select($sql);
				
				$col_num = 1;
				
				$col_array = array();
				
				foreach($columnas as $columna){
					
					if(isset($request->ordercol)){
					
						
						if($col_num == $request->ordercol){
							
							$col_num = $col_num + 1;
							
						}else{
							
							$col_array[] = $col_num++;
							
						}
					}else{
							
						$col_array[] = $col_num++;
						
					}
				
				}
				
				$sort = 'asc';
				
				if(isset($request->sort)) $sort = $request->sort;
				
				if(isset($request->ordercol)){
					
					$col_string = $request->ordercol.' '.$sort.','.implode(",",$col_array);
					
				}else{
					
					$col_string = implode(",",$col_array);
					
				}
				
				if(isset($request->where1)){
					
					$comparador1 = $request->comparador1;
					
					$columna_selected1 = $request->columna_selected1;
					
					if($charset_def != 'UTF8'){
					
						$where1 = utf8_decode($request->where1);
						
					}else{
						
						$where1 = $request->where1;
					}
					
					$busqueda = str_replace("´`'çÇ¨",'_',$where1);
					
					if($comparador1 === 'ilike'){
						
						$registros = $registros->whereRaw($function."($columna_selected1::text) ilike ".$function."('%".$busqueda."%')");
						
					}else{
						
						$registros = $registros->where($columna_selected1,$comparador1,$busqueda);
						
					}
					
				}
				
				$count_registros = count($registros->get());
				
				$registros = $registros->orderBy(DB::raw($col_string))->paginate(8);
				
				$caracteres_raros = NULL;
				
				if(isset($request->where1) && isset($request->caracteres_raros)){
					
					if($request->comparador1 === 'ilike'){
					
						$conexion->unprepared('DROP FUNCTION '.$function.'(text)');
						
					}
					
					$caracteres_raros = 'S';
					
				}
				
				if($charset_def != 'UTF8'){
					
					$where1 = utf8_encode($where1);
						
				}
				
				return view('home',['database' => $database,'schema' => $schema,'tablas' => $tablas,'tabla_selected' => $tabla_selected,'registros' => $registros,'columnas' => $columnas,'db_usuario' => $db_usuario,'db_host' => $db_host,'comparador1' => $comparador1,'columna_selected1' => $columna_selected1,'where1' => $where1,'charset_def' => $charset_def,'count_registros' => $count_registros,'sort' => $sort,'ordercol_def' => $request->ordercol,'caracteres_raros' => $caracteres_raros]);
				
			}else{
				
				return redirect('home');
				
			}
		
		}catch (\Exception $e) {
			
			$mensaje_error = $e->getMessage();
			
			return back()->withInput()->with('mensaje_error',$mensaje_error);
			
		}
		
    }
	
	
	function object_to_array($object)
	{
		$reflectionClass = new ReflectionClass(get_class($object));
		$array = array();
		foreach ($reflectionClass->getProperties() as $property) {
			$property->setAccessible(true);
			$array[$property->getName()] = $property->getValue($object);
			$property->setAccessible(false);
		}
		return $array;
	}
	
	
	public function export_excel(Request $request)
	{
		try
		
		{
			ini_set('memory_limit', -1);
			
			$tabla_selected = $request->tabla_selected;
			
			$date = date('dmYGis');
			
			return Excel::download(new ExcelExport($request), 'registros_'.$tabla_selected.'_'.$date.'.xlsx');
			
			/*Excel::create('registros_'.$tabla_selected.'_'.$date, function ($excel) use ($db_host,$db_usuario,$database,$tabla_selected,$columna_selected1,$comparador1,$where1,$registros,$columnas,$charset_def) {
				$excel->setTitle('Registros de '.$tabla_selected);
				$excel->sheet('Detalle Registros', function ($sheet) use ($db_host,$db_usuario,$database,$tabla_selected,$columna_selected1,$comparador1,$where1,$registros,$columnas,$charset_def) {
					$sheet->loadView('export.export_excel')->with(['db_host' => $db_host,'db_usuario' => $db_usuario,'database' => $database,'tabla_selected' => $tabla_selected,'columna_selected1' => $columna_selected1,'comparador1' => $comparador1,'where1' => $where1,'registros' => $registros,'columnas' => $columnas,'charset_def' => $charset_def]);;
				})->download('xls');
			return back();
			});*/
			
			//return back();
			
		}
		catch (\Exception $e)
		{
			
			$mensaje_error = $e->getMessage();
			
			return back()->withInput()->with('mensaje_error',$mensaje_error);
			
		}
		
	}
	
	public function store(Request $request)
    {
		
		try
		
		{
		
			$database = $request->database;
			
			$schema = $request->schema;
			
			$db_usuario = $request->session()->get('db_usuario');
			
			$db_host = $request->session()->get('db_host');
			
			$charset_def = $request->session()->get('charset_def');
			
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
			
			$tabla_selected = $request->tabla_selected;
			
			$sql="select column_name
						,is_nullable as required
						,character_maximum_length as max_char
						,data_type as type
						,data_type||coalesce('('||character_maximum_length::text||')','') as data_type
					from INFORMATION_SCHEMA.columns col 
				   where table_name = '".$tabla_selected."'
					 and table_schema = '".$schema."'
				order by col.ordinal_position";
			
			$columnas = $conexion->select($sql);
			
			$insert = '';
			
			$columnas_registro = '';
			
			foreach($columnas as $columna){
				
				$columnas_registro = $columnas_registro.$columna->column_name.',';
				
				$columna_registro = $columna->column_name;
				
				if($request->$columna_registro === NULL){
					
					$insert = $insert.'NULL,';
					
				}else{
				
					if($columna->type === 'timestamp without time zone'){
						
						$timestamp_without_time_zone = date('Y-m-d H:i:s', strtotime($request->$columna_registro));
						
						$insert = $insert."'".$timestamp_without_time_zone."',";
						
					}else{
						
						if($charset_def !== 'UTF8'){
					
							$insert = $insert."'".utf8_decode($request->$columna_registro)."',";
							
						}else{
							
							$insert = $insert."'".$request->$columna_registro."',";
						
						}
					
					}
				  
				}
				
			}
			
			$columnas_registro = trim($columnas_registro, ',');
			
			$insert = trim($insert, ',');
			
			$conexion->insert('insert into '.$tabla_selected.' ('.$columnas_registro.') values ('.$insert.');');
			
			return back()->withInput()->with('registro_agregado', 'El registro se agregó correctamente');
			
		}
		catch (\Exception $e) {
			
			$mensaje_error = $e->getMessage();
			
			return back()->withInput()->with('mensaje_error',$mensaje_error);
			
		}
		
    }
	
	public function destroy($id,Request $request)
	{
		
		try
		{
		
			$database = $request->database;
			
			$schema = $request->schema;
			
			$db_usuario = $request->session()->get('db_usuario');
			
			$db_host = $request->session()->get('db_host');
			
			$charset_def = $request->session()->get('charset_def');
			
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
			
			$tabla_selected = $request->tabla_selected;
			
			$primera_columna = $request->primera_columna;
			
			$sql_valores_repetidos_primera_columna = "SELECT $primera_columna
												  FROM $tabla_selected
												  GROUP BY $primera_columna
												  HAVING (COUNT($primera_columna) > 1)";
												  
			
			$valores_repetidos_primera_columna = $conexion->select($sql_valores_repetidos_primera_columna);
			
			if(count($valores_repetidos_primera_columna) === 0){
			
				$conexion->delete('delete from '.$tabla_selected.' where '.$primera_columna."::text = '".$id."';");
				
				return back()->withInput()->with('registro_eliminado', 'El registro se eliminó correctamente');
				
			}else{
				
				return back()->withInput()->with('registro_no_modificado', 'No se puede borrar el registro de '.$tabla_selected.' porque hay valores repetidos en la columna '.$primera_columna.' usada como primary key por la aplicación.');
				
			}
		
		}
		catch (\Exception $e) {
			
			$mensaje_error = $e->getMessage();
			
			return back()->withInput()->with('mensaje_error',$mensaje_error);
			
		}
    }
	
	public function edit($id,Request $request)
	{
		
		try
		{
		
			$database = $request->database;
			
			$schema = $request->schema;
			
			$db_usuario = $request->session()->get('db_usuario');
			
			$db_host = $request->session()->get('db_host');
			
			$charset_def = $request->session()->get('charset_def');
			
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
			
			$tabla_selected = $request->tabla_selected;
			
			$sql="select column_name
						,is_nullable as required
						,character_maximum_length as max_char
						,data_type as type
						,data_type||coalesce('('||character_maximum_length::text||')','') as data_type
					from INFORMATION_SCHEMA.columns col 
				   where table_name = '".$tabla_selected."'
					 and table_schema = '".$schema."'
				order by col.ordinal_position";
			
			$columnas = $conexion->select($sql);
			
			$insert = '';
			
			$columnas_registro = '';
			
			foreach($columnas as $columna){

				$primera_columna = $columna->column_name;
				
				break;
			
			}
			
			$sql_valores_repetidos_primera_columna = "SELECT $primera_columna
												  FROM $tabla_selected
												  GROUP BY $primera_columna
												  HAVING (COUNT($primera_columna) > 1)";
												  
			
			$valores_repetidos_primera_columna = $conexion->select($sql_valores_repetidos_primera_columna);
			
			if(count($valores_repetidos_primera_columna) === 0){
			
				$count_modificaciones = 0;
				
				
				foreach($columnas as $columna){
					
					$columna_registro = $columna->column_name;
					
					if($columna_registro != $primera_columna){
						
						if($request->$columna_registro === NULL){
							
							$update = 'NULL';
							
						}else{
						
							if($columna->type === 'timestamp without time zone'){
								
								$timestamp_without_time_zone = date('Y-m-d H:i:s', strtotime($request->$columna_registro));
								
								$update = "'".$timestamp_without_time_zone."'";
								
							}else{
							
								if($charset_def !== 'UTF8'){
					
									$update = "'".utf8_decode($request->$columna_registro)."'";
									
								}else{
									
									$update = "'".$request->$columna_registro."'";	
								
								}
								
							}
						  
						}
						
						$sql_select_columna = "select $columna_registro::text as $columna_registro from $tabla_selected where ($primera_columna)::text = ($id)::text";
						
						$select_columna = $conexion->select($sql_select_columna);
						
						$select_columna = $select_columna[0]->$columna_registro;
						
						if( $select_columna !== $request->$columna_registro){
							
							$conexion->update('update '.$tabla_selected.' set '.$columna_registro.' = '.$update.' where ('.$primera_columna.')::text = ('.$id.')::text;');
							
							$count_modificaciones++;
							
						}
					
					}
					
				}
				
				if($count_modificaciones === 0){
					
					return back()->withInput()->with('registro_no_modificado', 'Nada fue modificado.');
					
				}else{
				
					return back()->withInput()->with('registro_actualizado', 'El registro se actualizó correctamente (campos modificados = '.$count_modificaciones.').');
					
				}
			}else{
				
				return back()->withInput()->with('registro_no_modificado', 'No se puede modificar '.$tabla_selected.' porque hay valores repetidos en la columna '.$primera_columna.' usada como primary key por la aplicación.');
				
			}
		
		}
		catch (\Exception $e)
		{
			
			$mensaje_error = $e->getMessage();
			
			return back()->withInput()->with('mensaje_error',$mensaje_error);
			
		}
    }
	
}
