# notes for dedupe meeting

## Data structure & depulication plan and rules

1. Should search by site include the company name - as e.g. Dairy Crest Food Service becomes Food Service
  - Or should search by site rarely be used?
  - This would affect reports/filters currently as they are all set up to work with site name, should work be done to fix?

2. Numbering site records - should this appear on the workspace too?

3. Merging addresses. there's no created/edited date however we can tell which address is newest based on it's ID, also the site it is attached to

4. Soft deleting. is basically just archiving and hiding on the front-end, the data is kept and can be used to just keep off main lists.

5. When merging do duplicate categories, subcats, tier, co-tags, brands. Would this have any knock on issues in the system?

6. Does there need to be a new data structure to separate people & posts? separating the individual from the position?

7. Berkeley database????

8. List of example duplicate companies

## Normalisation

Do these rules need to be in a popup? To help someone as they are inputting

### Limited Companies

Should there be a separate type field in the database for PLC etc?


### Initials

validaiton rules?

### Addresses

Would it be better to add more address lines to the database?

#### Abbreviations

Is there a need for abbreviations?

Should we write validation rules to try catch occurences of these

#### London Addresses

Validation?

#### Counties

Counties are currently pulled from DB and put in dropdown?, does this need to be changed and use autocomplete?

validation on if uk - must fill in county?

#### Place names

autocomplete could be added to try cut down on varying data?

#### Postcode

Validation?

### Phone numbers

Not sure if validation could handle these...

## De-dupe stages

1. Create 1 level parent company for each site, grouping if sites have common names

2. Check through sites and merge where identical
  - merge by match on name, address, website, tag of dupe - project tags, ref, company tag
  - merge site fields e.g. address - make decision on what is more up to date, id?
  - re-join all joined fields e.g. categories, brands, posts, notes etc


3. De-dupe posts (possible new data structure?)
  - merge fields, make decision on which is newest etc
  - soft delete people marked as "LEFT", "retired" etc - hide on workspace, toggle to show

3. Sort posts into correct site
>"If direct phone number prefix matches other site move the post/person, see Bob Malcolm, Esher"

6. Berkeley database???

7. soft delete clients etc


## adding company - add site?

- checkbox to add site

## test sample

- get test sample from ian.