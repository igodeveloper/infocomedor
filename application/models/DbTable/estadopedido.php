<?php
class Application_Model_DbTable_EstadoPedido extends ZendExt_Db_Table_Abstract {
	protected $_schema = "infocomedor";
	protected $_name = "EstadoPedido";
	public $_rowClass = "Application_Model_EstadoPedido";
	public $_primary = array('COD_ESTADO');
	public $_primary_auto = TRUE;
	public $_esquema = "infocomedor";
        public $_nombre = "EstadoPedido";
} ?>
