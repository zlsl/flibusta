--
-- PostgreSQL database dump
--

-- Dumped from database version 13.4 (Debian 13.4-4.pgdg110+1)
-- Dumped by pg_dump version 13.6

-- Started on 2022-03-30 10:39:37 UTC

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
-- TOC entry 2 (class 3079 OID 16386)
-- Name: pg_trgm; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS pg_trgm WITH SCHEMA public;


--
-- TOC entry 3276 (class 0 OID 0)
-- Dependencies: 2
-- Name: EXTENSION pg_trgm; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION pg_trgm IS 'text similarity measurement and index searching based on trigrams';


--
-- TOC entry 3 (class 3079 OID 2183851)
-- Name: uuid-ossp; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS "uuid-ossp" WITH SCHEMA public;


--
-- TOC entry 3277 (class 0 OID 0)
-- Dependencies: 3
-- Name: EXTENSION "uuid-ossp"; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION "uuid-ossp" IS 'generate universally unique identifiers (UUIDs)';


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 202 (class 1259 OID 16490)
-- Name: book_zip; Type: TABLE; Schema: public; Owner: flibusta
--

CREATE TABLE public.book_zip (
    id bigint NOT NULL,
    filename character varying(64) NOT NULL,
    start_id bigint NOT NULL,
    end_id bigint NOT NULL,
    usr bigint DEFAULT '0'::bigint NOT NULL
);


ALTER TABLE public.book_zip OWNER TO flibusta;

--
-- TOC entry 203 (class 1259 OID 16494)
-- Name: book_zip_id_seq; Type: SEQUENCE; Schema: public; Owner: flibusta
--

CREATE SEQUENCE public.book_zip_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.book_zip_id_seq OWNER TO flibusta;

--
-- TOC entry 3278 (class 0 OID 0)
-- Dependencies: 203
-- Name: book_zip_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: flibusta
--

ALTER SEQUENCE public.book_zip_id_seq OWNED BY public.book_zip.id;


--
-- TOC entry 237 (class 1259 OID 206113)
-- Name: fav; Type: TABLE; Schema: public; Owner: flibusta
--

CREATE TABLE public.fav (
    id integer NOT NULL,
    user_uuid uuid,
    bookid bigint,
    avtorid bigint,
    seqid bigint
);


ALTER TABLE public.fav OWNER TO flibusta;

--
-- TOC entry 236 (class 1259 OID 206111)
-- Name: fav_id_seq; Type: SEQUENCE; Schema: public; Owner: flibusta
--

CREATE SEQUENCE public.fav_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.fav_id_seq OWNER TO flibusta;

--
-- TOC entry 3279 (class 0 OID 0)
-- Dependencies: 236
-- Name: fav_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: flibusta
--

ALTER SEQUENCE public.fav_id_seq OWNED BY public.fav.id;


--
-- TOC entry 238 (class 1259 OID 206121)
-- Name: fav_users; Type: TABLE; Schema: public; Owner: flibusta
--

CREATE TABLE public.fav_users (
    user_uuid uuid NOT NULL,
    name character varying(32) NOT NULL
);


ALTER TABLE public.fav_users OWNER TO flibusta;

--
-- TOC entry 204 (class 1259 OID 16530)
-- Name: libaannotations; Type: TABLE; Schema: public; Owner: flibusta
--

CREATE TABLE public.libaannotations (
    avtorid bigint NOT NULL,
    nid bigint NOT NULL,
    title character varying(255) NOT NULL,
    body text
);


ALTER TABLE public.libaannotations OWNER TO flibusta;

--
-- TOC entry 205 (class 1259 OID 16536)
-- Name: libapics; Type: TABLE; Schema: public; Owner: flibusta
--

CREATE TABLE public.libapics (
    avtorid bigint NOT NULL,
    nid bigint NOT NULL,
    file character varying(255) NOT NULL
);


ALTER TABLE public.libapics OWNER TO flibusta;

--
-- TOC entry 206 (class 1259 OID 16539)
-- Name: libavtor; Type: TABLE; Schema: public; Owner: flibusta
--

CREATE TABLE public.libavtor (
    bookid bigint DEFAULT '0'::bigint NOT NULL,
    avtorid bigint DEFAULT '0'::bigint NOT NULL,
    pos smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.libavtor OWNER TO flibusta;

--
-- TOC entry 207 (class 1259 OID 16545)
-- Name: libavtoraliase; Type: TABLE; Schema: public; Owner: flibusta
--

CREATE TABLE public.libavtoraliase (
    aliaseid bigint NOT NULL,
    badid bigint DEFAULT '0'::bigint NOT NULL,
    goodid bigint DEFAULT '0'::bigint NOT NULL
);


ALTER TABLE public.libavtoraliase OWNER TO flibusta;

--
-- TOC entry 208 (class 1259 OID 16550)
-- Name: libavtoraliase_aliaseid_seq; Type: SEQUENCE; Schema: public; Owner: flibusta
--

CREATE SEQUENCE public.libavtoraliase_aliaseid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.libavtoraliase_aliaseid_seq OWNER TO flibusta;

--
-- TOC entry 3280 (class 0 OID 0)
-- Dependencies: 208
-- Name: libavtoraliase_aliaseid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: flibusta
--

ALTER SEQUENCE public.libavtoraliase_aliaseid_seq OWNED BY public.libavtoraliase.aliaseid;


--
-- TOC entry 209 (class 1259 OID 16552)
-- Name: libavtorname; Type: TABLE; Schema: public; Owner: flibusta
--

CREATE TABLE public.libavtorname (
    avtorid bigint NOT NULL,
    firstname character varying(99) DEFAULT ''::character varying NOT NULL,
    middlename character varying(99) DEFAULT ''::character varying NOT NULL,
    lastname character varying(99) DEFAULT ''::character varying NOT NULL,
    nickname character varying(33) DEFAULT ''::character varying NOT NULL,
    uid bigint DEFAULT '0'::bigint NOT NULL,
    email character varying(255) NOT NULL,
    homepage character varying(255) NOT NULL,
    gender character(1) DEFAULT ''::bpchar NOT NULL,
    masterid bigint DEFAULT '0'::bigint NOT NULL
);


ALTER TABLE public.libavtorname OWNER TO flibusta;

--
-- TOC entry 210 (class 1259 OID 16565)
-- Name: libavtorname_avtorid_seq; Type: SEQUENCE; Schema: public; Owner: flibusta
--

CREATE SEQUENCE public.libavtorname_avtorid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.libavtorname_avtorid_seq OWNER TO flibusta;

--
-- TOC entry 3281 (class 0 OID 0)
-- Dependencies: 210
-- Name: libavtorname_avtorid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: flibusta
--

ALTER SEQUENCE public.libavtorname_avtorid_seq OWNED BY public.libavtorname.avtorid;


--
-- TOC entry 211 (class 1259 OID 16567)
-- Name: libavtorname_ts; Type: TABLE; Schema: public; Owner: flibusta
--

CREATE TABLE public.libavtorname_ts (
    avtorid bigint NOT NULL,
    vector tsvector
);


ALTER TABLE public.libavtorname_ts OWNER TO flibusta;

--
-- TOC entry 212 (class 1259 OID 16573)
-- Name: libbannotations; Type: TABLE; Schema: public; Owner: flibusta
--

CREATE TABLE public.libbannotations (
    bookid bigint NOT NULL,
    nid bigint NOT NULL,
    title character varying(255) NOT NULL,
    body text
);


ALTER TABLE public.libbannotations OWNER TO flibusta;

--
-- TOC entry 213 (class 1259 OID 16579)
-- Name: libbook; Type: TABLE; Schema: public; Owner: flibusta
--

CREATE TABLE public.libbook (
    bookid bigint NOT NULL,
    filesize bigint DEFAULT '0'::bigint NOT NULL,
    "time" timestamp with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    title character varying(254) DEFAULT ''::character varying NOT NULL,
    title1 character varying(254) NOT NULL,
    lang character(3) DEFAULT 'ru'::bpchar NOT NULL,
    langex integer DEFAULT 0 NOT NULL,
    srclang character(3) DEFAULT ''::bpchar NOT NULL,
    filetype character(4) NOT NULL,
    encoding character varying(32) DEFAULT ''::character varying NOT NULL,
    year smallint DEFAULT '0'::smallint NOT NULL,
    deleted character(1) DEFAULT '0'::bpchar NOT NULL,
    ver character varying(8) DEFAULT ''::character varying NOT NULL,
    fileauthor character varying(64) NOT NULL,
    n bigint DEFAULT '0'::bigint NOT NULL,
    keywords character varying(255) NOT NULL,
    md5 bytea NOT NULL,
    modified timestamp with time zone DEFAULT '2009-11-29 05:00:00+00'::timestamp with time zone NOT NULL,
    pmd5 character(32) DEFAULT ''::bpchar NOT NULL,
    infocode smallint DEFAULT '0'::smallint NOT NULL,
    pages bigint DEFAULT '0'::bigint NOT NULL,
    chars bigint DEFAULT '0'::bigint NOT NULL
);


ALTER TABLE public.libbook OWNER TO flibusta;

--
-- TOC entry 214 (class 1259 OID 16601)
-- Name: libbook_bookid_seq; Type: SEQUENCE; Schema: public; Owner: flibusta
--

CREATE SEQUENCE public.libbook_bookid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.libbook_bookid_seq OWNER TO flibusta;

--
-- TOC entry 3282 (class 0 OID 0)
-- Dependencies: 214
-- Name: libbook_bookid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: flibusta
--

ALTER SEQUENCE public.libbook_bookid_seq OWNED BY public.libbook.bookid;


--
-- TOC entry 215 (class 1259 OID 16603)
-- Name: libbook_ts; Type: TABLE; Schema: public; Owner: flibusta
--

CREATE TABLE public.libbook_ts (
    bookid bigint NOT NULL,
    vector tsvector
);


ALTER TABLE public.libbook_ts OWNER TO flibusta;

--
-- TOC entry 216 (class 1259 OID 16609)
-- Name: libbpics; Type: TABLE; Schema: public; Owner: flibusta
--

CREATE TABLE public.libbpics (
    bookid bigint NOT NULL,
    nid bigint NOT NULL,
    file character varying(255) NOT NULL
);


ALTER TABLE public.libbpics OWNER TO flibusta;

--
-- TOC entry 217 (class 1259 OID 16612)
-- Name: libfilename; Type: TABLE; Schema: public; Owner: flibusta
--

CREATE TABLE public.libfilename (
    bookid bigint NOT NULL,
    filename character varying(255) NOT NULL
);


ALTER TABLE public.libfilename OWNER TO flibusta;

--
-- TOC entry 218 (class 1259 OID 16615)
-- Name: libgenre; Type: TABLE; Schema: public; Owner: flibusta
--

CREATE TABLE public.libgenre (
    id bigint NOT NULL,
    bookid bigint DEFAULT '0'::bigint NOT NULL,
    genreid bigint DEFAULT '0'::bigint NOT NULL
);


ALTER TABLE public.libgenre OWNER TO flibusta;

--
-- TOC entry 219 (class 1259 OID 16620)
-- Name: libgenre_id_seq; Type: SEQUENCE; Schema: public; Owner: flibusta
--

CREATE SEQUENCE public.libgenre_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.libgenre_id_seq OWNER TO flibusta;

--
-- TOC entry 3283 (class 0 OID 0)
-- Dependencies: 219
-- Name: libgenre_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: flibusta
--

ALTER SEQUENCE public.libgenre_id_seq OWNED BY public.libgenre.id;


--
-- TOC entry 220 (class 1259 OID 16622)
-- Name: libgenrelist; Type: TABLE; Schema: public; Owner: flibusta
--

CREATE TABLE public.libgenrelist (
    genreid bigint NOT NULL,
    genrecode character varying(45) DEFAULT ''::character varying NOT NULL,
    genredesc character varying(99) DEFAULT ''::character varying NOT NULL,
    genremeta character varying(45) DEFAULT ''::character varying NOT NULL
);


ALTER TABLE public.libgenrelist OWNER TO flibusta;

--
-- TOC entry 221 (class 1259 OID 16628)
-- Name: libgenrelist_genreid_seq; Type: SEQUENCE; Schema: public; Owner: flibusta
--

CREATE SEQUENCE public.libgenrelist_genreid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.libgenrelist_genreid_seq OWNER TO flibusta;

--
-- TOC entry 3284 (class 0 OID 0)
-- Dependencies: 221
-- Name: libgenrelist_genreid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: flibusta
--

ALTER SEQUENCE public.libgenrelist_genreid_seq OWNED BY public.libgenrelist.genreid;


--
-- TOC entry 222 (class 1259 OID 16630)
-- Name: libjoinedbooks; Type: TABLE; Schema: public; Owner: flibusta
--

CREATE TABLE public.libjoinedbooks (
    id bigint NOT NULL,
    "time" timestamp with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    badid bigint DEFAULT '0'::bigint NOT NULL,
    goodid bigint DEFAULT '0'::bigint NOT NULL,
    realid bigint
);


ALTER TABLE public.libjoinedbooks OWNER TO flibusta;

--
-- TOC entry 223 (class 1259 OID 16636)
-- Name: libjoinedbooks_id_seq; Type: SEQUENCE; Schema: public; Owner: flibusta
--

CREATE SEQUENCE public.libjoinedbooks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.libjoinedbooks_id_seq OWNER TO flibusta;

--
-- TOC entry 3285 (class 0 OID 0)
-- Dependencies: 223
-- Name: libjoinedbooks_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: flibusta
--

ALTER SEQUENCE public.libjoinedbooks_id_seq OWNED BY public.libjoinedbooks.id;


--
-- TOC entry 224 (class 1259 OID 16638)
-- Name: librate; Type: TABLE; Schema: public; Owner: flibusta
--

CREATE TABLE public.librate (
    id bigint NOT NULL,
    bookid bigint,
    userid bigint,
    rate "char"
);


ALTER TABLE public.librate OWNER TO flibusta;

--
-- TOC entry 225 (class 1259 OID 16641)
-- Name: librecs; Type: TABLE; Schema: public; Owner: flibusta
--

CREATE TABLE public.librecs (
    id bigint NOT NULL,
    uid bigint,
    bid bigint,
    "timestamp" timestamp with time zone
);


ALTER TABLE public.librecs OWNER TO flibusta;

--
-- TOC entry 226 (class 1259 OID 16644)
-- Name: librecs_id_seq; Type: SEQUENCE; Schema: public; Owner: flibusta
--

CREATE SEQUENCE public.librecs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.librecs_id_seq OWNER TO flibusta;

--
-- TOC entry 3286 (class 0 OID 0)
-- Dependencies: 226
-- Name: librecs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: flibusta
--

ALTER SEQUENCE public.librecs_id_seq OWNED BY public.librecs.id;


--
-- TOC entry 227 (class 1259 OID 16646)
-- Name: libreviews; Type: TABLE; Schema: public; Owner: flibusta
--

CREATE TABLE public.libreviews (
    name character varying(255) NOT NULL,
    "time" timestamp with time zone,
    bookid bigint NOT NULL,
    text text NOT NULL
);


ALTER TABLE public.libreviews OWNER TO flibusta;

--
-- TOC entry 228 (class 1259 OID 16652)
-- Name: libseq; Type: TABLE; Schema: public; Owner: flibusta
--

CREATE TABLE public.libseq (
    bookid bigint NOT NULL,
    seqid bigint NOT NULL,
    seqnumb bigint NOT NULL,
    level smallint DEFAULT '0'::smallint NOT NULL,
    "Type" smallint DEFAULT 0
);


ALTER TABLE public.libseq OWNER TO flibusta;

--
-- TOC entry 235 (class 1259 OID 34060)
-- Name: libseqname; Type: TABLE; Schema: public; Owner: flibusta
--

CREATE TABLE public.libseqname (
    seqid bigint NOT NULL,
    seqname character varying(254) DEFAULT ''::character varying NOT NULL
);


ALTER TABLE public.libseqname OWNER TO flibusta;

--
-- TOC entry 229 (class 1259 OID 16663)
-- Name: libseqname_ts; Type: TABLE; Schema: public; Owner: flibusta
--

CREATE TABLE public.libseqname_ts (
    seqid bigint NOT NULL,
    vector tsvector
);


ALTER TABLE public.libseqname_ts OWNER TO flibusta;

--
-- TOC entry 230 (class 1259 OID 16669)
-- Name: libsrclang; Type: TABLE; Schema: public; Owner: flibusta
--

CREATE TABLE public.libsrclang (
    bookid bigint NOT NULL,
    srclang character(2) NOT NULL
);


ALTER TABLE public.libsrclang OWNER TO flibusta;

--
-- TOC entry 231 (class 1259 OID 16672)
-- Name: libtranslator; Type: TABLE; Schema: public; Owner: flibusta
--

CREATE TABLE public.libtranslator (
    bookid bigint NOT NULL,
    translatorid bigint NOT NULL,
    pos smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.libtranslator OWNER TO flibusta;

--
-- TOC entry 239 (class 1259 OID 257812)
-- Name: progress; Type: TABLE; Schema: public; Owner: flibusta
--

CREATE TABLE public.progress (
    user_uuid uuid NOT NULL,
    bookid bigint NOT NULL,
    pos double precision
);


ALTER TABLE public.progress OWNER TO flibusta;

--
-- TOC entry 232 (class 1259 OID 16684)
-- Name: seq; Type: TABLE; Schema: public; Owner: flibusta
--

CREATE TABLE public.seq (
    book_id integer NOT NULL,
    seq_id integer NOT NULL,
    num integer NOT NULL
);


ALTER TABLE public.seq OWNER TO flibusta;

--
-- TOC entry 233 (class 1259 OID 16687)
-- Name: seqname; Type: TABLE; Schema: public; Owner: flibusta
--

CREATE TABLE public.seqname (
    seq_id integer NOT NULL,
    name character varying(255) NOT NULL,
    search_content tsvector NOT NULL
);


ALTER TABLE public.seqname OWNER TO flibusta;

--
-- TOC entry 234 (class 1259 OID 16698)
-- Name: translation; Type: TABLE; Schema: public; Owner: flibusta
--

CREATE TABLE public.translation (
    book_id integer NOT NULL,
    translator_id integer NOT NULL,
    pos integer NOT NULL
);


ALTER TABLE public.translation OWNER TO flibusta;

--
-- TOC entry 3001 (class 2604 OID 16707)
-- Name: book_zip id; Type: DEFAULT; Schema: public; Owner: flibusta
--

ALTER TABLE ONLY public.book_zip ALTER COLUMN id SET DEFAULT nextval('public.book_zip_id_seq'::regclass);


--
-- TOC entry 3049 (class 2604 OID 206116)
-- Name: fav id; Type: DEFAULT; Schema: public; Owner: flibusta
--

ALTER TABLE ONLY public.fav ALTER COLUMN id SET DEFAULT nextval('public.fav_id_seq'::regclass);


--
-- TOC entry 3007 (class 2604 OID 16712)
-- Name: libavtoraliase aliaseid; Type: DEFAULT; Schema: public; Owner: flibusta
--

ALTER TABLE ONLY public.libavtoraliase ALTER COLUMN aliaseid SET DEFAULT nextval('public.libavtoraliase_aliaseid_seq'::regclass);


--
-- TOC entry 3015 (class 2604 OID 16713)
-- Name: libavtorname avtorid; Type: DEFAULT; Schema: public; Owner: flibusta
--

ALTER TABLE ONLY public.libavtorname ALTER COLUMN avtorid SET DEFAULT nextval('public.libavtorname_avtorid_seq'::regclass);


--
-- TOC entry 3032 (class 2604 OID 16714)
-- Name: libbook bookid; Type: DEFAULT; Schema: public; Owner: flibusta
--

ALTER TABLE ONLY public.libbook ALTER COLUMN bookid SET DEFAULT nextval('public.libbook_bookid_seq'::regclass);


--
-- TOC entry 3035 (class 2604 OID 16715)
-- Name: libgenre id; Type: DEFAULT; Schema: public; Owner: flibusta
--

ALTER TABLE ONLY public.libgenre ALTER COLUMN id SET DEFAULT nextval('public.libgenre_id_seq'::regclass);


--
-- TOC entry 3039 (class 2604 OID 16716)
-- Name: libgenrelist genreid; Type: DEFAULT; Schema: public; Owner: flibusta
--

ALTER TABLE ONLY public.libgenrelist ALTER COLUMN genreid SET DEFAULT nextval('public.libgenrelist_genreid_seq'::regclass);


--
-- TOC entry 3043 (class 2604 OID 16717)
-- Name: libjoinedbooks id; Type: DEFAULT; Schema: public; Owner: flibusta
--

ALTER TABLE ONLY public.libjoinedbooks ALTER COLUMN id SET DEFAULT nextval('public.libjoinedbooks_id_seq'::regclass);


--
-- TOC entry 3044 (class 2604 OID 16718)
-- Name: librecs id; Type: DEFAULT; Schema: public; Owner: flibusta
--

ALTER TABLE ONLY public.librecs ALTER COLUMN id SET DEFAULT nextval('public.librecs_id_seq'::regclass);


--
-- TOC entry 3134 (class 2606 OID 206118)
-- Name: fav fav_pkey; Type: CONSTRAINT; Schema: public; Owner: flibusta
--

ALTER TABLE ONLY public.fav
    ADD CONSTRAINT fav_pkey PRIMARY KEY (id);


--
-- TOC entry 3137 (class 2606 OID 206125)
-- Name: fav_users fav_users_pkey; Type: CONSTRAINT; Schema: public; Owner: flibusta
--

ALTER TABLE ONLY public.fav_users
    ADD CONSTRAINT fav_users_pkey PRIMARY KEY (user_uuid, name);


--
-- TOC entry 3071 (class 2606 OID 33825)
-- Name: libavtorname_ts libavtorname_ts_pkey; Type: CONSTRAINT; Schema: public; Owner: flibusta
--

ALTER TABLE ONLY public.libavtorname_ts
    ADD CONSTRAINT libavtorname_ts_pkey PRIMARY KEY (avtorid);


--
-- TOC entry 3091 (class 2606 OID 33827)
-- Name: libbook_ts libbook_ts_pkey; Type: CONSTRAINT; Schema: public; Owner: flibusta
--

ALTER TABLE ONLY public.libbook_ts
    ADD CONSTRAINT libbook_ts_pkey PRIMARY KEY (bookid);


--
-- TOC entry 3109 (class 2606 OID 33836)
-- Name: librate librate_pkey; Type: CONSTRAINT; Schema: public; Owner: flibusta
--

ALTER TABLE ONLY public.librate
    ADD CONSTRAINT librate_pkey PRIMARY KEY (id);


--
-- TOC entry 3118 (class 2606 OID 33839)
-- Name: libseqname_ts libseqname_ts_pkey; Type: CONSTRAINT; Schema: public; Owner: flibusta
--

ALTER TABLE ONLY public.libseqname_ts
    ADD CONSTRAINT libseqname_ts_pkey PRIMARY KEY (seqid);


--
-- TOC entry 3139 (class 2606 OID 257816)
-- Name: progress progress_pkey; Type: CONSTRAINT; Schema: public; Owner: flibusta
--

ALTER TABLE ONLY public.progress
    ADD CONSTRAINT progress_pkey PRIMARY KEY (user_uuid, bookid);


--
-- TOC entry 3124 (class 2606 OID 33841)
-- Name: seq seq_pkey; Type: CONSTRAINT; Schema: public; Owner: flibusta
--

ALTER TABLE ONLY public.seq
    ADD CONSTRAINT seq_pkey PRIMARY KEY (book_id, seq_id);


--
-- TOC entry 3128 (class 2606 OID 33843)
-- Name: seqname seqname_seq; Type: CONSTRAINT; Schema: public; Owner: flibusta
--

ALTER TABLE ONLY public.seqname
    ADD CONSTRAINT seqname_seq PRIMARY KEY (seq_id);


--
-- TOC entry 3131 (class 2606 OID 33845)
-- Name: translation translation_pkey; Type: CONSTRAINT; Schema: public; Owner: flibusta
--

ALTER TABLE ONLY public.translation
    ADD CONSTRAINT translation_pkey PRIMARY KEY (book_id, translator_id);


--
-- TOC entry 3089 (class 1259 OID 33852)
-- Name: i_book_vector; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX i_book_vector ON public.libbook_ts USING gin (vector);


--
-- TOC entry 3135 (class 1259 OID 206120)
-- Name: i_fav_uniq; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE UNIQUE INDEX i_fav_uniq ON public.fav USING btree (user_uuid, bookid, avtorid, seqid);


--
-- TOC entry 3069 (class 1259 OID 33856)
-- Name: i_libavroename_ts; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX i_libavroename_ts ON public.libavtorname_ts USING gin (vector);


--
-- TOC entry 3107 (class 1259 OID 33857)
-- Name: i_rate_bid; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX i_rate_bid ON public.librate USING btree (bookid);


--
-- TOC entry 3116 (class 1259 OID 33858)
-- Name: i_seq_vec; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX i_seq_vec ON public.libseqname_ts USING gin (vector);


--
-- TOC entry 3050 (class 1259 OID 33861)
-- Name: idx_88856_aa; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88856_aa ON public.book_zip USING btree (usr);


--
-- TOC entry 3051 (class 1259 OID 33862)
-- Name: idx_88856_primary; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE UNIQUE INDEX idx_88856_primary ON public.book_zip USING btree (id);


--
-- TOC entry 3052 (class 1259 OID 33863)
-- Name: idx_88856_s1; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88856_s1 ON public.book_zip USING btree (start_id);


--
-- TOC entry 3053 (class 1259 OID 33864)
-- Name: idx_88856_s2; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88856_s2 ON public.book_zip USING btree (end_id);


--
-- TOC entry 3054 (class 1259 OID 33865)
-- Name: idx_88856_ss; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88856_ss ON public.book_zip USING btree (filename);


--
-- TOC entry 3055 (class 1259 OID 33885)
-- Name: idx_88886_avtorid; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88886_avtorid ON public.libaannotations USING btree (avtorid);


--
-- TOC entry 3056 (class 1259 OID 33886)
-- Name: idx_88892_avtorid; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88892_avtorid ON public.libapics USING btree (avtorid);


--
-- TOC entry 3057 (class 1259 OID 33916)
-- Name: idx_88895_iav; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88895_iav ON public.libavtor USING btree (avtorid);


--
-- TOC entry 3058 (class 1259 OID 33917)
-- Name: idx_88895_primary; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE UNIQUE INDEX idx_88895_primary ON public.libavtor USING btree (bookid, avtorid);


--
-- TOC entry 3059 (class 1259 OID 33918)
-- Name: idx_88903_goodid; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88903_goodid ON public.libavtoraliase USING btree (goodid);


--
-- TOC entry 3060 (class 1259 OID 33919)
-- Name: idx_88903_primary; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE UNIQUE INDEX idx_88903_primary ON public.libavtoraliase USING btree (aliaseid);


--
-- TOC entry 3061 (class 1259 OID 33920)
-- Name: idx_88903_uids; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE UNIQUE INDEX idx_88903_uids ON public.libavtoraliase USING btree (badid, goodid);


--
-- TOC entry 3062 (class 1259 OID 33921)
-- Name: idx_88911_email; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88911_email ON public.libavtorname USING btree (email);


--
-- TOC entry 3063 (class 1259 OID 33922)
-- Name: idx_88911_firstname; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88911_firstname ON public.libavtorname USING btree (firstname);


--
-- TOC entry 3064 (class 1259 OID 33923)
-- Name: idx_88911_homepage; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88911_homepage ON public.libavtorname USING btree (homepage);


--
-- TOC entry 3065 (class 1259 OID 33924)
-- Name: idx_88911_lastname; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88911_lastname ON public.libavtorname USING btree (lastname);


--
-- TOC entry 3066 (class 1259 OID 33925)
-- Name: idx_88911_masterid; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88911_masterid ON public.libavtorname USING btree (masterid);


--
-- TOC entry 3067 (class 1259 OID 33926)
-- Name: idx_88911_primary; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE UNIQUE INDEX idx_88911_primary ON public.libavtorname USING btree (avtorid);


--
-- TOC entry 3068 (class 1259 OID 33927)
-- Name: idx_88911_uid; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88911_uid ON public.libavtorname USING btree (uid);


--
-- TOC entry 3072 (class 1259 OID 33928)
-- Name: idx_88925_bookid; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88925_bookid ON public.libbannotations USING btree (bookid);


--
-- TOC entry 3073 (class 1259 OID 33929)
-- Name: idx_88933_bookdel; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE UNIQUE INDEX idx_88933_bookdel ON public.libbook USING btree (deleted, bookid);


--
-- TOC entry 3074 (class 1259 OID 33930)
-- Name: idx_88933_deleted; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88933_deleted ON public.libbook USING btree (deleted);


--
-- TOC entry 3075 (class 1259 OID 33931)
-- Name: idx_88933_fileauthor; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88933_fileauthor ON public.libbook USING btree (fileauthor);


--
-- TOC entry 3076 (class 1259 OID 33932)
-- Name: idx_88933_filesize; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88933_filesize ON public.libbook USING btree (filesize);


--
-- TOC entry 3077 (class 1259 OID 33933)
-- Name: idx_88933_filetype; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88933_filetype ON public.libbook USING btree (filetype);


--
-- TOC entry 3078 (class 1259 OID 33934)
-- Name: idx_88933_filetypedel; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88933_filetypedel ON public.libbook USING btree (deleted, filetype);


--
-- TOC entry 3079 (class 1259 OID 33935)
-- Name: idx_88933_lang; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88933_lang ON public.libbook USING btree (lang);


--
-- TOC entry 3080 (class 1259 OID 33936)
-- Name: idx_88933_langdel; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88933_langdel ON public.libbook USING btree (deleted, lang);


--
-- TOC entry 3081 (class 1259 OID 33937)
-- Name: idx_88933_md5; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE UNIQUE INDEX idx_88933_md5 ON public.libbook USING btree (md5);


--
-- TOC entry 3082 (class 1259 OID 33938)
-- Name: idx_88933_n; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88933_n ON public.libbook USING btree (n);


--
-- TOC entry 3083 (class 1259 OID 33939)
-- Name: idx_88933_primary; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE UNIQUE INDEX idx_88933_primary ON public.libbook USING btree (bookid);


--
-- TOC entry 3084 (class 1259 OID 33940)
-- Name: idx_88933_time; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88933_time ON public.libbook USING btree ("time");


--
-- TOC entry 3085 (class 1259 OID 33941)
-- Name: idx_88933_title; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88933_title ON public.libbook USING btree (title);


--
-- TOC entry 3086 (class 1259 OID 33942)
-- Name: idx_88933_title1; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88933_title1 ON public.libbook USING btree (title1);


--
-- TOC entry 3087 (class 1259 OID 33943)
-- Name: idx_88933_year; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88933_year ON public.libbook USING btree (year);


--
-- TOC entry 3092 (class 1259 OID 33944)
-- Name: idx_88956_bookid; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88956_bookid ON public.libbpics USING btree (bookid);


--
-- TOC entry 3093 (class 1259 OID 33970)
-- Name: idx_88959_filename; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE UNIQUE INDEX idx_88959_filename ON public.libfilename USING btree (filename);


--
-- TOC entry 3094 (class 1259 OID 33971)
-- Name: idx_88959_primary; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE UNIQUE INDEX idx_88959_primary ON public.libfilename USING btree (bookid);


--
-- TOC entry 3095 (class 1259 OID 33972)
-- Name: idx_88964_ibook; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88964_ibook ON public.libgenre USING btree (bookid);


--
-- TOC entry 3096 (class 1259 OID 33973)
-- Name: idx_88964_igenre; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88964_igenre ON public.libgenre USING btree (genreid);


--
-- TOC entry 3097 (class 1259 OID 33974)
-- Name: idx_88964_primary; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE UNIQUE INDEX idx_88964_primary ON public.libgenre USING btree (id);


--
-- TOC entry 3098 (class 1259 OID 33975)
-- Name: idx_88964_u; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE UNIQUE INDEX idx_88964_u ON public.libgenre USING btree (bookid, genreid);


--
-- TOC entry 3099 (class 1259 OID 33976)
-- Name: idx_88972_genreid; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88972_genreid ON public.libgenrelist USING btree (genreid);


--
-- TOC entry 3100 (class 1259 OID 33977)
-- Name: idx_88972_meta; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88972_meta ON public.libgenrelist USING btree (genremeta);


--
-- TOC entry 3101 (class 1259 OID 33978)
-- Name: idx_88972_primary; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE UNIQUE INDEX idx_88972_primary ON public.libgenrelist USING btree (genreid, genrecode);


--
-- TOC entry 3102 (class 1259 OID 33979)
-- Name: idx_88981_badid; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE UNIQUE INDEX idx_88981_badid ON public.libjoinedbooks USING btree (badid);


--
-- TOC entry 3103 (class 1259 OID 33980)
-- Name: idx_88981_goodid; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88981_goodid ON public.libjoinedbooks USING btree (goodid);


--
-- TOC entry 3104 (class 1259 OID 33981)
-- Name: idx_88981_primary; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE UNIQUE INDEX idx_88981_primary ON public.libjoinedbooks USING btree (id);


--
-- TOC entry 3105 (class 1259 OID 33982)
-- Name: idx_88981_realid; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88981_realid ON public.libjoinedbooks USING btree (realid);


--
-- TOC entry 3106 (class 1259 OID 33983)
-- Name: idx_88981_time; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88981_time ON public.libjoinedbooks USING btree ("time");


--
-- TOC entry 3110 (class 1259 OID 33984)
-- Name: idx_88990_bu; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE UNIQUE INDEX idx_88990_bu ON public.librecs USING btree (bid, uid);


--
-- TOC entry 3111 (class 1259 OID 33985)
-- Name: idx_88990_primary; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE UNIQUE INDEX idx_88990_primary ON public.librecs USING btree (id);


--
-- TOC entry 3112 (class 1259 OID 33986)
-- Name: idx_88994_bookid; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_88994_bookid ON public.libreviews USING btree (bookid);


--
-- TOC entry 3113 (class 1259 OID 33987)
-- Name: idx_89000_bookid; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_89000_bookid ON public.libseq USING btree (bookid);


--
-- TOC entry 3114 (class 1259 OID 33988)
-- Name: idx_89000_primary; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE UNIQUE INDEX idx_89000_primary ON public.libseq USING btree (bookid, seqid);


--
-- TOC entry 3115 (class 1259 OID 33989)
-- Name: idx_89000_seqid; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_89000_seqid ON public.libseq USING btree (seqid);


--
-- TOC entry 3119 (class 1259 OID 33990)
-- Name: idx_89012_primary; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE UNIQUE INDEX idx_89012_primary ON public.libsrclang USING btree (bookid);


--
-- TOC entry 3120 (class 1259 OID 33991)
-- Name: idx_89012_srclang; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_89012_srclang ON public.libsrclang USING btree (srclang);


--
-- TOC entry 3121 (class 1259 OID 33992)
-- Name: idx_89015_primary; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE UNIQUE INDEX idx_89015_primary ON public.libtranslator USING btree (bookid, translatorid);


--
-- TOC entry 3122 (class 1259 OID 33993)
-- Name: idx_89015_translatorid; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_89015_translatorid ON public.libtranslator USING btree (translatorid);


--
-- TOC entry 3088 (class 1259 OID 102767)
-- Name: idx_libb_lower_p; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_libb_lower_p ON public.libbook USING btree (lower((title)::text) varchar_pattern_ops);


--
-- TOC entry 3132 (class 1259 OID 102766)
-- Name: idx_seqn_lower_p; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX idx_seqn_lower_p ON public.libseqname USING btree (lower((seqname)::text) varchar_pattern_ops);


--
-- TOC entry 3126 (class 1259 OID 34005)
-- Name: seqname_search_content2; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX seqname_search_content2 ON public.seqname USING gin (name public.gin_trgm_ops);


--
-- TOC entry 3125 (class 1259 OID 34006)
-- Name: sequence_book_id; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX sequence_book_id ON public.seq USING btree (book_id);


--
-- TOC entry 3129 (class 1259 OID 34007)
-- Name: translation_book_id; Type: INDEX; Schema: public; Owner: flibusta
--

CREATE INDEX translation_book_id ON public.translation USING btree (book_id);


--
-- TOC entry 3140 (class 2606 OID 34033)
-- Name: seq seq_seq_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: flibusta
--

ALTER TABLE ONLY public.seq
    ADD CONSTRAINT seq_seq_id_fkey FOREIGN KEY (seq_id) REFERENCES public.seqname(seq_id);


-- Completed on 2022-03-30 10:39:37 UTC

--
-- PostgreSQL database dump complete
--

