-- phpMyAdmin SQL Dump
-- version 3.3.3
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-07-2014 a las 22:35:33
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
  `monto_caja_cierre_cheque` int(15) DEFAULT NULL,
  `monto_diferencia_arqueo_cheque` int(15) DEFAULT NULL,
  PRIMARY KEY (`cod_caja`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Volcar la base de datos para la tabla `caja`
--

INSERT INTO `caja` (`cod_caja`, `cod_usuario_caja`, `fecha_hora_apertura`, `fecha_hora_cierre`, `monto_caja_apertura`, `monto_caja_cierre`, `monto_diferencia_arqueo`, `arqueo_caja`, `monto_caja_cierre_cheque`, `monto_diferencia_arqueo_cheque`) VALUES
(12, 1, '2014-07-15 12:11:40', '2014-07-15 12:11:40', 1000000, 1087500, -200000, 'S', 22000, 0),
(13, 1, '2014-07-17 02:02:45', '2014-07-17 02:02:45', 0, 5500, 0, 'S', 0, 0),
(14, 1, '2014-07-17 06:50:43', NULL, 0, NULL, NULL, NULL, NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Volcar la base de datos para la tabla `compra`
--

INSERT INTO `compra` (`COD_PROVEEDOR`, `NRO_FACTURA_COMPRA`, `FECHA_EMISION_FACTURA`, `FECHA_VENCIMIENTO_FACTURA`, `MONTO_TOTAL_COMPRA`, `COD_MONEDA_COMPRA`, `COD_FORMA_PAGO`, `COD_USUARIO`, `CONTROL_FISCAL`, `ESTADO`) VALUES
(15, 30, '2014-07-15', '2014-07-15', '140000.00', 1, 1, 1, '001-001-900001', 'T'),
(17, 31, '2014-07-17', '2014-07-17', '45000.00', 1, 1, 1, '001-001-77', 'T'),
(17, 32, '2014-07-17', '2014-07-17', '45000.00', 1, 1, 1, '001-001-77', 'T');

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
(30, 1, 47, '1.00', '23000.00', 2),
(30, 2, 48, '1.00', '24000.00', 5),
(30, 3, 43, '0.50', '4000.00', 2),
(30, 4, 46, '0.50', '5000.00', 2),
(30, 5, 35, '12.00', '6000.00', 1),
(30, 6, 44, '2.00', '70000.00', 2),
(30, 7, 42, '1.00', '8000.00', 2),
(31, 1, 50, '10.00', '45000.00', 1),
(32, 1, 50, '10.00', '45000.00', 1);

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
(30, 1, 10, '2090.00'),
(30, 2, 10, '2181.00'),
(30, 3, 10, '363.00'),
(30, 4, 10, '454.00'),
(30, 5, 10, '545.00'),
(30, 6, 10, '6363.00'),
(30, 7, 10, '727.00'),
(31, 1, 10, '4090.00'),
(32, 1, 10, '4090.00');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=41 ;

--
-- Volcar la base de datos para la tabla `factura`
--

INSERT INTO `factura` (`FAC_NRO`, `COD_CLIENTE`, `FAC_FECHA_EMI`, `FAC_MES`, `FAC_ANO`, `FAC_FECH_VTO`, `FAC_MONTO_TOTAL`, `ESTADO`, `CONTROL_FISCAL`) VALUES
(32, 6, '2014-07-15', 7, 14, '2014-07-15', 11000, 'C', '001-001-00002'),
(33, 5, '2014-07-15', 7, 14, '2014-07-15', 22000, 'C', '001-001-0000033'),
(34, 6, '2014-07-15', 7, 14, '2014-07-15', 8250, 'C', '001-001-000034'),
(35, 7, '2014-07-15', 7, 14, '2014-07-15', 8250, 'C', '001-001-000035'),
(36, 5, '2014-07-17', 7, 14, '2014-07-17', 5500, 'C', '001-001-000009'),
(38, 7, '2014-07-17', 7, 14, '2014-07-17', 3000, 'C', '001-001-000004'),
(39, 7, '2014-07-17', 7, 14, '2014-07-17', 30500, 'C', '001-001-00009'),
(40, 7, '2014-07-17', 7, 14, '2014-07-17', 37500, 'C', '001-001-00009');

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
-- Volcar la base de datos para la tabla `factura_detalle`
--

INSERT INTO `factura_detalle` (`FAC_NRO`, `FAC_DET_ITEM`, `COD_PRODUCTO`, `FAC_DET_CANTIDAD`, `FAC_DET_TOTAL`) VALUES
(32, 0, 49, 0.20, 11000),
(33, 0, 49, 0.40, 22000),
(34, 0, 49, 0.15, 8250),
(35, 0, 49, 0.15, 8250),
(36, 0, 49, 0.10, 5500),
(38, 0, 34, 0.15, 3000),
(39, 0, 34, 0.15, 3000),
(39, 1, 49, 0.50, 27500),
(40, 1, 49, 0.50, 27500),
(40, 2, 34, 0.50, 10000);

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
(25, 27, '2014-07-15', '1.20', '0.00', NULL, 'N'),
(25, 28, '2014-07-15', '0.00', '0.00', NULL, 'N'),
(25, 29, '2014-07-15', '1.50', '0.00', NULL, 'N'),
(25, 31, '2014-07-15', '11.80', '0.00', NULL, 'N'),
(25, 35, '2014-07-15', '2.00', '0.00', NULL, 'N'),
(25, 37, '2014-07-15', '1.00', '0.00', NULL, 'N'),
(25, 38, '2014-07-15', '3.70', '0.00', NULL, 'N'),
(25, 39, '2014-07-15', '0.50', '0.00', NULL, 'N'),
(25, 40, '2014-07-15', '3.50', '0.00', NULL, 'N'),
(26, 27, '2014-07-15', '1.20', '0.00', NULL, 'N'),
(26, 28, '2014-07-15', '0.00', '0.00', NULL, 'N'),
(26, 29, '2014-07-15', '1.50', '0.00', NULL, 'N'),
(26, 31, '2014-07-15', '11.80', '0.00', NULL, 'N'),
(26, 35, '2014-07-15', '2.00', '0.00', NULL, 'N'),
(26, 37, '2014-07-15', '1.00', '0.00', NULL, 'N'),
(26, 38, '2014-07-15', '3.70', '0.00', NULL, 'N'),
(26, 39, '2014-07-15', '0.50', '0.00', NULL, 'N'),
(26, 40, '2014-07-15', '3.50', '0.00', NULL, 'N'),
(27, 27, '2014-07-15', '1.20', '0.00', NULL, 'N'),
(27, 28, '2014-07-15', '0.00', '0.00', NULL, 'N'),
(27, 29, '2014-07-15', '1.50', '0.00', NULL, 'N'),
(27, 31, '2014-07-15', '11.80', '0.00', NULL, 'N'),
(27, 35, '2014-07-15', '2.00', '0.00', NULL, 'N'),
(27, 37, '2014-07-15', '1.00', '0.00', NULL, 'N'),
(27, 38, '2014-07-15', '3.70', '0.00', NULL, 'N'),
(27, 39, '2014-07-15', '0.50', '0.00', NULL, 'N'),
(27, 40, '2014-07-15', '3.50', '0.00', NULL, 'N'),
(28, 27, '2014-07-15', '1.20', '0.00', NULL, 'N'),
(28, 28, '2014-07-15', '0.00', '0.00', NULL, 'N'),
(28, 29, '2014-07-15', '1.50', '0.00', NULL, 'N'),
(29, 35, '2014-07-15', '8.00', '8.00', '0.00', 'S'),
(29, 42, '2014-07-15', '0.60', '0.50', '0.10', 'S'),
(29, 43, '2014-07-15', '0.49', '0.45', '0.04', 'S'),
(29, 44, '2014-07-15', '1.85', '1.80', '0.05', 'S'),
(29, 46, '2014-07-15', '0.49', '0.40', '0.09', 'S'),
(29, 47, '2014-07-15', '0.88', '0.90', '-0.02', 'S'),
(29, 48, '2014-07-15', '0.80', '0.75', '0.05', 'S'),
(30, 27, '2014-07-17', '0.00', '0.00', '0.00', 'S'),
(30, 28, '2014-07-17', '0.00', '0.00', '0.00', 'S'),
(30, 29, '2014-07-17', '-1.00', '0.00', '-1.00', 'S'),
(30, 31, '2014-07-17', '-0.20', '0.00', '-0.20', 'S'),
(30, 35, '2014-07-17', '-4.00', '0.00', '-4.00', 'S'),
(30, 37, '2014-07-17', '0.00', '0.00', '0.00', 'S'),
(30, 38, '2014-07-17', '-1.00', '0.00', '-1.00', 'S'),
(30, 39, '2014-07-17', '0.00', '0.00', '0.00', 'S'),
(30, 40, '2014-07-17', '0.00', '0.00', '0.00', 'S'),
(30, 42, '2014-07-17', '-0.30', '0.00', '-0.30', 'S'),
(30, 43, '2014-07-17', '0.43', '0.00', '0.43', 'S'),
(30, 44, '2014-07-17', '1.50', '0.00', '1.50', 'S'),
(30, 45, '2014-07-17', '0.00', '0.00', '0.00', 'S'),
(30, 46, '2014-07-17', '0.38', '0.00', '0.38', 'S'),
(30, 47, '2014-07-17', '0.66', '0.00', '0.66', 'S'),
(30, 48, '2014-07-17', '0.35', '0.00', '0.35', 'S'),
(31, 34, '2014-07-17', '1.20', '1.10', '0.10', 'S'),
(31, 49, '2014-07-17', '0.50', '0.10', '0.40', 'S'),
(32, 27, '2014-07-17', '0.00', '0.00', '0.00', 'N'),
(32, 28, '2014-07-17', '0.00', '0.00', '0.00', 'N'),
(32, 29, '2014-07-17', '0.00', '0.00', '0.00', 'N'),
(32, 31, '2014-07-17', '0.00', '0.00', '0.00', 'N'),
(32, 35, '2014-07-17', '0.00', '0.00', '0.00', 'N'),
(32, 37, '2014-07-17', '0.00', '0.00', '0.00', 'N'),
(32, 38, '2014-07-17', '0.00', '0.00', '0.00', 'N'),
(32, 39, '2014-07-17', '0.00', '0.00', '0.00', 'N'),
(32, 40, '2014-07-17', '0.00', '0.00', '0.00', 'N'),
(32, 42, '2014-07-17', '0.00', '0.00', '0.00', 'N'),
(32, 43, '2014-07-17', '0.00', '0.00', '0.00', 'N'),
(32, 44, '2014-07-17', '0.00', '0.00', '0.00', 'N'),
(32, 45, '2014-07-17', '0.00', '0.00', '0.00', 'N'),
(32, 46, '2014-07-17', '0.00', '0.00', '0.00', 'N'),
(32, 47, '2014-07-17', '0.00', '0.00', '0.00', 'N'),
(32, 48, '2014-07-17', '0.00', '0.00', '0.00', 'N');

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
  `COD_MOZO` int(11) NOT NULL,
  `FACT_NRO` int(11) NOT NULL,
  `ESTADO` varchar(2) NOT NULL,
  PRIMARY KEY (`COD_KARRITO`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Volcar la base de datos para la tabla `karrito`
--

INSERT INTO `karrito` (`COD_KARRITO`, `KAR_FECH_MOV`, `COD_CLIENTE`, `COD_MESA`, `COD_PRODUCTO`, `KAR_CANT_PRODUCTO`, `KAR_CANT_FACTURAR`, `KAR_PRECIO_PRODUCTO`, `KAR_PRECIO_FACTURAR`, `COD_MOZO`, `FACT_NRO`, `ESTADO`) VALUES
(1, '2014-07-15', 0, 1, 49, '0.3000', '0.0000', '16500.0000', '0.0000', 1, 35, 'PA'),
(2, '2014-07-15', 5, 0, 49, '0.4000', '0.0000', '22000.0000', '0.0000', 1, 33, 'PA'),
(3, '2014-07-15', 6, 1, 49, '0.2000', '0.2000', '11000.0000', '11000.0000', 1, 32, 'PA'),
(4, '2014-07-17', 5, 1, 49, '0.1000', '0.1000', '5500.0000', '5500.0000', 1, 36, 'PA'),
(5, '2014-07-17', 7, 0, 34, '0.3000', '0.0000', '6000.0000', '0.0000', 1, 39, 'PA'),
(6, '2014-07-17', 7, 0, 49, '0.5000', '0.0000', '27500.0000', '0.0000', 1, 39, 'PA'),
(7, '2014-07-17', 0, 5, 49, '1.0000', '0.5000', '55000.0000', '27500.0000', 1, 0, 'PE'),
(8, '2014-07-17', 0, 5, 34, '0.5000', '0.0000', '10000.0000', '0.0000', 1, 40, 'PA');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=57 ;

--
-- Volcar la base de datos para la tabla `mov_caja`
--

INSERT INTO `mov_caja` (`cod_mov_caja`, `cod_caja`, `fecha_hora_mov`, `monto_mov`, `cod_tipo_mov`, `factura_mov`, `tipo_factura_mov`, `observacion_mov`, `tipo_mov`) VALUES
(44, 12, '2014-07-15 11:27:32', 200000, 1, 30, 'C', 'Tiene Vuelto: 60000', 'EFECTIVO'),
(45, 12, '2014-07-15 11:41:02', 60000, 3, 30, 'C', 'Vuelto Factura Compra: 30', 'EFECTIVO'),
(46, 12, '2014-07-15 12:01:03', 11000, 2, 32, 'V', 'Factura Venta: 32', 'EFECTIVO'),
(47, 12, '2014-07-15 12:01:53', 22000, 2, 33, 'V', 'Factura Venta: 33', 'CHEQUE'),
(48, 12, '2014-07-15 12:02:31', 8250, 2, 34, 'V', 'Factura Venta: 34', 'EFECTIVO'),
(49, 12, '2014-07-15 12:02:56', 8250, 2, 35, 'V', 'Factura Venta: 35', 'EFECTIVO'),
(50, 13, '2014-07-17 02:02:27', 5500, 2, 36, 'V', 'Factura Venta: 36', 'EFECTIVO'),
(51, 14, '2014-07-17 18:52:45', 16750, 2, 38, 'V', 'Factura Venta: 38', 'EFECTIVO'),
(52, 14, '2014-07-17 18:55:05', 30500, 2, 39, 'V', 'Factura Venta: 39', 'EFECTIVO'),
(53, 14, '2014-07-17 18:57:20', 37500, 2, 40, 'V', 'Factura Venta: 40', 'EFECTIVO'),
(54, 14, '2014-07-17 19:42:29', 45000, 1, 31, 'C', 'Pago Factura Compra: 31', 'EFECTIVO'),
(55, 14, '2014-07-17 07:55:54', 50000, 1, 32, 'C', 'Tiene Vuelto: 5000', 'EFECTIVO'),
(56, 14, '2014-07-17 20:10:47', 5000, 3, 32, 'C', 'Vuelto Factura Compra: 32', 'EFECTIVO');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=57 ;

--
-- Volcar la base de datos para la tabla `pago_proveedor`
--

INSERT INTO `pago_proveedor` (`COD_PAGO_PROVEEDOR`, `NRO_FACTURA_COMPRA`, `MONTO_PAGO`, `COD_MONEDA_PAGO`, `NRO_CHEQUE`, `DES_BANCO`, `ESTADO_PAGO`) VALUES
(54, 30, '140000.00', 1, 0, '-', 'T'),
(55, 31, '45000.00', 1, 0, '-', 'T'),
(56, 32, '45000.00', 1, 0, '-', 'T');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=51 ;

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
(41, 10, '40000.00', 'Guizo de Arroz', 2, 2, 14),
(42, 10, '0.00', 'Spagetti', 1, 2, 0),
(43, 10, '0.00', 'Pimienta', 1, 2, 0),
(44, 10, '0.00', 'Queso pecorino', 1, 2, 0),
(45, 10, '0.00', 'Queso Provolone', 1, 2, 0),
(46, 10, '0.00', 'Sal', 1, 2, 0),
(47, 10, '0.00', 'Tocino ahumado', 1, 2, 0),
(48, 10, '0.00', 'Aceite', 1, 5, 0),
(49, 10, '55000.00', 'Espaguetis a la Carbonara', 2, 2, 15),
(50, 10, '5000.00', 'Coca', 2, 1, 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Volcar la base de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`COD_PROVEEDOR`, `PROVEEDOR_NOMBRE`, `PROVEEDOR_RUC`, `PROVEEDOR_DIRECCION`, `PROVEEDOR_TELEFONO`, `PROVEEDOR_CONTACTO`, `PROVEEDOR_EMAIL`, `PROVEEDOR_LIMITE_CREDITO`) VALUES
(14, 'Casa Grutter', '800010001-9', 'Mercado Abasto', '555011', 'Luis Grutter', 'grutter@grutter.com', 1000000),
(15, 'Casa Rica SA', '900001001-9', 'Espana 1990', '021660770', 'Luis', 'casa@rica.com.py', 0),
(16, 'Supermercado Stock', '8000001-9', 'Rca Argentina casi Pilar', '5556001', 'Liz', 'stock@stock.com', 0),
(17, 'CI', '8000000', 'SL', '9999', 'NN', 'nn', 80000000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receta`
--

CREATE TABLE IF NOT EXISTS `receta` (
  `COD_RECETA` int(11) NOT NULL AUTO_INCREMENT,
  `RECETA_DESCRIPCION` varchar(45) NOT NULL,
  PRIMARY KEY (`COD_RECETA`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Volcar la base de datos para la tabla `receta`
--

INSERT INTO `receta` (`COD_RECETA`, `RECETA_DESCRIPCION`) VALUES
(12, 'Tortillita con cebolla 1kg'),
(13, 'Milanesa de Carne por 1 kilogramo'),
(14, 'Guizo de Arroz x 1Kg'),
(15, 'Espaguetis a la Carbonara 1 kg');

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
(14, 3, 38, '0.10'),
(15, 1, 35, '4.00'),
(15, 2, 48, '0.20'),
(15, 3, 42, '0.40'),
(15, 4, 43, '0.01'),
(15, 5, 44, '0.15'),
(15, 6, 47, '0.12'),
(15, 7, 46, '0.01');

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
(27, '0.00', '2014-07-17'),
(28, '0.00', '2014-07-17'),
(29, '0.00', '2014-07-17'),
(31, '0.00', '2014-07-17'),
(34, '1.10', '2014-07-17'),
(35, '0.00', '2014-07-17'),
(36, '0.00', '2014-07-03'),
(37, '0.00', '2014-07-17'),
(38, '0.00', '2014-07-17'),
(39, '0.00', '2014-07-17'),
(40, '0.00', '2014-07-17'),
(41, '0.00', '2014-07-04'),
(42, '0.00', '2014-07-17'),
(43, '0.00', '2014-07-17'),
(44, '0.00', '2014-07-17'),
(45, '0.00', '2014-07-17'),
(46, '0.00', '2014-07-17'),
(47, '0.00', '2014-07-17'),
(48, '0.00', '2014-07-17'),
(49, '0.10', '2014-07-17'),
(50, '20.00', '2014-07-17');

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
(1, 'Egreso por compra', 'R'),
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
  PRIMARY KEY (`COD_USUARIO`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Volcar la base de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`COD_USUARIO`, `ID_USUARIO`, `NOMBRE_APELLIDO`, `USUARIO_PASSWORD`) VALUES
(1, 'IVAN', 'IVAN GOMEZ', 'IVAN'),
(2, 'RAMON', 'RAMON FILIP', 'RAMON'),
(4, 'JUAN', 'JUAN PEREZ', 'JUAN');

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
