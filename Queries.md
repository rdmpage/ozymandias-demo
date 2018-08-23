# Example queries



## Number of names published per year

```
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX tc: <http://rs.tdwg.org/ontology/voc/TaxonConcept#>
SELECT  ?year  (COUNT(?taxonName) AS ?count) WHERE
{   
VALUES ?root_name {"THEREVIDAE"}
?root <http://schema.org/name> ?root_name .
?child rdfs:subClassOf+ ?root . 
?child tc:nameString ?child_name .
  
 ?child  <http://taxref.mnhn.fr/lod/property/hasReferenceName>|<http://taxref.mnhn.fr/lod/property/hasSynonym> ?taxonName .
 ?taxonName <http://schema.org/name> ?tname .
 ?taxonName <http://rs.tdwg.org/ontology/voc/Common#publishedInCitation> ?work .
 ?work <http://schema.org/datePublished> ?year .
   
} 
GROUP BY ?year 
ORDER BY (?year)
```

## Publications for a taxon

PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX tc: <http://rs.tdwg.org/ontology/voc/TaxonConcept#>
SELECT  DISTINCT ?year ?work_name ?container_name ?doi WHERE
{   
VALUES ?root_name {"THEREVIDAE"}
?root <http://schema.org/name> ?root_name .
?child rdfs:subClassOf+ ?root . 
?child tc:nameString ?child_name .
  
 ?child  <http://taxref.mnhn.fr/lod/property/hasReferenceName>|<http://taxref.mnhn.fr/lod/property/hasSynonym> ?taxonName .
 ?taxonName <http://schema.org/name> ?tname .
 ?taxonName <http://rs.tdwg.org/ontology/voc/Common#publishedInCitation> ?work .
 ?work <http://schema.org/datePublished> ?year .
 ?work <http://schema.org/name> ?work_name .
 ?work <http://schema.org/isPartOf> ?container .
 ?container <http://schema.org/name> ?container_name .
  
  OPTIONAL {
   ?work <http://schema.org/identifier> ?identifier .
    ?identifier <http://schema.org/propertyID> "doi" .
    ?identifier <http://schema.org/value> ?doi .
  }
 
   
} 

ORDER BY DESC (?year)



## Major journals

```
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX tc: <http://rs.tdwg.org/ontology/voc/TaxonConcept#>
SELECT   ?journal  (COUNT(?journal) AS ?count) WHERE
{   
 ?work  <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://schema.org/ScholarlyArticle> .
 ?work <http://schema.org/isPartOf> ?container .
  ?container <http://schema.org/name> ?journal .
 ?work <http://schema.org/datePublished> ?year .
   
} 
GROUP BY ?journal 
ORDER BY DESC(?count)
LIMIT 20

```


## Dates of cited works

```
PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
SELECT ?cited_identifier_type (xsd:integer(?w_year) as ?from) (xsd:integer(?work_year) as ?to) 
WHERE
{
  
?w <http://schema.org/identifier> ?identifier .
  ?w <http://schema.org/name> ?w_name .
?w <http://schema.org/datePublished> ?w_year .
# Identifier (e.g., DOI) for work we are displaying 
?identifier <http://schema.org/value> ?identifier_value .  
  
?citing_identifier <http://schema.org/value> ?identifier_value .
?citing <http://schema.org/identifier> ?citing_identifier .

# What does this work cite (typically from CrossRef data)
?citing <http://schema.org/citation> ?cited .

# Translate the citing work's DOI (or other identifier) into AFD identifier
# Get identifier (typically a DOI) for citing work
?cited <http://schema.org/identifier> ?cited_identifier .
?cited_identifier <http://schema.org/value> ?cited_identifier_value .
?cited_identifier <http://schema.org/propertyID> ?cited_identifier_type .


# Get work(s) with this identifer (may have > 1 if we have CrossRef record in our triple store
?work_identifier <http://schema.org/value> ?cited_identifier_value .
?work <http://schema.org/identifier> ?work_identifier .
?work <http://schema.org/name> ?name .
?work <http://schema.org/datePublished> ?work_year .

# Just include citing records that are also in ALA
FILTER regex(str(?work),'biodiversity.org.au') .
FILTER regex(str(?w),'biodiversity.org.au') .
}
LIMIT 5000
```

## Age distribution of cited works

```
PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
SELECT 
(xsd:integer(?w_year) - xsd:integer(?work_year) as ?d) 

#?w_year ?work_year
WHERE
{
  
?w <http://schema.org/identifier> ?identifier .
  ?w <http://schema.org/name> ?w_name .
?w <http://schema.org/datePublished> ?w_year .
# Identifier (e.g., DOI) for work we are displaying 
?identifier <http://schema.org/value> ?identifier_value .  
  
?citing_identifier <http://schema.org/value> ?identifier_value .
?citing <http://schema.org/identifier> ?citing_identifier .

# What does this work cite (typically from CrossRef data)
?citing <http://schema.org/citation> ?cited .

# Translate the citing work's DOI (or other identifier) into AFD identifier
# Get identifier (typically a DOI) for citing work
?cited <http://schema.org/identifier> ?cited_identifier .
?cited_identifier <http://schema.org/value> ?cited_identifier_value .
?cited_identifier <http://schema.org/propertyID> ?cited_identifier_type .


# Get work(s) with this identifer (may have > 1 if we have CrossRef record in our triple store
?work_identifier <http://schema.org/value> ?cited_identifier_value .
?work <http://schema.org/identifier> ?work_identifier .
?work <http://schema.org/name> ?name .
?work <http://schema.org/datePublished> ?work_year .

# Just include citing records that are also in ALA
FILTER regex(str(?work),'biodiversity.org.au') .
FILTER regex(str(?w),'biodiversity.org.au') .
}
LIMIT 1000```



### Top cited papers in a year

```
PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
SELECT ?w ?w_name (COUNT(?work) as ?c)
WHERE
{
  ?w <http://schema.org/datePublished> "2012" .
  ?w <http://schema.org/identifier> ?identifier .
  ?w <http://schema.org/name> ?w_name .
  ?identifier <http://schema.org/value> ?identifier_value .

# For CrossRef records, we will have another object with this DOI 
?cited_identifier <http://schema.org/value> ?identifier_value .
?cited <http://schema.org/identifier> ?cited_identifier .
  
  # Work citing this work (typically from CrossRef data)
?cited_by <http://schema.org/citation> ?cited .
  
  # Translate the citing work's DOI (or other identifier) into AFD identifier
# Get identifier (typically a DOI) for citing work
?cited_by <http://schema.org/identifier> ?cited_by_identifier .
?cited_by_identifier <http://schema.org/value> ?cited_by_identifier_value .

# Get work(s) with this identifer (may have > 1 if we have CrossRef record in our triple store
?work_identifier <http://schema.org/value> ?cited_by_identifier_value .
?work <http://schema.org/identifier> ?work_identifier .
?work <http://schema.org/name> ?name .

# Just include citing records that are also in ALA
FILTER regex(str(?work),'biodiversity.org.au') .

} 
GROUP BY ?w ?w_name
ORDER BY DESC(?c)
LIMIT 10
```