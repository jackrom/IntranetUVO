<?php
/**
 * Administración de Acceso a la Intranet ( Authentication )
 * 
 *
 * @version 1.0
 * @author Juan Carlos Reyes
 */
class autenticacion {

	private $userID;
	private $logueado = false;
	private $admin = false;
	
	private $grupos = array();
	
	private $baneado = false;
	private $usuario;
	private $procesado = false;
	
    public function __construct(){
		
    }
    
    public function chequearParaAutenticar(){
    	if(isset($_SESSION['intranetUVO_auth_session_uid']) && intval($_SESSION['intranetUVO_auth_session_uid']) > 0){
    		$this->autenticacionDeSesion(intval( $_SESSION['intranetUVO_auth_session_uid'] ) );
    	}elseif(isset(  $_POST['intranet_auth_user']) &&  $_POST['intranet_auth_user'] != '' && isset($_POST['intranet_auth_pass']) && $_POST['intranet_auth_pass'] != ''){
    		$this->mensajeAutenticacion(IntranetUVORegistry::obtenerObjeto('db')->sanitizeData($_POST['intranet_auth_user']), md5($_POST['intranet_auth_pass']));
    	}
	     //echo $this->userID;
    }
    
    private function autenticacionDeSesion($uid){
    	$sql = "SELECT u.ID, u.usuario, u.activo, u.email, u.admin, u.baneado, u.nombre, (SELECT GROUP_CONCAT( g.nombre SEPARATOR '-groupsep-' ) FROM grupos g, miembros_de_grupos gm WHERE g.ID = gm.grupo AND gm.usuario = u.ID ) AS miembrosdegrupos FROM usuarios u WHERE u.ID={$uid}";
    	IntranetUVORegistry::obtenerObjeto('db')->executeQuery( $sql );
    	if( IntranetUVORegistry::obtenerObjeto('db')->numRows() == 1 ){
    		$userData = IntranetUVORegistry::obtenerObjeto('db')->getRows();
    		if( $userData['activo'] == 0 ){
    			$this->logueado = false;
    			$this->razonesDeFallaAlEntrar = 'inactivo';
    			$this->activo = false;
    		}elseif( $userData['baneado'] != 0){
    			$this->logueado = false;
    			$this->razonesDeFallaAlEntrar = 'baneado';
    			$this->baneado = false;
    		}else{
    			$this->logueado = true;
    			$this->userID = $uid;
    			$this->admin = ( $userData['admin'] == 1 ) ? true : false;
    			$this->usuario = $userData['usuario'];
    			$this->nombre = $userData['nombre'];
    			
    			$grupos = explode( '-groupsep-', $userData['miembrosdegrupo'] );
    			$this->grupos = $grupos;
    		}
    		
    	}else{
    		$this->logueado = false;
    		$this->razonesDeFallaAlEntrar = 'nousuario';
    	}
    	if($this->logueado == false){
    		$this->salir();
    	}
    }
    
    private function mensajeAutenticacion($u, $p){
    	$this->procesado = true;
    	$sql = "SELECT u.ID, u.usuario, u.email, u.admin, u.baneado, u.activo, u.nombre, (SELECT GROUP_CONCAT( g.nombre SEPARATOR '-groupsep-' ) FROM grupos g, miembros_de_grupos gm WHERE g.ID = gm.groupo AND gm.usuario = u.ID ) AS miembrodegrupo FROM usuarios u WHERE u.usuario='{$u}' AND u.password_hash='{$p}'";
    	//echo $sql;
    	IntranetUVORegistry::obtenerObjeto('db')->executeQuery( $sql );
    	if(IntranetUVORegistry::obtenerObjeto('db')->numRows() == 1 ){
    		$userData = IntranetUVORegistry::obtenerObjeto('db')->getRows();
    		if( $userData['activo'] == 0 ){
    			$this->logueado = false;
    			$this->razonesDeFallaAlEntrar = 'inactivo';
    			$this->activo = false;
    		}elseif( $userData['baneado'] != 0){
    			$this->logueado = false;
    			$this->razonesDeFallaAlEntrar = 'baneado';
    			$this->baneado = false;
    		}else{
    			$this->logueado = true;
    			$this->userID = $userData['ID'];
    			$this->admin = ( $userData['admin'] == 1 ) ? true : false;
    			$_SESSION['intranetUVO_auth_session_uid'] = $userData['ID'];
    			
    			$grupos = explode( '-groupsep-', $userData['groupmemberships']);
    			$this->grupos = $grupos;
    		}
    		
    	}else{
    		$this->logueado = false;
    		$this->razonesDeFallaAlEntrar = 'credencialesinvalidas';
    	}
    }
    
    function salir(){
		$_SESSION['intranetUVO_auth_session_uid'] = '';
	}
    
    
    public function obtenerIdUsuario(){
	    return $this->userID;
    }
    
    public function estaLogueado(){
	    return $this->logueado;
    }
    
    public function esAdmin(){
    	return $this->admin;
    }
    
    public function obtenerUsuario(){
    	return $this->usuario;
    }
    
    public  function esMiembroDeGrupo($grupo){
	    if(in_array($grupo, $this->grupos)){
		    return true;
	    }else{
		    return false;
	    }
    }
    
}
?>