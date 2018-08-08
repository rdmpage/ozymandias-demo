# ozymandias-demo
Ozymandias




## Server notes

### Blazegraph

If loading times are getting very slow, specially when reloading data and experimenting you may want to start from scratch. To do this stop the server, delete the file ```blazegraph.jnl``` and restart blazegraph.

### nginx

I use nginx to act as reverse proxy for Blazegraph running on Windows. When uploading data I often got **HTTP 413 Request Entity Too Large** errors, which can be fixed by setting ```client_max_body_size``` to a suitable value in the ```server``` part of nginx.conf file, for example:

```
client_max_body_size. 200M;
```

Another problem is the server timing out if Blazegraph is doing a task which takes a while (HTTP 504). To fix this and these settings to the **http** section:

```
proxy_connect_timeout       600;
proxy_send_timeout          600;
proxy_read_timeout          600;
send_timeout                600;
```

(See [How to Fix 504 Gateway Timeout using Nginx](https://www.scalescale.com/tips/nginx/504-gateway-time-out-using-nginx/)).

## Other notes

Beyond classifying people as researcher/non-researcher https://twitter.com/SiobhanLeachman/status/1025203488102334464

## Examples, errors, etc.

### Nice examples

http://localhost/~rpage/ozymandias-demo/?uri=https://biodiversity.org.au/afd/publication/%23creator/m-m-drummond



### Multiple author names

Variation in author names causes problems, e.g. http://localhost/~rpage/ozymandias-demo/?uri=https://biodiversity.org.au/afd/publication/a7cc7f8d-7e09-4cc8-916c-423b21b19d98 
- T. Y. Chan
- T.-Y. Chan
- T. Y Chan
- T-Y Chan
- T-Y. Chan

All due to missing “.” and “-“.

