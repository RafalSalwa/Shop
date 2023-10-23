GRANT ALL PRIVILEGES ON DATABASE testing TO tester;
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA interview TO tester;
GRANT EXECUTE ON ALL FUNCTIONS IN SCHEMA interview TO tester;
ALTER USER tester SET search_path = interview,testing,public;