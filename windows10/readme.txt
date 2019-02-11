# Notes on server

Run Blazegraph on Windows 10, requires Java 7 which can be downloaded from Oracle if you create an account.

Set IP address of Windows 10 machine to an externally accessible IP address.

Use nginx as reverse proxy, send all traffic to Blazegraph:

...
        # forward to Blazegraph listening on 127.0.0.1:9999
        #
        location / {
            proxy_set_header   X-Real-IP $remote_addr;
            proxy_set_header   Host      $http_host;
            proxy_pass         http://127.0.0.1:9999;
        }
...


