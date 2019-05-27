# Future

Future ideas, notes, and useful examples.


## Matching taxa across databases

Match taxa and names across multiple databases (GBIF, Wikidata, etc.) so that we can get text to display (e.g., from Dbpedia), and we can assert whether taxon has been sequenced (I.e., is it in NCBI?).

## Natural language generation

Could use _natural language generation_ (e.g., https://github.com/spro/nalgene-js ) to generate plain-text summaries of taxa, for example how many species a genus has, who has worked the most on that taxon, whether it is endemic, whether we have any genomes, etc.

## Questions

### Where are the type specimens?

What museums in the world have Australian type specimens? Requires populating with GBIF data because question is global

Go from GBIF taxa to GBIF types.

Cases where ALA/GBIF doesn’t know that type is a type, e.g. 

_Parvotettix goedei_ 
- http://localhost/~rpage/ozymandias-demo/?uri=https://biodiversity.org.au/afd/publication/f081ddd9-ae31-4aae-b694-bc64baf989c5 
- http://localhost/~rpage/ozymandias-demo/?uri=https://bie.ala.org.au/species/urn:lsid:biodiversity.org.au:afd.taxon:3d759214-b0ff-4087-9540-0280fa53f2c5 
- https://gbif.org/species/1729095
- https://www.gbif.org/occurrence/1100337378

### How many taxa have DNA sequences and/or DNA barcodes?

BOLD and NCBI identifiers for taxa?

## Images

### Images in GBIF but not ALA (e.g., because in overseas collections)

_Xabea leai_ Chopard, 1951

https://ozymandias-demo.herokuapp.com/?uri=https://bie.ala.org.au/species/urn:lsid:biodiversity.org.au:afd.taxon:0269d28d-b18d-4dde-b4f0-eb40e04a99c8

Types are in GBIF https://gbif.org/species/1720486 and there are images available: https://www.gbif.org/occurrence/1802798999 (this record also has bibliographic record as well).
