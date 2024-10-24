delete from libgenrelist where not exists (select 1 from libgenre where libgenrelist.genreid = libgenre.genreid);
delete from libseqname where not exists ( select 1 from libseq where libseqname.seqid = libseq.seqid);
delete from libavtorname where not exists (select 1 from libavtor where libavtorname.avtorid = libavtor.avtorid);
