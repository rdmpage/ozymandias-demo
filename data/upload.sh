#!/bin/sh

echo 'ala-0.nt'
curl http://kg-blazegraph.sloppy.zone/blazegraph/sparql?context-uri=https://bie.ala.org.au -H 'Content-Type: text/rdf+n3' --data-binary '@ala-0.nt'  --progress-bar | tee /dev/null
echo ''
sleep 5
echo 'ala-500000.nt'
curl http://kg-blazegraph.sloppy.zone/blazegraph/sparql?context-uri=https://bie.ala.org.au -H 'Content-Type: text/rdf+n3' --data-binary '@ala-500000.nt'  --progress-bar | tee /dev/null
echo ''
sleep 5
echo 'ala-1000000.nt'
curl http://kg-blazegraph.sloppy.zone/blazegraph/sparql?context-uri=https://bie.ala.org.au -H 'Content-Type: text/rdf+n3' --data-binary '@ala-1000000.nt'  --progress-bar | tee /dev/null
echo ''
sleep 5
echo 'ala-1500000.nt'
curl http://kg-blazegraph.sloppy.zone/blazegraph/sparql?context-uri=https://bie.ala.org.au -H 'Content-Type: text/rdf+n3' --data-binary '@ala-1500000.nt'  --progress-bar | tee /dev/null
echo ''
sleep 5
