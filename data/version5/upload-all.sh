#!/bin/sh

# Version 5 data uploaded to Hetzner

# Bibliography
echo 'bibliography-0.nt'
curl http://65.108.211.37:9999/blazegraph/sparql?context-uri=https://biodiversity.org.au/afd/publication -H 'Content-Type: text/rdf+n3' --data-binary '@bibliography-0.nt'  --progress-bar | tee /dev/null
echo ''
sleep 5
echo 'bibliography-500000.nt'
curl http://65.108.211.37:9999/blazegraph/sparql?context-uri=https://biodiversity.org.au/afd/publication -H 'Content-Type: text/rdf+n3' --data-binary '@bibliography-500000.nt'  --progress-bar | tee /dev/null
echo ''
sleep 5
echo 'bibliography-1000000.nt'
curl http://65.108.211.37:9999/blazegraph/sparql?context-uri=https://biodiversity.org.au/afd/publication -H 'Content-Type: text/rdf+n3' --data-binary '@bibliography-1000000.nt'  --progress-bar | tee /dev/null
echo ''
sleep 5
echo 'bibliography-1500000.nt'
curl http://65.108.211.37:9999/blazegraph/sparql?context-uri=https://biodiversity.org.au/afd/publication -H 'Content-Type: text/rdf+n3' --data-binary '@bibliography-1500000.nt'  --progress-bar | tee /dev/null
echo ''
sleep 5
echo 'bibliography-2000000.nt'
curl http://65.108.211.37:9999/blazegraph/sparql?context-uri=https://biodiversity.org.au/afd/publication -H 'Content-Type: text/rdf+n3' --data-binary '@bibliography-2000000.nt'  --progress-bar | tee /dev/null
echo ''
sleep 5
echo 'bibliography-2500000.nt'
curl http://65.108.211.37:9999/blazegraph/sparql?context-uri=https://biodiversity.org.au/afd/publication -H 'Content-Type: text/rdf+n3' --data-binary '@bibliography-2500000.nt'  --progress-bar | tee /dev/null
echo ''
sleep 5
echo 'bibliography-3000000.nt'
curl http://65.108.211.37:9999/blazegraph/sparql?context-uri=https://biodiversity.org.au/afd/publication -H 'Content-Type: text/rdf+n3' --data-binary '@bibliography-3000000.nt'  --progress-bar | tee /dev/null
echo ''
sleep 5
echo 'bibliography-3500000.nt'
curl http://65.108.211.37:9999/blazegraph/sparql?context-uri=https://biodiversity.org.au/afd/publication -H 'Content-Type: text/rdf+n3' --data-binary '@bibliography-3500000.nt'  --progress-bar | tee /dev/null
echo ''
sleep 5
echo 'bibliography-4000000.nt'
curl http://65.108.211.37:9999/blazegraph/sparql?context-uri=https://biodiversity.org.au/afd/publication -H 'Content-Type: text/rdf+n3' --data-binary '@bibliography-4000000.nt'  --progress-bar | tee /dev/null
echo ''
sleep 5

# Names
echo 'names-0.nt'
curl http://65.108.211.37:9999/blazegraph/sparql?context-uri=https://biodiversity.org.au/afd/publication -H 'Content-Type: text/rdf+n3' --data-binary '@names-0.nt'  --progress-bar | tee /dev/null
echo ''
sleep 5
echo 'names-500000.nt'
curl http://65.108.211.37:9999/blazegraph/sparql?context-uri=https://biodiversity.org.au/afd/publication -H 'Content-Type: text/rdf+n3' --data-binary '@names-500000.nt'  --progress-bar | tee /dev/null
echo ''
sleep 5
echo 'names-1000000.nt'
curl http://65.108.211.37:9999/blazegraph/sparql?context-uri=https://biodiversity.org.au/afd/publication -H 'Content-Type: text/rdf+n3' --data-binary '@names-1000000.nt'  --progress-bar | tee /dev/null
echo ''
sleep 5
echo 'names-1500000.nt'
curl http://65.108.211.37:9999/blazegraph/sparql?context-uri=https://biodiversity.org.au/afd/publication -H 'Content-Type: text/rdf+n3' --data-binary '@names-1500000.nt'  --progress-bar | tee /dev/null
echo ''
sleep 5
echo 'names-2000000.nt'
curl http://65.108.211.37:9999/blazegraph/sparql?context-uri=https://biodiversity.org.au/afd/publication -H 'Content-Type: text/rdf+n3' --data-binary '@names-2000000.nt'  --progress-bar | tee /dev/null
echo ''
sleep 5
echo 'names-2500000.nt'
curl http://65.108.211.37:9999/blazegraph/sparql?context-uri=https://biodiversity.org.au/afd/publication -H 'Content-Type: text/rdf+n3' --data-binary '@names-2500000.nt'  --progress-bar | tee /dev/null
echo ''
sleep 5
echo 'names-3000000.nt'
curl http://65.108.211.37:9999/blazegraph/sparql?context-uri=https://biodiversity.org.au/afd/publication -H 'Content-Type: text/rdf+n3' --data-binary '@names-3000000.nt'  --progress-bar | tee /dev/null
echo ''
sleep 5
echo 'names-3500000.nt'
curl http://65.108.211.37:9999/blazegraph/sparql?context-uri=https://biodiversity.org.au/afd/publication -H 'Content-Type: text/rdf+n3' --data-binary '@names-3500000.nt'  --progress-bar | tee /dev/null
echo ''
sleep 5
echo 'names-4000000.nt'
curl http://65.108.211.37:9999/blazegraph/sparql?context-uri=https://biodiversity.org.au/afd/publication -H 'Content-Type: text/rdf+n3' --data-binary '@names-4000000.nt'  --progress-bar | tee /dev/null
echo ''
sleep 5

# Classification
echo 'ala-0.nt'
curl http://65.108.211.37:9999/blazegraph/sparql?context-uri=https://bie.ala.org.au -H 'Content-Type: text/rdf+n3' --data-binary '@../ala-0.nt'  --progress-bar | tee /dev/null
echo ''
sleep 5
echo 'ala-500000.nt'
curl http://65.108.211.37:9999/blazegraph/sparql?context-uri=https://bie.ala.org.au -H 'Content-Type: text/rdf+n3' --data-binary '@../ala-500000.nt'  --progress-bar | tee /dev/null
echo ''
sleep 5
echo 'ala-1000000.nt'
curl http://65.108.211.37:9999/blazegraph/sparql?context-uri=https://bie.ala.org.au -H 'Content-Type: text/rdf+n3' --data-binary '@../ala-1000000.nt'  --progress-bar | tee /dev/null
echo ''
sleep 5
echo 'ala-1500000.nt'
curl http://65.108.211.37:9999/blazegraph/sparql?context-uri=https://bie.ala.org.au -H 'Content-Type: text/rdf+n3' --data-binary '@../ala-1500000.nt'  --progress-bar | tee /dev/null
echo ''
sleep 5

# GBIF
#curl http://65.108.211.37:9999/blazegraph/sparql?context-uri=https://gbif.org/species -H 'Content-Type: text/rdf+n3' --data-binary '@gbif-0.nt'  --progress-bar | tee /dev/null
#echo ''
#sleep 5

# ORCID
#curl http://65.108.211.37:9999/blazegraph/sparql?context-uri=https://orcid.org -H 'Content-Type: text/rdf+n3' --data-binary '@orcid-0.nt'  --progress-bar | tee /dev/null
#echo ''
#sleep 5

# CrossRef
#curl http://65.108.211.37:9999/blazegraph/sparql?context-uri=https://crossref.org -H 'Content-Type: text/rdf+n3' --data-binary '@crossref-0.nt'  --progress-bar | tee /dev/null
#echo ''
#sleep 5

# BOLD
#curl http://65.108.211.37:9999/blazegraph/sparql?context-uri=http://boldsystems.org -H 'Content-Type: text/rdf+n3' --data-binary '@bold-0.nt'  --progress-bar | tee /dev/null
#echo ''
#sleep 5

# Wikispecies
#curl http://65.108.211.37:9999/blazegraph/sparql?context-uri=https://species.wikimedia.org -H 'Content-Type: text/rdf+n3' --data-binary '@wikispecies.nt'  --progress-bar | tee /dev/null
#echo ''
#sleep 5

# Zenodo
curl http://65.108.211.37:9999/blazegraph/sparql?context-uri=https://zenodo.org -H 'Content-Type: text/rdf+n3' --data-binary '@zenodo-0.nt'  --progress-bar | tee /dev/null
echo ''
sleep 5

curl http://65.108.211.37:9999/blazegraph/sparql?context-uri=https://zenodo.org -H 'Content-Type: text/rdf+n3' --data-binary '@assassins.nt'  --progress-bar | tee /dev/null
echo ''
sleep 5

curl http://65.108.211.37:9999/blazegraph/sparql?context-uri=https://zenodo.org -H 'Content-Type: text/rdf+n3' --data-binary '@weevils.nt'  --progress-bar | tee /dev/null
echo ''
sleep 5

