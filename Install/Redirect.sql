# Dump of table cms_redirect_category
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cms_redirect_redirect`;

CREATE TABLE `cms_redirect_redirect` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `url` varchar(1024) NOT NULL COMMENT '实际地址',
  `short_id` varchar(128) NOT NULL COMMENT '当前地址',
  `frequency` int(10) NOT NULL DEFAULT '0' COMMENT '访问频率',
  `input_time` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `short_id` (`short_id`),
  KEY `input_time` (`input_time`),
  KEY `url` (`url`(255))
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
SET FOREIGN_KEY_CHECKS=1;