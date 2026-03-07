#!/bin/bash

./command run Dedupe/prepareData > dedupe_log/prepareData.json
./command run Dedupe/siteDuplicates > dedupe_log/siteDuplicates.json
./command run Dedupe/siteMergePrepare > dedupe_log/siteMergePrepare.csv
./command run Dedupe/mergeSites > dedupe_log/mergeSites.json
./command run Dedupe/groupCompanies > dedupe_log/groupCompanies.csv
./command run Dedupe/createCompanies > dedupe_log/createCompanies.json
./command run Dedupe/postDuplicates > dedupe_log/postDuplicates.csv