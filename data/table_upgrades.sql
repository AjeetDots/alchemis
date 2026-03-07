alter table tbl_companies add column deleted tinyint not null default 0;
alter table tbl_sites add column deleted tinyint not null default 0;
alter table tbl_posts add column deleted tinyint not null default 0;
alter table tbl_contacts add column deleted tinyint not null default 0;

alter table tbl_companies add index ix_tbl_companies_deleted (deleted);
alter table tbl_sites add index ix_tbl_sites_deleted (deleted);
alter table tbl_posts add index ix_tbl_posts_deleted (deleted);
alter table tbl_contacts add index ix_tbl_contacts_deleted (deleted);


alter table tbl_communications add index ix_tbl_communications_communication_date (communication_date);
alter table tbl_communications add index ix_tbl_communications_type (type);
alter table tbl_communications add index ix_tbl_communications_next_communication_date (next_communication_date);

alter table tbl_posts change column name job_title varchar(255);
alter table tbl_posts add index ix_tbl_posts_job_title (job_title);


---------------
eg - to change auto_increment value
alter table tbl_companies_seq auto_increment = 29496;