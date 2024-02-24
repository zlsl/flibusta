INSERT INTO  libavtorname_ts
select avtorid, to_tsvector('russian', concat(lastname, ' ', middlename, ' ', firstname, ' ', nickname)) vector
FROM libavtorname
ON CONFLICT DO NOTHING;

INSERT INTO  libbook_ts
select bookid, to_tsvector('russian',title) vector
FROM libbook
ON CONFLICT DO NOTHING;

INSERT INTO  libseqname_ts
select seqid, to_tsvector('russian', seqname) vector
FROM libseqname
ON CONFLICT DO NOTHING;

