--
-- PostgreSQL database dump
--

-- Dumped from database version 15.4
-- Dumped by pg_dump version 15.4 (Ubuntu 15.4-1.pgdg22.04+1)

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

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: address; Type: TABLE; Schema: public; Owner: interview
--

CREATE TABLE public.address (
                                address_id integer NOT NULL,
                                first_name character varying(40) NOT NULL,
                                last_name character varying(40) NOT NULL,
                                address_line_1 character varying(40) NOT NULL,
                                address_line_2 character varying(40),
                                city character varying(40) NOT NULL,
                                state character varying(40) NOT NULL,
                                postal_code character varying(40) NOT NULL,
                                user_id integer
);


ALTER TABLE public.address OWNER TO interview;

--
-- Name: address_addressid_seq; Type: SEQUENCE; Schema: public; Owner: interview
--

CREATE SEQUENCE public.address_addressid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.address_addressid_seq OWNER TO interview;

--
-- Name: cart; Type: TABLE; Schema: public; Owner: interview
--

CREATE TABLE public.cart (
                             cart_id integer NOT NULL,
                             created_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
                             status character varying(25) NOT NULL,
                             updated_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
                             user_id integer
);


ALTER TABLE public.cart OWNER TO interview;

--
-- Name: COLUMN cart.created_at; Type: COMMENT; Schema: public; Owner: interview
--

COMMENT ON COLUMN public.cart.created_at IS '(DC2Type:datetime_immutable)';


--
-- Name: cart_cart_id_seq; Type: SEQUENCE; Schema: public; Owner: interview
--

CREATE SEQUENCE public.cart_cart_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.cart_cart_id_seq OWNER TO interview;

--
-- Name: cart_item; Type: TABLE; Schema: public; Owner: interview
--

CREATE TABLE public.cart_item (
                                  cart_item_id integer NOT NULL,
                                  created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
                                  updated_at timestamp without time zone,
                                  item_type character varying(30) NOT NULL,
                                  cart_id integer,
                                  quantity integer DEFAULT 1 NOT NULL
);


ALTER TABLE public.cart_item OWNER TO interview;

--
-- Name: cart_item_cart_item_id_seq; Type: SEQUENCE; Schema: public; Owner: interview
--

CREATE SEQUENCE public.cart_item_cart_item_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.cart_item_cart_item_id_seq OWNER TO interview;

--
-- Name: categories; Type: TABLE; Schema: public; Owner: interview
--

CREATE TABLE public.categories (
                                   category_id smallint NOT NULL,
                                   category_name character varying(15) NOT NULL,
                                   description text
);


ALTER TABLE public.categories OWNER TO interview;

--
-- Name: categories_categoryid_seq; Type: SEQUENCE; Schema: public; Owner: interview
--

CREATE SEQUENCE public.categories_categoryid_seq
    START WITH 10
    INCREMENT BY 1
    MINVALUE 10
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.categories_categoryid_seq OWNER TO interview;

--
-- Name: intrv_user; Type: TABLE; Schema: public; Owner: interview
--

CREATE TABLE public.intrv_user (
                                   user_id integer NOT NULL,
                                   username character varying(180) NOT NULL,
                                   pass character varying(100) NOT NULL,
                                   first_name character varying(255),
                                   last_name character varying(255),
                                   email character varying(255) NOT NULL,
                                   phone_no character varying(11),
                                   roles json,
                                   verification_code character varying(12) NOT NULL,
                                   is_verified boolean DEFAULT false NOT NULL,
                                   is_active boolean DEFAULT false NOT NULL,
                                   created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
                                   updated_at timestamp without time zone,
                                   last_login timestamp without time zone,
                                   deleted_at timestamp without time zone,
                                   subscription_id integer
);


ALTER TABLE public.intrv_user OWNER TO interview;

--
-- Name: intrv_user_user_id_seq; Type: SEQUENCE; Schema: public; Owner: interview
--

CREATE SEQUENCE public.intrv_user_user_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.intrv_user_user_id_seq OWNER TO interview;

--
-- Name: intrv_user_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: interview
--

ALTER SEQUENCE public.intrv_user_user_id_seq OWNED BY public.intrv_user.user_id;


--
-- Name: oauth2_access_token; Type: TABLE; Schema: public; Owner: interview
--

CREATE TABLE public.oauth2_access_token (
                                            identifier character(80) NOT NULL,
                                            client character varying(32) NOT NULL,
                                            expiry timestamp(0) without time zone NOT NULL,
                                            user_identifier character varying(128) DEFAULT NULL::character varying,
                                            scopes text,
                                            revoked boolean NOT NULL
);


ALTER TABLE public.oauth2_access_token OWNER TO interview;

--
-- Name: COLUMN oauth2_access_token.expiry; Type: COMMENT; Schema: public; Owner: interview
--

COMMENT ON COLUMN public.oauth2_access_token.expiry IS '(DC2Type:datetime_immutable)';


--
-- Name: COLUMN oauth2_access_token.scopes; Type: COMMENT; Schema: public; Owner: interview
--

COMMENT ON COLUMN public.oauth2_access_token.scopes IS '(DC2Type:oauth2_scope)';


--
-- Name: oauth2_authorization_code; Type: TABLE; Schema: public; Owner: interview
--

CREATE TABLE public.oauth2_authorization_code (
                                                  identifier character(80) NOT NULL,
                                                  client character varying(32) NOT NULL,
                                                  expiry timestamp(0) without time zone NOT NULL,
                                                  user_identifier character varying(128) DEFAULT NULL::character varying,
                                                  scopes text,
                                                  revoked boolean NOT NULL
);


ALTER TABLE public.oauth2_authorization_code OWNER TO interview;

--
-- Name: COLUMN oauth2_authorization_code.expiry; Type: COMMENT; Schema: public; Owner: interview
--

COMMENT ON COLUMN public.oauth2_authorization_code.expiry IS '(DC2Type:datetime_immutable)';


--
-- Name: COLUMN oauth2_authorization_code.scopes; Type: COMMENT; Schema: public; Owner: interview
--

COMMENT ON COLUMN public.oauth2_authorization_code.scopes IS '(DC2Type:oauth2_scope)';


--
-- Name: oauth2_client; Type: TABLE; Schema: public; Owner: interview
--

CREATE TABLE public.oauth2_client (
                                      identifier character varying(32) NOT NULL,
                                      name character varying(128) NOT NULL,
                                      secret character varying(128) DEFAULT NULL::character varying,
                                      redirect_uris text,
                                      grants text,
                                      scopes text,
                                      active boolean NOT NULL,
                                      allow_plain_text_pkce boolean DEFAULT false NOT NULL
);


ALTER TABLE public.oauth2_client OWNER TO interview;

--
-- Name: COLUMN oauth2_client.redirect_uris; Type: COMMENT; Schema: public; Owner: interview
--

COMMENT ON COLUMN public.oauth2_client.redirect_uris IS '(DC2Type:oauth2_redirect_uri)';


--
-- Name: COLUMN oauth2_client.grants; Type: COMMENT; Schema: public; Owner: interview
--

COMMENT ON COLUMN public.oauth2_client.grants IS '(DC2Type:oauth2_grant)';


--
-- Name: COLUMN oauth2_client.scopes; Type: COMMENT; Schema: public; Owner: interview
--

COMMENT ON COLUMN public.oauth2_client.scopes IS '(DC2Type:oauth2_scope)';


--
-- Name: oauth2_client_profile; Type: TABLE; Schema: public; Owner: interview
--

CREATE TABLE public.oauth2_client_profile (
                                              id integer NOT NULL,
                                              client_id character varying(32) NOT NULL,
                                              name character varying(255) NOT NULL,
                                              description text
);


ALTER TABLE public.oauth2_client_profile OWNER TO interview;

--
-- Name: oauth2_client_profile_id_seq; Type: SEQUENCE; Schema: public; Owner: interview
--

CREATE SEQUENCE public.oauth2_client_profile_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.oauth2_client_profile_id_seq OWNER TO interview;

--
-- Name: oauth2_refresh_token; Type: TABLE; Schema: public; Owner: interview
--

CREATE TABLE public.oauth2_refresh_token (
                                             identifier character(80) NOT NULL,
                                             access_token character(80) DEFAULT NULL::bpchar,
                                             expiry timestamp(0) without time zone NOT NULL,
                                             revoked boolean NOT NULL
);


ALTER TABLE public.oauth2_refresh_token OWNER TO interview;

--
-- Name: COLUMN oauth2_refresh_token.expiry; Type: COMMENT; Schema: public; Owner: interview
--

COMMENT ON COLUMN public.oauth2_refresh_token.expiry IS '(DC2Type:datetime_immutable)';


--
-- Name: oauth2_user_consent; Type: TABLE; Schema: public; Owner: interview
--

CREATE TABLE public.oauth2_user_consent (
                                            id integer NOT NULL,
                                            client_id character varying(32) NOT NULL,
                                            user_id integer NOT NULL,
                                            created timestamp(0) without time zone NOT NULL,
                                            expires timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
                                            scopes text,
                                            ip_address character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE public.oauth2_user_consent OWNER TO interview;

--
-- Name: COLUMN oauth2_user_consent.created; Type: COMMENT; Schema: public; Owner: interview
--

COMMENT ON COLUMN public.oauth2_user_consent.created IS '(DC2Type:datetime_immutable)';


--
-- Name: COLUMN oauth2_user_consent.expires; Type: COMMENT; Schema: public; Owner: interview
--

COMMENT ON COLUMN public.oauth2_user_consent.expires IS '(DC2Type:datetime_immutable)';


--
-- Name: COLUMN oauth2_user_consent.scopes; Type: COMMENT; Schema: public; Owner: interview
--

COMMENT ON COLUMN public.oauth2_user_consent.scopes IS '(DC2Type:simple_array)';


--
-- Name: oauth2_user_consent_id_seq; Type: SEQUENCE; Schema: public; Owner: interview
--

CREATE SEQUENCE public.oauth2_user_consent_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.oauth2_user_consent_id_seq OWNER TO interview;

--
-- Name: order_item; Type: TABLE; Schema: public; Owner: interview
--

CREATE TABLE public.order_item (
                                   order_id integer,
                                   cart_item_entity json NOT NULL,
                                   order_item_id integer NOT NULL,
                                   cart_item_type character varying(25) NOT NULL
);


ALTER TABLE public.order_item OWNER TO interview;

--
-- Name: order_item_order_item_id_seq; Type: SEQUENCE; Schema: public; Owner: interview
--

CREATE SEQUENCE public.order_item_order_item_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.order_item_order_item_id_seq OWNER TO interview;

--
-- Name: order_orderid_seq; Type: SEQUENCE; Schema: public; Owner: interview
--

CREATE SEQUENCE public.order_orderid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.order_orderid_seq OWNER TO interview;

--
-- Name: order_pending; Type: TABLE; Schema: public; Owner: interview
--

CREATE TABLE public.order_pending (
                                      order_id integer NOT NULL,
                                      user_id integer NOT NULL,
                                      cart_id integer NOT NULL
);


ALTER TABLE public.order_pending OWNER TO interview;

--
-- Name: orders; Type: TABLE; Schema: public; Owner: interview
--

CREATE TABLE public.orders (
                               order_id integer NOT NULL,
                               status character varying(25) NOT NULL,
                               created_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
                               updated_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
                               user_id integer,
                               amount integer NOT NULL,
                               address_id integer
);


ALTER TABLE public.orders OWNER TO interview;

--
-- Name: payment; Type: TABLE; Schema: public; Owner: interview
--

CREATE TABLE public.payment (
                                user_id integer,
                                payment_id integer NOT NULL,
                                operation_number character varying(40) NOT NULL,
                                operation_type character varying(40) NOT NULL,
                                amount integer NOT NULL,
                                payment_date timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
                                created_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
                                order_id integer,
                                status character varying(25) NOT NULL
);


ALTER TABLE public.payment OWNER TO interview;

--
-- Name: payment_paymentid_seq; Type: SEQUENCE; Schema: public; Owner: interview
--

CREATE SEQUENCE public.payment_paymentid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.payment_paymentid_seq OWNER TO interview;

--
-- Name: plan; Type: TABLE; Schema: public; Owner: interview
--

CREATE TABLE public.plan (
                             plan_id integer NOT NULL,
                             plan_name character varying(255) NOT NULL,
                             description text NOT NULL,
                             is_active boolean DEFAULT false NOT NULL,
                             created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
                             updated_at timestamp without time zone,
                             deleted_at timestamp without time zone,
                             unit_price smallint NOT NULL,
                             is_visible boolean DEFAULT false NOT NULL,
                             tier smallint NOT NULL
);


ALTER TABLE public.plan OWNER TO interview;

--
-- Name: plan_plan_id_seq; Type: SEQUENCE; Schema: public; Owner: interview
--

CREATE SEQUENCE public.plan_plan_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.plan_plan_id_seq OWNER TO interview;

--
-- Name: plan_plan_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: interview
--

ALTER SEQUENCE public.plan_plan_id_seq OWNED BY public.plan.plan_id;


--
-- Name: product_cart_item; Type: TABLE; Schema: public; Owner: interview
--

CREATE TABLE public.product_cart_item (
                                          cart_item_id integer NOT NULL,
                                          destination_entity_id integer
);


ALTER TABLE public.product_cart_item OWNER TO interview;

--
-- Name: products; Type: TABLE; Schema: public; Owner: interview
--

CREATE TABLE public.products (
                                 product_id integer NOT NULL,
                                 product_name character varying(40) NOT NULL,
                                 supplier_id smallint,
                                 category_id smallint,
                                 quantity_per_unit character varying(20),
                                 unit_price smallint NOT NULL,
                                 units_in_stock smallint,
                                 units_on_order smallint,
                                 required_subscription_id integer
);


ALTER TABLE public.products OWNER TO interview;

--
-- Name: products_productid_seq; Type: SEQUENCE; Schema: public; Owner: interview
--

CREATE SEQUENCE public.products_productid_seq
    START WITH 80
    INCREMENT BY 1
    MINVALUE 80
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.products_productid_seq OWNER TO interview;

--
-- Name: subscription; Type: TABLE; Schema: public; Owner: interview
--

CREATE TABLE public.subscription (
                                     subscription_id integer NOT NULL,
                                     is_active boolean DEFAULT false NOT NULL,
                                     created_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
                                     starts_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
                                     ends_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
                                     subscription_plan_id integer,
                                     tier smallint
);


ALTER TABLE public.subscription OWNER TO interview;

--
-- Name: subscription_cart_item; Type: TABLE; Schema: public; Owner: interview
--

CREATE TABLE public.subscription_cart_item (
                                               cart_item_id integer NOT NULL,
                                               subscription_id integer
);


ALTER TABLE public.subscription_cart_item OWNER TO interview;

--
-- Name: subscription_plan_cart_item; Type: TABLE; Schema: public; Owner: interview
--

CREATE TABLE public.subscription_plan_cart_item (
                                                    cart_item_id integer NOT NULL,
                                                    destination_entity_id integer
);


ALTER TABLE public.subscription_plan_cart_item OWNER TO interview;

--
-- Name: subscription_subscription_id_seq; Type: SEQUENCE; Schema: public; Owner: interview
--

CREATE SEQUENCE public.subscription_subscription_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.subscription_subscription_id_seq OWNER TO interview;

--
-- Name: suppliers; Type: TABLE; Schema: public; Owner: interview
--

CREATE TABLE public.suppliers (
                                  supplier_id smallint NOT NULL,
                                  company_name character varying(40) NOT NULL
);


ALTER TABLE public.suppliers OWNER TO interview;

--
-- Name: suppliers_supplier_id_seq; Type: SEQUENCE; Schema: public; Owner: interview
--

CREATE SEQUENCE public.suppliers_supplier_id_seq
    START WITH 10
    INCREMENT BY 1
    MINVALUE 10
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.suppliers_supplier_id_seq OWNER TO interview;

--
-- Name: user_subscription; Type: TABLE; Schema: public; Owner: interview
--

CREATE TABLE public.user_subscription (
                                          subscription_id integer NOT NULL,
                                          user_id integer NOT NULL,
                                          plan_id integer NOT NULL,
                                          purchased_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
                                          started_at timestamp without time zone,
                                          ends_at timestamp without time zone
);


ALTER TABLE public.user_subscription OWNER TO interview;

--
-- Name: user_subscription_subscription_id_seq; Type: SEQUENCE; Schema: public; Owner: interview
--

CREATE SEQUENCE public.user_subscription_subscription_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_subscription_subscription_id_seq OWNER TO interview;

--
-- Name: user_subscription_subscription_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: interview
--

ALTER SEQUENCE public.user_subscription_subscription_id_seq OWNED BY public.user_subscription.subscription_id;


--
-- Name: voucher; Type: TABLE; Schema: public; Owner: interview
--

CREATE TABLE public.voucher (
                                voucher_id integer NOT NULL,
                                code character varying(25) NOT NULL,
                                valid_for character varying(12),
                                created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.voucher OWNER TO interview;

--
-- Name: voucher_voucher_id_seq; Type: SEQUENCE; Schema: public; Owner: interview
--

CREATE SEQUENCE public.voucher_voucher_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.voucher_voucher_id_seq OWNER TO interview;

--
-- Name: voucher_voucher_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: interview
--

ALTER SEQUENCE public.voucher_voucher_id_seq OWNED BY public.voucher.voucher_id;


--
-- Name: user_subscription subscription_id; Type: DEFAULT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.user_subscription ALTER COLUMN subscription_id SET DEFAULT nextval('public.user_subscription_subscription_id_seq'::regclass);


--
-- Name: voucher voucher_id; Type: DEFAULT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.voucher ALTER COLUMN voucher_id SET DEFAULT nextval('public.voucher_voucher_id_seq'::regclass);


--
-- Data for Name: address; Type: TABLE DATA; Schema: public; Owner: interview
--

COPY public.address (address_id, first_name, last_name, address_line_1, address_line_2, city, state, postal_code, user_id) FROM stdin;
1	Raf	Sal	Tw	asd	kielce	swietokrzyskie	20-085	\N
14	Raf	Sal	Tw	\N	kielce	swietokrzyskie	20-085	\N
15	Raf	Sal	Tw	\N	kielce	swietokrzyskie	20-085	\N
16	Raf	Sal	Tw	\N	kielce	swietokrzyskie	20-085	1
18	r	s	tttwww	\N	k	s	p	1
\.


--
-- Data for Name: cart; Type: TABLE DATA; Schema: public; Owner: interview
--

COPY public.cart (cart_id, created_at, status, updated_at, user_id) FROM stdin;
14	2023-08-18 20:20:26	confirmed	2023-08-21 16:55:26	1
15	2023-08-21 16:58:20	confirmed	2023-08-22 16:14:11	1
16	2023-08-22 17:07:43	confirmed	2023-08-22 18:23:59	1
17	2023-08-22 18:41:15	confirmed	2023-08-22 18:42:44	1
18	2023-08-23 11:35:32	confirmed	2023-08-23 15:19:51	1
19	2023-08-23 19:25:48	confirmed	2023-08-23 19:25:54	1
20	2023-08-23 20:21:32	confirmed	2023-08-28 22:05:36	1
\.


--
-- Data for Name: cart_item; Type: TABLE DATA; Schema: public; Owner: interview
--

COPY public.cart_item (cart_item_id, created_at, updated_at, item_type, cart_id, quantity) FROM stdin;
41	2023-08-17 19:36:48	\N	productcartitem	\N	1
42	2023-08-17 19:37:30	\N	productcartitem	\N	1
43	2023-08-17 19:38:32	\N	productcartitem	\N	1
50	2023-08-18 20:20:26	\N	subscriptionplancartitem	14	1
51	2023-08-18 20:20:32	\N	productcartitem	14	1
52	2023-08-18 20:20:36	\N	productcartitem	14	1
53	2023-08-18 20:20:41	\N	productcartitem	14	5
57	2023-08-21 19:59:05	\N	subscriptionplancartitem	15	2
56	2023-08-21 19:45:38	\N	subscriptionplancartitem	15	8
58	2023-08-21 19:59:13	\N	subscriptionplancartitem	15	6
90	2023-08-22 14:13:37	\N	productcartitem	15	5
91	2023-08-22 17:07:43	\N	subscriptionplancartitem	16	1
92	2023-08-22 17:07:48	\N	productcartitem	16	1
93	2023-08-22 18:41:15	\N	subscriptionplancartitem	17	1
94	2023-08-22 18:41:19	\N	productcartitem	17	1
95	2023-08-22 18:41:25	\N	productcartitem	17	1
96	2023-08-22 18:41:32	\N	productcartitem	17	1
102	2023-08-23 15:03:11	\N	subscriptionplancartitem	18	1
103	2023-08-23 15:03:24	\N	productcartitem	18	1
104	2023-08-23 19:25:48	\N	subscriptionplancartitem	19	1
\.


--
-- Data for Name: categories; Type: TABLE DATA; Schema: public; Owner: interview
--

COPY public.categories (category_id, category_name, description) FROM stdin;
1	Beverages	Soft drinks, coffees, teas, beers, and ales
2	Condiments	Sweet and savory sauces, relishes, spreads, and seasonings
3	Confections	Desserts, candies, and sweet breads
4	Dairy Products	Cheeses
5	Grains/Cereals	Breads, crackers, pasta, and cereal
6	Meat/Poultry	Prepared meats
7	Produce	Dried fruit and bean curd
8	Seafood	Seaweed and fish
\.


--
-- Data for Name: intrv_user; Type: TABLE DATA; Schema: public; Owner: interview
--

COPY public.intrv_user (user_id, username, pass, first_name, last_name, email, phone_no, roles, verification_code, is_verified, is_active, created_at, updated_at, last_login, deleted_at, subscription_id) FROM stdin;
2	another_user	$2a$13$5NmP4C6LG2wUzrkvZ/uVdue7QlZQNP/2FTFHo3/6QKmfWJD7YpAIa	another_user_firstname	another_lastname	user@user.com	987654321	{\n        "roles": "ROLE_USER"\n    }	176278	t	t	2023-05-01 22:14:09	\N	2023-05-02 22:14:09	\N	\N
3	bad_user	$2a$13$5NmP4C6LG2wUzrkvZ/uVdue7QlZQNP/2FTFHo3/6QKmfWJD7YpAIa	bad, bad,	very bad user	trickortreat@strange.com	123333777	{\n        "roles": "ROLE_USER"\n    }	347816	t	f	2023-05-01 04:33:20	2023-05-01 19:11:34	2023-05-02 15:47:04	2023-05-02 17:12:48	\N
5	vip	$2a$13$5NmP4C6LG2wUzrkvZ/uVdue7QlZQNP/2FTFHo3/6QKmfWJD7YpAIa	very important	user	memberplus@vip.com	777333444	{\n        "roles": [\n            "ROLE_VIP",\n            "ROLE_USER"\n        ]\n    }	642103	t	t	2023-05-01 04:33:20	\N	2023-05-02 15:47:04	\N	\N
6	rafsal	$2a$13$L4zZVIkX6o1KQLjCpwwxQ.8/cVm9ICx44AxkU.bHqJ11xFO4jvmT6	\N	\N	rafsal@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	OXSmjb	f	f	2023-05-19 23:30:33	2023-05-19 23:31:54	2023-05-19 23:31:54	\N	\N
8	XhuytkQZ	$2a$13$VGdoX2zgpMc0mATOxJzU2uY0GfTbCSj5TWXDWT2T/OTALGvhGsZhy	\N	\N	XhuytkQZ@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	ZBLgyz	f	f	2023-05-19 23:37:02	2023-05-19 23:37:03	2023-05-19 23:37:03	\N	\N
9	xfKcHzAQ	$2a$13$sSPqPJMMpi/4UdxpahYNOeZ4hXqZY6rmBis5AnLJtGM23tHHBc28W	\N	\N	xfKcHzAQ@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	tEwKvO	f	f	2023-05-19 23:38:05	2023-05-19 23:38:05	2023-05-19 23:38:05	\N	\N
10	tTGKsjQN	$2a$13$Z/XrRCc4ZI08WScWaojNW.e8luA/cmRwDY2pkpTGc22wMLSmS70Zm	\N	\N	tTGKsjQN@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	sizXxy	f	f	2023-05-19 23:38:12	2023-05-19 23:38:13	2023-05-19 23:38:13	\N	\N
11	YeehRLeX	$2a$13$yo8ifJmbMacJ0XyPbHhOCuI8a7MewaW.0L9olyMQ2Y1/isdusb1pe	\N	\N	YeehRLeX@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	fntbgo	f	f	2023-05-19 23:39:25	2023-05-19 23:39:25	2023-05-19 23:39:25	\N	\N
12	oWInjirT	$2a$13$MJwB2NYkwNJHND/CD2o5Vun/DaBtKAzSyWMJ7EF2EU1QlCdnoAOsC	\N	\N	oWInjirT@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	holEiY	f	f	2023-05-22 11:31:49	2023-05-22 11:31:49	2023-05-22 11:31:49	\N	\N
13	dcgpNuQZ	$2a$13$51D3D1fa3la9l9f8YyBtjeHG7k0Lc9Hozv0xMfQSxavDvga/riWmG	\N	\N	dcgpNuQZ@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	eRaQlh	f	f	2023-05-22 11:34:10	2023-05-22 11:34:10	2023-05-22 11:34:10	\N	\N
14	PytyKNpU	$2a$13$MUDeyizw36l7MxdFpRP0yOsqMPNHcRZoWLxOTCRARYpxjD7XOr2TO	\N	\N	PytyKNpU@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	kpEogF	f	f	2023-05-22 11:38:51	2023-05-22 11:38:52	2023-05-22 11:38:52	\N	\N
15	YEJBdARM	$2a$13$BF43Vnnd3pP8/ncDHIHufOPHfVgwjVQXu81nQkHMHacS5q31QYDwG	\N	\N	YEJBdARM@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	sPBKVK	f	f	2023-05-22 11:40:55	2023-05-22 11:40:55	2023-05-22 11:40:55	\N	\N
16	cUWejYWv	$2a$13$uin75bnGkvF0YmM778N7yO6BNDCUjpA.mFpwBVvgEpnd9..v6iGgC	\N	\N	cUWejYWv@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	EPlqZK	f	f	2023-05-22 11:45:21	2023-05-22 11:45:21	2023-05-22 11:45:21	\N	\N
17	IWBQcesa	$2a$13$NGvChpYxY/ppDbnhqOdaQ.xOYqoMEtArG8RRd5WPtz4tB6Gb9IA2m	\N	\N	IWBQcesa@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	MHxxBT	f	f	2023-05-22 11:45:57	2023-05-22 11:45:58	2023-05-22 11:45:58	\N	\N
18	OrLNaDcm	$2a$13$Pkwqymj/./WbWCG46WwEYeDH8JFWUCDwsZq0JoYobwrhxo8ysO94e	\N	\N	OrLNaDcm@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	lucTNW	f	f	2023-05-22 11:46:47	2023-05-22 11:46:47	2023-05-22 11:46:47	\N	\N
19	TrNazrGn	$2a$13$1t27jWwQnCdzlDpsb/940u0KaOF54tr9u/Ts7OcZ38SqGxBNfFWjG	\N	\N	TrNazrGn@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	CrJlHV	f	f	2023-05-22 11:48:51	2023-05-22 11:48:51	2023-05-22 11:48:51	\N	\N
20	eDsxWQWv	$2a$13$Bk2CTP2OThAwzNzuH4ftMO4onJlqAXCyPi328obT/C5fzhXDnYGlW	\N	\N	eDsxWQWv@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	hkCOHS	f	f	2023-05-22 11:50:08	2023-05-22 11:50:08	2023-05-22 11:50:08	\N	\N
21	IAnjTNao	$2a$13$W6kViSvl4g32C/s/EIYVEOmlNhF06XfqZwV73o.4W8fNH9SacYbC2	\N	\N	IAnjTNao@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	mNMTiZ	f	f	2023-05-22 11:58:15	2023-05-22 11:58:15	2023-05-22 11:58:15	\N	\N
22	WimEjAZL	$2a$13$30yKE4vqTAimTNoZ7ZFQA.En7sfkuCh0hK1BxQ/SOeqNSH8OmL.yW	\N	\N	WimEjAZL@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	svZHwo	f	f	2023-05-22 12:02:20	2023-05-22 12:02:20	2023-05-22 12:02:20	\N	\N
23	DEPEzJxl	$2a$13$/vCeaqorr18zJDQzCNFKYOahMYKn4g6wp8l9H2Vy2A1gjEezELx5O	\N	\N	DEPEzJxl@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	OeHjnb	f	f	2023-05-22 12:03:11	2023-05-22 12:03:12	2023-05-22 12:03:12	\N	\N
24	fQwNLgmW	$2a$13$zkkhlHRPQVq7R3V2DuLfk.4EXZzOt.B0lHggRLthVzaSfsZtFOyOa	\N	\N	fQwNLgmW@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	XpcaFx	f	f	2023-05-22 12:03:49	2023-05-22 12:03:49	2023-05-22 12:03:49	\N	\N
25	rTBlHytA	$2a$13$0HV5EIRcGSmvpH3QsMGQHuv0sVsejZihDjjgXawpBbrZThngX2Nla	\N	\N	rTBlHytA@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	CYmKmU	f	f	2023-05-22 12:04:16	2023-05-22 12:04:17	2023-05-22 12:04:17	\N	\N
26	fkPclayr	$2a$13$U6p0qVNOH0LCQTKz92sNYOX4hrH1WtLcjuJTKn.mF6IiO.CMK/8YC	\N	\N	fkPclayr@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	SHeusU	f	f	2023-05-22 12:27:09	2023-05-22 12:27:10	2023-05-22 12:27:10	\N	\N
27	CUPRTqtR	$2a$13$/dWp1cMTRmOhn7wPaHXRmuj3Z5fjqBGdfD8P6LdCmyDed1XwUS9.G	\N	\N	CUPRTqtR@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	YBQfXQ	f	f	2023-05-22 12:28:55	2023-05-22 12:28:55	2023-05-22 12:28:55	\N	\N
28	QBaViwvv	$2a$13$k7sDNK6VLbYf7hTFvvmKIOUyf4ge2JzjguhtMHrXlovCH4s0Q0MCC	\N	\N	QBaViwvv@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	OxXdwz	f	f	2023-05-22 12:29:48	2023-05-22 12:29:48	2023-05-22 12:29:48	\N	\N
29	dgIIEPea	$2a$13$wgHwRoISSf6Gnf4OTAeg7eo7KajnWCR7qt4hSPgLOmivGKXFplyrm	\N	\N	dgIIEPea@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	iyAHgM	f	f	2023-05-22 12:30:30	2023-05-22 12:30:31	2023-05-22 12:30:31	\N	\N
30	rqohkFsD	$2a$13$.F5TOol316l6O/ACPPdeGuD/qB9j6gihs3xXOUd5.sKfQlPeudOQi	\N	\N	rqohkFsD@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	gQvhOk	f	f	2023-05-22 12:31:47	2023-05-22 12:31:47	2023-05-22 12:31:47	\N	\N
31	ujqzEqLe	$2a$13$Gz43pXVg.HPxlyvR9Ju7I.S0wJIBxfaTijtS2LbIyyexWHjLEid7W	\N	\N	ujqzEqLe@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	UJKiqg	f	f	2023-05-22 12:33:18	2023-05-22 12:33:18	2023-05-22 12:33:18	\N	\N
32	NTyemSFU	$2a$13$NlkqtBzqQ/hLMLfPP6dhPe37ja1qP9Fpgk810AudSc1Xrr8N/3iRm	\N	\N	NTyemSFU@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	GEAXKO	t	f	2023-05-22 12:34:05	2023-05-22 12:34:05	\N	\N	\N
33	GJgAqXbo	$2a$13$BXIq71glwSEFKJBMLxuKJerXWBIsbGa.CFfGnt/.34RvurWlWDRFe	\N	\N	GJgAqXbo@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	WSknwp	t	f	2023-05-22 12:38:55	2023-05-22 12:38:55	\N	\N	\N
34	qBeZvsvN	$2a$13$Ff9fRvoN7QB37J9MXHynRuNH8f8IPrfzSkZGBI1WqamsBd0h61xJa	\N	\N	qBeZvsvN@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	SCxzko	t	f	2023-05-22 12:40:15	2023-05-22 12:40:15	\N	\N	\N
35	esTJCGEu	$2a$13$9Ll9r4ZgHmSsZ/EVITArzOqcE0hmr82BS3LQQ4.xf8MMY9RXxledm	\N	\N	esTJCGEu@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	kuSici	t	f	2023-05-22 12:41:14	2023-05-22 12:41:14	\N	\N	\N
36	wKUuVGhM	$2a$13$N.Dl2OIDCGeSL94bPVdYfuqdbv.Zg1CbR8cHDokfX/i4wKEAn1oDO	\N	\N	wKUuVGhM@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	sbqxhv	t	f	2023-05-22 12:41:53	2023-05-22 12:41:53	\N	\N	\N
37	mOoRfpLg	$2a$13$FvdalH9OumJb9cROe9xIFevCwty7WbFVep68Age3Fhg8xBV6HXwfu	\N	\N	mOoRfpLg@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	rEJjhL	t	f	2023-05-22 12:42:15	2023-05-22 12:42:15	\N	\N	\N
38	xepCwgbN	$2a$13$yI1pvrOQzK6TbpktdhjTI.iPxc4TXbhnIasaneERlb5/fAdcyo1rG	\N	\N	xepCwgbN@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	MuJmhb	t	f	2023-05-22 12:47:22	2023-05-22 12:47:22	2023-05-22 12:47:22	\N	\N
39	kfoibJtl	$2a$13$mvl/KERxriFVsp97OeAL8.Ra3fAjh6UVnrMDKUUUJ2mLcTC6YoY5e	\N	\N	kfoibJtl@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	bienSP	t	t	2023-05-22 12:48:32	2023-05-22 12:48:33	2023-05-22 12:48:33	\N	\N
40	vdIZlrZb	$2a$13$Xs9ldX.qxSu2B5m6UiDO/u7/uECOxZWfQJ/faV5lD49llWYSVQXsm	\N	\N	vdIZlrZb@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	NyacRv	f	f	2023-05-22 16:27:26	2023-05-22 16:27:26	\N	\N	\N
41	CBrpLIBy	$2a$13$4aML.dO35OlV6pl84JepuOgnKzTmHpxn93HXFU3b/uSWQUKFQoqSu	\N	\N	CBrpLIBy@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	cXHPed	f	f	2023-05-22 16:27:26	2023-05-22 16:27:26	\N	\N	\N
42	LRXJeSMp	$2a$13$MHB0Etx2kw8OUxXBUno8s.bp1cf/NOpi318K/rtrKC0loA6CI7Jvu	\N	\N	LRXJeSMp@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	rnVWdw	f	f	2023-05-22 16:27:26	2023-05-22 16:27:26	\N	\N	\N
43	rWTcANbD	$2a$13$o0eVoJxgQtpHy5nBFLIIj.1KolCItZqg53y5RhrN/VZBygzlUWqUy	\N	\N	rWTcANbD@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	TdQpGM	f	f	2023-05-22 16:27:27	2023-05-22 16:27:27	\N	\N	\N
44	HmBCtOHg	$2a$13$XxXys9jKh0oXeDjzd5843e.oiYzdQEp7GGuOUPrIa0JnAv.8oJj4m	\N	\N	HmBCtOHg@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	NFmrlf	f	f	2023-05-22 16:27:26	2023-05-22 16:27:27	\N	\N	\N
45	qTqRQllr	$2a$13$aU/6R0g89N1zfg0.vPXFkOcm.wC1LOkUKyCSbvgpmAH57//uKPcnm	\N	\N	qTqRQllr@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	SBBNtf	f	f	2023-05-22 16:27:26	2023-05-22 16:27:27	\N	\N	\N
46	bwecyjBu	$2a$13$FnsGGxwf2pv65FXaZPC4u.sLk0B5/rmu2OPP46ahrrC9S1EDY8F4u	\N	\N	bwecyjBu@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	leaRaU	f	f	2023-05-22 16:27:27	2023-05-22 16:27:27	\N	\N	\N
47	KHsBoeqr	$2a$13$9UgbpjtfZFxgCmU01wEliOF7ru5nDOUSIyrgojeuYV7SDyLAAK/LS	\N	\N	KHsBoeqr@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	FyEBUb	f	f	2023-05-22 16:27:27	2023-05-22 16:27:27	\N	\N	\N
48	IqofCsMN	$2a$13$i8bu4fJzcNW7UQIo8aiNI.B8wjjzeWuRnvd/Y6bM0uCFszKA.CwyS	\N	\N	IqofCsMN@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	cEnwya	f	f	2023-05-22 16:27:27	2023-05-22 16:27:27	\N	\N	\N
49	trBrNRKz	$2a$13$OHprwxZevnzl.kXJhGWxVe.JiUU1wGNYZwsWvSUfZ39klj.MNZRM2	\N	\N	trBrNRKz@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	uJCCCz	f	f	2023-05-22 16:27:27	2023-05-22 16:27:27	\N	\N	\N
50	tcOORZsV	$2a$13$/KCGkpQ3hGpIgjBwd/S0K.A9PwPpScsAbVaB8.Wi8ALNO3mSsrtbC	\N	\N	tcOORZsV@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	dbZPwe	f	f	2023-05-22 16:27:27	2023-05-22 16:27:27	\N	\N	\N
51	TYsVSmbj	$2a$13$R/Yqd9TpfZScPrIllKQofOKWluvN9p96xoKLwgSWqulHJY0.FdEBm	\N	\N	TYsVSmbj@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	tyjyBT	f	f	2023-05-22 16:27:27	2023-05-22 16:27:27	\N	\N	\N
52	ySCjtJFC	$2a$13$8OgEXcU5SZQtt8TERkaXDOVEGnhJ8hwEF/96VIURVACBWuyBS5TgC	\N	\N	ySCjtJFC@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	jQXGsE	f	f	2023-05-22 16:27:27	2023-05-22 16:27:27	\N	\N	\N
53	kITcaZuA	$2a$13$imKAjaWdFw2aI2Y71xjOF.Hx5kCpsLYTo.vfNjAVVBnCVu.rXtGvO	\N	\N	kITcaZuA@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	kecJWI	f	f	2023-05-22 16:27:27	2023-05-22 16:27:27	\N	\N	\N
54	vVyGUidi	$2a$13$/ga/mG1n43bC3sz5UVqykOq.0MXEzmhxt7T45jT5Elw1Dob6cleoi	\N	\N	vVyGUidi@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	fYvuop	f	f	2023-05-22 16:27:27	2023-05-22 16:27:27	\N	\N	\N
55	HXVsTOJG	$2a$13$p0QoZ7msrIJGMAcjvoyMVeL7h2NRAQn9R4rp6zsFB0zwOLbv4ab42	\N	\N	HXVsTOJG@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	ldpcmU	f	f	2023-05-22 16:27:27	2023-05-22 16:27:27	\N	\N	\N
56	CRpYdUhb	$2a$13$gR2YoXJxouI7mCaNfboiA.70Xs4Q0LHK8PX/mIqIkDOkMNywuZwri	\N	\N	CRpYdUhb@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	rNpcfB	f	f	2023-05-22 16:27:27	2023-05-22 16:27:27	\N	\N	\N
57	nPTGQJwI	$2a$13$pwc9sDiG1fSbXNvnQzH7qeUuQllzjLrsNF02cgbZERV2uK0kGbCDW	\N	\N	nPTGQJwI@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	fEfCiO	f	f	2023-05-22 16:27:27	2023-05-22 16:27:27	\N	\N	\N
58	aMCeLsxf	$2a$13$P4Mq0F5ltIh57O9a0osGDu/AEoXg.O23G.AVm/ZidajkJhS1C3HLe	\N	\N	aMCeLsxf@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	MxJmIN	f	f	2023-05-22 16:27:27	2023-05-22 16:27:27	\N	\N	\N
59	gwhPdHBQ	$2a$13$TJeW2MmbkbSHmnd29NS1OOodC6xIii7lCB3fijwadSQrdBPFL3CvS	\N	\N	gwhPdHBQ@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	zNleeT	f	f	2023-05-22 16:27:27	2023-05-22 16:27:27	\N	\N	\N
60	KKAqTheB	$2a$13$4zbfeFN3YK9p6vmlXMlvVezRDtPL/NgGVkC4Pr4Unq4eYuuNPkgiW	\N	\N	KKAqTheB@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	fRDUGb	f	f	2023-05-22 16:27:27	2023-05-22 16:27:27	\N	\N	\N
61	vajrVjGm	$2a$13$eqXXCd2l.eESXBwjS68ZZepp4ZPCBZ6HiARVszDVKVa9p3V26VInS	\N	\N	vajrVjGm@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	qyKMPm	f	f	2023-05-22 16:27:27	2023-05-22 16:27:27	\N	\N	\N
62	IuATaFtM	$2a$13$WH1mwTVAHRsBheebF64CHev6pPBKOgzSdzUBJL2iB1bRIiduSgWcK	\N	\N	IuATaFtM@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	pVeukN	f	f	2023-05-22 16:27:27	2023-05-22 16:27:27	\N	\N	\N
63	YwSIwZmU	$2a$13$tT2RodoMRYkpVsiyZg.15utQrQZU2s6Dg.HAwOhd14SVtxDlSsUbS	\N	\N	YwSIwZmU@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	OBAumH	f	f	2023-05-22 16:27:27	2023-05-22 16:27:27	\N	\N	\N
64	omeqkQEM	$2a$13$zX/BGHvUYvciaA0VtxWoNOpcIs4vwVvzgk68Qa1asIuCSMDA1GZDS	\N	\N	omeqkQEM@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	djJQqm	f	f	2023-05-22 16:27:27	2023-05-22 16:27:27	\N	\N	\N
65	ZVcifskV	$2a$13$nAhCyPDiA.cvFtIVu37hDOk.bHN2XMn5sCJq.4qOrW.a1oLnv1WlK	\N	\N	ZVcifskV@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	daYmaV	f	f	2023-05-22 16:27:27	2023-05-22 16:27:27	\N	\N	\N
66	gROSzVVT	$2a$13$TU3REMSuwSQHEV6ph6r6vufDxUQ0X5.buh00tePYyDyaB.wB5FFem	\N	\N	gROSzVVT@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	Oewxxi	f	f	2023-05-22 16:27:27	2023-05-22 16:27:27	\N	\N	\N
67	NptUgmRw	$2a$13$rKbRp5Crnk3sd0N/DYtmwOqDdo6nOG7P/ErDNApcOTT/u3xQ93IRa	\N	\N	NptUgmRw@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	paOPEN	f	f	2023-05-22 16:27:27	2023-05-22 16:27:27	\N	\N	\N
68	LSNoSpwr	$2a$13$a1vUMDZGvagt10FTqf6aG.z8EnNqi7j75Kc8o5rs.d.pcv1H2NTay	\N	\N	LSNoSpwr@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	KZcZec	f	f	2023-05-22 16:27:27	2023-05-22 16:27:27	\N	\N	\N
69	jxLrlXNs	$2a$13$JM5ckoijwflTSZaWh9D0Zuti2GX0j3dOOJ10oQAFYKrXT1sIpagSu	\N	\N	jxLrlXNs@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	gZkrIS	f	f	2023-05-22 16:27:27	2023-05-22 16:27:27	\N	\N	\N
70	UlryzEth	$2a$13$6ZhdBV5rWjwkZbwxL1zieuKd16ukEY96WAR3ZW2VihXnztMtZL9lW	\N	\N	UlryzEth@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	gPqbAu	f	f	2023-05-22 16:27:27	2023-05-22 16:27:27	\N	\N	\N
71	TodPophd	$2a$13$T.HWVNLh1KI8VEj.lKJUyuKwb4H/rblkD79MkNyhpBTiEgaGGGpRy	\N	\N	TodPophd@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	VEFvew	f	f	2023-05-22 16:28:57	2023-05-22 16:28:57	\N	\N	\N
72	sUJrGdbU	$2a$13$gJBWEACcL4j2lySk/ZWleeSlBn5oybikN7ekAkeTUbkjIZKSFZQQG	\N	\N	sUJrGdbU@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	EoxREc	f	f	2023-05-22 16:28:57	2023-05-22 16:28:57	\N	\N	\N
73	kUuHcBPh	$2a$13$UDp1hBQosJzPHodZGmuJVOuHkqo1snA05iZDt0.7b69qSIbKJlfua	\N	\N	kUuHcBPh@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	TxPQmD	f	f	2023-05-22 16:28:57	2023-05-22 16:28:57	\N	\N	\N
74	HDjBbsQN	$2a$13$AZRr/dThsInjmRN/MorGLuSKNiWj4YLqgRwxRVnfwvwBTDQyelF1u	\N	\N	HDjBbsQN@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	PDtleE	f	f	2023-05-22 16:28:57	2023-05-22 16:28:57	\N	\N	\N
75	rqabRZox	$2a$13$a7mdT4vahndvnTudqIrUo.FR0ZTeLqg74IGEtipKMGyM0pSo42WDm	\N	\N	rqabRZox@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	FXhsEd	f	f	2023-05-22 16:28:57	2023-05-22 16:28:57	\N	\N	\N
76	ycTNnrsk	$2a$13$pRaEbSs3zsn9VOt8LBOnXujiG/TFPDvX25RssoM8JP214BPNng0WW	\N	\N	ycTNnrsk@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	lTlaJV	f	f	2023-05-22 16:28:57	2023-05-22 16:28:57	\N	\N	\N
77	YCYoFOHx	$2a$13$U2e1Vkz7aOzk3d7qwuMhnOb0psW7mDnFYrDuqxZGlN/6vwIHNroqC	\N	\N	YCYoFOHx@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	bKTzBD	f	f	2023-05-22 16:28:57	2023-05-22 16:28:57	\N	\N	\N
78	UoCYCaZa	$2a$13$BWZxEkWMkj8zHFfCXACcHeSW0YmJh0.oFJJY9xLs.jhnYTO.5koz6	\N	\N	UoCYCaZa@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	wWwyzp	f	f	2023-05-22 16:28:57	2023-05-22 16:28:57	\N	\N	\N
79	ejKhNcoR	$2a$13$R5S6kMj7R4WiyWgMyBykh.vLRlT1oumjmHTBLJvOMynY0GWyncovm	\N	\N	ejKhNcoR@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	vxJiGa	f	f	2023-05-22 16:28:57	2023-05-22 16:28:57	\N	\N	\N
80	sPEFZtyt	$2a$13$yRQ26ry1sr6CjaTIihqxue7Bmp9CPjcNgfmAoQApezVOKSAzGBKee	\N	\N	sPEFZtyt@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	LWzgiV	f	f	2023-05-22 16:28:57	2023-05-22 16:28:57	\N	\N	\N
81	mxSeFZSA	$2a$13$pvng5arEaEKBAJSHapJwZONoiCtE7FPduTWt9R5AssB.ED3efPyeO	\N	\N	mxSeFZSA@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	UapzGQ	f	f	2023-05-22 16:28:57	2023-05-22 16:28:57	\N	\N	\N
82	EqnhDoaA	$2a$13$9sM.sTbg6rcmtccVVxmJDeyviaxgycvRDrufoGlurU1mb6zTF70F2	\N	\N	EqnhDoaA@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	mPndQr	f	f	2023-05-22 16:28:57	2023-05-22 16:28:57	\N	\N	\N
83	zsQEyTtS	$2a$13$hmuJrWI6T/JXrYNYWUDKpeEso2VtxMsYemPSKcDp2rMyILCmeuzsW	\N	\N	zsQEyTtS@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	ejZTjJ	f	f	2023-05-22 16:28:57	2023-05-22 16:28:57	\N	\N	\N
84	TWByPZbm	$2a$13$iCB5BYL8d2FoJCWUoG2RhuW2farlFk.hjpFx/9vwk6O1e9zHxzbFS	\N	\N	TWByPZbm@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	FRoiSs	f	f	2023-05-22 16:28:57	2023-05-22 16:28:57	\N	\N	\N
85	sTFtrtyX	$2a$13$G3WBl8EdpPD04fuc2U7MpeAbnNn/nGnr031TdzUqP6iq3gzkn79tW	\N	\N	sTFtrtyX@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	jISniv	f	f	2023-05-22 16:28:57	2023-05-22 16:28:57	\N	\N	\N
86	OeIjKtxL	$2a$13$jDvkxkWqLRyyd9FEiDTGO.U4IVoXBg/tpNlcCvXKh6p3ipmbejV26	\N	\N	OeIjKtxL@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	frPiuK	f	f	2023-05-22 16:28:57	2023-05-22 16:28:57	\N	\N	\N
87	itbhQTKu	$2a$13$UdVPNMV6VEPMj6.NR4gWSe0yk7kgcitA7jkhvx4Tb6s7cHW48OHFm	\N	\N	itbhQTKu@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	aeYVqc	f	f	2023-05-22 16:28:57	2023-05-22 16:28:58	\N	\N	\N
88	yWUOOTMI	$2a$13$Us.xv3neOE50QZmBQyWkkuUtZGh4makNAuhKLVkwuO9hWR/4IJNvG	\N	\N	yWUOOTMI@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	okIcGw	f	f	2023-05-22 16:28:57	2023-05-22 16:28:58	\N	\N	\N
89	ldJQLozY	$2a$13$LAUuXgYeCE3rF5tVAM6nK.WrvcoDZLpCBjaEp/svgTXqbkQbX9gQi	\N	\N	ldJQLozY@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	dSbAUq	f	f	2023-05-22 16:28:57	2023-05-22 16:28:57	\N	\N	\N
90	uXjVBqQu	$2a$13$F7kr2cLCCbxzDsCpadVAVeBwhGwSajd5FvEx1/xXZI4bRgRu8Hgaq	\N	\N	uXjVBqQu@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	KOBfhj	f	f	2023-05-22 16:28:58	2023-05-22 16:28:58	\N	\N	\N
91	VTcfYXKd	$2a$13$46INoJiq4XwwXk2mJfvwOuQIxszskZ3rTEnVZqiKPglDpy2JeJnYu	\N	\N	VTcfYXKd@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	zRybkP	f	f	2023-05-22 16:28:58	2023-05-22 16:28:58	\N	\N	\N
92	GdodmAHD	$2a$13$kmqI.y/.qRQThkD1xqG56O.WMxGcKmTZ/I8/IS0NNiW2qG5dcs01G	\N	\N	GdodmAHD@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	jRiwDK	f	f	2023-05-22 16:28:57	2023-05-22 16:28:58	\N	\N	\N
93	ryzJVhMo	$2a$13$u6d6TfbVgqD8434fvHWgv.daWiFEssiu65Lhi2RIjsXjHf2.wqnU6	\N	\N	ryzJVhMo@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	obxRRI	f	f	2023-05-22 16:28:57	2023-05-22 16:28:58	\N	\N	\N
94	sgMZqoMZ	$2a$13$tKNVi.jhKgcnjD.SrDL6RuXtoencSjdNrXrV/H6qeBLFSqyFQ2fh6	\N	\N	sgMZqoMZ@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	JNpvRz	f	f	2023-05-22 16:28:58	2023-05-22 16:28:58	\N	\N	\N
95	LxhFxpeI	$2a$13$upeW3GDBFn/OsuGq2JHkO.1tvZSlk/Q8qlTuBprJZ.hX3OiZ4gCK.	\N	\N	LxhFxpeI@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	hzaiHG	f	f	2023-05-22 16:28:57	2023-05-22 16:28:58	\N	\N	\N
96	cxWxZQDC	$2a$13$OBD9n1kBnqHmPSQEnPO2S.jVr79BQ/I8.VNHYPlfWg4tiN5B0.fgi	\N	\N	cxWxZQDC@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	SVtjnf	f	f	2023-05-22 16:28:57	2023-05-22 16:28:58	\N	\N	\N
97	EPfnuzCd	$2a$13$VBKD6gTYgSguBu.J4rxpq.TRtMH91bBHV7RhS/bWP6xHCwfY7iF..	\N	\N	EPfnuzCd@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	cgugxY	f	f	2023-05-22 16:28:57	2023-05-22 16:28:58	\N	\N	\N
98	MouftQcQ	$2a$13$ZbMO1z.RJGS4P5pHInVT5eP8ZuXn2/4vlX.XB3rHgOlc.axAL2Bfy	\N	\N	MouftQcQ@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	ckdrZn	f	f	2023-05-22 16:28:57	2023-05-22 16:28:58	\N	\N	\N
99	xxfeauAc	$2a$13$2TcwfHUMsEJUf3DepZyjquEwGOlZpRoq6I8YEBeyIpi/6DMgVXw8e	\N	\N	xxfeauAc@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	wUQfjj	f	f	2023-05-22 16:28:57	2023-05-22 16:28:58	\N	\N	\N
100	aOrFkhdD	$2a$13$A6VaqH0dbemDzuFQ6g3PlOXGGvdzo/arRHuuWHv/I5F5c/SLqYjjC	\N	\N	aOrFkhdD@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	gkXgnV	f	f	2023-05-22 16:28:57	2023-05-22 16:28:58	\N	\N	\N
101	zfpDlHUM	$2a$13$OZXujOP6ZpD3X94py7JQDOw0yRCBPkA9scWfImQwp5yf.mKdoQnGW	\N	\N	zfpDlHUM@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	iIOtJa	f	f	2023-05-22 16:28:57	2023-05-22 16:28:58	\N	\N	\N
102	bXCjNIdH	$2a$13$J8seIKfjCmxVdTkGQd51U.52HwC6H/GCwjS4uZ576FZE4yTKe4EJu	\N	\N	bXCjNIdH@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	vEQyHY	f	f	2023-05-22 16:28:57	2023-05-22 16:28:58	\N	\N	\N
103	fxFwvQHc	$2a$13$vg6t6j9gMVEnQ7dAWFLZ/OZl6cwpnyfcIBgLLLxLX/nd0RnRJstxe	\N	\N	fxFwvQHc@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	EJyeJJ	f	f	2023-05-22 16:28:58	2023-05-22 16:28:58	\N	\N	\N
104	tGuMsopL	$2a$13$UIHxhNJGOmw5.d9Ozzhm3e6IGUSZcCdBUkyrTe1u0jnEpNLelU5fq	\N	\N	tGuMsopL@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	IskjOX	f	f	2023-05-22 16:28:58	2023-05-22 16:28:58	\N	\N	\N
105	DgrwoCDS	$2a$13$P1/xlone3ZipKcYg6yDExOnk4ph6ck8UoCMWkCLoMlXzLZpkhGS/u	\N	\N	DgrwoCDS@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	NmxCQz	f	f	2023-05-22 16:28:58	2023-05-22 16:28:58	\N	\N	\N
106	PAFooGsz	$2a$13$9Me9unrcuA.py4c/JY4uw.WqSP4bGKitiVKe.xZQlekNwvGiGjR2e	\N	\N	PAFooGsz@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	xXbKzd	f	f	2023-05-22 16:28:58	2023-05-22 16:28:58	\N	\N	\N
107	GsOdcIeO	$2a$13$lnecVlbt0nkbQfBVh/95YO//L6zTX1QEDpdw2rdeUGFBv0hJ.0/x6	\N	\N	GsOdcIeO@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	OUBcxl	f	f	2023-05-22 16:28:58	2023-05-22 16:28:58	\N	\N	\N
108	YgVwOIwM	$2a$13$M3Xy6uukGrZcibuGEYHkeOn8aRKQ9OeP4OuAj3iPbeZLvB358e3XW	\N	\N	YgVwOIwM@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	qAmjhL	f	f	2023-05-22 16:28:58	2023-05-22 16:28:58	\N	\N	\N
109	Odgydzwy	$2a$13$LWWCA5UXQgb2EnNHNDeLR./ajQslUBRaD006UODlEFW0HXXK1wbcu	\N	\N	Odgydzwy@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	eEqZbf	f	f	2023-05-22 16:28:57	2023-05-22 16:28:58	\N	\N	\N
110	wtQvEknd	$2a$13$npJ2SMLa1EN1uv/sc02gF.XzBNYVDBSz3htvLeVg3txfRIC9pCmle	\N	\N	wtQvEknd@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	MnPWNI	f	f	2023-05-22 16:28:58	2023-05-22 16:28:58	\N	\N	\N
111	eERTlmwP	$2a$13$Q6ynoHtjPT689N1HdMOIhu6nEAyHJFz9xD6ABd1uxMSk4IPLJNxMG	\N	\N	eERTlmwP@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	KEUsqF	f	f	2023-05-22 16:28:58	2023-05-22 16:28:58	\N	\N	\N
112	NXeApYbx	$2a$13$hkrpGX9Qg/M6/4j0G37Z6eScc8gHq7yPQncJR8LfVadXlKOx//bk6	\N	\N	NXeApYbx@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	GAPVcQ	f	f	2023-05-22 16:28:58	2023-05-22 16:28:58	\N	\N	\N
113	EXsMhQRE	$2a$13$xkalVXyP2duL0VAidqFZhejMkjRuh3FyRd5bYSQhcqIrpwl40YfjS	\N	\N	EXsMhQRE@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	yYmITO	f	f	2023-05-22 16:28:58	2023-05-22 16:28:58	\N	\N	\N
114	KImEFGAG	$2a$13$hbI/4jthkvL4bdLr.qCs2uL5gdxxEvyHj3SPSw3YX6h45ZHTKfZWe	\N	\N	KImEFGAG@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	VHDpjv	f	f	2023-05-22 16:28:58	2023-05-22 16:28:58	\N	\N	\N
115	HBfwfBWu	$2a$13$.F6.EA2cbTZ7.rhrq0etoejJ4CTCQiKRjELpTEfnyvM0i7ijhp.3e	\N	\N	HBfwfBWu@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	FOWEli	f	f	2023-05-22 16:28:58	2023-05-22 16:28:58	\N	\N	\N
116	FxvlMOAh	$2a$13$tHhC7Kaot5hcnb0MsDAyG.zRhXFCdeJMFrHMp3hkRZE92hQZ5ffFe	\N	\N	FxvlMOAh@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	pmYLsf	f	f	2023-05-22 16:28:58	2023-05-22 16:28:58	\N	\N	\N
117	DJSkEHUD	$2a$13$qFq.VRGiCedGGVOlrAMaLOM.V3QLt7LebTr6kxaLd.Um0CHlHrZwa	\N	\N	DJSkEHUD@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	wfcIYQ	f	f	2023-05-22 16:28:58	2023-05-22 16:28:58	\N	\N	\N
118	lVhemADL	$2a$13$Al.tT1T9zQ8Rh8bEy6LfzeibnC08UoJq3OrXrYMs6IH1JRKo9GKDu	\N	\N	lVhemADL@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	CIxrFs	f	f	2023-05-22 16:28:57	2023-05-22 16:28:58	\N	\N	\N
119	ptsTvrih	$2a$13$nwEYEOUTvVZfuKD24hLPlOjNzC3j5C7TFqMlR4BCb6EPSX4CjoFqe	\N	\N	ptsTvrih@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	ywjhOM	f	f	2023-05-22 16:28:57	2023-05-22 16:28:58	\N	\N	\N
120	ILbCcppA	$2a$13$i1YKtLRT81mMk1aCz4cezu./z94XesKb0A9AnYyK525K73aiSexfG	\N	\N	ILbCcppA@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	kjSsLk	f	f	2023-05-22 16:28:58	2023-05-22 16:28:58	\N	\N	\N
121	gYdBAfoe	$2a$13$mrBUVnI/6.xoZ8EvxIe2iuc73Zu4bc/QCpFHAb2sVsKbN3p01m7DC	\N	\N	gYdBAfoe@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	HcZDgw	f	f	2023-05-22 16:28:58	2023-05-22 16:28:58	\N	\N	\N
122	qhGGHXbX	$2a$13$Gs3kZ4ci8MJpuy2Hsrv2luePGfsM6/biV5MkmzY7LpzrsYPHZ243O	\N	\N	qhGGHXbX@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	WuvlFx	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
123	RrNsuYdT	$2a$13$P6ZA/0B6Cyvyf3vnz0qBt.9FD7LAc5E6SmS9XxCkg9F2TYgerPiIe	\N	\N	RrNsuYdT@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	CpSctL	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
124	qutYyCjI	$2a$13$DJUpD4bMNUFAoSZFnjxbReqNK8cNnP62IWhB29hH51wcXJYNgrJAu	\N	\N	qutYyCjI@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	rhKbjM	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
125	AaEDcYIl	$2a$13$fCIsIqrCAC32fpq5qAJoZO/kaahqZ16foyYZt/.OvsQ4qnhxE1wk2	\N	\N	AaEDcYIl@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	PIQjFJ	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
126	SDSRHcfI	$2a$13$hJ/VJL6UIuZxsgPyx7k0y.et6AFv5io9U.4qPEmsIzmEZGUsnliX.	\N	\N	SDSRHcfI@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	iWpHjB	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
127	vpDwUVks	$2a$13$uVezmPk/LGnozoHE3qcUtuBcZeY7.RgB327GMG5vGv.VzcHQY1OZS	\N	\N	vpDwUVks@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	cUYSXs	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
128	quhMXTTI	$2a$13$5yBvrr1DwnT5O56PYkhFAujeN5sgjM8G0mnKvmGEaJeFD.iqbTTJK	\N	\N	quhMXTTI@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	oCRuYm	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
129	FsffnlXi	$2a$13$ZR9kllLGoHFUXyetBSPCEucrDCzr2x0a5182PBhBS4SQqmLVrHi/G	\N	\N	FsffnlXi@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	UzjTiQ	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
130	sPSzAoNA	$2a$13$BYZ8cDU0AUe5upQ/3UgHx.n7FZqs61HMHN6/TSVzspsXTmva.Opxa	\N	\N	sPSzAoNA@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	UHIdUL	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
131	DHJlyQKe	$2a$13$h16KIul79531w9whRUAo2.Aa4Nd/ic09Hvhmjc9Wk3iscCVkf0pMC	\N	\N	DHJlyQKe@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	LiZyLt	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
132	hmRlJvVM	$2a$13$.NWSs0Xr6mqu15HRWwK1KeHHa0MaksxLdSkowySU1EmIhwmhCqUKy	\N	\N	hmRlJvVM@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	vRhjOO	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
133	UDaxlZMT	$2a$13$9rkC67xWtUkw4tLd2NSQ6O40ftOsGQLoUkjF59WVf1oBRXSx6U49.	\N	\N	UDaxlZMT@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	TrEgbt	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
134	NaRodxeN	$2a$13$E.lRNfoRllklnlq6JQpr.OlXNc9FI7SpIUaC39MTzJ0tfKYmwAOla	\N	\N	NaRodxeN@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	BLZewU	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
135	dRADUflX	$2a$13$RqySeUQnI46cNYvig74PgeXWElRr4Wqn6.NI104SVDIR9gyuqGoT.	\N	\N	dRADUflX@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	DUnLoB	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
136	QuqswVAQ	$2a$13$MQoYVnA6g39GRh/blc/xROFCAGyNPkjDlE44k3YWkedZjiMHxrPli	\N	\N	QuqswVAQ@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	WMDjOT	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
137	zQgSTwAv	$2a$13$sfvkVUeEOkPbqYxQvz0FveWyJ46RGXTplL2md0/cGNPHtan8.iw3y	\N	\N	zQgSTwAv@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	jMMXQi	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
138	GvAzJQHu	$2a$13$Gym4kPjdOo7VkfJlolDXCOImyrYduIJpwa90XOt9Ktmi1OXNxwuUK	\N	\N	GvAzJQHu@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	EwrriL	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
139	tWmrkNvE	$2a$13$kwSZfpLrdAw7XK/iUHDxHOLaw5xFaXk3E1MM9DoVpjxhaVLghRFUu	\N	\N	tWmrkNvE@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	vHNiGZ	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
140	QLCqMOmv	$2a$13$lc2S5bRYXV3I5N//1xVxj.9Gu3wGgGgGZQjhuOarTMmKQkjauE8xK	\N	\N	QLCqMOmv@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	jJLbue	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
141	XvHNNDVB	$2a$13$dFa5iKkVyXxAWOMse/EjcuH5IcPQ88Q3mvgsEdDx7lbf6/9T/cbW6	\N	\N	XvHNNDVB@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	MkHKQd	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
142	OQiBKnkR	$2a$13$T8PvXYrV2OAkWPsfhwkVSeOGbr4.al/SO72wUomWi1qd1fPkuexOm	\N	\N	OQiBKnkR@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	kTtfRx	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
143	DpcZqyFz	$2a$13$TDcMinNp64m9V2q9wyIb0e97K1TjAGIqaCr161pnt3c/oHaogmdGS	\N	\N	DpcZqyFz@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	VTQgHf	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
144	WUmHpzuP	$2a$13$XM3HMF70dtexKl.potY2meCydWOsFrAySHYjlrq15gyOPVqJu9pZC	\N	\N	WUmHpzuP@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	dQpynz	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
145	XvkqgPUV	$2a$13$otSNwHndOSSyvhY4pS3L2eiAQxHUy7V9bDk.CGPA1d4p15Lmyyx6G	\N	\N	XvkqgPUV@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	TZhXYW	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
146	SUPlLGfK	$2a$13$28Fc6Ac.PR0MfgHJZUBFdehvLGbo30o3YdfNCIQFglXqqCM9iLoNS	\N	\N	SUPlLGfK@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	vxFinn	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
147	xqAuAdcB	$2a$13$uVqHH84vIPEzD9qDiVeBrO0ap58eobZ6kg2pIEcKzv0mJx9WuU2t.	\N	\N	xqAuAdcB@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	akQoly	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
148	CxtztLIw	$2a$13$kWHEamO92QQ2Z5J8g/HmMuz5pak/aS/CivegZ.Ymxf1u0nfZXam/W	\N	\N	CxtztLIw@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	DYhACt	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
149	KcUdtfRC	$2a$13$Kez.XDmVgzAi1iTkiTk23Ouit6WF7qR0wahsaH59WVZp8jMgJl776	\N	\N	KcUdtfRC@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	PsloZj	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
150	sAcadrBX	$2a$13$L7LT9LdbFyjiWj/dtJgEOesn7HTGCEdEd73cDNs6ZM5fd8d4qbulu	\N	\N	sAcadrBX@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	KYALXF	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
151	VPypozwF	$2a$13$9T8sj3JWOK8BvbdodfWsGeYYqN52Gy.ZvJapXgMkmhyaYeu4IkRa6	\N	\N	VPypozwF@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	XotzMz	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
152	gPlnTDPG	$2a$13$EK6tmicVRVJeSBxAHmHzsuFncWzt37S8ICUZFTKWRzBOI4bhymp7C	\N	\N	gPlnTDPG@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	hEwyvX	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
153	KgfJeQSz	$2a$13$f3fud1TovwQBD0KThxqw0.6yu8wxLuMYnoyG4U5f8oouKJCVJkGrW	\N	\N	KgfJeQSz@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	cwSZzP	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
154	ljffIayR	$2a$13$2sJNFB6FCMcOD/l8kF8z.eBt85Z3UqCjlQAjEzsar4Pt8xJAj.vVG	\N	\N	ljffIayR@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	TuYqQM	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
155	IGgZDfcJ	$2a$13$HGgVt9mGRFlMj6jm/Y5IUeTeFLfeUMny5wqdGh/l60AsLP9LuneK2	\N	\N	IGgZDfcJ@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	VVeMQO	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
156	ICDXVdAc	$2a$13$DEN2EAm6bix1Qp.lKIqjTOTU5zVuZslc.vhvlf8M0PBIO1suRmdiK	\N	\N	ICDXVdAc@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	ZiVXyk	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
157	GcTwwSmv	$2a$13$yNvp4gEWU.AwBt4KMOFheu71pkLsnUuAp562C36MR9jTVWfR/WkW6	\N	\N	GcTwwSmv@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	yBUwPL	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
158	VewuKkHs	$2a$13$iwCPEjbqUQgyw6UuyIvkUOV.8lwiw74uohUMPk/RYjHnzRpoeiiJe	\N	\N	VewuKkHs@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	NffJUp	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
159	juxsMwRy	$2a$13$5lZ1E0sgzSoYRxgnb6Sfe.PTYpTK56wqklOgYHJljTfwQC2MhrCF.	\N	\N	juxsMwRy@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	BBmsAN	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
160	kAZbknYL	$2a$13$8U.whleCvjIu61xFTO.FYuplxW/8OL3IsN/vs4xv25m7OlAulVpWO	\N	\N	kAZbknYL@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	iiBiiY	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
161	xpKbYzHj	$2a$13$WSxk1xhmVXNP/ZAklowN1OtaI6XUa60SYqvjtvna.CGYdRoBkm2Y6	\N	\N	xpKbYzHj@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	eslMih	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
162	cncPaayO	$2a$13$C6ujuaFFv88hqQIzKIKbCenB45pyTEZ9tpBeFUuw27dVHh4Wu8fVu	\N	\N	cncPaayO@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	YfAoqZ	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
163	BcgmQeqE	$2a$13$dkkPnRH6c2kjEmNA0F7QkuDpEukTedX/oHgjPQHA.6RymZ06v7g2i	\N	\N	BcgmQeqE@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	oeXfhC	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
164	JpvPHlOJ	$2a$13$2oOXvzPexC3RbnbZFuiWW.F0Er68Oa71og56WvA7Rv2SENSUuEy0m	\N	\N	JpvPHlOJ@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	tcnlWQ	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
165	JEqgBsoS	$2a$13$ffvF.ceUIyMbhNXIujtBFeZdk4HI4hzXBBd1VyGg7OjlMk4z2xIj6	\N	\N	JEqgBsoS@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	DIaMKQ	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
166	ctSunqyZ	$2a$13$JYLWNLURhSM3lXZFbAS0s.HwrVlKwVK7eH10AcXGsdcW95jH04mg.	\N	\N	ctSunqyZ@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	lCzMxe	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
167	ZHivVxNs	$2a$13$O3YuPsfaU8Z9e.xVcBEefO1hFn/5zxLzT36kNANrwnOh/wiW8iwGu	\N	\N	ZHivVxNs@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	DJMuIA	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
168	NoXSBpUC	$2a$13$.fm3pzTbXtaK233VXN.Hs.q9ev7FsRMIAgOpT3aqmaLb0WgqtRLlS	\N	\N	NoXSBpUC@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	sGaKEE	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
169	CkTEUMKF	$2a$13$0wZ8lZjAII7JmZZ78DkpCO5RuDVzhbBMKmO7bv9z8urUC0T0DqOqW	\N	\N	CkTEUMKF@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	awVejl	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
170	rmmvMgEv	$2a$13$rofXfOoJyQx8lzG6MLo3o.BYnBUjwJnFRn3T373.BX7Xka0gKnmL.	\N	\N	rmmvMgEv@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	SjYIAz	f	f	2023-05-22 16:28:59	2023-05-22 16:28:59	\N	\N	\N
171	qzNuHnue	$2a$13$psmnLEFjqxKr6vZq5uPfU.n9la0vz9GUCFjzXsdEP3ASOWFaDUrRq	\N	\N	qzNuHnue@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	fBEFzm	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
172	prKoyfDX	$2a$13$ewggORjXjX3f0yTBe1uIi..RbUWj3whAQ05cHJE.24LJzszRt6nGe	\N	\N	prKoyfDX@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	ngKmgu	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
173	oSUVnbYl	$2a$13$uv3SQ1HO74lcqtciPHmKmOhYA/vMMwJHDfNLeSjAcGgk0p/.x8j3m	\N	\N	oSUVnbYl@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	iPOops	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
174	RuWLliYT	$2a$13$/3Q5G7fuSlRo8/Bl5UYQcOHYSzmRI5g4CYiTQvnCyGNM4OuigV9be	\N	\N	RuWLliYT@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	iTSNhO	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
175	JrxEWZvP	$2a$13$Kf1BVqN3N.YcovzNuqGQu.s7df8oi.CXkYYjXht6LXOU1SxToGlP6	\N	\N	JrxEWZvP@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	MkURba	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
176	HexYRrej	$2a$13$/0UFhfrG8afVG4PlCb8Lue2/TJi0.jWF/7q/xbwLh7vEBUVVJnAYK	\N	\N	HexYRrej@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	lGeQVC	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
177	KzJwOJhk	$2a$13$M46saK4PPmi20KRKZoz78eFqxHIdnxO7xZmQyQ0lHd5z0trDveK8W	\N	\N	KzJwOJhk@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	bJFEdK	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
178	NGQGIIzQ	$2a$13$qcbwjPF3WRByTSaJoUAq5OHNR3UedsAlxWewujcstJTfsqBR0jn6e	\N	\N	NGQGIIzQ@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	yEjSyV	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
179	SLXwwAXm	$2a$13$32ouI3cHi7a4qncqwchv5uWlO354B32so.KGlFsSgDxPLagquleoG	\N	\N	SLXwwAXm@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	TiOytL	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
180	SyUcyxiV	$2a$13$BVzOi8oG1kdyDZoNZUYR8edVk1lpQNGVubxAMOoQgw.6nQAj8dqnm	\N	\N	SyUcyxiV@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	yxwAiD	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
181	BdgxTxmc	$2a$13$UcKzm7NjyA5.qhgiBT6cfODttjaoXTJ8OhErDfnmHQWSYmGdJJ5OC	\N	\N	BdgxTxmc@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	CcZgdZ	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
182	mngAhWwQ	$2a$13$90ywTHPps5fkXCvvmsRuM.vfop0CAYkc6bSJymZ5aX4.PwEyEd6ea	\N	\N	mngAhWwQ@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	ghmfdI	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
183	DoNVxmqc	$2a$13$xCmjVisz3PNrXAcdej0pNOJBHX.GfdcuQ0Tju9ZRBQITegitOyCTW	\N	\N	DoNVxmqc@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	YCCXvu	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
184	IjCYdpot	$2a$13$NjBbxqSmYsheM4aTJZEBVOsmc8OrUWGLdILF8BkRosy/2ufdUruky	\N	\N	IjCYdpot@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	NALluE	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
185	jbaHXwWN	$2a$13$fvyf3WYM6L1Yy.XdIK0qieSCRWUFoeijqW5LDMpfuudrYYE7ZAVDK	\N	\N	jbaHXwWN@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	lxYZWt	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
186	UnIpyTON	$2a$13$YXter5oZV.6hG5W0HmF8IO1XNzsX0zYobRTTh0d0h3wC/cW3Ixzk6	\N	\N	UnIpyTON@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	RSityb	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
187	kZJTKDBF	$2a$13$8gMgdutY9YpCfUBKVZ.4tOFgKhEklkSl1zxMQ5kAREqLWtUVKwJmO	\N	\N	kZJTKDBF@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	UbbLII	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
188	FZxqqGHh	$2a$13$KIyXP1y1v5JwpBfXYLpz1.PHavH4lXKizcDaeJn6ksQBnDCTFxZIy	\N	\N	FZxqqGHh@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	GjpCXx	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
189	SjCvxJFA	$2a$13$.S66qw16fqDG4VEpOgqEXOgtTom7FZ29oVMwinEulTlFJTXd/THCG	\N	\N	SjCvxJFA@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	KjVsVU	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
190	nHRIwqBp	$2a$13$MT92DTb2zH6ahlTmufIfP.ptPGTKw0Hs0VdLxoR/gMTIwjWwy5CT2	\N	\N	nHRIwqBp@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	cQaekf	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
191	eXFlCnFY	$2a$13$X.zolHqElJzkudtiBreGMuMiHPCz9FFwEIxP.Yh1ltJA0dcBP7IQq	\N	\N	eXFlCnFY@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	rjygbn	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
192	zdsUGiBQ	$2a$13$MhDjfgrvP/V2QHQHe7H9p.8.URxY1ZMXpDrnbBbEDmzJUFdYW0NOG	\N	\N	zdsUGiBQ@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	hdxiGK	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
193	vjHXfQRZ	$2a$13$NMqNTW.fQVCt5TjypyGN3.kcU1Jv1xx40RB0lrrv7or.DRb2sqnC2	\N	\N	vjHXfQRZ@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	JLXFhQ	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
194	ZcIsKfoZ	$2a$13$IKcg/Ko5kwIWAdKFq6NXmezB3bBfvehQ3A3UxveU52iODuSmBd/1e	\N	\N	ZcIsKfoZ@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	nVXXWR	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
195	xMoIdRCu	$2a$13$zDYvr22FVsAPf3/8vmixduax6z6Il75HA6BWeOVandAKhIdl011u6	\N	\N	xMoIdRCu@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	VJRDFd	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
196	ISBnfJVn	$2a$13$vqrk8YW2sCS.MIJcKo43bu8B.RBcggjcusU.i3cTfJD8f7P6QJj.2	\N	\N	ISBnfJVn@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	OJuQjf	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
197	BLWzLqGf	$2a$13$jieu2vNm1eBUJ1pA6/PmnOtNPnjiE.vB.NiR3CcJUM7vtI8sMuwrO	\N	\N	BLWzLqGf@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	hRlpmD	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
198	dBkxEXtX	$2a$13$jkV.H38tUrCSvJi9jLw87eJTQDLERgk30K1FaIJyU3s1m1x7vXzdu	\N	\N	dBkxEXtX@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	gGottS	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
199	WopDhppL	$2a$13$ts6PiLGWxDk4xOjbXlaFEuOxAt5xRPjCVpl9fVPZ2l9XFt0ZnlJY2	\N	\N	WopDhppL@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	nmdNVe	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
200	obdGlype	$2a$13$N/Xz85imdbMclBMt92mWq.S3eRx5oVuoR.EFH2sOFltvOfFmv0TPu	\N	\N	obdGlype@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	trfdMo	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
201	aTwypKJc	$2a$13$YP8jFhQLrAm8UCkPhfOEOuU.PStYOHfabOeg1wk9yCPf7ctH7Puoi	\N	\N	aTwypKJc@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	bRBsFp	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
202	dSELeSjj	$2a$13$/VDTOqm69yGDQULZbXECZuDQOGGzOVSAz4a9WXIqIxSfTwEk9SAsW	\N	\N	dSELeSjj@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	NbNuzi	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
203	kXnDswaK	$2a$13$ERlbhsFk14KCaTvnkaKq6OHzrY5OuXKCEysd2c9ER85RyfdLfuILq	\N	\N	kXnDswaK@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	GBcqNY	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
204	uFtKwhCP	$2a$13$Zv54mOoOE0ictPzu1F5TvObfQPV2hU5x4VCCcsePUbBBXdidd8a4y	\N	\N	uFtKwhCP@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	afCXMQ	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
205	wstPkGMm	$2a$13$d7tupW2Rd13McmRv8/zBge3z78G7wvl0nZpe8uHi9/xXEnO.y2tUK	\N	\N	wstPkGMm@interview.com	\N	{\n        "Roles": [\n            "ROLE_USER"\n        ]\n    }	QNPEwr	f	f	2023-05-22 16:31:38	2023-05-22 16:31:38	\N	\N	\N
1	interview	$2y$13$aEDuvAFksTmBfr.dfBP9Z.DEP7JwTT5IPQqgQvtTvOEV.RBHvNC6G	username1	lastname1	interview@interview.com	123456789	{\n        "roles": [\n            "ROLE_ADMIN",\n            "ROLE_USER"\n        ]\n    }	624813	t	t	2023-05-01 09:16:12	2023-08-23 16:41:57	2023-05-19 14:09:25	\N	3
\.


--
-- Data for Name: oauth2_access_token; Type: TABLE DATA; Schema: public; Owner: interview
--

COPY public.oauth2_access_token (identifier, client, expiry, user_identifier, scopes, revoked) FROM stdin;
92af1895fb331db5d0b2f57be4fb1bccae391286abc64aab22ccdd31a79056864013ca658771463e	testclient	2023-08-24 00:24:13	interview	profile email	f
ad7e3a8b59fd47e4f05a38053be5d779855a7bf67b6111f55bf283d26a861b4e8aa93f67c1677b07	testclient	2023-09-23 23:27:57	interview	profile email	f
1fdab0081f1a0acb3b718cbc644ec4929242e65216ce2dfeb08985ea5ebb969dbde82c8e50d72c4f	testclient	2023-09-23 23:34:49	interview	profile email	f
217c17a5d35e937dcf2a95fbef4e633903eac4776e24e9e880594c6ddee62d09bd8bd8b9531a7d12	testclient	2023-09-23 23:43:38	interview	profile email	f
79bb5b4f360b9741df503f2221fe005cb469e4efac598be3d3b844789b72be0146f610ebb9549722	testclient	2023-09-24 00:01:21	interview	profile email	f
3afd9af2981f05e541a4319d8b6beded71d5f9773cfd619b3f7cb42d37ef51c8659fa102453edfdf	testclient	2023-09-24 00:01:45	interview	profile email	f
30bc08eb90d0f0ac0efcf5889019abed1ad5c68e44eeb79bcc98f0d16e25e4df707a265978ed0c6e	testclient	2023-09-24 00:03:01	interview	profile email	f
bee4d7d14029569f5ee36c7a739ea8f942c4a5e529f7dc0fb91fbfef83451421caa441fcc033c1b9	testclient	2023-09-24 00:03:26	interview	profile email	f
cf2c806299082cf02fb54c25cf416f35c5abb9726122483e9e1c2612b185975126012db30aa4b3c3	testclient	2023-09-24 00:03:59	interview	profile email	f
7973b0ac537507b41a81e3331a64241400f816bc601bcfa935853ea04086d078e0bd2250c257004f	testclient	2023-09-24 00:05:01	interview	profile email	f
e4ed0c8488b2d014e008342cbed13fd930eaaa11124fc88df72dfaa166427e86c5b762db4efebd52	testclient	2023-09-24 00:08:42	interview	profile email	f
700af18a67f42d7e86603ea3a1ba5ec9da665c1783232b76f9190786d4dc63f2852c3fee213259d8	testclient	2023-09-24 00:11:05	interview	profile email	f
98dedb44b25996223cd9e8cb076c1ee096b0e554397c3d17137e2c4d50048a71cfec8693bc3d7dd5	testclient	2023-09-24 00:11:20	interview	profile email	f
b522e9c6ed42ba0dd869cccd5c571082cf6fbd9d9f030ef3fc45a9047f09a1454cca0d8ba93807bc	testclient	2023-09-24 00:11:57	interview	profile email	f
5804b55c3122aeb1da88dd1a74a48b8be71a1574458bec5d028d6b15ce9b35599dd309a61ff4e52a	testclient	2023-09-24 00:12:42	interview	profile email	f
7d7ebc1752da4f37b9ef73b2da062bbaba81203f2c0ea2ef0d313191d6179cdb5595ef9ff6e51636	testclient	2023-09-24 00:13:11	interview	profile email	f
3a6e7e8028dd4cc9cdbe5f4c6068af4882f62da58b93ad03073483a2e14f729114cbbe65105099a7	testclient	2023-09-24 00:13:21	interview	profile email	f
87c5d39e2bdb6609c667b5bf9357b582f16aaebb4c0c881964cc1efa1e8ccf4491eda364d400e53a	testclient	2023-09-24 00:13:41	interview	profile email	f
4a6b42771211bef4dc1599e655f40d212f4135e056ef522fde1710950cf6d63c47ff397c74b50ca4	testclient	2023-09-24 00:19:34	interview	profile email	f
febf1b7852f2a19a704f928bb2736e6a8e88a6c01e52c7c79b509dcd98ba8f0954a0c3031670a5d2	testclient	2023-09-24 00:21:48	interview	profile email	f
dd87a23d0393fa9b4b6f3d79940d9249859d95eb9f1f2efeb8cd924507c4436c07585a428953525c	testclient	2023-09-24 00:39:29	interview	profile email	f
9302b80190d625113ee40d91f0baf92c59fb1a201fc3b423fd3c5cc60e0c2ac2f32a1ce56e5246d4	testclient	2023-09-24 00:48:49	interview	profile email	f
5e712c6a06c0721c6755e0051cb8bbb469d27cceb2cb47ad4192a68e040a2ca6c0d1d7e15dfb5c3e	testclient	2023-09-24 00:50:33	interview	profile email	f
24891e73f138806bfa0c04168c0ec2cc0a41f65d2eee7ebed5a5a117de907c2b96bd4cd2cd1f9058	testclient	2023-09-28 11:55:13	interview	profile email	f
cb137b79c4cab4eefc497803620eff3250ae9b7b965cf6da54dfd9b979d9ea4f3eef998ed7133710	testclient	2023-09-28 12:00:27	interview	profile email	f
fce9370356e6a4a3f67c0bb4bfe2461b174e968f192c1f43aa467e37978856479582557be5adbfc2	testclient	2023-09-28 18:47:36	interview	profile email	f
\.


--
-- Data for Name: oauth2_authorization_code; Type: TABLE DATA; Schema: public; Owner: interview
--

COPY public.oauth2_authorization_code (identifier, client, expiry, user_identifier, scopes, revoked) FROM stdin;
bad31c432891d4fa5162d9539c27277fe5c76a0ae589a5342e129eb0c98fc1dd522d8ed52d3c6cbf	testclient	2023-08-23 22:09:42	interview	profile email	f
ec3822ba5068c5d041ed91b13c1be705a657018e93a75387444734831e5faa6478555193f696d18d	testclient	2023-08-23 22:12:25	interview	profile email	f
ec3a5b40992996e4b36c16debebba309a028dfeda7f55d5beb68de477fc0eb2e3f0636c19a071cdf	testclient	2023-08-23 22:22:27	interview	profile email	f
1ea36ae1af9d26bc1d097bb2d26a3eb0f4738e15a940e7f93346f46d0f508934f4e0e198ec283851	testclient	2023-08-23 22:23:45	interview	profile email	f
4246b455b43d23402bb996728d759206400c201dd661ab9066fcdc75ce030cbd3d3159d8b12290e7	testclient	2023-08-23 22:23:58	interview	profile email	f
67be91530875e49fc5df5fdf5f05b38e03f7f9521f4a81a27f2e7a698da96c948712f88bcbf5ca49	testclient	2023-08-23 22:25:19	interview	profile email	f
bb70ed69277adfd338bc3b2177aca2c942ec5fe83203d10a7c066671963f35c519b7bce4c1c6147a	testclient	2023-08-23 22:26:04	interview	profile email	f
726acd3c82b4e6c34b33bd62e66dc631001670f32d4dd03ef565fb58acea30c9d1adffe7fcb54edb	testclient	2023-08-23 22:27:54	interview	profile email	f
1a15a0ced9db12eb1a81f96db1dd8cfd3fbcbec6b080e400d664783b810ed5e185b2e926c76066b7	testclient	2023-08-23 22:28:35	interview	profile email	f
4686efae1dc7dd2a69f9679ca8c08809155b94d0d278c7b7a996153c9cdf7c27e953e72ce05e3d11	testclient	2023-08-23 22:40:15	interview	profile email	f
76ec9f1e20491d477602cb7707d670b303465a67e96597002eaa877769790869b785badbf5912672	testclient	2023-08-23 22:53:27	interview	profile email	f
cae38addfc380dac2fcd193077d16c93485ee71ea84b91a76828900a85806e9ac1d43f6060bb99e2	testclient	2023-08-23 22:54:32	interview	profile email	f
fbd59adab4553d13dc34b345c007f2c7527caeaf9f79cbd1d2a34f86978d15e970a276349cccc89d	testclient	2023-08-23 23:23:14	interview	profile email	f
3f47b3c32d3317fcf6e656a56447ce7246e23a548071f69c41290526e5c03535ac17f632269165ed	testclient	2023-08-23 23:29:44	interview	profile email	f
dd57bf71575f3662f55816702e9f009851c17311b1bef845f390c90521bf0c6ef2717c6d416b2865	testclient	2023-08-23 23:28:11	interview	profile email	t
7cc8b1b9038849d003bb5699913f27a2fcdbfaad785f79f6f96ff71c87ec903bdfe2e64949d4dfb9	testclient	2023-08-23 23:37:53	interview	profile email	t
019bb338ca9317c20a529cc7c760d38c00d4ceb4272296b40ce91082e67ee1ea0c9acfccab4e7e72	testclient	2023-08-23 23:42:31	interview	profile email	t
8e92cbf1bd34e9c7ebf6ac76b9b4e757adb508736e6b5c732bb795ae6d8658d0072c3a3dfc5149c7	testclient	2023-08-23 23:45:03	interview	profile email	f
14f18cf0f973d432c74b54164074721cdbaa76857b1eb396be1a924b2c6334d6346c618910d465dc	testclient	2023-08-23 23:46:19	interview	profile email	f
9c963872c4111690efe1a97f85fddb047db53174f4aa7953de63aa243863b4fd9abb75ea6967130b	testclient	2023-08-23 23:46:58	interview	profile email	f
45e052a693d16465fe224378bde111778ebbcab96aaf2ad517daac8946590066f2fffc28429037a1	testclient	2023-08-23 23:47:40	interview	profile email	f
47c755de6bfeb08e0e02c24675577cf47d99e147e3ce1222adc8bf55fdf14665b5420efb529ae93d	testclient	2023-08-23 23:50:07	interview	profile email	f
4b39edcfdd96eae9ddeddfd1e2cea0514fbcad69e0d45db4264188dda9a41f24cd55cbadc22972ef	testclient	2023-08-23 23:52:12	interview	profile email	f
1d82238f04bdceaf64c810004c67ee47f2778fd679b7799d74c3f5c4da0c7e5f3680a428afc84258	testclient	2023-08-23 23:52:46	interview	profile email	f
5ded0472c0e0326a8bdc28706950739bfee69014e97c6875616f1a349ca3f3ea517e214a052957f1	testclient	2023-08-23 23:53:37	interview	profile email	t
3705c5eede2ba8f74f19133b57a0c7f56cd1ee979877335fc920304f54660e11d56c3a2f4d4b042f	testclient	2023-08-24 00:11:21	interview	profile email	t
13ce187315c2148b75350cdbca44775cd75ca755744524e9fe02df4f16c0027b96cf264b5ee53804	testclient	2023-08-24 00:11:45	interview	profile email	t
5be2494f1a8a58307496e7df6f3b3e4b545f0400c56cd861859a1589c94aaa8dfc7e1cdac851fd48	testclient	2023-08-24 00:13:00	interview	profile email	t
7f57ebe96a0d2b2b4843f3fa25bbcfbcf54c745c07ff21d60b85d48e10bd6da46508d1a03c29b59a	testclient	2023-08-24 00:13:26	interview	profile email	t
59240368483a73618a1ff1685c6a099ff23de8d6ff7a633dcd9aabdce4f0f26e8d1e833cc5918920	testclient	2023-08-24 00:13:59	interview	profile email	t
703dacf03eaea2db097573e67a920940034951e17598059a2945e092356c40a2f058bdc015b31ed8	testclient	2023-08-24 00:15:01	interview	profile email	t
22dd374ea212c0ead28a7d5d42b0e84e1802bc2f070264b98b090dc0c68f8f55e0cb004f9436d48e	testclient	2023-08-24 00:18:41	interview	profile email	t
657e83f4f72661c849a1534ae50a6f0af2d914cd9208f91d070752774f9d24a681dd4e8db2629ea4	testclient	2023-08-24 00:21:05	interview	profile email	t
418e7690a8ebd72e4616783667aaf510908686ca6882e2ce3392269ce26722bd35946ecadd136e13	testclient	2023-08-24 00:21:20	interview	profile email	t
cc0bab9e6f0ac1b52a33471dbf17f5035b305ceb90842cb9843a4d671b977bced0fccf3f62da26e3	testclient	2023-08-24 00:21:57	interview	profile email	t
9c807cccf62f6d0e19fc5ee8e1b8b6c3467430f425b4cc7e1eb5d50f61a29f2caa50092683296f98	testclient	2023-08-24 00:22:42	interview	profile email	t
ac10adda53c5e556cdf39e007909f3ea1f2cffd34cbc66c8e3620d610f4d698eede0a52d05776573	testclient	2023-08-24 00:23:11	interview	profile email	t
07250f5101659ed301070637135771502ac3fb72885ed7b95350ada9b6507102d17b95cd2c1f501c	testclient	2023-08-24 00:23:20	interview	profile email	t
83702779513ee49786cb3577b200eccbb980e2e145613fa1010b09321ced350dd5a9d388671812f9	testclient	2023-08-24 00:23:40	interview	profile email	t
45c14f75cbbb568c26a765f90029f99db6496d546e878216c5e59e18c9dd217c8fdee1e3d9a72a68	testclient	2023-08-24 00:29:33	interview	profile email	t
6d447c4a97f209d2abacef1c6c89c4bc74b72f04fbeaee5a798bbef3bd97b5e371ac4b92cd2bba5a	testclient	2023-08-24 00:31:48	interview	profile email	t
827549f13d2af8b1bfafa1e45f042eb3a71f00d80183c505411adb9fba0a044ebec1c85c898e11ec	testclient	2023-08-24 00:49:29	interview	profile email	t
45ce2fd4e1dab13e4dedbacef93ccb55cca2522d2c20856a797cb066383fa174bed31bc7551eb161	testclient	2023-08-24 00:58:48	interview	profile email	t
70a04ba726e2bec77e833014aabc2c2cbce043eac1354724636b6e9d74cd8fac94520e059ea473f7	testclient	2023-08-24 01:00:32	interview	profile email	t
fd7c12d53ba08a97921180193aea726c995684538898dfbe567bb2d738dd806cb5f95fd7c4ab3993	testclient	2023-08-28 12:05:13	interview	profile email	t
b6c54c4f6e6de91f696ba9d5f9e15db1a21eaf1ab75133563989ed2d7aba99a1cabb5fb36006d7ed	testclient	2023-08-28 12:10:26	interview	profile email	t
cc1a33b9b2d45a83a1c59f778dfe23c0fb9722728abd68896438ed2747450a116a9d7b692a3624f9	testclient	2023-08-28 18:57:35	interview	profile email	t
\.


--
-- Data for Name: oauth2_client; Type: TABLE DATA; Schema: public; Owner: interview
--

COPY public.oauth2_client (identifier, name, secret, redirect_uris, grants, scopes, active, allow_plain_text_pkce) FROM stdin;
testclient	Test Client	testpass	https://interview.local/callback	authorization_code client_credentials refresh_token	profile email cart	t	f
\.


--
-- Data for Name: oauth2_client_profile; Type: TABLE DATA; Schema: public; Owner: interview
--

COPY public.oauth2_client_profile (id, client_id, name, description) FROM stdin;
1	testclient	Test Client App	\N
\.


--
-- Data for Name: oauth2_refresh_token; Type: TABLE DATA; Schema: public; Owner: interview
--

COPY public.oauth2_refresh_token (identifier, access_token, expiry, revoked) FROM stdin;
f86c715357e8c43b40623735889a884f3372f0ec9a425e721bb7a3e9fb06beaa541eb44f4ec34b35	92af1895fb331db5d0b2f57be4fb1bccae391286abc64aab22ccdd31a79056864013ca658771463e	2023-09-23 23:24:13	f
36da757c81021c91beaa1a1900798b86538a2a9192b5f112f02d971eaa88962c827fa449f5f6babe	ad7e3a8b59fd47e4f05a38053be5d779855a7bf67b6111f55bf283d26a861b4e8aa93f67c1677b07	2023-09-23 23:27:57	f
8becb754b7c9f7f5c8a99cc2aff38a9c5adb2a9529eca5bc787529b6d66109d29b8b0ef891cbf5d6	1fdab0081f1a0acb3b718cbc644ec4929242e65216ce2dfeb08985ea5ebb969dbde82c8e50d72c4f	2023-09-23 23:34:49	f
eb1744a78e9045dcfdd62eef5f80f9d25ad61ef036ea57c3851135ec5af7cfad706b00a67a378dc3	217c17a5d35e937dcf2a95fbef4e633903eac4776e24e9e880594c6ddee62d09bd8bd8b9531a7d12	2023-09-23 23:43:38	f
8b30a60f08333a7fbf7bec516b25b060766049da49566fae7c559b8917e099615deee20e80428029	79bb5b4f360b9741df503f2221fe005cb469e4efac598be3d3b844789b72be0146f610ebb9549722	2023-09-24 00:01:21	f
89f1050fd93d836ae2ddf4a16271764a45ddfba3d593e6059f92c06b28427fa07a14b3d6b6d32d31	3afd9af2981f05e541a4319d8b6beded71d5f9773cfd619b3f7cb42d37ef51c8659fa102453edfdf	2023-09-24 00:01:45	f
3158a0acf4ad54322b375c038212adaca564e42338b12347363929b3737723015ee7c7c6940e8ecd	30bc08eb90d0f0ac0efcf5889019abed1ad5c68e44eeb79bcc98f0d16e25e4df707a265978ed0c6e	2023-09-24 00:03:01	f
954322620eb5cd9569f9bc266bb4b2d36e4ba938870e1c25df60a1da5ae00970ebb07e208a92991f	bee4d7d14029569f5ee36c7a739ea8f942c4a5e529f7dc0fb91fbfef83451421caa441fcc033c1b9	2023-09-24 00:03:26	f
01a866aa747392edd37ce0abc177dc3a4f0eba7ea95ed16742430468905ee363c98793750ea25692	cf2c806299082cf02fb54c25cf416f35c5abb9726122483e9e1c2612b185975126012db30aa4b3c3	2023-09-24 00:03:59	f
e4f24bc95155288c0cc5e7e7d11c19f0e33defb8bcc6d7d9ca1fe2b922f6462555dbcccd3a81ae3f	7973b0ac537507b41a81e3331a64241400f816bc601bcfa935853ea04086d078e0bd2250c257004f	2023-09-24 00:05:01	f
419dcf70279e801e7f1dbb20f7533599490039b123b3dc9bcee9bfffa8e5dc4f28e2691741d3ebd0	e4ed0c8488b2d014e008342cbed13fd930eaaa11124fc88df72dfaa166427e86c5b762db4efebd52	2023-09-24 00:08:42	f
13f830edb04bbf2f70255291a76acd8af88c5323ed8fa92d6c2dba6c8e7b0c2655963cca555189e1	700af18a67f42d7e86603ea3a1ba5ec9da665c1783232b76f9190786d4dc63f2852c3fee213259d8	2023-09-24 00:11:05	f
700856576bb3bde09a21145e83b962633a395471b1e2662bec31cfca8f290cc2d78a99abed5fc063	98dedb44b25996223cd9e8cb076c1ee096b0e554397c3d17137e2c4d50048a71cfec8693bc3d7dd5	2023-09-24 00:11:20	f
09300cff58032096eb170b35b4402a551457f9e069831bc77a5d9549dcfb2125c604bfdd2ead9e7b	b522e9c6ed42ba0dd869cccd5c571082cf6fbd9d9f030ef3fc45a9047f09a1454cca0d8ba93807bc	2023-09-24 00:11:57	f
f565909e4678258a20ae625ce27624e9680363b15185609dfd5cac93ea73877dbfbf1aef59fa9b89	5804b55c3122aeb1da88dd1a74a48b8be71a1574458bec5d028d6b15ce9b35599dd309a61ff4e52a	2023-09-24 00:12:42	f
78f54568e6f4ecf8bc7b95cb90ae3faf5501d56392d8b156a5b0ff8c788ea8df8dc99d9f92cfa369	7d7ebc1752da4f37b9ef73b2da062bbaba81203f2c0ea2ef0d313191d6179cdb5595ef9ff6e51636	2023-09-24 00:13:11	f
08979c9d086f68a7dfadea554afbdec7e22ea21c719810f51431d7e29093d73bfb1daac46758bdab	3a6e7e8028dd4cc9cdbe5f4c6068af4882f62da58b93ad03073483a2e14f729114cbbe65105099a7	2023-09-24 00:13:21	f
4c88af788e0a9d3be679d8f3c43584568243a57fdbe5f5e96cbf94fec0eb2eacf6ac8bb8a582b6cc	87c5d39e2bdb6609c667b5bf9357b582f16aaebb4c0c881964cc1efa1e8ccf4491eda364d400e53a	2023-09-24 00:13:41	f
5670d56cf140460847c26c5de4723a0659572ffeb0448bd3484c473dd36e6359006823850863360c	4a6b42771211bef4dc1599e655f40d212f4135e056ef522fde1710950cf6d63c47ff397c74b50ca4	2023-09-24 00:19:34	f
158be8cf2de0c3b3697d27ac0b863feff07afca8ef2b56ca088ebac7b4bbf1066ba34cfbfcc8eaab	febf1b7852f2a19a704f928bb2736e6a8e88a6c01e52c7c79b509dcd98ba8f0954a0c3031670a5d2	2023-09-24 00:21:48	f
84ef19e1d359ce415264a815a94f8eff67160a0bfa3c27d4dfcb54d2b2fcabeb88dda32bd72f75ec	dd87a23d0393fa9b4b6f3d79940d9249859d95eb9f1f2efeb8cd924507c4436c07585a428953525c	2023-09-24 00:39:29	f
3b2f3ffe87a4531c3e9219bea63d95a3c8c7258bc49f49717a5cb4049bd97577c91a2d0ed7dd4aba	9302b80190d625113ee40d91f0baf92c59fb1a201fc3b423fd3c5cc60e0c2ac2f32a1ce56e5246d4	2023-09-24 00:48:49	f
a777dbb7edacd789f0f40e7eff6a5200d55cab08069e46280df898b46fe88b2cc8afa6ee512bb2a5	5e712c6a06c0721c6755e0051cb8bbb469d27cceb2cb47ad4192a68e040a2ca6c0d1d7e15dfb5c3e	2023-09-24 00:50:33	f
cb7b0bca51d3a4c9bdae8b52e8d1eac228c437c4a9ee3750b9f442737af5669f8ac3c7bb95898ce8	24891e73f138806bfa0c04168c0ec2cc0a41f65d2eee7ebed5a5a117de907c2b96bd4cd2cd1f9058	2023-09-28 11:55:13	f
bf36408eb272267f9aa592a6075c8cef34d420b2046154da77ae00d820668ec016f1fcf49fc42197	cb137b79c4cab4eefc497803620eff3250ae9b7b965cf6da54dfd9b979d9ea4f3eef998ed7133710	2023-09-28 12:00:27	f
d2ab8942e4f61d8d1ab7bd91e1bcaab6359ddc15853db017979839cba09d4d92d7661e2168dd0898	fce9370356e6a4a3f67c0bb4bfe2461b174e968f192c1f43aa467e37978856479582557be5adbfc2	2023-09-28 18:47:36	f
\.


--
-- Data for Name: oauth2_user_consent; Type: TABLE DATA; Schema: public; Owner: interview
--

COPY public.oauth2_user_consent (id, client_id, user_id, created, expires, scopes, ip_address) FROM stdin;
1	testclient	1	2023-08-24 00:47:30	2023-09-23 00:47:30	profile,email,cart	172.18.0.1
\.


--
-- Data for Name: order_item; Type: TABLE DATA; Schema: public; Owner: interview
--

COPY public.order_item (order_id, cart_item_entity, order_item_id, cart_item_type) FROM stdin;
46	"{\\n    \\"id\\": 3,\\n    \\"plan_name\\": \\"gold\\",\\n    \\"description\\": \\"Basic plan for all users, that allows platform usage\\",\\n    \\"is_active\\": true,\\n    \\"is_visible\\": true,\\n    \\"unit_price\\": 5000,\\n    \\"created_at\\": \\"2023-05-22T19:49:34+00:00\\",\\n    \\"updated_at\\": \\"2023-05-22T19:49:34+00:00\\"\\n}"	118	plan
46	"{\\n    \\"id\\": 73,\\n    \\"name\\": \\"R\\\\u00f6d Kaviar\\",\\n    \\"supplier_id\\": 17,\\n    \\"category_id\\": 8,\\n    \\"category\\": {\\n        \\"id\\": 8,\\n        \\"name\\": \\"Seafood\\",\\n        \\"description\\": \\"Seaweed and fish\\",\\n        \\"picture\\": \\"Resource id #60\\"\\n    },\\n    \\"quantity_per_unit\\": \\"24 - 150 g jars\\",\\n    \\"unit_price\\": 1500,\\n    \\"units_in_stock\\": 100,\\n    \\"units_on_order\\": 0\\n}"	119	product
47	"{\\n    \\"id\\": 4,\\n    \\"plan_name\\": \\"vip\\",\\n    \\"description\\": \\"exclusive offers for VIP members\\",\\n    \\"is_active\\": false,\\n    \\"is_visible\\": true,\\n    \\"unit_price\\": 10000,\\n    \\"tier\\": 30,\\n    \\"created_at\\": \\"2023-05-22T19:49:34+00:00\\",\\n    \\"deleted_at\\": \\"2023-05-22T19:49:34+00:00\\"\\n}"	120	plan
48	"{\\n    \\"id\\": 76,\\n    \\"name\\": \\"Lakkalik\\\\u00f6\\\\u00f6ri\\",\\n    \\"supplier_id\\": 23,\\n    \\"category_id\\": 1,\\n    \\"category\\": {\\n        \\"id\\": 1,\\n        \\"name\\": \\"Beverages\\",\\n        \\"description\\": \\"Soft drinks, coffees, teas, beers, and ales\\"\\n    },\\n    \\"quantity_per_unit\\": \\"500 ml\\",\\n    \\"unit_price\\": 1800,\\n    \\"units_in_stock\\": 56,\\n    \\"units_on_order\\": 0,\\n    \\"required_subscription\\": {\\n        \\"id\\": 2,\\n        \\"plan_name\\": \\"plus\\",\\n        \\"description\\": \\"Additional plan with more offers for user\\",\\n        \\"is_active\\": true,\\n        \\"is_visible\\": true,\\n        \\"unit_price\\": 1000,\\n        \\"tier\\": 10,\\n        \\"created_at\\": \\"2023-05-22T19:49:34+02:00\\"\\n    }\\n}"	121	product
49	"{\\n    \\"id\\": 76,\\n    \\"name\\": \\"Lakkalik\\\\u00f6\\\\u00f6ri\\",\\n    \\"supplier_id\\": 23,\\n    \\"category_id\\": 1,\\n    \\"category\\": {\\n        \\"id\\": 1,\\n        \\"name\\": \\"Beverages\\",\\n        \\"description\\": \\"Soft drinks, coffees, teas, beers, and ales\\"\\n    },\\n    \\"quantity_per_unit\\": \\"500 ml\\",\\n    \\"unit_price\\": 1800,\\n    \\"units_in_stock\\": 56,\\n    \\"units_on_order\\": 0,\\n    \\"required_subscription\\": {\\n        \\"id\\": 2,\\n        \\"plan_name\\": \\"plus\\",\\n        \\"description\\": \\"Additional plan with more offers for user\\",\\n        \\"is_active\\": true,\\n        \\"is_visible\\": true,\\n        \\"unit_price\\": 1000,\\n        \\"tier\\": 10,\\n        \\"created_at\\": \\"2023-05-22T19:49:34+02:00\\"\\n    }\\n}"	122	product
49	"{\\n    \\"id\\": 3,\\n    \\"plan_name\\": \\"gold\\",\\n    \\"description\\": \\"Basic plan for all users, that allows platform usage\\",\\n    \\"is_active\\": true,\\n    \\"is_visible\\": true,\\n    \\"unit_price\\": 5000,\\n    \\"tier\\": 20,\\n    \\"created_at\\": \\"2023-05-22T19:49:34+02:00\\",\\n    \\"updated_at\\": \\"2023-05-22T19:49:34+02:00\\"\\n}"	123	plan
\.


--
-- Data for Name: order_pending; Type: TABLE DATA; Schema: public; Owner: interview
--

COPY public.order_pending (order_id, user_id, cart_id) FROM stdin;
\.


--
-- Data for Name: orders; Type: TABLE DATA; Schema: public; Owner: interview
--

COPY public.orders (order_id, status, created_at, updated_at, user_id, amount, address_id) FROM stdin;
46	completed	2023-08-23 15:14:21	2023-08-23 15:19:51	1	6500	16
47	completed	2023-08-23 19:25:53	2023-08-23 19:25:54	1	10000	16
49	completed	2023-08-28 21:56:08	2023-08-28 21:58:05	1	6800	16
48	completed	2023-08-28 21:28:18	2023-08-28 22:05:36	1	1800	16
\.


--
-- Data for Name: payment; Type: TABLE DATA; Schema: public; Owner: interview
--

COPY public.payment (user_id, payment_id, operation_number, operation_type, amount, payment_date, created_at, order_id, status) FROM stdin;
1	17	27d12b45-ada4-47fa-a33b-e4b4bb348b29	payment	6500	\N	2023-08-23 15:14:21	46	completed
1	18	4738d475-3a6b-46c3-a43c-182005ad8ab7	payment	10000	\N	2023-08-23 19:25:53	47	completed
1	20	cd5a55de-3ff6-4291-9118-b69222a66205	payment	6800	\N	2023-08-28 21:56:08	49	completed
1	19	6739595e-fdb1-4e9e-ba41-67ed48959ab8	payment	1800	\N	2023-08-28 21:28:18	48	completed
\.


--
-- Data for Name: plan; Type: TABLE DATA; Schema: public; Owner: interview
--

COPY public.plan (plan_id, plan_name, description, is_active, created_at, updated_at, deleted_at, unit_price, is_visible, tier) FROM stdin;
1	freemium	Basic plan for all users, that allows platform usage	t	2023-05-22 19:49:34	\N	\N	0	f	1
2	plus	Additional plan with more offers for user	t	2023-05-22 19:49:34	\N	\N	1000	t	10
3	gold	Basic plan for all users, that allows platform usage	t	2023-05-22 19:49:34	2023-05-22 19:49:34	\N	5000	t	20
4	vip	exclusive offers for VIP members	f	2023-05-22 19:49:34	\N	2023-05-22 19:49:34	10000	t	30
5	500+	Many many discounts, everywhere	t	2023-05-22 19:49:34	\N	\N	15000	t	40
6	800+	They pay You for visiting them	t	2023-05-22 19:49:34	\N	\N	30000	t	50
\.


--
-- Data for Name: product_cart_item; Type: TABLE DATA; Schema: public; Owner: interview
--

COPY public.product_cart_item (cart_item_id, destination_entity_id) FROM stdin;
41	76
42	76
43	76
51	72
52	63
53	58
90	77
92	77
94	72
95	67
96	65
103	73
\.


--
-- Data for Name: products; Type: TABLE DATA; Schema: public; Owner: interview
--

COPY public.products (product_id, product_name, supplier_id, category_id, quantity_per_unit, unit_price, units_in_stock, units_on_order, required_subscription_id) FROM stdin;
75	Rhönbräu Klosterbier	12	1	24 - 0.5 l bottles	775	125	0	3
66	Louisiana Hot Spiced Okra	2	2	24 - 8 oz jars	1700	4	100	1
76	Lakkalikööri	23	1	500 ml	1800	57	0	2
74	Longlife Tofu	4	7	5 kg pkg.	1000	6	20	4
73	Röd Kaviar	17	8	24 - 150 g jars	1500	100	0	5
69	Gudbrandsdalsost	15	4	10 kg pkg.	3600	26	0	1
72	Mozzarella di Giovanni	14	4	24 - 200 g pkgs.	3480	14	0	6
71	Flotemysost	15	4	10 - 500 g pkgs.	2150	26	0	1
70	Outback Lager	7	1	24 - 355 ml bottles	1500	15	10	2
68	Scottish Longbreads	8	3	10 boxes x 8 pieces	1250	6	10	1
67	Laughing Lumberjack Lager	16	1	24 - 12 oz bottles	1400	52	0	1
77	Original Frankfurter grüne Soße	12	2	12 boxes	1300	2	0	1
65	Louisiana Fiery Hot Pepper Sauce	2	2	32 - 8 oz bottles	2105	76	0	3
64	Wimmers gute Semmelknödel	12	5	20 bags x 4 pieces	3325	22	80	4
63	Vegie-spread	7	2	15 - 625 g jars	4390	24	0	1
48	Chocolade	22	3	10 pkgs.	1275	15	70	2
47	Zaanse koeken	22	3	10 - 4 oz boxes	950	36	0	2
46	Spegesild	21	8	4 - 450 g glasses	1200	95	0	4
45	Rogede sild	21	8	1k pkg.	950	5	70	4
44	Gula Malacca	20	2	20 - 2 kg bags	1945	27	0	4
43	Ipoh Coffee	20	1	16 - 500 g tins	4600	17	10	2
42	Singaporean Hokkien Fried Mee	20	5	32 - 1 kg pkgs.	1400	26	0	2
41	Jack's New England Clam Chowder	19	8	12 - 12 oz cans	965	85	0	2
40	Boston Crab Meat	19	8	24 - 4 oz tins	1840	123	0	2
39	Chartreuse verte	18	1	750 cc per bottle	1800	69	0	2
38	Côte de Blaye	18	1	12 - 75 cl bottles	26350	17	0	2
37	Gravad lax	17	8	12 - 500 g pkgs.	2600	11	50	2
36	Inlagd Sill	17	8	24 - 250 g  jars	1900	112	0	2
35	Steeleye Stout	16	1	24 - 12 oz bottles	1800	20	0	2
34	Sasquatch Ale	16	1	24 - 12 oz bottles	1400	111	0	2
33	Geitost	15	4	500 g	250	112	0	2
32	Mascarpone Fabioli	14	4	24 - 200 g pkgs.	3200	9	40	2
31	Gorgonzola Telino	14	4	12 - 100 g pkgs	1250	0	70	2
30	Nord-Ost Matjeshering	13	8	10 - 200 g glasses	2589	10	0	2
29	Thüringer Rostbratwurst	12	6	50 bags x 30 sausgs.	12379	0	0	3
28	Rössle Sauerkraut	12	7	25 - 825 g cans	4560	26	0	3
27	Schoggi Schokolade	11	3	100 - 100 g pieces	4390	49	0	1
61	Sirop d'érable	29	2	24 - 500 ml bottles	2850	113	0	5
60	Camembert Pierrot	28	4	15 - 300 g rounds	3400	19	0	6
58	Escargots de Bourgogne	27	8	24 pieces	1325	62	0	2
57	Ravioli Angelo	26	5	24 - 250 g pkgs.	1950	36	0	2
56	Gnocchi di nonna Alice	26	5	24 - 250 g pkgs.	3800	21	10	2
55	Pâté chinois	25	6	24 boxes x 2 pies	2400	115	0	3
54	Tourtière	25	6	16 pies	745	21	0	2
53	Perth Pasties	24	6	48 pieces	3280	0	0	4
52	Filo Mix	24	5	16 - 2 kg boxes	700	38	0	3
51	Manjimup Dried Apples	24	7	50 - 300 g pkgs.	5300	20	0	3
50	Valkoinen suklaa	23	3	12 - 100 g bars	1625	65	0	3
49	Maxilaku	23	3	24 - 50 g pkgs.	2000	10	60	3
62	Tarte au sucre	29	3	48 pies	4930	17	0	1
59	Raclette Courdavault	28	4	5 kg pkg.	5500	79	0	1
26	Gumbär Gummibärchen	11	3	100 - 250 g bags	3123	15	0	1
25	NuNuCa Nuß-Nougat-Creme	11	3	20 - 450 g glasses	1400	76	0	1
24	Guaraná Fantástica	10	1	12 - 355 ml cans	450	20	0	1
23	Tunnbröd	9	5	12 - 250 g pkgs.	900	61	0	1
22	Gustaf's Knäckebröd	9	5	24 - 500 g pkgs.	2100	104	0	1
21	Sir Rodney's Scones	8	3	24 pkgs. x 4 pieces	1000	3	40	1
20	Sir Rodney's Marmalade	8	3	30 gift boxes	8100	40	0	1
19	Teatime Chocolate Biscuits	8	3	10 boxes x 12 pieces	920	25	0	1
18	Carnarvon Tigers	7	8	16 kg pkg.	6250	42	0	1
17	Alice Mutton	7	6	20 - 1 kg tins	3900	0	0	1
16	Pavlova	7	3	32 - 500 g boxes	1745	29	0	1
15	Genen Shouyu	6	2	24 - 250 ml bottles	1300	39	0	1
14	Tofu	6	7	40 - 100 g pkgs.	2325	35	0	1
13	Konbu	6	8	2 kg box	600	24	0	1
12	Queso Manchego La Pastora	5	4	10 - 500 g pkgs.	3800	86	0	1
9	Mishi Kobe Niku	4	6	18 - 500 g pkgs.	9700	29	0	1
8	Northwoods Cranberry Sauce	3	2	12 - 12 oz jars	4000	6	0	1
7	Uncle Bob's Organic Dried Pears	3	7	12 - 1 lb pkgs.	3000	15	0	1
6	Grandma's Boysenberry Spread	3	2	12 - 8 oz jars	2500	120	0	1
5	Chef Anton's Gumbo Mix	2	2	36 boxes	2135	0	0	1
4	Chef Anton's Cajun Seasoning	2	2	48 - 6 oz jars	2200	53	0	1
3	Aniseed Syrup	1	2	12 - 550 ml bottles	1000	13	70	1
2	Chang	1	1	24 - 12 oz bottles	1900	17	40	1
1	Chai	8	1	10 boxes x 30 bags	1800	39	0	1
10	Ikura	4	8	12 - 200 ml jars	3100	31	0	1
11	Queso Cabrales	5	4	1 kg pkg.	2100	11	30	1
\.


--
-- Data for Name: subscription; Type: TABLE DATA; Schema: public; Owner: interview
--

COPY public.subscription (subscription_id, is_active, created_at, starts_at, ends_at, subscription_plan_id, tier) FROM stdin;
3	f	2023-08-23 16:41:57	\N	\N	1	\N
\.


--
-- Data for Name: subscription_cart_item; Type: TABLE DATA; Schema: public; Owner: interview
--

COPY public.subscription_cart_item (cart_item_id, subscription_id) FROM stdin;
\.


--
-- Data for Name: subscription_plan_cart_item; Type: TABLE DATA; Schema: public; Owner: interview
--

COPY public.subscription_plan_cart_item (cart_item_id, destination_entity_id) FROM stdin;
50	6
56	6
57	1
58	2
91	2
93	5
102	3
104	4
\.


--
-- Data for Name: suppliers; Type: TABLE DATA; Schema: public; Owner: interview
--

COPY public.suppliers (supplier_id, company_name) FROM stdin;
1	Exotic Liquids
2	New Orleans Cajun Delights
3	Grandma Kelly's Homestead
4	Tokyo Traders
5	Cooperativa de Quesos 'Las Cabras'
6	Mayumi's
7	Pavlova, Ltd.
8	Specialty Biscuits, Ltd.
9	PB Knäckebröd AB
10	Refrescos Americanas LTDA
11	Heli Süßwaren GmbH & Co. KG
12	Plutzer Lebensmittelgroßmärkte AG
13	Nord-Ost-Fisch Handelsgesellschaft mbH
14	Formaggi Fortini s.r.l.
15	Norske Meierier
16	Bigfoot Breweries
17	Svensk Sjöföda AB
18	Aux joyeux ecclésiastiques
19	New England Seafood Cannery
20	Leka Trading
21	Lyngbysild
22	Zaanse Snoepfabriek
23	Karkki Oy
24	G'day, Mate
25	Ma Maison
26	Pasta Buttini s.r.l.
27	Escargots Nouveaux
28	Gai pâturage
29	Forêts d'érables
\.


--
-- Data for Name: user_subscription; Type: TABLE DATA; Schema: public; Owner: interview
--

COPY public.user_subscription (subscription_id, user_id, plan_id, purchased_at, started_at, ends_at) FROM stdin;
\.


--
-- Data for Name: voucher; Type: TABLE DATA; Schema: public; Owner: interview
--

COPY public.voucher (voucher_id, code, valid_for, created_at) FROM stdin;
\.


--
-- Name: address_addressid_seq; Type: SEQUENCE SET; Schema: public; Owner: interview
--

SELECT pg_catalog.setval('public.address_addressid_seq', 18, true);


--
-- Name: cart_cart_id_seq; Type: SEQUENCE SET; Schema: public; Owner: interview
--

SELECT pg_catalog.setval('public.cart_cart_id_seq', 20, true);


--
-- Name: cart_item_cart_item_id_seq; Type: SEQUENCE SET; Schema: public; Owner: interview
--

SELECT pg_catalog.setval('public.cart_item_cart_item_id_seq', 134, true);


--
-- Name: categories_categoryid_seq; Type: SEQUENCE SET; Schema: public; Owner: interview
--

SELECT pg_catalog.setval('public.categories_categoryid_seq', 10, false);


--
-- Name: intrv_user_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: interview
--

SELECT pg_catalog.setval('public.intrv_user_user_id_seq', 1, false);


--
-- Name: oauth2_client_profile_id_seq; Type: SEQUENCE SET; Schema: public; Owner: interview
--

SELECT pg_catalog.setval('public.oauth2_client_profile_id_seq', 1, false);


--
-- Name: oauth2_user_consent_id_seq; Type: SEQUENCE SET; Schema: public; Owner: interview
--

SELECT pg_catalog.setval('public.oauth2_user_consent_id_seq', 1, true);


--
-- Name: order_item_order_item_id_seq; Type: SEQUENCE SET; Schema: public; Owner: interview
--

SELECT pg_catalog.setval('public.order_item_order_item_id_seq', 123, true);


--
-- Name: order_orderid_seq; Type: SEQUENCE SET; Schema: public; Owner: interview
--

SELECT pg_catalog.setval('public.order_orderid_seq', 49, true);


--
-- Name: payment_paymentid_seq; Type: SEQUENCE SET; Schema: public; Owner: interview
--

SELECT pg_catalog.setval('public.payment_paymentid_seq', 20, true);


--
-- Name: plan_plan_id_seq; Type: SEQUENCE SET; Schema: public; Owner: interview
--

SELECT pg_catalog.setval('public.plan_plan_id_seq', 6, true);


--
-- Name: products_productid_seq; Type: SEQUENCE SET; Schema: public; Owner: interview
--

SELECT pg_catalog.setval('public.products_productid_seq', 80, false);


--
-- Name: subscription_subscription_id_seq; Type: SEQUENCE SET; Schema: public; Owner: interview
--

SELECT pg_catalog.setval('public.subscription_subscription_id_seq', 3, true);


--
-- Name: suppliers_supplier_id_seq; Type: SEQUENCE SET; Schema: public; Owner: interview
--

SELECT pg_catalog.setval('public.suppliers_supplier_id_seq', 10, false);


--
-- Name: user_subscription_subscription_id_seq; Type: SEQUENCE SET; Schema: public; Owner: interview
--

SELECT pg_catalog.setval('public.user_subscription_subscription_id_seq', 1, false);


--
-- Name: voucher_voucher_id_seq; Type: SEQUENCE SET; Schema: public; Owner: interview
--

SELECT pg_catalog.setval('public.voucher_voucher_id_seq', 1, false);


--
-- Name: address address_pkey; Type: CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.address
    ADD CONSTRAINT address_pkey PRIMARY KEY (address_id);


--
-- Name: cart_item cart_item_id_pk; Type: CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.cart_item
    ADD CONSTRAINT cart_item_id_pk PRIMARY KEY (cart_item_id);


--
-- Name: cart cart_pkey; Type: CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.cart
    ADD CONSTRAINT cart_pkey PRIMARY KEY (cart_id);


--
-- Name: categories categories_pkey; Type: CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (category_id);


--
-- Name: intrv_user intrv_user_pkey; Type: CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.intrv_user
    ADD CONSTRAINT intrv_user_pkey PRIMARY KEY (user_id);


--
-- Name: oauth2_access_token oauth2_access_token_pkey; Type: CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.oauth2_access_token
    ADD CONSTRAINT oauth2_access_token_pkey PRIMARY KEY (identifier);


--
-- Name: oauth2_authorization_code oauth2_authorization_code_pkey; Type: CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.oauth2_authorization_code
    ADD CONSTRAINT oauth2_authorization_code_pkey PRIMARY KEY (identifier);


--
-- Name: oauth2_client oauth2_client_pkey; Type: CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.oauth2_client
    ADD CONSTRAINT oauth2_client_pkey PRIMARY KEY (identifier);


--
-- Name: oauth2_client_profile oauth2_client_profile_pkey; Type: CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.oauth2_client_profile
    ADD CONSTRAINT oauth2_client_profile_pkey PRIMARY KEY (id);


--
-- Name: oauth2_refresh_token oauth2_refresh_token_pkey; Type: CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.oauth2_refresh_token
    ADD CONSTRAINT oauth2_refresh_token_pkey PRIMARY KEY (identifier);


--
-- Name: oauth2_user_consent oauth2_user_consent_pkey; Type: CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.oauth2_user_consent
    ADD CONSTRAINT oauth2_user_consent_pkey PRIMARY KEY (id);


--
-- Name: order_item order_item_pkey; Type: CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.order_item
    ADD CONSTRAINT order_item_pkey PRIMARY KEY (order_item_id);


--
-- Name: order_pending order_pending_id_pk; Type: CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.order_pending
    ADD CONSTRAINT order_pending_id_pk PRIMARY KEY (order_id);


--
-- Name: orders order_pkey; Type: CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.orders
    ADD CONSTRAINT order_pkey PRIMARY KEY (order_id);


--
-- Name: payment payment_pkey; Type: CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.payment
    ADD CONSTRAINT payment_pkey PRIMARY KEY (payment_id);


--
-- Name: products pk_products; Type: CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.products
    ADD CONSTRAINT pk_products PRIMARY KEY (product_id);


--
-- Name: plan plan_pkey; Type: CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.plan
    ADD CONSTRAINT plan_pkey PRIMARY KEY (plan_id);


--
-- Name: product_cart_item product_cart_item_pkey; Type: CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.product_cart_item
    ADD CONSTRAINT product_cart_item_pkey PRIMARY KEY (cart_item_id);


--
-- Name: subscription_cart_item subscription_cart_item_pkey; Type: CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.subscription_cart_item
    ADD CONSTRAINT subscription_cart_item_pkey PRIMARY KEY (cart_item_id);


--
-- Name: subscription subscription_pkey; Type: CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.subscription
    ADD CONSTRAINT subscription_pkey PRIMARY KEY (subscription_id);


--
-- Name: subscription_plan_cart_item subscription_plan_cart_item_pkey; Type: CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.subscription_plan_cart_item
    ADD CONSTRAINT subscription_plan_cart_item_pkey PRIMARY KEY (cart_item_id);


--
-- Name: suppliers suppliers_pkey; Type: CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.suppliers
    ADD CONSTRAINT suppliers_pkey PRIMARY KEY (supplier_id);


--
-- Name: user_subscription user_subscription_pkey; Type: CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.user_subscription
    ADD CONSTRAINT user_subscription_pkey PRIMARY KEY (subscription_id);


--
-- Name: user_subscription user_subscription_user_id_key; Type: CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.user_subscription
    ADD CONSTRAINT user_subscription_user_id_key UNIQUE (user_id);


--
-- Name: voucher voucher_pkey; Type: CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.voucher
    ADD CONSTRAINT voucher_pkey PRIMARY KEY (voucher_id);


--
-- Name: idx_454d9673c7440455; Type: INDEX; Schema: public; Owner: interview
--

CREATE INDEX idx_454d9673c7440455 ON public.oauth2_access_token USING btree (client);


--
-- Name: idx_4dd90732b6a2dd68; Type: INDEX; Schema: public; Owner: interview
--

CREATE INDEX idx_4dd90732b6a2dd68 ON public.oauth2_refresh_token USING btree (access_token);


--
-- Name: idx_509fef5fc7440455; Type: INDEX; Schema: public; Owner: interview
--

CREATE INDEX idx_509fef5fc7440455 ON public.oauth2_authorization_code USING btree (client);


--
-- Name: idx_52ea1f098d9f6d38; Type: INDEX; Schema: public; Owner: interview
--

CREATE INDEX idx_52ea1f098d9f6d38 ON public.order_item USING btree (order_id);


--
-- Name: idx_6a21f6f6d0a5cda7; Type: INDEX; Schema: public; Owner: interview
--

CREATE INDEX idx_6a21f6f6d0a5cda7 ON public.subscription_plan_cart_item USING btree (destination_entity_id);


--
-- Name: idx_6d28840d8d9f6d38; Type: INDEX; Schema: public; Owner: interview
--

CREATE INDEX idx_6d28840d8d9f6d38 ON public.payment USING btree (order_id);


--
-- Name: idx_9e5e93aad0a5cda7; Type: INDEX; Schema: public; Owner: interview
--

CREATE INDEX idx_9e5e93aad0a5cda7 ON public.product_cart_item USING btree (destination_entity_id);


--
-- Name: idx_a3c664d39b8ce200; Type: INDEX; Schema: public; Owner: interview
--

CREATE INDEX idx_a3c664d39b8ce200 ON public.subscription USING btree (subscription_plan_id);


--
-- Name: idx_b3ba5a5a12469de2; Type: INDEX; Schema: public; Owner: interview
--

CREATE INDEX idx_b3ba5a5a12469de2 ON public.products USING btree (category_id);


--
-- Name: idx_b3ba5a5acb1d096a; Type: INDEX; Schema: public; Owner: interview
--

CREATE INDEX idx_b3ba5a5acb1d096a ON public.products USING btree (required_subscription_id);


--
-- Name: idx_ba388b7a76ed395; Type: INDEX; Schema: public; Owner: interview
--

CREATE INDEX idx_ba388b7a76ed395 ON public.cart USING btree (user_id);


--
-- Name: idx_c241e79b9a1887dc; Type: INDEX; Schema: public; Owner: interview
--

CREATE INDEX idx_c241e79b9a1887dc ON public.subscription_cart_item USING btree (subscription_id);


--
-- Name: idx_c8f05d0119eb6921; Type: INDEX; Schema: public; Owner: interview
--

CREATE INDEX idx_c8f05d0119eb6921 ON public.oauth2_user_consent USING btree (client_id);


--
-- Name: idx_c8f05d01a76ed395; Type: INDEX; Schema: public; Owner: interview
--

CREATE INDEX idx_c8f05d01a76ed395 ON public.oauth2_user_consent USING btree (user_id);


--
-- Name: idx_d4e6f81a76ed395; Type: INDEX; Schema: public; Owner: interview
--

CREATE INDEX idx_d4e6f81a76ed395 ON public.address USING btree (user_id);


--
-- Name: idx_e52ffdeea76ed395; Type: INDEX; Schema: public; Owner: interview
--

CREATE INDEX idx_e52ffdeea76ed395 ON public.orders USING btree (user_id);


--
-- Name: idx_e52ffdeef5b7af75; Type: INDEX; Schema: public; Owner: interview
--

CREATE INDEX idx_e52ffdeef5b7af75 ON public.orders USING btree (address_id);


--
-- Name: idx_f0fe25271ad5cdbf; Type: INDEX; Schema: public; Owner: interview
--

CREATE INDEX idx_f0fe25271ad5cdbf ON public.cart_item USING btree (cart_id);


--
-- Name: plan_created_at_index; Type: INDEX; Schema: public; Owner: interview
--

CREATE INDEX plan_created_at_index ON public.user_subscription USING btree (purchased_at);


--
-- Name: subscriptions_user_idx; Type: INDEX; Schema: public; Owner: interview
--

CREATE INDEX subscriptions_user_idx ON public.user_subscription USING btree (user_id);


--
-- Name: u_plan_idx; Type: INDEX; Schema: public; Owner: interview
--

CREATE INDEX u_plan_idx ON public.plan USING btree (plan_name);


--
-- Name: uniq_9b524e1f19eb6921; Type: INDEX; Schema: public; Owner: interview
--

CREATE UNIQUE INDEX uniq_9b524e1f19eb6921 ON public.oauth2_client_profile USING btree (client_id);


--
-- Name: uniq_c2dbfc019a1887dc; Type: INDEX; Schema: public; Owner: interview
--

CREATE UNIQUE INDEX uniq_c2dbfc019a1887dc ON public.intrv_user USING btree (subscription_id);


--
-- Name: oauth2_access_token fk_454d9673c7440455; Type: FK CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.oauth2_access_token
    ADD CONSTRAINT fk_454d9673c7440455 FOREIGN KEY (client) REFERENCES public.oauth2_client(identifier) ON DELETE CASCADE;


--
-- Name: oauth2_refresh_token fk_4dd90732b6a2dd68; Type: FK CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.oauth2_refresh_token
    ADD CONSTRAINT fk_4dd90732b6a2dd68 FOREIGN KEY (access_token) REFERENCES public.oauth2_access_token(identifier) ON DELETE SET NULL;


--
-- Name: oauth2_authorization_code fk_509fef5fc7440455; Type: FK CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.oauth2_authorization_code
    ADD CONSTRAINT fk_509fef5fc7440455 FOREIGN KEY (client) REFERENCES public.oauth2_client(identifier) ON DELETE CASCADE;


--
-- Name: order_item fk_52ea1f098d9f6d38; Type: FK CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.order_item
    ADD CONSTRAINT fk_52ea1f098d9f6d38 FOREIGN KEY (order_id) REFERENCES public.orders(order_id);


--
-- Name: subscription_plan_cart_item fk_6a21f6f6d0a5cda7; Type: FK CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.subscription_plan_cart_item
    ADD CONSTRAINT fk_6a21f6f6d0a5cda7 FOREIGN KEY (destination_entity_id) REFERENCES public.plan(plan_id);


--
-- Name: subscription_plan_cart_item fk_6a21f6f6e9b59a59; Type: FK CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.subscription_plan_cart_item
    ADD CONSTRAINT fk_6a21f6f6e9b59a59 FOREIGN KEY (cart_item_id) REFERENCES public.cart_item(cart_item_id) ON DELETE CASCADE;


--
-- Name: payment fk_6d28840d8d9f6d38; Type: FK CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.payment
    ADD CONSTRAINT fk_6d28840d8d9f6d38 FOREIGN KEY (order_id) REFERENCES public.orders(order_id);


--
-- Name: oauth2_client_profile fk_9b524e1f19eb6921; Type: FK CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.oauth2_client_profile
    ADD CONSTRAINT fk_9b524e1f19eb6921 FOREIGN KEY (client_id) REFERENCES public.oauth2_client(identifier);


--
-- Name: product_cart_item fk_9e5e93aad0a5cda7; Type: FK CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.product_cart_item
    ADD CONSTRAINT fk_9e5e93aad0a5cda7 FOREIGN KEY (destination_entity_id) REFERENCES public.products(product_id);


--
-- Name: product_cart_item fk_9e5e93aae9b59a59; Type: FK CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.product_cart_item
    ADD CONSTRAINT fk_9e5e93aae9b59a59 FOREIGN KEY (cart_item_id) REFERENCES public.cart_item(cart_item_id) ON DELETE CASCADE;


--
-- Name: subscription fk_a3c664d39b8ce200; Type: FK CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.subscription
    ADD CONSTRAINT fk_a3c664d39b8ce200 FOREIGN KEY (subscription_plan_id) REFERENCES public.plan(plan_id);


--
-- Name: products fk_b3ba5a5a12469de2; Type: FK CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.products
    ADD CONSTRAINT fk_b3ba5a5a12469de2 FOREIGN KEY (category_id) REFERENCES public.categories(category_id);


--
-- Name: products fk_b3ba5a5acb1d096a; Type: FK CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.products
    ADD CONSTRAINT fk_b3ba5a5acb1d096a FOREIGN KEY (required_subscription_id) REFERENCES public.plan(plan_id);


--
-- Name: cart fk_ba388b7a76ed395; Type: FK CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.cart
    ADD CONSTRAINT fk_ba388b7a76ed395 FOREIGN KEY (user_id) REFERENCES public.intrv_user(user_id);


--
-- Name: subscription_cart_item fk_c241e79b9a1887dc; Type: FK CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.subscription_cart_item
    ADD CONSTRAINT fk_c241e79b9a1887dc FOREIGN KEY (subscription_id) REFERENCES public.plan(plan_id);


--
-- Name: subscription_cart_item fk_c241e79be9b59a59; Type: FK CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.subscription_cart_item
    ADD CONSTRAINT fk_c241e79be9b59a59 FOREIGN KEY (cart_item_id) REFERENCES public.cart_item(cart_item_id) ON DELETE CASCADE;


--
-- Name: intrv_user fk_c2dbfc019a1887dc; Type: FK CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.intrv_user
    ADD CONSTRAINT fk_c2dbfc019a1887dc FOREIGN KEY (subscription_id) REFERENCES public.subscription(subscription_id);


--
-- Name: oauth2_user_consent fk_c8f05d0119eb6921; Type: FK CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.oauth2_user_consent
    ADD CONSTRAINT fk_c8f05d0119eb6921 FOREIGN KEY (client_id) REFERENCES public.oauth2_client(identifier);


--
-- Name: oauth2_user_consent fk_c8f05d01a76ed395; Type: FK CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.oauth2_user_consent
    ADD CONSTRAINT fk_c8f05d01a76ed395 FOREIGN KEY (user_id) REFERENCES public.intrv_user(user_id);


--
-- Name: address fk_d4e6f81a76ed395; Type: FK CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.address
    ADD CONSTRAINT fk_d4e6f81a76ed395 FOREIGN KEY (user_id) REFERENCES public.intrv_user(user_id);


--
-- Name: orders fk_e52ffdeef5b7af75; Type: FK CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.orders
    ADD CONSTRAINT fk_e52ffdeef5b7af75 FOREIGN KEY (address_id) REFERENCES public.address(address_id);


--
-- Name: cart_item fk_f0fe25271ad5cdbf; Type: FK CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.cart_item
    ADD CONSTRAINT fk_f0fe25271ad5cdbf FOREIGN KEY (cart_id) REFERENCES public.cart(cart_id);


--
-- Name: orders fk_f5299398a76ed395; Type: FK CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.orders
    ADD CONSTRAINT fk_f5299398a76ed395 FOREIGN KEY (user_id) REFERENCES public.intrv_user(user_id);


--
-- Name: payment payment_user_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.payment
    ADD CONSTRAINT payment_user_id_fk FOREIGN KEY (user_id) REFERENCES public.intrv_user(user_id);


--
-- Name: user_subscription subscription_plan_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.user_subscription
    ADD CONSTRAINT subscription_plan_id_fk FOREIGN KEY (plan_id) REFERENCES public.plan(plan_id);


--
-- Name: user_subscription subscription_user_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: interview
--

ALTER TABLE ONLY public.user_subscription
    ADD CONSTRAINT subscription_user_id_fk FOREIGN KEY (user_id) REFERENCES public.intrv_user(user_id);


--
-- PostgreSQL database dump complete
--
