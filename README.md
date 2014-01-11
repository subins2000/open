Open
====
An Open Source Social Network. Available @ http://open.subinsb.com
Feel free to contribute and please report bugs if you find any.

If you need help, documentation and other info about Open, please see our blog : http://open.subinsb.com/blog

Note that the blog folder is not included in source code. The blog is ran using Wordpress.
Run On Localhost
====
If you want to test Open on your Localhost server, you should do the following steps :

1) Download The Git

2) Place the Git folder "open" in /var/www (Linux). The entire files will be now @ /var/www/open

3) Using Replacing Softwares like regexxer(http://regexxer.sourceforge.net/), replace the text "open.subinsb.com" with "localhost/open" on the /var/www/open directory

4) Create a MySQL database named "open"

5) Execute The SQL Code : http://pastebin.com/F9qX8MXx

6) Go To http://localhost/open to see Open live in action

Libraries used In Open
====
jQuery
The @Mentions have been implemented on Open by using jQuery sMention Plugin (https://github.com/subins2000/smention)
PHP OAuth Class(http://www.phpclasses.org/package/7700-PHP-Authorize-and-access-APIs-using-OAuth.html). Thanks Manuel Lemos
