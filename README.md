This is just a very basic session based shopping cart project.
Based on bundle-less Symfony Flex approach to create web apps.

- native session storage, no fancy stuff
- annotation based routing (always wanted to try this out as opposed to YML)
- Symfony’s auto wiring (it actually makes life easier, but I wonder how it would behave in project with hundreds of components)
- In some places you can see DQL (Doctrine Query Language) that I touched for the first time, so pardon me if it’s ugly
- included easyadmin bundle, never used it but it works better than I expected
- no fixtures, sorry

Category
```
+-------+--------------+------+-----+---------+----------------+
| Field | Type         | Null | Key | Default | Extra          |
+-------+--------------+------+-----+---------+----------------+
| id    | int(11)      | NO   | PRI | NULL    | auto_increment |
| name  | varchar(255) | NO   |     | NULL    |                |
+-------+--------------+------+-----+---------+----------------+
```
Product
```
+-------------+---------------+------+-----+---------+----------------+
| Field       | Type          | Null | Key | Default | Extra          |
+-------------+---------------+------+-----+---------+----------------+
| id          | int(11)       | NO   | PRI | NULL    | auto_increment |
| name        | longtext      | NO   |     | NULL    |                |
| price       | decimal(19,4) | NO   |     | NULL    |                |
| vat_percent | int(11)       | NO   |     | NULL    |                |
| category_id | int(11)       | YES  | MUL | NULL    |                |
+-------------+---------------+------+-----+---------+----------------+
```

more on flex: https://github.com/symfony/flex
