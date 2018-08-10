# ozymandias-demo
Ozymandias

## Model

Below is a simplified model of the knowledge graph. The core entities are taxa, taxonomic names, publications, journals, and people.

Taxa have type http://rs.tdwg.org/ontology/voc/TaxonConcept#TaxonConcept. A taxonomic classification is represented by rdfs:subClassOf relationship between parent and child taxa (a child is a rdfs:subClassOf its parent). Taxa are connected to taxonomic names (type http://rs.tdwg.org/ontology/voc/TaxonName#TaxonName) by relations from the taxref vocabulary, and are typically either accepted names or synonyms. Names are published in publications (typically of type http://schema.org/ScholarlyArticle but may also be other types derived from http://schema.org/CreativeWork). Articles are http://schema.org/isPartOf journals (http://schema.org/Periodical). Authors are linked to their publications using “roles” (http://schema.org/Role) which enables us to include information on order of authorship. Publications may be linked by http://schema.org/citation relations. Figures within a publication (http://schema.org/ImageObject) are http://schema.org/isPartOf that publication. To handle the existence of multiple identifiers I create a http://schema.org/PropertyValue item for each identifier (linked to the publication using http://schema.org/identifier) and store the identifier (stripped of any resolution mechanism) as a http://schema.org/value. This indirection avoids having to figure out which IRI is used to identify an entity, instead of asking for the entity with IRI https://doi.org/10.11646/zootaxa.4340.1.1 (or some variation of that URL) we can ask what entity as an identifier with http://schema.org/propertyID “doi:” and http://schema.org/value “10.11646/zootaxa.4340.1.1”.


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

