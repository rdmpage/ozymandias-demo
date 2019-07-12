# Reloading data

## Taxonomic names

Chunk and upload names.nt, this should be loaded first

```
cd oz-afd-export
# Make sure names-rdf.php SQL dumps whole database
php names-rdf.php > names
# Make sure chunk2.php parses names.nt
php chunk2.php
./upload.sh 
```

## Bibliographic data

```
cd oz-afd-export
# Make sure bibliography-rdf.php SQL dumps whole database
php bibliography-rdf.php > bibliography.nt
# Make sure chunc2.php parses bibliography.nt
php chunk2.php
./upload.sh 
```

## Classification


## More

## Simple upload

### GBIF

```
curl http://kg-blazegraph.sloppy.zone/blazegraph/sparql?context-uri=https://gbif.org/species -H 'Content-Type: text/rdf+n3' --data-binary '@gbif-0.nt'  --progress-bar | tee /dev/null
```

### ORCID

```
curl http://kg-blazegraph.sloppy.zone/blazegraph/sparql?context-uri=https://orcid.org -H 'Content-Type: text/rdf+n3' --data-binary '@orcid-0.nt'  --progress-bar | tee /dev/null
```

### CrossRef

```
curl http://kg-blazegraph.sloppy.zone/blazegraph/sparql?context-uri=https://crossref.org -H 'Content-Type: text/rdf+n3' --data-binary '@crossref-0.nt'  --progress-bar | tee /dev/null
```

### BOLD

```
curl http://kg-blazegraph.sloppy.zone/blazegraph/sparql?context-uri=http://boldsystems.org -H 'Content-Type: text/rdf+n3' --data-binary '@bold-0.nt'  --progress-bar | tee /dev/null
```

### Zenodo

```
curl http://kg-blazegraph.sloppy.zone/blazegraph/sparql?context-uri=https://zenodo.org -H 'Content-Type: text/rdf+n3' --data-binary '@zenodo-0.nt'  --progress-bar | tee /dev/null
```

### Wikispecies

```
curl http://kg-blazegraph.sloppy.zone/blazegraph/sparql?context-uri=https://species.wikimedia.org -H 'Content-Type: text/rdf+n3' --data-binary '@version3/wikispecies.nt'  --progress-bar | tee /dev/null
```


