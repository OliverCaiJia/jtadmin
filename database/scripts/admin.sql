/*
SQLyog Enterprise v12.09 (64 bit)
MySQL - 5.6.34 : Database - admin
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `admin_permission_role` */

CREATE TABLE `admin_permission_role` (
  `permission_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `admin_permission_role` */

/*Table structure for table `admin_permissions` */

CREATE TABLE `admin_permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '权限名',
  `label` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '权限解释名称',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '描述与备注',
  `cid` tinyint(4) NOT NULL COMMENT '级别',
  `icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '图标',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `admin_permissions` */

insert  into `admin_permissions`(`id`,`name`,`label`,`description`,`cid`,`icon`,`created_at`,`updated_at`) values (1,'admin.permission','权限管理','',0,'fa-users','2016-05-21 10:06:50','2016-06-22 13:49:09'),(2,'admin.permission.index','权限列表','',1,'','2016-05-21 10:08:04','2016-05-21 10:08:04'),(3,'admin.permission.create','权限添加','',1,'','2016-05-21 10:08:18','2016-05-21 10:08:18'),(4,'admin.permission.edit','权限修改','',1,'','2016-05-21 10:08:35','2016-05-21 10:08:35'),(5,'admin.permission.destroy ','权限删除','',1,'','2016-05-21 10:09:57','2016-05-21 10:09:57'),(6,'admin.role.index','角色列表','',1,'','2016-05-23 10:36:40','2016-05-23 10:36:40'),(7,'admin.role.create','角色添加','',1,'','2016-05-23 10:37:07','2016-05-23 10:37:07'),(8,'admin.role.edit','角色修改','',1,'','2016-05-23 10:37:22','2016-05-23 10:37:22'),(9,'admin.role.destroy','角色删除','',1,'','2016-05-23 10:37:48','2016-05-23 10:37:48'),(10,'admin.user.index','用户管理','',1,'','2016-05-23 10:38:52','2016-05-23 10:38:52'),(11,'admin.user.create','用户添加','',1,'','2016-05-23 10:39:21','2016-06-22 13:49:29'),(12,'admin.user.edit','用户编辑','',1,'','2016-05-23 10:39:52','2016-05-23 10:39:52'),(13,'admin.user.destroy','用户删除','',1,'','2016-05-23 10:40:36','2016-05-23 10:40:36');

/*Table structure for table `admin_role_user` */

CREATE TABLE `admin_role_user` (
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `admin_role_user` */

/*Table structure for table `admin_roles` */

CREATE TABLE `admin_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色名称',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '备注',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `admin_roles` */

/*Table structure for table `admin_users` */

CREATE TABLE `admin_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员用户表ID',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `admin_users` */

insert  into `admin_users`(`id`,`name`,`email`,`password`,`remember_token`,`created_at`,`updated_at`) values (1,'root','root@root.com','$2y$10$a9aLgvjtHas3faIT2CLDxea1pldr.MLS4w0VgHgCmWshUn/bzCqG6','LUgwSfGz6B4svwGDa8DeBCi1kEnYBvc2y4si5wI0Tb5yfTPNej49FQvOzSSQ','2017-01-09 16:06:00','2017-01-10 22:02:18');

/*Table structure for table `password_resets` */

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `password_resets` */

/*Table structure for table `users` */

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `users` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
