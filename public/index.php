<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
//require_once 'Zend/Application.php';
require_once '../library/Zend/Application.php';
require_once '../library/fpdf/fpdf.php';
require_once '../library/reports/db_operation.php';
require_once '../library/reports/n2t.class.php';
require_once '../library/reports/PDFReportearqueocaja.php';
require_once '../library/reports/PDFReporteclientes.php';
require_once '../library/reports/PDFReportecompras.php';
require_once '../library/reports/PDFReportepagos.php';
require_once '../library/reports/PDFReporteproduccion.php';
require_once '../library/reports/PDFReporteinventario.php';
require_once '../library/reports/PDFReporteinventarioajuste.php';
require_once '../library/reports/PDFReportefacturacion.php';
require_once '../library/reports/PDFReporteegresocaja.php';
require_once '../library/reports/PDFFacturaPY.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();