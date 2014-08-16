-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-08-2014 a las 02:48:04
-- Versión del servidor: 5.5.32
-- Versión de PHP: 5.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `infocomedor`
--
CREATE DATABASE IF NOT EXISTS `infocomedor` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `infocomedor`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `baja_stock`
--

CREATE TABLE IF NOT EXISTS `baja_stock` (
  `cod_baja_stock` int(11) NOT NULL AUTO_INCREMENT,
  `cod_producto` int(11) NOT NULL,
  `cod_unidad_medida` int(11) NOT NULL,
  `cantidad_baja` int(11) NOT NULL,
  `fecha_hora_baja` datetime NOT NULL,
  `observacion_mov` varchar(100) DEFAULT NULL,
  `estado` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`cod_baja_stock`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `baja_stock`
--

INSERT INTO `baja_stock` (`cod_baja_stock`, `cod_producto`, `cod_unidad_medida`, `cantidad_baja`, `fecha_hora_baja`, `observacion_mov`, `estado`) VALUES
(4, 62, 5, 1, '2014-08-16 01:54:39', 'descompuesto', 'A'),
(5, 62, 5, 1, '2014-08-16 02:24:22', 'descompuesto', 'A'),
(6, 62, 5, 1, '2014-08-16 02:46:17', 'vencido', 'A');

--
-- Disparadores `baja_stock`
--
DROP TRIGGER IF EXISTS `del_bajastock`;
DELIMITER //
CREATE TRIGGER `del_bajastock` AFTER DELETE ON `baja_stock`
 FOR EACH ROW BEGIN
		UPDATE stock
		SET saldo_stock = saldo_stock + OLD.cantidad_baja
		WHERE cod_producto = OLD.cod_producto;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `ins_bajastock`;
DELIMITER //
CREATE TRIGGER `ins_bajastock` AFTER INSERT ON `baja_stock`
 FOR EACH ROW BEGIN
		UPDATE stock
		SET saldo_stock = saldo_stock - NEW.cantidad_baja
		WHERE cod_producto = NEW.cod_producto;
	END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `upd_bajastock`;
DELIMITER //
CREATE TRIGGER `upd_bajastock` AFTER UPDATE ON `baja_stock`
 FOR EACH ROW UPDATE stock
			SET saldo_stock = saldo_stock + OLD.cantidad_baja
			WHERE cod_producto = OLD.cod_producto
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja`
--

CREATE TABLE IF NOT EXISTS `caja` (
  `cod_caja` int(11) NOT NULL AUTO_INCREMENT,
  `cod_usuario_caja` int(11) NOT NULL,
  `fecha_hora_apertura` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_hora_cierre` timestamp NULL DEFAULT NULL,
  `monto_caja_apertura` int(15) NOT NULL,
  `monto_caja_cierre` int(15) DEFAULT NULL,
  `monto_diferencia_arqueo` int(15) DEFAULT NULL,
  `arqueo_caja` varchar(1) DEFAULT NULL,
  `monto_caja_cierre_cheque` int(15) DEFAULT NULL,
  `monto_diferencia_arqueo_cheque` int(15) DEFAULT NULL,
  PRIMARY KEY (`cod_caja`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Volcado de datos para la tabla `caja`
--

INSERT INTO `caja` (`cod_caja`, `cod_usuario_caja`, `fecha_hora_apertura`, `fecha_hora_cierre`, `monto_caja_apertura`, `monto_caja_cierre`, `monto_diferencia_arqueo`, `arqueo_caja`, `monto_caja_cierre_cheque`, `monto_diferencia_arqueo_cheque`) VALUES
(27, 5, '2014-08-02 23:36:35', '2014-08-02 12:55:03', 200000, 290000, -498850, 'S', 4500, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE IF NOT EXISTS `cliente` (
  `COD_CLIENTE` int(11) NOT NULL AUTO_INCREMENT,
  `CLIENTE_DES` varchar(45) NOT NULL,
  `CLIENTE_RUC` varchar(20) NOT NULL,
  `CLIENTE_DIRECCION` varchar(45) NOT NULL,
  `CLIENTE_TELEFONO` varchar(20) NOT NULL,
  `CLIENTE_EMAIL` varchar(45) NOT NULL,
  `COD_EMPRESA` int(11) NOT NULL,
  PRIMARY KEY (`COD_CLIENTE`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`COD_CLIENTE`, `CLIENTE_DES`, `CLIENTE_RUC`, `CLIENTE_DIRECCION`, `CLIENTE_TELEFONO`, `CLIENTE_EMAIL`, `COD_EMPRESA`) VALUES
(5, 'IVAN GOMEZ', '4048560-9', 'AZZARA CASI CHILE', '0981972342', 'ivan@ivan.com', 0),
(6, 'CONSUMIDOR FINAL', '4444444-7', 'CONSUMIDOR FINAL', '0', '0', 0),
(7, 'RAMON ACOSTA FILIP', '987107-9', 'ETTIENE CASI PROFESOR MILCIADES', '021515007', 'ramon@gmail.com', 0),
(8, 'ALFONSO CABRERA', '80059317-0', 'YPANE 234 ZONA NORTE', '0981254362', '-', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE IF NOT EXISTS `compra` (
  `COD_PROVEEDOR` int(11) NOT NULL,
  `NRO_FACTURA_COMPRA` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Numero correlativo interno',
  `FECHA_EMISION_FACTURA` date NOT NULL,
  `FECHA_VENCIMIENTO_FACTURA` date NOT NULL,
  `MONTO_TOTAL_COMPRA` decimal(15,2) NOT NULL,
  `COD_MONEDA_COMPRA` int(11) NOT NULL,
  `COD_FORMA_PAGO` int(11) NOT NULL,
  `COD_USUARIO` int(11) NOT NULL,
  `CONTROL_FISCAL` varchar(45) NOT NULL,
  `ESTADO` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`NRO_FACTURA_COMPRA`),
  KEY `COD_PROVEEDOR` (`COD_PROVEEDOR`),
  KEY `COD_MONEDA_COMPRA` (`COD_MONEDA_COMPRA`),
  KEY `COD_FORMA_PAGO` (`COD_FORMA_PAGO`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=48 ;

--
-- Volcado de datos para la tabla `compra`
--

INSERT INTO `compra` (`COD_PROVEEDOR`, `NRO_FACTURA_COMPRA`, `FECHA_EMISION_FACTURA`, `FECHA_VENCIMIENTO_FACTURA`, `MONTO_TOTAL_COMPRA`, `COD_MONEDA_COMPRA`, `COD_FORMA_PAGO`, `COD_USUARIO`, `CONTROL_FISCAL`, `ESTADO`) VALUES
(16, 46, '2014-08-02', '2014-08-02', '444850.00', 1, 1, 1, '001-001-1000999', 'T'),
(14, 47, '2014-08-02', '2014-08-02', '50000.00', 1, 1, 1, '001-001-0000076', 'T');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_detalle`
--

CREATE TABLE IF NOT EXISTS `compra_detalle` (
  `NRO_FACTURA_COMPRA` int(11) NOT NULL,
  `DET_ITEM_COMPRA` int(11) NOT NULL,
  `COD_PRODUCTO_ITEM` int(11) DEFAULT NULL,
  `CANTIDAD_COMPRA` decimal(15,2) NOT NULL,
  `MONTO_COMPRA` decimal(15,2) NOT NULL,
  `COD_UNIDAD_MEDIDA` int(11) NOT NULL,
  PRIMARY KEY (`NRO_FACTURA_COMPRA`,`DET_ITEM_COMPRA`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `compra_detalle`
--

INSERT INTO `compra_detalle` (`NRO_FACTURA_COMPRA`, `DET_ITEM_COMPRA`, `COD_PRODUCTO_ITEM`, `CANTIDAD_COMPRA`, `MONTO_COMPRA`, `COD_UNIDAD_MEDIDA`) VALUES
(46, 1, 52, '10.00', '250000.00', 2),
(46, 2, 54, '24.00', '12000.00', 1),
(46, 3, 53, '2.00', '4000.00', 2),
(46, 4, 62, '10.00', '170000.00', 5),
(46, 5, 55, '1.00', '1100.00', 2),
(46, 6, 56, '0.00', '5250.00', 2),
(46, 7, 57, '0.00', '2500.00', 2),
(47, 1, 61, '2.00', '50000.00', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_impuesto`
--

CREATE TABLE IF NOT EXISTS `compra_impuesto` (
  `NRO_FACTURA_COMPRA` int(11) NOT NULL,
  `DET_ITEM_IMPUESTO` int(11) NOT NULL,
  `COD_IMPUESTO` int(11) NOT NULL,
  `MONTO_IMPUESTO` decimal(15,2) NOT NULL,
  PRIMARY KEY (`NRO_FACTURA_COMPRA`,`DET_ITEM_IMPUESTO`),
  KEY `FK_COMPRACOMIMP_idx` (`NRO_FACTURA_COMPRA`),
  KEY `COD_IMPUESTO` (`COD_IMPUESTO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `compra_impuesto`
--

INSERT INTO `compra_impuesto` (`NRO_FACTURA_COMPRA`, `DET_ITEM_IMPUESTO`, `COD_IMPUESTO`, `MONTO_IMPUESTO`) VALUES
(46, 1, 10, '22727.00'),
(46, 2, 10, '1090.00'),
(46, 3, 10, '363.00'),
(46, 4, 10, '15454.00'),
(46, 5, 10, '100.00'),
(46, 6, 10, '477.00'),
(46, 7, 10, '227.00'),
(47, 1, 10, '4545.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `concepto`
--

CREATE TABLE IF NOT EXISTS `concepto` (
  `COD_CONCEPTO` int(11) NOT NULL,
  `DS_CONCEPTO` varchar(45) NOT NULL,
  `CONCEPTO_ACCION` char(1) NOT NULL,
  PRIMARY KEY (`COD_CONCEPTO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE IF NOT EXISTS `empresa` (
  `COD_EMPRESA` int(11) NOT NULL AUTO_INCREMENT,
  `DES_EMPRESA` varchar(45) NOT NULL,
  `EMP_RUC` varchar(11) NOT NULL,
  `EMP_DIRECCION` varchar(45) NOT NULL,
  `EMP_TELEFONO` varchar(12) NOT NULL,
  `EMP_NOMBRE_CONTAC` varchar(45) NOT NULL,
  PRIMARY KEY (`COD_EMPRESA`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadopedido`
--

CREATE TABLE IF NOT EXISTS `estadopedido` (
  `COD_ESTADO` int(11) NOT NULL,
  `DS_ESTADO` varchar(45) NOT NULL,
  `SIG_ESTADO` char(3) NOT NULL,
  PRIMARY KEY (`COD_ESTADO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE IF NOT EXISTS `factura` (
  `FAC_NRO` int(11) NOT NULL AUTO_INCREMENT,
  `COD_CLIENTE` int(11) NOT NULL,
  `FAC_FECHA_EMI` date NOT NULL,
  `FAC_MES` int(11) NOT NULL,
  `FAC_ANO` int(11) NOT NULL,
  `FAC_FECH_VTO` date NOT NULL,
  `FAC_MONTO_TOTAL` int(11) NOT NULL,
  `ESTADO` varchar(2) NOT NULL,
  `CONTROL_FISCAL` varchar(20) NOT NULL,
  PRIMARY KEY (`FAC_NRO`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=85 ;

--
-- Volcado de datos para la tabla `factura`
--

INSERT INTO `factura` (`FAC_NRO`, `COD_CLIENTE`, `FAC_FECHA_EMI`, `FAC_MES`, `FAC_ANO`, `FAC_FECH_VTO`, `FAC_MONTO_TOTAL`, `ESTADO`, `CONTROL_FISCAL`) VALUES
(80, 6, '2014-08-02', 20, 2, '2014-08-02', 13500, 'C', '001-001-0000001'),
(81, 7, '2014-08-02', 20, 2, '2014-08-02', 13500, 'C', '001-001-0000002'),
(82, 5, '2014-08-02', 20, 2, '2014-08-02', 4500, 'C', '001-001-0000003'),
(83, 8, '2014-08-02', 20, 2, '2014-08-02', 9000, 'C', '001-001-0000004'),
(84, 6, '2014-08-02', 20, 2, '2014-08-02', 9000, 'A', '001-001-0000005');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_detalle`
--

CREATE TABLE IF NOT EXISTS `factura_detalle` (
  `FAC_NRO` int(11) NOT NULL,
  `FAC_DET_ITEM` int(11) NOT NULL,
  `COD_PRODUCTO` int(11) NOT NULL,
  `FAC_DET_CANTIDAD` float(11,2) NOT NULL,
  `FAC_DET_TOTAL` int(11) NOT NULL,
  PRIMARY KEY (`FAC_NRO`,`FAC_DET_ITEM`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `factura_detalle`
--

INSERT INTO `factura_detalle` (`FAC_NRO`, `FAC_DET_ITEM`, `COD_PRODUCTO`, `FAC_DET_CANTIDAD`, `FAC_DET_TOTAL`) VALUES
(78, 2, 50, 0.50, 2500),
(79, 1, 50, 1.00, 5000),
(79, 2, 51, 0.50, 5000),
(80, 1, 63, 0.30, 13500),
(81, 1, 64, 0.15, 4500),
(81, 2, 63, 0.20, 9000),
(82, 1, 64, 0.15, 4500),
(83, 1, 63, 0.20, 9000),
(84, 1, 63, 0.20, 9000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_impuesto`
--

CREATE TABLE IF NOT EXISTS `factura_impuesto` (
  `FAC_NRO` int(11) NOT NULL,
  `FAC_IMPUESTO_ITEM` int(11) NOT NULL,
  `COD_IMPUESTO` int(11) NOT NULL,
  `FACT_IMP_MONTO` int(11) NOT NULL,
  PRIMARY KEY (`FAC_NRO`,`FAC_IMPUESTO_ITEM`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `factura_impuesto`
--

INSERT INTO `factura_impuesto` (`FAC_NRO`, `FAC_IMPUESTO_ITEM`, `COD_IMPUESTO`, `FACT_IMP_MONTO`) VALUES
(78, 2, 10, 227),
(79, 1, 10, 454),
(79, 2, 10, 454),
(80, 1, 10, 1227),
(81, 1, 10, 409),
(81, 2, 10, 818),
(82, 1, 10, 409),
(83, 1, 10, 818),
(84, 1, 10, 818);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `forma_pago`
--

CREATE TABLE IF NOT EXISTS `forma_pago` (
  `COD_FORMA_PAGO` int(11) NOT NULL AUTO_INCREMENT,
  `DES_FORMA_PAGO` varchar(45) NOT NULL,
  `FORMA_PAGO_SIGLA` char(2) NOT NULL,
  PRIMARY KEY (`COD_FORMA_PAGO`),
  UNIQUE KEY `FORM_PAGO_SIGLA_UNIQUE` (`FORMA_PAGO_SIGLA`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `forma_pago`
--

INSERT INTO `forma_pago` (`COD_FORMA_PAGO`, `DES_FORMA_PAGO`, `FORMA_PAGO_SIGLA`) VALUES
(1, 'Contado', 'CH'),
(2, 'Credito', 'EF');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impuesto`
--

CREATE TABLE IF NOT EXISTS `impuesto` (
  `COD_IMPUESTO` int(11) NOT NULL,
  `DES_IMPUESTO` varchar(45) NOT NULL,
  `IMP_SIGLA` varchar(10) NOT NULL,
  `IMP_PORCENTAJE` int(11) NOT NULL,
  PRIMARY KEY (`COD_IMPUESTO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `impuesto`
--

INSERT INTO `impuesto` (`COD_IMPUESTO`, `DES_IMPUESTO`, `IMP_SIGLA`, `IMP_PORCENTAJE`) VALUES
(1, 'Exento', 'EX', 0),
(5, 'Impuesto al Valor agregado 5%', 'IVA5', 5),
(10, 'Impuesto al Valor agregado 10%', 'IVA10', 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE IF NOT EXISTS `inventario` (
  `COD_INVENTARIO` int(11) NOT NULL,
  `COD_PRODUCTO` int(11) NOT NULL,
  `INVENTARIO_FECHA` date NOT NULL,
  `INVENTARIO_ENTRADA` decimal(15,2) DEFAULT NULL,
  `INVENTARIO_SALIDA` decimal(15,2) DEFAULT NULL,
  `INVENTARIO_SALDO` decimal(15,2) DEFAULT NULL,
  `ESTADO` varchar(1) DEFAULT 'N',
  PRIMARY KEY (`COD_INVENTARIO`,`COD_PRODUCTO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`COD_INVENTARIO`, `COD_PRODUCTO`, `INVENTARIO_FECHA`, `INVENTARIO_ENTRADA`, `INVENTARIO_SALIDA`, `INVENTARIO_SALDO`, `ESTADO`) VALUES
(1, 63, '2014-08-02', '1.30', '1.00', '0.30', 'S'),
(1, 64, '2014-08-02', '0.70', '0.60', '0.10', 'S'),
(2, 63, '2014-08-02', '1.00', '0.00', NULL, 'N'),
(2, 64, '2014-08-02', '0.60', '0.00', NULL, 'N');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `karrito`
--

CREATE TABLE IF NOT EXISTS `karrito` (
  `COD_KARRITO` int(11) NOT NULL AUTO_INCREMENT,
  `KAR_FECH_MOV` date NOT NULL,
  `COD_CLIENTE` int(11) NOT NULL,
  `COD_MESA` int(11) NOT NULL,
  `COD_PRODUCTO` int(11) NOT NULL,
  `KAR_CANT_PRODUCTO` decimal(11,4) NOT NULL,
  `KAR_CANT_FACTURAR` decimal(11,4) NOT NULL,
  `KAR_PRECIO_PRODUCTO` decimal(11,4) NOT NULL,
  `KAR_PRECIO_FACTURAR` decimal(11,4) NOT NULL,
  `COD_IMPUESTO` int(11) NOT NULL,
  `MONTO_IMPUESTO` int(11) NOT NULL,
  `COD_MOZO` int(11) NOT NULL,
  `FACT_NRO` int(11) NOT NULL,
  `ESTADO` varchar(2) NOT NULL,
  PRIMARY KEY (`COD_KARRITO`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=55 ;

--
-- Volcado de datos para la tabla `karrito`
--

INSERT INTO `karrito` (`COD_KARRITO`, `KAR_FECH_MOV`, `COD_CLIENTE`, `COD_MESA`, `COD_PRODUCTO`, `KAR_CANT_PRODUCTO`, `KAR_CANT_FACTURAR`, `KAR_PRECIO_PRODUCTO`, `KAR_PRECIO_FACTURAR`, `COD_IMPUESTO`, `MONTO_IMPUESTO`, `COD_MOZO`, `FACT_NRO`, `ESTADO`) VALUES
(51, '2014-08-02', 0, 1, 63, '0.4000', '0.0000', '18000.0000', '0.0000', 10, 818, 1, 84, 'AN'),
(52, '2014-08-02', 7, 0, 64, '0.3000', '0.0000', '9000.0000', '0.0000', 10, 409, 1, 82, 'PA'),
(53, '2014-08-02', 7, 0, 63, '0.2000', '0.0000', '9000.0000', '0.0000', 10, 818, 1, 81, 'PA'),
(54, '2014-08-02', 6, 1, 63, '0.3000', '0.3000', '13500.0000', '13500.0000', 10, 1227, 1, 80, 'PA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesa`
--

CREATE TABLE IF NOT EXISTS `mesa` (
  `COD_MESA` int(11) NOT NULL AUTO_INCREMENT,
  `DES_MESA` varchar(45) NOT NULL,
  PRIMARY KEY (`COD_MESA`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `mesa`
--

INSERT INTO `mesa` (`COD_MESA`, `DES_MESA`) VALUES
(1, 'Mostrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `moneda`
--

CREATE TABLE IF NOT EXISTS `moneda` (
  `COD_MONEDA` int(11) NOT NULL,
  `DESC_MONEDA` varchar(45) NOT NULL,
  `ISO_MONEDA` varchar(5) NOT NULL,
  PRIMARY KEY (`COD_MONEDA`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `moneda`
--

INSERT INTO `moneda` (`COD_MONEDA`, `DESC_MONEDA`, `ISO_MONEDA`) VALUES
(1, 'Guaranies', 'GS');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mov_caja`
--

CREATE TABLE IF NOT EXISTS `mov_caja` (
  `cod_mov_caja` int(11) NOT NULL AUTO_INCREMENT,
  `cod_caja` int(11) NOT NULL,
  `fecha_hora_mov` datetime NOT NULL,
  `monto_mov` int(15) NOT NULL,
  `cod_tipo_mov` int(15) NOT NULL,
  `factura_mov` int(15) NOT NULL,
  `tipo_factura_mov` varchar(1) DEFAULT NULL,
  `observacion_mov` varchar(100) DEFAULT NULL,
  `tipo_mov` varchar(10) NOT NULL COMMENT 'CHEQUE-EFECTIVO',
  `firmante_mov` varchar(100) DEFAULT NULL,
  `estado` varchar(1) DEFAULT 'T',
  PRIMARY KEY (`cod_mov_caja`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=134 ;

--
-- Volcado de datos para la tabla `mov_caja`
--

INSERT INTO `mov_caja` (`cod_mov_caja`, `cod_caja`, `fecha_hora_mov`, `monto_mov`, `cod_tipo_mov`, `factura_mov`, `tipo_factura_mov`, `observacion_mov`, `tipo_mov`, `firmante_mov`, `estado`) VALUES
(126, 27, '2014-08-02 07:42:37', 300000, 4, 46, 'C', 'Tiene Vuelto: 0', 'EFECTIVO', 'RAMON ACOSTA', 'T'),
(127, 27, '2014-08-02 19:44:34', 144850, 1, 46, 'C', 'Pago Factura Compra: 46', 'EFECTIVO', NULL, 'T'),
(128, 27, '2014-08-02 20:04:12', 13500, 2, 80, 'V', 'Factura Venta: 80', 'EFECTIVO', NULL, 'T'),
(129, 27, '2014-08-02 20:06:03', 13500, 2, 81, 'V', 'Factura Venta: 81', 'EFECTIVO', NULL, 'T'),
(130, 27, '2014-08-02 20:07:48', 4500, 2, 82, 'V', 'Factura Venta: 82', 'EFECTIVO', NULL, 'T'),
(131, 27, '2014-08-02 20:10:33', 4500, 2, 83, 'V', 'Factura Venta: 83', 'CHEQUE', NULL, 'T'),
(132, 27, '2014-08-02 20:10:33', 4500, 2, 83, 'V', 'Factura Venta: 83', 'EFECTIVO', NULL, 'T'),
(133, 27, '2014-08-02 20:12:03', 9000, 2, 84, 'V', 'Factura Venta: 84', 'EFECTIVO', NULL, 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago_cliente`
--

CREATE TABLE IF NOT EXISTS `pago_cliente` (
  `COD_PAGO_CLIENTE` int(11) NOT NULL AUTO_INCREMENT,
  `FAC_NRO` int(11) NOT NULL,
  `MONTO_PAGO` decimal(15,2) NOT NULL,
  `NRO_CHEQUE` int(11) DEFAULT NULL,
  `DES_BANCO` varchar(45) DEFAULT NULL,
  `ESTADO_PAGO` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`COD_PAGO_CLIENTE`),
  KEY `FAC_NRO` (`FAC_NRO`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Volcado de datos para la tabla `pago_cliente`
--

INSERT INTO `pago_cliente` (`COD_PAGO_CLIENTE`, `FAC_NRO`, `MONTO_PAGO`, `NRO_CHEQUE`, `DES_BANCO`, `ESTADO_PAGO`) VALUES
(12, 80, '13500.00', 0, '0', 'T'),
(13, 81, '13500.00', 0, '0', 'T'),
(14, 82, '4500.00', 0, '0', 'T'),
(15, 83, '4500.00', 9009, 'ITAU', 'T'),
(16, 83, '4500.00', 0, '0', 'T'),
(17, 84, '9000.00', 0, '0', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago_proveedor`
--

CREATE TABLE IF NOT EXISTS `pago_proveedor` (
  `COD_PAGO_PROVEEDOR` int(11) NOT NULL AUTO_INCREMENT,
  `NRO_FACTURA_COMPRA` int(11) NOT NULL,
  `MONTO_PAGO` decimal(15,2) NOT NULL,
  `COD_MONEDA_PAGO` int(11) NOT NULL,
  `NRO_CHEQUE` int(11) DEFAULT NULL,
  `DES_BANCO` varchar(45) DEFAULT NULL,
  `ESTADO_PAGO` varchar(1) DEFAULT NULL,
  `COD_CAJA` int(11) DEFAULT NULL,
  `COD_MOV_CAJA` int(10) DEFAULT NULL,
  PRIMARY KEY (`COD_PAGO_PROVEEDOR`),
  KEY `NRO_FACTURA_COMPRA` (`NRO_FACTURA_COMPRA`),
  KEY `COD_MONEDA_PAGO` (`COD_MONEDA_PAGO`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=94 ;

--
-- Volcado de datos para la tabla `pago_proveedor`
--

INSERT INTO `pago_proveedor` (`COD_PAGO_PROVEEDOR`, `NRO_FACTURA_COMPRA`, `MONTO_PAGO`, `COD_MONEDA_PAGO`, `NRO_CHEQUE`, `DES_BANCO`, `ESTADO_PAGO`, `COD_CAJA`, `COD_MOV_CAJA`) VALUES
(91, 46, '300000.00', 1, 0, '-', 'A', 27, 126),
(92, 46, '144850.00', 1, 0, '-', 'T', 27, 0),
(93, 46, '300000.00', 1, 0, '-', 'T', 27, 126);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE IF NOT EXISTS `producto` (
  `COD_PRODUCTO` int(11) NOT NULL AUTO_INCREMENT,
  `COD_IMPUESTO` int(11) NOT NULL,
  `PRECIO_VENTA` decimal(12,2) NOT NULL,
  `PRODUCTO_DESC` varchar(45) NOT NULL,
  `COD_PRODUCTO_TIPO` int(11) NOT NULL,
  `COD_UNIDAD_MEDIDA` int(11) NOT NULL,
  `COD_RECETA` int(11) NOT NULL,
  PRIMARY KEY (`COD_PRODUCTO`),
  KEY `fk_product_tipoproduto` (`COD_PRODUCTO_TIPO`),
  KEY `fk_producto_unidadmedida` (`COD_UNIDAD_MEDIDA`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=65 ;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`COD_PRODUCTO`, `COD_IMPUESTO`, `PRECIO_VENTA`, `PRODUCTO_DESC`, `COD_PRODUCTO_TIPO`, `COD_UNIDAD_MEDIDA`, `COD_RECETA`) VALUES
(52, 10, '0.00', 'CARNE BOLA DE LOMO', 1, 2, 0),
(53, 10, '0.00', 'PAN RAYADO', 1, 2, 0),
(54, 10, '0.00', 'HUEVO', 1, 1, 0),
(55, 10, '0.00', 'PEREJIL', 1, 2, 0),
(56, 10, '0.00', 'MOSTAZA', 1, 2, 0),
(57, 10, '0.00', 'SAL', 1, 2, 0),
(58, 10, '0.00', 'ACEITE', 1, 5, 0),
(61, 10, '0.00', 'CHORIZO PICANTE', 1, 2, 0),
(62, 10, '0.00', 'ACEITE DE OLIVA', 1, 5, 0),
(63, 10, '45000.00', 'MILANESA DE CARNE POR KG', 2, 2, 16),
(64, 10, '30000.00', 'CHORIZO CON HUEVO POR KG', 2, 2, 17);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE IF NOT EXISTS `proveedor` (
  `COD_PROVEEDOR` int(11) NOT NULL AUTO_INCREMENT,
  `PROVEEDOR_NOMBRE` varchar(45) NOT NULL,
  `PROVEEDOR_RUC` varchar(45) NOT NULL,
  `PROVEEDOR_DIRECCION` varchar(45) NOT NULL,
  `PROVEEDOR_TELEFONO` varchar(12) NOT NULL,
  `PROVEEDOR_CONTACTO` varchar(45) NOT NULL,
  `PROVEEDOR_EMAIL` varchar(45) NOT NULL,
  `PROVEEDOR_LIMITE_CREDITO` int(12) NOT NULL,
  PRIMARY KEY (`COD_PROVEEDOR`),
  UNIQUE KEY `PROVEEDOR_RUC_UNIQUE` (`PROVEEDOR_RUC`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`COD_PROVEEDOR`, `PROVEEDOR_NOMBRE`, `PROVEEDOR_RUC`, `PROVEEDOR_DIRECCION`, `PROVEEDOR_TELEFONO`, `PROVEEDOR_CONTACTO`, `PROVEEDOR_EMAIL`, `PROVEEDOR_LIMITE_CREDITO`) VALUES
(14, 'CASA GRUTTER', '80000101-9', 'MERCADO DE ABASTO', '555011', 'LUIS GRUTTER', 'grutter@grutter.com', 0),
(15, 'CASA RICA  SA', '8000111-4', 'ESPANA CASI BRASILIA', '021660770', 'LUIS GIMENEZ', 'casa@rica.com.py', 0),
(16, 'SUPERMERCADO STOCK', '80001002-1', 'RCA ARGENTINA CASI PILAR', '021444001', 'LIZ ARCE', 'stock@stock.com', 0),
(18, 'CERVEPAR', '80000001-9', 'YPANE', '901001', 'ELIANA FERREIRA', 'cervepar@cervepar.com', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receta`
--

CREATE TABLE IF NOT EXISTS `receta` (
  `COD_RECETA` int(11) NOT NULL AUTO_INCREMENT,
  `RECETA_DESCRIPCION` varchar(45) NOT NULL,
  PRIMARY KEY (`COD_RECETA`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Volcado de datos para la tabla `receta`
--

INSERT INTO `receta` (`COD_RECETA`, `RECETA_DESCRIPCION`) VALUES
(16, 'MILANESA DE CARNE POR KILOGRAMO'),
(17, 'CHORIZO CON HUEVO POR KILOGRAMO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receta_detalle`
--

CREATE TABLE IF NOT EXISTS `receta_detalle` (
  `COD_RECETA` int(11) NOT NULL,
  `RECETA_DET_ITEM` int(11) NOT NULL,
  `COD_PRODUCTO` int(11) NOT NULL,
  `RECETA_DET_CANTIDAD` decimal(15,2) NOT NULL,
  PRIMARY KEY (`COD_RECETA`,`RECETA_DET_ITEM`),
  KEY `fk_RECETA_DETALLE_PRODUCTO` (`COD_PRODUCTO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `receta_detalle`
--

INSERT INTO `receta_detalle` (`COD_RECETA`, `RECETA_DET_ITEM`, `COD_PRODUCTO`, `RECETA_DET_CANTIDAD`) VALUES
(16, 1, 52, '0.90'),
(16, 2, 53, '0.50'),
(16, 3, 54, '3.00'),
(16, 4, 55, '0.10'),
(16, 5, 56, '0.10'),
(16, 6, 57, '0.01'),
(16, 7, 58, '0.10'),
(17, 1, 54, '2.00'),
(17, 2, 61, '0.50'),
(17, 3, 57, '0.10'),
(17, 4, 58, '0.20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recibo`
--

CREATE TABLE IF NOT EXISTS `recibo` (
  `REC_COD_FACTURA` int(11) NOT NULL,
  `REC_NUMERO` int(11) NOT NULL,
  `REC_MONTO` int(11) NOT NULL,
  `REC_COD_FOR_PAG` int(11) NOT NULL,
  `REC_NRO_CHEQUE` int(11) NOT NULL,
  `REC_DES_TARJETA_CRED` varchar(45) NOT NULL,
  `REC_NRO_TARJETA` int(11) NOT NULL,
  PRIMARY KEY (`REC_COD_FACTURA`,`REC_NUMERO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock`
--

CREATE TABLE IF NOT EXISTS `stock` (
  `COD_PRODUCTO` int(11) NOT NULL,
  `SALDO_STOCK` decimal(15,2) NOT NULL,
  `STOCK_FECHA_ACTUALIZA` date NOT NULL,
  PRIMARY KEY (`COD_PRODUCTO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `stock`
--

INSERT INTO `stock` (`COD_PRODUCTO`, `SALDO_STOCK`, `STOCK_FECHA_ACTUALIZA`) VALUES
(52, '8.20', '2014-08-02'),
(53, '1.00', '2014-08-02'),
(54, '16.00', '2014-08-02'),
(55, '0.80', '2014-08-02'),
(56, '0.55', '2014-08-02'),
(57, '0.38', '2014-08-02'),
(58, '-0.40', '2014-08-02'),
(61, '1.50', '2014-08-02'),
(62, '16.00', '2014-08-02'),
(63, '1.00', '2014-08-02'),
(64, '0.60', '2014-08-02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_cambio`
--

CREATE TABLE IF NOT EXISTS `tipo_cambio` (
  `COD_CAMBIO` int(11) NOT NULL,
  `COD_MONEDA_DE` int(11) NOT NULL,
  `COD_MONEDA_A` int(11) NOT NULL,
  `CAMBIO_COMPRA` decimal(15,2) NOT NULL,
  `CAMBIO_VENTA` decimal(15,2) NOT NULL,
  `FECHA_HORA_CAMBIO` datetime NOT NULL,
  PRIMARY KEY (`COD_CAMBIO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_movimiento`
--

CREATE TABLE IF NOT EXISTS `tipo_movimiento` (
  `cod_tipo_mov` int(11) NOT NULL AUTO_INCREMENT,
  `desc_tipo_mov` varchar(100) DEFAULT NULL,
  `tipo_mov` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`cod_tipo_mov`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `tipo_movimiento`
--

INSERT INTO `tipo_movimiento` (`cod_tipo_mov`, `desc_tipo_mov`, `tipo_mov`) VALUES
(1, 'EGRESO PAGO', 'R'),
(2, 'INGRESO VENTAS', 'S'),
(3, 'VUELTOS PAGOS', 'S'),
(4, 'EGRESOS COMPRAS', 'R');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_producto`
--

CREATE TABLE IF NOT EXISTS `tipo_producto` (
  `COD_TIPO_PRODUCTO` int(11) NOT NULL AUTO_INCREMENT,
  `TIPO_PRODUCTO_DESCRIPCION` varchar(30) NOT NULL,
  PRIMARY KEY (`COD_TIPO_PRODUCTO`),
  KEY `COD_TIPO_PRODUCTO` (`COD_TIPO_PRODUCTO`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `tipo_producto`
--

INSERT INTO `tipo_producto` (`COD_TIPO_PRODUCTO`, `TIPO_PRODUCTO_DESCRIPCION`) VALUES
(1, 'MATERIA PRIMA'),
(2, 'CONSUMO FINAL');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad_medida`
--

CREATE TABLE IF NOT EXISTS `unidad_medida` (
  `COD_UNIDAD_MEDIDA` int(11) NOT NULL AUTO_INCREMENT,
  `DESC_UNIDAD_MEDIDA` varchar(45) NOT NULL,
  `ISO_UNIDAD_MEDIDA` varchar(45) NOT NULL,
  PRIMARY KEY (`COD_UNIDAD_MEDIDA`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `unidad_medida`
--

INSERT INTO `unidad_medida` (`COD_UNIDAD_MEDIDA`, `DESC_UNIDAD_MEDIDA`, `ISO_UNIDAD_MEDIDA`) VALUES
(1, 'Unidad', 'Uni'),
(2, 'kilogramo', 'Kg'),
(5, 'Litro', 'Lts');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `COD_USUARIO` int(11) NOT NULL AUTO_INCREMENT,
  `ID_USUARIO` varchar(45) NOT NULL,
  `NOMBRE_APELLIDO` varchar(45) NOT NULL,
  `USUARIO_PASSWORD` varchar(45) NOT NULL,
  `PERMISO` int(2) NOT NULL,
  PRIMARY KEY (`COD_USUARIO`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`COD_USUARIO`, `ID_USUARIO`, `NOMBRE_APELLIDO`, `USUARIO_PASSWORD`, `PERMISO`) VALUES
(1, 'IVAN', 'IVAN GOMEZ', 'IVAN', 1),
(2, 'RAMON', 'RAMON FILIP', 'RAMON', 0),
(4, 'JUAN', 'JUAN PEREZ', '', 2),
(5, 'ADMIN', 'ADMIN', 'ADMIN', 1),
(6, 'MOSTRADOR', 'MOSTRADOR', 'MOSTRADOR', 2),
(7, 'FEDE', 'FEDERICO CANO', 'fede', 1);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `compra_ibfk_1` FOREIGN KEY (`COD_PROVEEDOR`) REFERENCES `proveedor` (`COD_PROVEEDOR`),
  ADD CONSTRAINT `compra_ibfk_2` FOREIGN KEY (`COD_MONEDA_COMPRA`) REFERENCES `moneda` (`COD_MONEDA`),
  ADD CONSTRAINT `compra_ibfk_3` FOREIGN KEY (`COD_FORMA_PAGO`) REFERENCES `forma_pago` (`COD_FORMA_PAGO`);

--
-- Filtros para la tabla `compra_detalle`
--
ALTER TABLE `compra_detalle`
  ADD CONSTRAINT `compra_detalle_ibfk_1` FOREIGN KEY (`NRO_FACTURA_COMPRA`) REFERENCES `compra` (`NRO_FACTURA_COMPRA`);

--
-- Filtros para la tabla `compra_impuesto`
--
ALTER TABLE `compra_impuesto`
  ADD CONSTRAINT `compra_impuesto_ibfk_1` FOREIGN KEY (`NRO_FACTURA_COMPRA`) REFERENCES `compra` (`NRO_FACTURA_COMPRA`),
  ADD CONSTRAINT `compra_impuesto_ibfk_2` FOREIGN KEY (`COD_IMPUESTO`) REFERENCES `impuesto` (`COD_IMPUESTO`);

--
-- Filtros para la tabla `pago_cliente`
--
ALTER TABLE `pago_cliente`
  ADD CONSTRAINT `pago_cliente_ibfk_1` FOREIGN KEY (`FAC_NRO`) REFERENCES `factura` (`FAC_NRO`);

--
-- Filtros para la tabla `pago_proveedor`
--
ALTER TABLE `pago_proveedor`
  ADD CONSTRAINT `pago_proveedor_ibfk_1` FOREIGN KEY (`NRO_FACTURA_COMPRA`) REFERENCES `compra` (`NRO_FACTURA_COMPRA`),
  ADD CONSTRAINT `pago_proveedor_ibfk_2` FOREIGN KEY (`COD_MONEDA_PAGO`) REFERENCES `moneda` (`COD_MONEDA`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `fk_producto_unidadmedida` FOREIGN KEY (`COD_UNIDAD_MEDIDA`) REFERENCES `unidad_medida` (`COD_UNIDAD_MEDIDA`),
  ADD CONSTRAINT `fk_product_tipoproduto` FOREIGN KEY (`COD_PRODUCTO_TIPO`) REFERENCES `tipo_producto` (`COD_TIPO_PRODUCTO`);

--
-- Filtros para la tabla `receta_detalle`
--
ALTER TABLE `receta_detalle`
  ADD CONSTRAINT `fk_RECETADETALLE_RECETA` FOREIGN KEY (`COD_RECETA`) REFERENCES `receta` (`COD_RECETA`),
  ADD CONSTRAINT `fk_RECETA_DETALLE_PRODUCTO` FOREIGN KEY (`COD_PRODUCTO`) REFERENCES `producto` (`COD_PRODUCTO`),
  ADD CONSTRAINT `fk_RECETA_DETALLE_RECETA` FOREIGN KEY (`COD_RECETA`) REFERENCES `receta` (`COD_RECETA`);

--
-- Filtros para la tabla `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`COD_PRODUCTO`) REFERENCES `producto` (`COD_PRODUCTO`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
