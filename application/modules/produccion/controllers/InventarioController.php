<?php

class Produccion_InventarioController extends Zend_Controller_Action {

    public function init() {
         $parametrosNamespace = new Zend_Session_Namespace('parametros');
        $parametrosNamespace->unlock();
            if(!$parametrosNamespace->username){
                $r = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
                $r->gotoUrl('/menus/menu')->redirectAndExit();
            }
        $parametrosNamespace->lock();
    }

    public function indexAction() {
        //$this->_helper->viewRenderer->setNoRender ( true );
    }
	
    
	public function buscarAction() {

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
        $parametrosNamespace->lock();
        $datos = $this->getRequest()->getParam("data");
        $Obj = json_decode($datos);

        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('S' => 'INVENTARIO'), 
                	array(
                	'S.COD_INVENTARIO',
                	'S.COD_PRODUCTO',
                    'P.PRODUCTO_DESC',
                    'TP.TIPO_PRODUCTO_DESCRIPCION',
                	'S.INVENTARIO_FECHA',
					'S.INVENTARIO_ENTRADA',
					'S.INVENTARIO_SALIDA',
					'S.INVENTARIO_SALDO',
                    'UM.DESC_UNIDAD_MEDIDA'))
                ->join(array('P' => 'PRODUCTO'), 'S.COD_PRODUCTO = P.COD_PRODUCTO')
                ->join(array('TP' => 'TIPO_PRODUCTO'), 'P.COD_PRODUCTO_TIPO = TP.COD_TIPO_PRODUCTO')
                ->join(array('UM' => 'UNIDAD_MEDIDA'), 'UM.COD_UNIDAD_MEDIDA = P.COD_UNIDAD_MEDIDA')
                 ->order(array('S.COD_INVENTARIO DESC'));        
                
//        die($select);
				$select->order(array('S.COD_INVENTARIO DESC','S.COD_PRODUCTO'));
		if ($Obj != null) {
            if ($Obj->tipoproducto !== null || $Obj->tipoproducto.length == 0 ) {
                $select->where("upper(TP.TIPO_PRODUCTO_DESCRIPCION) like upper('%".$Obj->tipoproducto."%')");
            }
		 	if ($Obj->producto !== null || $Obj->producto.length == 0 ) {
                $select->where("upper(P.PRODUCTO_DESC) like upper('%".$Obj->producto."%')");
            }
                  
            $result = $db->fetchAll($select);
        } else {
        	
            $result = $db->fetchAll($select);
        }
     
        $pagina = self::obtenerPaginas($result, $cantidadFilas, $page);
        echo $this->_helper->json($pagina);
    }
    public function productodataAction() {
//        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('P' => 'PRODUCTO'))
                ->order(array('P.PRODUCTO_DESC'));
//        print_r($select);die();
        $result = $db->fetchAll($select);

        echo json_encode($result);
    }    
private function obtenerPaginas($result, $cantidadFilas, $page) {
        $this->_paginator = Zend_Paginator::factory($result);
        $this->_paginator->setItemCountPerPage($cantidadFilas);
        $this->_paginator->setCurrentPageNumber($page);
        $pagina ['rows'] = array();
        foreach ($this->_paginator as $item) {
            $arrayDatos ['cell'] = array(
            	null,
                $item['COD_INVENTARIO'],
				$item['COD_PRODUCTO'],
				$item['PRODUCTO_DESC'],
				$item['TIPO_PRODUCTO_DESCRIPCION'],
				$item['INVENTARIO_FECHA'],
				$item['INVENTARIO_ENTRADA'],
				$item['INVENTARIO_SALIDA'],
				$item['INVENTARIO_SALDO'],
				$item['DESC_UNIDAD_MEDIDA']
            );
            $arrayDatos ['columns'] = array(
            	null,
              	"COD_INVENTARIO",
				"COD_PRODUCTO",
				"PRODUCTO_DESC",
				"TIPO_PRODUCTO_DESCRIPCION",
				"INVENTARIO_FECHA",
				"INVENTARIO_ENTRADA",
				"INVENTARIO_SALIDA",
				"INVENTARIO_SALDO",
				"DESC_UNIDAD_MEDIDA"
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
        $data_grilla = json_decode($this->getRequest()->getParam("dataGrilla"));
        $data_inventario = json_decode($this->getRequest()->getParam("inventario"));
       	
        try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            // Recuperamos el ultimo numero de inventario y le sumamos 1
        
	            $select = $db->select()
	                		->from(array('S' => 'INVENTARIO'), array('MAX(S.COD_INVENTARIO)'));
	            $resultado_select = $db->fetchAll($select);
	           	$codigo_inventario = ($resultado_select[0]['MAX(S.COD_INVENTARIO)'])+1;

            $listado = new Zend_Session_Namespace('listado');
        	$listado->unlock();
            $list_data = array();
           	foreach ($data_grilla as $fila) {
           		$fila->saldos=($fila->saldos == '')?0:$fila->saldos;
           		// $fila->diferencia=($fila->diferencia == '')?0:$fila->diferencia;
           		if($data_inventario == null || $data_inventario == 0){
           			$listado->inventario = 0;
	           		$data = array(
			                'COD_INVENTARIO' => $codigo_inventario,
							'COD_PRODUCTO' => $fila->codproducto,
							'INVENTARIO_FECHA'   => date("Y-m-d H:i:s"),
							'INVENTARIO_ENTRADA'  => $fila->saldo,
							'INVENTARIO_SALIDA'  => $fila->saldos,
							'INVENTARIO_SALDO'  => $fila->diferencia
		        	);
					$upd = $db->insert('INVENTARIO', $data);
                    // echo  $upd;
                    $listado->codigo_inventario = $db->lastInsertValue;
           		}else{
           			$listado->inventario = $data_inventario;
           		//	if($fila->saldo < 0){
           		//		$fila->diferencia = $fila->saldo + ($fila->saldos);
           		//	} else {
           				$fila->diferencia = $fila->saldo - ($fila->saldos);
           		//	}
           			$data = array(
			               	'INVENTARIO_SALIDA'  => $fila->saldos,
							'INVENTARIO_SALDO'  => $fila->diferencia
		        	);
		        	$where = 'COD_INVENTARIO = '.$data_inventario.' and COD_PRODUCTO = '.$fila->codproducto;
					$upd = $db->update('INVENTARIO', $data, $where);
           		
           		}
	        	array_push($list_data, $fila);	
            }
           
        	$listado->data = $list_data;
        	$listado->lock();
            $db->commit();
        	echo json_encode(array("result" => "EXITO"));
       } catch (Exception $e) {
       		$db->rollBack();
            echo json_encode(array("result" => "ERROR", "code" => $e->getCode(),"mensaje" => $e->getMessage()));
            
        }
   }
   
   public function printpdfAction() {
   	 	$this->_helper->viewRenderer->setNoRender(true);
     // include auto-loader class
		require_once 'Zend/Loader/Autoloader.php';
	// register auto-loader
		$loader = Zend_Loader_Autoloader::getInstance();

		try {
			// create PDF
			$pdf = new Zend_Pdf();
			
			// create A4 page
			$page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_LETTER);
			
			// define font resource
			$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
			
			//prueba ancho y largo
			$width  = $page->getWidth();
			$height = $page->getHeight();
			
			//Color de la linea
			$page->setLineColor(new Zend_Pdf_Color_Rgb(0,0,0));
			//Linea superior Horizontal
			$page->drawLine(38, $height-38, ($width-38), $height-38);
			//Linea inferior Horizontal
			$page->drawLine(38, 38, ($width-38), 38);
			//left line vertical
			$page->drawLine(38, 38, 38, $height-38);
			//right line vertical
			$page->drawLine($width-38, $height-38, $width-38, 38);
			
			//Tamanho de letra, color, y titulo
			$page->setFont($font, 14)
			->setFillColor(new Zend_Pdf_Color_Rgb(1, 0, 0))
			->drawText('INFOCOMEDOR', 250, $height-75);
			
            
			// Linea bajo el titulo
			$page->drawLine(50, $height-78, ($width-50), $height-78);
			
			$listado = new Zend_Session_Namespace('listado');
			$listado->unlock();
			$y = 100;
			$i=0;
            
            if($listado->codigo_inventario > 0){
                $page->setFont($font, 14)
                ->setFillColor(new Zend_Pdf_Color_Rgb(0, 0, 0))
                ->drawText($listado->codigo_inventario, 450, $height-75);
            }
            if($listado->inventario > 0){
                 $page->setFont($font, 14)
                    ->setFillColor(new Zend_Pdf_Color_Rgb(0, 0, 0))
                    ->drawText($listado->inventario, 450, $height-75);
            }

			// Hacemos la cabecera
			
			$page->setFont($font, 14)
					 ->setFillColor(new Zend_Pdf_Color_Rgb(0, 0, 0))
					 ->drawText('Item', 40, $height-$y)
					 ->drawText('Producto', 90, $height-$y)
					 ->drawText('Inventario', 400, $height-$y);
					 $y = $y+20;
					 
			if($listado->inventario == 0){
				foreach ($listado->data as $fila) {
					$i++;	
					$page->setFont($font, 12)
					 ->setFillColor(new Zend_Pdf_Color_Rgb(0, 0, 0))
					 ->drawText($i.'-', 40, $height-$y)
					 ->drawText($fila->descripcionproducto, 90, $height-$y)
					 ->drawText('_____', 400, $height-$y);
					 $y = $y+20;
				}
			}else{
				$y = $y-20;
				$page->setFont($font, 14)
					 ->setFillColor(new Zend_Pdf_Color_Rgb(0, 0, 0))
					 ->drawText('Existencia', 290, $height-$y)
					 ->drawText('Diferencia', 500, $height-$y);
				$y = $y+20;
				foreach ($listado->data as $fila) {
					$i++;	
					$page->setFont($font, 12)
					 ->setFillColor(new Zend_Pdf_Color_Rgb(0, 0, 0))
					 ->drawText($i.' - ', 40, $height-$y)
					 ->drawText($fila->descripcionproducto, 90, $height-$y)
					 ->drawText($fila->saldo, 290, $height-$y)
					 ->drawText($fila->saldos, 400, $height-$y)
					 ->drawText($fila->diferencia, 500, $height-$y);
					 $y = $y+20;
				}
			
			}
			$listado->lock();
			// add page to document
			$pdf->pages[] = $page;
			$name = 'inventario'. date("Ymd").date("H").date("i").date("s").'.pdf';
	
				foreach($pdf->pages As $key => $page){
					$page->drawText("Page " . ($key+1) . " of " . count($pdf->pages), 260, 50);                      
				}
			header('Content-type: application/pdf');
			$pdf->save($name);
         
			echo json_encode(array("result" => "EXITO","url" => $name));
		} catch (Zend_Pdf_Exception $e) {
			die ('PDF error: ' . $e->getMessage());  
		} catch (Exception $e) {
			die ('Application error: ' . $e->getMessage());    
		}
	}
   
public function modalinventarioAction() {

//		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
        $nro_inventario = $this->getRequest()->getParam("nro_inventario");
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('S' => 'INVENTARIO'), 
                	array(
                	'S.COD_PRODUCTO',
                    'P.PRODUCTO_DESC',
                	'P.COD_UNIDAD_MEDIDA',
                	'UM.ISO_UNIDAD_MEDIDA',
                	'S.INVENTARIO_ENTRADA',
                	'S.INVENTARIO_SALIDA',
                    'S.ESTADO'))
                ->join(array('P' => 'PRODUCTO'), 'S.COD_PRODUCTO = P.COD_PRODUCTO')
                ->join(array('TP' => 'TIPO_PRODUCTO'), 'P.COD_PRODUCTO_TIPO = TP.COD_TIPO_PRODUCTO')
                ->join(array('UM' => 'UNIDAD_MEDIDA'), 'UM.COD_UNIDAD_MEDIDA = P.COD_UNIDAD_MEDIDA');
                $select->where("S.COD_INVENTARIO = ?", $nro_inventario); 
        $result = $db->fetchAll($select);
        $option = array();
        foreach ($result as $value) {
            $value ['INVENTARIO_SALIDA']=($value ['INVENTARIO_SALIDA'] == '')?0:$value ['INVENTARIO_SALIDA'];
            $option1 = array(
                "codproducto" => $value ['COD_PRODUCTO'],
                "descripcionproducto" => $value ['PRODUCTO_DESC'],
                "codUnidadMedida" => $value ['COD_UNIDAD_MEDIDA'],
                "unidadmedida" => $value ['ISO_UNIDAD_MEDIDA'],
                "saldo" => $value ['INVENTARIO_ENTRADA'],
            	"saldos" => $value ['INVENTARIO_SALIDA']
            );
            array_push($option, $option1);
        }

        echo json_encode($option);
   }
   
       public function cargartipoproductoAction()
    {
//      $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $result = '';
        try {
             $db = Zend_Db_Table::getDefaultAdapter();
             $select = $db->select()
                ->from(array('R' => 'TIPO_PRODUCTO'), array('R.COD_TIPO_PRODUCTO', 'R.TIPO_PRODUCTO_DESCRIPCION'))
                ->distinct(true);
            $result = $db->fetchAll($select);
            $htmlResultado = '<option value="0">Seleccione</option>';
            foreach ($result as $arr) {
                $htmlResultado .= '<option value="' . $arr["COD_TIPO_PRODUCTO"] . '">' .
                trim(utf8_encode($arr["TIPO_PRODUCTO_DESCRIPCION"])) . '</option>';
            }
        } catch (Exception $e) {
            $htmlResultado = "error";
        }
        echo $htmlResultado;
    }



       public function cargagrillaproductoAction()
    {
       $this->_helper->viewRenderer->setNoRender(true);

        $cod = $this->getRequest()->getParam("data");
//        die($NumeroInterno);

        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('M' => 'PRODUCTO'), 
                    array(
                        'M.COD_PRODUCTO',
                        'M.PRODUCTO_DESC',
                        'M.COD_UNIDAD_MEDIDA',
                        'T.ISO_UNIDAD_MEDIDA',
                        'M.COD_RECETA',
                        'S.SALDO_STOCK'
                        ))
                ->joinLeft(array('S' => 'STOCK'), 'M.COD_PRODUCTO = S.COD_PRODUCTO')
                ->join(array('T' => 'UNIDAD_MEDIDA'), 'M.COD_UNIDAD_MEDIDA = T.COD_UNIDAD_MEDIDA')
                ->where("M.COD_PRODUCTO_TIPO = ?",$cod);
//        print_r($select);
//        die();
        $result = $db->fetchAll($select);
        $option = array();
        foreach ($result as $value) {
          
            $option1 = array(
                "codproducto"=> $value ['COD_PRODUCTO'],
                "descripcionproducto" => $value ['PRODUCTO_DESC'],
                "codUnidadMedida" => $value ['COD_UNIDAD_MEDIDA'],
                "unidadmedida" => $value ['ISO_UNIDAD_MEDIDA'],
                "COD_RECETA" => $value ['COD_RECETA'],
                "saldo" => $value ['SALDO_STOCK'],
                "saldos" => 0,
            );
            array_push($option, $option1);
        }

        echo json_encode($option);
    }
     public function productvalidationdataAction() {
//        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $datos = $this->getRequest()->getParam("parametro");
        $getvalue = trim($datos["value"]);
        $getreference = $datos["reference"];
        switch ($getreference) {
            case 'cod':
                $where = "P.COD_PRODUCTO=" . $getvalue;
                break;
            case 'descripcion':
                $where = "P.PRODUCTO_DESC= '" . $getvalue . "'";
                break;
        }

        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('P' => 'PRODUCTO'), array('P.COD_PRODUCTO', 'P.PRODUCTO_DESC', 
                                                       'P.COD_UNIDAD_MEDIDA', 'U.ISO_UNIDAD_MEDIDA',
                                                       'S.SALDO_STOCK','P.PRECIO_VENTA'))
                ->distinct(true)
                ->join(array('U' => 'UNIDAD_MEDIDA'), 'P.COD_UNIDAD_MEDIDA = U.COD_UNIDAD_MEDIDA', array())
                ->joinLeft(array('S' => 'STOCK'), 'S.COD_PRODUCTO = P.COD_PRODUCTO', array())
                ->where($where);
        $result = $db->fetchAll($select);

        foreach ($result as $value) {
            $descripcionProducto = utf8_encode(trim($value ['PRODUCTO_DESC']));
            $codProducto = $value ['COD_PRODUCTO'];
            $uniMedidaCod = $value ['COD_UNIDAD_MEDIDA'];
            $uniMedidaDesc = utf8_encode(trim($value ['ISO_UNIDAD_MEDIDA']));
            $saldo_producto = $value ['SALDO_STOCK'];
             $precio_venta = $value ['PRECIO_VENTA'];
            $option = array("cod" => $codProducto, "descripcion" => $descripcionProducto, 
                            "unimedcod" => $uniMedidaCod, "unimeddesc" => $uniMedidaDesc, "saldo" => $saldo_producto, "precioventa" =>$precio_venta );
        }
        echo json_encode($option);
    }

        public function imprimirreporteAction() {
    //        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $json_rowData = $this->getRequest ()->getParam ( "parametros" );
       // die($json_rowData);
       // die();
        //$rowData = json_decode($json_rowData);
        //$nro_caja = $rowData->nro_caja;
        //$curso = $rowData->curso;
        
        $var_nombrearchivo = 'inventario_';
        $path_tmp = './pdfs/';
        $orientation='P';
        $unit='mm';
        $format='A4';
        
        if(!isset($pdf))
          $pdf= new PDFReporteinventario($orientation,$unit,$format,$json_rowData);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->Body($json_rowData);

        $file = basename($var_nombrearchivo."_".date('Ymdhis'));
        $file .= '.pdf';
        //Guardar el PDF en un fichero
        $pdf->Output($path_tmp.$file, 'F');
        $pdf->close();
        unset($pdf);
        echo json_encode(array("result" => "EXITO","archivo" => $file));
       // echo json_encode(array("result" => "EXITO","archivo" => $file));
        //echo "<script>  window.open('".$path_tmp.$file."');  </script>";                      
    }


}