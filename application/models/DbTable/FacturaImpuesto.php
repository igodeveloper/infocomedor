<?php
class Application_Model_DbTable_FacturaImpuesto extends ZendExt_Db_Table_Abstract {
	protected $_schema = "INFOCOMEDOR";
	protected $_name = "FACTURA_IMPUESTO";
	public $_rowClass = "Application_Model_FacturaImpuesto";
	public $_primary = array('COD_SUCURSAL','FAC_NRO','FAC_DET_ITEM','FAC_IMPUESTO_ITEM');
	public $_primary_auto = TRUE;
	public $_esquema = "INFOCOMEDOR";
        public $_nombre = "FACTURA_IMPUESTO";
        public $_foreignkey = array('Application_Model_DbTable_FacturaDetalle' => array('COD_SUCURSAL','FAC_NRO','FAC_DET_ITEM'),
                                    'Application_Model_DbTable_Impuesto' => array('COD_IMPUESTO')

                                    );
} ?>