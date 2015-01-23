<?php
/**
 * El objeto PCARegistry
 * Implementa los patrones de diseño: Registro y Singleton
 * IntranetUVO
 *
 * @version 1.0
 * @author Juan Carlos Reyes
 */
class IntranetUVORegistry {
	
	/**
	 * La matriz de objetos que será almacenada dentro del registro
	 * @access private
	 */
	private static $objetos = array();
	
	/**
	 * La matriz de configuración que será almacenada dentro del registro
	 * @access private
	 */
	private static $configuraciones = array();
	
	
	/**
	 * La instancia del registro
	 * @access private
	 */
	private static $instancia;
	
	private static $urlPath;
	private static $urlBits = array();
	
	/**
	 * Un constructor privado para prevenir que cualquier objetop sea creado directamente
	 * @access private
	 */
	private function __construct(){
	
	}
		
	/**
	 * El método Singleton usado para accesar el objeto
	 * @access public
	 * @return 
	 */
	public static function singleton(){
		if( !isset(self::$instancia)){
			$obj = __CLASS__;
			self::$instancia = new $obj;
		}
		return self::$instancia;
	}
	
	/**
	 * Para prevenir la clonación del objeto: emitira un E_USER_ERROR si se intenta crearlo
	 */
	public function __clone(){
		trigger_error( 'La clonaci&oacute;n del registro no esta permitida en esta aplicaci&oacute;n', E_USER_ERROR );
	}
	
	/**
	 * Almacenamos un objeto en el registro
	 * @param String $object el nombre del objeto
	 * @param String $key la clave para la matriz
	 * @return void
	 */
	public function almacenarObjeto($objeto, $key){
		if( strpos( $objeto, 'database' ) !== false ){
			$objeto_a = str_replace('.database', 'database', $objeto);
			$objeto = str_replace('.database', '', $objeto);
			require_once('databaseobjetos/' . $objeto . '.database.class.php');
			$objeto = $objeto_a;
		}else{
			require_once('objetos/' . $objeto . '.class.php');
		}
		
		self::$objetos[$key] = new $objeto(self::$instancia);
	}
	
	/**
	 * Tomamos el objeto dentro del registro
	 * @param String $key La matriz de las claves usada para almacenar los objetos
	 * @return object - el objeto
	 */
	public function obtenerObjeto($key){
		if(is_object(self::$objetos[$key])){
			return self::$objetos[$key];
		} 
	}
	
	/**
	 * Guardamos la configuración en el registro
	 * @param String $data la configuración que deseamos almacenar o guardar
	 * @param String $key la clave de la matriz para accesar la configuración
	 * @return void
	 */
	public function almacenarConfiguracion($data, $key){
		self::$configuraciones[ $key ] = $data;
	}
	
	/**
	 * Tomamos la configuración desde el registro
	 * @param String $key La clave usada para almacenar la configuración
	 * @return String la configuración
	 */
	public function obtenerConfiguracion( $key ){
		return self::$configuraciones[ $key ];
	}
	
	
	/**
	 * Tomamos los datos desde la URL activa o actual
	 * @return void
	 */
	public function obtenerURLData(){
		$urldata = (isset($_GET['page'])) ? $_GET['page'] : '' ;
		self::$urlPath = $urldata;
		if($urldata == ''){
			self::$urlBits[] = 'home';
			self::$urlPath = 'home';
		}else{
			$data = explode('/', $urldata);
			while (!empty($data) && strlen(reset($data)) === 0){
		    	array_shift( $data );
		    }
		    while (!empty($data) && strlen(end($data )) === 0){
		        array_pop($data);
		    }
			self::$urlBits = $this->array_trim( $data );
		}
	}
	
	public function obtenerURLBits(){
		return self::$urlBits;
	}
	
	public function obtenerURLBit($whichBit){
		return self::$urlBits[ $whichBit ];
	}
	
	public function obtenerURLPath(){
		return self::$urlPath;
	}
	
	private function array_trim($array){
	    while (!empty($array) and strlen(reset($array)) === 0){
	        array_shift($array);
	    }
	    while (!empty($array) and strlen(end($array)) === 0){
	        array_pop($array);
	    }
	    return $array;
	}

	
	
	
	
}

?>