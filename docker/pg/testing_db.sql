--
-- PostgreSQL database dump
--

-- Dumped from database version 15.4
-- Dumped by pg_dump version 15.4 (Ubuntu 15.4-1.pgdg22.04+1)

-- Started on 2023-10-20 13:53:14 CEST

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 4 (class 2615 OID 2200)
-- Name: testing; Type: SCHEMA; Schema: -; Owner: pg_database_owner
--

CREATE SCHEMA testing;


ALTER SCHEMA testing OWNER TO pg_database_owner;

--
-- TOC entry 3620 (class 0 OID 0)
-- Dependencies: 4
-- Name: SCHEMA testing; Type: COMMENT; Schema: -; Owner: pg_database_owner
--

COMMENT ON SCHEMA testing IS 'standard testing schema';


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 215 (class 1259 OID 16385)
-- Name: address; Type: TABLE; Schema: testing; Owner: interview
--

CREATE TABLE testing.address
(
    address_id     integer               NOT NULL,
    first_name     character varying(40) NOT NULL,
    last_name      character varying(40) NOT NULL,
    address_line_1 character varying(40) NOT NULL,
    address_line_2 character varying(40),
    city           character varying(40) NOT NULL,
    state          character varying(40) NOT NULL,
    postal_code    character varying(40) NOT NULL,
    user_id        integer
);


ALTER TABLE testing.address
    OWNER TO interview;

--
-- TOC entry 216 (class 1259 OID 16388)
-- Name: address_addressid_seq; Type: SEQUENCE; Schema: testing; Owner: interview
--

CREATE SEQUENCE testing.address_addressid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE testing.address_addressid_seq
    OWNER TO interview;

--
-- TOC entry 217 (class 1259 OID 16389)
-- Name: cart; Type: TABLE; Schema: testing; Owner: interview
--

CREATE TABLE testing.cart
(
    cart_id    integer                                                  NOT NULL,
    created_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    status     character varying(25)                                    NOT NULL,
    updated_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    user_id    integer
);


ALTER TABLE testing.cart
    OWNER TO interview;

--
-- TOC entry 3621 (class 0 OID 0)
-- Dependencies: 217
-- Name: COLUMN cart.created_at; Type: COMMENT; Schema: testing; Owner: interview
--

COMMENT ON COLUMN testing.cart.created_at IS '(DC2Type:datetime_immutable)';


--
-- TOC entry 218 (class 1259 OID 16394)
-- Name: cart_cart_id_seq; Type: SEQUENCE; Schema: testing; Owner: interview
--

CREATE SEQUENCE testing.cart_cart_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE testing.cart_cart_id_seq
    OWNER TO interview;

--
-- TOC entry 219 (class 1259 OID 16395)
-- Name: cart_item; Type: TABLE; Schema: testing; Owner: interview
--

CREATE TABLE testing.cart_item
(
    cart_item_id        integer                                               NOT NULL,
    created_at          timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at          timestamp without time zone,
    item_type           character varying(30)                                 NOT NULL,
    cart_id             integer,
    quantity            integer                     DEFAULT 1                 NOT NULL,
    reference_entity_id integer
);


ALTER TABLE testing.cart_item
    OWNER TO interview;

--
-- TOC entry 220 (class 1259 OID 16400)
-- Name: cart_item_cart_item_id_seq; Type: SEQUENCE; Schema: testing; Owner: interview
--

CREATE SEQUENCE testing.cart_item_cart_item_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE testing.cart_item_cart_item_id_seq
    OWNER TO interview;

--
-- TOC entry 221 (class 1259 OID 16401)
-- Name: categories; Type: TABLE; Schema: testing; Owner: interview
--

CREATE TABLE testing.categories
(
    category_id   smallint              NOT NULL,
    category_name character varying(15) NOT NULL,
    description   text
);


ALTER TABLE testing.categories
    OWNER TO interview;

--
-- TOC entry 222 (class 1259 OID 16406)
-- Name: categories_categoryid_seq; Type: SEQUENCE; Schema: testing; Owner: interview
--

CREATE SEQUENCE testing.categories_categoryid_seq
    START WITH 10
    INCREMENT BY 1
    MINVALUE 10
    NO MAXVALUE
    CACHE 1;


ALTER TABLE testing.categories_categoryid_seq
    OWNER TO interview;

--
-- TOC entry 223 (class 1259 OID 16407)
-- Name: intrv_user; Type: TABLE; Schema: testing; Owner: interview
--

CREATE TABLE testing.intrv_user
(
    user_id           integer                                               NOT NULL,
    username          character varying(180)                                NOT NULL,
    pass              character varying(100)                                NOT NULL,
    first_name        character varying(255),
    last_name         character varying(255),
    email             character varying(255)                                NOT NULL,
    phone_no          character varying(11),
    roles             json,
    verification_code character varying(12)                                 NOT NULL,
    is_verified       boolean                     DEFAULT false             NOT NULL,
    is_active         boolean                     DEFAULT false             NOT NULL,
    created_at        timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at        timestamp without time zone,
    last_login        timestamp without time zone,
    deleted_at        timestamp without time zone,
    subscription_id   integer
);


ALTER TABLE testing.intrv_user
    OWNER TO interview;

--
-- TOC entry 224 (class 1259 OID 16415)
-- Name: intrv_user_user_id_seq; Type: SEQUENCE; Schema: testing; Owner: interview
--

CREATE SEQUENCE testing.intrv_user_user_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE testing.intrv_user_user_id_seq
    OWNER TO interview;

--
-- TOC entry 3622 (class 0 OID 0)
-- Dependencies: 224
-- Name: intrv_user_user_id_seq; Type: SEQUENCE OWNED BY; Schema: testing; Owner: interview
--

ALTER SEQUENCE testing.intrv_user_user_id_seq OWNED BY testing.intrv_user.user_id;


--
-- TOC entry 225 (class 1259 OID 16416)
-- Name: oauth2_access_token; Type: TABLE; Schema: testing; Owner: interview
--

CREATE TABLE testing.oauth2_access_token
(
    identifier      character(80)                  NOT NULL,
    client          character varying(32)          NOT NULL,
    expiry          timestamp(0) without time zone NOT NULL,
    user_identifier character varying(128) DEFAULT NULL::character varying,
    scopes          text,
    revoked         boolean                        NOT NULL
);


ALTER TABLE testing.oauth2_access_token
    OWNER TO interview;

--
-- TOC entry 3623 (class 0 OID 0)
-- Dependencies: 225
-- Name: COLUMN oauth2_access_token.expiry; Type: COMMENT; Schema: testing; Owner: interview
--

COMMENT ON COLUMN testing.oauth2_access_token.expiry IS '(DC2Type:datetime_immutable)';


--
-- TOC entry 3624 (class 0 OID 0)
-- Dependencies: 225
-- Name: COLUMN oauth2_access_token.scopes; Type: COMMENT; Schema: testing; Owner: interview
--

COMMENT ON COLUMN testing.oauth2_access_token.scopes IS '(DC2Type:oauth2_scope)';


--
-- TOC entry 226 (class 1259 OID 16422)
-- Name: oauth2_authorization_code; Type: TABLE; Schema: testing; Owner: interview
--

CREATE TABLE testing.oauth2_authorization_code
(
    identifier      character(80)                  NOT NULL,
    client          character varying(32)          NOT NULL,
    expiry          timestamp(0) without time zone NOT NULL,
    user_identifier character varying(128) DEFAULT NULL::character varying,
    scopes          text,
    revoked         boolean                        NOT NULL
);


ALTER TABLE testing.oauth2_authorization_code
    OWNER TO interview;

--
-- TOC entry 3625 (class 0 OID 0)
-- Dependencies: 226
-- Name: COLUMN oauth2_authorization_code.expiry; Type: COMMENT; Schema: testing; Owner: interview
--

COMMENT ON COLUMN testing.oauth2_authorization_code.expiry IS '(DC2Type:datetime_immutable)';


--
-- TOC entry 3626 (class 0 OID 0)
-- Dependencies: 226
-- Name: COLUMN oauth2_authorization_code.scopes; Type: COMMENT; Schema: testing; Owner: interview
--

COMMENT ON COLUMN testing.oauth2_authorization_code.scopes IS '(DC2Type:oauth2_scope)';


--
-- TOC entry 227 (class 1259 OID 16428)
-- Name: oauth2_client; Type: TABLE; Schema: testing; Owner: interview
--

CREATE TABLE testing.oauth2_client
(
    identifier            character varying(32)                NOT NULL,
    name                  character varying(128)               NOT NULL,
    secret                character varying(128) DEFAULT NULL::character varying,
    redirect_uris         text,
    grants                text,
    scopes                text,
    active                boolean                              NOT NULL,
    allow_plain_text_pkce boolean                DEFAULT false NOT NULL
);


ALTER TABLE testing.oauth2_client
    OWNER TO interview;

--
-- TOC entry 3627 (class 0 OID 0)
-- Dependencies: 227
-- Name: COLUMN oauth2_client.redirect_uris; Type: COMMENT; Schema: testing; Owner: interview
--

COMMENT ON COLUMN testing.oauth2_client.redirect_uris IS '(DC2Type:oauth2_redirect_uri)';


--
-- TOC entry 3628 (class 0 OID 0)
-- Dependencies: 227
-- Name: COLUMN oauth2_client.grants; Type: COMMENT; Schema: testing; Owner: interview
--

COMMENT ON COLUMN testing.oauth2_client.grants IS '(DC2Type:oauth2_grant)';


--
-- TOC entry 3629 (class 0 OID 0)
-- Dependencies: 227
-- Name: COLUMN oauth2_client.scopes; Type: COMMENT; Schema: testing; Owner: interview
--

COMMENT ON COLUMN testing.oauth2_client.scopes IS '(DC2Type:oauth2_scope)';


--
-- TOC entry 228 (class 1259 OID 16435)
-- Name: oauth2_client_profile; Type: TABLE; Schema: testing; Owner: interview
--

CREATE TABLE testing.oauth2_client_profile
(
    id          integer                NOT NULL,
    client_id   character varying(32)  NOT NULL,
    name        character varying(255) NOT NULL,
    description text
);


ALTER TABLE testing.oauth2_client_profile
    OWNER TO interview;

--
-- TOC entry 229 (class 1259 OID 16440)
-- Name: oauth2_client_profile_id_seq; Type: SEQUENCE; Schema: testing; Owner: interview
--

CREATE SEQUENCE testing.oauth2_client_profile_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE testing.oauth2_client_profile_id_seq
    OWNER TO interview;

--
-- TOC entry 230 (class 1259 OID 16441)
-- Name: oauth2_refresh_token; Type: TABLE; Schema: testing; Owner: interview
--

CREATE TABLE testing.oauth2_refresh_token
(
    identifier   character(80)                  NOT NULL,
    access_token character(80) DEFAULT NULL::bpchar,
    expiry       timestamp(0) without time zone NOT NULL,
    revoked      boolean                        NOT NULL
);


ALTER TABLE testing.oauth2_refresh_token
    OWNER TO interview;

--
-- TOC entry 3630 (class 0 OID 0)
-- Dependencies: 230
-- Name: COLUMN oauth2_refresh_token.expiry; Type: COMMENT; Schema: testing; Owner: interview
--

COMMENT ON COLUMN testing.oauth2_refresh_token.expiry IS '(DC2Type:datetime_immutable)';


--
-- TOC entry 231 (class 1259 OID 16445)
-- Name: oauth2_user_consent; Type: TABLE; Schema: testing; Owner: interview
--

CREATE TABLE testing.oauth2_user_consent
(
    id         integer                        NOT NULL,
    client_id  character varying(32)          NOT NULL,
    user_id    integer                        NOT NULL,
    created    timestamp(0) without time zone NOT NULL,
    expires    timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    scopes     text,
    ip_address character varying(255)         DEFAULT NULL::character varying
);


ALTER TABLE testing.oauth2_user_consent
    OWNER TO interview;

--
-- TOC entry 3631 (class 0 OID 0)
-- Dependencies: 231
-- Name: COLUMN oauth2_user_consent.created; Type: COMMENT; Schema: testing; Owner: interview
--

COMMENT ON COLUMN testing.oauth2_user_consent.created IS '(DC2Type:datetime_immutable)';


--
-- TOC entry 3632 (class 0 OID 0)
-- Dependencies: 231
-- Name: COLUMN oauth2_user_consent.expires; Type: COMMENT; Schema: testing; Owner: interview
--

COMMENT ON COLUMN testing.oauth2_user_consent.expires IS '(DC2Type:datetime_immutable)';


--
-- TOC entry 3633 (class 0 OID 0)
-- Dependencies: 231
-- Name: COLUMN oauth2_user_consent.scopes; Type: COMMENT; Schema: testing; Owner: interview
--

COMMENT ON COLUMN testing.oauth2_user_consent.scopes IS '(DC2Type:simple_array)';


--
-- TOC entry 232 (class 1259 OID 16452)
-- Name: oauth2_user_consent_id_seq; Type: SEQUENCE; Schema: testing; Owner: interview
--

CREATE SEQUENCE testing.oauth2_user_consent_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE testing.oauth2_user_consent_id_seq
    OWNER TO interview;

--
-- TOC entry 233 (class 1259 OID 16453)
-- Name: order_item; Type: TABLE; Schema: testing; Owner: interview
--

CREATE TABLE testing.order_item
(
    order_id         integer,
    cart_item_entity json                  NOT NULL,
    order_item_id    integer               NOT NULL,
    cart_item_type   character varying(25) NOT NULL
);


ALTER TABLE testing.order_item
    OWNER TO interview;

--
-- TOC entry 234 (class 1259 OID 16458)
-- Name: order_item_order_item_id_seq; Type: SEQUENCE; Schema: testing; Owner: interview
--

CREATE SEQUENCE testing.order_item_order_item_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE testing.order_item_order_item_id_seq
    OWNER TO interview;

--
-- TOC entry 235 (class 1259 OID 16459)
-- Name: order_orderid_seq; Type: SEQUENCE; Schema: testing; Owner: interview
--

CREATE SEQUENCE testing.order_orderid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE testing.order_orderid_seq
    OWNER TO interview;

--
-- TOC entry 236 (class 1259 OID 16460)
-- Name: order_pending; Type: TABLE; Schema: testing; Owner: interview
--

CREATE TABLE testing.order_pending
(
    order_id integer NOT NULL,
    user_id  integer NOT NULL,
    cart_id  integer NOT NULL
);


ALTER TABLE testing.order_pending
    OWNER TO interview;

--
-- TOC entry 237 (class 1259 OID 16463)
-- Name: orders; Type: TABLE; Schema: testing; Owner: interview
--

CREATE TABLE testing.orders
(
    order_id   integer                                                  NOT NULL,
    status     character varying(25)                                    NOT NULL,
    created_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    user_id    integer,
    amount     integer                                                  NOT NULL,
    address_id integer
);


ALTER TABLE testing.orders
    OWNER TO interview;

--
-- TOC entry 238 (class 1259 OID 16468)
-- Name: payment; Type: TABLE; Schema: testing; Owner: interview
--

CREATE TABLE testing.payment
(
    user_id          integer,
    payment_id       integer                                                  NOT NULL,
    operation_number character varying(40)                                    NOT NULL,
    operation_type   character varying(40)                                    NOT NULL,
    amount           integer                                                  NOT NULL,
    payment_date     timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    created_at       timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    order_id         integer,
    status           character varying(25)                                    NOT NULL
);


ALTER TABLE testing.payment
    OWNER TO interview;

--
-- TOC entry 239 (class 1259 OID 16473)
-- Name: payment_paymentid_seq; Type: SEQUENCE; Schema: testing; Owner: interview
--

CREATE SEQUENCE testing.payment_paymentid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE testing.payment_paymentid_seq
    OWNER TO interview;

--
-- TOC entry 240 (class 1259 OID 16474)
-- Name: plan; Type: TABLE; Schema: testing; Owner: interview
--

CREATE TABLE testing.plan
(
    plan_id     integer                                               NOT NULL,
    plan_name   character varying(255)                                NOT NULL,
    description text                                                  NOT NULL,
    is_active   boolean                     DEFAULT false             NOT NULL,
    created_at  timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at  timestamp without time zone,
    deleted_at  timestamp without time zone,
    unit_price  smallint                                              NOT NULL,
    is_visible  boolean                     DEFAULT false             NOT NULL,
    tier        smallint                                              NOT NULL
);


ALTER TABLE testing.plan
    OWNER TO interview;

--
-- TOC entry 241 (class 1259 OID 16482)
-- Name: plan_plan_id_seq; Type: SEQUENCE; Schema: testing; Owner: interview
--

CREATE SEQUENCE testing.plan_plan_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE testing.plan_plan_id_seq
    OWNER TO interview;

--
-- TOC entry 3634 (class 0 OID 0)
-- Dependencies: 241
-- Name: plan_plan_id_seq; Type: SEQUENCE OWNED BY; Schema: testing; Owner: interview
--

ALTER SEQUENCE testing.plan_plan_id_seq OWNED BY testing.plan.plan_id;


--
-- TOC entry 242 (class 1259 OID 16483)
-- Name: product_cart_item; Type: TABLE; Schema: testing; Owner: interview
--

CREATE TABLE testing.product_cart_item
(
    cart_item_id          integer NOT NULL,
    destination_entity_id integer
);


ALTER TABLE testing.product_cart_item
    OWNER TO interview;

--
-- TOC entry 243 (class 1259 OID 16486)
-- Name: products; Type: TABLE; Schema: testing; Owner: interview
--

CREATE TABLE testing.products
(
    product_id               integer               NOT NULL,
    product_name             character varying(40) NOT NULL,
    supplier_id              smallint,
    category_id              smallint,
    quantity_per_unit        character varying(20),
    unit_price               smallint              NOT NULL,
    units_in_stock           smallint,
    units_on_order           smallint,
    required_subscription_id integer
);


ALTER TABLE testing.products
    OWNER TO interview;

--
-- TOC entry 244 (class 1259 OID 16489)
-- Name: products_productid_seq; Type: SEQUENCE; Schema: testing; Owner: interview
--

CREATE SEQUENCE testing.products_productid_seq
    START WITH 80
    INCREMENT BY 1
    MINVALUE 80
    NO MAXVALUE
    CACHE 1;


ALTER TABLE testing.products_productid_seq
    OWNER TO interview;

--
-- TOC entry 245 (class 1259 OID 16490)
-- Name: subscription; Type: TABLE; Schema: testing; Owner: interview
--

CREATE TABLE testing.subscription
(
    subscription_id      integer                                                  NOT NULL,
    is_active            boolean                        DEFAULT false             NOT NULL,
    created_at           timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    starts_at            timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    ends_at              timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    subscription_plan_id integer,
    tier                 smallint
);


ALTER TABLE testing.subscription
    OWNER TO interview;

--
-- TOC entry 246 (class 1259 OID 16497)
-- Name: subscription_cart_item; Type: TABLE; Schema: testing; Owner: interview
--

CREATE TABLE testing.subscription_cart_item
(
    cart_item_id    integer NOT NULL,
    subscription_id integer
);


ALTER TABLE testing.subscription_cart_item
    OWNER TO interview;

--
-- TOC entry 247 (class 1259 OID 16500)
-- Name: subscription_plan_cart_item; Type: TABLE; Schema: testing; Owner: interview
--

CREATE TABLE testing.subscription_plan_cart_item
(
    cart_item_id          integer NOT NULL,
    destination_entity_id integer
);


ALTER TABLE testing.subscription_plan_cart_item
    OWNER TO interview;

--
-- TOC entry 248 (class 1259 OID 16503)
-- Name: subscription_subscription_id_seq; Type: SEQUENCE; Schema: testing; Owner: interview
--

CREATE SEQUENCE testing.subscription_subscription_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE testing.subscription_subscription_id_seq
    OWNER TO interview;

--
-- TOC entry 249 (class 1259 OID 16504)
-- Name: suppliers; Type: TABLE; Schema: testing; Owner: interview
--

CREATE TABLE testing.suppliers
(
    supplier_id  smallint              NOT NULL,
    company_name character varying(40) NOT NULL
);


ALTER TABLE testing.suppliers
    OWNER TO interview;

--
-- TOC entry 250 (class 1259 OID 16507)
-- Name: suppliers_supplier_id_seq; Type: SEQUENCE; Schema: testing; Owner: interview
--

CREATE SEQUENCE testing.suppliers_supplier_id_seq
    START WITH 10
    INCREMENT BY 1
    MINVALUE 10
    NO MAXVALUE
    CACHE 1;


ALTER TABLE testing.suppliers_supplier_id_seq
    OWNER TO interview;

--
-- TOC entry 251 (class 1259 OID 16508)
-- Name: user_subscription; Type: TABLE; Schema: testing; Owner: interview
--

CREATE TABLE testing.user_subscription
(
    subscription_id integer NOT NULL,
    user_id         integer NOT NULL,
    plan_id         integer NOT NULL,
    purchased_at    timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    started_at      timestamp without time zone,
    ends_at         timestamp without time zone
);


ALTER TABLE testing.user_subscription
    OWNER TO interview;

--
-- TOC entry 252 (class 1259 OID 16512)
-- Name: user_subscription_subscription_id_seq; Type: SEQUENCE; Schema: testing; Owner: interview
--

CREATE SEQUENCE testing.user_subscription_subscription_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE testing.user_subscription_subscription_id_seq
    OWNER TO interview;

--
-- TOC entry 3635 (class 0 OID 0)
-- Dependencies: 252
-- Name: user_subscription_subscription_id_seq; Type: SEQUENCE OWNED BY; Schema: testing; Owner: interview
--

ALTER SEQUENCE testing.user_subscription_subscription_id_seq OWNED BY testing.user_subscription.subscription_id;


--
-- TOC entry 253 (class 1259 OID 16513)
-- Name: voucher; Type: TABLE; Schema: testing; Owner: interview
--

CREATE TABLE testing.voucher
(
    voucher_id integer                                               NOT NULL,
    code       character varying(25)                                 NOT NULL,
    valid_for  character varying(12),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE testing.voucher
    OWNER TO interview;

--
-- TOC entry 254 (class 1259 OID 16517)
-- Name: voucher_voucher_id_seq; Type: SEQUENCE; Schema: testing; Owner: interview
--

CREATE SEQUENCE testing.voucher_voucher_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE testing.voucher_voucher_id_seq
    OWNER TO interview;

--
-- TOC entry 3636 (class 0 OID 0)
-- Dependencies: 254
-- Name: voucher_voucher_id_seq; Type: SEQUENCE OWNED BY; Schema: testing; Owner: interview
--

ALTER SEQUENCE testing.voucher_voucher_id_seq OWNED BY testing.voucher.voucher_id;


--
-- TOC entry 3329 (class 2604 OID 16518)
-- Name: user_subscription subscription_id; Type: DEFAULT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.user_subscription
    ALTER COLUMN subscription_id SET DEFAULT nextval('testing.user_subscription_subscription_id_seq'::regclass);


--
-- TOC entry 3331 (class 2604 OID 16519)
-- Name: voucher voucher_id; Type: DEFAULT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.voucher
    ALTER COLUMN voucher_id SET DEFAULT nextval('testing.voucher_voucher_id_seq'::regclass);


--
-- TOC entry 3575 (class 0 OID 16385)
-- Dependencies: 215
-- Data for Name: address; Type: TABLE DATA; Schema: testing; Owner: interview
--

INSERT INTO testing.address
VALUES (1, 'Raf', 'Sal', 'Tw', 'asd', 'kielce', 'swietokrzyskie', '20-085', NULL);
INSERT INTO testing.address
VALUES (14, 'Raf', 'Sal', 'Tw', NULL, 'kielce', 'swietokrzyskie', '20-085', NULL);
INSERT INTO testing.address
VALUES (15, 'Raf', 'Sal', 'Tw', NULL, 'kielce', 'swietokrzyskie', '20-085', NULL);
INSERT INTO testing.address
VALUES (16, 'Raf', 'Sal', 'Tw', NULL, 'kielce', 'swietokrzyskie', '20-085', 1);
INSERT INTO testing.address
VALUES (18, 'r', 's', 'tttwww', NULL, 'k', 's', 'p', 1);
INSERT INTO testing.address
VALUES (19, 'rrrr', 'ssss', 'ttttttttttttt', NULL, 'kkkkkkk', 'ssssss', 'p', 1);
INSERT INTO testing.address
VALUES (20, 'rrrr', 'ssss', 'ttttttttttttt', NULL, 'kkkkkkk', 'ssssss', 'p', 1);
INSERT INTO testing.address
VALUES (21, 'rrrr', 'ssss', 'ttttttttttttt', NULL, 'kkkkkkk', 'ssssss', 'p', 1);
INSERT INTO testing.address
VALUES (22, 'rrrr', 'ssss', 'ttttttttttttt', NULL, 'kkkkkkk', 'ssssss', 'p', 1);
INSERT INTO testing.address
VALUES (23, 'rrrr', 'ssss', 'ttttttttttttt', NULL, 'kkkkkkk', 'ssssss', 'p', 1);
INSERT INTO testing.address
VALUES (24, 'rrrr', 'ssss', 'ttttttttttttt', NULL, 'kkkkkkk', 'ssssss', 'p', 1);
INSERT INTO testing.address
VALUES (25, 'rrrr', 'ssss', 'ttttttttttttt', NULL, 'kkkkkkk', 'ssssss', 'p', 1);
INSERT INTO testing.address
VALUES (26, 'rrrr', 'ssss', 'ttttttttttttt', NULL, 'kkkkkkk', 'ssssss', 'p', 1);
INSERT INTO testing.address
VALUES (27, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 1);
INSERT INTO testing.address
VALUES (28, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 1);
INSERT INTO testing.address
VALUES (29, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 1);
INSERT INTO testing.address
VALUES (30, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 1);
INSERT INTO testing.address
VALUES (31, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 1);
INSERT INTO testing.address
VALUES (32, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 1);
INSERT INTO testing.address
VALUES (33, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 1);
INSERT INTO testing.address
VALUES (34, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 1);


--
-- TOC entry 3577 (class 0 OID 16389)
-- Dependencies: 217
-- Data for Name: cart; Type: TABLE DATA; Schema: testing; Owner: interview
--

INSERT INTO testing.cart
VALUES (14, '2023-08-18 20:20:26', 'confirmed', '2023-08-21 16:55:26', 1);
INSERT INTO testing.cart
VALUES (15, '2023-08-21 16:58:20', 'confirmed', '2023-08-22 16:14:11', 1);
INSERT INTO testing.cart
VALUES (16, '2023-08-22 17:07:43', 'confirmed', '2023-08-22 18:23:59', 1);
INSERT INTO testing.cart
VALUES (17, '2023-08-22 18:41:15', 'confirmed', '2023-08-22 18:42:44', 1);
INSERT INTO testing.cart
VALUES (18, '2023-08-23 11:35:32', 'confirmed', '2023-08-23 15:19:51', 1);
INSERT INTO testing.cart
VALUES (19, '2023-08-23 19:25:48', 'confirmed', '2023-08-23 19:25:54', 1);
INSERT INTO testing.cart
VALUES (20, '2023-08-23 20:21:32', 'confirmed', '2023-08-28 22:05:36', 1);
INSERT INTO testing.cart
VALUES (21, '2023-10-10 15:30:22', 'confirmed', '2023-10-11 13:49:24', 1);
INSERT INTO testing.cart
VALUES (22, '2023-10-11 14:13:58', 'confirmed', '2023-10-11 14:17:35', 1);
INSERT INTO testing.cart
VALUES (23, '2023-10-11 14:23:50', 'created', '2023-10-11 14:23:50', 1);


--
-- TOC entry 3579 (class 0 OID 16395)
-- Dependencies: 219
-- Data for Name: cart_item; Type: TABLE DATA; Schema: testing; Owner: interview
--

INSERT INTO testing.cart_item
VALUES (41, '2023-08-17 19:36:48', NULL, 'productcartitem', NULL, 1, NULL);
INSERT INTO testing.cart_item
VALUES (42, '2023-08-17 19:37:30', NULL, 'productcartitem', NULL, 1, NULL);
INSERT INTO testing.cart_item
VALUES (43, '2023-08-17 19:38:32', NULL, 'productcartitem', NULL, 1, NULL);
INSERT INTO testing.cart_item
VALUES (50, '2023-08-18 20:20:26', NULL, 'subscriptionplancartitem', 14, 1, NULL);
INSERT INTO testing.cart_item
VALUES (51, '2023-08-18 20:20:32', NULL, 'productcartitem', 14, 1, NULL);
INSERT INTO testing.cart_item
VALUES (52, '2023-08-18 20:20:36', NULL, 'productcartitem', 14, 1, NULL);
INSERT INTO testing.cart_item
VALUES (53, '2023-08-18 20:20:41', NULL, 'productcartitem', 14, 5, NULL);
INSERT INTO testing.cart_item
VALUES (57, '2023-08-21 19:59:05', NULL, 'subscriptionplancartitem', 15, 2, NULL);
INSERT INTO testing.cart_item
VALUES (56, '2023-08-21 19:45:38', NULL, 'subscriptionplancartitem', 15, 8, NULL);
INSERT INTO testing.cart_item
VALUES (58, '2023-08-21 19:59:13', NULL, 'subscriptionplancartitem', 15, 6, NULL);
INSERT INTO testing.cart_item
VALUES (90, '2023-08-22 14:13:37', NULL, 'productcartitem', 15, 5, NULL);
INSERT INTO testing.cart_item
VALUES (91, '2023-08-22 17:07:43', NULL, 'subscriptionplancartitem', 16, 1, NULL);
INSERT INTO testing.cart_item
VALUES (92, '2023-08-22 17:07:48', NULL, 'productcartitem', 16, 1, NULL);
INSERT INTO testing.cart_item
VALUES (93, '2023-08-22 18:41:15', NULL, 'subscriptionplancartitem', 17, 1, NULL);
INSERT INTO testing.cart_item
VALUES (94, '2023-08-22 18:41:19', NULL, 'productcartitem', 17, 1, NULL);
INSERT INTO testing.cart_item
VALUES (95, '2023-08-22 18:41:25', NULL, 'productcartitem', 17, 1, NULL);
INSERT INTO testing.cart_item
VALUES (96, '2023-08-22 18:41:32', NULL, 'productcartitem', 17, 1, NULL);
INSERT INTO testing.cart_item
VALUES (102, '2023-08-23 15:03:11', NULL, 'subscriptionplancartitem', 18, 1, NULL);
INSERT INTO testing.cart_item
VALUES (103, '2023-08-23 15:03:24', NULL, 'productcartitem', 18, 1, NULL);
INSERT INTO testing.cart_item
VALUES (104, '2023-08-23 19:25:48', NULL, 'subscriptionplancartitem', 19, 1, NULL);
INSERT INTO testing.cart_item
VALUES (141, '2023-10-11 14:23:50', '2023-10-11 14:23:50', 'product', 23, 1, 11);


--
-- TOC entry 3581 (class 0 OID 16401)
-- Dependencies: 221
-- Data for Name: categories; Type: TABLE DATA; Schema: testing; Owner: interview
--

INSERT INTO testing.categories
VALUES (1, 'Beverages', 'Soft drinks, coffees, teas, beers, and ales');
INSERT INTO testing.categories
VALUES (2, 'Condiments', 'Sweet and savory sauces, relishes, spreads, and seasonings');
INSERT INTO testing.categories
VALUES (3, 'Confections', 'Desserts, candies, and sweet breads');
INSERT INTO testing.categories
VALUES (4, 'Dairy Products', 'Cheeses');
INSERT INTO testing.categories
VALUES (5, 'Grains/Cereals', 'Breads, crackers, pasta, and cereal');
INSERT INTO testing.categories
VALUES (6, 'Meat/Poultry', 'Prepared meats');
INSERT INTO testing.categories
VALUES (7, 'Produce', 'Dried fruit and bean curd');
INSERT INTO testing.categories
VALUES (8, 'Seafood', 'Seaweed and fish');


--
-- TOC entry 3583 (class 0 OID 16407)
-- Dependencies: 223
-- Data for Name: intrv_user; Type: TABLE DATA; Schema: testing; Owner: interview
--

INSERT INTO testing.intrv_user
VALUES (2, 'another_user', '$2a$13$5NmP4C6LG2wUzrkvZ/uVdue7QlZQNP/2FTFHo3/6QKmfWJD7YpAIa', 'another_user_firstname',
        'another_lastname', 'user@user.com', '987654321', '{
    "roles": "ROLE_USER"
  }', '176278', true, true, '2023-05-01 22:14:09', NULL, '2023-05-02 22:14:09', NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (3, 'bad_user', '$2a$13$5NmP4C6LG2wUzrkvZ/uVdue7QlZQNP/2FTFHo3/6QKmfWJD7YpAIa', 'bad, bad,', 'very bad user',
        'trickortreat@strange.com', '123333777', '{
    "roles": "ROLE_USER"
  }', '347816', true, false, '2023-05-01 04:33:20', '2023-05-01 19:11:34', '2023-05-02 15:47:04', '2023-05-02 17:12:48',
        NULL);
INSERT INTO testing.intrv_user
VALUES (5, 'vip', '$2a$13$5NmP4C6LG2wUzrkvZ/uVdue7QlZQNP/2FTFHo3/6QKmfWJD7YpAIa', 'very important', 'user',
        'memberplus@vip.com', '777333444', '{
    "roles": [
      "ROLE_VIP",
      "ROLE_USER"
    ]
  }', '642103', true, true, '2023-05-01 04:33:20', NULL, '2023-05-02 15:47:04', NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (6, 'rafsal', '$2a$13$L4zZVIkX6o1KQLjCpwwxQ.8/cVm9ICx44AxkU.bHqJ11xFO4jvmT6', NULL, NULL, 'rafsal@interview.com',
        NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'OXSmjb', false, false, '2023-05-19 23:30:33', '2023-05-19 23:31:54', '2023-05-19 23:31:54', NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (8, 'XhuytkQZ', '$2a$13$VGdoX2zgpMc0mATOxJzU2uY0GfTbCSj5TWXDWT2T/OTALGvhGsZhy', NULL, NULL,
        'XhuytkQZ@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'ZBLgyz', false, false, '2023-05-19 23:37:02', '2023-05-19 23:37:03', '2023-05-19 23:37:03', NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (9, 'xfKcHzAQ', '$2a$13$sSPqPJMMpi/4UdxpahYNOeZ4hXqZY6rmBis5AnLJtGM23tHHBc28W', NULL, NULL,
        'xfKcHzAQ@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'tEwKvO', false, false, '2023-05-19 23:38:05', '2023-05-19 23:38:05', '2023-05-19 23:38:05', NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (10, 'tTGKsjQN', '$2a$13$Z/XrRCc4ZI08WScWaojNW.e8luA/cmRwDY2pkpTGc22wMLSmS70Zm', NULL, NULL,
        'tTGKsjQN@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'sizXxy', false, false, '2023-05-19 23:38:12', '2023-05-19 23:38:13', '2023-05-19 23:38:13', NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (11, 'YeehRLeX', '$2a$13$yo8ifJmbMacJ0XyPbHhOCuI8a7MewaW.0L9olyMQ2Y1/isdusb1pe', NULL, NULL,
        'YeehRLeX@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'fntbgo', false, false, '2023-05-19 23:39:25', '2023-05-19 23:39:25', '2023-05-19 23:39:25', NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (12, 'oWInjirT', '$2a$13$MJwB2NYkwNJHND/CD2o5Vun/DaBtKAzSyWMJ7EF2EU1QlCdnoAOsC', NULL, NULL,
        'oWInjirT@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'holEiY', false, false, '2023-05-22 11:31:49', '2023-05-22 11:31:49', '2023-05-22 11:31:49', NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (13, 'dcgpNuQZ', '$2a$13$51D3D1fa3la9l9f8YyBtjeHG7k0Lc9Hozv0xMfQSxavDvga/riWmG', NULL, NULL,
        'dcgpNuQZ@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'eRaQlh', false, false, '2023-05-22 11:34:10', '2023-05-22 11:34:10', '2023-05-22 11:34:10', NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (14, 'PytyKNpU', '$2a$13$MUDeyizw36l7MxdFpRP0yOsqMPNHcRZoWLxOTCRARYpxjD7XOr2TO', NULL, NULL,
        'PytyKNpU@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'kpEogF', false, false, '2023-05-22 11:38:51', '2023-05-22 11:38:52', '2023-05-22 11:38:52', NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (15, 'YEJBdARM', '$2a$13$BF43Vnnd3pP8/ncDHIHufOPHfVgwjVQXu81nQkHMHacS5q31QYDwG', NULL, NULL,
        'YEJBdARM@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'sPBKVK', false, false, '2023-05-22 11:40:55', '2023-05-22 11:40:55', '2023-05-22 11:40:55', NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (16, 'cUWejYWv', '$2a$13$uin75bnGkvF0YmM778N7yO6BNDCUjpA.mFpwBVvgEpnd9..v6iGgC', NULL, NULL,
        'cUWejYWv@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'EPlqZK', false, false, '2023-05-22 11:45:21', '2023-05-22 11:45:21', '2023-05-22 11:45:21', NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (17, 'IWBQcesa', '$2a$13$NGvChpYxY/ppDbnhqOdaQ.xOYqoMEtArG8RRd5WPtz4tB6Gb9IA2m', NULL, NULL,
        'IWBQcesa@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'MHxxBT', false, false, '2023-05-22 11:45:57', '2023-05-22 11:45:58', '2023-05-22 11:45:58', NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (18, 'OrLNaDcm', '$2a$13$Pkwqymj/./WbWCG46WwEYeDH8JFWUCDwsZq0JoYobwrhxo8ysO94e', NULL, NULL,
        'OrLNaDcm@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'lucTNW', false, false, '2023-05-22 11:46:47', '2023-05-22 11:46:47', '2023-05-22 11:46:47', NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (19, 'TrNazrGn', '$2a$13$1t27jWwQnCdzlDpsb/940u0KaOF54tr9u/Ts7OcZ38SqGxBNfFWjG', NULL, NULL,
        'TrNazrGn@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'CrJlHV', false, false, '2023-05-22 11:48:51', '2023-05-22 11:48:51', '2023-05-22 11:48:51', NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (20, 'eDsxWQWv', '$2a$13$Bk2CTP2OThAwzNzuH4ftMO4onJlqAXCyPi328obT/C5fzhXDnYGlW', NULL, NULL,
        'eDsxWQWv@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'hkCOHS', false, false, '2023-05-22 11:50:08', '2023-05-22 11:50:08', '2023-05-22 11:50:08', NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (21, 'IAnjTNao', '$2a$13$W6kViSvl4g32C/s/EIYVEOmlNhF06XfqZwV73o.4W8fNH9SacYbC2', NULL, NULL,
        'IAnjTNao@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'mNMTiZ', false, false, '2023-05-22 11:58:15', '2023-05-22 11:58:15', '2023-05-22 11:58:15', NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (22, 'WimEjAZL', '$2a$13$30yKE4vqTAimTNoZ7ZFQA.En7sfkuCh0hK1BxQ/SOeqNSH8OmL.yW', NULL, NULL,
        'WimEjAZL@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'svZHwo', false, false, '2023-05-22 12:02:20', '2023-05-22 12:02:20', '2023-05-22 12:02:20', NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (23, 'DEPEzJxl', '$2a$13$/vCeaqorr18zJDQzCNFKYOahMYKn4g6wp8l9H2Vy2A1gjEezELx5O', NULL, NULL,
        'DEPEzJxl@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'OeHjnb', false, false, '2023-05-22 12:03:11', '2023-05-22 12:03:12', '2023-05-22 12:03:12', NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (24, 'fQwNLgmW', '$2a$13$zkkhlHRPQVq7R3V2DuLfk.4EXZzOt.B0lHggRLthVzaSfsZtFOyOa', NULL, NULL,
        'fQwNLgmW@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'XpcaFx', false, false, '2023-05-22 12:03:49', '2023-05-22 12:03:49', '2023-05-22 12:03:49', NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (25, 'rTBlHytA', '$2a$13$0HV5EIRcGSmvpH3QsMGQHuv0sVsejZihDjjgXawpBbrZThngX2Nla', NULL, NULL,
        'rTBlHytA@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'CYmKmU', false, false, '2023-05-22 12:04:16', '2023-05-22 12:04:17', '2023-05-22 12:04:17', NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (26, 'fkPclayr', '$2a$13$U6p0qVNOH0LCQTKz92sNYOX4hrH1WtLcjuJTKn.mF6IiO.CMK/8YC', NULL, NULL,
        'fkPclayr@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'SHeusU', false, false, '2023-05-22 12:27:09', '2023-05-22 12:27:10', '2023-05-22 12:27:10', NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (27, 'CUPRTqtR', '$2a$13$/dWp1cMTRmOhn7wPaHXRmuj3Z5fjqBGdfD8P6LdCmyDed1XwUS9.G', NULL, NULL,
        'CUPRTqtR@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'YBQfXQ', false, false, '2023-05-22 12:28:55', '2023-05-22 12:28:55', '2023-05-22 12:28:55', NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (28, 'QBaViwvv', '$2a$13$k7sDNK6VLbYf7hTFvvmKIOUyf4ge2JzjguhtMHrXlovCH4s0Q0MCC', NULL, NULL,
        'QBaViwvv@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'OxXdwz', false, false, '2023-05-22 12:29:48', '2023-05-22 12:29:48', '2023-05-22 12:29:48', NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (29, 'dgIIEPea', '$2a$13$wgHwRoISSf6Gnf4OTAeg7eo7KajnWCR7qt4hSPgLOmivGKXFplyrm', NULL, NULL,
        'dgIIEPea@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'iyAHgM', false, false, '2023-05-22 12:30:30', '2023-05-22 12:30:31', '2023-05-22 12:30:31', NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (30, 'rqohkFsD', '$2a$13$.F5TOol316l6O/ACPPdeGuD/qB9j6gihs3xXOUd5.sKfQlPeudOQi', NULL, NULL,
        'rqohkFsD@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'gQvhOk', false, false, '2023-05-22 12:31:47', '2023-05-22 12:31:47', '2023-05-22 12:31:47', NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (31, 'ujqzEqLe', '$2a$13$Gz43pXVg.HPxlyvR9Ju7I.S0wJIBxfaTijtS2LbIyyexWHjLEid7W', NULL, NULL,
        'ujqzEqLe@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'UJKiqg', false, false, '2023-05-22 12:33:18', '2023-05-22 12:33:18', '2023-05-22 12:33:18', NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (32, 'NTyemSFU', '$2a$13$NlkqtBzqQ/hLMLfPP6dhPe37ja1qP9Fpgk810AudSc1Xrr8N/3iRm', NULL, NULL,
        'NTyemSFU@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'GEAXKO', true, false, '2023-05-22 12:34:05', '2023-05-22 12:34:05', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (33, 'GJgAqXbo', '$2a$13$BXIq71glwSEFKJBMLxuKJerXWBIsbGa.CFfGnt/.34RvurWlWDRFe', NULL, NULL,
        'GJgAqXbo@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'WSknwp', true, false, '2023-05-22 12:38:55', '2023-05-22 12:38:55', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (34, 'qBeZvsvN', '$2a$13$Ff9fRvoN7QB37J9MXHynRuNH8f8IPrfzSkZGBI1WqamsBd0h61xJa', NULL, NULL,
        'qBeZvsvN@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'SCxzko', true, false, '2023-05-22 12:40:15', '2023-05-22 12:40:15', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (35, 'esTJCGEu', '$2a$13$9Ll9r4ZgHmSsZ/EVITArzOqcE0hmr82BS3LQQ4.xf8MMY9RXxledm', NULL, NULL,
        'esTJCGEu@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'kuSici', true, false, '2023-05-22 12:41:14', '2023-05-22 12:41:14', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (36, 'wKUuVGhM', '$2a$13$N.Dl2OIDCGeSL94bPVdYfuqdbv.Zg1CbR8cHDokfX/i4wKEAn1oDO', NULL, NULL,
        'wKUuVGhM@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'sbqxhv', true, false, '2023-05-22 12:41:53', '2023-05-22 12:41:53', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (37, 'mOoRfpLg', '$2a$13$FvdalH9OumJb9cROe9xIFevCwty7WbFVep68Age3Fhg8xBV6HXwfu', NULL, NULL,
        'mOoRfpLg@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'rEJjhL', true, false, '2023-05-22 12:42:15', '2023-05-22 12:42:15', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (38, 'xepCwgbN', '$2a$13$yI1pvrOQzK6TbpktdhjTI.iPxc4TXbhnIasaneERlb5/fAdcyo1rG', NULL, NULL,
        'xepCwgbN@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'MuJmhb', true, false, '2023-05-22 12:47:22', '2023-05-22 12:47:22', '2023-05-22 12:47:22', NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (39, 'kfoibJtl', '$2a$13$mvl/KERxriFVsp97OeAL8.Ra3fAjh6UVnrMDKUUUJ2mLcTC6YoY5e', NULL, NULL,
        'kfoibJtl@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'bienSP', true, true, '2023-05-22 12:48:32', '2023-05-22 12:48:33', '2023-05-22 12:48:33', NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (40, 'vdIZlrZb', '$2a$13$Xs9ldX.qxSu2B5m6UiDO/u7/uECOxZWfQJ/faV5lD49llWYSVQXsm', NULL, NULL,
        'vdIZlrZb@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'NyacRv', false, false, '2023-05-22 16:27:26', '2023-05-22 16:27:26', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (41, 'CBrpLIBy', '$2a$13$4aML.dO35OlV6pl84JepuOgnKzTmHpxn93HXFU3b/uSWQUKFQoqSu', NULL, NULL,
        'CBrpLIBy@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'cXHPed', false, false, '2023-05-22 16:27:26', '2023-05-22 16:27:26', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (42, 'LRXJeSMp', '$2a$13$MHB0Etx2kw8OUxXBUno8s.bp1cf/NOpi318K/rtrKC0loA6CI7Jvu', NULL, NULL,
        'LRXJeSMp@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'rnVWdw', false, false, '2023-05-22 16:27:26', '2023-05-22 16:27:26', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (43, 'rWTcANbD', '$2a$13$o0eVoJxgQtpHy5nBFLIIj.1KolCItZqg53y5RhrN/VZBygzlUWqUy', NULL, NULL,
        'rWTcANbD@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'TdQpGM', false, false, '2023-05-22 16:27:27', '2023-05-22 16:27:27', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (44, 'HmBCtOHg', '$2a$13$XxXys9jKh0oXeDjzd5843e.oiYzdQEp7GGuOUPrIa0JnAv.8oJj4m', NULL, NULL,
        'HmBCtOHg@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'NFmrlf', false, false, '2023-05-22 16:27:26', '2023-05-22 16:27:27', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (45, 'qTqRQllr', '$2a$13$aU/6R0g89N1zfg0.vPXFkOcm.wC1LOkUKyCSbvgpmAH57//uKPcnm', NULL, NULL,
        'qTqRQllr@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'SBBNtf', false, false, '2023-05-22 16:27:26', '2023-05-22 16:27:27', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (46, 'bwecyjBu', '$2a$13$FnsGGxwf2pv65FXaZPC4u.sLk0B5/rmu2OPP46ahrrC9S1EDY8F4u', NULL, NULL,
        'bwecyjBu@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'leaRaU', false, false, '2023-05-22 16:27:27', '2023-05-22 16:27:27', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (47, 'KHsBoeqr', '$2a$13$9UgbpjtfZFxgCmU01wEliOF7ru5nDOUSIyrgojeuYV7SDyLAAK/LS', NULL, NULL,
        'KHsBoeqr@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'FyEBUb', false, false, '2023-05-22 16:27:27', '2023-05-22 16:27:27', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (48, 'IqofCsMN', '$2a$13$i8bu4fJzcNW7UQIo8aiNI.B8wjjzeWuRnvd/Y6bM0uCFszKA.CwyS', NULL, NULL,
        'IqofCsMN@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'cEnwya', false, false, '2023-05-22 16:27:27', '2023-05-22 16:27:27', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (49, 'trBrNRKz', '$2a$13$OHprwxZevnzl.kXJhGWxVe.JiUU1wGNYZwsWvSUfZ39klj.MNZRM2', NULL, NULL,
        'trBrNRKz@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'uJCCCz', false, false, '2023-05-22 16:27:27', '2023-05-22 16:27:27', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (50, 'tcOORZsV', '$2a$13$/KCGkpQ3hGpIgjBwd/S0K.A9PwPpScsAbVaB8.Wi8ALNO3mSsrtbC', NULL, NULL,
        'tcOORZsV@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'dbZPwe', false, false, '2023-05-22 16:27:27', '2023-05-22 16:27:27', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (51, 'TYsVSmbj', '$2a$13$R/Yqd9TpfZScPrIllKQofOKWluvN9p96xoKLwgSWqulHJY0.FdEBm', NULL, NULL,
        'TYsVSmbj@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'tyjyBT', false, false, '2023-05-22 16:27:27', '2023-05-22 16:27:27', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (52, 'ySCjtJFC', '$2a$13$8OgEXcU5SZQtt8TERkaXDOVEGnhJ8hwEF/96VIURVACBWuyBS5TgC', NULL, NULL,
        'ySCjtJFC@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'jQXGsE', false, false, '2023-05-22 16:27:27', '2023-05-22 16:27:27', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (53, 'kITcaZuA', '$2a$13$imKAjaWdFw2aI2Y71xjOF.Hx5kCpsLYTo.vfNjAVVBnCVu.rXtGvO', NULL, NULL,
        'kITcaZuA@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'kecJWI', false, false, '2023-05-22 16:27:27', '2023-05-22 16:27:27', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (54, 'vVyGUidi', '$2a$13$/ga/mG1n43bC3sz5UVqykOq.0MXEzmhxt7T45jT5Elw1Dob6cleoi', NULL, NULL,
        'vVyGUidi@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'fYvuop', false, false, '2023-05-22 16:27:27', '2023-05-22 16:27:27', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (55, 'HXVsTOJG', '$2a$13$p0QoZ7msrIJGMAcjvoyMVeL7h2NRAQn9R4rp6zsFB0zwOLbv4ab42', NULL, NULL,
        'HXVsTOJG@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'ldpcmU', false, false, '2023-05-22 16:27:27', '2023-05-22 16:27:27', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (56, 'CRpYdUhb', '$2a$13$gR2YoXJxouI7mCaNfboiA.70Xs4Q0LHK8PX/mIqIkDOkMNywuZwri', NULL, NULL,
        'CRpYdUhb@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'rNpcfB', false, false, '2023-05-22 16:27:27', '2023-05-22 16:27:27', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (57, 'nPTGQJwI', '$2a$13$pwc9sDiG1fSbXNvnQzH7qeUuQllzjLrsNF02cgbZERV2uK0kGbCDW', NULL, NULL,
        'nPTGQJwI@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'fEfCiO', false, false, '2023-05-22 16:27:27', '2023-05-22 16:27:27', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (58, 'aMCeLsxf', '$2a$13$P4Mq0F5ltIh57O9a0osGDu/AEoXg.O23G.AVm/ZidajkJhS1C3HLe', NULL, NULL,
        'aMCeLsxf@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'MxJmIN', false, false, '2023-05-22 16:27:27', '2023-05-22 16:27:27', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (59, 'gwhPdHBQ', '$2a$13$TJeW2MmbkbSHmnd29NS1OOodC6xIii7lCB3fijwadSQrdBPFL3CvS', NULL, NULL,
        'gwhPdHBQ@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'zNleeT', false, false, '2023-05-22 16:27:27', '2023-05-22 16:27:27', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (60, 'KKAqTheB', '$2a$13$4zbfeFN3YK9p6vmlXMlvVezRDtPL/NgGVkC4Pr4Unq4eYuuNPkgiW', NULL, NULL,
        'KKAqTheB@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'fRDUGb', false, false, '2023-05-22 16:27:27', '2023-05-22 16:27:27', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (61, 'vajrVjGm', '$2a$13$eqXXCd2l.eESXBwjS68ZZepp4ZPCBZ6HiARVszDVKVa9p3V26VInS', NULL, NULL,
        'vajrVjGm@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'qyKMPm', false, false, '2023-05-22 16:27:27', '2023-05-22 16:27:27', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (62, 'IuATaFtM', '$2a$13$WH1mwTVAHRsBheebF64CHev6pPBKOgzSdzUBJL2iB1bRIiduSgWcK', NULL, NULL,
        'IuATaFtM@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'pVeukN', false, false, '2023-05-22 16:27:27', '2023-05-22 16:27:27', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (63, 'YwSIwZmU', '$2a$13$tT2RodoMRYkpVsiyZg.15utQrQZU2s6Dg.HAwOhd14SVtxDlSsUbS', NULL, NULL,
        'YwSIwZmU@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'OBAumH', false, false, '2023-05-22 16:27:27', '2023-05-22 16:27:27', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (64, 'omeqkQEM', '$2a$13$zX/BGHvUYvciaA0VtxWoNOpcIs4vwVvzgk68Qa1asIuCSMDA1GZDS', NULL, NULL,
        'omeqkQEM@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'djJQqm', false, false, '2023-05-22 16:27:27', '2023-05-22 16:27:27', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (65, 'ZVcifskV', '$2a$13$nAhCyPDiA.cvFtIVu37hDOk.bHN2XMn5sCJq.4qOrW.a1oLnv1WlK', NULL, NULL,
        'ZVcifskV@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'daYmaV', false, false, '2023-05-22 16:27:27', '2023-05-22 16:27:27', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (66, 'gROSzVVT', '$2a$13$TU3REMSuwSQHEV6ph6r6vufDxUQ0X5.buh00tePYyDyaB.wB5FFem', NULL, NULL,
        'gROSzVVT@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'Oewxxi', false, false, '2023-05-22 16:27:27', '2023-05-22 16:27:27', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (67, 'NptUgmRw', '$2a$13$rKbRp5Crnk3sd0N/DYtmwOqDdo6nOG7P/ErDNApcOTT/u3xQ93IRa', NULL, NULL,
        'NptUgmRw@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'paOPEN', false, false, '2023-05-22 16:27:27', '2023-05-22 16:27:27', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (68, 'LSNoSpwr', '$2a$13$a1vUMDZGvagt10FTqf6aG.z8EnNqi7j75Kc8o5rs.d.pcv1H2NTay', NULL, NULL,
        'LSNoSpwr@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'KZcZec', false, false, '2023-05-22 16:27:27', '2023-05-22 16:27:27', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (69, 'jxLrlXNs', '$2a$13$JM5ckoijwflTSZaWh9D0Zuti2GX0j3dOOJ10oQAFYKrXT1sIpagSu', NULL, NULL,
        'jxLrlXNs@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'gZkrIS', false, false, '2023-05-22 16:27:27', '2023-05-22 16:27:27', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (70, 'UlryzEth', '$2a$13$6ZhdBV5rWjwkZbwxL1zieuKd16ukEY96WAR3ZW2VihXnztMtZL9lW', NULL, NULL,
        'UlryzEth@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'gPqbAu', false, false, '2023-05-22 16:27:27', '2023-05-22 16:27:27', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (71, 'TodPophd', '$2a$13$T.HWVNLh1KI8VEj.lKJUyuKwb4H/rblkD79MkNyhpBTiEgaGGGpRy', NULL, NULL,
        'TodPophd@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'VEFvew', false, false, '2023-05-22 16:28:57', '2023-05-22 16:28:57', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (72, 'sUJrGdbU', '$2a$13$gJBWEACcL4j2lySk/ZWleeSlBn5oybikN7ekAkeTUbkjIZKSFZQQG', NULL, NULL,
        'sUJrGdbU@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'EoxREc', false, false, '2023-05-22 16:28:57', '2023-05-22 16:28:57', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (73, 'kUuHcBPh', '$2a$13$UDp1hBQosJzPHodZGmuJVOuHkqo1snA05iZDt0.7b69qSIbKJlfua', NULL, NULL,
        'kUuHcBPh@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'TxPQmD', false, false, '2023-05-22 16:28:57', '2023-05-22 16:28:57', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (74, 'HDjBbsQN', '$2a$13$AZRr/dThsInjmRN/MorGLuSKNiWj4YLqgRwxRVnfwvwBTDQyelF1u', NULL, NULL,
        'HDjBbsQN@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'PDtleE', false, false, '2023-05-22 16:28:57', '2023-05-22 16:28:57', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (75, 'rqabRZox', '$2a$13$a7mdT4vahndvnTudqIrUo.FR0ZTeLqg74IGEtipKMGyM0pSo42WDm', NULL, NULL,
        'rqabRZox@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'FXhsEd', false, false, '2023-05-22 16:28:57', '2023-05-22 16:28:57', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (76, 'ycTNnrsk', '$2a$13$pRaEbSs3zsn9VOt8LBOnXujiG/TFPDvX25RssoM8JP214BPNng0WW', NULL, NULL,
        'ycTNnrsk@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'lTlaJV', false, false, '2023-05-22 16:28:57', '2023-05-22 16:28:57', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (77, 'YCYoFOHx', '$2a$13$U2e1Vkz7aOzk3d7qwuMhnOb0psW7mDnFYrDuqxZGlN/6vwIHNroqC', NULL, NULL,
        'YCYoFOHx@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'bKTzBD', false, false, '2023-05-22 16:28:57', '2023-05-22 16:28:57', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (78, 'UoCYCaZa', '$2a$13$BWZxEkWMkj8zHFfCXACcHeSW0YmJh0.oFJJY9xLs.jhnYTO.5koz6', NULL, NULL,
        'UoCYCaZa@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'wWwyzp', false, false, '2023-05-22 16:28:57', '2023-05-22 16:28:57', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (79, 'ejKhNcoR', '$2a$13$R5S6kMj7R4WiyWgMyBykh.vLRlT1oumjmHTBLJvOMynY0GWyncovm', NULL, NULL,
        'ejKhNcoR@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'vxJiGa', false, false, '2023-05-22 16:28:57', '2023-05-22 16:28:57', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (80, 'sPEFZtyt', '$2a$13$yRQ26ry1sr6CjaTIihqxue7Bmp9CPjcNgfmAoQApezVOKSAzGBKee', NULL, NULL,
        'sPEFZtyt@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'LWzgiV', false, false, '2023-05-22 16:28:57', '2023-05-22 16:28:57', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (81, 'mxSeFZSA', '$2a$13$pvng5arEaEKBAJSHapJwZONoiCtE7FPduTWt9R5AssB.ED3efPyeO', NULL, NULL,
        'mxSeFZSA@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'UapzGQ', false, false, '2023-05-22 16:28:57', '2023-05-22 16:28:57', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (82, 'EqnhDoaA', '$2a$13$9sM.sTbg6rcmtccVVxmJDeyviaxgycvRDrufoGlurU1mb6zTF70F2', NULL, NULL,
        'EqnhDoaA@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'mPndQr', false, false, '2023-05-22 16:28:57', '2023-05-22 16:28:57', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (83, 'zsQEyTtS', '$2a$13$hmuJrWI6T/JXrYNYWUDKpeEso2VtxMsYemPSKcDp2rMyILCmeuzsW', NULL, NULL,
        'zsQEyTtS@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'ejZTjJ', false, false, '2023-05-22 16:28:57', '2023-05-22 16:28:57', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (84, 'TWByPZbm', '$2a$13$iCB5BYL8d2FoJCWUoG2RhuW2farlFk.hjpFx/9vwk6O1e9zHxzbFS', NULL, NULL,
        'TWByPZbm@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'FRoiSs', false, false, '2023-05-22 16:28:57', '2023-05-22 16:28:57', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (85, 'sTFtrtyX', '$2a$13$G3WBl8EdpPD04fuc2U7MpeAbnNn/nGnr031TdzUqP6iq3gzkn79tW', NULL, NULL,
        'sTFtrtyX@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'jISniv', false, false, '2023-05-22 16:28:57', '2023-05-22 16:28:57', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (86, 'OeIjKtxL', '$2a$13$jDvkxkWqLRyyd9FEiDTGO.U4IVoXBg/tpNlcCvXKh6p3ipmbejV26', NULL, NULL,
        'OeIjKtxL@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'frPiuK', false, false, '2023-05-22 16:28:57', '2023-05-22 16:28:57', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (87, 'itbhQTKu', '$2a$13$UdVPNMV6VEPMj6.NR4gWSe0yk7kgcitA7jkhvx4Tb6s7cHW48OHFm', NULL, NULL,
        'itbhQTKu@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'aeYVqc', false, false, '2023-05-22 16:28:57', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (88, 'yWUOOTMI', '$2a$13$Us.xv3neOE50QZmBQyWkkuUtZGh4makNAuhKLVkwuO9hWR/4IJNvG', NULL, NULL,
        'yWUOOTMI@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'okIcGw', false, false, '2023-05-22 16:28:57', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (89, 'ldJQLozY', '$2a$13$LAUuXgYeCE3rF5tVAM6nK.WrvcoDZLpCBjaEp/svgTXqbkQbX9gQi', NULL, NULL,
        'ldJQLozY@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'dSbAUq', false, false, '2023-05-22 16:28:57', '2023-05-22 16:28:57', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (90, 'uXjVBqQu', '$2a$13$F7kr2cLCCbxzDsCpadVAVeBwhGwSajd5FvEx1/xXZI4bRgRu8Hgaq', NULL, NULL,
        'uXjVBqQu@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'KOBfhj', false, false, '2023-05-22 16:28:58', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (91, 'VTcfYXKd', '$2a$13$46INoJiq4XwwXk2mJfvwOuQIxszskZ3rTEnVZqiKPglDpy2JeJnYu', NULL, NULL,
        'VTcfYXKd@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'zRybkP', false, false, '2023-05-22 16:28:58', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (92, 'GdodmAHD', '$2a$13$kmqI.y/.qRQThkD1xqG56O.WMxGcKmTZ/I8/IS0NNiW2qG5dcs01G', NULL, NULL,
        'GdodmAHD@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'jRiwDK', false, false, '2023-05-22 16:28:57', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (93, 'ryzJVhMo', '$2a$13$u6d6TfbVgqD8434fvHWgv.daWiFEssiu65Lhi2RIjsXjHf2.wqnU6', NULL, NULL,
        'ryzJVhMo@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'obxRRI', false, false, '2023-05-22 16:28:57', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (94, 'sgMZqoMZ', '$2a$13$tKNVi.jhKgcnjD.SrDL6RuXtoencSjdNrXrV/H6qeBLFSqyFQ2fh6', NULL, NULL,
        'sgMZqoMZ@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'JNpvRz', false, false, '2023-05-22 16:28:58', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (95, 'LxhFxpeI', '$2a$13$upeW3GDBFn/OsuGq2JHkO.1tvZSlk/Q8qlTuBprJZ.hX3OiZ4gCK.', NULL, NULL,
        'LxhFxpeI@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'hzaiHG', false, false, '2023-05-22 16:28:57', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (96, 'cxWxZQDC', '$2a$13$OBD9n1kBnqHmPSQEnPO2S.jVr79BQ/I8.VNHYPlfWg4tiN5B0.fgi', NULL, NULL,
        'cxWxZQDC@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'SVtjnf', false, false, '2023-05-22 16:28:57', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (97, 'EPfnuzCd', '$2a$13$VBKD6gTYgSguBu.J4rxpq.TRtMH91bBHV7RhS/bWP6xHCwfY7iF..', NULL, NULL,
        'EPfnuzCd@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'cgugxY', false, false, '2023-05-22 16:28:57', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (98, 'MouftQcQ', '$2a$13$ZbMO1z.RJGS4P5pHInVT5eP8ZuXn2/4vlX.XB3rHgOlc.axAL2Bfy', NULL, NULL,
        'MouftQcQ@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'ckdrZn', false, false, '2023-05-22 16:28:57', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (99, 'xxfeauAc', '$2a$13$2TcwfHUMsEJUf3DepZyjquEwGOlZpRoq6I8YEBeyIpi/6DMgVXw8e', NULL, NULL,
        'xxfeauAc@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'wUQfjj', false, false, '2023-05-22 16:28:57', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (100, 'aOrFkhdD', '$2a$13$A6VaqH0dbemDzuFQ6g3PlOXGGvdzo/arRHuuWHv/I5F5c/SLqYjjC', NULL, NULL,
        'aOrFkhdD@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'gkXgnV', false, false, '2023-05-22 16:28:57', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (101, 'zfpDlHUM', '$2a$13$OZXujOP6ZpD3X94py7JQDOw0yRCBPkA9scWfImQwp5yf.mKdoQnGW', NULL, NULL,
        'zfpDlHUM@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'iIOtJa', false, false, '2023-05-22 16:28:57', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (102, 'bXCjNIdH', '$2a$13$J8seIKfjCmxVdTkGQd51U.52HwC6H/GCwjS4uZ576FZE4yTKe4EJu', NULL, NULL,
        'bXCjNIdH@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'vEQyHY', false, false, '2023-05-22 16:28:57', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (103, 'fxFwvQHc', '$2a$13$vg6t6j9gMVEnQ7dAWFLZ/OZl6cwpnyfcIBgLLLxLX/nd0RnRJstxe', NULL, NULL,
        'fxFwvQHc@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'EJyeJJ', false, false, '2023-05-22 16:28:58', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (104, 'tGuMsopL', '$2a$13$UIHxhNJGOmw5.d9Ozzhm3e6IGUSZcCdBUkyrTe1u0jnEpNLelU5fq', NULL, NULL,
        'tGuMsopL@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'IskjOX', false, false, '2023-05-22 16:28:58', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (105, 'DgrwoCDS', '$2a$13$P1/xlone3ZipKcYg6yDExOnk4ph6ck8UoCMWkCLoMlXzLZpkhGS/u', NULL, NULL,
        'DgrwoCDS@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'NmxCQz', false, false, '2023-05-22 16:28:58', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (106, 'PAFooGsz', '$2a$13$9Me9unrcuA.py4c/JY4uw.WqSP4bGKitiVKe.xZQlekNwvGiGjR2e', NULL, NULL,
        'PAFooGsz@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'xXbKzd', false, false, '2023-05-22 16:28:58', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (107, 'GsOdcIeO', '$2a$13$lnecVlbt0nkbQfBVh/95YO//L6zTX1QEDpdw2rdeUGFBv0hJ.0/x6', NULL, NULL,
        'GsOdcIeO@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'OUBcxl', false, false, '2023-05-22 16:28:58', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (108, 'YgVwOIwM', '$2a$13$M3Xy6uukGrZcibuGEYHkeOn8aRKQ9OeP4OuAj3iPbeZLvB358e3XW', NULL, NULL,
        'YgVwOIwM@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'qAmjhL', false, false, '2023-05-22 16:28:58', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (109, 'Odgydzwy', '$2a$13$LWWCA5UXQgb2EnNHNDeLR./ajQslUBRaD006UODlEFW0HXXK1wbcu', NULL, NULL,
        'Odgydzwy@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'eEqZbf', false, false, '2023-05-22 16:28:57', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (110, 'wtQvEknd', '$2a$13$npJ2SMLa1EN1uv/sc02gF.XzBNYVDBSz3htvLeVg3txfRIC9pCmle', NULL, NULL,
        'wtQvEknd@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'MnPWNI', false, false, '2023-05-22 16:28:58', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (111, 'eERTlmwP', '$2a$13$Q6ynoHtjPT689N1HdMOIhu6nEAyHJFz9xD6ABd1uxMSk4IPLJNxMG', NULL, NULL,
        'eERTlmwP@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'KEUsqF', false, false, '2023-05-22 16:28:58', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (112, 'NXeApYbx', '$2a$13$hkrpGX9Qg/M6/4j0G37Z6eScc8gHq7yPQncJR8LfVadXlKOx//bk6', NULL, NULL,
        'NXeApYbx@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'GAPVcQ', false, false, '2023-05-22 16:28:58', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (113, 'EXsMhQRE', '$2a$13$xkalVXyP2duL0VAidqFZhejMkjRuh3FyRd5bYSQhcqIrpwl40YfjS', NULL, NULL,
        'EXsMhQRE@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'yYmITO', false, false, '2023-05-22 16:28:58', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (114, 'KImEFGAG', '$2a$13$hbI/4jthkvL4bdLr.qCs2uL5gdxxEvyHj3SPSw3YX6h45ZHTKfZWe', NULL, NULL,
        'KImEFGAG@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'VHDpjv', false, false, '2023-05-22 16:28:58', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (115, 'HBfwfBWu', '$2a$13$.F6.EA2cbTZ7.rhrq0etoejJ4CTCQiKRjELpTEfnyvM0i7ijhp.3e', NULL, NULL,
        'HBfwfBWu@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'FOWEli', false, false, '2023-05-22 16:28:58', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (116, 'FxvlMOAh', '$2a$13$tHhC7Kaot5hcnb0MsDAyG.zRhXFCdeJMFrHMp3hkRZE92hQZ5ffFe', NULL, NULL,
        'FxvlMOAh@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'pmYLsf', false, false, '2023-05-22 16:28:58', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (117, 'DJSkEHUD', '$2a$13$qFq.VRGiCedGGVOlrAMaLOM.V3QLt7LebTr6kxaLd.Um0CHlHrZwa', NULL, NULL,
        'DJSkEHUD@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'wfcIYQ', false, false, '2023-05-22 16:28:58', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (118, 'lVhemADL', '$2a$13$Al.tT1T9zQ8Rh8bEy6LfzeibnC08UoJq3OrXrYMs6IH1JRKo9GKDu', NULL, NULL,
        'lVhemADL@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'CIxrFs', false, false, '2023-05-22 16:28:57', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (119, 'ptsTvrih', '$2a$13$nwEYEOUTvVZfuKD24hLPlOjNzC3j5C7TFqMlR4BCb6EPSX4CjoFqe', NULL, NULL,
        'ptsTvrih@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'ywjhOM', false, false, '2023-05-22 16:28:57', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (120, 'ILbCcppA', '$2a$13$i1YKtLRT81mMk1aCz4cezu./z94XesKb0A9AnYyK525K73aiSexfG', NULL, NULL,
        'ILbCcppA@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'kjSsLk', false, false, '2023-05-22 16:28:58', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (121, 'gYdBAfoe', '$2a$13$mrBUVnI/6.xoZ8EvxIe2iuc73Zu4bc/QCpFHAb2sVsKbN3p01m7DC', NULL, NULL,
        'gYdBAfoe@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'HcZDgw', false, false, '2023-05-22 16:28:58', '2023-05-22 16:28:58', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (122, 'qhGGHXbX', '$2a$13$Gs3kZ4ci8MJpuy2Hsrv2luePGfsM6/biV5MkmzY7LpzrsYPHZ243O', NULL, NULL,
        'qhGGHXbX@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'WuvlFx', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (123, 'RrNsuYdT', '$2a$13$P6ZA/0B6Cyvyf3vnz0qBt.9FD7LAc5E6SmS9XxCkg9F2TYgerPiIe', NULL, NULL,
        'RrNsuYdT@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'CpSctL', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (124, 'qutYyCjI', '$2a$13$DJUpD4bMNUFAoSZFnjxbReqNK8cNnP62IWhB29hH51wcXJYNgrJAu', NULL, NULL,
        'qutYyCjI@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'rhKbjM', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (125, 'AaEDcYIl', '$2a$13$fCIsIqrCAC32fpq5qAJoZO/kaahqZ16foyYZt/.OvsQ4qnhxE1wk2', NULL, NULL,
        'AaEDcYIl@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'PIQjFJ', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (126, 'SDSRHcfI', '$2a$13$hJ/VJL6UIuZxsgPyx7k0y.et6AFv5io9U.4qPEmsIzmEZGUsnliX.', NULL, NULL,
        'SDSRHcfI@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'iWpHjB', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (127, 'vpDwUVks', '$2a$13$uVezmPk/LGnozoHE3qcUtuBcZeY7.RgB327GMG5vGv.VzcHQY1OZS', NULL, NULL,
        'vpDwUVks@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'cUYSXs', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (128, 'quhMXTTI', '$2a$13$5yBvrr1DwnT5O56PYkhFAujeN5sgjM8G0mnKvmGEaJeFD.iqbTTJK', NULL, NULL,
        'quhMXTTI@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'oCRuYm', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (129, 'FsffnlXi', '$2a$13$ZR9kllLGoHFUXyetBSPCEucrDCzr2x0a5182PBhBS4SQqmLVrHi/G', NULL, NULL,
        'FsffnlXi@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'UzjTiQ', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (130, 'sPSzAoNA', '$2a$13$BYZ8cDU0AUe5upQ/3UgHx.n7FZqs61HMHN6/TSVzspsXTmva.Opxa', NULL, NULL,
        'sPSzAoNA@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'UHIdUL', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (131, 'DHJlyQKe', '$2a$13$h16KIul79531w9whRUAo2.Aa4Nd/ic09Hvhmjc9Wk3iscCVkf0pMC', NULL, NULL,
        'DHJlyQKe@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'LiZyLt', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (132, 'hmRlJvVM', '$2a$13$.NWSs0Xr6mqu15HRWwK1KeHHa0MaksxLdSkowySU1EmIhwmhCqUKy', NULL, NULL,
        'hmRlJvVM@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'vRhjOO', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (133, 'UDaxlZMT', '$2a$13$9rkC67xWtUkw4tLd2NSQ6O40ftOsGQLoUkjF59WVf1oBRXSx6U49.', NULL, NULL,
        'UDaxlZMT@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'TrEgbt', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (134, 'NaRodxeN', '$2a$13$E.lRNfoRllklnlq6JQpr.OlXNc9FI7SpIUaC39MTzJ0tfKYmwAOla', NULL, NULL,
        'NaRodxeN@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'BLZewU', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (135, 'dRADUflX', '$2a$13$RqySeUQnI46cNYvig74PgeXWElRr4Wqn6.NI104SVDIR9gyuqGoT.', NULL, NULL,
        'dRADUflX@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'DUnLoB', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (136, 'QuqswVAQ', '$2a$13$MQoYVnA6g39GRh/blc/xROFCAGyNPkjDlE44k3YWkedZjiMHxrPli', NULL, NULL,
        'QuqswVAQ@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'WMDjOT', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (137, 'zQgSTwAv', '$2a$13$sfvkVUeEOkPbqYxQvz0FveWyJ46RGXTplL2md0/cGNPHtan8.iw3y', NULL, NULL,
        'zQgSTwAv@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'jMMXQi', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (138, 'GvAzJQHu', '$2a$13$Gym4kPjdOo7VkfJlolDXCOImyrYduIJpwa90XOt9Ktmi1OXNxwuUK', NULL, NULL,
        'GvAzJQHu@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'EwrriL', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (139, 'tWmrkNvE', '$2a$13$kwSZfpLrdAw7XK/iUHDxHOLaw5xFaXk3E1MM9DoVpjxhaVLghRFUu', NULL, NULL,
        'tWmrkNvE@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'vHNiGZ', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (140, 'QLCqMOmv', '$2a$13$lc2S5bRYXV3I5N//1xVxj.9Gu3wGgGgGZQjhuOarTMmKQkjauE8xK', NULL, NULL,
        'QLCqMOmv@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'jJLbue', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (141, 'XvHNNDVB', '$2a$13$dFa5iKkVyXxAWOMse/EjcuH5IcPQ88Q3mvgsEdDx7lbf6/9T/cbW6', NULL, NULL,
        'XvHNNDVB@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'MkHKQd', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (142, 'OQiBKnkR', '$2a$13$T8PvXYrV2OAkWPsfhwkVSeOGbr4.al/SO72wUomWi1qd1fPkuexOm', NULL, NULL,
        'OQiBKnkR@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'kTtfRx', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (143, 'DpcZqyFz', '$2a$13$TDcMinNp64m9V2q9wyIb0e97K1TjAGIqaCr161pnt3c/oHaogmdGS', NULL, NULL,
        'DpcZqyFz@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'VTQgHf', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (144, 'WUmHpzuP', '$2a$13$XM3HMF70dtexKl.potY2meCydWOsFrAySHYjlrq15gyOPVqJu9pZC', NULL, NULL,
        'WUmHpzuP@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'dQpynz', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (145, 'XvkqgPUV', '$2a$13$otSNwHndOSSyvhY4pS3L2eiAQxHUy7V9bDk.CGPA1d4p15Lmyyx6G', NULL, NULL,
        'XvkqgPUV@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'TZhXYW', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (146, 'SUPlLGfK', '$2a$13$28Fc6Ac.PR0MfgHJZUBFdehvLGbo30o3YdfNCIQFglXqqCM9iLoNS', NULL, NULL,
        'SUPlLGfK@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'vxFinn', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (147, 'xqAuAdcB', '$2a$13$uVqHH84vIPEzD9qDiVeBrO0ap58eobZ6kg2pIEcKzv0mJx9WuU2t.', NULL, NULL,
        'xqAuAdcB@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'akQoly', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (148, 'CxtztLIw', '$2a$13$kWHEamO92QQ2Z5J8g/HmMuz5pak/aS/CivegZ.Ymxf1u0nfZXam/W', NULL, NULL,
        'CxtztLIw@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'DYhACt', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (149, 'KcUdtfRC', '$2a$13$Kez.XDmVgzAi1iTkiTk23Ouit6WF7qR0wahsaH59WVZp8jMgJl776', NULL, NULL,
        'KcUdtfRC@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'PsloZj', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (150, 'sAcadrBX', '$2a$13$L7LT9LdbFyjiWj/dtJgEOesn7HTGCEdEd73cDNs6ZM5fd8d4qbulu', NULL, NULL,
        'sAcadrBX@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'KYALXF', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (151, 'VPypozwF', '$2a$13$9T8sj3JWOK8BvbdodfWsGeYYqN52Gy.ZvJapXgMkmhyaYeu4IkRa6', NULL, NULL,
        'VPypozwF@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'XotzMz', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (152, 'gPlnTDPG', '$2a$13$EK6tmicVRVJeSBxAHmHzsuFncWzt37S8ICUZFTKWRzBOI4bhymp7C', NULL, NULL,
        'gPlnTDPG@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'hEwyvX', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (153, 'KgfJeQSz', '$2a$13$f3fud1TovwQBD0KThxqw0.6yu8wxLuMYnoyG4U5f8oouKJCVJkGrW', NULL, NULL,
        'KgfJeQSz@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'cwSZzP', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (154, 'ljffIayR', '$2a$13$2sJNFB6FCMcOD/l8kF8z.eBt85Z3UqCjlQAjEzsar4Pt8xJAj.vVG', NULL, NULL,
        'ljffIayR@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'TuYqQM', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (155, 'IGgZDfcJ', '$2a$13$HGgVt9mGRFlMj6jm/Y5IUeTeFLfeUMny5wqdGh/l60AsLP9LuneK2', NULL, NULL,
        'IGgZDfcJ@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'VVeMQO', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (156, 'ICDXVdAc', '$2a$13$DEN2EAm6bix1Qp.lKIqjTOTU5zVuZslc.vhvlf8M0PBIO1suRmdiK', NULL, NULL,
        'ICDXVdAc@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'ZiVXyk', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (157, 'GcTwwSmv', '$2a$13$yNvp4gEWU.AwBt4KMOFheu71pkLsnUuAp562C36MR9jTVWfR/WkW6', NULL, NULL,
        'GcTwwSmv@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'yBUwPL', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (158, 'VewuKkHs', '$2a$13$iwCPEjbqUQgyw6UuyIvkUOV.8lwiw74uohUMPk/RYjHnzRpoeiiJe', NULL, NULL,
        'VewuKkHs@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'NffJUp', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (159, 'juxsMwRy', '$2a$13$5lZ1E0sgzSoYRxgnb6Sfe.PTYpTK56wqklOgYHJljTfwQC2MhrCF.', NULL, NULL,
        'juxsMwRy@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'BBmsAN', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (160, 'kAZbknYL', '$2a$13$8U.whleCvjIu61xFTO.FYuplxW/8OL3IsN/vs4xv25m7OlAulVpWO', NULL, NULL,
        'kAZbknYL@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'iiBiiY', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (161, 'xpKbYzHj', '$2a$13$WSxk1xhmVXNP/ZAklowN1OtaI6XUa60SYqvjtvna.CGYdRoBkm2Y6', NULL, NULL,
        'xpKbYzHj@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'eslMih', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (162, 'cncPaayO', '$2a$13$C6ujuaFFv88hqQIzKIKbCenB45pyTEZ9tpBeFUuw27dVHh4Wu8fVu', NULL, NULL,
        'cncPaayO@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'YfAoqZ', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (163, 'BcgmQeqE', '$2a$13$dkkPnRH6c2kjEmNA0F7QkuDpEukTedX/oHgjPQHA.6RymZ06v7g2i', NULL, NULL,
        'BcgmQeqE@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'oeXfhC', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (164, 'JpvPHlOJ', '$2a$13$2oOXvzPexC3RbnbZFuiWW.F0Er68Oa71og56WvA7Rv2SENSUuEy0m', NULL, NULL,
        'JpvPHlOJ@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'tcnlWQ', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (165, 'JEqgBsoS', '$2a$13$ffvF.ceUIyMbhNXIujtBFeZdk4HI4hzXBBd1VyGg7OjlMk4z2xIj6', NULL, NULL,
        'JEqgBsoS@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'DIaMKQ', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (166, 'ctSunqyZ', '$2a$13$JYLWNLURhSM3lXZFbAS0s.HwrVlKwVK7eH10AcXGsdcW95jH04mg.', NULL, NULL,
        'ctSunqyZ@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'lCzMxe', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (167, 'ZHivVxNs', '$2a$13$O3YuPsfaU8Z9e.xVcBEefO1hFn/5zxLzT36kNANrwnOh/wiW8iwGu', NULL, NULL,
        'ZHivVxNs@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'DJMuIA', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (168, 'NoXSBpUC', '$2a$13$.fm3pzTbXtaK233VXN.Hs.q9ev7FsRMIAgOpT3aqmaLb0WgqtRLlS', NULL, NULL,
        'NoXSBpUC@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'sGaKEE', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (169, 'CkTEUMKF', '$2a$13$0wZ8lZjAII7JmZZ78DkpCO5RuDVzhbBMKmO7bv9z8urUC0T0DqOqW', NULL, NULL,
        'CkTEUMKF@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'awVejl', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (170, 'rmmvMgEv', '$2a$13$rofXfOoJyQx8lzG6MLo3o.BYnBUjwJnFRn3T373.BX7Xka0gKnmL.', NULL, NULL,
        'rmmvMgEv@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'SjYIAz', false, false, '2023-05-22 16:28:59', '2023-05-22 16:28:59', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (171, 'qzNuHnue', '$2a$13$psmnLEFjqxKr6vZq5uPfU.n9la0vz9GUCFjzXsdEP3ASOWFaDUrRq', NULL, NULL,
        'qzNuHnue@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'fBEFzm', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (172, 'prKoyfDX', '$2a$13$ewggORjXjX3f0yTBe1uIi..RbUWj3whAQ05cHJE.24LJzszRt6nGe', NULL, NULL,
        'prKoyfDX@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'ngKmgu', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (173, 'oSUVnbYl', '$2a$13$uv3SQ1HO74lcqtciPHmKmOhYA/vMMwJHDfNLeSjAcGgk0p/.x8j3m', NULL, NULL,
        'oSUVnbYl@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'iPOops', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (174, 'RuWLliYT', '$2a$13$/3Q5G7fuSlRo8/Bl5UYQcOHYSzmRI5g4CYiTQvnCyGNM4OuigV9be', NULL, NULL,
        'RuWLliYT@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'iTSNhO', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (175, 'JrxEWZvP', '$2a$13$Kf1BVqN3N.YcovzNuqGQu.s7df8oi.CXkYYjXht6LXOU1SxToGlP6', NULL, NULL,
        'JrxEWZvP@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'MkURba', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (176, 'HexYRrej', '$2a$13$/0UFhfrG8afVG4PlCb8Lue2/TJi0.jWF/7q/xbwLh7vEBUVVJnAYK', NULL, NULL,
        'HexYRrej@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'lGeQVC', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (177, 'KzJwOJhk', '$2a$13$M46saK4PPmi20KRKZoz78eFqxHIdnxO7xZmQyQ0lHd5z0trDveK8W', NULL, NULL,
        'KzJwOJhk@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'bJFEdK', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (178, 'NGQGIIzQ', '$2a$13$qcbwjPF3WRByTSaJoUAq5OHNR3UedsAlxWewujcstJTfsqBR0jn6e', NULL, NULL,
        'NGQGIIzQ@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'yEjSyV', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (179, 'SLXwwAXm', '$2a$13$32ouI3cHi7a4qncqwchv5uWlO354B32so.KGlFsSgDxPLagquleoG', NULL, NULL,
        'SLXwwAXm@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'TiOytL', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (180, 'SyUcyxiV', '$2a$13$BVzOi8oG1kdyDZoNZUYR8edVk1lpQNGVubxAMOoQgw.6nQAj8dqnm', NULL, NULL,
        'SyUcyxiV@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'yxwAiD', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (181, 'BdgxTxmc', '$2a$13$UcKzm7NjyA5.qhgiBT6cfODttjaoXTJ8OhErDfnmHQWSYmGdJJ5OC', NULL, NULL,
        'BdgxTxmc@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'CcZgdZ', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (182, 'mngAhWwQ', '$2a$13$90ywTHPps5fkXCvvmsRuM.vfop0CAYkc6bSJymZ5aX4.PwEyEd6ea', NULL, NULL,
        'mngAhWwQ@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'ghmfdI', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (183, 'DoNVxmqc', '$2a$13$xCmjVisz3PNrXAcdej0pNOJBHX.GfdcuQ0Tju9ZRBQITegitOyCTW', NULL, NULL,
        'DoNVxmqc@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'YCCXvu', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (184, 'IjCYdpot', '$2a$13$NjBbxqSmYsheM4aTJZEBVOsmc8OrUWGLdILF8BkRosy/2ufdUruky', NULL, NULL,
        'IjCYdpot@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'NALluE', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (185, 'jbaHXwWN', '$2a$13$fvyf3WYM6L1Yy.XdIK0qieSCRWUFoeijqW5LDMpfuudrYYE7ZAVDK', NULL, NULL,
        'jbaHXwWN@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'lxYZWt', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (186, 'UnIpyTON', '$2a$13$YXter5oZV.6hG5W0HmF8IO1XNzsX0zYobRTTh0d0h3wC/cW3Ixzk6', NULL, NULL,
        'UnIpyTON@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'RSityb', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (187, 'kZJTKDBF', '$2a$13$8gMgdutY9YpCfUBKVZ.4tOFgKhEklkSl1zxMQ5kAREqLWtUVKwJmO', NULL, NULL,
        'kZJTKDBF@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'UbbLII', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (188, 'FZxqqGHh', '$2a$13$KIyXP1y1v5JwpBfXYLpz1.PHavH4lXKizcDaeJn6ksQBnDCTFxZIy', NULL, NULL,
        'FZxqqGHh@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'GjpCXx', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (189, 'SjCvxJFA', '$2a$13$.S66qw16fqDG4VEpOgqEXOgtTom7FZ29oVMwinEulTlFJTXd/THCG', NULL, NULL,
        'SjCvxJFA@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'KjVsVU', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (190, 'nHRIwqBp', '$2a$13$MT92DTb2zH6ahlTmufIfP.ptPGTKw0Hs0VdLxoR/gMTIwjWwy5CT2', NULL, NULL,
        'nHRIwqBp@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'cQaekf', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (191, 'eXFlCnFY', '$2a$13$X.zolHqElJzkudtiBreGMuMiHPCz9FFwEIxP.Yh1ltJA0dcBP7IQq', NULL, NULL,
        'eXFlCnFY@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'rjygbn', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (192, 'zdsUGiBQ', '$2a$13$MhDjfgrvP/V2QHQHe7H9p.8.URxY1ZMXpDrnbBbEDmzJUFdYW0NOG', NULL, NULL,
        'zdsUGiBQ@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'hdxiGK', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (193, 'vjHXfQRZ', '$2a$13$NMqNTW.fQVCt5TjypyGN3.kcU1Jv1xx40RB0lrrv7or.DRb2sqnC2', NULL, NULL,
        'vjHXfQRZ@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'JLXFhQ', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (194, 'ZcIsKfoZ', '$2a$13$IKcg/Ko5kwIWAdKFq6NXmezB3bBfvehQ3A3UxveU52iODuSmBd/1e', NULL, NULL,
        'ZcIsKfoZ@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'nVXXWR', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (195, 'xMoIdRCu', '$2a$13$zDYvr22FVsAPf3/8vmixduax6z6Il75HA6BWeOVandAKhIdl011u6', NULL, NULL,
        'xMoIdRCu@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'VJRDFd', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (196, 'ISBnfJVn', '$2a$13$vqrk8YW2sCS.MIJcKo43bu8B.RBcggjcusU.i3cTfJD8f7P6QJj.2', NULL, NULL,
        'ISBnfJVn@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'OJuQjf', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (197, 'BLWzLqGf', '$2a$13$jieu2vNm1eBUJ1pA6/PmnOtNPnjiE.vB.NiR3CcJUM7vtI8sMuwrO', NULL, NULL,
        'BLWzLqGf@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'hRlpmD', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (198, 'dBkxEXtX', '$2a$13$jkV.H38tUrCSvJi9jLw87eJTQDLERgk30K1FaIJyU3s1m1x7vXzdu', NULL, NULL,
        'dBkxEXtX@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'gGottS', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (199, 'WopDhppL', '$2a$13$ts6PiLGWxDk4xOjbXlaFEuOxAt5xRPjCVpl9fVPZ2l9XFt0ZnlJY2', NULL, NULL,
        'WopDhppL@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'nmdNVe', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (200, 'obdGlype', '$2a$13$N/Xz85imdbMclBMt92mWq.S3eRx5oVuoR.EFH2sOFltvOfFmv0TPu', NULL, NULL,
        'obdGlype@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'trfdMo', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (201, 'aTwypKJc', '$2a$13$YP8jFhQLrAm8UCkPhfOEOuU.PStYOHfabOeg1wk9yCPf7ctH7Puoi', NULL, NULL,
        'aTwypKJc@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'bRBsFp', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (202, 'dSELeSjj', '$2a$13$/VDTOqm69yGDQULZbXECZuDQOGGzOVSAz4a9WXIqIxSfTwEk9SAsW', NULL, NULL,
        'dSELeSjj@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'NbNuzi', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (203, 'kXnDswaK', '$2a$13$ERlbhsFk14KCaTvnkaKq6OHzrY5OuXKCEysd2c9ER85RyfdLfuILq', NULL, NULL,
        'kXnDswaK@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'GBcqNY', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (204, 'uFtKwhCP', '$2a$13$Zv54mOoOE0ictPzu1F5TvObfQPV2hU5x4VCCcsePUbBBXdidd8a4y', NULL, NULL,
        'uFtKwhCP@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'afCXMQ', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (205, 'wstPkGMm', '$2a$13$d7tupW2Rd13McmRv8/zBge3z78G7wvl0nZpe8uHi9/xXEnO.y2tUK', NULL, NULL,
        'wstPkGMm@interview.com', NULL, '{
    "Roles": [
      "ROLE_USER"
    ]
  }', 'QNPEwr', false, false, '2023-05-22 16:31:38', '2023-05-22 16:31:38', NULL, NULL, NULL);
INSERT INTO testing.intrv_user
VALUES (1, 'interview', '$2y$13$aEDuvAFksTmBfr.dfBP9Z.DEP7JwTT5IPQqgQvtTvOEV.RBHvNC6G', 'username1', 'lastname1',
        'interview@interview.com', '123456789', '{
    "roles": [
      "ROLE_ADMIN",
      "ROLE_USER"
    ]
  }', '624813', true, true, '2023-05-01 09:16:12', '2023-08-23 16:41:57', '2023-05-19 14:09:25', NULL, 3);


--
-- TOC entry 3585 (class 0 OID 16416)
-- Dependencies: 225
-- Data for Name: oauth2_access_token; Type: TABLE DATA; Schema: testing; Owner: interview
--

INSERT INTO testing.oauth2_access_token
VALUES ('92af1895fb331db5d0b2f57be4fb1bccae391286abc64aab22ccdd31a79056864013ca658771463e', 'testclient',
        '2023-08-24 00:24:13', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('ad7e3a8b59fd47e4f05a38053be5d779855a7bf67b6111f55bf283d26a861b4e8aa93f67c1677b07', 'testclient',
        '2023-09-23 23:27:57', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('1fdab0081f1a0acb3b718cbc644ec4929242e65216ce2dfeb08985ea5ebb969dbde82c8e50d72c4f', 'testclient',
        '2023-09-23 23:34:49', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('217c17a5d35e937dcf2a95fbef4e633903eac4776e24e9e880594c6ddee62d09bd8bd8b9531a7d12', 'testclient',
        '2023-09-23 23:43:38', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('79bb5b4f360b9741df503f2221fe005cb469e4efac598be3d3b844789b72be0146f610ebb9549722', 'testclient',
        '2023-09-24 00:01:21', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('3afd9af2981f05e541a4319d8b6beded71d5f9773cfd619b3f7cb42d37ef51c8659fa102453edfdf', 'testclient',
        '2023-09-24 00:01:45', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('30bc08eb90d0f0ac0efcf5889019abed1ad5c68e44eeb79bcc98f0d16e25e4df707a265978ed0c6e', 'testclient',
        '2023-09-24 00:03:01', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('bee4d7d14029569f5ee36c7a739ea8f942c4a5e529f7dc0fb91fbfef83451421caa441fcc033c1b9', 'testclient',
        '2023-09-24 00:03:26', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('cf2c806299082cf02fb54c25cf416f35c5abb9726122483e9e1c2612b185975126012db30aa4b3c3', 'testclient',
        '2023-09-24 00:03:59', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('7973b0ac537507b41a81e3331a64241400f816bc601bcfa935853ea04086d078e0bd2250c257004f', 'testclient',
        '2023-09-24 00:05:01', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('e4ed0c8488b2d014e008342cbed13fd930eaaa11124fc88df72dfaa166427e86c5b762db4efebd52', 'testclient',
        '2023-09-24 00:08:42', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('700af18a67f42d7e86603ea3a1ba5ec9da665c1783232b76f9190786d4dc63f2852c3fee213259d8', 'testclient',
        '2023-09-24 00:11:05', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('98dedb44b25996223cd9e8cb076c1ee096b0e554397c3d17137e2c4d50048a71cfec8693bc3d7dd5', 'testclient',
        '2023-09-24 00:11:20', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('b522e9c6ed42ba0dd869cccd5c571082cf6fbd9d9f030ef3fc45a9047f09a1454cca0d8ba93807bc', 'testclient',
        '2023-09-24 00:11:57', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('5804b55c3122aeb1da88dd1a74a48b8be71a1574458bec5d028d6b15ce9b35599dd309a61ff4e52a', 'testclient',
        '2023-09-24 00:12:42', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('7d7ebc1752da4f37b9ef73b2da062bbaba81203f2c0ea2ef0d313191d6179cdb5595ef9ff6e51636', 'testclient',
        '2023-09-24 00:13:11', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('3a6e7e8028dd4cc9cdbe5f4c6068af4882f62da58b93ad03073483a2e14f729114cbbe65105099a7', 'testclient',
        '2023-09-24 00:13:21', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('87c5d39e2bdb6609c667b5bf9357b582f16aaebb4c0c881964cc1efa1e8ccf4491eda364d400e53a', 'testclient',
        '2023-09-24 00:13:41', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('4a6b42771211bef4dc1599e655f40d212f4135e056ef522fde1710950cf6d63c47ff397c74b50ca4', 'testclient',
        '2023-09-24 00:19:34', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('febf1b7852f2a19a704f928bb2736e6a8e88a6c01e52c7c79b509dcd98ba8f0954a0c3031670a5d2', 'testclient',
        '2023-09-24 00:21:48', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('dd87a23d0393fa9b4b6f3d79940d9249859d95eb9f1f2efeb8cd924507c4436c07585a428953525c', 'testclient',
        '2023-09-24 00:39:29', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('9302b80190d625113ee40d91f0baf92c59fb1a201fc3b423fd3c5cc60e0c2ac2f32a1ce56e5246d4', 'testclient',
        '2023-09-24 00:48:49', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('5e712c6a06c0721c6755e0051cb8bbb469d27cceb2cb47ad4192a68e040a2ca6c0d1d7e15dfb5c3e', 'testclient',
        '2023-09-24 00:50:33', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('24891e73f138806bfa0c04168c0ec2cc0a41f65d2eee7ebed5a5a117de907c2b96bd4cd2cd1f9058', 'testclient',
        '2023-09-28 11:55:13', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('cb137b79c4cab4eefc497803620eff3250ae9b7b965cf6da54dfd9b979d9ea4f3eef998ed7133710', 'testclient',
        '2023-09-28 12:00:27', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('fce9370356e6a4a3f67c0bb4bfe2461b174e968f192c1f43aa467e37978856479582557be5adbfc2', 'testclient',
        '2023-09-28 18:47:36', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('9a1d42be3b7ff9d62a1358a9cec47e8335939e98e1560de942aebf35d8e14c6ec7e0b45596b9fc18', 'testclient',
        '2023-11-10 20:40:41', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('e3e3baf7798b7a1f5175a7c172f3126be46c3edbf71c1393b19f1e4a054c51b721a90cfc7eeff509', 'testclient',
        '2023-11-10 20:43:55', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('828cbc52a7e3e0be7288e088c4671d0a721e873f38a73b76c1f3ac38b618292d6a9b15d63a0da038', 'testclient',
        '2023-11-10 20:46:04', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('77fd255c7af5c22478eec202c27a9996e063245523ac2ff4dd8a7f2b82278b647579474acd2107c7', 'testclient',
        '2023-11-10 20:46:12', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('502618af61e4bf7749ac39ef89e76eed63de675d24cb5749f1ae6c0ed20fe1be3132e94295057340', 'testclient',
        '2023-11-10 20:46:26', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('a2f2d41c9234339bd91f4e941a4826e64d1006c4aa44141f8be2813691a0123247445643bb913528', 'testclient',
        '2023-11-10 20:47:19', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('b70891ed2b27f6bc9f55fb918fa2e43d88314c82246ec8861bf2793a8fbc928eb2570a8be26bc560', 'testclient',
        '2023-11-10 20:47:52', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('4000ec8d583a893affc31a653df9430e7cceca56f8782877ccfaba4666fba226e71e69e3fc033d0c', 'testclient',
        '2023-11-10 20:49:47', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_access_token
VALUES ('973e36287b63b94816f3235178212407550bb3bd1fd6051efc671b58fdf831cdd19cc800c9b1d3c1', 'testclient',
        '2023-11-11 14:21:14', 'interview', 'profile email', false);


--
-- TOC entry 3586 (class 0 OID 16422)
-- Dependencies: 226
-- Data for Name: oauth2_authorization_code; Type: TABLE DATA; Schema: testing; Owner: interview
--

INSERT INTO testing.oauth2_authorization_code
VALUES ('bad31c432891d4fa5162d9539c27277fe5c76a0ae589a5342e129eb0c98fc1dd522d8ed52d3c6cbf', 'testclient',
        '2023-08-23 22:09:42', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_authorization_code
VALUES ('ec3822ba5068c5d041ed91b13c1be705a657018e93a75387444734831e5faa6478555193f696d18d', 'testclient',
        '2023-08-23 22:12:25', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_authorization_code
VALUES ('ec3a5b40992996e4b36c16debebba309a028dfeda7f55d5beb68de477fc0eb2e3f0636c19a071cdf', 'testclient',
        '2023-08-23 22:22:27', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_authorization_code
VALUES ('1ea36ae1af9d26bc1d097bb2d26a3eb0f4738e15a940e7f93346f46d0f508934f4e0e198ec283851', 'testclient',
        '2023-08-23 22:23:45', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_authorization_code
VALUES ('4246b455b43d23402bb996728d759206400c201dd661ab9066fcdc75ce030cbd3d3159d8b12290e7', 'testclient',
        '2023-08-23 22:23:58', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_authorization_code
VALUES ('67be91530875e49fc5df5fdf5f05b38e03f7f9521f4a81a27f2e7a698da96c948712f88bcbf5ca49', 'testclient',
        '2023-08-23 22:25:19', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_authorization_code
VALUES ('bb70ed69277adfd338bc3b2177aca2c942ec5fe83203d10a7c066671963f35c519b7bce4c1c6147a', 'testclient',
        '2023-08-23 22:26:04', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_authorization_code
VALUES ('726acd3c82b4e6c34b33bd62e66dc631001670f32d4dd03ef565fb58acea30c9d1adffe7fcb54edb', 'testclient',
        '2023-08-23 22:27:54', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_authorization_code
VALUES ('1a15a0ced9db12eb1a81f96db1dd8cfd3fbcbec6b080e400d664783b810ed5e185b2e926c76066b7', 'testclient',
        '2023-08-23 22:28:35', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_authorization_code
VALUES ('4686efae1dc7dd2a69f9679ca8c08809155b94d0d278c7b7a996153c9cdf7c27e953e72ce05e3d11', 'testclient',
        '2023-08-23 22:40:15', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_authorization_code
VALUES ('76ec9f1e20491d477602cb7707d670b303465a67e96597002eaa877769790869b785badbf5912672', 'testclient',
        '2023-08-23 22:53:27', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_authorization_code
VALUES ('cae38addfc380dac2fcd193077d16c93485ee71ea84b91a76828900a85806e9ac1d43f6060bb99e2', 'testclient',
        '2023-08-23 22:54:32', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_authorization_code
VALUES ('fbd59adab4553d13dc34b345c007f2c7527caeaf9f79cbd1d2a34f86978d15e970a276349cccc89d', 'testclient',
        '2023-08-23 23:23:14', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_authorization_code
VALUES ('3f47b3c32d3317fcf6e656a56447ce7246e23a548071f69c41290526e5c03535ac17f632269165ed', 'testclient',
        '2023-08-23 23:29:44', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_authorization_code
VALUES ('dd57bf71575f3662f55816702e9f009851c17311b1bef845f390c90521bf0c6ef2717c6d416b2865', 'testclient',
        '2023-08-23 23:28:11', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('7cc8b1b9038849d003bb5699913f27a2fcdbfaad785f79f6f96ff71c87ec903bdfe2e64949d4dfb9', 'testclient',
        '2023-08-23 23:37:53', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('019bb338ca9317c20a529cc7c760d38c00d4ceb4272296b40ce91082e67ee1ea0c9acfccab4e7e72', 'testclient',
        '2023-08-23 23:42:31', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('8e92cbf1bd34e9c7ebf6ac76b9b4e757adb508736e6b5c732bb795ae6d8658d0072c3a3dfc5149c7', 'testclient',
        '2023-08-23 23:45:03', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_authorization_code
VALUES ('14f18cf0f973d432c74b54164074721cdbaa76857b1eb396be1a924b2c6334d6346c618910d465dc', 'testclient',
        '2023-08-23 23:46:19', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_authorization_code
VALUES ('9c963872c4111690efe1a97f85fddb047db53174f4aa7953de63aa243863b4fd9abb75ea6967130b', 'testclient',
        '2023-08-23 23:46:58', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_authorization_code
VALUES ('45e052a693d16465fe224378bde111778ebbcab96aaf2ad517daac8946590066f2fffc28429037a1', 'testclient',
        '2023-08-23 23:47:40', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_authorization_code
VALUES ('47c755de6bfeb08e0e02c24675577cf47d99e147e3ce1222adc8bf55fdf14665b5420efb529ae93d', 'testclient',
        '2023-08-23 23:50:07', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_authorization_code
VALUES ('4b39edcfdd96eae9ddeddfd1e2cea0514fbcad69e0d45db4264188dda9a41f24cd55cbadc22972ef', 'testclient',
        '2023-08-23 23:52:12', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_authorization_code
VALUES ('1d82238f04bdceaf64c810004c67ee47f2778fd679b7799d74c3f5c4da0c7e5f3680a428afc84258', 'testclient',
        '2023-08-23 23:52:46', 'interview', 'profile email', false);
INSERT INTO testing.oauth2_authorization_code
VALUES ('5ded0472c0e0326a8bdc28706950739bfee69014e97c6875616f1a349ca3f3ea517e214a052957f1', 'testclient',
        '2023-08-23 23:53:37', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('3705c5eede2ba8f74f19133b57a0c7f56cd1ee979877335fc920304f54660e11d56c3a2f4d4b042f', 'testclient',
        '2023-08-24 00:11:21', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('13ce187315c2148b75350cdbca44775cd75ca755744524e9fe02df4f16c0027b96cf264b5ee53804', 'testclient',
        '2023-08-24 00:11:45', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('5be2494f1a8a58307496e7df6f3b3e4b545f0400c56cd861859a1589c94aaa8dfc7e1cdac851fd48', 'testclient',
        '2023-08-24 00:13:00', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('7f57ebe96a0d2b2b4843f3fa25bbcfbcf54c745c07ff21d60b85d48e10bd6da46508d1a03c29b59a', 'testclient',
        '2023-08-24 00:13:26', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('59240368483a73618a1ff1685c6a099ff23de8d6ff7a633dcd9aabdce4f0f26e8d1e833cc5918920', 'testclient',
        '2023-08-24 00:13:59', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('703dacf03eaea2db097573e67a920940034951e17598059a2945e092356c40a2f058bdc015b31ed8', 'testclient',
        '2023-08-24 00:15:01', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('22dd374ea212c0ead28a7d5d42b0e84e1802bc2f070264b98b090dc0c68f8f55e0cb004f9436d48e', 'testclient',
        '2023-08-24 00:18:41', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('657e83f4f72661c849a1534ae50a6f0af2d914cd9208f91d070752774f9d24a681dd4e8db2629ea4', 'testclient',
        '2023-08-24 00:21:05', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('418e7690a8ebd72e4616783667aaf510908686ca6882e2ce3392269ce26722bd35946ecadd136e13', 'testclient',
        '2023-08-24 00:21:20', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('cc0bab9e6f0ac1b52a33471dbf17f5035b305ceb90842cb9843a4d671b977bced0fccf3f62da26e3', 'testclient',
        '2023-08-24 00:21:57', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('9c807cccf62f6d0e19fc5ee8e1b8b6c3467430f425b4cc7e1eb5d50f61a29f2caa50092683296f98', 'testclient',
        '2023-08-24 00:22:42', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('ac10adda53c5e556cdf39e007909f3ea1f2cffd34cbc66c8e3620d610f4d698eede0a52d05776573', 'testclient',
        '2023-08-24 00:23:11', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('07250f5101659ed301070637135771502ac3fb72885ed7b95350ada9b6507102d17b95cd2c1f501c', 'testclient',
        '2023-08-24 00:23:20', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('83702779513ee49786cb3577b200eccbb980e2e145613fa1010b09321ced350dd5a9d388671812f9', 'testclient',
        '2023-08-24 00:23:40', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('45c14f75cbbb568c26a765f90029f99db6496d546e878216c5e59e18c9dd217c8fdee1e3d9a72a68', 'testclient',
        '2023-08-24 00:29:33', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('6d447c4a97f209d2abacef1c6c89c4bc74b72f04fbeaee5a798bbef3bd97b5e371ac4b92cd2bba5a', 'testclient',
        '2023-08-24 00:31:48', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('827549f13d2af8b1bfafa1e45f042eb3a71f00d80183c505411adb9fba0a044ebec1c85c898e11ec', 'testclient',
        '2023-08-24 00:49:29', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('45ce2fd4e1dab13e4dedbacef93ccb55cca2522d2c20856a797cb066383fa174bed31bc7551eb161', 'testclient',
        '2023-08-24 00:58:48', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('70a04ba726e2bec77e833014aabc2c2cbce043eac1354724636b6e9d74cd8fac94520e059ea473f7', 'testclient',
        '2023-08-24 01:00:32', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('fd7c12d53ba08a97921180193aea726c995684538898dfbe567bb2d738dd806cb5f95fd7c4ab3993', 'testclient',
        '2023-08-28 12:05:13', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('b6c54c4f6e6de91f696ba9d5f9e15db1a21eaf1ab75133563989ed2d7aba99a1cabb5fb36006d7ed', 'testclient',
        '2023-08-28 12:10:26', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('cc1a33b9b2d45a83a1c59f778dfe23c0fb9722728abd68896438ed2747450a116a9d7b692a3624f9', 'testclient',
        '2023-08-28 18:57:35', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('f2d0b4fda403965f214615294b5211da3f90046231755da6954ddfec82791a43da8cf1cf7bc9e694', 'testclient',
        '2023-10-10 20:50:41', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('cfb98f9af3d26a0af09d6623b0c03dc520e29f6556480e441a81b39f297b27a5c65d4cc23b27851a', 'testclient',
        '2023-10-10 20:53:55', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('aef690cc95709bcf280451afddcae2b2a94d3de0ff694bdcce94c31657afc22a5d33bec8e7c60e0a', 'testclient',
        '2023-10-10 20:56:04', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('ebf063a773657882368e638c9639f70b39141e109a01fa9d1db80684c3210176154dbfd7d6a84e76', 'testclient',
        '2023-10-10 20:56:12', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('627a6396b1f95ca7449f65df2982ad30158eceb12c288872919266a11f7bd36f5f42fa66c35eb450', 'testclient',
        '2023-10-10 20:56:26', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('07ec195c6d6e5d4776ad847f8923f1d65df3af3a2da684f20414fad143ca7dd96041872eebdcc7e7', 'testclient',
        '2023-10-10 20:57:19', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('0db1d4c66ce9b7732651673b3614a0fbd9cd7fc934935a810c3ac0108479551e3f5145cd59c2ce35', 'testclient',
        '2023-10-10 20:57:51', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('59640a1625b094845f59f73e13879dc75adb99c61592a4b9752baab922b37e314497e328500b860f', 'testclient',
        '2023-10-10 20:59:47', 'interview', 'profile email', true);
INSERT INTO testing.oauth2_authorization_code
VALUES ('c64e59967d9de84581c2322fc926a6d1cd192b89d1128848420b61f743c0606b78dc8eb6ef43d789', 'testclient',
        '2023-10-11 14:31:13', 'interview', 'profile email', true);


--
-- TOC entry 3587 (class 0 OID 16428)
-- Dependencies: 227
-- Data for Name: oauth2_client; Type: TABLE DATA; Schema: testing; Owner: interview
--

INSERT INTO testing.oauth2_client
VALUES ('testclient', 'Test Client', 'testpass', 'https://interview.local/callback',
        'authorization_code client_credentials refresh_token', 'profile email cart', true, false);


--
-- TOC entry 3588 (class 0 OID 16435)
-- Dependencies: 228
-- Data for Name: oauth2_client_profile; Type: TABLE DATA; Schema: testing; Owner: interview
--

INSERT INTO testing.oauth2_client_profile
VALUES (1, 'testclient', 'Test Client App', NULL);


--
-- TOC entry 3590 (class 0 OID 16441)
-- Dependencies: 230
-- Data for Name: oauth2_refresh_token; Type: TABLE DATA; Schema: testing; Owner: interview
--

INSERT INTO testing.oauth2_refresh_token
VALUES ('f86c715357e8c43b40623735889a884f3372f0ec9a425e721bb7a3e9fb06beaa541eb44f4ec34b35',
        '92af1895fb331db5d0b2f57be4fb1bccae391286abc64aab22ccdd31a79056864013ca658771463e', '2023-09-23 23:24:13',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('36da757c81021c91beaa1a1900798b86538a2a9192b5f112f02d971eaa88962c827fa449f5f6babe',
        'ad7e3a8b59fd47e4f05a38053be5d779855a7bf67b6111f55bf283d26a861b4e8aa93f67c1677b07', '2023-09-23 23:27:57',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('8becb754b7c9f7f5c8a99cc2aff38a9c5adb2a9529eca5bc787529b6d66109d29b8b0ef891cbf5d6',
        '1fdab0081f1a0acb3b718cbc644ec4929242e65216ce2dfeb08985ea5ebb969dbde82c8e50d72c4f', '2023-09-23 23:34:49',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('eb1744a78e9045dcfdd62eef5f80f9d25ad61ef036ea57c3851135ec5af7cfad706b00a67a378dc3',
        '217c17a5d35e937dcf2a95fbef4e633903eac4776e24e9e880594c6ddee62d09bd8bd8b9531a7d12', '2023-09-23 23:43:38',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('8b30a60f08333a7fbf7bec516b25b060766049da49566fae7c559b8917e099615deee20e80428029',
        '79bb5b4f360b9741df503f2221fe005cb469e4efac598be3d3b844789b72be0146f610ebb9549722', '2023-09-24 00:01:21',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('89f1050fd93d836ae2ddf4a16271764a45ddfba3d593e6059f92c06b28427fa07a14b3d6b6d32d31',
        '3afd9af2981f05e541a4319d8b6beded71d5f9773cfd619b3f7cb42d37ef51c8659fa102453edfdf', '2023-09-24 00:01:45',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('3158a0acf4ad54322b375c038212adaca564e42338b12347363929b3737723015ee7c7c6940e8ecd',
        '30bc08eb90d0f0ac0efcf5889019abed1ad5c68e44eeb79bcc98f0d16e25e4df707a265978ed0c6e', '2023-09-24 00:03:01',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('954322620eb5cd9569f9bc266bb4b2d36e4ba938870e1c25df60a1da5ae00970ebb07e208a92991f',
        'bee4d7d14029569f5ee36c7a739ea8f942c4a5e529f7dc0fb91fbfef83451421caa441fcc033c1b9', '2023-09-24 00:03:26',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('01a866aa747392edd37ce0abc177dc3a4f0eba7ea95ed16742430468905ee363c98793750ea25692',
        'cf2c806299082cf02fb54c25cf416f35c5abb9726122483e9e1c2612b185975126012db30aa4b3c3', '2023-09-24 00:03:59',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('e4f24bc95155288c0cc5e7e7d11c19f0e33defb8bcc6d7d9ca1fe2b922f6462555dbcccd3a81ae3f',
        '7973b0ac537507b41a81e3331a64241400f816bc601bcfa935853ea04086d078e0bd2250c257004f', '2023-09-24 00:05:01',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('419dcf70279e801e7f1dbb20f7533599490039b123b3dc9bcee9bfffa8e5dc4f28e2691741d3ebd0',
        'e4ed0c8488b2d014e008342cbed13fd930eaaa11124fc88df72dfaa166427e86c5b762db4efebd52', '2023-09-24 00:08:42',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('13f830edb04bbf2f70255291a76acd8af88c5323ed8fa92d6c2dba6c8e7b0c2655963cca555189e1',
        '700af18a67f42d7e86603ea3a1ba5ec9da665c1783232b76f9190786d4dc63f2852c3fee213259d8', '2023-09-24 00:11:05',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('700856576bb3bde09a21145e83b962633a395471b1e2662bec31cfca8f290cc2d78a99abed5fc063',
        '98dedb44b25996223cd9e8cb076c1ee096b0e554397c3d17137e2c4d50048a71cfec8693bc3d7dd5', '2023-09-24 00:11:20',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('09300cff58032096eb170b35b4402a551457f9e069831bc77a5d9549dcfb2125c604bfdd2ead9e7b',
        'b522e9c6ed42ba0dd869cccd5c571082cf6fbd9d9f030ef3fc45a9047f09a1454cca0d8ba93807bc', '2023-09-24 00:11:57',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('f565909e4678258a20ae625ce27624e9680363b15185609dfd5cac93ea73877dbfbf1aef59fa9b89',
        '5804b55c3122aeb1da88dd1a74a48b8be71a1574458bec5d028d6b15ce9b35599dd309a61ff4e52a', '2023-09-24 00:12:42',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('78f54568e6f4ecf8bc7b95cb90ae3faf5501d56392d8b156a5b0ff8c788ea8df8dc99d9f92cfa369',
        '7d7ebc1752da4f37b9ef73b2da062bbaba81203f2c0ea2ef0d313191d6179cdb5595ef9ff6e51636', '2023-09-24 00:13:11',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('08979c9d086f68a7dfadea554afbdec7e22ea21c719810f51431d7e29093d73bfb1daac46758bdab',
        '3a6e7e8028dd4cc9cdbe5f4c6068af4882f62da58b93ad03073483a2e14f729114cbbe65105099a7', '2023-09-24 00:13:21',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('4c88af788e0a9d3be679d8f3c43584568243a57fdbe5f5e96cbf94fec0eb2eacf6ac8bb8a582b6cc',
        '87c5d39e2bdb6609c667b5bf9357b582f16aaebb4c0c881964cc1efa1e8ccf4491eda364d400e53a', '2023-09-24 00:13:41',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('5670d56cf140460847c26c5de4723a0659572ffeb0448bd3484c473dd36e6359006823850863360c',
        '4a6b42771211bef4dc1599e655f40d212f4135e056ef522fde1710950cf6d63c47ff397c74b50ca4', '2023-09-24 00:19:34',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('158be8cf2de0c3b3697d27ac0b863feff07afca8ef2b56ca088ebac7b4bbf1066ba34cfbfcc8eaab',
        'febf1b7852f2a19a704f928bb2736e6a8e88a6c01e52c7c79b509dcd98ba8f0954a0c3031670a5d2', '2023-09-24 00:21:48',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('84ef19e1d359ce415264a815a94f8eff67160a0bfa3c27d4dfcb54d2b2fcabeb88dda32bd72f75ec',
        'dd87a23d0393fa9b4b6f3d79940d9249859d95eb9f1f2efeb8cd924507c4436c07585a428953525c', '2023-09-24 00:39:29',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('3b2f3ffe87a4531c3e9219bea63d95a3c8c7258bc49f49717a5cb4049bd97577c91a2d0ed7dd4aba',
        '9302b80190d625113ee40d91f0baf92c59fb1a201fc3b423fd3c5cc60e0c2ac2f32a1ce56e5246d4', '2023-09-24 00:48:49',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('a777dbb7edacd789f0f40e7eff6a5200d55cab08069e46280df898b46fe88b2cc8afa6ee512bb2a5',
        '5e712c6a06c0721c6755e0051cb8bbb469d27cceb2cb47ad4192a68e040a2ca6c0d1d7e15dfb5c3e', '2023-09-24 00:50:33',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('cb7b0bca51d3a4c9bdae8b52e8d1eac228c437c4a9ee3750b9f442737af5669f8ac3c7bb95898ce8',
        '24891e73f138806bfa0c04168c0ec2cc0a41f65d2eee7ebed5a5a117de907c2b96bd4cd2cd1f9058', '2023-09-28 11:55:13',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('bf36408eb272267f9aa592a6075c8cef34d420b2046154da77ae00d820668ec016f1fcf49fc42197',
        'cb137b79c4cab4eefc497803620eff3250ae9b7b965cf6da54dfd9b979d9ea4f3eef998ed7133710', '2023-09-28 12:00:27',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('d2ab8942e4f61d8d1ab7bd91e1bcaab6359ddc15853db017979839cba09d4d92d7661e2168dd0898',
        'fce9370356e6a4a3f67c0bb4bfe2461b174e968f192c1f43aa467e37978856479582557be5adbfc2', '2023-09-28 18:47:36',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('671d628cc3941e6279402997b176237a7a2b59161eac5d014319efd2c5fe57a7517119f7a92af4a7',
        '9a1d42be3b7ff9d62a1358a9cec47e8335939e98e1560de942aebf35d8e14c6ec7e0b45596b9fc18', '2023-11-10 20:40:41',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('d0517d588c5f08f9dcbfcf7e16d7f494d3c6c27eb74412608538599e95a192c15dfefc2fb2601f65',
        'e3e3baf7798b7a1f5175a7c172f3126be46c3edbf71c1393b19f1e4a054c51b721a90cfc7eeff509', '2023-11-10 20:43:55',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('c3fd477b724a4b7576f66c758d160e29345863cce60214e355a436f62d86ad1be87e612a3dd81714',
        '828cbc52a7e3e0be7288e088c4671d0a721e873f38a73b76c1f3ac38b618292d6a9b15d63a0da038', '2023-11-10 20:46:04',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('79fa22048c349bad453bca2bd67062e79f0d2ea18bbf37e7d01dcdafd10b83affae381a90dc87d06',
        '77fd255c7af5c22478eec202c27a9996e063245523ac2ff4dd8a7f2b82278b647579474acd2107c7', '2023-11-10 20:46:12',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('55fd0a81165994e4eab364c628ce00afae1eabac017e1e9dd255c4342048ae913b45d68f8146ab86',
        '502618af61e4bf7749ac39ef89e76eed63de675d24cb5749f1ae6c0ed20fe1be3132e94295057340', '2023-11-10 20:46:26',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('cf51d22ebda650437d2127427096cdc2e51f56878acfdc15907e8426bf36ad5daeb5edcaf74a1637',
        'a2f2d41c9234339bd91f4e941a4826e64d1006c4aa44141f8be2813691a0123247445643bb913528', '2023-11-10 20:47:19',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('fcb1edc8b2ef24607de7d7532310a76afac766c8b6522250ae53dbef0bb46c7aa2ea5e63d4fbf76a',
        'b70891ed2b27f6bc9f55fb918fa2e43d88314c82246ec8861bf2793a8fbc928eb2570a8be26bc560', '2023-11-10 20:47:52',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('0ce006207450509df2fa5c39348d3c700f59cab6956ba032594e9b15c1e4395e61266ae57921f63f',
        '4000ec8d583a893affc31a653df9430e7cceca56f8782877ccfaba4666fba226e71e69e3fc033d0c', '2023-11-10 20:49:47',
        false);
INSERT INTO testing.oauth2_refresh_token
VALUES ('5a7bf760d4a3ac48c79a8c355c5e4c3be185914fe39c3dd6145887e37cb4e72b203c0e51ad222517',
        '973e36287b63b94816f3235178212407550bb3bd1fd6051efc671b58fdf831cdd19cc800c9b1d3c1', '2023-11-11 14:21:14',
        false);


--
-- TOC entry 3591 (class 0 OID 16445)
-- Dependencies: 231
-- Data for Name: oauth2_user_consent; Type: TABLE DATA; Schema: testing; Owner: interview
--

INSERT INTO testing.oauth2_user_consent
VALUES (1, 'testclient', 1, '2023-08-24 00:47:30', '2023-09-23 00:47:30', 'profile,email,cart', '172.18.0.1');


--
-- TOC entry 3593 (class 0 OID 16453)
-- Dependencies: 233
-- Data for Name: order_item; Type: TABLE DATA; Schema: testing; Owner: interview
--

INSERT INTO testing.order_item
VALUES (46,
        '"{\n    \"id\": 3,\n    \"plan_name\": \"gold\",\n    \"description\": \"Basic plan for all users, that allows platform usage\",\n    \"is_active\": true,\n    \"is_visible\": true,\n    \"unit_price\": 5000,\n    \"created_at\": \"2023-05-22T19:49:34+00:00\",\n    \"updated_at\": \"2023-05-22T19:49:34+00:00\"\n}"',
        118, 'plan');
INSERT INTO testing.order_item
VALUES (46,
        '"{\n    \"id\": 73,\n    \"name\": \"R\\u00f6d Kaviar\",\n    \"supplier_id\": 17,\n    \"category_id\": 8,\n    \"category\": {\n        \"id\": 8,\n        \"name\": \"Seafood\",\n        \"description\": \"Seaweed and fish\",\n        \"picture\": \"Resource id #60\"\n    },\n    \"quantity_per_unit\": \"24 - 150 g jars\",\n    \"unit_price\": 1500,\n    \"units_in_stock\": 100,\n    \"units_on_order\": 0\n}"',
        119, 'product');
INSERT INTO testing.order_item
VALUES (47,
        '"{\n    \"id\": 4,\n    \"plan_name\": \"vip\",\n    \"description\": \"exclusive offers for VIP members\",\n    \"is_active\": false,\n    \"is_visible\": true,\n    \"unit_price\": 10000,\n    \"tier\": 30,\n    \"created_at\": \"2023-05-22T19:49:34+00:00\",\n    \"deleted_at\": \"2023-05-22T19:49:34+00:00\"\n}"',
        120, 'plan');
INSERT INTO testing.order_item
VALUES (48,
        '"{\n    \"id\": 76,\n    \"name\": \"Lakkalik\\u00f6\\u00f6ri\",\n    \"supplier_id\": 23,\n    \"category_id\": 1,\n    \"category\": {\n        \"id\": 1,\n        \"name\": \"Beverages\",\n        \"description\": \"Soft drinks, coffees, teas, beers, and ales\"\n    },\n    \"quantity_per_unit\": \"500 ml\",\n    \"unit_price\": 1800,\n    \"units_in_stock\": 56,\n    \"units_on_order\": 0,\n    \"required_subscription\": {\n        \"id\": 2,\n        \"plan_name\": \"plus\",\n        \"description\": \"Additional plan with more offers for user\",\n        \"is_active\": true,\n        \"is_visible\": true,\n        \"unit_price\": 1000,\n        \"tier\": 10,\n        \"created_at\": \"2023-05-22T19:49:34+02:00\"\n    }\n}"',
        121, 'product');
INSERT INTO testing.order_item
VALUES (49,
        '"{\n    \"id\": 76,\n    \"name\": \"Lakkalik\\u00f6\\u00f6ri\",\n    \"supplier_id\": 23,\n    \"category_id\": 1,\n    \"category\": {\n        \"id\": 1,\n        \"name\": \"Beverages\",\n        \"description\": \"Soft drinks, coffees, teas, beers, and ales\"\n    },\n    \"quantity_per_unit\": \"500 ml\",\n    \"unit_price\": 1800,\n    \"units_in_stock\": 56,\n    \"units_on_order\": 0,\n    \"required_subscription\": {\n        \"id\": 2,\n        \"plan_name\": \"plus\",\n        \"description\": \"Additional plan with more offers for user\",\n        \"is_active\": true,\n        \"is_visible\": true,\n        \"unit_price\": 1000,\n        \"tier\": 10,\n        \"created_at\": \"2023-05-22T19:49:34+02:00\"\n    }\n}"',
        122, 'product');
INSERT INTO testing.order_item
VALUES (49,
        '"{\n    \"id\": 3,\n    \"plan_name\": \"gold\",\n    \"description\": \"Basic plan for all users, that allows platform usage\",\n    \"is_active\": true,\n    \"is_visible\": true,\n    \"unit_price\": 5000,\n    \"tier\": 20,\n    \"created_at\": \"2023-05-22T19:49:34+02:00\",\n    \"updated_at\": \"2023-05-22T19:49:34+02:00\"\n}"',
        123, 'plan');
INSERT INTO testing.order_item
VALUES (50,
        '"{\n    \"id\": 75,\n    \"name\": \"Rh\\u00f6nbr\\u00e4u Klosterbier\",\n    \"supplier_id\": 12,\n    \"category_id\": 1,\n    \"category\": {\n        \"id\": 1,\n        \"name\": \"Beverages\",\n        \"description\": \"Soft drinks, coffees, teas, beers, and ales\"\n    },\n    \"quantity_per_unit\": \"24 - 0.5 l bottles\",\n    \"unit_price\": 775,\n    \"units_in_stock\": 123,\n    \"units_on_order\": 0,\n    \"required_subscription\": {\n        \"id\": 3,\n        \"plan_name\": \"gold\",\n        \"description\": \"Basic plan for all users, that allows platform usage\",\n        \"is_active\": true,\n        \"is_visible\": true,\n        \"unit_price\": 5000,\n        \"tier\": 20,\n        \"created_at\": \"2023-05-22T19:49:34+02:00\",\n        \"updated_at\": \"2023-05-22T19:49:34+02:00\"\n    }\n}"',
        124, 'product');
INSERT INTO testing.order_item
VALUES (52,
        '"{\n    \"id\": 4,\n    \"plan_name\": \"vip\",\n    \"description\": \"exclusive offers for VIP members\",\n    \"is_active\": false,\n    \"is_visible\": true,\n    \"unit_price\": 10000,\n    \"tier\": 30,\n    \"created_at\": \"2023-05-22T19:49:34+02:00\",\n    \"deleted_at\": \"2023-05-22T19:49:34+02:00\"\n}"',
        126, 'plan');
INSERT INTO testing.order_item
VALUES (53,
        '"{\n    \"id\": 75,\n    \"name\": \"Rh\\u00f6nbr\\u00e4u Klosterbier\",\n    \"supplier_id\": 12,\n    \"category_id\": 1,\n    \"category\": {\n        \"id\": 1,\n        \"name\": \"Beverages\",\n        \"description\": \"Soft drinks, coffees, teas, beers, and ales\"\n    },\n    \"quantity_per_unit\": \"24 - 0.5 l bottles\",\n    \"unit_price\": 775,\n    \"units_in_stock\": 124,\n    \"units_on_order\": 0,\n    \"required_subscription\": {\n        \"id\": 3,\n        \"plan_name\": \"gold\",\n        \"description\": \"Basic plan for all users, that allows platform usage\",\n        \"is_active\": true,\n        \"is_visible\": true,\n        \"unit_price\": 5000,\n        \"tier\": 20,\n        \"created_at\": \"2023-05-22T19:49:34+02:00\",\n        \"updated_at\": \"2023-05-22T19:49:34+02:00\"\n    }\n}"',
        127, 'product');


--
-- TOC entry 3596 (class 0 OID 16460)
-- Dependencies: 236
-- Data for Name: order_pending; Type: TABLE DATA; Schema: testing; Owner: interview
--


--
-- TOC entry 3597 (class 0 OID 16463)
-- Dependencies: 237
-- Data for Name: orders; Type: TABLE DATA; Schema: testing; Owner: interview
--

INSERT INTO testing.orders
VALUES (46, 'completed', '2023-08-23 15:14:21', '2023-08-23 15:19:51', 1, 6500, 16);
INSERT INTO testing.orders
VALUES (47, 'completed', '2023-08-23 19:25:53', '2023-08-23 19:25:54', 1, 10000, 16);
INSERT INTO testing.orders
VALUES (49, 'completed', '2023-08-28 21:56:08', '2023-08-28 21:58:05', 1, 6800, 16);
INSERT INTO testing.orders
VALUES (48, 'completed', '2023-08-28 21:28:18', '2023-08-28 22:05:36', 1, 1800, 16);
INSERT INTO testing.orders
VALUES (50, 'completed', '2023-10-10 18:24:48', '2023-10-10 18:24:52', 1, 1550, 16);
INSERT INTO testing.orders
VALUES (52, 'completed', '2023-10-11 13:49:20', '2023-10-11 13:49:24', 1, 10000, 16);
INSERT INTO testing.orders
VALUES (53, 'completed', '2023-10-11 14:14:07', '2023-10-11 14:17:35', 1, 775, 34);


--
-- TOC entry 3598 (class 0 OID 16468)
-- Dependencies: 238
-- Data for Name: payment; Type: TABLE DATA; Schema: testing; Owner: interview
--

INSERT INTO testing.payment
VALUES (1, 17, '27d12b45-ada4-47fa-a33b-e4b4bb348b29', 'payment', 6500, NULL, '2023-08-23 15:14:21', 46, 'completed');
INSERT INTO testing.payment
VALUES (1, 18, '4738d475-3a6b-46c3-a43c-182005ad8ab7', 'payment', 10000, NULL, '2023-08-23 19:25:53', 47, 'completed');
INSERT INTO testing.payment
VALUES (1, 20, 'cd5a55de-3ff6-4291-9118-b69222a66205', 'payment', 6800, NULL, '2023-08-28 21:56:08', 49, 'completed');
INSERT INTO testing.payment
VALUES (1, 19, '6739595e-fdb1-4e9e-ba41-67ed48959ab8', 'payment', 1800, NULL, '2023-08-28 21:28:18', 48, 'completed');
INSERT INTO testing.payment
VALUES (1, 21, '2a10d6dd-f83f-4e8c-89eb-9f904c205cf6', 'payment', 1550, NULL, '2023-10-10 18:24:48', 50, 'completed');
INSERT INTO testing.payment
VALUES (1, 22, '1a507d5f-dc34-4096-9174-40b222ef095b', 'payment', 10000, NULL, '2023-10-11 13:49:20', 52, 'completed');
INSERT INTO testing.payment
VALUES (1, 23, '780163ee-5ef1-48b7-9e6b-2e953dd50851', 'payment', 775, NULL, '2023-10-11 14:14:07', 53, 'completed');


--
-- TOC entry 3600 (class 0 OID 16474)
-- Dependencies: 240
-- Data for Name: plan; Type: TABLE DATA; Schema: testing; Owner: interview
--

INSERT INTO testing.plan
VALUES (1, 'freemium', 'Basic plan for all users, that allows platform usage', true, '2023-05-22 19:49:34', NULL, NULL,
        0, false, 1);
INSERT INTO testing.plan
VALUES (2, 'plus', 'Additional plan with more offers for user', true, '2023-05-22 19:49:34', NULL, NULL, 1000, true,
        10);
INSERT INTO testing.plan
VALUES (3, 'gold', 'Basic plan for all users, that allows platform usage', true, '2023-05-22 19:49:34',
        '2023-05-22 19:49:34', NULL, 5000, true, 20);
INSERT INTO testing.plan
VALUES (4, 'vip', 'exclusive offers for VIP members', false, '2023-05-22 19:49:34', NULL, '2023-05-22 19:49:34', 10000,
        true, 30);
INSERT INTO testing.plan
VALUES (5, '500+', 'Many many discounts, everywhere', true, '2023-05-22 19:49:34', NULL, NULL, 15000, true, 40);
INSERT INTO testing.plan
VALUES (6, '800+', 'They pay You for visiting them', true, '2023-05-22 19:49:34', NULL, NULL, 30000, true, 50);


--
-- TOC entry 3602 (class 0 OID 16483)
-- Dependencies: 242
-- Data for Name: product_cart_item; Type: TABLE DATA; Schema: testing; Owner: interview
--

INSERT INTO testing.product_cart_item
VALUES (41, 76);
INSERT INTO testing.product_cart_item
VALUES (42, 76);
INSERT INTO testing.product_cart_item
VALUES (43, 76);
INSERT INTO testing.product_cart_item
VALUES (51, 72);
INSERT INTO testing.product_cart_item
VALUES (52, 63);
INSERT INTO testing.product_cart_item
VALUES (53, 58);
INSERT INTO testing.product_cart_item
VALUES (90, 77);
INSERT INTO testing.product_cart_item
VALUES (92, 77);
INSERT INTO testing.product_cart_item
VALUES (94, 72);
INSERT INTO testing.product_cart_item
VALUES (95, 67);
INSERT INTO testing.product_cart_item
VALUES (96, 65);
INSERT INTO testing.product_cart_item
VALUES (103, 73);


--
-- TOC entry 3603 (class 0 OID 16486)
-- Dependencies: 243
-- Data for Name: products; Type: TABLE DATA; Schema: testing; Owner: interview
--

INSERT INTO testing.products
VALUES (66, 'Louisiana Hot Spiced Okra', 2, 2, '24 - 8 oz jars', 1700, 4, 100, 1);
INSERT INTO testing.products
VALUES (74, 'Longlife Tofu', 4, 7, '5 kg pkg.', 1000, 6, 20, 4);
INSERT INTO testing.products
VALUES (73, 'Röd Kaviar', 17, 8, '24 - 150 g jars', 1500, 100, 0, 5);
INSERT INTO testing.products
VALUES (69, 'Gudbrandsdalsost', 15, 4, '10 kg pkg.', 3600, 26, 0, 1);
INSERT INTO testing.products
VALUES (72, 'Mozzarella di Giovanni', 14, 4, '24 - 200 g pkgs.', 3480, 14, 0, 6);
INSERT INTO testing.products
VALUES (71, 'Flotemysost', 15, 4, '10 - 500 g pkgs.', 2150, 26, 0, 1);
INSERT INTO testing.products
VALUES (70, 'Outback Lager', 7, 1, '24 - 355 ml bottles', 1500, 15, 10, 2);
INSERT INTO testing.products
VALUES (68, 'Scottish Longbreads', 8, 3, '10 boxes x 8 pieces', 1250, 6, 10, 1);
INSERT INTO testing.products
VALUES (67, 'Laughing Lumberjack Lager', 16, 1, '24 - 12 oz bottles', 1400, 52, 0, 1);
INSERT INTO testing.products
VALUES (77, 'Original Frankfurter grüne Soße', 12, 2, '12 boxes', 1300, 2, 0, 1);
INSERT INTO testing.products
VALUES (65, 'Louisiana Fiery Hot Pepper Sauce', 2, 2, '32 - 8 oz bottles', 2105, 76, 0, 3);
INSERT INTO testing.products
VALUES (64, 'Wimmers gute Semmelknödel', 12, 5, '20 bags x 4 pieces', 3325, 22, 80, 4);
INSERT INTO testing.products
VALUES (63, 'Vegie-spread', 7, 2, '15 - 625 g jars', 4390, 24, 0, 1);
INSERT INTO testing.products
VALUES (48, 'Chocolade', 22, 3, '10 pkgs.', 1275, 15, 70, 2);
INSERT INTO testing.products
VALUES (47, 'Zaanse koeken', 22, 3, '10 - 4 oz boxes', 950, 36, 0, 2);
INSERT INTO testing.products
VALUES (46, 'Spegesild', 21, 8, '4 - 450 g glasses', 1200, 95, 0, 4);
INSERT INTO testing.products
VALUES (45, 'Rogede sild', 21, 8, '1k pkg.', 950, 5, 70, 4);
INSERT INTO testing.products
VALUES (44, 'Gula Malacca', 20, 2, '20 - 2 kg bags', 1945, 27, 0, 4);
INSERT INTO testing.products
VALUES (43, 'Ipoh Coffee', 20, 1, '16 - 500 g tins', 4600, 17, 10, 2);
INSERT INTO testing.products
VALUES (42, 'Singaporean Hokkien Fried Mee', 20, 5, '32 - 1 kg pkgs.', 1400, 26, 0, 2);
INSERT INTO testing.products
VALUES (41, 'Jack''s New England Clam Chowder', 19, 8, '12 - 12 oz cans', 965, 85, 0, 2);
INSERT INTO testing.products
VALUES (40, 'Boston Crab Meat', 19, 8, '24 - 4 oz tins', 1840, 123, 0, 2);
INSERT INTO testing.products
VALUES (39, 'Chartreuse verte', 18, 1, '750 cc per bottle', 1800, 69, 0, 2);
INSERT INTO testing.products
VALUES (38, 'Côte de Blaye', 18, 1, '12 - 75 cl bottles', 26350, 17, 0, 2);
INSERT INTO testing.products
VALUES (37, 'Gravad lax', 17, 8, '12 - 500 g pkgs.', 2600, 11, 50, 2);
INSERT INTO testing.products
VALUES (36, 'Inlagd Sill', 17, 8, '24 - 250 g  jars', 1900, 112, 0, 2);
INSERT INTO testing.products
VALUES (35, 'Steeleye Stout', 16, 1, '24 - 12 oz bottles', 1800, 20, 0, 2);
INSERT INTO testing.products
VALUES (34, 'Sasquatch Ale', 16, 1, '24 - 12 oz bottles', 1400, 111, 0, 2);
INSERT INTO testing.products
VALUES (33, 'Geitost', 15, 4, '500 g', 250, 112, 0, 2);
INSERT INTO testing.products
VALUES (32, 'Mascarpone Fabioli', 14, 4, '24 - 200 g pkgs.', 3200, 9, 40, 2);
INSERT INTO testing.products
VALUES (31, 'Gorgonzola Telino', 14, 4, '12 - 100 g pkgs', 1250, 0, 70, 2);
INSERT INTO testing.products
VALUES (30, 'Nord-Ost Matjeshering', 13, 8, '10 - 200 g glasses', 2589, 10, 0, 2);
INSERT INTO testing.products
VALUES (29, 'Thüringer Rostbratwurst', 12, 6, '50 bags x 30 sausgs.', 12379, 0, 0, 3);
INSERT INTO testing.products
VALUES (28, 'Rössle Sauerkraut', 12, 7, '25 - 825 g cans', 4560, 26, 0, 3);
INSERT INTO testing.products
VALUES (27, 'Schoggi Schokolade', 11, 3, '100 - 100 g pieces', 4390, 49, 0, 1);
INSERT INTO testing.products
VALUES (61, 'Sirop d''érable', 29, 2, '24 - 500 ml bottles', 2850, 113, 0, 5);
INSERT INTO testing.products
VALUES (60, 'Camembert Pierrot', 28, 4, '15 - 300 g rounds', 3400, 19, 0, 6);
INSERT INTO testing.products
VALUES (58, 'Escargots de Bourgogne', 27, 8, '24 pieces', 1325, 62, 0, 2);
INSERT INTO testing.products
VALUES (57, 'Ravioli Angelo', 26, 5, '24 - 250 g pkgs.', 1950, 36, 0, 2);
INSERT INTO testing.products
VALUES (56, 'Gnocchi di nonna Alice', 26, 5, '24 - 250 g pkgs.', 3800, 21, 10, 2);
INSERT INTO testing.products
VALUES (55, 'Pâté chinois', 25, 6, '24 boxes x 2 pies', 2400, 115, 0, 3);
INSERT INTO testing.products
VALUES (54, 'Tourtière', 25, 6, '16 pies', 745, 21, 0, 2);
INSERT INTO testing.products
VALUES (53, 'Perth Pasties', 24, 6, '48 pieces', 3280, 0, 0, 4);
INSERT INTO testing.products
VALUES (52, 'Filo Mix', 24, 5, '16 - 2 kg boxes', 700, 38, 0, 3);
INSERT INTO testing.products
VALUES (51, 'Manjimup Dried Apples', 24, 7, '50 - 300 g pkgs.', 5300, 20, 0, 3);
INSERT INTO testing.products
VALUES (50, 'Valkoinen suklaa', 23, 3, '12 - 100 g bars', 1625, 65, 0, 3);
INSERT INTO testing.products
VALUES (49, 'Maxilaku', 23, 3, '24 - 50 g pkgs.', 2000, 10, 60, 3);
INSERT INTO testing.products
VALUES (62, 'Tarte au sucre', 29, 3, '48 pies', 4930, 17, 0, 1);
INSERT INTO testing.products
VALUES (59, 'Raclette Courdavault', 28, 4, '5 kg pkg.', 5500, 79, 0, 1);
INSERT INTO testing.products
VALUES (26, 'Gumbär Gummibärchen', 11, 3, '100 - 250 g bags', 3123, 15, 0, 1);
INSERT INTO testing.products
VALUES (25, 'NuNuCa Nuß-Nougat-Creme', 11, 3, '20 - 450 g glasses', 1400, 76, 0, 1);
INSERT INTO testing.products
VALUES (24, 'Guaraná Fantástica', 10, 1, '12 - 355 ml cans', 450, 20, 0, 1);
INSERT INTO testing.products
VALUES (23, 'Tunnbröd', 9, 5, '12 - 250 g pkgs.', 900, 61, 0, 1);
INSERT INTO testing.products
VALUES (22, 'Gustaf''s Knäckebröd', 9, 5, '24 - 500 g pkgs.', 2100, 104, 0, 1);
INSERT INTO testing.products
VALUES (21, 'Sir Rodney''s Scones', 8, 3, '24 pkgs. x 4 pieces', 1000, 3, 40, 1);
INSERT INTO testing.products
VALUES (20, 'Sir Rodney''s Marmalade', 8, 3, '30 gift boxes', 8100, 40, 0, 1);
INSERT INTO testing.products
VALUES (19, 'Teatime Chocolate Biscuits', 8, 3, '10 boxes x 12 pieces', 920, 25, 0, 1);
INSERT INTO testing.products
VALUES (18, 'Carnarvon Tigers', 7, 8, '16 kg pkg.', 6250, 42, 0, 1);
INSERT INTO testing.products
VALUES (17, 'Alice Mutton', 7, 6, '20 - 1 kg tins', 3900, 0, 0, 1);
INSERT INTO testing.products
VALUES (16, 'Pavlova', 7, 3, '32 - 500 g boxes', 1745, 29, 0, 1);
INSERT INTO testing.products
VALUES (15, 'Genen Shouyu', 6, 2, '24 - 250 ml bottles', 1300, 39, 0, 1);
INSERT INTO testing.products
VALUES (14, 'Tofu', 6, 7, '40 - 100 g pkgs.', 2325, 35, 0, 1);
INSERT INTO testing.products
VALUES (13, 'Konbu', 6, 8, '2 kg box', 600, 24, 0, 1);
INSERT INTO testing.products
VALUES (12, 'Queso Manchego La Pastora', 5, 4, '10 - 500 g pkgs.', 3800, 86, 0, 1);
INSERT INTO testing.products
VALUES (9, 'Mishi Kobe Niku', 4, 6, '18 - 500 g pkgs.', 9700, 29, 0, 1);
INSERT INTO testing.products
VALUES (8, 'Northwoods Cranberry Sauce', 3, 2, '12 - 12 oz jars', 4000, 6, 0, 1);
INSERT INTO testing.products
VALUES (7, 'Uncle Bob''s Organic Dried Pears', 3, 7, '12 - 1 lb pkgs.', 3000, 15, 0, 1);
INSERT INTO testing.products
VALUES (6, 'Grandma''s Boysenberry Spread', 3, 2, '12 - 8 oz jars', 2500, 120, 0, 1);
INSERT INTO testing.products
VALUES (5, 'Chef Anton''s Gumbo Mix', 2, 2, '36 boxes', 2135, 0, 0, 1);
INSERT INTO testing.products
VALUES (4, 'Chef Anton''s Cajun Seasoning', 2, 2, '48 - 6 oz jars', 2200, 53, 0, 1);
INSERT INTO testing.products
VALUES (3, 'Aniseed Syrup', 1, 2, '12 - 550 ml bottles', 1000, 13, 70, 1);
INSERT INTO testing.products
VALUES (2, 'Chang', 1, 1, '24 - 12 oz bottles', 1900, 17, 40, 1);
INSERT INTO testing.products
VALUES (1, 'Chai', 8, 1, '10 boxes x 30 bags', 1800, 39, 0, 1);
INSERT INTO testing.products
VALUES (10, 'Ikura', 4, 8, '12 - 200 ml jars', 3100, 31, 0, 1);
INSERT INTO testing.products
VALUES (76, 'Lakkalikööri', 23, 1, '500 ml', 1800, 84, 0, 2);
INSERT INTO testing.products
VALUES (75, 'Rhönbräu Klosterbier', 12, 1, '24 - 0.5 l bottles', 775, 125, 0, 3);
INSERT INTO testing.products
VALUES (11, 'Queso Cabrales', 5, 4, '1 kg pkg.', 2100, 10, 30, 1);


--
-- TOC entry 3605 (class 0 OID 16490)
-- Dependencies: 245
-- Data for Name: subscription; Type: TABLE DATA; Schema: testing; Owner: interview
--

INSERT INTO testing.subscription
VALUES (3, false, '2023-08-23 16:41:57', NULL, NULL, 4, NULL);


--
-- TOC entry 3606 (class 0 OID 16497)
-- Dependencies: 246
-- Data for Name: subscription_cart_item; Type: TABLE DATA; Schema: testing; Owner: interview
--


--
-- TOC entry 3607 (class 0 OID 16500)
-- Dependencies: 247
-- Data for Name: subscription_plan_cart_item; Type: TABLE DATA; Schema: testing; Owner: interview
--

INSERT INTO testing.subscription_plan_cart_item
VALUES (50, 6);
INSERT INTO testing.subscription_plan_cart_item
VALUES (56, 6);
INSERT INTO testing.subscription_plan_cart_item
VALUES (57, 1);
INSERT INTO testing.subscription_plan_cart_item
VALUES (58, 2);
INSERT INTO testing.subscription_plan_cart_item
VALUES (91, 2);
INSERT INTO testing.subscription_plan_cart_item
VALUES (93, 5);
INSERT INTO testing.subscription_plan_cart_item
VALUES (102, 3);
INSERT INTO testing.subscription_plan_cart_item
VALUES (104, 4);


--
-- TOC entry 3609 (class 0 OID 16504)
-- Dependencies: 249
-- Data for Name: suppliers; Type: TABLE DATA; Schema: testing; Owner: interview
--

INSERT INTO testing.suppliers
VALUES (1, 'Exotic Liquids');
INSERT INTO testing.suppliers
VALUES (2, 'New Orleans Cajun Delights');
INSERT INTO testing.suppliers
VALUES (3, 'Grandma Kelly''s Homestead');
INSERT INTO testing.suppliers
VALUES (4, 'Tokyo Traders');
INSERT INTO testing.suppliers
VALUES (5, 'Cooperativa de Quesos ''Las Cabras''');
INSERT INTO testing.suppliers
VALUES (6, 'Mayumi''s');
INSERT INTO testing.suppliers
VALUES (7, 'Pavlova, Ltd.');
INSERT INTO testing.suppliers
VALUES (8, 'Specialty Biscuits, Ltd.');
INSERT INTO testing.suppliers
VALUES (9, 'PB Knäckebröd AB');
INSERT INTO testing.suppliers
VALUES (10, 'Refrescos Americanas LTDA');
INSERT INTO testing.suppliers
VALUES (11, 'Heli Süßwaren GmbH & Co. KG');
INSERT INTO testing.suppliers
VALUES (12, 'Plutzer Lebensmittelgroßmärkte AG');
INSERT INTO testing.suppliers
VALUES (13, 'Nord-Ost-Fisch Handelsgesellschaft mbH');
INSERT INTO testing.suppliers
VALUES (14, 'Formaggi Fortini s.r.l.');
INSERT INTO testing.suppliers
VALUES (15, 'Norske Meierier');
INSERT INTO testing.suppliers
VALUES (16, 'Bigfoot Breweries');
INSERT INTO testing.suppliers
VALUES (17, 'Svensk Sjöföda AB');
INSERT INTO testing.suppliers
VALUES (18, 'Aux joyeux ecclésiastiques');
INSERT INTO testing.suppliers
VALUES (19, 'New England Seafood Cannery');
INSERT INTO testing.suppliers
VALUES (20, 'Leka Trading');
INSERT INTO testing.suppliers
VALUES (21, 'Lyngbysild');
INSERT INTO testing.suppliers
VALUES (22, 'Zaanse Snoepfabriek');
INSERT INTO testing.suppliers
VALUES (23, 'Karkki Oy');
INSERT INTO testing.suppliers
VALUES (24, 'G''day, Mate');
INSERT INTO testing.suppliers
VALUES (25, 'Ma Maison');
INSERT INTO testing.suppliers
VALUES (26, 'Pasta Buttini s.r.l.');
INSERT INTO testing.suppliers
VALUES (27, 'Escargots Nouveaux');
INSERT INTO testing.suppliers
VALUES (28, 'Gai pâturage');
INSERT INTO testing.suppliers
VALUES (29, 'Forêts d''érables');


--
-- TOC entry 3611 (class 0 OID 16508)
-- Dependencies: 251
-- Data for Name: user_subscription; Type: TABLE DATA; Schema: testing; Owner: interview
--


--
-- TOC entry 3613 (class 0 OID 16513)
-- Dependencies: 253
-- Data for Name: voucher; Type: TABLE DATA; Schema: testing; Owner: interview
--


--
-- TOC entry 3637 (class 0 OID 0)
-- Dependencies: 216
-- Name: address_addressid_seq; Type: SEQUENCE SET; Schema: testing; Owner: interview
--

SELECT pg_catalog.setval('testing.address_addressid_seq', 34, true);


--
-- TOC entry 3638 (class 0 OID 0)
-- Dependencies: 218
-- Name: cart_cart_id_seq; Type: SEQUENCE SET; Schema: testing; Owner: interview
--

SELECT pg_catalog.setval('testing.cart_cart_id_seq', 23, true);


--
-- TOC entry 3639 (class 0 OID 0)
-- Dependencies: 220
-- Name: cart_item_cart_item_id_seq; Type: SEQUENCE SET; Schema: testing; Owner: interview
--

SELECT pg_catalog.setval('testing.cart_item_cart_item_id_seq', 144, true);


--
-- TOC entry 3640 (class 0 OID 0)
-- Dependencies: 222
-- Name: categories_categoryid_seq; Type: SEQUENCE SET; Schema: testing; Owner: interview
--

SELECT pg_catalog.setval('testing.categories_categoryid_seq', 10, false);


--
-- TOC entry 3641 (class 0 OID 0)
-- Dependencies: 224
-- Name: intrv_user_user_id_seq; Type: SEQUENCE SET; Schema: testing; Owner: interview
--

SELECT pg_catalog.setval('testing.intrv_user_user_id_seq', 1, false);


--
-- TOC entry 3642 (class 0 OID 0)
-- Dependencies: 229
-- Name: oauth2_client_profile_id_seq; Type: SEQUENCE SET; Schema: testing; Owner: interview
--

SELECT pg_catalog.setval('testing.oauth2_client_profile_id_seq', 1, false);


--
-- TOC entry 3643 (class 0 OID 0)
-- Dependencies: 232
-- Name: oauth2_user_consent_id_seq; Type: SEQUENCE SET; Schema: testing; Owner: interview
--

SELECT pg_catalog.setval('testing.oauth2_user_consent_id_seq', 1, true);


--
-- TOC entry 3644 (class 0 OID 0)
-- Dependencies: 234
-- Name: order_item_order_item_id_seq; Type: SEQUENCE SET; Schema: testing; Owner: interview
--

SELECT pg_catalog.setval('testing.order_item_order_item_id_seq', 127, true);


--
-- TOC entry 3645 (class 0 OID 0)
-- Dependencies: 235
-- Name: order_orderid_seq; Type: SEQUENCE SET; Schema: testing; Owner: interview
--

SELECT pg_catalog.setval('testing.order_orderid_seq', 53, true);


--
-- TOC entry 3646 (class 0 OID 0)
-- Dependencies: 239
-- Name: payment_paymentid_seq; Type: SEQUENCE SET; Schema: testing; Owner: interview
--

SELECT pg_catalog.setval('testing.payment_paymentid_seq', 23, true);


--
-- TOC entry 3647 (class 0 OID 0)
-- Dependencies: 241
-- Name: plan_plan_id_seq; Type: SEQUENCE SET; Schema: testing; Owner: interview
--

SELECT pg_catalog.setval('testing.plan_plan_id_seq', 6, true);


--
-- TOC entry 3648 (class 0 OID 0)
-- Dependencies: 244
-- Name: products_productid_seq; Type: SEQUENCE SET; Schema: testing; Owner: interview
--

SELECT pg_catalog.setval('testing.products_productid_seq', 80, false);


--
-- TOC entry 3649 (class 0 OID 0)
-- Dependencies: 248
-- Name: subscription_subscription_id_seq; Type: SEQUENCE SET; Schema: testing; Owner: interview
--

SELECT pg_catalog.setval('testing.subscription_subscription_id_seq', 3, true);


--
-- TOC entry 3650 (class 0 OID 0)
-- Dependencies: 250
-- Name: suppliers_supplier_id_seq; Type: SEQUENCE SET; Schema: testing; Owner: interview
--

SELECT pg_catalog.setval('testing.suppliers_supplier_id_seq', 10, false);


--
-- TOC entry 3651 (class 0 OID 0)
-- Dependencies: 252
-- Name: user_subscription_subscription_id_seq; Type: SEQUENCE SET; Schema: testing; Owner: interview
--

SELECT pg_catalog.setval('testing.user_subscription_subscription_id_seq', 1, false);


--
-- TOC entry 3652 (class 0 OID 0)
-- Dependencies: 254
-- Name: voucher_voucher_id_seq; Type: SEQUENCE SET; Schema: testing; Owner: interview
--

SELECT pg_catalog.setval('testing.voucher_voucher_id_seq', 1, false);


--
-- TOC entry 3334 (class 2606 OID 16521)
-- Name: address address_pkey; Type: CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.address
    ADD CONSTRAINT address_pkey PRIMARY KEY (address_id);


--
-- TOC entry 3340 (class 2606 OID 16523)
-- Name: cart_item cart_item_id_pk; Type: CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.cart_item
    ADD CONSTRAINT cart_item_id_pk PRIMARY KEY (cart_item_id);


--
-- TOC entry 3337 (class 2606 OID 16525)
-- Name: cart cart_pkey; Type: CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.cart
    ADD CONSTRAINT cart_pkey PRIMARY KEY (cart_id);


--
-- TOC entry 3344 (class 2606 OID 16527)
-- Name: categories categories_pkey; Type: CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (category_id);


--
-- TOC entry 3346 (class 2606 OID 16529)
-- Name: intrv_user intrv_user_pkey; Type: CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.intrv_user
    ADD CONSTRAINT intrv_user_pkey PRIMARY KEY (user_id);


--
-- TOC entry 3350 (class 2606 OID 16531)
-- Name: oauth2_access_token oauth2_access_token_pkey; Type: CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.oauth2_access_token
    ADD CONSTRAINT oauth2_access_token_pkey PRIMARY KEY (identifier);


--
-- TOC entry 3353 (class 2606 OID 16533)
-- Name: oauth2_authorization_code oauth2_authorization_code_pkey; Type: CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.oauth2_authorization_code
    ADD CONSTRAINT oauth2_authorization_code_pkey PRIMARY KEY (identifier);


--
-- TOC entry 3355 (class 2606 OID 16535)
-- Name: oauth2_client oauth2_client_pkey; Type: CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.oauth2_client
    ADD CONSTRAINT oauth2_client_pkey PRIMARY KEY (identifier);


--
-- TOC entry 3357 (class 2606 OID 16537)
-- Name: oauth2_client_profile oauth2_client_profile_pkey; Type: CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.oauth2_client_profile
    ADD CONSTRAINT oauth2_client_profile_pkey PRIMARY KEY (id);


--
-- TOC entry 3361 (class 2606 OID 16539)
-- Name: oauth2_refresh_token oauth2_refresh_token_pkey; Type: CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.oauth2_refresh_token
    ADD CONSTRAINT oauth2_refresh_token_pkey PRIMARY KEY (identifier);


--
-- TOC entry 3365 (class 2606 OID 16541)
-- Name: oauth2_user_consent oauth2_user_consent_pkey; Type: CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.oauth2_user_consent
    ADD CONSTRAINT oauth2_user_consent_pkey PRIMARY KEY (id);


--
-- TOC entry 3368 (class 2606 OID 16543)
-- Name: order_item order_item_pkey; Type: CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.order_item
    ADD CONSTRAINT order_item_pkey PRIMARY KEY (order_item_id);


--
-- TOC entry 3370 (class 2606 OID 16545)
-- Name: order_pending order_pending_id_pk; Type: CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.order_pending
    ADD CONSTRAINT order_pending_id_pk PRIMARY KEY (order_id);


--
-- TOC entry 3374 (class 2606 OID 16547)
-- Name: orders order_pkey; Type: CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.orders
    ADD CONSTRAINT order_pkey PRIMARY KEY (order_id);


--
-- TOC entry 3377 (class 2606 OID 16549)
-- Name: payment payment_pkey; Type: CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.payment
    ADD CONSTRAINT payment_pkey PRIMARY KEY (payment_id);


--
-- TOC entry 3387 (class 2606 OID 16551)
-- Name: products pk_products; Type: CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.products
    ADD CONSTRAINT pk_products PRIMARY KEY (product_id);


--
-- TOC entry 3379 (class 2606 OID 16553)
-- Name: plan plan_pkey; Type: CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.plan
    ADD CONSTRAINT plan_pkey PRIMARY KEY (plan_id);


--
-- TOC entry 3383 (class 2606 OID 16555)
-- Name: product_cart_item product_cart_item_pkey; Type: CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.product_cart_item
    ADD CONSTRAINT product_cart_item_pkey PRIMARY KEY (cart_item_id);


--
-- TOC entry 3393 (class 2606 OID 16557)
-- Name: subscription_cart_item subscription_cart_item_pkey; Type: CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.subscription_cart_item
    ADD CONSTRAINT subscription_cart_item_pkey PRIMARY KEY (cart_item_id);


--
-- TOC entry 3390 (class 2606 OID 16559)
-- Name: subscription subscription_pkey; Type: CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.subscription
    ADD CONSTRAINT subscription_pkey PRIMARY KEY (subscription_id);


--
-- TOC entry 3396 (class 2606 OID 16561)
-- Name: subscription_plan_cart_item subscription_plan_cart_item_pkey; Type: CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.subscription_plan_cart_item
    ADD CONSTRAINT subscription_plan_cart_item_pkey PRIMARY KEY (cart_item_id);


--
-- TOC entry 3398 (class 2606 OID 16563)
-- Name: suppliers suppliers_pkey; Type: CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.suppliers
    ADD CONSTRAINT suppliers_pkey PRIMARY KEY (supplier_id);


--
-- TOC entry 3402 (class 2606 OID 16565)
-- Name: user_subscription user_subscription_pkey; Type: CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.user_subscription
    ADD CONSTRAINT user_subscription_pkey PRIMARY KEY (subscription_id);


--
-- TOC entry 3404 (class 2606 OID 16567)
-- Name: user_subscription user_subscription_user_id_key; Type: CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.user_subscription
    ADD CONSTRAINT user_subscription_user_id_key UNIQUE (user_id);


--
-- TOC entry 3406 (class 2606 OID 16569)
-- Name: voucher voucher_pkey; Type: CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.voucher
    ADD CONSTRAINT voucher_pkey PRIMARY KEY (voucher_id);


--
-- TOC entry 3348 (class 1259 OID 16570)
-- Name: idx_454d9673c7440455; Type: INDEX; Schema: testing; Owner: interview
--

CREATE INDEX idx_454d9673c7440455 ON testing.oauth2_access_token USING btree (client);


--
-- TOC entry 3359 (class 1259 OID 16571)
-- Name: idx_4dd90732b6a2dd68; Type: INDEX; Schema: testing; Owner: interview
--

CREATE INDEX idx_4dd90732b6a2dd68 ON testing.oauth2_refresh_token USING btree (access_token);


--
-- TOC entry 3351 (class 1259 OID 16572)
-- Name: idx_509fef5fc7440455; Type: INDEX; Schema: testing; Owner: interview
--

CREATE INDEX idx_509fef5fc7440455 ON testing.oauth2_authorization_code USING btree (client);


--
-- TOC entry 3366 (class 1259 OID 16573)
-- Name: idx_52ea1f098d9f6d38; Type: INDEX; Schema: testing; Owner: interview
--

CREATE INDEX idx_52ea1f098d9f6d38 ON testing.order_item USING btree (order_id);


--
-- TOC entry 3394 (class 1259 OID 16574)
-- Name: idx_6a21f6f6d0a5cda7; Type: INDEX; Schema: testing; Owner: interview
--

CREATE INDEX idx_6a21f6f6d0a5cda7 ON testing.subscription_plan_cart_item USING btree (destination_entity_id);


--
-- TOC entry 3375 (class 1259 OID 16575)
-- Name: idx_6d28840d8d9f6d38; Type: INDEX; Schema: testing; Owner: interview
--

CREATE INDEX idx_6d28840d8d9f6d38 ON testing.payment USING btree (order_id);


--
-- TOC entry 3381 (class 1259 OID 16576)
-- Name: idx_9e5e93aad0a5cda7; Type: INDEX; Schema: testing; Owner: interview
--

CREATE INDEX idx_9e5e93aad0a5cda7 ON testing.product_cart_item USING btree (destination_entity_id);


--
-- TOC entry 3388 (class 1259 OID 16577)
-- Name: idx_a3c664d39b8ce200; Type: INDEX; Schema: testing; Owner: interview
--

CREATE INDEX idx_a3c664d39b8ce200 ON testing.subscription USING btree (subscription_plan_id);


--
-- TOC entry 3384 (class 1259 OID 16578)
-- Name: idx_b3ba5a5a12469de2; Type: INDEX; Schema: testing; Owner: interview
--

CREATE INDEX idx_b3ba5a5a12469de2 ON testing.products USING btree (category_id);


--
-- TOC entry 3385 (class 1259 OID 16579)
-- Name: idx_b3ba5a5acb1d096a; Type: INDEX; Schema: testing; Owner: interview
--

CREATE INDEX idx_b3ba5a5acb1d096a ON testing.products USING btree (required_subscription_id);


--
-- TOC entry 3338 (class 1259 OID 16580)
-- Name: idx_ba388b7a76ed395; Type: INDEX; Schema: testing; Owner: interview
--

CREATE INDEX idx_ba388b7a76ed395 ON testing.cart USING btree (user_id);


--
-- TOC entry 3391 (class 1259 OID 16581)
-- Name: idx_c241e79b9a1887dc; Type: INDEX; Schema: testing; Owner: interview
--

CREATE INDEX idx_c241e79b9a1887dc ON testing.subscription_cart_item USING btree (subscription_id);


--
-- TOC entry 3362 (class 1259 OID 16582)
-- Name: idx_c8f05d0119eb6921; Type: INDEX; Schema: testing; Owner: interview
--

CREATE INDEX idx_c8f05d0119eb6921 ON testing.oauth2_user_consent USING btree (client_id);


--
-- TOC entry 3363 (class 1259 OID 16583)
-- Name: idx_c8f05d01a76ed395; Type: INDEX; Schema: testing; Owner: interview
--

CREATE INDEX idx_c8f05d01a76ed395 ON testing.oauth2_user_consent USING btree (user_id);


--
-- TOC entry 3335 (class 1259 OID 16584)
-- Name: idx_d4e6f81a76ed395; Type: INDEX; Schema: testing; Owner: interview
--

CREATE INDEX idx_d4e6f81a76ed395 ON testing.address USING btree (user_id);


--
-- TOC entry 3371 (class 1259 OID 16585)
-- Name: idx_e52ffdeea76ed395; Type: INDEX; Schema: testing; Owner: interview
--

CREATE INDEX idx_e52ffdeea76ed395 ON testing.orders USING btree (user_id);


--
-- TOC entry 3372 (class 1259 OID 16586)
-- Name: idx_e52ffdeef5b7af75; Type: INDEX; Schema: testing; Owner: interview
--

CREATE INDEX idx_e52ffdeef5b7af75 ON testing.orders USING btree (address_id);


--
-- TOC entry 3341 (class 1259 OID 16587)
-- Name: idx_f0fe25271ad5cdbf; Type: INDEX; Schema: testing; Owner: interview
--

CREATE INDEX idx_f0fe25271ad5cdbf ON testing.cart_item USING btree (cart_id);


--
-- TOC entry 3342 (class 1259 OID 24915)
-- Name: idx_f0fe252741471bab; Type: INDEX; Schema: testing; Owner: interview
--

CREATE INDEX idx_f0fe252741471bab ON testing.cart_item USING btree (reference_entity_id);


--
-- TOC entry 3399 (class 1259 OID 16588)
-- Name: plan_created_at_index; Type: INDEX; Schema: testing; Owner: interview
--

CREATE INDEX plan_created_at_index ON testing.user_subscription USING btree (purchased_at);


--
-- TOC entry 3400 (class 1259 OID 16589)
-- Name: subscriptions_user_idx; Type: INDEX; Schema: testing; Owner: interview
--

CREATE INDEX subscriptions_user_idx ON testing.user_subscription USING btree (user_id);


--
-- TOC entry 3380 (class 1259 OID 16590)
-- Name: u_plan_idx; Type: INDEX; Schema: testing; Owner: interview
--

CREATE INDEX u_plan_idx ON testing.plan USING btree (plan_name);


--
-- TOC entry 3358 (class 1259 OID 16591)
-- Name: uniq_9b524e1f19eb6921; Type: INDEX; Schema: testing; Owner: interview
--

CREATE UNIQUE INDEX uniq_9b524e1f19eb6921 ON testing.oauth2_client_profile USING btree (client_id);


--
-- TOC entry 3347 (class 1259 OID 16592)
-- Name: uniq_c2dbfc019a1887dc; Type: INDEX; Schema: testing; Owner: interview
--

CREATE UNIQUE INDEX uniq_c2dbfc019a1887dc ON testing.intrv_user USING btree (subscription_id);


--
-- TOC entry 3411 (class 2606 OID 16593)
-- Name: oauth2_access_token fk_454d9673c7440455; Type: FK CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.oauth2_access_token
    ADD CONSTRAINT fk_454d9673c7440455 FOREIGN KEY (client) REFERENCES testing.oauth2_client (identifier) ON DELETE CASCADE;


--
-- TOC entry 3414 (class 2606 OID 16598)
-- Name: oauth2_refresh_token fk_4dd90732b6a2dd68; Type: FK CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.oauth2_refresh_token
    ADD CONSTRAINT fk_4dd90732b6a2dd68 FOREIGN KEY (access_token) REFERENCES testing.oauth2_access_token (identifier) ON DELETE SET NULL;


--
-- TOC entry 3412 (class 2606 OID 16603)
-- Name: oauth2_authorization_code fk_509fef5fc7440455; Type: FK CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.oauth2_authorization_code
    ADD CONSTRAINT fk_509fef5fc7440455 FOREIGN KEY (client) REFERENCES testing.oauth2_client (identifier) ON DELETE CASCADE;


--
-- TOC entry 3417 (class 2606 OID 16608)
-- Name: order_item fk_52ea1f098d9f6d38; Type: FK CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.order_item
    ADD CONSTRAINT fk_52ea1f098d9f6d38 FOREIGN KEY (order_id) REFERENCES testing.orders (order_id);


--
-- TOC entry 3429 (class 2606 OID 16613)
-- Name: subscription_plan_cart_item fk_6a21f6f6d0a5cda7; Type: FK CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.subscription_plan_cart_item
    ADD CONSTRAINT fk_6a21f6f6d0a5cda7 FOREIGN KEY (destination_entity_id) REFERENCES testing.plan (plan_id);


--
-- TOC entry 3430 (class 2606 OID 16618)
-- Name: subscription_plan_cart_item fk_6a21f6f6e9b59a59; Type: FK CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.subscription_plan_cart_item
    ADD CONSTRAINT fk_6a21f6f6e9b59a59 FOREIGN KEY (cart_item_id) REFERENCES testing.cart_item (cart_item_id) ON DELETE CASCADE;


--
-- TOC entry 3420 (class 2606 OID 16623)
-- Name: payment fk_6d28840d8d9f6d38; Type: FK CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.payment
    ADD CONSTRAINT fk_6d28840d8d9f6d38 FOREIGN KEY (order_id) REFERENCES testing.orders (order_id);


--
-- TOC entry 3413 (class 2606 OID 16628)
-- Name: oauth2_client_profile fk_9b524e1f19eb6921; Type: FK CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.oauth2_client_profile
    ADD CONSTRAINT fk_9b524e1f19eb6921 FOREIGN KEY (client_id) REFERENCES testing.oauth2_client (identifier);


--
-- TOC entry 3422 (class 2606 OID 16633)
-- Name: product_cart_item fk_9e5e93aad0a5cda7; Type: FK CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.product_cart_item
    ADD CONSTRAINT fk_9e5e93aad0a5cda7 FOREIGN KEY (destination_entity_id) REFERENCES testing.products (product_id);


--
-- TOC entry 3423 (class 2606 OID 16638)
-- Name: product_cart_item fk_9e5e93aae9b59a59; Type: FK CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.product_cart_item
    ADD CONSTRAINT fk_9e5e93aae9b59a59 FOREIGN KEY (cart_item_id) REFERENCES testing.cart_item (cart_item_id) ON DELETE CASCADE;


--
-- TOC entry 3426 (class 2606 OID 16643)
-- Name: subscription fk_a3c664d39b8ce200; Type: FK CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.subscription
    ADD CONSTRAINT fk_a3c664d39b8ce200 FOREIGN KEY (subscription_plan_id) REFERENCES testing.plan (plan_id);


--
-- TOC entry 3424 (class 2606 OID 16648)
-- Name: products fk_b3ba5a5a12469de2; Type: FK CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.products
    ADD CONSTRAINT fk_b3ba5a5a12469de2 FOREIGN KEY (category_id) REFERENCES testing.categories (category_id);


--
-- TOC entry 3425 (class 2606 OID 16653)
-- Name: products fk_b3ba5a5acb1d096a; Type: FK CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.products
    ADD CONSTRAINT fk_b3ba5a5acb1d096a FOREIGN KEY (required_subscription_id) REFERENCES testing.plan (plan_id);


--
-- TOC entry 3408 (class 2606 OID 16658)
-- Name: cart fk_ba388b7a76ed395; Type: FK CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.cart
    ADD CONSTRAINT fk_ba388b7a76ed395 FOREIGN KEY (user_id) REFERENCES testing.intrv_user (user_id);


--
-- TOC entry 3427 (class 2606 OID 16663)
-- Name: subscription_cart_item fk_c241e79b9a1887dc; Type: FK CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.subscription_cart_item
    ADD CONSTRAINT fk_c241e79b9a1887dc FOREIGN KEY (subscription_id) REFERENCES testing.plan (plan_id);


--
-- TOC entry 3428 (class 2606 OID 16668)
-- Name: subscription_cart_item fk_c241e79be9b59a59; Type: FK CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.subscription_cart_item
    ADD CONSTRAINT fk_c241e79be9b59a59 FOREIGN KEY (cart_item_id) REFERENCES testing.cart_item (cart_item_id) ON DELETE CASCADE;


--
-- TOC entry 3410 (class 2606 OID 16673)
-- Name: intrv_user fk_c2dbfc019a1887dc; Type: FK CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.intrv_user
    ADD CONSTRAINT fk_c2dbfc019a1887dc FOREIGN KEY (subscription_id) REFERENCES testing.subscription (subscription_id);


--
-- TOC entry 3415 (class 2606 OID 16678)
-- Name: oauth2_user_consent fk_c8f05d0119eb6921; Type: FK CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.oauth2_user_consent
    ADD CONSTRAINT fk_c8f05d0119eb6921 FOREIGN KEY (client_id) REFERENCES testing.oauth2_client (identifier);


--
-- TOC entry 3416 (class 2606 OID 16683)
-- Name: oauth2_user_consent fk_c8f05d01a76ed395; Type: FK CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.oauth2_user_consent
    ADD CONSTRAINT fk_c8f05d01a76ed395 FOREIGN KEY (user_id) REFERENCES testing.intrv_user (user_id);


--
-- TOC entry 3407 (class 2606 OID 16688)
-- Name: address fk_d4e6f81a76ed395; Type: FK CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.address
    ADD CONSTRAINT fk_d4e6f81a76ed395 FOREIGN KEY (user_id) REFERENCES testing.intrv_user (user_id);


--
-- TOC entry 3418 (class 2606 OID 16693)
-- Name: orders fk_e52ffdeef5b7af75; Type: FK CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.orders
    ADD CONSTRAINT fk_e52ffdeef5b7af75 FOREIGN KEY (address_id) REFERENCES testing.address (address_id);


--
-- TOC entry 3409 (class 2606 OID 16698)
-- Name: cart_item fk_f0fe25271ad5cdbf; Type: FK CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.cart_item
    ADD CONSTRAINT fk_f0fe25271ad5cdbf FOREIGN KEY (cart_id) REFERENCES testing.cart (cart_id);


--
-- TOC entry 3419 (class 2606 OID 16703)
-- Name: orders fk_f5299398a76ed395; Type: FK CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.orders
    ADD CONSTRAINT fk_f5299398a76ed395 FOREIGN KEY (user_id) REFERENCES testing.intrv_user (user_id);


--
-- TOC entry 3421 (class 2606 OID 16708)
-- Name: payment payment_user_id_fk; Type: FK CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.payment
    ADD CONSTRAINT payment_user_id_fk FOREIGN KEY (user_id) REFERENCES testing.intrv_user (user_id);


--
-- TOC entry 3431 (class 2606 OID 16713)
-- Name: user_subscription subscription_plan_id_fk; Type: FK CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.user_subscription
    ADD CONSTRAINT subscription_plan_id_fk FOREIGN KEY (plan_id) REFERENCES testing.plan (plan_id);


--
-- TOC entry 3432 (class 2606 OID 16718)
-- Name: user_subscription subscription_user_id_fk; Type: FK CONSTRAINT; Schema: testing; Owner: interview
--

ALTER TABLE ONLY testing.user_subscription
    ADD CONSTRAINT subscription_user_id_fk FOREIGN KEY (user_id) REFERENCES testing.intrv_user (user_id);


-- Completed on 2023-10-20 13:53:14 CEST

--
-- PostgreSQL database dump complete
--

