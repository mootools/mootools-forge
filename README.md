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
Make sure the connection information and database name specified in `<project>/config/database.yml` are correct and set up.

##### generate-db-classes.sh
Generates the required Propel Base-classes, which are extended by the different model- filters- and form-classes in the `<project>/lib` directory.

##### load-db-data.sh
Loads some base data (from the `<project>/data/fixtures` directory) into the database.
Make sure you have created both the database tables and database classes at this point.
