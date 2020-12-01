--
-- PostgreSQL database dump
--

-- Dumped from database version 12.4
-- Dumped by pg_dump version 12.4

-- Started on 2020-11-30 21:35:58

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
-- TOC entry 207 (class 1259 OID 30716)
-- Name: oauth_access_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.oauth_access_tokens (
    access_token character varying(40) NOT NULL,
    client_id character varying(80) NOT NULL,
    user_id character varying(80),
    expires timestamp without time zone NOT NULL,
    scope character varying(4000)
);


ALTER TABLE public.oauth_access_tokens OWNER TO postgres;

--
-- TOC entry 208 (class 1259 OID 30724)
-- Name: oauth_authorization_codes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.oauth_authorization_codes (
    authorization_code character varying(40) NOT NULL,
    client_id character varying(80) NOT NULL,
    user_id character varying(80),
    redirect_uri character varying(2000),
    expires timestamp without time zone NOT NULL,
    scope character varying(4000),
    id_token character varying(1000)
);


ALTER TABLE public.oauth_authorization_codes OWNER TO postgres;

--
-- TOC entry 206 (class 1259 OID 30708)
-- Name: oauth_clients; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.oauth_clients (
    client_id character varying(80) NOT NULL,
    client_secret character varying(80),
    redirect_uri character varying(2000),
    grant_types character varying(80),
    scope character varying(4000),
    user_id character varying(80)
);


ALTER TABLE public.oauth_clients OWNER TO postgres;

--
-- TOC entry 212 (class 1259 OID 30753)
-- Name: oauth_jwt; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.oauth_jwt (
    client_id character varying(80) NOT NULL,
    subject character varying(80),
    public_key character varying(2000) NOT NULL
);


ALTER TABLE public.oauth_jwt OWNER TO postgres;

--
-- TOC entry 209 (class 1259 OID 30732)
-- Name: oauth_refresh_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.oauth_refresh_tokens (
    refresh_token character varying(40) NOT NULL,
    client_id character varying(80) NOT NULL,
    user_id character varying(80),
    expires timestamp without time zone NOT NULL,
    scope character varying(4000)
);


ALTER TABLE public.oauth_refresh_tokens OWNER TO postgres;

--
-- TOC entry 211 (class 1259 OID 30748)
-- Name: oauth_scopes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.oauth_scopes (
    scope character varying(80) NOT NULL,
    is_default boolean
);


ALTER TABLE public.oauth_scopes OWNER TO postgres;

--
-- TOC entry 210 (class 1259 OID 30740)
-- Name: oauth_users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.oauth_users (
    username character varying(80) NOT NULL,
    password character varying(80),
    first_name character varying(80),
    last_name character varying(80),
    email character varying(80),
    email_verified boolean,
    scope character varying(4000)
);


ALTER TABLE public.oauth_users OWNER TO postgres;

--
-- TOC entry 202 (class 1259 OID 22493)
-- Name: sent_emails; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sent_emails (
    id bigint NOT NULL,
    payload text NOT NULL,
    sent_at timestamp without time zone
);


ALTER TABLE public.sent_emails OWNER TO postgres;

--
-- TOC entry 204 (class 1259 OID 22529)
-- Name: sent_emails_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.sent_emails_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sent_emails_id_seq OWNER TO postgres;

--
-- TOC entry 2889 (class 0 OID 0)
-- Dependencies: 204
-- Name: sent_emails_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.sent_emails_id_seq OWNED BY public.sent_emails.id;


--
-- TOC entry 203 (class 1259 OID 22505)
-- Name: unsent_emails; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.unsent_emails (
    id bigint NOT NULL,
    payload text NOT NULL,
    failed_at timestamp without time zone
);


ALTER TABLE public.unsent_emails OWNER TO postgres;

--
-- TOC entry 205 (class 1259 OID 22557)
-- Name: unsent_emails_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.unsent_emails_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.unsent_emails_id_seq OWNER TO postgres;

--
-- TOC entry 2890 (class 0 OID 0)
-- Dependencies: 205
-- Name: unsent_emails_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.unsent_emails_id_seq OWNED BY public.unsent_emails.id;


--
-- TOC entry 2729 (class 2604 OID 22531)
-- Name: sent_emails id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sent_emails ALTER COLUMN id SET DEFAULT nextval('public.sent_emails_id_seq'::regclass);


--
-- TOC entry 2730 (class 2604 OID 22559)
-- Name: unsent_emails id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.unsent_emails ALTER COLUMN id SET DEFAULT nextval('public.unsent_emails_id_seq'::regclass);


--
-- TOC entry 2878 (class 0 OID 30716)
-- Dependencies: 207
-- Data for Name: oauth_access_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2879 (class 0 OID 30724)
-- Dependencies: 208
-- Data for Name: oauth_authorization_codes; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2877 (class 0 OID 30708)
-- Dependencies: 206
-- Data for Name: oauth_clients; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.oauth_clients (client_id, client_secret, redirect_uri, grant_types, scope, user_id) VALUES ('agam', 'testpass', 'http://fake', NULL, NULL, NULL);


--
-- TOC entry 2883 (class 0 OID 30753)
-- Dependencies: 212
-- Data for Name: oauth_jwt; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2880 (class 0 OID 30732)
-- Dependencies: 209
-- Data for Name: oauth_refresh_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2882 (class 0 OID 30748)
-- Dependencies: 211
-- Data for Name: oauth_scopes; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2881 (class 0 OID 30740)
-- Dependencies: 210
-- Data for Name: oauth_users; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2873 (class 0 OID 22493)
-- Dependencies: 202
-- Data for Name: sent_emails; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2874 (class 0 OID 22505)
-- Dependencies: 203
-- Data for Name: unsent_emails; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2891 (class 0 OID 0)
-- Dependencies: 204
-- Name: sent_emails_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.sent_emails_id_seq', 59, true);


--
-- TOC entry 2892 (class 0 OID 0)
-- Dependencies: 205
-- Name: unsent_emails_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.unsent_emails_id_seq', 47, true);


--
-- TOC entry 2738 (class 2606 OID 30723)
-- Name: oauth_access_tokens oauth_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.oauth_access_tokens
    ADD CONSTRAINT oauth_access_tokens_pkey PRIMARY KEY (access_token);


--
-- TOC entry 2740 (class 2606 OID 30731)
-- Name: oauth_authorization_codes oauth_authorization_codes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.oauth_authorization_codes
    ADD CONSTRAINT oauth_authorization_codes_pkey PRIMARY KEY (authorization_code);


--
-- TOC entry 2736 (class 2606 OID 30715)
-- Name: oauth_clients oauth_clients_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.oauth_clients
    ADD CONSTRAINT oauth_clients_pkey PRIMARY KEY (client_id);


--
-- TOC entry 2742 (class 2606 OID 30739)
-- Name: oauth_refresh_tokens oauth_refresh_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.oauth_refresh_tokens
    ADD CONSTRAINT oauth_refresh_tokens_pkey PRIMARY KEY (refresh_token);


--
-- TOC entry 2746 (class 2606 OID 30752)
-- Name: oauth_scopes oauth_scopes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.oauth_scopes
    ADD CONSTRAINT oauth_scopes_pkey PRIMARY KEY (scope);


--
-- TOC entry 2744 (class 2606 OID 30747)
-- Name: oauth_users oauth_users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.oauth_users
    ADD CONSTRAINT oauth_users_pkey PRIMARY KEY (username);


--
-- TOC entry 2732 (class 2606 OID 22539)
-- Name: sent_emails sent_emails_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sent_emails
    ADD CONSTRAINT sent_emails_pkey PRIMARY KEY (id);


--
-- TOC entry 2734 (class 2606 OID 22567)
-- Name: unsent_emails unsent_emails_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.unsent_emails
    ADD CONSTRAINT unsent_emails_pkey PRIMARY KEY (id);


-- Completed on 2020-11-30 21:35:59

--
-- PostgreSQL database dump complete
--

