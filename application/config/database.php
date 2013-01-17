<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

#GETTINGSTARTED: Database connection settings
# Change the next three lines to your username/db password/database.
# Change CHANGEME to your ACM username

# This block is for the registration database.  The administration application pulls
# records from the registration database, approves them, and sends them off to LDAP.
$db['registration']['hostname'] = 'db-prod.acm.umn.edu';
$db['registration']['username'] = 'CHANGEME';
$db['registration']['password'] = '<YOUR_ACM_DB_PASSWORD>';
$db['registration']['database'] = 'CHANGEME_registration';
$db['registration']['dbdriver'] = 'mysql';
$db['registration']['dbprefix'] = '';
$db['registration']['pconnect'] = TRUE;
$db['registration']['db_debug'] = FALSE;
$db['registration']['cache_on'] = FALSE;
$db['registration']['cachedir'] = '';
$db['registration']['char_set'] = 'utf8';
$db['registration']['dbcollat'] = 'utf8_general_ci';
$db['registration']['swap_pre'] = '';
$db['registration']['autoinit'] = TRUE;
$db['registration']['stricton'] = FALSE;

$active_group = 'default';
$active_record = TRUE;

# This block is for the administration database.  This is where logging information is
# stored and approved users recorded.
$db['default']['hostname'] = 'db-prod.acm.umn.edu';
$db['default']['username'] = 'CHANGEME';
$db['default']['password'] = '<YOUR_ACM_DB_PASSWORD>';
$db['default']['database'] = 'CHANGEME_administration';
$db['default']['dbdriver'] = 'mysql';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = FALSE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;


/* End of file database.php */
/* Location: ./application/config/database.php */
