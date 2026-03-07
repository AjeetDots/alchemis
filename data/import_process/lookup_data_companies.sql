#-----------------------------------
select 'tbl_lkp_counties';
#-----------------------------------
/*!40000 alter table tbl_lkp_counties disable keys */;
lock tables tbl_lkp_counties write;
#-----------------------------------|----|----------------------------------------------------|
# -- Columns --                     | id | name (50)                                          |
#-----------------------------------|----|----------------------------------------------------|
#--- England ---
insert into tbl_lkp_counties values (   1, 'Bedfordshire');
insert into tbl_lkp_counties values (   2, 'Berkshire');
insert into tbl_lkp_counties values (   3, 'Buckinghamshire');
insert into tbl_lkp_counties values (   4, 'Cambridgeshire');
insert into tbl_lkp_counties values (   5, 'Cheshire');
insert into tbl_lkp_counties values (   6, 'Cornwall');
insert into tbl_lkp_counties values (   7, 'Cumberland');
insert into tbl_lkp_counties values (   8, 'Derbyshire');
insert into tbl_lkp_counties values (   9, 'Devon');
insert into tbl_lkp_counties values (  10, 'Dorset');
insert into tbl_lkp_counties values (  11, 'Durham');
insert into tbl_lkp_counties values (  12, 'Essex');
insert into tbl_lkp_counties values (  13, 'Gloucestershire');
insert into tbl_lkp_counties values (  14, 'Hampshire');
insert into tbl_lkp_counties values (  15, 'Herefordshire');
insert into tbl_lkp_counties values (  16, 'Hertfordshire');
insert into tbl_lkp_counties values (  17, 'Huntingdonshire');
insert into tbl_lkp_counties values (  18, 'Kent');
insert into tbl_lkp_counties values (  19, 'Lancashire');
insert into tbl_lkp_counties values (  20, 'Leicestershire');
insert into tbl_lkp_counties values (  21, 'Lincolnshire');
insert into tbl_lkp_counties values (  22, 'Middlesex');
insert into tbl_lkp_counties values (  23, 'Norfolk');
insert into tbl_lkp_counties values (  24, 'Northamptonshire');
insert into tbl_lkp_counties values (  25, 'Northumberland');
insert into tbl_lkp_counties values (  26, 'Nottinghamshire');
insert into tbl_lkp_counties values (  27, 'Oxfordshire');
insert into tbl_lkp_counties values (  28, 'Rutland');
insert into tbl_lkp_counties values (  29, 'Shropshire');
insert into tbl_lkp_counties values (  30, 'Somerset');
insert into tbl_lkp_counties values (  31, 'Staffordshire');
insert into tbl_lkp_counties values (  32, 'Suffolk');
insert into tbl_lkp_counties values (  33, 'Surrey');
insert into tbl_lkp_counties values (  34, 'Sussex');
insert into tbl_lkp_counties values (  35, 'Warwickshire');
insert into tbl_lkp_counties values (  36, 'Westmorland');
insert into tbl_lkp_counties values (  37, 'Wiltshire');
insert into tbl_lkp_counties values (  38, 'Worcestershire');
insert into tbl_lkp_counties values (  39, 'Yorkshire');
#--- Scotland ---
insert into tbl_lkp_counties values (  40, 'Aberdeenshire');
insert into tbl_lkp_counties values (  41, 'Angus/Forfarshire');
insert into tbl_lkp_counties values (  42, 'Argyllshire');
insert into tbl_lkp_counties values (  43, 'Ayrshire');
insert into tbl_lkp_counties values (  44, 'Banffshire');
insert into tbl_lkp_counties values (  45, 'Berwickshire');
insert into tbl_lkp_counties values (  46, 'Buteshire');
insert into tbl_lkp_counties values (  47, 'Cromartyshire');
insert into tbl_lkp_counties values (  48, 'Caithness');
insert into tbl_lkp_counties values (  49, 'Clackmannanshire');
insert into tbl_lkp_counties values (  50, 'Dumfriesshire');
insert into tbl_lkp_counties values (  51, 'Dunbartonshire/Dumbartonshire');
insert into tbl_lkp_counties values (  52, 'East Lothian/Haddingtonshire');
insert into tbl_lkp_counties values (  53, 'Fife');
insert into tbl_lkp_counties values (  54, 'Inverness-shire');
insert into tbl_lkp_counties values (  55, 'Kincardineshire');
insert into tbl_lkp_counties values (  56, 'Kinross-shire');
insert into tbl_lkp_counties values (  57, 'Kirkcudbrightshire');
insert into tbl_lkp_counties values (  58, 'Lanarkshire');
insert into tbl_lkp_counties values (  59, 'Midlothian/Edinburghshire');
insert into tbl_lkp_counties values (  60, 'Morayshire');
insert into tbl_lkp_counties values (  61, 'Nairnshire');
insert into tbl_lkp_counties values (  62, 'Orkney');
insert into tbl_lkp_counties values (  63, 'Peeblesshire');
insert into tbl_lkp_counties values (  64, 'Perthshire');
insert into tbl_lkp_counties values (  65, 'Renfrewshire');
insert into tbl_lkp_counties values (  66, 'Ross-shire');
insert into tbl_lkp_counties values (  67, 'Roxburghshire');
insert into tbl_lkp_counties values (  68, 'Selkirkshire');
insert into tbl_lkp_counties values (  69, 'Shetland');
insert into tbl_lkp_counties values (  70, 'Stirlingshire');
insert into tbl_lkp_counties values (  71, 'Sutherland');
insert into tbl_lkp_counties values (  72, 'West Lothian/Linlithgowshire');
insert into tbl_lkp_counties values (  73, 'Wigtownshire');
#--- Wales ---
insert into tbl_lkp_counties values (  74, 'Anglesey');
insert into tbl_lkp_counties values (  75, 'Brecknockshire');
insert into tbl_lkp_counties values (  76, 'Caernarfonshire');
insert into tbl_lkp_counties values (  77, 'Carmarthenshire');
insert into tbl_lkp_counties values (  78, 'Cardiganshire');
insert into tbl_lkp_counties values (  79, 'Denbighshire');
insert into tbl_lkp_counties values (  80, 'Flintshire');
insert into tbl_lkp_counties values (  81, 'Glamorgan');
insert into tbl_lkp_counties values (  82, 'Merioneth');
insert into tbl_lkp_counties values (  83, 'Monmouthshire');
insert into tbl_lkp_counties values (  84, 'Montgomeryshire');
insert into tbl_lkp_counties values (  85, 'Pembrokeshire');
insert into tbl_lkp_counties values (  86, 'Radnorshire');
# --- Northern Ireland ---
insert into tbl_lkp_counties values (  87, 'Antrim');
insert into tbl_lkp_counties values (  88, 'Armagh');
insert into tbl_lkp_counties values (  89, 'Derry/Londonderry');
insert into tbl_lkp_counties values (  90, 'Down');
insert into tbl_lkp_counties values (  91, 'Fermanagh');
insert into tbl_lkp_counties values (  92, 'Tyrone');
#--- Ireland ---
insert into tbl_lkp_counties values (  93, 'Dublin');
insert into tbl_lkp_counties values (  94, 'Cavan');
insert into tbl_lkp_counties values (  95, 'Kilkenny');
insert into tbl_lkp_counties values (  96, 'Kildare');
insert into tbl_lkp_counties values (  97, 'Carlow');
insert into tbl_lkp_counties values (  98, 'Kerry');
insert into tbl_lkp_counties values (  99, 'Clare');
insert into tbl_lkp_counties values ( 101, 'Wicklow');
insert into tbl_lkp_counties values ( 102, 'Cork');
insert into tbl_lkp_counties values ( 103, 'Donegal');
insert into tbl_lkp_counties values ( 104, 'Galway');
insert into tbl_lkp_counties values ( 105, 'Westmeath');
insert into tbl_lkp_counties values ( 106, 'Leix');
insert into tbl_lkp_counties values ( 107, 'Wexford');
insert into tbl_lkp_counties values ( 108, 'Leitrim');
insert into tbl_lkp_counties values ( 109, 'Limerick');
insert into tbl_lkp_counties values ( 110, 'Longford');
insert into tbl_lkp_counties values ( 111, 'Louth');
insert into tbl_lkp_counties values ( 112, 'Mayo');
insert into tbl_lkp_counties values ( 113, 'Meath');
insert into tbl_lkp_counties values ( 114, 'Monaghan');
insert into tbl_lkp_counties values ( 115, 'Waterford');
insert into tbl_lkp_counties values ( 116, 'Roscommon');
insert into tbl_lkp_counties values ( 117, 'Sligo');
insert into tbl_lkp_counties values ( 118, 'Tipperary');
insert into tbl_lkp_counties values ( 119, 'Offaly');
#-----------------------------------|----|----------------------------------------------------|
unlock tables;
/*!40000 alter table tbl_lkp_counties enable keys */;

#------------------------------------
select 'tbl_lkp_counties_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_counties_seq disable keys */;
lock tables tbl_lkp_counties_seq write;

update tbl_lkp_counties_seq set sequence = 119;

unlock tables;
/*!40000 alter table tbl_lkp_counties_seq enable keys */;

#---------------------------------------------------------------------------------------------------------------------------------

#-----------------------------------
select 'tbl_lkp_countries';
#-----------------------------------
/*!40000 alter table tbl_lkp_countries disable keys */;
lock tables tbl_lkp_countries write;
#------------------------------------|----|----------------------------------------------------|
# -- Columns --                      | id | name (50)                                          |
#------------------------------------|----|----------------------------------------------------|
insert into tbl_lkp_countries values (   1, 'England');
insert into tbl_lkp_countries values (   2, 'Scotland');
insert into tbl_lkp_countries values (   3, 'Wales');
insert into tbl_lkp_countries values (   4, 'Northern Ireland');
insert into tbl_lkp_countries values (   5, 'Ireland');
#------------------------------------|----|----------------------------------------------------|
unlock tables;
/*!40000 alter table tbl_lkp_countries enable keys */;

#------------------------------------
select 'tbl_lkp_countries_seq';
#------------------------------------
/*!40000 alter table tbl_lkp_countries_seq disable keys */;
lock tables tbl_lkp_countries_seq write;

update tbl_lkp_countries_seq set sequence = 5;

unlock tables;
/*!40000 alter table tbl_lkp_countries_seq enable keys */;