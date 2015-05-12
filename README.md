elections.org.my
================
- Based on data by TindakMalaysia
- Example Docker deployment forthcoming
- Copy over env-example to env and fill in with your own credentials/data
- nginx config fairly standard; relevant snippet below (code is under /var/www/public/)..
```
    listen       80;
    server_name ec.192.168.99.100.xip.io;

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

Test Cases
===========
- Scenario #1: Raw EC data --> http://ec.192.168.99.100.xip.io:32769/ec-process-daftar.php
- Scenario #2: Raw MapIt data --> http://ec.192.168.99.100.xip.io:32769/ec-process-mapit.php
- Scenario #3: UI/UX --> http://ec.192.168.99.100.xip.io:32769/ec-parse-mapit-result-test.php

