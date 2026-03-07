alter table tbl_campaign_sectors add column `weighting` int(11) default '0' NOT NULL;

alter table tbl_lkp_communication_status add column `report_description` varchar(255) null;
alter table tbl_lkp_communication_status add column `report_sort_order` int(11) null;
alter table tbl_lkp_communication_status add column `report_break_after_line` int(11) default '0';

update tbl_lkp_communication_status set report_break_after_line = 0;

--update tbl_lkp_communication_status set report_description = '' where id = ;
update tbl_lkp_communication_status set report_description = 'New Business Win as a result of Alchemis activity', report_sort_order = 1 where id = 30;
update tbl_lkp_communication_status set report_description = 'CLIENT has submitted a proposal following a meeting that has been attended', report_sort_order = 2 where id = 29;
update tbl_lkp_communication_status set report_description = 'CLIENT has received a brief from the prospect', report_sort_order = 3 where id = 28;
update tbl_lkp_communication_status set report_description = 'Meeting attended but opportunity has gone cold', report_sort_order = 4, report_break_after_line = 1 where id = 31;

update tbl_lkp_communication_status set report_description = 'F/up meeting has been attended, CLIENT has confirmed Alchemis to keep in contact', report_sort_order = 5 where id = 27;
update tbl_lkp_communication_status set report_description = 'F/up meeting has been attended, CLIENT has confirmed CLIENT to keep in contact', report_sort_order = 6 where id = 25;
update tbl_lkp_communication_status set report_description = 'F/up meeting set, CLIENT has cancelled', report_sort_order = 7 where id = 23;
update tbl_lkp_communication_status set report_description = 'F/up meeting set, prospect has cancelled', report_sort_order = 8 where id = 21;
update tbl_lkp_communication_status set report_description = 'F/up meeting set, was temporarily postponed, then reset with a new date', report_sort_order = 9 where id = 19;
update tbl_lkp_communication_status set report_description = 'F/up meeting set, but postponed, Alchemis will rearrange directly with prospect, by default', report_sort_order = 10 where id = 17;
update tbl_lkp_communication_status set report_description = 'F/up meeting set but postponed, CLIENT has confirmed they will rearrange themselves', report_sort_order = 11 where id = 15;
update tbl_lkp_communication_status set report_description = 'Meeting has been attended, CLIENT has requested a further meeting to be arranged', report_sort_order = 12 where id = 32;
update tbl_lkp_communication_status set report_description = 'A 2nd meeting has been set with this prospect', report_sort_order = 13, report_break_after_line = 1 where id = 13;

update tbl_lkp_communication_status set report_description = 'Meeting has been attended. CLIENT has confirmed Alchemis to keep in contact', report_sort_order = 14 where id = 26;
update tbl_lkp_communication_status set report_description = 'Meeting has been attended. CLIENT has confirmed CLIENT to keep in contact', report_sort_order = 15 where id = 24;
update tbl_lkp_communication_status set report_description = 'Meeting set, CLIENT has cancelled', report_sort_order = 16 where id = 22;
update tbl_lkp_communication_status set report_description = 'Meeting set, Prospect has cancelled due to no requirement', report_sort_order = 17 where id = 20;
update tbl_lkp_communication_status set report_description = 'Meeting set, was temporarily postponed, then reset with a new date', report_sort_order = 18 where id = 18;
update tbl_lkp_communication_status set report_description = 'Meeting set but temporarily postponed, Alchemis will rearrange directly with prospect, by default', report_sort_order = 19 where id = 16;
update tbl_lkp_communication_status set report_description = 'Meeting set but temporarily postponed, CLIENT will rearrange directly with prospect, by default', report_sort_order = 20, report_break_after_line = 1 where id = 14;

update tbl_lkp_communication_status set report_description = 'Meeting has been arranged with prospect', report_sort_order = 21 where id = 12;
update tbl_lkp_communication_status set report_description = 'Priority callback imminent', report_sort_order = 22 where id = 6;
update tbl_lkp_communication_status set report_description = 'Priority callback e.g. 0-3 months', report_sort_order = 23 where id = 5;
update tbl_lkp_communication_status set report_description = 'Priority callback e.g. 3-6 months', report_sort_order = 24 where id = 4;
update tbl_lkp_communication_status set report_description = 'Possible requirement e.g 3-6 months', report_sort_order = 25 where id = 3;
update tbl_lkp_communication_status set report_description = 'Possible requirement e.g 6 months +', report_sort_order = 26 where id = 2;
update tbl_lkp_communication_status set report_description = 'No requirement - possible callback e.g. 12 months', report_sort_order = 27, report_break_after_line = 1 where id = 1;

update tbl_lkp_communication_status set report_description = 'The prospect has referred us to a new decision maker for further contact', report_sort_order = 28 where id = 11;
update tbl_lkp_communication_status set report_description = 'Having spoken to the prospect the person was judged to be not worthwhile for a callback', report_sort_order = 29 where id = 9;
update tbl_lkp_communication_status set report_description = 'Having spoken to the prospect the company was judged to be not worthwhile for a callback', report_sort_order = 30 where id = 10;
update tbl_lkp_communication_status set report_description = "CLIENT has requested that Alchemis doesn't contact the prospect again", report_sort_order = 31 where id = 8;
update tbl_lkp_communication_status set report_description = 'Fresh lead', report_sort_order = 32, report_break_after_line = 1  where id = 7;


