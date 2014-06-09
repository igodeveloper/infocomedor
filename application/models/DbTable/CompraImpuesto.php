<?php
class Application_Model_DbTable_CompraImpuesto extends ZendExt_Db_Table_Abstract {
	protected $_schema = "INFOCOMEDOR";
	protected $_name = "COMPRA_IMPUESTO";
	public $_rowClass = "Application_Model_CompraImpuesto";
	public $_primary = array('NRO_FACTURA_COMPRA','DET_ITEM_IMPUESTO');
	public $_primary_auto = FALSE;
	public $_esquema = "INFOCOMEDOR";
        public $_nombre = "COMPRA_IMPUESTO";
        public $_foreignkey = array();
} ?>