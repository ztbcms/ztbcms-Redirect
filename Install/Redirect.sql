# Dump of table cms_redirect_category
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cms_redirect_redirect`;

CREATE TABLE `cms_redirect_redirect` (
  `id` int(10) NOT NULL auto_increment COMMENT 'ID',
  `url` varchar(128) NOT NULL COMMENT '实际地址',
  `short_id` varchar(128) NOT NULL COMMENT '当前地址',
  `frequency` int(10) NOT NULL DEFAULT 0 COMMENT '访问频率',
  `input_time` int NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `input_time` (`input_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;