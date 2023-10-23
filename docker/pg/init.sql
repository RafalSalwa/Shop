CREATE DATABASE testing;
CREATE USER tester WITH ENCRYPTED PASSWORD 'interview';
GRANT ALL PRIVILEGES ON DATABASE testing TO tester;
ALTER USER tester SET search_path = testing;