<?php
class Application_Model_DataService
{
    protected $_dbTable = null;
    protected $_rowRepository = array();
    protected $_tableRow = null;
    /**
     * @return the $_tableRow
     */
    public function getTableRow ()
    {
        //		return Application_Model_DataService::$_tableRow;
        return $this->_tableRow;
    }
    /**
     * @return the $_dbTable
     */
    public function getDbTable ()
    {
        //		return Application_Model_DataService::$_dbTable;
        return $this->_dbTable;
    }
    /**
     * @param field_type $_dbTable
     */
    public function setDbTable ($_dbTable)
    {
        $this->_dbTable = $_dbTable;
    }
    /**
     * Crea un Modulo para manejar una tabla
     * @param String $tableClass
     * @example $modTabla = new Application_Model_DataService('Application_Model_DbTable_NombreTabla')
     * @copyright Infocomedor S.A.
     * @author Federico Cano
     */
    public function __construct ($tableClass)
    {
        if ($this->_dbTable == null) {
            $this->_dbTable = new $tableClass();
            $laTabla = $this->getDbTable();
            $rowclass = $laTabla->_rowClass;
             //          $this->_tableRow = new $rowclass;
        }
    }
    

    /**
     * Devuelve un Array de filas de una Tabla Madre y sus Relaciones
     * @param $tables String  $tables es un Array de (Objetos de Modelos de Tabla) ej. (Application_Model_DbTable_Sucursal)
     * @param array $fields
     * @param array $where
     * @param String $orders
     * @param array $groups 
     * @example $fields = array('IMPUES.IMPSIG','EMPRES.EMPNM','SUCURS.SUCNM');
     * 			$tables =  array('Application_Model_DbTable_Empres');
     * 			$where = array('nombre_campo', 'valor_campo');
     * 			$orders = array('EMPRES.EMPNM DESC');
     * 			$groups = array('SUCURS.SUCNM');
     * @return $rows  => foreach ($rows as $row) {echo $row['EMPNM'];}
     * @copyright Infocomedor S.A.
     * @author Federico Cano
     */
    
     public function getRowsJoin ($fields, $tables,$leftTables = null, $where = null, $orders = null, $groups = null){
     	$db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select();
     	$aliasBas = " ". $this->getDbTable()->_nombre;
     	$tabBas = $this->getDbTable()->_esquema .'.'. $this->getDbTable()->_nombre;
     	$select->from(array($aliasBas => $tabBas), $fields);
     	if(isset($tables)) {
	     	foreach ($tables as $table){
	     		
	     		$on ='';
	     		$laTabla = new $table();
	     		$esqTab = $laTabla->_esquema .'.'. $laTabla->_nombre;
	     		if(isset( $laTabla->_alias)){
	     			$aliasFor =  " ". $laTabla->_alias;
	     		}else{
	     			$aliasFor =  " ". $laTabla->_nombre;
	     		}
	     			     		
                        $clavesfor = $laTabla->_primary;
                        foreach($this->getDbTable()->_foreignkey[$table] as $indi => $clave){
                                $on .= 	$aliasFor . '.' . $clavesfor[$indi] . ' = ' . $aliasBas . '.' . $clave . ' AND ';
                        }
                        $largo = strlen($on);
                        $cort = $largo -4 ;
                        $on = substr($on, 0, $cort);
		     	$select->join(array( $aliasFor =>  $esqTab ), $on);
	     	}
     	}
     	if(isset($leftTables)) {
	     	foreach ($leftTables as $table){
	     		
	     		$on ='';
	     		$laTabla = new $table();
	     		$esqTab = $laTabla->_esquema .'.'. $laTabla->_nombre;
	     		if(isset( $laTabla->_alias)){
	     			$aliasFor =  " ". $laTabla->_alias;
	     		}else{
	     			$aliasFor =  " ". $laTabla->_nombre;
	     		}
	     		
	     		
				$clavesfor = $laTabla->_primary;
	    				foreach($this->getDbTable()->_foreignkey[$table] as $indi => $clave){
	    					$on .= 	$aliasFor . '.' . $clavesfor[$indi] . ' = ' . $aliasBas . '.' . $clave . ' AND ';
	    				}
	     				$largo = strlen($on);
	     				$cort = $largo -4 ;
						$on = substr($on, 0, $cort);
		     	$select->joinLeft(array( $aliasFor =>  $esqTab ), $on);
	     	}
     	}        
     	if ($where != null) {foreach ($where as $key => $value) { $select->where($value);}}
        if ($groups != null) {foreach ($groups as $key => $group) {$select->group($group);}}
        if ($orders != null) {foreach ($orders as $key => $order) {$select->order($order);}}
     	$select = str_replace('" ', '"', $select);
        $select = str_replace("` ", "`", $select);        
if ($where != null){        
print_r($select);
die();}
     	return $db->fetchAll($select);	
     }
     
    /**
     * Devuelve un Array de Datos en el ->data['clave']
     * @param array $fields
     * @param array $where
     * @param String $orders
     * @param array $groups 
     * @example $fields = array('IMPUES.IMPSIG','EMPRES.EMPNM','SUCURS.SUCNM');
      			$where = array('nombre_campo', 'valor_campo');
      			$orders = array('EMPRES.EMPNM DESC');
      			$groups = array('SUCURS.SUCNM');
     * @return $rows  => foreach ($rows as $row) {echo $row['EMPNM'];}
     * @copyright Infocomedor S.A.
     * @author Federico Cano
     */
    public function getRowsBySelect ($fields, $where = null, $order = null, 
    $group = null)
    {
        $select = $this->getDbTable()->select();
        $select->from($this->getDbTable(), $fields);
        if ($where != null) {
            foreach ($where as $key => $value) {
                $select->where($value);
            }
        }
        if ($group != null) {
            $select->group($group);
        }
        if ($order != null) {
            $select->order($order);
        }
        //echo '<br>' . $select . '<br>';
        //die();
        return $this->getDbTable()->fetchAll($select);
    }
        /**
     * Obtiene un Registro por el Id de la Tabla,
     * el Id debe ser un Array 
     * Ej. array('CODIGO'=> 1)
     * @param array $id
     * @return ZendExt_Db_Table_Row_Abstract $row 
     * @copyright Infocomedor S.A.
     * @author Federico Cano
     */
    public function getRowById ($id = null)
    {
        if ($id != null) {
        	//print_r($id);
        	//die();
            $laTabla = $this->getDbTable();
            $rowclass = $laTabla->_rowClass;
            $row = new $rowclass();
            $select = $laTabla->select();
            foreach ($laTabla->_primary as $pk) {
                $select->where($pk . ' = ?', $id[$pk]);
            }      
            //echo 'select : '.$select;die();            
            //$row = $laTabla->fetchRow($select);
            $row = $laTabla->fetchAll($select);            
            if ($row instanceof ZendExt_Db_Table_Row_Abstract) {
//                $row->setFromArray($row->toArray());                
//saco esta linea por que da error y no se por que. fede
                return $row;
            } else {
                return false;
            }
        }
    }
    /**
     * Guarda un Registro en la Tabla correspondiente,
     * @param ZendExt_Db_Table_Row_Abstrac $row 
     * @copyright Infocomedor S.A.
     * @author Federico Cano
     */
    public function saveRow ($row)
    {
        $rowClass = $this->getDbTable()->_rowClass;
        $clave1="";
        if ($row instanceof $rowClass) {
            $laTabla = $this->getDbTable();
            $data = $row->toArr();           
            $clave = array();
            $hayClave = false;
            foreach ($laTabla->_primary as $pk) {
                $clave[$pk] = $data[$pk];
                if ($data[$pk] != '') {
                    $hayClave = true;
                }
            }
            if (count($clave) == 0) {
                //INSERT
                //die("entroo");
                $clave1 = $this->insertRow($hayClave, $row);
            } else {
            	//print_r($clave);die();
                //UPDATE    
                //Buscar Registro	
                $rowExsite = null;
                if ($hayClave) {                    
                    $rowExsite = $this->getRowById($clave);
                    //print_r($rowExsite);die();
                }
                if ($rowExsite != null) {
                    //Se Arma el where 
                    $where = null;
                    $where = $this->autoWhere($row);
                    //Elimina los campos clave del array
                    $data = $this->removePk($row);
                    if ($laTabla->_primary_auto) {
                        $clave1 = $laTabla->update($data, $where);
                    } else {
                        $clave1 = $laTabla->update($row->toArr(), $where);
                    }
                } else {
                    $clave1 = $this->insertRow($hayClave, $row);
                }
            }
        }
        return $clave1;
    }
    private function insertRow ($hayClave, $row)
    {
        //echo 'aca entro insertRow';die();        
        $laTabla = $this->getDbTable();
        if ($laTabla->_primary_auto) {
            if ($hayClave) {
                $data = $this->removePk($row);
            } else {
                $data = $row->toArr();
            }
        } else {
            $data = $row->toArr();
        }
        //print_r($data);die();        
        $id = $laTabla->insert($data);
        $laTabla = null;
        return $id;
    }
    private function removePk ($row)
    {
        $laTabla = $this->getDbTable();
        $data = $row->toArr();
        foreach ($laTabla->_primary as $pk) {
            unset($data[$pk]);
        }
        $laTabla = null;
        return $data;
    }
    private function autoWhere ($row)
    {
        $where = array();
        $rowClass = $this->getDbTable()->_rowClass;
        if ($row instanceof $rowClass) {
            $laTabla = $this->getDbTable();
            $data = $row->toArr();
            //print_r($row);
            //die();
            foreach ($laTabla->_primary as $pk) {
                $pkwh = $pk . ' = ?';
                $where[$pkwh] = $data[$pk];
            }
        }
        $laTabla = null;
        return $where;
    }
    /**
     * Obtiene Registros de una Tabla segun un "WHERE" dado
     * @example "IDIOMA_CODIGO >= 10 AND NOMBRE = '" . 'JAPONES'. "'"
     * @param String $where
     * @return array(array()) de Datos
     * @copyright Infocomedor S.A.
     * @author Federico Cano
     */
    public function getRowsByWhere ($where = null)
    {
        $select = $this->getDbTable()->select();
        if ($where != null) {
            $select->where($where);
            return $this->getDbTable()->fetchAll($select)->toArray();
        } else {
            return $this->getAllRows();
        }
    }
    /**
     * Obtiene Todos los Registros de una Tabla 
     * @return array(array()) de Datos
     * @copyright Infocomedor S.A.
     * @author Federico Cano
     */
    public function getAllRows ()
    {
        return $this->getDbTable()
            ->fetchAll()
            ->toArray();
    }
    
 /**
     * Obtiene Todos los Registros de una Tabla ordenados por el campo especificado 
     * @param array de campos por los cuales se ordenaran las filas 
     * @return array(array()) de Datos
     * @copyright Infocomedor S.A.
     * @author Federico Cano
     */
    public function getAllRowsOrdered ($arrayOrder)
    {        
    	$lista = $this->getDbTable()->select()
             	->order($arrayOrder);
        
        return $this->getDbTable()->fetchAll($lista)
            	->toArray();
    }

/**
     * Obtiene Todos los Registros de una Tabla ordenados por el campo especificado y por la condicion recibida
     * @param array de campos por los cuales se ordenaran las filas 
     * @param string condicion 
     * @return array(array()) de Datos
     * @copyright Infocomedor S.A.
     * @author Federico Cano
     */
    public function getAllRowsByWhereOrdered($arrayOrder,$where)
    {
    	$lista = $this->getDbTable()->select()->where($where)
             	->order($arrayOrder);
            
        return $this->getDbTable()->fetchAll($lista)
            	->toArray();
    }
    /**
     * Elimina el Registro de la Tabla Correspondiente
     * @param ZendExt_Db_Table_Row_Abstrac $row 
     * @copyright Infocomedor S.A.
     * @author Federico Cano
     */
    public function deleteRow ($row)
    {
        $laTabla = $this->getDbTable();
        $where = $this->autoWhere($row);
       // print_r($where);
       // die();
        $laTabla->delete($where);
        $laTabla = null;
        $where = null;
    }
    /**
     * Elimina los Registro de la Tabla Correspondiente segun el WHERE
     * @param String $where
     * @example "IDIOMA_CODIGO >= 10 AND NOMBRE = '" . 'JAPONES'. "'"
     * @copyright Infocomedor S.A.
     * @author Federico Cano
     */
    public function deleteRowByWhere ($where)
    {
        $laTabla = $this->getDbTable();
        $laTabla->delete($where);
        $laTabla = null;
        $where = null;
    }
    /**
     * Elimina un Registro por el Id de la Tabla,
     * el Id debe ser un Array 
     * Ej. array('CODIGO'=> 1)
     * @param array $id
     * @return int Cantidad de Filas Borradas
     * @copyright Infocomedor S.A.
     * @author Federico Cano
     */
    public function deleteRowById ($id = null)
    {
        $filasBorradas = 0;
        if ($id != null) {
            $where = array();
            foreach ($this->getDbTable()->_primary as $pk) {
                $pkwh = $pk . ' = ?';
                $where[$pkwh] = $id[$pk];
            }
            $filasBorradas = $this->getDbTable()->delete($where);
        }
        return $filasBorradas;
    }
}