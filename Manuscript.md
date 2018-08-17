Manuscript
==========

 1 billion, projected onto a map, not interlinked
 
 No links to primary evidence, nor researchers (cf Nico) Links to data providers, but not primary knowledge providers.
 
 Related issue of citation
 
 Crowd sourcing, e.g. wikispecies and wikidata.
 
 Knowledge graph 
 
 Scope delimited by wikidata
 
 Novelty? e.g., adding images for taxa that don't have images, linking to original decsriptions
 
 
 Compare with Scholia project, and openbiodiv
 
 
 Reasons why now much easier to do
 
 Make everything linkable, including pages (cf ted Nelson)
 
 Computable data,
 
 - visualise classification
 - visualise coverage
 
 Idea of being able to automate assessments of taxonomic community 9age, gender, nationality, etc.)
 
 
 
 Future plans to expand to sequences, occurrences, phylogenies. Use hypothes.is for annotation (everything is an anotation).
 
 
 Examples
 - images from paper base don string matching http://localhost/~rpage/ozymandias-demo/?uri=https://doi.org/10.3897/zookeys.556.6126.figure11
 
 weevil tribe tree
 
 ```
 PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
SELECT ?root_name ?parent_name ?child_name  WHERE
{   
VALUES ?root_name {"Amycterini Waterhouse, 1854"}
?root <http://schema.org/name> ?root_name .
?child rdfs:subClassOf+ ?root .
?child rdfs:subClassOf ?parent .
?child <http://schema.org/name> ?child_name .
?parent <http://schema.org/name> ?parent_name .
}
```

Can we do activity plots?
 
 
 Script for demo
 ===============
 
 1. ALA as starting point, limite dimages, no evidence, no researchers, limited tracakbiluty for museums and herbaria
 
 2. ALA as a mini-GBIF
 
 3. Knowledge graph idea
 
 4. Demo web app
 
 5. Conclusions - ultimately need a platform for where we do science.
 
 
 
 
 
 
 
 
 
 
 