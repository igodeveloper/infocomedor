-- phpMyAdmin SQL Dump
-- version 3.3.3
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 29-06-2014 a las 21:42:18
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Volcar la base de datos para la tabla `caja`
--


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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Volcar la base de datos para la tabla `cliente`
--


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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Volcar la base de datos para la tabla `compra`
--


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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Volcar la base de datos para la tabla `factura`
--


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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=35 ;

--
-- Volcar la base de datos para la tabla `karrito`
--


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
  PRIMARY KEY (`cod_mov_caja`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Volcar la base de datos para la tabla `mov_caja`
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

--
-- Volcar la base de datos para la tabla `pago_proveedor`
--


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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Volcar la base de datos para la tabla `producto`
--


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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Volcar la base de datos para la tabla `proveedor`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receta`
--

CREATE TABLE IF NOT EXISTS `receta` (
  `COD_RECETA` int(11) NOT NULL AUTO_INCREMENT,
  `RECETA_DESCRIPCION` varchar(45) NOT NULL,
  PRIMARY KEY (`COD_RECETA`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Volcar la base de datos para la tabla `receta`
--


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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`COD_USUARIO`, `ID_USUARIO`, `NOMBRE_APELLIDO`, `USUARIO_PASSWORD`) VALUES
(1, 'IVAN', 'IVAN GOMEZ', 'IVAN');

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
