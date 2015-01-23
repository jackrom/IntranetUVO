<?php
/**
 * IntranetUVO
 *
 * @version 1.0
 * @author Juan Carlos Reyes
 */
 
// first and foremost, start our sessions
session_start();

// setup some definitions
// The applications root path, so we can easily get this path from files located in other folders
define( "INTRANETUVO_PATH", dirname( __FILE__ ) ."/" );


// require our registry
require_once('registro/registry.class.php');
$registro = IntranetUVORegistry::singleton();
$registro->obtenerURLData();
// store core objects in the registry.
$registro->almacenarObjeto('mysql.database', 'db');
$registro->almacenarObjeto('template', 'plantillas');
$registro->almacenarObjeto('autenticacion', 'autenticar');
// database settings
include(INTRANETUVO_PATH . 'config.php');
// create a database connection
$registro->obtenerObjeto('db')->newConnection($configs['db_host_intranetuvo'], $configs['db_user_intranetuvo'], $configs['db_pass_intranetuvo'], $configs['db_name_intranetuvo']);
// process any authentication
$registro->obtenerObjeto('autenticar')->chequearParaAutenticar();


// set the default skin setting (we will store these in the database later...)
$registro->almacenarConfiguracion('default', 'skin');

// populate our page object from a template file
$registro->obtenerObjeto('plantilla')->buildFromTemplates('header.tpl.php', 'main.tpl.php', 'footer.tpl.php');

$controladoresActivos = array();
$registro->obtenerObjeto('db')->executeQuery('SELECT controlador FROM controladores WHERE activo=1');
while( $controladorActivo = $registro->obtenerObjeto('db')->getRows() )
{
	$controladoresActivos[] = $controladorActivo['controller'];
}
$currentController = $registro->getURLBit(0);
if( in_array( $controladorActual, $controladoresActivos)){
	require_once( INTRANETUVO_PATH . 'controladores/' . $controladorActual . '/controlador.php');
	$controladorInc = $controladorActual.'controlador';
	$controlador = new $controladorInc( $registro, true );
}
else
{
	require_once( INTRANETUVO_PATH . 'controladores/page/controlador.php');
	$controlador = new Pagecontroller( $registro, true );
}


// parse it all, and spit it out
$registro->obtenerObjeto('plantilla')->parseOutput();
print $registro->obtenerObjeto('plantilla')->getPage()->getContent();


exit();

?>