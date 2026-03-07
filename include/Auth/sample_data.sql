SET FOREIGN_KEY_CHECKS = 0;

--
-- Sample data for table `tbl_rbac_commands`
--

SELECT 'tbl_rbac_commands';
/*!40000 ALTER TABLE `tbl_rbac_commands` DISABLE KEYS */;
LOCK TABLES `tbl_rbac_commands` WRITE;
#--------------------------------------|----|------------------|
# -- Columns --                        | id | name             |
#--------------------------------------|----|------------------|
INSERT INTO `tbl_rbac_commands` VALUES (  1,  'About');
INSERT INTO `tbl_rbac_commands` VALUES (  2,  'Dashboard');
INSERT INTO `tbl_rbac_commands` VALUES (  3,  'Login');
INSERT INTO `tbl_rbac_commands` VALUES (  4,  'Logout');
INSERT INTO `tbl_rbac_commands` VALUES (  5,  'HelpContent');
INSERT INTO `tbl_rbac_commands` VALUES (  6,  'HelpRbac');
INSERT INTO `tbl_rbac_commands` VALUES (  7,  'RbacCommandList');
INSERT INTO `tbl_rbac_commands` VALUES (  8,  'RbacCommandView');
INSERT INTO `tbl_rbac_commands` VALUES (  9,  'RbacRoleList');
INSERT INTO `tbl_rbac_commands` VALUES ( 10,  'RbacRoleView');
INSERT INTO `tbl_rbac_commands` VALUES ( 11,  'RbacUserList');
INSERT INTO `tbl_rbac_commands` VALUES ( 12,  'RbacUserView');
#--------------------------------------|----|------------------|
UNLOCK TABLES;
/*!40000 ALTER TABLE `tbl_rbac_commands` ENABLE KEYS */;

--
-- Sample data for table `tbl_rbac_permissions`
--

SELECT 'tbl_rbac_permissions';
/*!40000 ALTER TABLE `tbl_rbac_permissions` DISABLE KEYS */;
LOCK TABLES `tbl_rbac_permissions` WRITE;
#-----------------------------------------|----|------------|---------|
# -- Columns --                           | id | command_id | name    |
#-----------------------------------------|----|------------|---------|
INSERT INTO `tbl_rbac_permissions` VALUES (  1,  1,           'read');
INSERT INTO `tbl_rbac_permissions` VALUES (  2,  2,           'create');
INSERT INTO `tbl_rbac_permissions` VALUES (  3,  2,           'read');
INSERT INTO `tbl_rbac_permissions` VALUES (  4,  2,           'update');
INSERT INTO `tbl_rbac_permissions` VALUES (  5,  2,           'delete');
INSERT INTO `tbl_rbac_permissions` VALUES (  6,  3,           'read');
INSERT INTO `tbl_rbac_permissions` VALUES (  7,  4,           'read');
#-----------------------------------------|----|------------|---------|
UNLOCK TABLES;
/*!40000 ALTER TABLE `tbl_rbac_permissions` ENABLE KEYS */;

--
-- Sample data for table `tbl_rbac_roles`
--

SELECT 'tbl_rbac_roles';
/*!40000 ALTER TABLE `tbl_rbac_roles` DISABLE KEYS */;
LOCK TABLES `tbl_rbac_roles` WRITE;
#-----------------------------------|----|----------------------|
# -- Columns --                     | id | name                 |
#-----------------------------------|----|----------------------|
INSERT INTO `tbl_rbac_roles` VALUES (  1,  'Anonymous');
INSERT INTO `tbl_rbac_roles` VALUES (  2,  'NBM');
INSERT INTO `tbl_rbac_roles` VALUES (  3,  'Database Guardian');
INSERT INTO `tbl_rbac_roles` VALUES (  4,  'Administrator');
INSERT INTO `tbl_rbac_roles` VALUES (  5,  'Super Administrator');
#-----------------------------------|----|----------------------|
UNLOCK TABLES;
/*!40000 ALTER TABLE `tbl_rbac_roles` ENABLE KEYS */;

--
-- Sample data for table `tbl_rbac_role_permissions`
--

SELECT 'tbl_rbac_role_permissions';
/*!40000 ALTER TABLE `tbl_rbac_role_permissions` DISABLE KEYS */;
LOCK TABLES `tbl_rbac_role_permissions` WRITE;
#----------------------------------------------|----|---------|---------------|
# -- Columns --                                | id | role_id | permission_id |
#----------------------------------------------|----|---------|---------------|
INSERT INTO `tbl_rbac_role_permissions` VALUES (  1,         5,              1);
INSERT INTO `tbl_rbac_role_permissions` VALUES (  2,         5,              2);
INSERT INTO `tbl_rbac_role_permissions` VALUES (  3,         5,              3);
INSERT INTO `tbl_rbac_role_permissions` VALUES (  4,         5,              4);
INSERT INTO `tbl_rbac_role_permissions` VALUES (  5,         5,              5);
INSERT INTO `tbl_rbac_role_permissions` VALUES (  6,         5,              6);
INSERT INTO `tbl_rbac_role_permissions` VALUES (  7,         5,              7);
#----------------------------------------------|----|---------|---------------|
UNLOCK TABLES;
/*!40000 ALTER TABLE `tbl_rbac_role_permissions` ENABLE KEYS */;

--
-- Sample data for table `tbl_rbac_users`
--

SELECT 'tbl_rbac_users';
/*!40000 ALTER TABLE `tbl_rbac_users` DISABLE KEYS */;
LOCK TABLES `tbl_rbac_users` WRITE;
#-----------------------------------|----|--------|--------------|----------------------|-----------|
# -- Columns --                     | id | handle | password     | last_login           | is_active |
#-----------------------------------|----|--------|--------------|----------------------|-----------|
INSERT INTO `tbl_rbac_users` VALUES (   4, 'ian',   md5('munday'), '2007-01-01 01:00:00', '1'       );
INSERT INTO `tbl_rbac_users` VALUES (   2, 'david', md5('carter'), '2007-01-03 09:51:16', '1'       );
INSERT INTO `tbl_rbac_users` VALUES (   3, 'rob',   md5('anning'), '2007-01-03 14:02:22', '1'       );
#-----------------------------------|----|--------|--------------|----------------------|-----------|
UNLOCK TABLES;
/*!40000 ALTER TABLE `tbl_rbac_users` ENABLE KEYS */;

--
-- Sample data for table `tbl_rbac_users`
--

SELECT 'tbl_rbac_user_roles';
/*!40000 ALTER TABLE `tbl_rbac_user_roles` DISABLE KEYS */;
LOCK TABLES `tbl_rbac_user_roles` WRITE;
#----------------------------------------|----|---------|---------|
# -- Columns --                          | id | user_id | role_id |
#----------------------------------------|----|---------|---------|
INSERT INTO `tbl_rbac_user_roles` VALUES (   1,        1,        5);
INSERT INTO `tbl_rbac_user_roles` VALUES (   2,        2,        4);
INSERT INTO `tbl_rbac_user_roles` VALUES (   3,        3,        2);
INSERT INTO `tbl_rbac_user_roles` VALUES (   4,        3,        3);
#----------------------------------------|----|---------|---------|
UNLOCK TABLES;
/*!40000 ALTER TABLE `tbl_rbac_user_roles` ENABLE KEYS */;

SET FOREIGN_KEY_CHECKS = 1;