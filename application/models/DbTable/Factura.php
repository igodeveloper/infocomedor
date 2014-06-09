<?php
class Application_Model_DbTable_Factura extends ZendExt_Db_Table_Abstract {
	protected $_schema = "INFOCOMEDOR";
	protected $_name = "FACTURA";
	public $_rowClass = "Application_Model_Factura";
	public $_primary = array('COD_SUCURSAL','FAC_NRO');
	public $_primary_auto = TRUE;
	public $_esquema = "INFOCOMEDOR";
        public $_nombre = "FACTURA";
        public $_foreignkey = array('Application_Model_DbTable_Sucursal' => array('COD_SUCURSAL'),
                                    'Application_Model_DbTable_Cliente' => array('COD_CLIENTE'),

                                    );
} ?>
