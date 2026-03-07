# Companies we are reasonably confident we can soft delete:-
# - No telephone number
# - No website address
# - No posts/people
# - No client records
# - Last communication date before 2008
# - ID is less than x - don't delete recent records


select count(*)
from tbl_companies c
where 
  (telephone = '' or telephone is null)
  or
  (website = '' or website is null)
  or (
    select count(*)
    from tbl_posts p
    where p.company_id = c.id
  ) = 0
  or (
    select count(*)
    from tbl_post_initiatives pi
    join tbl_posts p on p.id = pi.post_id
    where p.company_id = c.id
  ) = 0
  or (
    select count(*)
    from tbl_communications co
    join tbl_post_initiatives pi
    on co.post_initiative_id = pi.id
    join tbl_posts p
    on p.id = pi.post_id
    where p.company_id = c.id
    and co.communication_date >= '2009-01-01'
    order by co.id desc
  ) = 0