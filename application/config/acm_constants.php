<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array();

# This is the OU where new users will be created.  It should not be modified.
$config['ACM_LDAP_USERS_OU'] = "ou=users,dc=dev,dc=acm,dc=umn,dc=edu";

# THis is the OU where administrator accounts exist.  It should not be modified.
$config['ACM_ADMIN_OU'] = "ou=users,ou=ns,dc=acm,dc=umn,dc=edu";

# GETTINGSTARTED: uid
# This is where you connect to our demo LDAP server so you can pretend to make accounts and whatnot.
# You connect using your Distinguished Name (DN):
#
# For most people, your DN looks like this:
#
# uid=<YOUR_ACM_USER>,ou=active,ou=users,ou=ns,dc=acm,dc=umn,dc=edu
#
# If you're an officer, your distinguished name looks like this:
#
# uid=<MYUSER>,ou=current,ou=officers,ou=active,ou=users,ou=ns,dc=acm,dc=umn,dc=edu
#
# If you want to find your DN, you can run a command like this on one of the ACM machines:
# > ldapsearch -x -h ldap.acm.umn.edu uid=YOUR_ACM_USER dn
$config['ACM_LDAP_BIND_DN'] = "uid=<YOUR_ACM_USER>,ou=active,ou=users,ou=ns,dc=acm,dc=umn,dc=edu";

# GETTINGSTARTED: password
# Put your password here.  Make sure do follow the directions on the wiki so that no one ganks your password.
$config['ACM_LDAP_BIND_PW'] = "<YOUR_ACM_PASSWORD_HERE>";

$config['ACM_LDAP_SERVER'] = "ldap://ldap.acm.umn.edu";

# GETTINGSTARTED: Password secret
# This must be the same as the password secret in your registration application.
$config['ACM_REGISTRATION_KEY'] = "supersecretpassword!";

?>
