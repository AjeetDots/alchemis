--
-- tbl_actions
-- Add created_at field to tbl_actions - appears to have already been done.
-- Appears already done.
--

--
-- tbl_lkp_communication_status
--
alter table `tbl_lkp_communication_status` add column `full_description` varchar(255) not null default '' after `description`;
update `tbl_lkp_communication_status` set `full_description` = 'No requirement, long term callback' where id = 1;
update `tbl_lkp_communication_status` set `full_description` = 'Possible requirement, Callback long term e.g. 6mths' where id = 2;
update `tbl_lkp_communication_status` set `full_description` = 'Possible requirement, Callback Medium term e.g. 3-6 mths' where id = 3;
update `tbl_lkp_communication_status` set `full_description` = 'Priority Callback e.g. 3-6 mths' where id = 4;
update `tbl_lkp_communication_status` set `full_description` = 'Priority Callback e.g. 1-3 mths' where id = 5;
update `tbl_lkp_communication_status` set `full_description` = 'Priority Callback imminent' where id = 6;
update `tbl_lkp_communication_status` set `full_description` = 'An attempt to reach a key decision maker or key influencer' where id = 7;
update `tbl_lkp_communication_status` set `full_description` = '[Client] requested that we don\'t contact the prospect again' where id = 8;
update `tbl_lkp_communication_status` set `full_description` = 'Having spoken to the prospect the person was judged to be unworthwhile for callback' where id = 9;
update `tbl_lkp_communication_status` set `full_description` = 'Having spoken to the prospect the company was judged to be unworthwhile for callback' where id = 10;
update `tbl_lkp_communication_status` set `full_description` = '' where id = 11;
update `tbl_lkp_communication_status` set `full_description` = 'Meeting has been arranged with prospect' where id = 12;
update `tbl_lkp_communication_status` set `full_description` = '' where id = 13;
update `tbl_lkp_communication_status` set `full_description` = 'Meeting Set but temporarily postponed, [Client] has confirmed they will rearrange directly with prospect' where id = 14;
update `tbl_lkp_communication_status` set `full_description` = '' where id = 15;
update `tbl_lkp_communication_status` set `full_description` = 'Meeting Set but temporarily postponed, Alchemis will rearrange directly with prospect, by default' where id = 16;
update `tbl_lkp_communication_status` set `full_description` = '' where id = 17;
update `tbl_lkp_communication_status` set `full_description` = 'Meeting Set, was temporarily postponed, then reset with a new date' where id = 18;
update `tbl_lkp_communication_status` set `full_description` = '' where id = 19;
update `tbl_lkp_communication_status` set `full_description` = 'Meeting Set, Prospect has cancelled due to no requirement' where id = 20;
update `tbl_lkp_communication_status` set `full_description` = '' where id = 21;
update `tbl_lkp_communication_status` set `full_description` = 'Meeting Set, [Client] has cancelled' where id = 22;
update `tbl_lkp_communication_status` set `full_description` = '' where id = 23;
update `tbl_lkp_communication_status` set `full_description` = 'Meeting has been attended, [Client] has confirmed they will keep in contact' where id = 24;
update `tbl_lkp_communication_status` set `full_description` = '' where id = 25;
update `tbl_lkp_communication_status` set `full_description` = 'Meeting has been attended, [Client] has confirmed Alchemis to keep in contact' where id = 26;
update `tbl_lkp_communication_status` set `full_description` = '' where id = 27;
update `tbl_lkp_communication_status` set `full_description` = '[Client] has received a brief from the prospect' where id = 28;
update `tbl_lkp_communication_status` set `full_description` = '' where id = 29;
update `tbl_lkp_communication_status` set `full_description` = '' where id = 30;
update `tbl_lkp_communication_status` set `full_description` = '' where id = 31;
update `tbl_lkp_communication_status` set `full_description` = '' where id = 32;

--
-- vw_events
-- Needed recreating - was causing problems with display of calendar.  Problem no longer exists.
--