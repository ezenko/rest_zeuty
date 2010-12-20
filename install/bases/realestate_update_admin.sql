UPDATE [db_prefix]user set fname='[admin_name]', login = '[admin_login]', password='[admin_passw]', email = '[admin_email]' where id='1';
UPDATE [db_prefix]settings set value = '[admin_email]' where name='site_email';
