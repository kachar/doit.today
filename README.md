# doit.today
Project for "be IT today" conference

SQLite structure

```
CREATE TABLE todo (
    `id` integer primary key autoincrement,
    `message` varchar(128), 
    `is_done` integer, 
    `created_at` varchar(20)
)
```
