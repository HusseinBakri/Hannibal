# Hannibal
Hannibal is an PHP7 adaptive engine for 3D Web digital heritage artefacts in Web-Based Virtual Museums (WBVMs). It uses a forward chaining expert system hooked to a <a href="https://www.mysql.com/" target="_blank">MySQL</a> Database Management System (DBMS). Hannibal is a middleware that works with [Omeka](https://omeka.org/ "Omeka DAMS") Digital Asset Management System (DAMS) as a backend and with a <a href="https://leafletjs.com/" target="_blank">leaflet JS</a> map-based Web-Based Virtual Museum on the front end. 

Hannibal is presented in repository in a way that allows you to use it in your virtual museum. You can also alter it to be used in any adaptive application that requires doing an action based on a set of decisions.

The adaptive engine is named after _Hannibal Barca_, the hero of the Phoenician Carthaginians who was himself
adaptive and cunning in his war tactics against the Romans during the Punic wars.

Hannibal is integrated in a small subset clone of a WBVM (for the Shetland musuem (Scotland)) built for the [EULAC](https://eu-lac.org/map/?page=europe) Web-Based Virtual Museum initiative, a complete web-based virtual museum infrastructure developed by researchers in the Open Virtual World research group at the University of St Andrews, that aims to replicate all the functions of traditional museums on the web providing curators a management interface where they can upload digitised 3D models and other media and their metadata, in addition to providing documentation in the form of wiki articles.


## Notez Bien
Due to copyrighted material the Web-Based Virtual Museum clone subset pertaining to [EULAC](https://eu-lac.org/map/?page=europe) will not be uploaded. I do not have permission of showing digitised material pertaining to the musuem. In addition the aim of this repository is the expert engine and the adaptive engine (precisely detection component of network conditions and WebGL Benchmark).

## Requirements
The following are configuration done on a Ubuntu Server 16.04.X
I would assume you have Apache Web server. If not install the following packages:
```
sudo apt-get install apache2 apache2-doc apache2-utils
sudo apt-get install libapache2-mod-php5 php5 php-pear php5-xcache

#Install php7 (PS: it will install php7 now not php5 by default)
sudo apt-get install php

#or install explicitly PHP7
sudo apt-get install php7.0

```
You should have MySQL on your server if not, install the following:
```
#For MySQL server
sudo  apt-get install php5-mysql		#for php5 (but who uses it anymore 
sudo apt-get install php7.0-mysql		#for php7
sudo apt-get install mysql-server mysql-client

Other Necessary Packages
sudo apt-get install build-essential
sudo apt-get install libncurses5-dev libncursesw5-dev libreadline6-dev 
sudo apt-get install libbz2-dev libexpat1-dev liblzma-dev zlib1g-dev

```
Install the Python virtual environment. It is usually a better practice to install Python packages in a Python virtual environment that in the whole server system using pip of the system.

```
sudo apt-get install python-virtualenv
```

Since our WBVM is talking to Omeka so we are using the Omeka Client Python: [omeka-client-py](https://github.com/jimsafley/omeka-client-py) by Jim Safley. The needed Python modules are markdown, httplib2, pyyaml, and urllib3. 

So you have to make sure Apache can have access to all Python modules required in this library. As an easy and headache free technique is to install the Python modules as I have mention in a Python Virtual Enviornment inside your Apache /var/www/html/SOMETHING. So navigate to such directory.

```
virtualenv env

#This will install a small python environment inside the map folder with pip and other essential modules.
#To check the pip version of the virtual environment
env/bin/pip --version

#To check the Python version of the virtual environment
env/bin/python --version

#Now activate the python virtual environment
source env/bin/activate

#Now install all the needed python modules inside the python virtual environment
env/bin/pip install markdown
env/bin/pip install httplib2
env/bin/pip install pyyaml
env/bin/pip install urllib3

```
I had some problems installing the EULAC WBVM containing Hannibal on many Linux servers and this was due to problems in permissions so make sure you have these set correctly.

Since both Hannibal and the WBVM talks to MySQL DBMS so make you sure you configure these folks correctly.
```
#Setting up MySQL users and root user

sudo mysql -uroot –p
#This should ask you for the password you entered before during the installation, enter it. Write this down (important).
#THEN (IMPORTANT) install phpmyadmin

sudo apt-get install phpmyadmin

#This will start installing the packages. You will be asked which Web Server is to be used. Choose apache2 
```

Now  for the Nginx server and the folks who are in love with it, Hannibal itself without its backend (Decimated models are stored in Omeka) works gracefully with this web server but not Omeka (the backend). As you know probably, Omeka needs the Apache Rewrite rules to work and access .htaccess files. I tried to create a solution for nginx following material online but to no avail.

For Omeka to work gracefully with Apache Web Server you need to enable mod_rewrite in Apache.
```
sudo a2enmod rewrite
#Restart apache2 after that for things to take effect
sudo systemctl restart apache2    

```

You need also to change /etc/apache2/apache2.conf for effective rewrite. So have something similar to the following:

```
<Directory /var/www/>
        Options Indexes FollowSymLinks
        AllowOverride None
        Require all granted
</Directory>
```

and change it to
```
<Directory /var/www/>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
</Directory>
```
then  restart Apache

```
sudo service apache2 restart
```
