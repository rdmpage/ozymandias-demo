#!/bin/sh

# Fetch triples from triplestore for each named graph

curl  -X POST http://130.209.46.63/blazegraph/sparql --data-urlencode "query=CONSTRUCT { ?s ?p ?o } WHERE { graph <https://bie.ala.org.au> { hint:Query hint:constructDistinctSPO false . ?s ?p ?o } }" -H 'Accept:text/x-nquads' > oz-ala.nt

curl  -X POST http://130.209.46.63/blazegraph/sparql --data-urlencode "query=CONSTRUCT { ?s ?p ?o } WHERE { graph <https://biodiversity.org.au/afd/publication> { hint:Query hint:constructDistinctSPO false . ?s ?p ?o } }" -H 'Accept:text/x-nquads' > oz-publication.nt

curl  -X POST http://130.209.46.63/blazegraph/sparql --data-urlencode "query=CONSTRUCT { ?s ?p ?o } WHERE { graph <https://zenodo.org> { hint:Query hint:constructDistinctSPO false . ?s ?p ?o } }" -H 'Accept:text/x-nquads' > oz-zenodo.nt

curl  -X POST http://130.209.46.63/blazegraph/sparql --data-urlencode "query=CONSTRUCT { ?s ?p ?o } WHERE { graph <https://crossref.org> { hint:Query hint:constructDistinctSPO false . ?s ?p ?o } }" -H 'Accept:text/x-nquads' > oz-crossref.nt

curl  -X POST http://130.209.46.63/blazegraph/sparql --data-urlencode "query=CONSTRUCT { ?s ?p ?o } WHERE { graph <https://orcid.org> { hint:Query hint:constructDistinctSPO false . ?s ?p ?o } }" -H 'Accept:text/x-nquads' > oz-orcid.nt

curl  -X POST http://130.209.46.63/blazegraph/sparql --data-urlencode "query=CONSTRUCT { ?s ?p ?o } WHERE { graph <https://species.wikimedia.org> { hint:Query hint:constructDistinctSPO false . ?s ?p ?o } }" -H 'Accept:text/x-nquads' > oz-species.nt

curl  -X POST http://130.209.46.63/blazegraph/sparql --data-urlencode "query=CONSTRUCT { ?s ?p ?o } WHERE { graph <https://gbif.org/species> { hint:Query hint:constructDistinctSPO false . ?s ?p ?o } }" -H 'Accept:text/x-nquads' > oz-gbif.nt

curl  -X POST http://130.209.46.63/blazegraph/sparql --data-urlencode "query=CONSTRUCT { ?s ?p ?o } WHERE { graph <http://boldsystems.org> { hint:Query hint:constructDistinctSPO false . ?s ?p ?o } }" -H 'Accept:text/x-nquads' > oz-bold.nt

