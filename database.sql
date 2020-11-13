create table if not EXISTS unidades(

	id 		int(3) auto_increment PRIMARY KEY,
	magnitud	varchar(15),
    unidad	varchar(15),
	updated_at date,
	created_at date
)

create table if not EXISTS cambios(

	id 		int(4) auto_increment PRIMARY KEY,
	magnitud	varchar(15),
    u_entrada	varchar(15),
    u_salida	varchar(15),
    factor		double,
	updated_at date,
	created_at date
)