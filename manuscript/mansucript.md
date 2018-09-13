# Manuscript

# Introduction

The announcement that GBIF has reached the milestone of one billion occurrence records online reflects the success the biodiversity community has had in mobilising data. Part of this success comes from standardising on a simple column-based data format (Darwin Core) and indexing that data using three fields: taxonomic name, location, and date (what, where, and when). None of these fields is unproblematic, but they enable basic searching, and compelling visualisations such as maps with hundreds of millions of data points. By flattening the data into one table of columns and rows, Darwin Core makes data easy to enter and view, but at the cost of potentially obscuring relationships between entities that are better represented using a network. In this paper I explore the representation of biodiversity data using a network or “knowledge graph” (). (Need to say it is Australia here)

## Knowledge graph

A knowledge graph is a network or graph where nodes represent entities or concepts (“things”) and the links or edges of the graph represent relationships between those things. Each node is labelled by a unique identifier, and may have one or more attributes or properties. Each edge of the graph is labelled by the name of the relationship it represents. A common representation of a knowledge graph is the linked data triple of subject, predicate, and object, where the subject (e.g., a publication) is connected to an object (e.g., a person) by a predicate (e.g., “is authored by”). Knowledge graphs need not be modelled using linked data, other options are available, but doing so provides access to an existing tool kit of data stores and a standardised query language. 

Most datasets will have their own local identifiers for the entities they contain. For example, each species may have a locally unique identifier, as may a publication, specimen, or collector. These identifiers are adequate for local use, and for constructing a local knowledge graph, but local identifiers also serve to keep data in distinct silos. Knowledge graphs are potentially global in scope, and hence we need some way to map identifiers for the same thing between different local silos. This can be done, for example, by establishing a “broker” service that asserts identify between a set of identifiers, or by mapping local identifiers to a single global identifier. The case for mapping to a single global identifier (“strings to things”) is attractive in terms of scalability (mapping each local identifier to a single global identifier rather than managing cross mappings between multiple identifiers), and is even more attractive if there are services built around that global identifier. For example, Digital Object Identifiers (DOIs) are becoming the standard for identifying academic publications. Given a DOI we can retrieve metadata about the work from CrossRef, we can get measures of attention from services such as altmetric.org, and we can discover the identities of the work’s authors from ORCID. Furthermore, by agreeing on a centralised identifier (e.g., a DOI) we *decentralise* the building of the knowledge graph. Anybody that links data to that DOI is potentially contributing  to the construction of the knowledge graph.

Mapping strings to things give us a way to refer to the nodes in the knowledge graph, but we also need a consistent way to label the edges of the graph. There has been an explosion in vocabularies and ontologies for describing both attributes of entities and relationships between entities. Just within the scope of this project there are multiple vocabularies for x, y, and z. While arguments can be made that these ontologies enable us to represent knowledge with greater fidelity, the existence of multiple vocabularies imposes the cognitive overhead of having to decide which term from what vocabulary to use. In contrast to, say, Senderov et al, who use many ontologies, the approach I have adopted here is to keep things as simple as possible by minimising the number of vocabularies employed, and to avoid domain-specific vocabularies where ever possible. For this reason the default vocabulary used is schema.org , a vocabulary developed by a consortium of search engine vendors. In addition to simplifying development, adopting a widely used vocabulary increases the potential utility of the knowledge graph. One motivation for the development of schema.org was encourage the inclusion of structured data in web pages to help search engines interpret the contents of those pages. By adopting schema.org in knowledge graphs we can make it easier for developers of biodiversity web sites to include structured data in their web pages.

## Australia

This knowledge graph is populated using a regional-scale dataset (the Australian fauna) to balance scalability with realism. I wanted a dataset that would be sufficiently large to do interesting things with, without being too distracted by issues of engineering for scale.
		




### Scope

Anyone constructing a knowledge graph rapidly runs into the problem of scope, in other words, when do you stop adding entities? For the Ozymandias project I started with taxa, taxonomic names, publications, and journals. But publications have authors, and journals have publishers. Publishers are located somewhere, for example, in cities.  Publishers may be scientific institutions, societies, or commercial businesses. Do we add these to our graph? Publications typically cite other publications, do we add all the cited and citing literature? Unless we put limits on the graph fairly quickly it can explode in size. A rule of thumb used here is to stop at the point that we encounter and entity in Wikidata, which means that from there onwards the exploration of the graph becomes the exploration of Wikidata. This is essentially what Su et al. [] have argued, specialist knowledge resides in the local knowledge graph, which links to Wikidata. The two can be queried using federated SPARQL queries. Note that this rule of thumb is a guide only, and entities may be in both the knowledge graph and Wikidata.  

### External data sources

Information relevant to a local knowledge graph can come from numerous external sources. For example, Wikispecies is a crowd sourced project recording the names of all species on the planet. Many species pages include bibliographic information on publications, often linked to a page in Wikispecies for the author(s) of that publication. 

The ORCID project aims to provide all academic researchers with a unique identifier, as is being populated with data on publications, datasets, and other outputs. Some of this data is provided by authors themselves, other data comes from organisations such as CrossRef.

Sources such as Wikispecies…

## Modelling entities

Basic model genes here


### Identifiers

Identifiers are both central to any attempt to link data together, and at the same time can be one of the major obstacles to this linking. Ideally identifiers should be globally unique, persistent, and each entity should have only one identifier. In reality, entities may have many identifiers, typically minted by different databases, and identifiers can change, or at least have multiple representations. For example, DOIs may contain upper and lower case letters, but are case insensitive. Some databases may chose to store DOIs in lower case form, others in upper case, or any combination in between. Identifiers typically require dereferencing and the mechanism for this may evolve, often for reasons outside the control of the organisation that minted the identifier. For example, DOIs are dereferenced (“resolved”) using a web proxy at doi.org. This proxy recently switched from the HTTP to the HTTPS protocol, immediately breaking any database that stored DOIs as URLs starting with the prefix “http://“.

To minimise the impact of these changes, OZ stores identifiers both as URLs (where appropriate) but also as key-value pairs using “propertyValues” [figure].



### Authors

[graph]

RDF does not make it easy to store ordered lists, which presents a challenge for representing lists of authors. Not only is the order of authorship an important feature when formatting a work for display, it is also useful information when trying to reconcile author names (see below).

The approach adopted here is to use schema.org’s “role” type, which introduces a level of indirection. The “creator” property of a work is a role, which in turn has the author as a “creator” property. The position of author in the list of authors is stored in the “roleName” property (e.g., “1”, “2”, etc.).

```
http://blog.schema.org/2014/06/introducing-role.html
Introducing 'Role'
MONDAY, JUNE 16, 2014
Vicki Tardif Holland (Google)
Jason Johnson (Microsoft)
```

### Content

Modelling PDF, scanned pages, etc.

### Citation 

Citation is a link between identifiers. CrossRef does this via DOIs. Many references don’t have DOIs (can we graph this) so we all make simple SICIs (cite BioGUID paper).

[can we cite work by Shotton?]


## Reconciling data

[cite paper on reconciling]

### Reconciling works

One of the biggest data cleaning challenges is to match publications in OZ with external databases such as CrossRef, BioStor etc.

CrossRef is not the only registration agency for DOIs, there are several others that are used by digital libraries and publishers (e.g.,,,,,), most f which lack the discover services provided by CrossRef.

[discuss how matching was done]


### Reconciling authors

Several different approaches were used to match author names to external identifiers. For ORCID identifiers I parsed the ORCID record for a person and extracted a set of RDF triples linking the identifiers for a work (e.g., DOI) to a person’s ORCID. These triples modelled the order of authorship using Roles as described above. Similarly, I parsed Wikispecies pages and extracted bibliographic records for works identified by a DOI, and constructed triples linking the work to its authors where those authors had their own Wikispecies page. Hence to match authors in the knowledge graph to authors in ORCID or Wikispecies we can yes whether the same pairing of work and author name appears in both databases. For example, we can retrieve the second author of a work in the knowledge graph and in ORCID by querying by DOI for the work and restricting the roleName to “2”. As a final check we can compare the author names and accept only those names whose similarity exceeds a threshold. In this way we can automate matching authors across databases, and assign identifiers to authors. Wikispecies identifiers are especially powerful because most authors in Wikispecies with their own page also have an entry in Wikidata, and that entry is typically linked to multiple author identifiers, such as VIAF, ORCID, and ZooBank. 

[deduplicating authors, e.g. by clustering, can we use John Le Salle as an example?]

		

## Implementation

MySQL, CouchDB, triple store

# Results



## Applications

## Major journals
## Where are journals based?


Top n(20) journals

Where are they published?

-chloropleth/density map

Have script for this.

```
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX tc: <http://rs.tdwg.org/ontology/voc/TaxonConcept#>
SELECT   ?journal ?issn  (COUNT(?journal) AS ?count) WHERE
{   
 ?work  <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://schema.org/ScholarlyArticle> .
 ?work <http://schema.org/isPartOf> ?container .
  ?container <http://schema.org/name> ?journal .
 ?work <http://schema.org/datePublished> ?year .
  
  OPTIONAL {
  ?container <http://schema.org/issn> ?issn . 
  }
   
} 
GROUP BY ?journal ?issn
ORDER BY DESC(?count)
LIMIT 20
```


## Time to description of specimens?
[needs GBIF API]

## Uptake of DOis



## Citation and long data

[chart of citations against time]

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
  
FILTER regex(?w_year, "^[0-9]{4}$")
FILTER regex(?work_year, "^[0-9]{4}$")
}
LIMIT 5000
```


## Authors with identifiers

-social implications, cf Nico





## How many works are open access [do we have info for this?]

## Enhancing taxa using figures



## Annotation and locating page-level annotations
[move to future]

## Automating “top 10” links


## Population biology of taxonomists, page numbers, etc.



## Future directions

Annotation
		Annotation, Xanadu, etc.
		
Links to sequences, genomics, specimens


## References






