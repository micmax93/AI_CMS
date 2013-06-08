<?php defined('SYSPATH') OR die('No direct script access.'); ?>

2013-06-08 11:32:45 --- ERROR: HTTP_Exception_404 [ 404 ]: The requested URL / was not found on this server. ~ SYSPATH/classes/kohana/request/client/internal.php [ 87 ]
2013-06-08 11:32:45 --- STRACE: HTTP_Exception_404 [ 404 ]: The requested URL / was not found on this server. ~ SYSPATH/classes/kohana/request/client/internal.php [ 87 ]
--
#0 /home/micmax93/git/AI_CMS/cms/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#1 /home/micmax93/git/AI_CMS/cms/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#2 /home/micmax93/git/AI_CMS/cms/index.php(109): Kohana_Request->execute()
#3 {main}
2013-06-08 11:36:08 --- ERROR: Database_Exception [ 0 ]: pg_connect(): Unable to connect to PostgreSQL server: FATAL:  password authentication failed for user &quot;cms&quot;
FATAL:  password authentication failed for user &quot;cms&quot; ~ MODPATH/kohana-postgresql/classes/kohana/database/postgresql.php [ 84 ]
2013-06-08 11:36:08 --- STRACE: Database_Exception [ 0 ]: pg_connect(): Unable to connect to PostgreSQL server: FATAL:  password authentication failed for user &quot;cms&quot;
FATAL:  password authentication failed for user &quot;cms&quot; ~ MODPATH/kohana-postgresql/classes/kohana/database/postgresql.php [ 84 ]
--
#0 /home/micmax93/git/AI_CMS/cms/modules/kohana-postgresql/classes/kohana/database/postgresql.php(380): Kohana_Database_PostgreSQL->connect()
#1 /home/micmax93/git/AI_CMS/cms/modules/database/classes/kohana/database.php(478): Kohana_Database_PostgreSQL->escape('admin')
#2 /home/micmax93/git/AI_CMS/cms/modules/database/classes/kohana/database/query/builder.php(116): Kohana_Database->quote('admin')
#3 /home/micmax93/git/AI_CMS/cms/modules/database/classes/kohana/database/query/builder/select.php(366): Kohana_Database_Query_Builder->_compile_conditions(Object(Database_PostgreSQL), Array)
#4 /home/micmax93/git/AI_CMS/cms/modules/database/classes/kohana/database/query.php(228): Kohana_Database_Query_Builder_Select->compile(Object(Database_PostgreSQL))
#5 /home/micmax93/git/AI_CMS/cms/modules/orm/classes/kohana/orm.php(1002): Kohana_Database_Query->execute(Object(Database_PostgreSQL))
#6 /home/micmax93/git/AI_CMS/cms/modules/orm/classes/kohana/orm.php(910): Kohana_ORM->_load_result(false)
#7 /home/micmax93/git/AI_CMS/cms/application/classes/model/user.php(65): Kohana_ORM->find()
#8 /home/micmax93/git/AI_CMS/cms/application/classes/controller/user.php(36): Model_User->getUserByUsername('admin')
#9 [internal function]: Controller_User->action_authorize()
#10 /home/micmax93/git/AI_CMS/cms/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_User))
#11 /home/micmax93/git/AI_CMS/cms/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#12 /home/micmax93/git/AI_CMS/cms/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#13 /home/micmax93/git/AI_CMS/cms/index.php(109): Kohana_Request->execute()
#14 {main}
2013-06-08 11:36:10 --- ERROR: Database_Exception [ 0 ]: pg_connect(): Unable to connect to PostgreSQL server: FATAL:  password authentication failed for user &quot;cms&quot;
FATAL:  password authentication failed for user &quot;cms&quot; ~ MODPATH/kohana-postgresql/classes/kohana/database/postgresql.php [ 84 ]
2013-06-08 11:36:10 --- STRACE: Database_Exception [ 0 ]: pg_connect(): Unable to connect to PostgreSQL server: FATAL:  password authentication failed for user &quot;cms&quot;
FATAL:  password authentication failed for user &quot;cms&quot; ~ MODPATH/kohana-postgresql/classes/kohana/database/postgresql.php [ 84 ]
--
#0 /home/micmax93/git/AI_CMS/cms/modules/kohana-postgresql/classes/kohana/database/postgresql.php(380): Kohana_Database_PostgreSQL->connect()
#1 /home/micmax93/git/AI_CMS/cms/modules/database/classes/kohana/database.php(478): Kohana_Database_PostgreSQL->escape('admin')
#2 /home/micmax93/git/AI_CMS/cms/modules/database/classes/kohana/database/query/builder.php(116): Kohana_Database->quote('admin')
#3 /home/micmax93/git/AI_CMS/cms/modules/database/classes/kohana/database/query/builder/select.php(366): Kohana_Database_Query_Builder->_compile_conditions(Object(Database_PostgreSQL), Array)
#4 /home/micmax93/git/AI_CMS/cms/modules/database/classes/kohana/database/query.php(228): Kohana_Database_Query_Builder_Select->compile(Object(Database_PostgreSQL))
#5 /home/micmax93/git/AI_CMS/cms/modules/orm/classes/kohana/orm.php(1002): Kohana_Database_Query->execute(Object(Database_PostgreSQL))
#6 /home/micmax93/git/AI_CMS/cms/modules/orm/classes/kohana/orm.php(910): Kohana_ORM->_load_result(false)
#7 /home/micmax93/git/AI_CMS/cms/application/classes/model/user.php(65): Kohana_ORM->find()
#8 /home/micmax93/git/AI_CMS/cms/application/classes/controller/user.php(36): Model_User->getUserByUsername('admin')
#9 [internal function]: Controller_User->action_authorize()
#10 /home/micmax93/git/AI_CMS/cms/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_User))
#11 /home/micmax93/git/AI_CMS/cms/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#12 /home/micmax93/git/AI_CMS/cms/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#13 /home/micmax93/git/AI_CMS/cms/index.php(109): Kohana_Request->execute()
#14 {main}
2013-06-08 11:36:15 --- ERROR: Database_Exception [ 0 ]: pg_connect(): Unable to connect to PostgreSQL server: FATAL:  password authentication failed for user &quot;cms&quot;
FATAL:  password authentication failed for user &quot;cms&quot; ~ MODPATH/kohana-postgresql/classes/kohana/database/postgresql.php [ 84 ]
2013-06-08 11:36:15 --- STRACE: Database_Exception [ 0 ]: pg_connect(): Unable to connect to PostgreSQL server: FATAL:  password authentication failed for user &quot;cms&quot;
FATAL:  password authentication failed for user &quot;cms&quot; ~ MODPATH/kohana-postgresql/classes/kohana/database/postgresql.php [ 84 ]
--
#0 /home/micmax93/git/AI_CMS/cms/modules/kohana-postgresql/classes/kohana/database/postgresql.php(380): Kohana_Database_PostgreSQL->connect()
#1 /home/micmax93/git/AI_CMS/cms/modules/database/classes/kohana/database.php(478): Kohana_Database_PostgreSQL->escape('admin')
#2 /home/micmax93/git/AI_CMS/cms/modules/database/classes/kohana/database/query/builder.php(116): Kohana_Database->quote('admin')
#3 /home/micmax93/git/AI_CMS/cms/modules/database/classes/kohana/database/query/builder/select.php(366): Kohana_Database_Query_Builder->_compile_conditions(Object(Database_PostgreSQL), Array)
#4 /home/micmax93/git/AI_CMS/cms/modules/database/classes/kohana/database/query.php(228): Kohana_Database_Query_Builder_Select->compile(Object(Database_PostgreSQL))
#5 /home/micmax93/git/AI_CMS/cms/modules/orm/classes/kohana/orm.php(1002): Kohana_Database_Query->execute(Object(Database_PostgreSQL))
#6 /home/micmax93/git/AI_CMS/cms/modules/orm/classes/kohana/orm.php(910): Kohana_ORM->_load_result(false)
#7 /home/micmax93/git/AI_CMS/cms/application/classes/model/user.php(65): Kohana_ORM->find()
#8 /home/micmax93/git/AI_CMS/cms/application/classes/controller/user.php(36): Model_User->getUserByUsername('admin')
#9 [internal function]: Controller_User->action_authorize()
#10 /home/micmax93/git/AI_CMS/cms/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_User))
#11 /home/micmax93/git/AI_CMS/cms/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#12 /home/micmax93/git/AI_CMS/cms/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#13 /home/micmax93/git/AI_CMS/cms/index.php(109): Kohana_Request->execute()
#14 {main}
2013-06-08 11:36:23 --- ERROR: Database_Exception [ 0 ]: pg_connect(): Unable to connect to PostgreSQL server: FATAL:  password authentication failed for user &quot;cms&quot;
FATAL:  password authentication failed for user &quot;cms&quot; ~ MODPATH/kohana-postgresql/classes/kohana/database/postgresql.php [ 84 ]
2013-06-08 11:36:23 --- STRACE: Database_Exception [ 0 ]: pg_connect(): Unable to connect to PostgreSQL server: FATAL:  password authentication failed for user &quot;cms&quot;
FATAL:  password authentication failed for user &quot;cms&quot; ~ MODPATH/kohana-postgresql/classes/kohana/database/postgresql.php [ 84 ]
--
#0 /home/micmax93/git/AI_CMS/cms/modules/kohana-postgresql/classes/kohana/database/postgresql.php(380): Kohana_Database_PostgreSQL->connect()
#1 /home/micmax93/git/AI_CMS/cms/modules/database/classes/kohana/database.php(478): Kohana_Database_PostgreSQL->escape('admin')
#2 /home/micmax93/git/AI_CMS/cms/modules/database/classes/kohana/database/query/builder.php(116): Kohana_Database->quote('admin')
#3 /home/micmax93/git/AI_CMS/cms/modules/database/classes/kohana/database/query/builder/select.php(366): Kohana_Database_Query_Builder->_compile_conditions(Object(Database_PostgreSQL), Array)
#4 /home/micmax93/git/AI_CMS/cms/modules/database/classes/kohana/database/query.php(228): Kohana_Database_Query_Builder_Select->compile(Object(Database_PostgreSQL))
#5 /home/micmax93/git/AI_CMS/cms/modules/orm/classes/kohana/orm.php(1002): Kohana_Database_Query->execute(Object(Database_PostgreSQL))
#6 /home/micmax93/git/AI_CMS/cms/modules/orm/classes/kohana/orm.php(910): Kohana_ORM->_load_result(false)
#7 /home/micmax93/git/AI_CMS/cms/application/classes/model/user.php(65): Kohana_ORM->find()
#8 /home/micmax93/git/AI_CMS/cms/application/classes/controller/user.php(36): Model_User->getUserByUsername('admin')
#9 [internal function]: Controller_User->action_authorize()
#10 /home/micmax93/git/AI_CMS/cms/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_User))
#11 /home/micmax93/git/AI_CMS/cms/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#12 /home/micmax93/git/AI_CMS/cms/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#13 /home/micmax93/git/AI_CMS/cms/index.php(109): Kohana_Request->execute()
#14 {main}
2013-06-08 11:50:50 --- ERROR: View_Exception [ 0 ]: The requested view canvas could not be found ~ SYSPATH/classes/kohana/view.php [ 252 ]
2013-06-08 11:50:50 --- STRACE: View_Exception [ 0 ]: The requested view canvas could not be found ~ SYSPATH/classes/kohana/view.php [ 252 ]
--
#0 /home/micmax93/git/AI_CMS/cms/system/classes/kohana/view.php(137): Kohana_View->set_filename('canvas')
#1 /home/micmax93/git/AI_CMS/cms/system/classes/kohana/view.php(30): Kohana_View->__construct('canvas', NULL)
#2 /home/micmax93/git/AI_CMS/cms/application/classes/controller/cms.php(13): Kohana_View::factory('canvas')
#3 [internal function]: Controller_Cms->action_index()
#4 /home/micmax93/git/AI_CMS/cms/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Cms))
#5 /home/micmax93/git/AI_CMS/cms/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/micmax93/git/AI_CMS/cms/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/micmax93/git/AI_CMS/cms/index.php(109): Kohana_Request->execute()
#8 {main}