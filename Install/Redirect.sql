# Dump of table cms_redirect_category
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cms_redirect_redirect`;

CREATE TABLE `cms_redirect_redirect` (
  `id` int(10) NOT NULL auto_increment COMMENT 'ID',
  `url` varchar(128) NOT NULL COMMENT '实际地址',
  `preurl` varchar(128) NOT NULL COMMENT '当前地址',
  `inputtime` int NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `inputtime` (`inputtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;