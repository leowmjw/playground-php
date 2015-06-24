elections.org.my: Malaysian EC Done Right!!
==============================================
- Inspired by the NZ Electoral Commission --> http://www.elections.org.nz/voters/find-my-electorate
- Based on liberated electoral boundaries data obtained via manual geo-referencing 
  by volunteers from TindakMalaysia (http://www.tindakmalaysia.org). 
  The maps are from the last re-delineation process >10 years ago + 2013 Election 
- Uses and demonstrates integration with MapIt (http://mapit.poplus.org/), one 
  of the Poplus Components --> http://poplus.org/components/current/

Scenarios
==========
Scenario #1
-----------
Objective: Check Electoral Boundaries (PAR, DUN, DM) data of voter based on IC number
and address. Also, it allows the voter to double check EC data (official) against data contributed
by TindakMalaysia so that any error can be reported back.

The official Electoral Boundary data which has been only obtainable in physical maps; 
is not freely available online for voter inspection and objection.

As of today, Electoral Boundary data continues to be restricted and obfuscated by 
the EC of Malaysia; see the case of the state of Sarawak re-delineation proposal, 
the detailed Voting District; "Daerah Mengundi" (DM) information was purposely removed!!

Scenario #2
------------
Objective: Demo the MapIt API for rendering PAR, DUN, DM and AREA shapefiles 
based on postcode lookup.

Development Environment Setup
==============================
0) Assumption: You have a Google Developer Account with API Key; 
   and you have white-listed your IP address in the Google Developer panel.  
   Otherwise, likely the Google Geocode call will fail.  Debug using tools below ..

1) Get Docker
    a) OSX, Mac: Use Kitematic; it is awesome!! --> https://kitematic.com/
    b) Windows: Kitematci coming soon! --> https://blog.docker.com/2015/06/kitematic-windows-alpha/

2) Run the containers needed (nginx + php-fpm); linking to the source code  
   Example below based on Kitematic running in MacOSX; source code cloned to 
   master branch at "/Users/leow/Desktop/PROJECTS/SINARPROJECT/elections.org.my"
```

# Below runs a standard nginx container 
> docker run -d -p 80:80 -h "nginx" --name=nginx leowmjw/nginx

# Below runs; replace with your own absolute path to source code
> docker run -d -p 9999:9000 \ 
    -h "ec-php" --name=ec-php \ 
    -v /Users/leow/Desktop/PROJECTS/SINARPROJECT/elections.org.my:/var/www \ 
    leowmjw/ec-php:fpm

```

3) Ensure containers is setup to handle PHP and with the correct VirtualHosts
    a) From within the "ec-php" container; use Composer to get the needed vendor libs
```

# Click the "Docker CLI" button within Kitematic ..
host> docker exec -it <<ec-php-container_id>> /bin/bash
ec-php# cd /var/www
ec-php# composer install 
ec-php# .... << vendor libs getting installed >> ..

```
    b) Still in "ec-php" container; copy over env-example to env and fill in 
       with your own credentials/data for Google API Keys 
    c) Ensure nginx config is pointing to the code; relevant snippet below.
       Document root is at "/var/www/public/"
```
    listen       80;
    server_name  ec.192.168.99.100.xip.io;

    #charset koi8-r;
    #access_log  /var/log/nginx/log/host.access.log  main;

    location / {
        root   /var/www/public;
        index  index.php index.html index.htm;
        try_files $uri $uri/ /index.php?$query_string;
    }

    #error_page  404              /404.html;

    # redirect server error pages to the static page /50x.html
    #
    error_page   500 502 503 504  /50x.html;
    location = /50x.html {
        root   /usr/share/nginx/html;
    }

    location ~ \.php$ {
        fastcgi_pass   192.168.99.100:9999;
        include        fastcgi_params;
        fastcgi_param  SCRIPT_FILENAME  /var/www/public/$fastcgi_script_name;
    }

```

4) Test!
   a) Scenario #1: http://ec.192.168.99.100.xip.io/
   b) Scenario #2: http://ec.192.168.99.100.xip.io/mapit
   c) If EC site seems slow; enable injection of Dummy data by modifying the 
      following section in /public/index.php (uncomment the Dummy implementation 
      and comment the actual implementation)
```
    // Dummy tests below; should be further to be injected in ..
    // $ec_site = new \EC\ECSiteDummy($myic);
    $ec_site = new \EC\ECSite($myic);

```

Debugging Tools
===============
1) Debug Raw EC data --> http://ec.192.168.99.100.xip.io/ec-process-daftar.php
2) Debug Raw MapIt data --> http://ec.192.168.99.100.xip.io/ec-process-mapit.php
3) Debug UI/UX mock --> http://ec.192.168.99.100.xip.io/ec-parse-mapit-result-test.php

What's Next?
==============
1) Try a more modern UI using ReactJS --> http://facebook.github.io/react/
2) Refactor code; and add better tests using Codeception --> http://codeception.com/
3) Add libraries so facilitate integration with PopIt; using the documented API 
   interfaces found at: https://sinarproject.hackpad.com/Sinar-API-EdqB22nfSBe
4) Try a better mobile-first interface (using the same API backend); using 
   Reapp --> http://reapp.io/
5) Better performance via lazy loading/caching of data from the EC and MapIt backends

Feel free to fork ^^; report issues, give suggestions, help out or contribute PRs!

