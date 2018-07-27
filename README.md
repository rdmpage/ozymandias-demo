# ozymandias-demo
Ozymandias




## Server notes

### nginx

I use nginx to act as reverse proxy for Blazegraph, when uploading data got **HTTP 413 Request Entity Too Large** errors, which can be fixed by setting ```client_max_body_size``` to a suitable value in the ```server``` part of nginx.conf file.
