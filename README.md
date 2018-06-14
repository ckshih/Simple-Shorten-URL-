# 1. Usage

Two way to make a long url to a short new url:

  - UI website
  - Restful POST API

### a. UI website

  - Website URL : [here].
  - Fill the long url on text box, and then click **Short it!**.
  - You will get the short url.

### b. Restful POST API
  - API URL : http://172.104.106.119/api.php
  - Use curl way to get shorten url.
  - Example:
  ```sh
    $ curl -X POST -d "url=http://www.google.com" http://172.104.106.119/api.php
  ```
  - Replace google url with your URL after **url=**.
  - You will get JSON type format {"status":[0|1], "msg":"[OK | Fail]", "shortURL":"[shorten url]"}.

# 2.Setup
### Environment:
  - FreeBSD 11.1-RELEASE(recommend) or Other Linux
  - Apache24
  - MySQL 5.6
  - PHP 5.6
### Apache Installation
```sh
    # pkg install apache24 
```
After installation, add auto start script to /etc/rc.conf, or use
```sh
    # sysrc apache24_enable=yes 
```
And then start the apaceh:
```sh
     # service apache24 start 
```
### MySQL Installation
```sh
    # pkg install mysql56-server 
```
After installation, add auto start script to /etc/rc.conf, or use
```sh
    # sysrc mysql_enable=yes 
```
And then start the mysql:
```sh
     # service mysql-server start
```
Final start up MySQL
```sh
    # mysql_secure_installation 
```
### PHP Installation
```sh
    # pkg install php56 php56-extensions php56-mysql php56-mysql php56-mysqli 
```
After installation, copy a setting file php.ini to specific place
```sh
    # cp /usr/local/etc/php.ini-production /usr/local/etc/php.ini
    # rehash
```
### Other Setting
We have to edit some setting in apache setting file, so 
```sh
    # vim /usr/local/etc/apache24/httpd.conf 
```
find module 'mod_rewrite' and enable it!(Remove quote #)
```sh
     # --- Remove prefix # symbol --- #
    LoadModule rewrite_module libexec/apache24/mod_rewrite.so
```
Find your apache website root block, usuall be **<Directory /usr/local/www/apache24/data>**. And set AllowOverride to All:
```sh
<Directory /usr/local/www/apache24/data>
    options Indexes FollowSymLinks
    # --- set it to All from None --- #
    AllowOverride All 
    Require all granted
< /Directory>
```
Find 'DirectoryIndex' block and set it to:
```sh
    <IfModule dir_module>
        DirectoryIndex index.php index.html index.htm
    </IfModule>
```
At the end of setting, add below script in the end of file
```sh
    <FilesMatch "\.php$">
        SetHandler application/x-httpd-php
    </FilesMatch>
    <FilesMatch "\.phps$">
        SetHandler application/x-httpd-php-source
    </FilesMatch>
```
Restart Apache server:
```sh
     # service apache24 restart 
```
### Create a New Database
Create a database named **shorten** and a table named **shortentable** with only three colmun:
| Name | Type | other |
| ------ | ------ | ------ |
| ID | int(11) | AUTO_INCREMENT, PRIMARY |
| oriURL | varchar(2000) ||
| shortURL | varchar(6)  ||

### Move All file to Website Root
There are six php files and one.htaccess file and move them to website root, usually be /usr/local/www/apache24/data

### Set Database Account and Password
Change ***$userName*** and  ***$password*** to your account and password in **sqlconfig.php** file.
# 3. All Done!
I think it will work fine after above! Enjoy it!


   [here]: <http://172.104.106.119>
 
