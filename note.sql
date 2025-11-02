-- ユーザーテーブル
create table if not exists users(
    user_id int auto_increment primary key,
    username varchar(255) not null unique,
    password varchar(255) not null,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update current_timestamp
) engine=innodb ddefault charset=suf8mb4 collate=utf8mb4_unicode_ci;

create table if not exists mangas (
    id Int auto_increment primary key,
    user_id int not null,
    manga_id varchar()
)