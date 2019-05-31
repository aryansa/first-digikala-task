<?php
/**
 * Created by PhpStorm.
 * User: aryanpc
 * Date: 5/30/19
 * Time: 4:20 AM
 */

namespace App\Models;


use App\Services;

class InstallModel
{
    public function run()
    {
        $mysql = Services::mysqlService();
            $query = ["create table color
    (
      id   int auto_increment
        primary key,
      name text null
    );", "create table products
    (
      id          int auto_increment,
      title       text  not null,
      description text  not null,
      constraint products_id_uindex
      unique (id)
    );
    ", "
    alter table products
      add primary key (id);
    ", "create table users
    (
      id          int auto_increment,
      mail        varchar(255) null,
      user_name   text         not null,
      password    text         not null,
      create_date datetime     not null,
      constraint Users_id_uindex
      unique (id),
      constraint Users_mail_uindex
      unique (mail)
    );", "create table variants
(
  id         int auto_increment
    primary key,
  product_id int null,
  color_id   int null,
  price      int null,
  constraint variants___color
  foreign key (color_id) references color (id),
  constraint variants_products_id_fk
  foreign key (product_id) references products (id)
);", "create index variants__price
  on variants (price);
    ","create index variants_product_id_index
  on variants (product_id);
  ", "INSERT INTO users (mail, user_name, password, create_date)
    VALUES ('admin@digikala.com', 'Admin', MD5('1234'), NOW())"];

        foreach ($query as $item) {
            $mysql->query($item, []);
        }
    }
}