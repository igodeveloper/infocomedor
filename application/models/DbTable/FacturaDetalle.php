<?php
class Application_Model_DbTable_FacturaDetalle extends ZendExt_Db_Table_Abstract {
	protected $_schema = "INFOCOMEDOR";
	protected $_name = "FACTURA_DETALLE";
	public $_rowClass = "Application_Model_FacturaDetalle";
	public $_primary = array('COD_SUCURSAL','FAC_NRO','FAC_DET_ITEM');
	public $_primary_auto = TRUE;
	public $_esquema = "INFOCOMEDOR";
        public $_nombre = "FACTURA_DETALLE";
        public $_foreignkey = array('Application_Model_DbTable_Factura' => array('COD_SUCURSAL','FAC_NRO'),
                                    'Application_Model_DbTable_UnidadMedida' => array('COD_UNIDAD_MEDIDA'),
                                    'Application_Model_DbTable_Producto' => array('COD_PRODUCTO')

                                    );
} ?>