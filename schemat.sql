-- Schemat bazy danych do projektu:
CREATE SEQUENCE gra_seq;
CREATE SEQUENCE pytanie_seq;
CREATE SEQUENCE odpowiedz_seq;
CREATE SEQUENCE obrazek_seq;
CREATE SEQUENCE uzytkownik_seq;
CREATE SEQUENCE sesja_seq;

CREATE DOMAIN typ_uzytkownika AS char CHECK(VALUE IN ('G', 'T', 'A'));

CREATE TABLE gra (
	id_gry bigint PRIMARY KEY DEFAULT nextval('gra_seq'),
	nazwa text NOT NULL,
	id_pytania bigint
);

CREATE TABLE obrazek (
	id_obrazka bigint PRIMARY KEY DEFAULT nextval('obrazek_seq'),
	alt char[255],
	src text NOT NULL
);

CREATE TABLE pytanie (
	id_pytania bigint PRIMARY KEY DEFAULT nextval('pytanie_seq'),
	id_gry bigint NOT NULL,
	id_uzytkownika bigint,
	nazwa text NOT NULL,
	tekst text NOT NULL,
	stan text,
	warunek text,
	id_obrazka bigint,
	CONSTRAINT pytanie_id_gry_fk FOREIGN KEY (id_gry) REFERENCES gra ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pytanie_id_obrazka_fk FOREIGN KEY (id_obrazka) REFERENCES obrazek ON DELETE RESTRICT ON UPDATE CASCADE
);

ALTER TABLE gra ADD CONSTRAINT gra_id_pytania_fk FOREIGN KEY (id_pytania) REFERENCES pytanie ON DELETE RESTRICT ON UPDATE CASCADE;

CREATE TABLE odpowiedz (
	id_odpowiedzi bigint PRIMARY KEY DEFAULT nextval('odpowiedz_seq'),
	id_pytania bigint NOT NULL,
	nazwa text NOT NULL,
	tekst text NOT NULL,
	warunek text,
	stan text,
	CONSTRAINT odpowiedz_id_pytania FOREIGN KEY (id_pytania) REFERENCES pytanie ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE pytanie_odpowiedz (
	id_pytania bigint NOT NULL,
	id_odpowiedzi bigint NOT NULL,
	CONSTRAINT pytanie_odpowiedz_uniq UNIQUE(id_pytania, id_odpowiedzi),
	CONSTRAINT pytanie_odpowiedz_id_pytania_fk FOREIGN KEY (id_pytania) REFERENCES pytanie ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT pytanie_odpowiedz_id_odpowiedzi_fk FOREIGN KEY (id_odpowiedzi) REFERENCES odpowiedz ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE uzytkownik (
	id_uzytkownika bigint PRIMARY KEY DEFAULT nextval('uzytkownik_seq'),
	nazwa text NOT NULL,
	login text NOT NULL,
	hash_passwd text NOT NULL,
	CONSTRAINT uzytkownik_uniq_login UNIQUE(login)
);

ALTER TABLE pytanie ADD CONSTRAINT pytanie_id_uzytkownika_fk FOREIGN KEY (id_uzytkownika) REFERENCES uzytkownik ON DELETE SET NULL ON UPDATE CASCADE; 

CREATE TABLE uprawnienie (
	id_uzytkownika bigint NOT NULL,
	id_gry bigint NOT NULL,
	ranga typ_uzytkownika NOT NULL DEFAULT 'G',
	CONSTRAINT uprawnienie_uniq UNIQUE(id_uzytkownika, id_gry),
	CONSTRAINT uprawnienie_id_uzytkownika_fk FOREIGN KEY (id_uzytkownika) REFERENCES uzytkownik ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT uprawnienie_id_gry_fk FOREIGN KEY (id_gry) REFERENCES gra ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE sesja (
	id_sesji bigint PRIMARY KEY DEFAULT nextval('sesja_seq'),
	id_uzytkownika bigint NOT NULL,
	id_gry bigint NOT NULL,
	id_pytania bigint NOT NULL,
	punkty integer NOT NULL DEFAULT 0,
	rozpoczecie timestamp DEFAULT now(),
	CONSTRAINT sesja_id_uzytkownika_fk FOREIGN KEY (id_uzytkownika) REFERENCES uzytkownik ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT sesja_id_gry_fk FOREIGN KEY (id_gry) REFERENCES gra ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT sesja_id_pytania_fk FOREIGN KEY (id_pytania) REFERENCES pytanie ON DELETE RESTRICT ON UPDATE CASCADE,
	CONSTRAINT sesja_uniq UNIQUE(id_gry, id_uzytkownika)
);

CREATE TABLE srodowisko (
	id_sesji bigint NOT NULL,
	nazwa text NOT NULL,
	wartosc text NOT NULL,
	CONSTRAINT srodowisko_id_sesji_fk FOREIGN KEY (id_sesji) REFERENCES sesja ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT srodowisko_uniq UNIQUE(id_sesji, nazwa)
);

CREATE TABLE klucz_przegladarki (
  klucz text PRIMARY KEY,
  user_agent text NOT NULL,
  wygasa timestamp NOT NULL,
  id_uzytkownika bigint NOT NULL,
  CONSTRAINT klucz_przegladarki_uniq UNIQUE(klucz, id_uzytkownika),
  CONSTRAINT klucz_przegladarki_id_uzytkownika_fk FOREIGN KEY (id_uzytkownika) REFERENCES uzytkownik ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE OR REPLACE FUNCTION usun_niepodpiete() RETURNS TRIGGER AS
$$
BEGIN
	DELETE FROM odpowiedz WHERE id_odpowiedzi NOT IN (
		SELECT id_odpowiedzi FROM pytanie_odpowiedz
	);
	RETURN OLD;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER usun_odp AFTER DELETE ON pytanie_odpowiedz
	EXECUTE PROCEDURE usun_niepodpiete();

-- Odpowiednie GRANTy dla użytkowników:
-- Zakładam, że twórca gry to rola bd2013_creator, a użytkownik - bd2013_user.
-- GRANT SELECT ON TABLE gra, klucz_przegladarki, obrazek, odpowiedz, pytanie, pytanie_odpowiedz, sesja, srodowisko, uprawnienie, uzytkownik TO bd2013_creator;
-- GRANT SELECT ON TABLE gra, klucz_przegladarki, obrazek, odpowiedz, pytanie, pytanie_odpowaiedz, sesja, srodowisko, uprawnienie, uzytkownik TO bd2013_user;
-- GRANT ALL ON TABLE uzytkownik, srodowisko, sesja, klucz_przegladarki TO bd2013_user;
-- GRANT ALL ON TABLE uzytkownik, srodowisko, sesja, klucz_przegladarki, pytanie, pytanie_odpowiedz, odpowiedz, gra, obrazek, uprawnienie TO bd2013_creator;
-- GRANT ALL ON ALL SEQUENCES IN SCHEMA public TO bd2013_user, bd2013_admin, bd2013_creator;
-- Jeżeli Twoim administratorem jest bd2013_admin, to po stworzeniu bazy (CREATE DATABASE <nazwa>)
-- należy zrobić: ALTER DATABASE <nazwa> OWNER TO bd2013_admin;
