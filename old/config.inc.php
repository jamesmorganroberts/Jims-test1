<?php
/* $Id: config.inc.php,v 1.151 2002/11/08 22:20:22 robbat2 Exp $ */
// vim: expandtab sw=4 ts=4 sts=4:

/**
 * phpMyAdmin Configuration File
 *
 * All directives are explained in Documentation.html
 */


/**
 * Sets the PHP error reporting - Please do not change this line!
 */
if (!isset($old_error_reporting)) {
    error_reporting(E_ALL);
    @ini_set('display_errors', '1');
}


/**
 * Your phpMyAdmin URL
 *
 * Complete the variable below with the full URL ie
 *    http://www.your_web.net/path_to_your_phpMyAdmin_directory/
 *
 * It must contain characters that are valid for a URL, and the path is
 * case sensitive on some Web servers, for example Unix-based servers.
 *
 * In most cases you can leave this variable empty, as the correct value
 * will be detected automatically. However, we recommend that you do
 * test to see that the auto-detection code works in your system. A good
 * test is to browse a table, then edit a row and save it.  There will be
 * an error message if phpMyAdmin cannot auto-detect the correct value.
 *
 * If the auto-detection code does work properly, you can set to TRUE the
 * $cfg['PmaAbsoluteUri_DisableWarning'] variable below.
 */
$cfg['PmaAbsoluteUri'] = 'http://s242703172.websitehome.co.uk/phpmyadmin/';


/**
 * Disable the default warning about $cfg['PmaAbsoluteUri'] not being set
 * You should use this if and ONLY if the PmaAbsoluteUri auto-detection
 * works perfectly.
 */
$cfg['PmaAbsoluteUri_DisableWarning'] = FALSE;

/**
 * Disable the default warning that is displayed on the DB Details Structure page if
 * any of the required Tables for the relationfeatures could not be found
 */
$cfg['PmaNoRelation_DisableWarning']  = FALSE;


/**
 * Server(s) configuration
 */
$i = 0;
// The $cfg['Servers'] array starts with $cfg['Servers'][1].  Do not use $cfg['Servers'][0].
// You can disable a server config entry by setting host to ''.
$i++;
$cfg['Servers'][$i]['host']          = 'db2437.oneandone.co.uk'; // MySQL hostname
$cfg['Servers'][$i]['port']          = '3306';          // MySQL port - leave blank for default port
$cfg['Servers'][$i]['socket']        = '';          // Path to the socket - leave blank for default socket
$cfg['Servers'][$i]['connect_type']  = 'tcp';       // How to connect to MySQL server ('tcp' or 'socket')
$cfg['Servers'][$i]['controluser']   = '';          // MySQL control user settings
                                                    // (this user must have read-only
$cfg['Servers'][$i]['controlpass']   = '';          // access to the "mysql/user"
                                                    // and "mysql/db" tables)
$cfg['Servers'][$i]['auth_type']     = 'config';    // Authentication method (config, http or cookie based)?
$cfg['Servers'][$i]['user']          = 'dbo325300139';      // MySQL user
$cfg['Servers'][$i]['password']      = 'sidfriend';          // MySQL password (only needed
                                                    // with 'config' auth_type)
$cfg['Servers'][$i]['only_db']       = '';          // If set to a db-name, only
                                                    // this db is displayed
                                                    // at left frame
                                                    // It may also be an array
                                                    // of db-names
$cfg['Servers'][$i]['verbose']       = '';          // Verbose name for this host - leave blank to show the hostname

$cfg['Servers'][$i]['pmadb']         = '';          // Database used for Relation, Bookmark and PDF Features
                                                    // - leave blank for no support
$cfg['Servers'][$i]['bookmarktable'] = '';          // Bookmark table - leave blank for no bookmark support
$cfg['Servers'][$i]['relation']      = '';          // table to describe the relation between links (see doc)
                                                    //   - leave blank for no relation-links support
$cfg['Servers'][$i]['table_info']    = '';          // table to describe the display fields
                                                    //   - leave blank for no display fields support
$cfg['Servers'][$i]['table_coords']  = '';          // table to describe the tables position for the PDF
                                                    //   schema - leave blank for no PDF schema support
$cfg['Servers'][$i]['pdf_pages']     = '';          // table to describe pages of relationpdf
                                                    // - leave blank if you don't want to use this
$cfg['Servers'][$i]['column_comments']              // table to store columncomments
                                     = '';          // - leave blank if you don't want to use this
$cfg['Servers'][$i]['AllowDeny']['order']           // Host authentication order, leave blank to not use
                                     = '';
$cfg['Servers'][$i]['AllowDeny']['rules']           // Host authentication rules, leave blank for defaults
                                     = array();


?>
