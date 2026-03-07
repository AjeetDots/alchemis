update tbl_communications set notes = replace(notes, 'Non-Effective: ', '');
update tbl_communications set notes = replace(notes, 'Non-Effective', '') where notes like 'Non-Effective';
update tbl_communications set notes = replace(notes, 'Effective: ', '') where notes like 'Effective: ';