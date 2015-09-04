SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `accords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `identifier` varchar(255) DEFAULT NULL COMMENT 'Название или артикул в прайсе',
  `goods_id` int(11) NOT NULL,
  `providers_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_accords_goods1_idx` (`goods_id`),
  KEY `fk_accords_providers1_idx` (`providers_id`),
  KEY `inentifer_idx` (`identifier`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `accords` (`id`, `identifier`, `goods_id`, `providers_id`) VALUES
(1, 'Автошины HAIDA 235/40R18 HD919 Б/У (1000 км)', 14, 1);

CREATE TABLE IF NOT EXISTS `goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) DEFAULT NULL COMMENT 'Название',
  `goods_type_type` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_goods_goods_type1_idx` (`goods_type_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

INSERT INTO `goods` (`id`, `name`, `goods_type_type`) VALUES
(14, '3_5_9_11_13_15_17_19_23_25_1', 'tyre');

CREATE TABLE IF NOT EXISTS `goods_params` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(100) DEFAULT NULL COMMENT 'Значение',
  `public_value` varchar(100) DEFAULT NULL COMMENT 'Отображаемое значение',
  `sort` int(11) DEFAULT NULL COMMENT 'Сортировка',
  `link_category` int(11) NOT NULL COMMENT 'Привязано к',
  `goods_params_name_id` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_goods_params_goods_params_name1_idx` (`goods_params_name_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

INSERT INTO `goods_params` (`id`, `value`, `public_value`, `sort`, `link_category`, `goods_params_name_id`) VALUES
(1, '1', 'Шипованная', 1, 0, 'tyre_spike'),
(2, '0', '===', 0, 0, 'tyre_spike'),
(3, 'AEOLUS', '', 10, 0, 'tyre_brand'),
(4, 'BARUM', '', 20, 0, 'tyre_brand'),
(5, 'CROSS ACE AS02', '', 10, 3, 'tyre_model'),
(6, 'CROSS ACE AS03', '', 20, 3, 'tyre_model'),
(7, 'FEDERAL ', '', 30, 0, 'tyre_brand'),
(8, 'М/Т COURAGIA', '', NULL, 7, 'tyre_model'),
(9, '265', '', NULL, 0, 'tyre_width'),
(10, '235', '', NULL, 0, 'tyre_width'),
(11, '65', '', NULL, 0, 'tyre_heigth'),
(12, '75', '', NULL, 0, 'tyre_heigth'),
(13, '17', '', NULL, 0, 'tyre_dia'),
(14, '15', '', NULL, 0, 'tyre_dia'),
(15, '112', '', NULL, 0, 'tyre_i_speed'),
(16, '108', '', NULL, 0, 'tyre_i_speed'),
(17, 'S', '', NULL, 0, 'tyre_i_load'),
(18, 'H', '', NULL, 0, 'tyre_i_load'),
(19, 'Зима', '', NULL, 0, 'tyre_season'),
(20, 'Лето', '', NULL, 0, 'tyre_season'),
(21, 'Всесезонная', '', NULL, 0, 'tyre_season'),
(23, '1', 'Run Flat', NULL, 0, 'tyre_rf'),
(24, '0', '===', NULL, 0, 'tyre_rf'),
(25, 'Легковая', '', NULL, 0, 'tyre_type_auto'),
(26, 'Легкогрузовая', '', NULL, 0, 'tyre_type_auto');

CREATE TABLE IF NOT EXISTS `goods_params_name` (
  `id` varchar(50) NOT NULL COMMENT 'Код параметра',
  `name` varchar(100) NOT NULL COMMENT 'Название параметра',
  `data_type` varchar(12) NOT NULL COMMENT 'Тип данных параметра',
  `required` tinyint(1) DEFAULT '0' COMMENT 'Обязательное ли поле',
  `parent_param` varchar(50) DEFAULT NULL COMMENT 'Родительский параметр',
  `sort` int(2) DEFAULT NULL,
  `goods_type_type` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_goods_params_name_goods_type1_idx` (`goods_type_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `goods_params_name` (`id`, `name`, `data_type`, `required`, `parent_param`, `sort`, `goods_type_type`) VALUES
('tyre_brand', 'Марка', 'varchar(20)', 1, '', 10, 'tyre'),
('tyre_dia', 'Диаметр', 'float', 1, '', 60, 'tyre'),
('tyre_heigth', 'Высота', 'float', 1, '', 50, 'tyre'),
('tyre_i_load', 'Индекс нагрузки', 'int', 1, '', 80, 'tyre'),
('tyre_i_speed', 'Индекс скорости', 'varchar(10)', 1, '', 70, 'tyre'),
('tyre_model', 'Модель', 'varchar(30)', 1, 'tyre_brand', 20, 'tyre'),
('tyre_rf', 'RanFlaf', 'int', 0, '', 100, 'tyre'),
('tyre_season', 'Сезонность', 'varchar(20)', 0, '', 90, 'tyre'),
('tyre_spike', 'Шипы', 'int', 0, '', 120, 'tyre'),
('tyre_type_auto', 'Тип авто', 'varchar(20)', 0, '', 110, 'tyre'),
('tyre_width', 'Ширина', 'float', 1, '', 40, 'tyre');

CREATE TABLE IF NOT EXISTS `goods_type` (
  `type` varchar(45) NOT NULL COMMENT 'Код типа',
  `name` varchar(30) DEFAULT NULL COMMENT 'Название',
  `alias` varchar(255) DEFAULT NULL COMMENT 'Алиасы через запятую',
  `template_view` varchar(255) DEFAULT NULL COMMENT 'Шаблон вывода названия товара',
  PRIMARY KEY (`type`),
  UNIQUE KEY `type_UNIQUE` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `goods_type` (`type`, `name`, `alias`, `template_view`) VALUES
('discs', 'Диски', 'диск,disk', NULL),
('tyre', 'Шина', 'автошин', '{goodType} {tyre_brand}  {tyre_model} {tyre_width}/{tyre_heigth}/{tyre_dia} {tyre_i_speed}{tyre_i_load} {tyre_rf} {tyre_season} {tyre_type_auto}!');

CREATE TABLE IF NOT EXISTS `goods_t_discs` (
  `goods_id` int(11) NOT NULL,
  `discription` text COMMENT 'Описание',
  `photo` varchar(255) DEFAULT NULL COMMENT 'Фото',
  `discs_width` int(11) DEFAULT NULL,
  `discs_brand` varchar(30) NOT NULL,
  `discs_model` varchar(30) NOT NULL,
  PRIMARY KEY (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `goods_t_tyre` (
  `goods_id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL COMMENT 'Название',
  `discription` text COMMENT 'Описание',
  `photo` varchar(255) DEFAULT NULL COMMENT 'Фото',
  `tyre_brand` varchar(20) NOT NULL,
  `tyre_model` varchar(30) NOT NULL,
  `tyre_rf` int(11) DEFAULT NULL,
  `tyre_width` float NOT NULL,
  `tyre_heigth` float NOT NULL,
  `tyre_dia` float NOT NULL,
  `tyre_i_speed` varchar(10) NOT NULL,
  `tyre_i_load` int(11) NOT NULL,
  `tyre_season` varchar(20) DEFAULT NULL,
  `tyre_type_auto` varchar(20) DEFAULT NULL,
  `tyre_spike` int(11) DEFAULT NULL,
  PRIMARY KEY (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `goods_t_tyre` (`goods_id`, `name`, `discription`, `photo`, `tyre_brand`, `tyre_model`, `tyre_rf`, `tyre_width`, `tyre_heigth`, `tyre_dia`, `tyre_i_speed`, `tyre_i_load`, `tyre_season`, `tyre_type_auto`, `tyre_spike`) VALUES
(14, NULL, NULL, NULL, '3', '5', 23, 9, 11, 13, '15', 17, '19', '25', 1);

CREATE TABLE IF NOT EXISTS `offers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quantity` int(11) DEFAULT NULL COMMENT 'Кол-во',
  `fix_price` float DEFAULT NULL COMMENT 'Фиксированная цена',
  `providers_id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `price` float DEFAULT NULL COMMENT 'Цена',
  PRIMARY KEY (`id`),
  KEY `fk_offers_providers1_idx` (`providers_id`),
  KEY `fk_offers_goods1_idx` (`goods_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `offers` (`id`, `quantity`, `fix_price`, `providers_id`, `goods_id`, `price`) VALUES
(1, 1, NULL, 1, 14, 3600);

CREATE TABLE IF NOT EXISTS `providers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meta` longtext,
  `name` varchar(50) DEFAULT NULL COMMENT 'Имя поставщика',
  `id_script` varchar(10) DEFAULT NULL COMMENT 'Код скрипта',
  `date_last_down` int(11) DEFAULT NULL COMMENT 'Дата обновления прайса',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

INSERT INTO `providers` (`id`, `meta`, `name`, `id_script`, `date_last_down`) VALUES
(1, 'a:1:{s:7:"accords";a:4:{i:0;s:1:"0";i:1;s:4:"name";i:2;s:8:"quantity";i:3;s:5:"price";}}', 'Вершина1', NULL, 1441008252),
(3, 'a:1:{s:7:"accords";a:16:{i:0;s:10:"identifier";i:1;s:1:"0";i:2;s:1:"0";i:3;s:4:"name";i:4;s:1:"0";i:5;s:1:"0";i:6;s:1:"0";i:7;s:8:"quantity";i:8;s:1:"0";i:9;s:1:"0";i:10;s:1:"0";i:11;s:1:"0";i:12;s:1:"0";i:13;s:1:"0";i:14;s:5:"price";i:15;s:1:"0";}}', 'itr', NULL, NULL);


ALTER TABLE `accords`
  ADD CONSTRAINT `fk_accords_goods1` FOREIGN KEY (`goods_id`) REFERENCES `goods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_accords_providers1` FOREIGN KEY (`providers_id`) REFERENCES `providers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `goods`
  ADD CONSTRAINT `fk_goods_goods_type1` FOREIGN KEY (`goods_type_type`) REFERENCES `goods_type` (`type`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `goods_params`
  ADD CONSTRAINT `fk_goods_params_goods_params_name1` FOREIGN KEY (`goods_params_name_id`) REFERENCES `goods_params_name` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `goods_params_name`
  ADD CONSTRAINT `fk_goods_params_name_goods_type1` FOREIGN KEY (`goods_type_type`) REFERENCES `goods_type` (`type`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `goods_t_discs`
  ADD CONSTRAINT `fk_goods_t_discs_goods` FOREIGN KEY (`goods_id`) REFERENCES `goods` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `goods_t_tyre`
  ADD CONSTRAINT `fk_goods_t_tyre_goods10` FOREIGN KEY (`goods_id`) REFERENCES `goods` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `offers`
  ADD CONSTRAINT `fk_offers_goods1` FOREIGN KEY (`goods_id`) REFERENCES `goods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_offers_providers1` FOREIGN KEY (`providers_id`) REFERENCES `providers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
