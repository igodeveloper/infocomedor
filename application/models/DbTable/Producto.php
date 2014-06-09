<?php
class Application_Model_DbTable_Producto extends ZendExt_Db_Table_Abstract {
	protected $_schema = "INFOCOMEDOR";
	protected $_name = "PRODUCTO";
	public $_rowClass = "Application_Model_Producto";
	public $_primary = array('COD_PRODUCTO');
	public $_primary_auto = TRUE;
	public $_esquema = "INFOCOMEDOR";
    public $_nombre = "PRODUCTO";
    public $_foreignkey = array('Application_Model_DbTable_TipoProducto' => array('COD_TIPO_PRODUCTO'),
                                    'Application_Model_DbTable_UnidadMedida' => array('COD_UNIDAD_MEDIDA')
                                    );         
} ?>
