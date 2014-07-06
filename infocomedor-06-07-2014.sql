-- phpMyAdmin SQL Dump
-- version 3.3.3
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 06-07-2014 a las 19:05:55
-- Versión del servidor: 5.1.50
-- Versión de PHP: 5.3.9-ZS5.6.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `infocomedor`
--

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
  PRIMARY KEY (`cod_baja_stock`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `baja_stock`
--


--
-- (Evento) desencadenante `baja_stock`
--
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
  PRIMARY KEY (`cod_caja`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Volcar la base de datos para la tabla `caja`
--

INSERT INTO `caja` (`cod_caja`, `cod_usuario_caja`, `fecha_hora_apertura`, `fecha_hora_cierre`, `monto_caja_apertura`, `monto_caja_cierre`, `monto_diferencia_arqueo`, `arqueo_caja`) VALUES
(7, 1, '2014-07-01 12:26:02', '2014-07-01 12:26:02', 10000, 100000, -156000, 'S'),
(8, 1, '2014-07-01 06:47:16', '2014-07-01 06:47:16', 0, 6000, 0, 'S'),
(9, 2, '2014-07-01 06:54:39', '2014-07-01 06:54:39', 100000, 10000, 114000, 'S'),
(10, 1, '2014-07-06 05:17:31', '2014-07-06 05:17:31', 10000, 300000, -337000, 'S'),
(11, 1, '2014-07-06 05:22:03', NULL, 100000, NULL, NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Volcar la base de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`COD_CLIENTE`, `CLIENTE_DES`, `CLIENTE_RUC`, `CLIENTE_DIRECCION`, `CLIENTE_TELEFONO`, `CLIENTE_EMAIL`, `COD_EMPRESA`) VALUES
(5, 'Ivan Gomez', '4048560-9', 'Centro', '0981972342', 'ivan@ivan.com', 0),
(6, 'Consumidor Final', '444444-7', 'Consumidor Final', 'Consumidor Final', 'Consumidor Final', 0),
(7, 'Ramon Filip', '987107-9', 'Ettiene 897 casi Ypane', '021551001', 'ramon@gmai.com', 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

--
-- Volcar la base de datos para la tabla `compra`
--

INSERT INTO `compra` (`COD_PROVEEDOR`, `NRO_FACTURA_COMPRA`, `FECHA_EMISION_FACTURA`, `FECHA_VENCIMIENTO_FACTURA`, `MONTO_TOTAL_COMPRA`, `COD_MONEDA_COMPRA`, `COD_FORMA_PAGO`, `COD_USUARIO`, `CONTROL_FISCAL`, `ESTADO`) VALUES
(14, 24, '2014-06-29', '2014-06-29', '74000.00', 1, 1, 1, '001-001-00001', 'T'),
(15, 25, '2014-07-01', '2014-07-31', '40000.00', 1, 1, 1, '001-001-9000001', 'T'),
(16, 26, '2014-07-01', '2014-07-01', '60000.00', 1, 1, 1, '001-001-900001', 'T'),
(14, 27, '2014-07-01', '2014-07-01', '24000.00', 1, 1, 1, '001-001-899901', 'T'),
(14, 28, '2014-07-01', '2014-07-01', '48000.00', 1, 1, 1, '001-001-98655', 'T'),
(14, 29, '2014-07-01', '2014-07-01', '90000.00', 1, 1, 1, '001-001-900000', 'T');

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
-- Volcar la base de datos para la tabla `compra_detalle`
--

INSERT INTO `compra_detalle` (`NRO_FACTURA_COMPRA`, `DET_ITEM_COMPRA`, `COD_PRODUCTO_ITEM`, `CANTIDAD_COMPRA`, `MONTO_COMPRA`, `COD_UNIDAD_MEDIDA`) VALUES
(24, 1, 31, '1.00', '10000.00', 2),
(24, 2, 29, '2.00', '18000.00', 2),
(24, 3, 27, '2.00', '46000.00', 2),
(25, 1, 37, '2.00', '6000.00', 2),
(25, 2, 29, '1.00', '2000.00', 2),
(25, 3, 27, '2.00', '26000.00', 2),
(25, 4, 35, '12.00', '6000.00', 1),
(26, 1, 38, '5.00', '60000.00', 2),
(27, 1, 31, '2.00', '12000.00', 2),
(27, 2, 29, '2.00', '12000.00', 2),
(28, 1, 40, '5.00', '40000.00', 2),
(28, 2, 39, '2.00', '8000.00', 2),
(29, 1, 31, '10.00', '90000.00', 2);

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
-- Volcar la base de datos para la tabla `compra_impuesto`
--

INSERT INTO `compra_impuesto` (`NRO_FACTURA_COMPRA`, `DET_ITEM_IMPUESTO`, `COD_IMPUESTO`, `MONTO_IMPUESTO`) VALUES
(24, 1, 10, '909.00'),
(24, 2, 10, '1636.00'),
(24, 3, 10, '4181.00'),
(25, 1, 10, '545.00'),
(25, 2, 10, '181.00'),
(25, 3, 10, '2363.00'),
(25, 4, 10, '545.00'),
(26, 1, 10, '5454.00'),
(27, 1, 10, '1090.00'),
(27, 2, 10, '1090.00'),
(28, 1, 10, '3636.00'),
(28, 2, 10, '727.00'),
(29, 1, 10, '8181.00');

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

--
-- Volcar la base de datos para la tabla `concepto`
--


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

--
-- Volcar la base de datos para la tabla `empresa`
--


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

--
-- Volcar la base de datos para la tabla `estadopedido`
--


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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Volcar la base de datos para la tabla `factura`
--

INSERT INTO `factura` (`FAC_NRO`, `COD_CLIENTE`, `FAC_FECHA_EMI`, `FAC_MES`, `FAC_ANO`, `FAC_FECH_VTO`, `FAC_MONTO_TOTAL`, `ESTADO`, `CONTROL_FISCAL`) VALUES
(18, 5, '2014-06-30', 6, 14, '2014-06-30', 20000, 'C', '001-001-0000001'),
(19, 6, '2014-07-01', 7, 14, '2014-07-01', 8000, 'C', '001-001-09990'),
(20, 5, '2014-07-01', 7, 14, '2014-07-01', 8000, 'C', '001-001-900000'),
(21, 6, '2014-07-01', 7, 14, '2014-07-01', 12000, 'C', '001-001-12999'),
(22, 6, '2014-07-01', 7, 14, '2014-07-01', 10000, 'C', '001-001-000002'),
(23, 7, '2014-07-01', 7, 14, '2014-07-01', 6000, 'C', '001-001-909999'),
(24, 5, '2014-07-01', 7, 14, '2014-07-01', 2000, 'C', '001-001-9000001'),
(25, 6, '2014-07-01', 7, 14, '2014-07-01', 12000, 'C', '001-001-900001'),
(26, 6, '2014-07-01', 7, 14, '2014-07-01', 12000, 'C', '001-001-999888'),
(27, 6, '2014-07-01', 7, 14, '2014-07-01', 60000, 'C', '001-001-900009'),
(28, 6, '2014-07-01', 7, 14, '2014-07-01', 12000, 'C', '001-001-90000'),
(29, 6, '2014-07-03', 7, 14, '2014-07-03', 80000, 'C', '001-001-123'),
(30, 6, '2014-07-04', 7, 14, '2014-07-04', 44000, 'C', '001-001-12312312');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_detalle`
--

CREATE TABLE IF NOT EXISTS `factura_detalle` (
  `FAC_NRO` int(11) NOT NULL,
  `FAC_DET_ITEM` int(11) NOT NULL,
  `COD_PRODUCTO` int(11) NOT NULL,
  `FAC_DET_TOTAL` int(11) NOT NULL,
  PRIMARY KEY (`FAC_NRO`,`FAC_DET_ITEM`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `factura_detalle`
--

INSERT INTO `factura_detalle` (`FAC_NRO`, `FAC_DET_ITEM`, `COD_PRODUCTO`, `FAC_DET_TOTAL`) VALUES
(18, 0, 34, 20000),
(19, 0, 36, 8000),
(20, 0, 36, 8000),
(21, 0, 36, 12000),
(22, 0, 34, 10000),
(23, 0, 34, 6000),
(24, 0, 34, 2000),
(25, 0, 34, 8000),
(25, 1, 36, 4000),
(26, 0, 41, 12000),
(27, 0, 41, 60000),
(28, 0, 34, 12000),
(29, 0, 41, 40000),
(29, 1, 36, 40000),
(30, 0, 41, 44000);

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
-- Volcar la base de datos para la tabla `factura_impuesto`
--


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
-- Volcar la base de datos para la tabla `forma_pago`
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
-- Volcar la base de datos para la tabla `impuesto`
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
-- Volcar la base de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`COD_INVENTARIO`, `COD_PRODUCTO`, `INVENTARIO_FECHA`, `INVENTARIO_ENTRADA`, `INVENTARIO_SALIDA`, `INVENTARIO_SALDO`, `ESTADO`) VALUES
(1, 27, '2014-06-30', '2.00', '1.00', '1.00', 'S'),
(1, 29, '2014-06-30', '0.50', '0.20', '0.30', 'S'),
(1, 31, '2014-06-30', '0.70', '1.00', '-0.30', 'S'),
(1, 35, '2014-06-30', '-6.00', '0.00', '-6.00', 'S'),
(3, 27, '2014-06-30', '1.00', '2.00', '-1.00', 'S'),
(3, 29, '2014-06-30', '1.00', '0.00', '1.00', 'S'),
(3, 31, '2014-06-30', '1.00', '1.00', '0.00', 'S'),
(4, 34, '2014-06-30', '3.00', '2.00', '1.00', 'S'),
(5, 36, '2014-07-01', '0.30', '0.10', '0.20', 'S'),
(6, 34, '2014-07-01', '0.70', '0.50', '0.20', 'S'),
(6, 36, '2014-07-01', '0.00', '0.00', '0.00', 'S'),
(7, 41, '2014-07-01', '2.70', '2.00', '0.70', 'S'),
(8, 27, '2014-07-05', '1.00', '0.13', '0.87', 'N'),
(8, 28, '2014-07-05', '0.00', '0.00', '0.00', 'N'),
(8, 29, '2014-07-05', '2.00', '0.00', '2.00', 'N'),
(8, 31, '2014-07-05', '12.00', '0.00', '12.00', 'N'),
(8, 35, '2014-07-05', '2.00', '0.12', '1.88', 'N'),
(8, 37, '2014-07-05', '1.00', '0.00', '1.00', 'N'),
(8, 38, '2014-07-05', '4.00', '0.00', '4.00', 'N'),
(8, 39, '2014-07-05', '1.00', '0.00', '1.00', 'N'),
(8, 40, '2014-07-05', '4.00', '0.00', '4.00', 'N'),
(9, 27, '2014-07-05', '1.20', '0.00', '0.00', 'N'),
(9, 28, '2014-07-05', '0.00', '0.00', '0.00', 'N'),
(9, 29, '2014-07-05', '1.50', '0.00', '0.00', 'N'),
(9, 31, '2014-07-05', '11.80', '0.00', '0.00', 'N'),
(9, 35, '2014-07-05', '2.00', '0.00', '0.00', 'N'),
(9, 37, '2014-07-05', '1.00', '0.00', '0.00', 'N'),
(9, 38, '2014-07-05', '3.70', '0.00', '0.00', 'N'),
(9, 39, '2014-07-05', '0.50', '0.00', '0.00', 'N'),
(9, 40, '2014-07-05', '3.50', '0.00', '0.00', 'N'),
(10, 34, '2014-07-05', '-0.10', '0.12', '-0.22', 'N'),
(10, 36, '2014-07-05', '-1.00', '0.00', '-1.00', 'N'),
(10, 41, '2014-07-05', '-1.10', '0.00', '-1.10', 'N');

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
  `KAR_PRECIO_PRODUCTO` decimal(11,4) NOT NULL,
  `COD_MOZO` int(11) NOT NULL,
  `FACT_NRO` int(11) NOT NULL,
  `ESTADO` varchar(2) NOT NULL,
  PRIMARY KEY (`COD_KARRITO`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=51 ;

--
-- Volcar la base de datos para la tabla `karrito`
--

INSERT INTO `karrito` (`COD_KARRITO`, `KAR_FECH_MOV`, `COD_CLIENTE`, `COD_MESA`, `COD_PRODUCTO`, `KAR_CANT_PRODUCTO`, `KAR_PRECIO_PRODUCTO`, `COD_MOZO`, `FACT_NRO`, `ESTADO`) VALUES
(35, '2014-06-30', 5, 1, 34, '1.0000', '20000.0000', 1, 18, 'PA'),
(36, '2014-07-01', 5, 0, 36, '0.2000', '8000.0000', 1, 20, 'PA'),
(37, '2014-07-01', 0, 7, 36, '0.3000', '12000.0000', 1, 21, 'PA'),
(38, '2014-07-01', 6, 1, 36, '0.2000', '8000.0000', 1, 19, 'PA'),
(39, '2014-07-01', 7, 0, 34, '0.3000', '6000.0000', 1, 23, 'PA'),
(40, '2014-07-01', 0, 8, 34, '0.5000', '10000.0000', 1, 22, 'PA'),
(41, '2014-07-01', 5, 1, 34, '0.1000', '2000.0000', 1, 24, 'PA'),
(42, '2014-07-01', 6, 1, 34, '0.4000', '8000.0000', 1, 25, 'PA'),
(43, '2014-07-01', 6, 1, 36, '0.1000', '4000.0000', 1, 25, 'PA'),
(44, '2014-07-01', 6, 1, 41, '0.3000', '12000.0000', 1, 26, 'PA'),
(45, '2014-07-01', 6, 1, 41, '1.5000', '60000.0000', 1, 27, 'PA'),
(46, '2014-07-01', 0, 9, 34, '0.6000', '12000.0000', 1, 28, 'PA'),
(47, '2014-07-01', 5, 0, 36, '0.5000', '20000.0000', 1, 0, 'PE'),
(48, '2014-07-03', 6, 1, 41, '1.0000', '40000.0000', 1, 29, 'PA'),
(49, '2014-07-03', 6, 1, 36, '1.0000', '40000.0000', 1, 29, 'PA'),
(50, '2014-07-04', 6, 0, 41, '1.1000', '44000.0000', 1, 30, 'PA');

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
-- Volcar la base de datos para la tabla `mesa`
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
-- Volcar la base de datos para la tabla `moneda`
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
  PRIMARY KEY (`cod_mov_caja`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=44 ;

--
-- Volcar la base de datos para la tabla `mov_caja`
--

INSERT INTO `mov_caja` (`cod_mov_caja`, `cod_caja`, `fecha_hora_mov`, `monto_mov`, `cod_tipo_mov`, `factura_mov`, `tipo_factura_mov`, `observacion_mov`, `tipo_mov`) VALUES
(33, 10, '2014-07-03 01:45:14', 30000, 2, 29, 'V', 'Factura Venta: 29', 'EFECTIVO'),
(34, 10, '2014-07-03 01:45:14', 30000, 2, 29, 'V', 'Factura Venta: 29', 'CHEQUE'),
(35, 10, '2014-07-03 01:45:14', 0, 2, 29, 'V', 'Factura Venta: 29', 'EFECTIVO'),
(36, 10, '2014-07-03 01:45:14', 20000, 2, 29, 'V', 'Factura Venta: 29', 'EFECTIVO'),
(37, 10, '2014-07-04 22:21:51', 30000, 2, 30, 'V', 'Factura Venta: 30', 'EFECTIVO'),
(38, 10, '2014-07-04 22:21:51', 14000, 2, 30, 'V', 'Factura Venta: 30', 'EFECTIVO'),
(39, 10, '2014-07-04 22:21:51', 100000, 1, 29, 'C', 'Tiene Vuelto: 91000', ''),
(40, 10, '2014-07-05 20:56:19', 1000, 1, 29, 'C', 'Pago Factura Compra: 29', 'EFECTIVO'),
(41, 10, '2014-07-05 20:56:19', 80000, 1, 29, 'C', 'Pago Factura Compra: 29', 'CHEQUE'),
(42, 10, '2014-07-05 20:56:19', 91000, 3, 29, 'C', 'Vuelto Factura Compra: 29', 'EFECTIVO'),
(43, 10, '2014-07-05 21:06:32', 81000, 1, 0, '', '', '');

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `pago_cliente`
--


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
  PRIMARY KEY (`COD_PAGO_PROVEEDOR`),
  KEY `NRO_FACTURA_COMPRA` (`NRO_FACTURA_COMPRA`),
  KEY `COD_MONEDA_PAGO` (`COD_MONEDA_PAGO`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=54 ;

--
-- Volcar la base de datos para la tabla `pago_proveedor`
--

INSERT INTO `pago_proveedor` (`COD_PAGO_PROVEEDOR`, `NRO_FACTURA_COMPRA`, `MONTO_PAGO`, `COD_MONEDA_PAGO`, `NRO_CHEQUE`, `DES_BANCO`, `ESTADO_PAGO`) VALUES
(43, 24, '74000.00', 1, 0, '0', 'T'),
(44, 25, '40000.00', 1, 0, '0', 'T'),
(45, 27, '24000.00', 1, 0, '0', 'T'),
(46, 28, '48000.00', 1, 0, '0', 'T'),
(47, 29, '50000.00', 1, 0, '-', 'A'),
(48, 29, '40000.00', 1, 0, '-', 'A'),
(49, 29, '40000.00', 1, 0, '', 'A'),
(50, 29, '1000.00', 1, 0, '-', 'A'),
(51, 29, '80000.00', 1, 12312, 'ITAU', 'A'),
(52, 29, '9000.00', 1, 0, '-', 'A'),
(53, 29, '81000.00', 1, 0, '-', 'T');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

--
-- Volcar la base de datos para la tabla `producto`
--

INSERT INTO `producto` (`COD_PRODUCTO`, `COD_IMPUESTO`, `PRECIO_VENTA`, `PRODUCTO_DESC`, `COD_PRODUCTO_TIPO`, `COD_UNIDAD_MEDIDA`, `COD_RECETA`) VALUES
(27, 10, '0.00', 'Rabadilla', 1, 2, 0),
(28, 10, '0.00', 'Lomo', 1, 2, 0),
(29, 10, '0.00', 'Harina', 1, 2, 0),
(31, 10, '0.00', 'Cebolla', 1, 2, 0),
(34, 10, '20000.00', 'Tortilla', 2, 2, 12),
(35, 10, '0.00', 'Huevo', 1, 1, 0),
(36, 10, '40000.00', 'Milanesa de Carne', 2, 2, 13),
(37, 10, '0.00', 'Galleta Molida', 1, 2, 0),
(38, 10, '0.00', 'Queso Paraguay', 1, 2, 0),
(39, 10, '0.00', 'Arroz', 1, 2, 0),
(40, 10, '0.00', 'Tomate', 1, 2, 0),
(41, 10, '40000.00', 'Guizo de Arroz', 2, 2, 14);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Volcar la base de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`COD_PROVEEDOR`, `PROVEEDOR_NOMBRE`, `PROVEEDOR_RUC`, `PROVEEDOR_DIRECCION`, `PROVEEDOR_TELEFONO`, `PROVEEDOR_CONTACTO`, `PROVEEDOR_EMAIL`, `PROVEEDOR_LIMITE_CREDITO`) VALUES
(14, 'Casa Grutter', '800010001-9', 'Mercado Abasto', '555011', 'Luis Grutter', 'grutter@grutter.com', 1000000),
(15, 'Casa Rica SA', '900001001-9', 'Espana 1990', '021660770', 'Luis', 'casa@rica.com.py', 0),
(16, 'Supermercado Stock', '8000001-9', 'Rca Argentina casi Pilar', '5556001', 'Liz', 'stock@stock.com', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receta`
--

CREATE TABLE IF NOT EXISTS `receta` (
  `COD_RECETA` int(11) NOT NULL AUTO_INCREMENT,
  `RECETA_DESCRIPCION` varchar(45) NOT NULL,
  PRIMARY KEY (`COD_RECETA`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Volcar la base de datos para la tabla `receta`
--

INSERT INTO `receta` (`COD_RECETA`, `RECETA_DESCRIPCION`) VALUES
(12, 'Tortillita con cebolla 1kg'),
(13, 'Milanesa de Carne por 1 kilogramo'),
(14, 'Guizo de Arroz x 1Kg');

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
-- Volcar la base de datos para la tabla `receta_detalle`
--

INSERT INTO `receta_detalle` (`COD_RECETA`, `RECETA_DET_ITEM`, `COD_PRODUCTO`, `RECETA_DET_CANTIDAD`) VALUES
(12, 1, 35, '2.00'),
(12, 2, 31, '0.10'),
(12, 3, 29, '0.50'),
(12, 4, 38, '0.50'),
(13, 1, 27, '0.80'),
(13, 2, 37, '1.00'),
(13, 3, 29, '0.50'),
(13, 4, 35, '6.00'),
(14, 1, 39, '0.50'),
(14, 2, 40, '0.50'),
(14, 3, 38, '0.10');

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

--
-- Volcar la base de datos para la tabla `recibo`
--


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
-- Volcar la base de datos para la tabla `stock`
--

INSERT INTO `stock` (`COD_PRODUCTO`, `SALDO_STOCK`, `STOCK_FECHA_ACTUALIZA`) VALUES
(27, '1.20', '2014-07-01'),
(28, '0.00', '2014-06-30'),
(29, '1.50', '2014-07-01'),
(31, '11.80', '2014-07-01'),
(34, '-0.10', '2014-07-01'),
(35, '2.00', '2014-07-01'),
(36, '-1.00', '2014-07-03'),
(37, '1.00', '2014-07-01'),
(38, '3.70', '2014-07-01'),
(39, '0.50', '2014-07-01'),
(40, '3.50', '2014-07-01'),
(41, '-1.10', '2014-07-04');

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

--
-- Volcar la base de datos para la tabla `tipo_cambio`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_movimiento`
--

CREATE TABLE IF NOT EXISTS `tipo_movimiento` (
  `cod_tipo_mov` int(11) NOT NULL AUTO_INCREMENT,
  `desc_tipo_mov` varchar(100) DEFAULT NULL,
  `tipo_mov` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`cod_tipo_mov`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Volcar la base de datos para la tabla `tipo_movimiento`
--

INSERT INTO `tipo_movimiento` (`cod_tipo_mov`, `desc_tipo_mov`, `tipo_mov`) VALUES
(1, 'Egredo por compra', 'R'),
(2, 'Ingreso por venta', 'S'),
(3, 'Vuelto', 'S'),
(4, 'Diferencia en compra', 'R');

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
-- Volcar la base de datos para la tabla `tipo_producto`
--

INSERT INTO `tipo_producto` (`COD_TIPO_PRODUCTO`, `TIPO_PRODUCTO_DESCRIPCION`) VALUES
(1, 'Materia Prima'),
(2, 'Consumo Final');

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
-- Volcar la base de datos para la tabla `unidad_medida`
--

INSERT INTO `unidad_medida` (`COD_UNIDAD_MEDIDA`, `DESC_UNIDAD_MEDIDA`, `ISO_UNIDAD_MEDIDA`) VALUES
(1, 'Unitario', 'UNI'),
(2, 'kilo', 'KG'),
(3, 'Gramo', 'GR'),
(4, 'Docena', 'DC'),
(5, 'Litro', 'lts');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `COD_USUARIO` int(11) NOT NULL AUTO_INCREMENT,
  `ID_USUARIO` varchar(45) NOT NULL,
  `NOMBRE_APELLIDO` varchar(45) NOT NULL,
  `USUARIO_PASSWORD` varchar(45) NOT NULL,
  PRIMARY KEY (`COD_USUARIO`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcar la base de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`COD_USUARIO`, `ID_USUARIO`, `NOMBRE_APELLIDO`, `USUARIO_PASSWORD`) VALUES
(1, 'IVAN', 'IVAN GOMEZ', 'IVAN'),
(2, 'RAMON', 'RAMON FILIP', 'RAMON');

--
-- Filtros para las tablas descargadas (dump)
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
