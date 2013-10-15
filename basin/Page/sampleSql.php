<pre>
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
--
-- 数据库: `baiphp`
--
CREATE DATABASE `baiphp` _DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `baiphp`;
-- --------------------------------------------------------
--
-- 表的结构 `sample`
--
CREATE TABLE IF NOT EXISTS `sample` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(10) NOT NULL,
  `sex` tinyint(1) NOT NULL _DEFAULT '1',
  `age` int(3) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB _DEFAULT CHARSET=utf8;
--
-- 转存表中的数据 `sample`
--
INSERT INTO `sample` (`id`, `name`, `sex`, `age`) VALUES
(1, '张三', 1, 26),
(2, '李四', 1, 19),
(3, '王眉', 0, 22);
</pre>
