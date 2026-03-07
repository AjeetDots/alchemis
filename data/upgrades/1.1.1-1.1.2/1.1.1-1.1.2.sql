SET FOREIGN_KEY_CHECKS = 0;

--
-- tbl_actions
--
alter table tbl_actions add column communication_id int(11) default NULL;

update tbl_actions set created_at = due_date where created_at is null or created_at = '0000-00-00 00:00:00';


--
-- tbl_lkp_communication_status
--
DROP TABLE `tbl_lkp_communication_status`;
CREATE TABLE `tbl_lkp_communication_status` (
  `id` int(11) NOT NULL default '0',
  `lower_value` int(11) NOT NULL default '0',
  `upper_value` int(11) NOT NULL default '0',
  `description` varchar(50) NOT NULL default '',
  `full_description` varchar(255) NOT NULL default '',
  `is_auto_calculate` tinyint(1) NOT NULL default '0',
  `show_auto_calculate_options` tinyint(1) NOT NULL default '0',
  `sort_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`description`),
  KEY `ix_tbl_lkp_communication_status_lower_value` (`lower_value`),
  KEY `ix_tbl_lkp_communication_status_upper_value` (`upper_value`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

insert into `tbl_lkp_communication_status` values('1','0','13','Dormant','No requirement identified, long term call back','1','1','6'),
 ('2','14','21','Receptive long term','Possible requirement, interest expressed, Call back long term e.g. 6mths','1','1','8'),
 ('3','14','21','Receptive medium term','Possible requirement, interest expressed, Call back Medium term e.g. 3-6 mths','1','1','9'),
 ('4','22','25','Very receptive medium term','Priority Call back e.g. 3-6 mths','1','1','10'),
 ('5','22','25','Very receptive near term','Priority Call back e.g. 1-3 mths','1','1','11'),
 ('6','26','63','Hot','Priority Call back imminent, clear requirement identified','1','1','12'),
 ('7','64','64','Fresh lead','An attempt to reach a key decision maker or key influencer','0','1','2'),
 ('8','128','128','Do not call','[Client] requested that we don\'t contact the prospect again','0','1','3'),
 ('9','256','256','Not worthwhile prospect','Having spoken to the prospect the person was judged to be not worth a call back','0','1','5'),
 ('10','512','512','Not worthwhile company','Having spoken to the prospect the company was judged to be not worth a call back','0','1','4'),
 ('11','750','750','Referred to new DM','The prospect has referred us to a new decision maker for further contact','0','1','7'),
 ('12','1000','1000','Meeting set','Meeting has been arranged with prospect','0','0','13'),
 ('13','2000','2000','Follow-up meeting set','A 2nd meeting has been set with this prospect','0','0','14'),
 ('14','3000','3000','Meeting to be rearranged: client','Meeting Set but postponed, [Client] has confirmed they will rearrange directly with prospect','0','0','15'),
 ('15','4000','4000','Follow-up meeting to be rearranged: client','F/up Meeting Set but postponed, [Client] has confirmed they will rearrange themselves','0','0','16'),
 ('16','4500','4500','Meeting to be rearranged: Alchemis','Meeting Set but temporarily postponed, Alchemis will rearrange directly with prospect','0','0','17'),
 ('17','5000','5000','Follow-up meeting to be rearranged: Alchemis','F/up Meeting Set but postponed, Alchemis will rearrange directly with prospect, by default','0','0','18'),
 ('18','6000','6000','Meeting rearranged','Meeting Set, was temporarily postponed, then reset with a new date','0','0','19'),
 ('19','7000','7000','Follow-up meeting rearranged','F/up Meeting Set, was temporarily postponed, then reset with a new date','0','0','20'),
 ('20','8000','8000','Meeting cancelled: prospect','Meeting Set, Prospect has cancelled due to no requirement','0','0','21'),
 ('21','9000','9000','Follow-up meeting cancelled: prospect','F/up Meeting Set, [Client] has cancelled','0','0','22'),
 ('22','10000','10000','Meeting cancelled: client','Meeting Set, [Client] has cancelled','0','0','23'),
 ('23','11000','11000','Follow-up meeting cancelled: client','F/up Meeting Set, [Client] has cancelled','0','0','24'),
 ('24','12000','12000','Meeting attended: client','Meeting has been attended, [Client] has confirmed [Client] will keep in contact','0','0','25'),
 ('25','13000','13000','Follow-up meeting attended: client','F/up Meeting has been attended, [Client] has confirmed [Client] to keep in contact','0','0','26'),
 ('26','14000','14000','Meeting attended: Alchemis','Meeting has been attended, [Client] has confirmed Alchemis to keep in contact','0','0','27'),
 ('27','15000','15000','Follow-up meeting attended: Alchemis','F/up Meeting has been attended, [Client] has confirmed Alchemis to keep in contact','0','0','28'),
 ('28','16000','16000','Brief received','[Client] has received a brief from the prospect','0','0','30'),
 ('29','17000','17000','Proposal','[Client] has submitted a proposal following a meeting that has been attended','0','0','31'),
 ('30','18000','18000','Win','New Business Win as a result of Alchemis activity','0','1','32'),
 ('31','19000','19000','Gone cold','Meeting attended but opportunity has now gone cold','0','1','29'),
 ('32','20000','20000','Follow-up meeting to be arranged','','0','0','0');

SET FOREIGN_KEY_CHECKS = 1;