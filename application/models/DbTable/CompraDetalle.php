<?php
class Application_Model_DbTable_CompraDetalle extends ZendExt_Db_Table_Abstract {
	protected $_schema = "INFOCOMEDOR";
	protected $_name = "COMPRA_DETALLE";
	public $_rowClass = "Application_Model_CompraDetalle";
	public $_primary = array('NRO_FACTURA_COMPRA','DET_ITEM_COMPRA');
	public $_primary_auto = FALSE;
	public $_esquema = "INFOCOMEDOR";
        public $_nombre = "COMPRA_DETALLE";
        public $_foreignkey = array();
} ?>
