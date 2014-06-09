<?php

class Produccion_RecetaController extends Zend_Controller_Action {

    public function init() {
        
    }

    public function indexAction() {
        //$this->_helper->viewRenderer->setNoRender ( true );
    }
	
	public function listarAction() {

        $this->_helper->viewRenderer->setNoRender(true);

        $parametrosNamespace = new Zend_Session_Namespace('parametros');
        $parametrosNamespace->unlock();
        $cantidadFilas = $this->getRequest()->getParam("rows");
        if (!isset($cantidadFilas)) {
            $cantidadFilas = 10;
        }
        $parametrosNamespace->cantidadFilas = $cantidadFilas;
        $page = $this->getRequest()->getParam("page");
        if (!isset($page)) {
            $page = 1;
        }

        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('R' => 'RECETA'), array('R.COD_RECETA',
                    'R.RECETA_DESCRIPCION'));
//        die($select);
        $result = $db->fetchAll($select);
        $pagina = self::obtenerPaginas($result, $cantidadFilas, $page);
        echo $this->_helper->json($pagina);
    }
    
    private function obtenerPaginas($result, $cantidadFilas, $page) {
        $this->_paginator = Zend_Paginator::factory($result);
        $this->_paginator->setItemCountPerPage($cantidadFilas);
        $this->_paginator->setCurrentPageNumber($page);
        $pagina ['rows'] = array();
        foreach ($this->_paginator as $item) {
            $arrayDatos ['cell'] = array(
                null,
                $item['COD_RECETA'],
                $item['RECETA_DESCRIPCION']
                
            );
            $arrayDatos ['columns'] = array(
                "modificar",
                "COD_RECETA",
                "RECETA_DESCRIPCION"
            );
            array_push($pagina ['rows'], $arrayDatos);
        }

        if ($cantidadFilas == 0)
            $cantidadFilas = 10;

        $pagina ['records'] = count($result);
        $pagina ['page'] = $page;
        $pagina ['total'] = ceil($pagina ['records'] / $cantidadFilas);

        if ($pagina['records'] == 0) {
            $pagina ['mensajeSinFilas'] = true;
        }
        return $pagina;
    }
    
public function guardarAction() {

//		$this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $dataReceta = json_decode($this->getRequest()->getParam("dataReceta"));
        $dataRecetaDetalle = json_decode($this->getRequest()->getParam("dataRecetaDetalle"));
        $dataRecetaCantItems = json_decode($this->getRequest()->getParam("dataRecetaDetalleItem"));
//    	echo $dataReceta.'hola';
////    	print_r($dataRecetaDetalle);
//    	die();
       try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            if($dataReceta->codigoReceta == 0){
	            $data = array(
	                'COD_RECETA' => $dataReceta->codigoReceta,
	                'RECETA_DESCRIPCION' =>(trim($dataReceta->descripcionReceta))                
	            );
	            $insert = $db->insert('RECETA', $data);
	            $cod_receta = $db->lastInsertId();
            } else {
            	$data = array(
	                'RECETA_DESCRIPCION' =>(trim($dataReceta->descripcionReceta))                
	            );
	           	$where = "COD_RECETA= " . $dataReceta->codigoReceta;
	            $upd = $db->update('RECETA', $data, $where);
	            
             	$cod_receta = $dataReceta->codigoReceta;
             	$borrado = self::borrarfilas($cod_receta,$db);
            }
            if ($cod_receta != null) {
				$i = 0;
                foreach ($dataRecetaDetalle as $fila) {	
                    $i++;
//                    print_r($fila);
//                    die();
                 	$data = array(
		                'COD_RECETA' => $cod_receta,
                 		'RECETA_DET_ITEM' => $i,
		                'COD_PRODUCTO' => $fila->codproducto,
                 		'RECETA_DET_CANTIDAD' => $fila->cantidad
		            );
		            $insertDetalle = $db->insert('RECETA_DETALLE', $data);    
                }
                $db->commit();
             }
             
             echo json_encode(array("result" => "EXITO"));
       } catch (Exception $e) {
       		$db->rollBack();
            echo json_encode(array("result" => "ERROR", "code" => $e->getCode(),"mensaje" => $e->getMessage()));
            
        }
   }
   
   public function borrarfilas($cod_receta,$db){
            if($cod_receta > 0){
				$where= array(
		                'COD_RECETA = ?' => $cod_receta
		            );
				$n = $db->delete('RECETA_DETALLE', $where);
//				 print_r($n); die();
            }
	return $n;
   }
   
	public function modaleditarAction() {
//        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $codigo_receta = json_decode($this->getRequest()->getParam("codigo_receta"));

        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('RD' => 'RECETA_DETALLE'), 
                	   array('RD.COD_RECETA',
		                    'RD.RECETA_DET_ITEM',
		                    'RD.COD_PRODUCTO',
		                    'RD.RECETA_DET_CANTIDAD',
                	   		'P.COD_UNIDAD_MEDIDA',
                	   		'UM.ISO_UNIDAD_MEDIDA'))
                ->join(array('R' => 'RECETA'), 'RD.COD_RECETA = R.COD_RECETA')
                ->join(array('P' => 'PRODUCTO'), 'P.COD_PRODUCTO = RD.COD_PRODUCTO')
                ->join(array('UM' => 'UNIDAD_MEDIDA'), 'UM.COD_UNIDAD_MEDIDA = P.COD_UNIDAD_MEDIDA')
                ->where("R.COD_RECETA = ?", $codigo_receta);
//        print_r($select);
//        die();
        $result = $db->fetchAll($select);
        $option = array();
        foreach ($result as $value) {
			$COD_RECETA = $value ['COD_RECETA'];
			$RECETA_DET_ITEM = $value ['RECETA_DET_ITEM'];
            $codproducto = $value ['COD_PRODUCTO'];
            $descripcionproducto = $value ['PRODUCTO_DESC'];
            $cantidad = $value ['RECETA_DET_CANTIDAD'];
            $codUnidadMedida = $value['COD_UNIDAD_MEDIDA'];
            $unidadmedida = $value['ISO_UNIDAD_MEDIDA'];
            
            $option1 = array("codproducto" => $codproducto, "descripcionproducto" => $descripcionproducto, "cantidad" => $cantidad,
                "codUnidadMedida" => $codUnidadMedida, "unidadmedida" => $unidadmedida, 
                "COD_RECETA" => $COD_RECETA, "RECETA_DET_ITEM" => $RECETA_DET_ITEM);
            array_push($option, $option1);
        }

        echo json_encode($option);
    }
    
public function eliminarAction() {

       $this->_helper->viewRenderer->setNoRender(true);
       $cod_receta = json_decode($this->getRequest()->getParam("id"));
       try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
               	$borrado = self::borrarfilas($cod_receta,$db);
               	$where= array('COD_RECETA = ?' => $cod_receta);
				$n = $db->delete('RECETA', $where);
         	 $db->commit();
             echo json_encode(array("result" => "EXITO"));
       } catch (Exception $e) {
       		$db->rollBack();
            echo json_encode(array("result" => "ERROR", "code" => $e->getCode(),"mensaje" => $e->getMessage()));
            
        }
   }
    

}