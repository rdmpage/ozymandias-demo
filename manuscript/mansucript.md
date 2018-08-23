# Manuscript


Motivation



### Scope

Wikidata as a hard stop.

Wikispecies 


### Identifiers

Identifiers are both central to any attempt to link data together, and at the same time are one of the major obstacle to such linking. Ideally identifiers should be globally unique, persistent, and each entity should have only one identifier. In reality, entities may have many identifiers, typically minted by different databases, and identifiers can change, or at least have multiple representations. For example, DOIs may contain upper and lower case letters but are case insensitive. Some databases may choice to store DOIs in lower case form, others in upper case, or any combination in between. Identifiers typically require dereferencing, the mechanism for this may evolve, often times for reasons outside the control of the organisation that minted the identifier. For example, DOIs are dereferenced (“resolved”) using a web proxy at doi.org. This proxy recently switch from the HTTP to the HTTPS protocol, immediately breaking any database that stored DOIs as URLs starting “http://“.

To minimise the impact of these changes, OZ stores identifiers both as URLs (where appropriate) but also as “propertyValues” [figure]



### Authors

[graph]

Whereas in, say, JSON, ordered lists are trivial, in RDF this is not the case. Not only is the order of authorship an important feature when formatting a work for display, it is also useful information when trying to reconcile author names (see below).

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

### Annotation

Hypothesis, taxonomic databases as annotation stores.

## Reconciling works

One of the biggest data cleaning challenges is to match publications in OZ with external databases such as CrossrEf, BioStor etc. matching a work to a Crossref work means we have access to the DOI for that work, which in turn gives access to a additional information (such as the citation graph in CrossRef or the attention graph in Altmetric).

CrossRef is not the only registration agency for DOIs, there are several others that are used by digital libraries and publishers (e.g.,,,,,), most f which lack the discover services provided by CrossRef.


## Reconciling authors



One approach is to align authors for the same work across different databases. For example, if we have a work in OZ and in  a researcher’s ORCID profile, then potentially we have an identifier for the author. To create this matching I model authors in ORCID in the same way as OZ (figure) using “roles”. Given a work with the same identifier in ORCID (e.g., sharing the same DOI) we can then list authors of each work, and compare the names of authors in the same position in the author list. If the strings are sufficiently close they are regarded as a match. 



# Results


## Uptake of DOis



## Citation and long data


## Authors with identifiers

-social implications, cf Nico


## Where are journals based



## How many works are open access

## Enhancing taxa using figures

## Annotation and locating page-level annotations

## Automating “top 10” links


## Population biology of taxonomists, page numbers, etc.







