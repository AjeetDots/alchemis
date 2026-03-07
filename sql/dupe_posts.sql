-- Dupe names in companies
select co.id, co.name, c.first_name, c.surname, count(*) as count
from tbl_contacts c
join tbl_posts p
  on p.id = c.post_id
join tbl_companies co
  on co.id = p.company_id
where c.deleted = 0
  and p.deleted = 0
  and co.deleted = 0
group by co.id, c.first_name, c.surname
having count(*) > 1
order by co.name

-- Dupes per company
select r.id, r.name, count(*)
from (
  select co.id, co.name, c.first_name, c.surname, count(*) as count
  from tbl_contacts c
  join tbl_posts p
    on p.id = c.post_id
  join tbl_companies co
    on co.id = p.company_id
  where c.deleted = 0
    and p.deleted = 0
    and co.deleted = 0
  group by co.id, c.first_name, c.surname
  having count(*) > 1
) as r
group by r.id

-- Dupes grouped by parent companies
select r.id, r.name, count(*)
from (
  select pc.id, pc.name, c.first_name, c.surname, count(*) as count
  from tbl_contacts c
  join tbl_posts p
    on p.id = c.post_id
  join tbl_companies co
    on co.id = p.company_id
  join tbl_parent_company pc
    on pc.id = co.parent_company_id
  where c.deleted = 0
    and p.deleted = 0
    and co.deleted = 0
  group by pc.id, c.first_name, c.surname
  having count(*) > 1
) as r
group by r.id