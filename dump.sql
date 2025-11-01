--
-- PostgreSQL database dump
--

-- Dumped from database version 17.5
-- Dumped by pg_dump version 17.5

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
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
-- Name: cache; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache OWNER TO postgres;

--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO postgres;

--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO postgres;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.failed_jobs_id_seq OWNER TO postgres;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


ALTER TABLE public.job_batches OWNER TO postgres;

--
-- Name: jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE public.jobs OWNER TO postgres;

--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jobs_id_seq OWNER TO postgres;

--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO postgres;

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO postgres;

--
-- Name: production_logs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.production_logs (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    production_order_id bigint NOT NULL,
    status_from character varying(255),
    status_to character varying(255) NOT NULL,
    notes text,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.production_logs OWNER TO postgres;

--
-- Name: production_logs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.production_logs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.production_logs_id_seq OWNER TO postgres;

--
-- Name: production_logs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.production_logs_id_seq OWNED BY public.production_logs.id;


--
-- Name: production_orders; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.production_orders (
    id bigint NOT NULL,
    product_id bigint NOT NULL,
    production_plan_id bigint NOT NULL,
    quantity_planned integer NOT NULL,
    quantity_actual integer,
    quantity_rejected integer,
    status character varying(255) NOT NULL,
    deadline date,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT production_orders_status_check CHECK (((status)::text = ANY ((ARRAY['WAITING'::character varying, 'IN_PROGRESS'::character varying, 'COMPLETED'::character varying, 'CANCELED'::character varying])::text[])))
);


ALTER TABLE public.production_orders OWNER TO postgres;

--
-- Name: production_orders_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.production_orders_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.production_orders_id_seq OWNER TO postgres;

--
-- Name: production_orders_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.production_orders_id_seq OWNED BY public.production_orders.id;


--
-- Name: production_plans; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.production_plans (
    id bigint NOT NULL,
    product_id bigint NOT NULL,
    status character varying(255) NOT NULL,
    deadline timestamp(0) without time zone,
    quantity integer NOT NULL,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT production_plans_status_check CHECK (((status)::text = ANY ((ARRAY['CREATED'::character varying, 'NEEDS_APPROVAL'::character varying, 'APPROVED'::character varying, 'DECLINED'::character varying, 'PROCESSED'::character varying])::text[])))
);


ALTER TABLE public.production_plans OWNER TO postgres;

--
-- Name: production_plans_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.production_plans_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.production_plans_id_seq OWNER TO postgres;

--
-- Name: production_plans_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.production_plans_id_seq OWNED BY public.production_plans.id;


--
-- Name: products; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.products (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    description text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.products OWNER TO postgres;

--
-- Name: products_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.products_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.products_id_seq OWNER TO postgres;

--
-- Name: products_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.products_id_seq OWNED BY public.products.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO postgres;

--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    role character varying(255) NOT NULL,
    department character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT users_department_check CHECK (((department)::text = ANY ((ARRAY['PPIC'::character varying, 'PRODUCTION'::character varying])::text[]))),
    CONSTRAINT users_role_check CHECK (((role)::text = ANY ((ARRAY['MANAGER'::character varying, 'STAFF'::character varying])::text[])))
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: production_logs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.production_logs ALTER COLUMN id SET DEFAULT nextval('public.production_logs_id_seq'::regclass);


--
-- Name: production_orders id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.production_orders ALTER COLUMN id SET DEFAULT nextval('public.production_orders_id_seq'::regclass);


--
-- Name: production_plans id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.production_plans ALTER COLUMN id SET DEFAULT nextval('public.production_plans_id_seq'::regclass);


--
-- Name: products id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.products ALTER COLUMN id SET DEFAULT nextval('public.products_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache (key, value, expiration) FROM stdin;
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2025_10_26_111037_create_products_table	1
5	2025_10_27_110704_create_production_plans_table	1
6	2025_10_28_095536_create_production_orders_table	1
7	2025_10_28_100547_create_production_logs_table	1
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: production_logs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.production_logs (id, user_id, production_order_id, status_from, status_to, notes, created_at) FROM stdin;
1	3	1	\N	WAITING	Order dibuat	2025-10-31 05:18:03
2	3	2	\N	WAITING	Order dibuat	2025-10-31 08:54:08
3	3	3	\N	WAITING	Order dibuat	2025-10-31 08:54:15
4	3	4	\N	WAITING	Order dibuat	2025-10-31 15:08:23
5	3	5	\N	WAITING	Order dibuat	2025-10-31 15:45:00
6	3	6	\N	WAITING	Order dibuat	2025-10-31 17:00:02
7	3	7	\N	WAITING	Order dibuat	2025-10-31 17:00:28
8	2	1	WAITING	IN_PROGRESS	Status diubah dari WAITING menjadi IN_PROGRESS.	2025-10-31 23:54:33
9	2	6	WAITING	IN_PROGRESS	Status diubah dari WAITING menjadi IN_PROGRESS.	2025-11-01 00:16:51
10	2	5	WAITING	IN_PROGRESS	Status diubah dari WAITING menjadi IN_PROGRESS.	2025-11-01 00:16:57
11	2	5	IN_PROGRESS	CANCELED	Order dibatalkan	2025-11-01 00:17:02
12	2	2	WAITING	IN_PROGRESS	Status diubah dari WAITING menjadi IN_PROGRESS.	2025-11-01 02:00:13
13	2	6	IN_PROGRESS	COMPLETED	Hasil jadi: 0, hasil reject: 0	2025-11-01 02:00:19
14	2	4	WAITING	IN_PROGRESS	Status diubah dari WAITING menjadi IN_PROGRESS.	2025-11-01 02:02:23
15	2	4	IN_PROGRESS	COMPLETED	Hasil jadi: 0, hasil reject: 0	2025-11-01 02:02:37
16	2	3	WAITING	IN_PROGRESS	Status diubah dari WAITING menjadi IN_PROGRESS.	2025-11-01 02:04:14
17	2	7	WAITING	IN_PROGRESS	Status diubah dari WAITING menjadi IN_PROGRESS.	2025-11-01 02:27:09
18	2	1	IN_PROGRESS	COMPLETED	Hasil jadi: 90, hasil reject: 10	2025-11-01 02:42:10
19	2	7	IN_PROGRESS	COMPLETED	Hasil jadi: 8, hasil reject: 92	2025-11-01 02:42:40
\.


--
-- Data for Name: production_orders; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.production_orders (id, product_id, production_plan_id, quantity_planned, quantity_actual, quantity_rejected, status, deadline, created_at, updated_at) FROM stdin;
5	2	7	100	\N	\N	CANCELED	2025-11-30	2025-10-31 15:45:00	2025-11-01 00:17:02
2	2	3	100	\N	\N	IN_PROGRESS	2025-11-26	2025-10-31 08:54:08	2025-11-01 02:00:13
6	2	10	100	0	0	COMPLETED	2025-11-14	2025-10-31 17:00:02	2025-11-01 02:00:19
4	2	4	100	0	0	COMPLETED	2025-11-30	2025-10-31 15:08:23	2025-11-01 02:02:37
3	2	4	100	\N	\N	IN_PROGRESS	2025-11-30	2025-10-31 08:54:15	2025-11-01 02:04:14
1	2	1	100	90	10	COMPLETED	2025-11-04	2025-10-31 05:18:03	2025-11-01 02:42:10
7	2	2	100	8	92	COMPLETED	2025-11-15	2025-10-31 17:00:28	2025-11-01 02:42:40
\.


--
-- Data for Name: production_plans; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.production_plans (id, product_id, status, deadline, quantity, notes, created_at, updated_at) FROM stdin;
1	2	APPROVED	2025-11-04 16:00:00	100	\N	2025-10-31 02:35:25	2025-10-31 05:18:03
9	2	CREATED	\N	100	\N	2025-10-31 08:39:20	2025-10-31 08:39:20
3	2	APPROVED	2025-11-26 16:00:00	100	\N	2025-10-31 08:39:16	2025-10-31 08:54:08
14	7	CREATED	\N	88	CITO, SEKARANG!	2025-10-31 13:44:08	2025-10-31 13:49:35
4	2	APPROVED	2025-11-30 16:00:00	100	\N	2025-10-31 08:39:17	2025-10-31 15:08:23
5	2	CREATED	\N	100	\N	2025-10-31 08:39:18	2025-10-31 15:14:50
7	2	APPROVED	2025-11-30 16:00:00	100	\N	2025-10-31 08:39:19	2025-10-31 15:45:00
13	6	DECLINED	2025-11-07 16:00:00	9	\N	2025-10-31 12:50:04	2025-10-31 15:46:52
8	2	NEEDS_APPROVAL	\N	100	\N	2025-10-31 08:39:20	2025-10-31 15:47:15
6	2	NEEDS_APPROVAL	\N	100	\N	2025-10-31 08:39:18	2025-10-31 15:47:26
10	2	APPROVED	2025-11-14 16:00:00	100	\N	2025-10-31 08:39:21	2025-10-31 17:00:02
2	2	APPROVED	2025-11-15 16:00:00	100	\N	2025-10-31 08:39:16	2025-10-31 17:00:28
\.


--
-- Data for Name: products; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.products (id, name, description, created_at, updated_at) FROM stdin;
1	et adipisci est	Neque suscipit consequatur perferendis corporis nesciunt. Consequatur soluta deserunt dolores aperiam. Animi est mollitia incidunt.	2025-10-29 09:22:58	2025-10-29 09:22:58
2	praesentium enim ut	Sunt iste mollitia nihil consequatur. Illo facilis laudantium ea. Quia et sint velit.	2025-10-29 09:22:58	2025-10-29 09:22:58
3	accusantium a aut	Ut non et sunt in omnis molestias et. Quaerat incidunt maiores facere exercitationem assumenda vel eveniet. Nulla voluptatem voluptates vero.	2025-10-29 09:22:58	2025-10-29 09:22:58
4	doloribus incidunt sequi	Accusamus nisi doloremque soluta neque ad ratione voluptas. Sapiente beatae laudantium et modi ea sed eligendi quidem. Rem ut dolores molestiae sunt officiis mollitia.	2025-10-29 09:22:58	2025-10-29 09:22:58
5	reiciendis temporibus architecto	Fugit delectus voluptate aut repellat natus. Itaque quis quis est.	2025-10-29 09:22:58	2025-10-29 09:22:58
6	quas id occaecati	Nisi architecto doloribus eum dolores esse nisi. Praesentium voluptatem quas impedit aut quidem laudantium. Eum blanditiis voluptatem perspiciatis minima.	2025-10-29 09:22:58	2025-10-29 09:22:58
7	delectus reprehenderit iure	Itaque nemo ut consequuntur incidunt ipsa reiciendis. Fugiat exercitationem voluptatem ut eligendi et.	2025-10-29 09:22:58	2025-10-29 09:22:58
8	omnis et ipsa	Soluta dolor sint molestiae nisi. Quas ipsam beatae fuga doloribus sed accusamus iusto. Neque accusantium delectus cum quae.	2025-10-29 09:22:58	2025-10-29 09:22:58
9	soluta quae debitis	Beatae repudiandae natus consequatur unde est nobis nam. Eum ipsam est enim alias alias. Sit suscipit qui rerum eum iure et debitis praesentium.	2025-10-29 09:22:58	2025-10-29 09:22:58
10	adipisci qui laborum	Officia eum quasi est hic laudantium perferendis. Expedita est recusandae temporibus ipsam omnis. Qui sed facilis aut voluptas.	2025-10-29 09:22:58	2025-10-29 09:22:58
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
MQfPzFaxFUeRR6pYKa9IyfuLRBPabagEsLttKDJu	\N	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36	YTozOntzOjY6Il90b2tlbiI7czo0MDoid0QzMW5IREhDMjAwU1FEWkt2YnhpVERSUDJtQ2NnZEVpcnRveEVzdSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly9xdWlldC1uZWFybHktYnVsbGZyb2cubmdyb2stZnJlZS5hcHAiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=	1761730995
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, email, role, department, password, created_at, updated_at) FROM stdin;
1	Kevin PPIC	kevin.staff.ppic@gmail.com	STAFF	PPIC	$2y$12$kC2tiI.8vSidMuOHMzorIeYa1OAmlYykgYkJFb9n78nuaOkAuFxmi	2025-10-29 09:22:58	2025-10-29 09:22:58
2	Kevin Produksi	kevin.staff.production@gmail.com	STAFF	PRODUCTION	$2y$12$kC2tiI.8vSidMuOHMzorIeYa1OAmlYykgYkJFb9n78nuaOkAuFxmi	2025-10-29 09:22:58	2025-10-29 09:22:58
3	Kevin Manager Produksi	kevin.manager.production@gmail.com	MANAGER	PRODUCTION	$2y$12$kC2tiI.8vSidMuOHMzorIeYa1OAmlYykgYkJFb9n78nuaOkAuFxmi	2025-10-29 09:22:58	2025-10-29 09:22:58
4	Kevin Manager PPIC	kevin.manager.ppic@gmail.com	MANAGER	PPIC	$2y$12$kC2tiI.8vSidMuOHMzorIeYa1OAmlYykgYkJFb9n78nuaOkAuFxmi	2025-10-29 09:22:58	2025-10-29 09:22:58
\.


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 7, true);


--
-- Name: production_logs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.production_logs_id_seq', 19, true);


--
-- Name: production_orders_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.production_orders_id_seq', 7, true);


--
-- Name: production_plans_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.production_plans_id_seq', 14, true);


--
-- Name: products_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.products_id_seq', 10, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 4, true);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: production_logs production_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.production_logs
    ADD CONSTRAINT production_logs_pkey PRIMARY KEY (id);


--
-- Name: production_orders production_orders_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.production_orders
    ADD CONSTRAINT production_orders_pkey PRIMARY KEY (id);


--
-- Name: production_plans production_plans_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.production_plans
    ADD CONSTRAINT production_plans_pkey PRIMARY KEY (id);


--
-- Name: products products_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.products
    ADD CONSTRAINT products_pkey PRIMARY KEY (id);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: production_logs production_logs_production_order_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.production_logs
    ADD CONSTRAINT production_logs_production_order_id_foreign FOREIGN KEY (production_order_id) REFERENCES public.production_orders(id) ON DELETE CASCADE;


--
-- Name: production_logs production_logs_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.production_logs
    ADD CONSTRAINT production_logs_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id);


--
-- Name: production_orders production_orders_product_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.production_orders
    ADD CONSTRAINT production_orders_product_id_foreign FOREIGN KEY (product_id) REFERENCES public.products(id);


--
-- Name: production_orders production_orders_production_plan_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.production_orders
    ADD CONSTRAINT production_orders_production_plan_id_foreign FOREIGN KEY (production_plan_id) REFERENCES public.production_plans(id);


--
-- Name: production_plans production_plans_product_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.production_plans
    ADD CONSTRAINT production_plans_product_id_foreign FOREIGN KEY (product_id) REFERENCES public.products(id);


--
-- PostgreSQL database dump complete
--

