create view show_contacts as 
select 
    concat(first_name, ' ', IF(LENGTH(substring(middle_name, 1, 1)) > 0, concat(substring(middle_name, 1, 1), '. '), ' '), last_name) as name,
    email_add1,
    contact_no
from user_profile;