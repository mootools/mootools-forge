The MooTools Forge
==================

The official repository for the MooTools Forge. This is a fork of the excellent [PluginsKit](https://github.com/guille/PluginsKit) by [Guillermo Rauch](https://github.com/guille).

Installation
------------

*In the text below, replace `<project>` with the directory you cloned the repository to (i.e. the directory this README file is in).*

To have a working forge, you firstly need a working webserver (like Apache HTTPD) with PHP and MySQL installed, and set up a virtual host with `<project>/web` as document root. You can find an example virtual host configuration in `<project>/config/vhost.sample`.

For the rest of the installation, there is an `install` directory with a number of shell scripts. These shell scripts wrap Symfony's functions and help you set up your own Forge environment quickly and easily.

##### permissions.sh
To start with the exception right away, this one does not wrap any of Symfony's functions. (We need a few more directories `chmod`ed than what Symfony's `project:permissions` one does.)
Changes permissions of directories to which the webserver process has to be able to write.

##### create-db-tables.sh
Creates SQL queries in the `<project>/data/sql` directory, then inserts them into the database.
Enter the right information in `<project>/config/databases.yml`, and make sure you have created the database before running this script.

The last part of the script will warn you that it will remove all data in this database. This shouldn't be a problem, since you (probably) have no data yet. If you do have data, you'll have to apply the changes manually. After running the command (and answering 'No' to removing your data) you can find the generated queries in `<project>/data/sql/lib.model.schema.sql`.

##### generate-db-classes.sh
Generates the required Propel Base-classes, which are extended by the different model- filters- and form-classes in the `<project>/lib` directory.

##### load-db-data.sh
Loads some base data (from the `<project>/data/fixtures` directory) into the database.
Make sure you have created both the database tables and database classes at this point.

Installation problems
---------------------

##### Cache
If you are getting `Access denied for user` or `Unknown database` errors even after updating your `<project>/config/database.yml`, see if `<project>/symfony cache:clear` helps.

##### Rewriting
If you are getting redirect loops (errors in your error log like `Request exceeded the limit of internal redirects`), make sure you have your `DocumentRoot` set up right, or uncomment the `RewriteBase` line in `<project>/web/.htaccess`.

##### XSLT errors
If you are getting errors like `Could not perform XLST transformation. Make sure PHP has been compiled/configured to support XSLT.` (when generating database classes or creating database tables) make sure you have a version of PHP with XSL support. In Debian or Ubuntu you'll have to install the `php5-xsl` package.
