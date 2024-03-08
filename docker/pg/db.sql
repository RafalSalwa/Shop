--
-- PostgreSQL database dump
--

-- Dumped from database version 15.6
-- Dumped by pg_dump version 15.6 (Ubuntu 15.6-1.pgdg22.04+1)

-- Started on 2024-03-04 17:37:34 CET

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = on;

DROP DATABASE IF EXISTS interview;
--
-- TOC entry 3543 (class 1262 OID 16384)
-- Name: interview; Type: DATABASE; Schema: -; Owner: interview
--

CREATE DATABASE interview WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'en_US.utf8';


ALTER DATABASE interview OWNER TO interview;

\connect interview

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = on;

--
-- TOC entry 6 (class 2615 OID 16386)
-- Name: interview; Type: SCHEMA; Schema: -; Owner: pg_database_owner
--

CREATE SCHEMA interview;


ALTER SCHEMA interview OWNER TO pg_database_owner;

--
-- TOC entry 3544 (class 0 OID 0)
-- Dependencies: 6
-- Name: SCHEMA interview; Type: COMMENT; Schema: -; Owner: pg_database_owner
--

COMMENT ON SCHEMA interview IS 'standard public schema';


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 234 (class 1259 OID 25322)
-- Name: address; Type: TABLE; Schema: interview; Owner: interview
--

CREATE TABLE interview.address (
    address_id integer NOT NULL,
    first_name character varying(40) NOT NULL,
    last_name character varying(40) NOT NULL,
    address_line_1 character varying(40) NOT NULL,
    address_line_2 character varying(40) DEFAULT NULL::character varying,
    city character varying(40) NOT NULL,
    state character varying(40) NOT NULL,
    postal_code character varying(40) NOT NULL,
    user_id integer NOT NULL
);


ALTER TABLE interview.address OWNER TO interview;

--
-- TOC entry 222 (class 1259 OID 25272)
-- Name: address_addressid_seq; Type: SEQUENCE; Schema: interview; Owner: interview
--

CREATE SEQUENCE interview.address_addressid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE interview.address_addressid_seq OWNER TO interview;

--
-- TOC entry 231 (class 1259 OID 25300)
-- Name: cart; Type: TABLE; Schema: interview; Owner: interview
--

CREATE TABLE interview.cart (
    cart_id integer NOT NULL,
    user_id integer NOT NULL,
    status character varying(25) NOT NULL,
    created_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone
);


ALTER TABLE interview.cart OWNER TO interview;

--
-- TOC entry 3545 (class 0 OID 0)
-- Dependencies: 231
-- Name: COLUMN cart.created_at; Type: COMMENT; Schema: interview; Owner: interview
--

COMMENT ON COLUMN interview.cart.created_at IS '(DC2Type:datetime_immutable)';


--
-- TOC entry 219 (class 1259 OID 25269)
-- Name: cart_cart_id_seq; Type: SEQUENCE; Schema: interview; Owner: interview
--

CREATE SEQUENCE interview.cart_cart_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE interview.cart_cart_id_seq OWNER TO interview;

--
-- TOC entry 228 (class 1259 OID 25278)
-- Name: cart_item; Type: TABLE; Schema: interview; Owner: interview
--

CREATE TABLE interview.cart_item (
    cart_item_id integer NOT NULL,
    cart_id integer,
    quantity integer DEFAULT 1 NOT NULL,
    created_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    item_type character varying(30) NOT NULL,
    reference_entity_id integer
);


ALTER TABLE interview.cart_item OWNER TO interview;

--
-- TOC entry 216 (class 1259 OID 25266)
-- Name: cart_item_cart_item_id_seq; Type: SEQUENCE; Schema: interview; Owner: interview
--

CREATE SEQUENCE interview.cart_item_cart_item_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE interview.cart_item_cart_item_id_seq OWNER TO interview;

--
-- TOC entry 239 (class 1259 OID 25363)
-- Name: categories; Type: TABLE; Schema: interview; Owner: interview
--

CREATE TABLE interview.categories (
    category_id smallint NOT NULL,
    category_name character varying(32) NOT NULL,
    description text,
    slug character varying(64) DEFAULT NULL::character varying
);


ALTER TABLE interview.categories OWNER TO interview;

--
-- TOC entry 225 (class 1259 OID 25275)
-- Name: categories_categoryid_seq; Type: SEQUENCE; Schema: interview; Owner: interview
--

CREATE SEQUENCE interview.categories_categoryid_seq
    START WITH 10
    INCREMENT BY 1
    MINVALUE 10
    NO MAXVALUE
    CACHE 1;


ALTER TABLE interview.categories_categoryid_seq OWNER TO interview;

--
-- TOC entry 215 (class 1259 OID 25259)
-- Name: doctrine_migration_versions; Type: TABLE; Schema: interview; Owner: interview
--

CREATE TABLE interview.doctrine_migration_versions (
    version character varying(191) NOT NULL,
    executed_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    execution_time integer
);


ALTER TABLE interview.doctrine_migration_versions OWNER TO interview;

--
-- TOC entry 245 (class 1259 OID 25412)
-- Name: oauth2_access_token; Type: TABLE; Schema: interview; Owner: interview
--

CREATE TABLE interview.oauth2_access_token (
    identifier character(80) NOT NULL,
    client character varying(32) NOT NULL,
    expiry timestamp(0) without time zone NOT NULL,
    user_identifier character varying(128) DEFAULT NULL::character varying,
    scopes text,
    revoked boolean NOT NULL
);


ALTER TABLE interview.oauth2_access_token OWNER TO interview;

--
-- TOC entry 3546 (class 0 OID 0)
-- Dependencies: 245
-- Name: COLUMN oauth2_access_token.expiry; Type: COMMENT; Schema: interview; Owner: interview
--

COMMENT ON COLUMN interview.oauth2_access_token.expiry IS '(DC2Type:datetime_immutable)';


--
-- TOC entry 3547 (class 0 OID 0)
-- Dependencies: 245
-- Name: COLUMN oauth2_access_token.scopes; Type: COMMENT; Schema: interview; Owner: interview
--

COMMENT ON COLUMN interview.oauth2_access_token.scopes IS '(DC2Type:oauth2_scope)';


--
-- TOC entry 242 (class 1259 OID 25387)
-- Name: oauth2_authorization_code; Type: TABLE; Schema: interview; Owner: interview
--

CREATE TABLE interview.oauth2_authorization_code (
    identifier character(80) NOT NULL,
    client character varying(32) NOT NULL,
    expiry timestamp(0) without time zone NOT NULL,
    user_identifier character varying(128) DEFAULT NULL::character varying,
    scopes text,
    revoked boolean NOT NULL
);


ALTER TABLE interview.oauth2_authorization_code OWNER TO interview;

--
-- TOC entry 3548 (class 0 OID 0)
-- Dependencies: 242
-- Name: COLUMN oauth2_authorization_code.expiry; Type: COMMENT; Schema: interview; Owner: interview
--

COMMENT ON COLUMN interview.oauth2_authorization_code.expiry IS '(DC2Type:datetime_immutable)';


--
-- TOC entry 3549 (class 0 OID 0)
-- Dependencies: 242
-- Name: COLUMN oauth2_authorization_code.scopes; Type: COMMENT; Schema: interview; Owner: interview
--

COMMENT ON COLUMN interview.oauth2_authorization_code.scopes IS '(DC2Type:oauth2_scope)';


--
-- TOC entry 244 (class 1259 OID 25403)
-- Name: oauth2_client; Type: TABLE; Schema: interview; Owner: interview
--

CREATE TABLE interview.oauth2_client (
    identifier character varying(32) NOT NULL,
    name character varying(128) NOT NULL,
    secret character varying(128) DEFAULT NULL::character varying,
    redirect_uris text,
    grants text,
    scopes text,
    active boolean NOT NULL,
    allow_plain_text_pkce boolean DEFAULT false NOT NULL
);


ALTER TABLE interview.oauth2_client OWNER TO interview;

--
-- TOC entry 3550 (class 0 OID 0)
-- Dependencies: 244
-- Name: COLUMN oauth2_client.redirect_uris; Type: COMMENT; Schema: interview; Owner: interview
--

COMMENT ON COLUMN interview.oauth2_client.redirect_uris IS '(DC2Type:oauth2_redirect_uri)';


--
-- TOC entry 3551 (class 0 OID 0)
-- Dependencies: 244
-- Name: COLUMN oauth2_client.grants; Type: COMMENT; Schema: interview; Owner: interview
--

COMMENT ON COLUMN interview.oauth2_client.grants IS '(DC2Type:oauth2_grant)';


--
-- TOC entry 3552 (class 0 OID 0)
-- Dependencies: 244
-- Name: COLUMN oauth2_client.scopes; Type: COMMENT; Schema: interview; Owner: interview
--

COMMENT ON COLUMN interview.oauth2_client.scopes IS '(DC2Type:oauth2_scope)';


--
-- TOC entry 240 (class 1259 OID 25371)
-- Name: oauth2_client_profile; Type: TABLE; Schema: interview; Owner: interview
--

CREATE TABLE interview.oauth2_client_profile (
    id integer NOT NULL,
    client_id character varying(32) NOT NULL,
    name character varying(255) NOT NULL,
    description text
);


ALTER TABLE interview.oauth2_client_profile OWNER TO interview;

--
-- TOC entry 226 (class 1259 OID 25276)
-- Name: oauth2_client_profile_id_seq; Type: SEQUENCE; Schema: interview; Owner: interview
--

CREATE SEQUENCE interview.oauth2_client_profile_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE interview.oauth2_client_profile_id_seq OWNER TO interview;

--
-- TOC entry 243 (class 1259 OID 25396)
-- Name: oauth2_refresh_token; Type: TABLE; Schema: interview; Owner: interview
--

CREATE TABLE interview.oauth2_refresh_token (
    identifier character(80) NOT NULL,
    access_token character(80) DEFAULT NULL::bpchar,
    expiry timestamp(0) without time zone NOT NULL,
    revoked boolean NOT NULL
);


ALTER TABLE interview.oauth2_refresh_token OWNER TO interview;

--
-- TOC entry 3553 (class 0 OID 0)
-- Dependencies: 243
-- Name: COLUMN oauth2_refresh_token.expiry; Type: COMMENT; Schema: interview; Owner: interview
--

COMMENT ON COLUMN interview.oauth2_refresh_token.expiry IS '(DC2Type:datetime_immutable)';


--
-- TOC entry 237 (class 1259 OID 25340)
-- Name: oauth2_user_consent; Type: TABLE; Schema: interview; Owner: interview
--

CREATE TABLE interview.oauth2_user_consent (
    id integer NOT NULL,
    client_id character varying(32) NOT NULL,
    created timestamp(0) without time zone NOT NULL,
    expires timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    scopes text,
    ip_address character varying(255) DEFAULT NULL::character varying,
    user_id integer NOT NULL
);


ALTER TABLE interview.oauth2_user_consent OWNER TO interview;

--
-- TOC entry 3554 (class 0 OID 0)
-- Dependencies: 237
-- Name: COLUMN oauth2_user_consent.created; Type: COMMENT; Schema: interview; Owner: interview
--

COMMENT ON COLUMN interview.oauth2_user_consent.created IS '(DC2Type:datetime_immutable)';


--
-- TOC entry 3555 (class 0 OID 0)
-- Dependencies: 237
-- Name: COLUMN oauth2_user_consent.expires; Type: COMMENT; Schema: interview; Owner: interview
--

COMMENT ON COLUMN interview.oauth2_user_consent.expires IS '(DC2Type:datetime_immutable)';


--
-- TOC entry 3556 (class 0 OID 0)
-- Dependencies: 237
-- Name: COLUMN oauth2_user_consent.scopes; Type: COMMENT; Schema: interview; Owner: interview
--

COMMENT ON COLUMN interview.oauth2_user_consent.scopes IS '(DC2Type:simple_array)';


--
-- TOC entry 223 (class 1259 OID 25273)
-- Name: oauth2_user_consent_id_seq; Type: SEQUENCE; Schema: interview; Owner: interview
--

CREATE SEQUENCE interview.oauth2_user_consent_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE interview.oauth2_user_consent_id_seq OWNER TO interview;

--
-- TOC entry 241 (class 1259 OID 25379)
-- Name: order_item; Type: TABLE; Schema: interview; Owner: interview
--

CREATE TABLE interview.order_item (
    order_item_id integer NOT NULL,
    order_id integer,
    cart_item_entity json NOT NULL,
    cart_item_type character varying(25) NOT NULL
);


ALTER TABLE interview.order_item OWNER TO interview;

--
-- TOC entry 227 (class 1259 OID 25277)
-- Name: order_item_order_item_id_seq; Type: SEQUENCE; Schema: interview; Owner: interview
--

CREATE SEQUENCE interview.order_item_order_item_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE interview.order_item_order_item_id_seq OWNER TO interview;

--
-- TOC entry 217 (class 1259 OID 25267)
-- Name: order_orderid_seq; Type: SEQUENCE; Schema: interview; Owner: interview
--

CREATE SEQUENCE interview.order_orderid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE interview.order_orderid_seq OWNER TO interview;

--
-- TOC entry 229 (class 1259 OID 25288)
-- Name: orders; Type: TABLE; Schema: interview; Owner: interview
--

CREATE TABLE interview.orders (
    order_id integer NOT NULL,
    status character varying(25) NOT NULL,
    amount integer NOT NULL,
    created_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    user_id integer NOT NULL
);


ALTER TABLE interview.orders OWNER TO interview;

--
-- TOC entry 232 (class 1259 OID 25307)
-- Name: payment; Type: TABLE; Schema: interview; Owner: interview
--

CREATE TABLE interview.payment (
    payment_id integer NOT NULL,
    order_id integer,
    operation_number character varying(40) NOT NULL,
    operation_type character varying(40) NOT NULL,
    amount integer NOT NULL,
    status character varying(25) NOT NULL,
    payment_date timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    created_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    user_id integer NOT NULL
);


ALTER TABLE interview.payment OWNER TO interview;

--
-- TOC entry 220 (class 1259 OID 25270)
-- Name: payment_paymentid_seq; Type: SEQUENCE; Schema: interview; Owner: interview
--

CREATE SEQUENCE interview.payment_paymentid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE interview.payment_paymentid_seq OWNER TO interview;

--
-- TOC entry 238 (class 1259 OID 25350)
-- Name: plan; Type: TABLE; Schema: interview; Owner: interview
--

CREATE TABLE interview.plan (
    plan_id integer NOT NULL,
    plan_name character varying(255) NOT NULL,
    description text NOT NULL,
    is_active boolean DEFAULT false NOT NULL,
    is_visible boolean DEFAULT false NOT NULL,
    unit_price smallint NOT NULL,
    tier smallint NOT NULL,
    created_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    deleted_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone
);


ALTER TABLE interview.plan OWNER TO interview;

--
-- TOC entry 224 (class 1259 OID 25274)
-- Name: plan_plan_id_seq; Type: SEQUENCE; Schema: interview; Owner: interview
--

CREATE SEQUENCE interview.plan_plan_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE interview.plan_plan_id_seq OWNER TO interview;

--
-- TOC entry 233 (class 1259 OID 25315)
-- Name: products; Type: TABLE; Schema: interview; Owner: interview
--

CREATE TABLE interview.products (
    product_id integer NOT NULL,
    subscription_plan_id integer NOT NULL,
    product_name character varying(40) NOT NULL,
    category_id smallint,
    quantity_per_unit character varying(20) DEFAULT NULL::character varying,
    unit_price smallint NOT NULL,
    units_in_stock smallint,
    units_on_order smallint
);


ALTER TABLE interview.products OWNER TO interview;

--
-- TOC entry 221 (class 1259 OID 25271)
-- Name: products_productid_seq; Type: SEQUENCE; Schema: interview; Owner: interview
--

CREATE SEQUENCE interview.products_productid_seq
    START WITH 80
    INCREMENT BY 1
    MINVALUE 80
    NO MAXVALUE
    CACHE 1;


ALTER TABLE interview.products_productid_seq OWNER TO interview;

--
-- TOC entry 236 (class 1259 OID 25329)
-- Name: subscription; Type: TABLE; Schema: interview; Owner: interview
--

CREATE TABLE interview.subscription (
    subscription_id integer NOT NULL,
    subscription_plan_id integer,
    user_id integer,
    tier smallint NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    starts_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP,
    ends_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone
);


ALTER TABLE interview.subscription OWNER TO interview;

--
-- TOC entry 235 (class 1259 OID 25328)
-- Name: subscription_subscription_id_seq; Type: SEQUENCE; Schema: interview; Owner: interview
--

CREATE SEQUENCE interview.subscription_subscription_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE interview.subscription_subscription_id_seq OWNER TO interview;

--
-- TOC entry 3557 (class 0 OID 0)
-- Dependencies: 235
-- Name: subscription_subscription_id_seq; Type: SEQUENCE OWNED BY; Schema: interview; Owner: interview
--

ALTER SEQUENCE interview.subscription_subscription_id_seq OWNED BY interview.subscription.subscription_id;


--
-- TOC entry 230 (class 1259 OID 25295)
-- Name: suppliers; Type: TABLE; Schema: interview; Owner: interview
--

CREATE TABLE interview.suppliers (
    supplier_id smallint NOT NULL,
    company_name character varying(40) NOT NULL
);


ALTER TABLE interview.suppliers OWNER TO interview;

--
-- TOC entry 218 (class 1259 OID 25268)
-- Name: suppliers_supplier_id_seq; Type: SEQUENCE; Schema: interview; Owner: interview
--

CREATE SEQUENCE interview.suppliers_supplier_id_seq
    START WITH 10
    INCREMENT BY 1
    MINVALUE 10
    NO MAXVALUE
    CACHE 1;


ALTER TABLE interview.suppliers_supplier_id_seq OWNER TO interview;

--
-- TOC entry 3289 (class 2604 OID 25332)
-- Name: subscription subscription_id; Type: DEFAULT; Schema: interview; Owner: interview
--

ALTER TABLE ONLY interview.subscription ALTER COLUMN subscription_id SET DEFAULT nextval('interview.subscription_subscription_id_seq'::regclass);


--
-- TOC entry 3526 (class 0 OID 25322)
-- Dependencies: 234
-- Data for Name: address; Type: TABLE DATA; Schema: interview; Owner: interview
--



--
-- TOC entry 3523 (class 0 OID 25300)
-- Dependencies: 231
-- Data for Name: cart; Type: TABLE DATA; Schema: interview; Owner: interview
--



--
-- TOC entry 3520 (class 0 OID 25278)
-- Dependencies: 228
-- Data for Name: cart_item; Type: TABLE DATA; Schema: interview; Owner: interview
--



--
-- TOC entry 3531 (class 0 OID 25363)
-- Dependencies: 239
-- Data for Name: categories; Type: TABLE DATA; Schema: interview; Owner: interview
--

INSERT INTO interview.categories (category_id, category_name, description, slug) VALUES (1, 'Beverages', 'Soft drinks, coffees, teas, beers, and ales', 'beverages') ON CONFLICT DO NOTHING;
INSERT INTO interview.categories (category_id, category_name, description, slug) VALUES (2, 'Condiments', 'Sweet and savory sauces, relishes, spreads, and seasonings', 'condiments') ON CONFLICT DO NOTHING;
INSERT INTO interview.categories (category_id, category_name, description, slug) VALUES (3, 'Confections', 'Desserts, candies, and sweet breads', 'confections') ON CONFLICT DO NOTHING;
INSERT INTO interview.categories (category_id, category_name, description, slug) VALUES (4, 'Dairy Products', 'Cheeses', 'dairy-products') ON CONFLICT DO NOTHING;
INSERT INTO interview.categories (category_id, category_name, description, slug) VALUES (5, 'Grains/Cereals', 'Breads, crackers, pasta, and cereal', 'grains-cereals') ON CONFLICT DO NOTHING;
INSERT INTO interview.categories (category_id, category_name, description, slug) VALUES (6, 'Meat/Poultry', 'Prepared meats', 'meat-poultry') ON CONFLICT DO NOTHING;
INSERT INTO interview.categories (category_id, category_name, description, slug) VALUES (7, 'Produce', 'Dried fruit and bean curd', 'produce') ON CONFLICT DO NOTHING;
INSERT INTO interview.categories (category_id, category_name, description, slug) VALUES (8, 'Seafood', 'Seaweed and fish', 'seafood') ON CONFLICT DO NOTHING;


--
-- TOC entry 3507 (class 0 OID 25259)
-- Dependencies: 215
-- Data for Name: doctrine_migration_versions; Type: TABLE DATA; Schema: interview; Owner: interview
--



--
-- TOC entry 3537 (class 0 OID 25412)
-- Dependencies: 245
-- Data for Name: oauth2_access_token; Type: TABLE DATA; Schema: interview; Owner: interview
--



--
-- TOC entry 3534 (class 0 OID 25387)
-- Dependencies: 242
-- Data for Name: oauth2_authorization_code; Type: TABLE DATA; Schema: interview; Owner: interview
--



--
-- TOC entry 3536 (class 0 OID 25403)
-- Dependencies: 244
-- Data for Name: oauth2_client; Type: TABLE DATA; Schema: interview; Owner: interview
--

INSERT INTO interview.oauth2_client (identifier, name, secret, redirect_uris, grants, scopes, active, allow_plain_text_pkce) VALUES ('testclient', 'Test Client', 'testpass', 'https://interview.local/callback', 'authorization_code client_credentials refresh_token', 'profile email cart', true, false) ON CONFLICT DO NOTHING;


--
-- TOC entry 3532 (class 0 OID 25371)
-- Dependencies: 240
-- Data for Name: oauth2_client_profile; Type: TABLE DATA; Schema: interview; Owner: interview
--

INSERT INTO interview.oauth2_client_profile (id, client_id, name, description) VALUES (1, 'testclient', 'Test Client App', NULL) ON CONFLICT DO NOTHING;


--
-- TOC entry 3535 (class 0 OID 25396)
-- Dependencies: 243
-- Data for Name: oauth2_refresh_token; Type: TABLE DATA; Schema: interview; Owner: interview
--



--
-- TOC entry 3529 (class 0 OID 25340)
-- Dependencies: 237
-- Data for Name: oauth2_user_consent; Type: TABLE DATA; Schema: interview; Owner: interview
--

INSERT INTO interview.oauth2_user_consent (id, client_id, created, expires, scopes, ip_address, user_id) VALUES (1, 'testclient', '2023-08-24 00:47:30', '2023-09-23 00:47:30', 'profile,email,cart', '172.18.0.1', 1) ON CONFLICT DO NOTHING;


--
-- TOC entry 3533 (class 0 OID 25379)
-- Dependencies: 241
-- Data for Name: order_item; Type: TABLE DATA; Schema: interview; Owner: interview
--



--
-- TOC entry 3521 (class 0 OID 25288)
-- Dependencies: 229
-- Data for Name: orders; Type: TABLE DATA; Schema: interview; Owner: interview
--



--
-- TOC entry 3524 (class 0 OID 25307)
-- Dependencies: 232
-- Data for Name: payment; Type: TABLE DATA; Schema: interview; Owner: interview
--



--
-- TOC entry 3530 (class 0 OID 25350)
-- Dependencies: 238
-- Data for Name: plan; Type: TABLE DATA; Schema: interview; Owner: interview
--

INSERT INTO interview.plan (plan_id, plan_name, description, is_active, is_visible, unit_price, tier, created_at, updated_at, deleted_at) VALUES (1, 'freemium', 'Basic plan for all users, that allows platform usage', true, true, 0, 1, '2023-05-22 19:49:34', NULL, NULL) ON CONFLICT DO NOTHING;
INSERT INTO interview.plan (plan_id, plan_name, description, is_active, is_visible, unit_price, tier, created_at, updated_at, deleted_at) VALUES (2, 'plus', 'Additional plan with more offers for user', true, true, 1000, 10, '2023-05-22 19:49:34', NULL, NULL) ON CONFLICT DO NOTHING;
INSERT INTO interview.plan (plan_id, plan_name, description, is_active, is_visible, unit_price, tier, created_at, updated_at, deleted_at) VALUES (3, 'gold', 'Basic plan for all users, that allows platform usage', true, true, 5000, 20, '2023-05-22 19:49:34', '2023-05-22 19:49:34', NULL) ON CONFLICT DO NOTHING;
INSERT INTO interview.plan (plan_id, plan_name, description, is_active, is_visible, unit_price, tier, created_at, updated_at, deleted_at) VALUES (4, 'vip', 'exclusive offers for VIP members', false, true, 10000, 30, '2023-05-22 19:49:34', NULL, '2023-05-22 19:49:34') ON CONFLICT DO NOTHING;
INSERT INTO interview.plan (plan_id, plan_name, description, is_active, is_visible, unit_price, tier, created_at, updated_at, deleted_at) VALUES (5, '500+', 'Many many discounts, everywhere', true, true, 15000, 40, '2023-05-22 19:49:34', NULL, NULL) ON CONFLICT DO NOTHING;
INSERT INTO interview.plan (plan_id, plan_name, description, is_active, is_visible, unit_price, tier, created_at, updated_at, deleted_at) VALUES (6, '800+', 'They pay You for visiting them', true, true, 30000, 50, '2023-05-22 19:49:34', NULL, NULL) ON CONFLICT DO NOTHING;


--
-- TOC entry 3525 (class 0 OID 25315)
-- Dependencies: 233
-- Data for Name: products; Type: TABLE DATA; Schema: interview; Owner: interview
--

INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (66, 1, 'Louisiana Hot Spiced Okra', 2, '24 - 8 oz jars', 1700, 4, 100) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (74, 4, 'Longlife Tofu', 4, '5 kg pkg.', 1000, 6, 20) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (73, 5, 'Röd Kaviar', 17, '24 - 150 g jars', 1500, 100, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (69, 1, 'Gudbrandsdalsost', 15, '10 kg pkg.', 3600, 26, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (72, 6, 'Mozzarella di Giovanni', 14, '24 - 200 g pkgs.', 3480, 14, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (70, 2, 'Outback Lager', 7, '24 - 355 ml bottles', 1500, 15, 10) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (68, 1, 'Scottish Longbreads', 8, '10 boxes x 8 pieces', 1250, 6, 10) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (67, 1, 'Laughing Lumberjack Lager', 16, '24 - 12 oz bottles', 1400, 52, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (77, 1, 'Original Frankfurter grüne Soße', 12, '12 boxes', 1300, 2, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (65, 3, 'Louisiana Fiery Hot Pepper Sauce', 2, '32 - 8 oz bottles', 2105, 76, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (64, 4, 'Wimmers gute Semmelknödel', 12, '20 bags x 4 pieces', 3325, 22, 80) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (63, 1, 'Vegie-spread', 7, '15 - 625 g jars', 4390, 24, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (48, 2, 'Chocolade', 22, '10 pkgs.', 1275, 15, 70) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (47, 2, 'Zaanse koeken', 22, '10 - 4 oz boxes', 950, 36, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (46, 4, 'Spegesild', 21, '4 - 450 g glasses', 1200, 95, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (45, 4, 'Rogede sild', 21, '1k pkg.', 950, 5, 70) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (44, 4, 'Gula Malacca', 20, '20 - 2 kg bags', 1945, 27, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (43, 2, 'Ipoh Coffee', 20, '16 - 500 g tins', 4600, 17, 10) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (42, 2, 'Singaporean Hokkien Fried Mee', 20, '32 - 1 kg pkgs.', 1400, 26, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (41, 2, 'Jack''s New England Clam Chowder', 19, '12 - 12 oz cans', 965, 85, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (40, 2, 'Boston Crab Meat', 19, '24 - 4 oz tins', 1840, 123, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (39, 2, 'Chartreuse verte', 18, '750 cc per bottle', 1800, 69, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (38, 2, 'Côte de Blaye', 18, '12 - 75 cl bottles', 26350, 17, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (37, 2, 'Gravad lax', 17, '12 - 500 g pkgs.', 2600, 11, 50) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (36, 2, 'Inlagd Sill', 17, '24 - 250 g  jars', 1900, 112, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (35, 2, 'Steeleye Stout', 16, '24 - 12 oz bottles', 1800, 20, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (34, 2, 'Sasquatch Ale', 16, '24 - 12 oz bottles', 1400, 111, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (33, 2, 'Geitost', 15, '500 g', 250, 112, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (32, 2, 'Mascarpone Fabioli', 14, '24 - 200 g pkgs.', 3200, 9, 40) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (31, 2, 'Gorgonzola Telino', 14, '12 - 100 g pkgs', 1250, 0, 70) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (30, 2, 'Nord-Ost Matjeshering', 13, '10 - 200 g glasses', 2589, 10, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (29, 3, 'Thüringer Rostbratwurst', 12, '50 bags x 30 sausgs.', 12379, 0, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (28, 3, 'Rössle Sauerkraut', 12, '25 - 825 g cans', 4560, 26, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (27, 1, 'Schoggi Schokolade', 11, '100 - 100 g pieces', 4390, 49, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (61, 5, 'Sirop d''érable', 29, '24 - 500 ml bottles', 2850, 113, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (60, 6, 'Camembert Pierrot', 28, '15 - 300 g rounds', 3400, 19, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (58, 2, 'Escargots de Bourgogne', 27, '24 pieces', 1325, 62, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (57, 2, 'Ravioli Angelo', 26, '24 - 250 g pkgs.', 1950, 36, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (56, 2, 'Gnocchi di nonna Alice', 26, '24 - 250 g pkgs.', 3800, 21, 10) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (55, 3, 'Pâté chinois', 25, '24 boxes x 2 pies', 2400, 115, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (54, 2, 'Tourtière', 25, '16 pies', 745, 21, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (53, 4, 'Perth Pasties', 24, '48 pieces', 3280, 0, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (52, 3, 'Filo Mix', 24, '16 - 2 kg boxes', 700, 38, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (51, 3, 'Manjimup Dried Apples', 24, '50 - 300 g pkgs.', 5300, 20, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (50, 3, 'Valkoinen suklaa', 23, '12 - 100 g bars', 1625, 65, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (49, 3, 'Maxilaku', 23, '24 - 50 g pkgs.', 2000, 10, 60) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (62, 1, 'Tarte au sucre', 29, '48 pies', 4930, 17, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (59, 1, 'Raclette Courdavault', 28, '5 kg pkg.', 5500, 79, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (26, 1, 'Gumbär Gummibärchen', 11, '100 - 250 g bags', 3123, 15, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (25, 1, 'NuNuCa Nuß-Nougat-Creme', 11, '20 - 450 g glasses', 1400, 76, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (24, 1, 'Guaraná Fantástica', 10, '12 - 355 ml cans', 450, 20, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (23, 1, 'Tunnbröd', 9, '12 - 250 g pkgs.', 900, 61, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (22, 1, 'Gustaf''s Knäckebröd', 9, '24 - 500 g pkgs.', 2100, 104, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (21, 1, 'Sir Rodney''s Scones', 8, '24 pkgs. x 4 pieces', 1000, 3, 40) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (20, 1, 'Sir Rodney''s Marmalade', 8, '30 gift boxes', 8100, 40, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (19, 1, 'Teatime Chocolate Biscuits', 8, '10 boxes x 12 pieces', 920, 25, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (18, 1, 'Carnarvon Tigers', 7, '16 kg pkg.', 6250, 42, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (17, 1, 'Alice Mutton', 7, '20 - 1 kg tins', 3900, 0, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (16, 1, 'Pavlova', 7, '32 - 500 g boxes', 1745, 29, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (15, 1, 'Genen Shouyu', 6, '24 - 250 ml bottles', 1300, 39, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (14, 1, 'Tofu', 6, '40 - 100 g pkgs.', 2325, 35, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (13, 1, 'Konbu', 6, '2 kg box', 600, 24, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (12, 1, 'Queso Manchego La Pastora', 5, '10 - 500 g pkgs.', 3800, 86, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (9, 1, 'Mishi Kobe Niku', 4, '18 - 500 g pkgs.', 9700, 29, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (8, 1, 'Northwoods Cranberry Sauce', 3, '12 - 12 oz jars', 4000, 6, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (7, 1, 'Uncle Bob''s Organic Dried Pears', 3, '12 - 1 lb pkgs.', 3000, 15, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (6, 1, 'Grandma''s Boysenberry Spread', 3, '12 - 8 oz jars', 2500, 120, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (5, 1, 'Chef Anton''s Gumbo Mix', 2, '36 boxes', 2135, 0, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (4, 1, 'Chef Anton''s Cajun Seasoning', 2, '48 - 6 oz jars', 2200, 53, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (3, 1, 'Aniseed Syrup', 1, '12 - 550 ml bottles', 1000, 13, 70) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (2, 1, 'Chang', 1, '24 - 12 oz bottles', 1900, 17, 40) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (1, 1, 'Chai', 8, '10 boxes x 30 bags', 1800, 39, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (10, 1, 'Ikura', 4, '12 - 200 ml jars', 3100, 31, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (71, 1, 'Flotemysost', 15, '10 - 500 g pkgs.', 2150, 25, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (76, 2, 'Lakkalikööri', 23, '500 ml', 1800, 84, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (75, 3, 'Rhönbräu Klosterbier', 12, '24 - 0.5 l bottles', 775, 125, 0) ON CONFLICT DO NOTHING;
INSERT INTO interview.products (product_id, subscription_plan_id, product_name, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order) VALUES (11, 1, 'Queso Cabrales', 5, '1 kg pkg.', 2100, 10, 30) ON CONFLICT DO NOTHING;


--
-- TOC entry 3528 (class 0 OID 25329)
-- Dependencies: 236
-- Data for Name: subscription; Type: TABLE DATA; Schema: interview; Owner: interview
--



--
-- TOC entry 3522 (class 0 OID 25295)
-- Dependencies: 230
-- Data for Name: suppliers; Type: TABLE DATA; Schema: interview; Owner: interview
--

INSERT INTO interview.suppliers (supplier_id, company_name) VALUES (1, 'Exotic Liquids') ON CONFLICT DO NOTHING;
INSERT INTO interview.suppliers (supplier_id, company_name) VALUES (2, 'New Orleans Cajun Delights') ON CONFLICT DO NOTHING;
INSERT INTO interview.suppliers (supplier_id, company_name) VALUES (3, 'Grandma Kelly''s Homestead') ON CONFLICT DO NOTHING;
INSERT INTO interview.suppliers (supplier_id, company_name) VALUES (4, 'Tokyo Traders') ON CONFLICT DO NOTHING;
INSERT INTO interview.suppliers (supplier_id, company_name) VALUES (5, 'Cooperativa de Quesos ''Las Cabras''') ON CONFLICT DO NOTHING;
INSERT INTO interview.suppliers (supplier_id, company_name) VALUES (6, 'Mayumi''s') ON CONFLICT DO NOTHING;
INSERT INTO interview.suppliers (supplier_id, company_name) VALUES (7, 'Pavlova, Ltd.') ON CONFLICT DO NOTHING;
INSERT INTO interview.suppliers (supplier_id, company_name) VALUES (8, 'Specialty Biscuits, Ltd.') ON CONFLICT DO NOTHING;
INSERT INTO interview.suppliers (supplier_id, company_name) VALUES (9, 'PB Knäckebröd AB') ON CONFLICT DO NOTHING;
INSERT INTO interview.suppliers (supplier_id, company_name) VALUES (10, 'Refrescos Americanas LTDA') ON CONFLICT DO NOTHING;
INSERT INTO interview.suppliers (supplier_id, company_name) VALUES (11, 'Heli Süßwaren GmbH & Co. KG') ON CONFLICT DO NOTHING;
INSERT INTO interview.suppliers (supplier_id, company_name) VALUES (12, 'Plutzer Lebensmittelgroßmärkte AG') ON CONFLICT DO NOTHING;
INSERT INTO interview.suppliers (supplier_id, company_name) VALUES (13, 'Nord-Ost-Fisch Handelsgesellschaft mbH') ON CONFLICT DO NOTHING;
INSERT INTO interview.suppliers (supplier_id, company_name) VALUES (14, 'Formaggi Fortini s.r.l.') ON CONFLICT DO NOTHING;
INSERT INTO interview.suppliers (supplier_id, company_name) VALUES (15, 'Norske Meierier') ON CONFLICT DO NOTHING;
INSERT INTO interview.suppliers (supplier_id, company_name) VALUES (16, 'Bigfoot Breweries') ON CONFLICT DO NOTHING;
INSERT INTO interview.suppliers (supplier_id, company_name) VALUES (17, 'Svensk Sjöföda AB') ON CONFLICT DO NOTHING;
INSERT INTO interview.suppliers (supplier_id, company_name) VALUES (18, 'Aux joyeux ecclésiastiques') ON CONFLICT DO NOTHING;
INSERT INTO interview.suppliers (supplier_id, company_name) VALUES (19, 'New England Seafood Cannery') ON CONFLICT DO NOTHING;
INSERT INTO interview.suppliers (supplier_id, company_name) VALUES (20, 'Leka Trading') ON CONFLICT DO NOTHING;
INSERT INTO interview.suppliers (supplier_id, company_name) VALUES (21, 'Lyngbysild') ON CONFLICT DO NOTHING;
INSERT INTO interview.suppliers (supplier_id, company_name) VALUES (22, 'Zaanse Snoepfabriek') ON CONFLICT DO NOTHING;
INSERT INTO interview.suppliers (supplier_id, company_name) VALUES (23, 'Karkki Oy') ON CONFLICT DO NOTHING;
INSERT INTO interview.suppliers (supplier_id, company_name) VALUES (24, 'G''day, Mate') ON CONFLICT DO NOTHING;
INSERT INTO interview.suppliers (supplier_id, company_name) VALUES (25, 'Ma Maison') ON CONFLICT DO NOTHING;
INSERT INTO interview.suppliers (supplier_id, company_name) VALUES (26, 'Pasta Buttini s.r.l.') ON CONFLICT DO NOTHING;
INSERT INTO interview.suppliers (supplier_id, company_name) VALUES (27, 'Escargots Nouveaux') ON CONFLICT DO NOTHING;
INSERT INTO interview.suppliers (supplier_id, company_name) VALUES (28, 'Gai pâturage') ON CONFLICT DO NOTHING;
INSERT INTO interview.suppliers (supplier_id, company_name) VALUES (29, 'Forêts d''érables') ON CONFLICT DO NOTHING;


--
-- TOC entry 3558 (class 0 OID 0)
-- Dependencies: 222
-- Name: address_addressid_seq; Type: SEQUENCE SET; Schema: interview; Owner: interview
--

SELECT pg_catalog.setval('interview.address_addressid_seq', 1, false);


--
-- TOC entry 3559 (class 0 OID 0)
-- Dependencies: 219
-- Name: cart_cart_id_seq; Type: SEQUENCE SET; Schema: interview; Owner: interview
--

SELECT pg_catalog.setval('interview.cart_cart_id_seq', 1, false);


--
-- TOC entry 3560 (class 0 OID 0)
-- Dependencies: 216
-- Name: cart_item_cart_item_id_seq; Type: SEQUENCE SET; Schema: interview; Owner: interview
--

SELECT pg_catalog.setval('interview.cart_item_cart_item_id_seq', 1, false);


--
-- TOC entry 3561 (class 0 OID 0)
-- Dependencies: 225
-- Name: categories_categoryid_seq; Type: SEQUENCE SET; Schema: interview; Owner: interview
--

SELECT pg_catalog.setval('interview.categories_categoryid_seq', 10, false);


--
-- TOC entry 3562 (class 0 OID 0)
-- Dependencies: 226
-- Name: oauth2_client_profile_id_seq; Type: SEQUENCE SET; Schema: interview; Owner: interview
--

SELECT pg_catalog.setval('interview.oauth2_client_profile_id_seq', 1, false);


--
-- TOC entry 3563 (class 0 OID 0)
-- Dependencies: 223
-- Name: oauth2_user_consent_id_seq; Type: SEQUENCE SET; Schema: interview; Owner: interview
--

SELECT pg_catalog.setval('interview.oauth2_user_consent_id_seq', 1, false);


--
-- TOC entry 3564 (class 0 OID 0)
-- Dependencies: 227
-- Name: order_item_order_item_id_seq; Type: SEQUENCE SET; Schema: interview; Owner: interview
--

SELECT pg_catalog.setval('interview.order_item_order_item_id_seq', 1, false);


--
-- TOC entry 3565 (class 0 OID 0)
-- Dependencies: 217
-- Name: order_orderid_seq; Type: SEQUENCE SET; Schema: interview; Owner: interview
--

SELECT pg_catalog.setval('interview.order_orderid_seq', 1, false);


--
-- TOC entry 3566 (class 0 OID 0)
-- Dependencies: 220
-- Name: payment_paymentid_seq; Type: SEQUENCE SET; Schema: interview; Owner: interview
--

SELECT pg_catalog.setval('interview.payment_paymentid_seq', 1, false);


--
-- TOC entry 3567 (class 0 OID 0)
-- Dependencies: 224
-- Name: plan_plan_id_seq; Type: SEQUENCE SET; Schema: interview; Owner: interview
--

SELECT pg_catalog.setval('interview.plan_plan_id_seq', 1, false);


--
-- TOC entry 3568 (class 0 OID 0)
-- Dependencies: 221
-- Name: products_productid_seq; Type: SEQUENCE SET; Schema: interview; Owner: interview
--

SELECT pg_catalog.setval('interview.products_productid_seq', 80, false);


--
-- TOC entry 3569 (class 0 OID 0)
-- Dependencies: 235
-- Name: subscription_subscription_id_seq; Type: SEQUENCE SET; Schema: interview; Owner: interview
--

SELECT pg_catalog.setval('interview.subscription_subscription_id_seq', 1, false);


--
-- TOC entry 3570 (class 0 OID 0)
-- Dependencies: 218
-- Name: suppliers_supplier_id_seq; Type: SEQUENCE SET; Schema: interview; Owner: interview
--

SELECT pg_catalog.setval('interview.suppliers_supplier_id_seq', 10, false);


--
-- TOC entry 3326 (class 2606 OID 25327)
-- Name: address address_pkey; Type: CONSTRAINT; Schema: interview; Owner: interview
--

ALTER TABLE ONLY interview.address
    ADD CONSTRAINT address_pkey PRIMARY KEY (address_id);


--
-- TOC entry 3310 (class 2606 OID 25285)
-- Name: cart_item cart_item_pkey; Type: CONSTRAINT; Schema: interview; Owner: interview
--

ALTER TABLE ONLY interview.cart_item
    ADD CONSTRAINT cart_item_pkey PRIMARY KEY (cart_item_id);


--
-- TOC entry 3318 (class 2606 OID 25306)
-- Name: cart cart_pkey; Type: CONSTRAINT; Schema: interview; Owner: interview
--

ALTER TABLE ONLY interview.cart
    ADD CONSTRAINT cart_pkey PRIMARY KEY (cart_id);


--
-- TOC entry 3337 (class 2606 OID 25370)
-- Name: categories categories_pkey; Type: CONSTRAINT; Schema: interview; Owner: interview
--

ALTER TABLE ONLY interview.categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (category_id);


--
-- TOC entry 3308 (class 2606 OID 25264)
-- Name: doctrine_migration_versions doctrine_migration_versions_pkey; Type: CONSTRAINT; Schema: interview; Owner: interview
--

ALTER TABLE ONLY interview.doctrine_migration_versions
    ADD CONSTRAINT doctrine_migration_versions_pkey PRIMARY KEY (version);


--
-- TOC entry 3354 (class 2606 OID 25419)
-- Name: oauth2_access_token oauth2_access_token_pkey; Type: CONSTRAINT; Schema: interview; Owner: interview
--

ALTER TABLE ONLY interview.oauth2_access_token
    ADD CONSTRAINT oauth2_access_token_pkey PRIMARY KEY (identifier);


--
-- TOC entry 3346 (class 2606 OID 25394)
-- Name: oauth2_authorization_code oauth2_authorization_code_pkey; Type: CONSTRAINT; Schema: interview; Owner: interview
--

ALTER TABLE ONLY interview.oauth2_authorization_code
    ADD CONSTRAINT oauth2_authorization_code_pkey PRIMARY KEY (identifier);


--
-- TOC entry 3351 (class 2606 OID 25411)
-- Name: oauth2_client oauth2_client_pkey; Type: CONSTRAINT; Schema: interview; Owner: interview
--

ALTER TABLE ONLY interview.oauth2_client
    ADD CONSTRAINT oauth2_client_pkey PRIMARY KEY (identifier);


--
-- TOC entry 3339 (class 2606 OID 25377)
-- Name: oauth2_client_profile oauth2_client_profile_pkey; Type: CONSTRAINT; Schema: interview; Owner: interview
--

ALTER TABLE ONLY interview.oauth2_client_profile
    ADD CONSTRAINT oauth2_client_profile_pkey PRIMARY KEY (id);


--
-- TOC entry 3349 (class 2606 OID 25401)
-- Name: oauth2_refresh_token oauth2_refresh_token_pkey; Type: CONSTRAINT; Schema: interview; Owner: interview
--

ALTER TABLE ONLY interview.oauth2_refresh_token
    ADD CONSTRAINT oauth2_refresh_token_pkey PRIMARY KEY (identifier);


--
-- TOC entry 3332 (class 2606 OID 25348)
-- Name: oauth2_user_consent oauth2_user_consent_pkey; Type: CONSTRAINT; Schema: interview; Owner: interview
--

ALTER TABLE ONLY interview.oauth2_user_consent
    ADD CONSTRAINT oauth2_user_consent_pkey PRIMARY KEY (id);


--
-- TOC entry 3343 (class 2606 OID 25385)
-- Name: order_item order_item_pkey; Type: CONSTRAINT; Schema: interview; Owner: interview
--

ALTER TABLE ONLY interview.order_item
    ADD CONSTRAINT order_item_pkey PRIMARY KEY (order_item_id);


--
-- TOC entry 3314 (class 2606 OID 25294)
-- Name: orders orders_pkey; Type: CONSTRAINT; Schema: interview; Owner: interview
--

ALTER TABLE ONLY interview.orders
    ADD CONSTRAINT orders_pkey PRIMARY KEY (order_id);


--
-- TOC entry 3321 (class 2606 OID 25313)
-- Name: payment payment_pkey; Type: CONSTRAINT; Schema: interview; Owner: interview
--

ALTER TABLE ONLY interview.payment
    ADD CONSTRAINT payment_pkey PRIMARY KEY (payment_id);


--
-- TOC entry 3334 (class 2606 OID 25361)
-- Name: plan plan_pkey; Type: CONSTRAINT; Schema: interview; Owner: interview
--

ALTER TABLE ONLY interview.plan
    ADD CONSTRAINT plan_pkey PRIMARY KEY (plan_id);


--
-- TOC entry 3324 (class 2606 OID 25320)
-- Name: products products_pkey; Type: CONSTRAINT; Schema: interview; Owner: interview
--

ALTER TABLE ONLY interview.products
    ADD CONSTRAINT products_pkey PRIMARY KEY (product_id);


--
-- TOC entry 3329 (class 2606 OID 25338)
-- Name: subscription subscription_pkey; Type: CONSTRAINT; Schema: interview; Owner: interview
--

ALTER TABLE ONLY interview.subscription
    ADD CONSTRAINT subscription_pkey PRIMARY KEY (subscription_id);


--
-- TOC entry 3316 (class 2606 OID 25299)
-- Name: suppliers suppliers_pkey; Type: CONSTRAINT; Schema: interview; Owner: interview
--

ALTER TABLE ONLY interview.suppliers
    ADD CONSTRAINT suppliers_pkey PRIMARY KEY (supplier_id);


--
-- TOC entry 3341 (class 1259 OID 25386)
-- Name: idx_1a78c8d78d9f6d38; Type: INDEX; Schema: interview; Owner: interview
--

CREATE INDEX idx_1a78c8d78d9f6d38 ON interview.order_item USING btree (order_id);


--
-- TOC entry 3322 (class 1259 OID 25321)
-- Name: idx_3daa4e2f9b8ce200; Type: INDEX; Schema: interview; Owner: interview
--

CREATE INDEX idx_3daa4e2f9b8ce200 ON interview.products USING btree (subscription_plan_id);


--
-- TOC entry 3352 (class 1259 OID 25420)
-- Name: idx_454d9673c7440455; Type: INDEX; Schema: interview; Owner: interview
--

CREATE INDEX idx_454d9673c7440455 ON interview.oauth2_access_token USING btree (client);


--
-- TOC entry 3347 (class 1259 OID 25402)
-- Name: idx_4dd90732b6a2dd68; Type: INDEX; Schema: interview; Owner: interview
--

CREATE INDEX idx_4dd90732b6a2dd68 ON interview.oauth2_refresh_token USING btree (access_token);


--
-- TOC entry 3344 (class 1259 OID 25395)
-- Name: idx_509fef5fc7440455; Type: INDEX; Schema: interview; Owner: interview
--

CREATE INDEX idx_509fef5fc7440455 ON interview.oauth2_authorization_code USING btree (client);


--
-- TOC entry 3327 (class 1259 OID 25339)
-- Name: idx_a4ca83a79b8ce200; Type: INDEX; Schema: interview; Owner: interview
--

CREATE INDEX idx_a4ca83a79b8ce200 ON interview.subscription USING btree (subscription_plan_id);


--
-- TOC entry 3319 (class 1259 OID 25314)
-- Name: idx_c3d308908d9f6d38; Type: INDEX; Schema: interview; Owner: interview
--

CREATE INDEX idx_c3d308908d9f6d38 ON interview.payment USING btree (order_id);


--
-- TOC entry 3330 (class 1259 OID 25349)
-- Name: idx_c8f05d0119eb6921; Type: INDEX; Schema: interview; Owner: interview
--

CREATE INDEX idx_c8f05d0119eb6921 ON interview.oauth2_user_consent USING btree (client_id);


--
-- TOC entry 3311 (class 1259 OID 25286)
-- Name: idx_d01fb0801ad5cdbf; Type: INDEX; Schema: interview; Owner: interview
--

CREATE INDEX idx_d01fb0801ad5cdbf ON interview.cart_item USING btree (cart_id);


--
-- TOC entry 3312 (class 1259 OID 25287)
-- Name: idx_d01fb08041471bab; Type: INDEX; Schema: interview; Owner: interview
--

CREATE INDEX idx_d01fb08041471bab ON interview.cart_item USING btree (reference_entity_id);


--
-- TOC entry 3335 (class 1259 OID 25362)
-- Name: u_plan_idx; Type: INDEX; Schema: interview; Owner: interview
--

CREATE INDEX u_plan_idx ON interview.plan USING btree (plan_name);


--
-- TOC entry 3340 (class 1259 OID 25378)
-- Name: uniq_9b524e1f19eb6921; Type: INDEX; Schema: interview; Owner: interview
--

CREATE UNIQUE INDEX uniq_9b524e1f19eb6921 ON interview.oauth2_client_profile USING btree (client_id);


--
-- TOC entry 3361 (class 2606 OID 25451)
-- Name: order_item fk_1a78c8d78d9f6d38; Type: FK CONSTRAINT; Schema: interview; Owner: interview
--

ALTER TABLE ONLY interview.order_item
    ADD CONSTRAINT fk_1a78c8d78d9f6d38 FOREIGN KEY (order_id) REFERENCES interview.orders(order_id);


--
-- TOC entry 3357 (class 2606 OID 25431)
-- Name: products fk_3daa4e2f9b8ce200; Type: FK CONSTRAINT; Schema: interview; Owner: interview
--

ALTER TABLE ONLY interview.products
    ADD CONSTRAINT fk_3daa4e2f9b8ce200 FOREIGN KEY (subscription_plan_id) REFERENCES interview.plan(plan_id);


--
-- TOC entry 3364 (class 2606 OID 25466)
-- Name: oauth2_access_token fk_454d9673c7440455; Type: FK CONSTRAINT; Schema: interview; Owner: interview
--

ALTER TABLE ONLY interview.oauth2_access_token
    ADD CONSTRAINT fk_454d9673c7440455 FOREIGN KEY (client) REFERENCES interview.oauth2_client(identifier) ON DELETE CASCADE;


--
-- TOC entry 3363 (class 2606 OID 25461)
-- Name: oauth2_refresh_token fk_4dd90732b6a2dd68; Type: FK CONSTRAINT; Schema: interview; Owner: interview
--

ALTER TABLE ONLY interview.oauth2_refresh_token
    ADD CONSTRAINT fk_4dd90732b6a2dd68 FOREIGN KEY (access_token) REFERENCES interview.oauth2_access_token(identifier) ON DELETE SET NULL;


--
-- TOC entry 3362 (class 2606 OID 25456)
-- Name: oauth2_authorization_code fk_509fef5fc7440455; Type: FK CONSTRAINT; Schema: interview; Owner: interview
--

ALTER TABLE ONLY interview.oauth2_authorization_code
    ADD CONSTRAINT fk_509fef5fc7440455 FOREIGN KEY (client) REFERENCES interview.oauth2_client(identifier) ON DELETE CASCADE;


--
-- TOC entry 3360 (class 2606 OID 25446)
-- Name: oauth2_client_profile fk_9b524e1f19eb6921; Type: FK CONSTRAINT; Schema: interview; Owner: interview
--

ALTER TABLE ONLY interview.oauth2_client_profile
    ADD CONSTRAINT fk_9b524e1f19eb6921 FOREIGN KEY (client_id) REFERENCES interview.oauth2_client(identifier);


--
-- TOC entry 3358 (class 2606 OID 25436)
-- Name: subscription fk_a4ca83a79b8ce200; Type: FK CONSTRAINT; Schema: interview; Owner: interview
--

ALTER TABLE ONLY interview.subscription
    ADD CONSTRAINT fk_a4ca83a79b8ce200 FOREIGN KEY (subscription_plan_id) REFERENCES interview.plan(plan_id);


--
-- TOC entry 3356 (class 2606 OID 25426)
-- Name: payment fk_c3d308908d9f6d38; Type: FK CONSTRAINT; Schema: interview; Owner: interview
--

ALTER TABLE ONLY interview.payment
    ADD CONSTRAINT fk_c3d308908d9f6d38 FOREIGN KEY (order_id) REFERENCES interview.orders(order_id);


--
-- TOC entry 3359 (class 2606 OID 25441)
-- Name: oauth2_user_consent fk_c8f05d0119eb6921; Type: FK CONSTRAINT; Schema: interview; Owner: interview
--

ALTER TABLE ONLY interview.oauth2_user_consent
    ADD CONSTRAINT fk_c8f05d0119eb6921 FOREIGN KEY (client_id) REFERENCES interview.oauth2_client(identifier);


--
-- TOC entry 3355 (class 2606 OID 25421)
-- Name: cart_item fk_d01fb0801ad5cdbf; Type: FK CONSTRAINT; Schema: interview; Owner: interview
--

ALTER TABLE ONLY interview.cart_item
    ADD CONSTRAINT fk_d01fb0801ad5cdbf FOREIGN KEY (cart_id) REFERENCES interview.cart(cart_id);


-- Completed on 2024-03-04 17:37:34 CET

--
-- PostgreSQL database dump complete
--

