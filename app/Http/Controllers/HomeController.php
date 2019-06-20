<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Config;
use Maatwebsite\Excel\Facades\Excel;

use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\Border;
use Box\Spout\Writer\Style\BorderBuilder;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterFactory;

use ReflectionClass;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
		
		return view('home');
    }
	
	public function host(Request $request){
		
		try
		{
			
			
				
			$request->session()->put('db_host',$request->db_host);
			
			$request->session()->put('db_usuario',$request->db_usuario);
			
			$request->session()->put('db_contrasenia',$request->db_contrasenia);
			
			$db_usuario = $request->session()->get('db_usuario');
		
			$db_host = $request->session()->get('db_host');
			
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
			
			$sql="select pg_database.datname
						  from pg_database
						 where pg_database.datname not in ('template0','template1')
					  order by pg_database.datname;";
			
			$bases = $conexion->select($sql);
			
			return view('home',['bases' => $bases,'db_usuario' => $db_usuario,'db_host' => $db_host]);
			
		}
		catch (\Exception $e) {
			
			$mensaje_error = $e->getMessage();
			
			return redirect('home')->with('mensaje_error',$mensaje_error);
			
		}
		
	}
	
	public function database(Request $request)
    {
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
		
		
		$conexion = DB::connection('pgsql_variable');
		
		$sql="select schema_name
					from information_schema.schemata
				   where not schema_name ilike 'pg%'
					 and schema_name <> 'information_schema'
					 and catalog_name = '".$database."'
				order by schema_name;";
		
		$schemas = $conexion->select($sql);
		
		$sql_charset = 'SHOW SERVER_ENCODING';
		
		$charset_registro = $conexion->select($sql_charset);
		
		$charset = $charset_registro[0]->server_encoding;
		
		$request->session()->put('charset_def',$charset);
		
		return view('home',['database' => $database,'schemas' => $schemas,'db_usuario' => $db_usuario,'db_host' => $db_host]);
			
    }
	
	public function schema(Request $request)
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
		
		$sql="select table_name
						from information_schema.tables 
					   where table_schema = '".$schema."'
					order by table_name;";
		
		$tablas = $conexion->select($sql);
		
		return view('home',['database' => $database,'schema' => $schema,'tablas' => $tablas,'db_usuario' => $db_usuario,'db_host' => $db_host]);
    }
	
	public function tabla(Request $request)
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
		
		if(isset($request->where1)){
			
			$comparador1 = $request->comparador1;
			
			$columna_selected1 = $request->columna_selected1;
			
			$where1 = $request->where1;
			
			if($comparador1 === 'ilike'){
				$registros = $registros->whereRaw("$columna_selected1::text ilike '%".$where1."%'");
			}else{
				$registros = $registros->where($columna_selected1,$comparador1,$where1);
			}
		}
		$registros = $registros->orderBy(DB::raw(' 1 '))->paginate(8);
		
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
		
		return view('home',['database' => $database,'schema' => $schema,'tablas' => $tablas,'tabla_selected' => $tabla_selected,'registros' => $registros,'columnas' => $columnas,'db_usuario' => $db_usuario,'db_host' => $db_host,'comparador1' => $comparador1,'columna_selected1' => $columna_selected1,'where1' => $where1,'charset_def' => $charset_def]);
		
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
			
			$registros = $conexion->table($tabla_selected);
			
			$comparador1 = NULL;
				
			$columna_selected1 = NULL;
			
			$where1 = NULL;
			
			if(isset($request->where1)){
				
				$comparador1 = $request->comparador1;
				
				$columna_selected1 = $request->columna_selected1;
				
				$where1 = $request->where1;
				
				if($comparador1 === 'ilike'){
					$registros = $registros->whereRaw("$columna_selected1::text ilike '%".$where1."%'");
				}else{
					$registros = $registros->where($columna_selected1,$comparador1,$where1);
				}
			}
			$registros = $registros->orderBy(DB::raw(' 1 '))->get();
			
			$sql="select column_name
						,data_type||coalesce('('||character_maximum_length::text||')','') as data_type
					from INFORMATION_SCHEMA.columns col 
				   where table_name = '".$tabla_selected."'
					 and table_schema = '".$schema."'
				order by col.ordinal_position";
			
			$columnas = $conexion->select($sql);
			
			$date = date('dmYGis');
			
			Excel::create('registros_'.$tabla_selected.'_'.$date, function ($excel) use ($db_host,$db_usuario,$database,$tabla_selected,$columna_selected1,$comparador1,$where1,$registros,$columnas,$charset_def) {
				$excel->setTitle('Registros de '.$tabla_selected);
				$excel->sheet('Detalle Registros', function ($sheet) use ($db_host,$db_usuario,$database,$tabla_selected,$columna_selected1,$comparador1,$where1,$registros,$columnas,$charset_def) {
					$sheet->loadView('export.export_excel')->with(['db_host' => $db_host,'db_usuario' => $db_usuario,'database' => $database,'tabla_selected' => $tabla_selected,'columna_selected1' => $columna_selected1,'comparador1' => $comparador1,'where1' => $where1,'registros' => $registros,'columnas' => $columnas,'charset_def' => $charset_def]);;
				})->download('xls');
			return back();
			});
			
			//$singleRow = $this->object_to_array($columnas);
			
			/*$columna_array = array();
			
			foreach($columnas as $columna){
				$columnas_array[] = $columna->column_name.' ('.$columna->data_type.')';
			}*/
			
			/*print_r($columnas_array);
			
			exit;*/
			
			/*foreach($registros as $registro){
				
				$registros_array[] = (array)$registro;
				
			}*/
			
			/*print_r($registros_array);
			
			exit;*/
			
			/*$border = (new BorderBuilder())
							  ->setBorderBottom(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
							  ->setBorderRight(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
							  ->build();
			
			$style_primera_linea = (new StyleBuilder())
								   ->setFontBold()
								   ->setFontSize(12)
								   ->setFontColor(Color::WHITE)
								   //->setShouldWrapText(false)
								   ->setBackgroundColor(Color::DARK_BLUE)
								   ->setBorder($border)
								   ->build();
								   
			$style_resto_filas = (new StyleBuilder())
								   //->setFontBold(false)
								   ->setFontSize(11)
								   ->setFontColor(Color::BLACK)
								   ->setShouldWrapText(true)
								   ->setBackgroundColor(Color::WHITE)
								   ->setBorder($border)
								   ->build();*/
			
			//$writer = WriterFactory::create(Type::XLSX); // for XLSX files
			//$writer = WriterFactory::create(Type::CSV); // for CSV files
			//$writer = WriterFactory::create(Type::ODS); // for ODS files
			
			//$fileName = 'registros_'.$tabla_selected.'_'.$date.'.xlsx';
			
			//$writer->openToFile($filePath); // write data to a file or to a PHP stream
			//$writer->openToBrowser($fileName); // stream data directly to the browser
			
			//$columnas_array = ['columna1','columna2'];
			
			/*$writer->addRowWithStyle($columnas_array,$style_primera_linea); // add a row at a time
			
			
			$writer->addRowsWithStyle($registros_array,$style_resto_filas); // add multiple rows at a time
			
			$writer->close();*/
			
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
			
			/*echo $columnas_registro;
			
			echo '<br>';
			
			echo $insert;
			
			exit;*/
			
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
			
			//print_r($valores_repetidos_primera_columna); exit;
			
			if(count($valores_repetidos_primera_columna) === 0){
			
				$conexion->delete('delete from '.$tabla_selected.' where '.$primera_columna."::text = '".$id."';");
				
				return back()->withInput()->with('registro_eliminado', 'El registro se eliminó correctamente');
			}else{
				return back()->withInput()->with('registro_no_modificado', 'No se puede borrar el registro de '.$tabla_selected.' porque hay valores repetidos en la columna '.$primera_columna.' usada como primary key.');
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
			
			//print_r($valores_repetidos_primera_columna); exit;
			
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
							
								$update = "'".$request->$columna_registro."'";
								
							}
						  
						}
						
						$sql_select_columna = "select $columna_registro::text as $columna_registro from $tabla_selected where ($primera_columna)::text = ($id)::text";
						
						$select_columna = $conexion->select($sql_select_columna);
						
						//$select_columna = NULL;
						
						//print_r($select_columna[0]->$columna_registro); exit;
						
						$select_columna = $select_columna[0]->$columna_registro;
						
					}else{
						
						if($charset_def !== 'UTF8'){
					
							$update = "'".utf8_decode($request->$columna_registro)."'";
							
						}else{
							
							$update = "'".$request->$columna_registro."'";	
						
						}
						
						//echo $update; exit;
						
						if( $select_columna !== $request->$columna_registro){
							
							//echo $select_columna; exit;
							
							$conexion->update('update '.$tabla_selected.' set '.$columna_registro.' = '.$update.' where ('.$primera_columna.')::text = ('.$id.')::text;');
							
							$count_modificaciones++;
							
						}
					
					}
					
				}
				
				//echo $count_modificaciones; exit;
				
				if($count_modificaciones === 0){
					
					return back()->withInput()->with('registro_no_modificado', 'Nada fue modificado.');
					
				}else{
				
					return back()->withInput()->with('registro_actualizado', 'El registro se actualizó correctamente (campos modificados = '.$count_modificaciones.').');
					
				}
			}else{
				return back()->withInput()->with('registro_no_modificado', 'No se puede modificar '.$tabla_selected.' porque hay valores repetidos en la columna '.$primera_columna.' usada como primary key.');
			}
		
		}
		catch (\Exception $e)
		{
			
			$mensaje_error = $e->getMessage();
			
			return back()->withInput()->with('mensaje_error',$mensaje_error);
			
		}
    }
	
}
