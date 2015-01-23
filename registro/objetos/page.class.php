<?php

/**
 * Page object for our template manager
 *
 * @author Michael Peacock
 * @version 1.0
 */
class page {


	// page elements
	
	// page title
	private $titulo = '';
	// template tags
	private $tags = array();
	// tags which should be processed after the page has been parsed
	// reason: what if there are template tags within the database content, we must parse the page, then parse it again for post parse tags
	private $postParseTags = array();
	// template bits
	private $modulos = array();
	// the page content
	private $contenido = "";
	
	/**
	 * Create our page object
	 */
    function __construct() { }
    
    /**
     * tomamos el titulo de la página desde la página
     * @return String
     */
    public function obtenerTitulo()
    {
    	return $this->titulo;
    }
    
    /**
     * Configuramos el titulo de la página
     * @param String $titulo el titulo de la página
     * @return void
     */
    public function configurarTitulo( $titulo )
    {
	    $this->titulo = $titulo;
    }
    
    /**
     * Configuramos el contenido de la página
     * @param String $contenido el contenido de la página
     * @return void
     */
    public function configurarContenido( $contenido )
    {
	    $this->contenido = $contenido;
    }
    
    /**
     * Agregar una etiqueta de plantilla, y su valor de reposicion/data para la página
     * @param String $key la clave para guardar dentro de la matriz de etiquetas
     * @param String $data Los datos de reposición (puede ser tambien una matriz)
     * @return void
     */
    public function agregarTag( $key, $data )
    {
	    $this->tags[$key] = $data;
    }
    
    /**
     * Tomamos las etiquetas asociadas a la página
     * @return void
     */
    public function obtenerTags()
    {
	    return $this->tags;
    }
    
    /**
     * Agregar un enunciado de analisis sintáctico de las etiquetas: como el agregado de etiquetas
     * @param String $key la clave para guardar dentro de la matriz
     * @param String $data los datos de reposición
     * @return void
     */
    public function addPPTag( $key, $data )
    {
	    $this->postParseTags[$key] = $data;
    }
    
    /**
     * Get tags to be parsed after the first batch have been parsed
     * @return array
     */
    public function getPPTags()
    {
	    return $this->postParseTags;
    }
    
    /**
     * Add a template bit to the page, doesnt actually add the content just yet
     * @param String the tag where the template is added
     * @param String the template file name
     * @return void
     */
    public function addTemplateBit( $tag, $bit )
    {
	    $this->bits[ $tag ] = $bit;
    }
    
    /**
     * Get the template bits to be entered into the page
     * @return array the array of template tags and template file names
     */
    public function getBits()
    {
	    return $this->bits;
    }
    
    /**
     * Gets a chunk of page content
     * @param String the tag wrapping the block ( <!-- START tag --> block <!-- END tag --> )
     * @return String the block of content
     */
    public function getBlock( $tag )
    {
		preg_match ('#<!-- START '. $tag . ' -->(.+?)<!-- END '. $tag . ' -->#si', $this->content, $tor);
		
		$tor = str_replace ('<!-- START '. $tag . ' -->', "", $tor[0]);
		$tor = str_replace ('<!-- END '  . $tag . ' -->', "", $tor);
		
		return $tor;
    }
    
    public function getContent()
    {
	    return $this->content;
    }
  
}
?>