# Migration Files based on SQL CODE

Do you have a sql code and you want to make a Laravel migration files to backup it or it's easy to modify?
<br>
#Example

```
-- Create department table
CREATE TABLE `department` (
  `department_id` INT            NOT NULL  AUTO_INCREMENT,
  `name`          VARCHAR(100)   NOT NULL,
  `description`   VARCHAR(1000),
  PRIMARY KEY  (`department_id`)
) ENGINE=MyISAM;

```

---

```
Schema::create('department', function (Blueprint $table) {
                $table->engine = 'MyISAM';
$table->integer( 'department_id')->autoIncrement();
$table->string( 'name',100);
$table->string( 'description',1000)->nullable();
$table->primary('department_id');

            });

```

#NOTE
if you need use this for something else it's easy because a sql code will split into objects(model and column),so a Filter Unit will do the task without you re-write it and also you can easy to modify.

## How does it work ?

1- break down a sql code into models => Table and Column
_ FilterUnit Class
2- build a text from tables and column
_ handler Class
3- write a text info file \* Process File Class

### parts of Project

<ol>
  
 <li>
 Core
 <ul><li>filter SQL CODE to PHP Column Models by REGEX
     use to build syntax of table from Filter Unit</li></ul>
     </li>
 <li>databases
   <ul><li> get  migrations files here</li></ul>
    </li>
 <li>File
    <ul><li>unit to handle file by create it or modify it.</li></ul>
    </li>
 <li>Models
    <ul><li>TABLE</li></ul>
   <ul><li> COLUMN</li></ul>
    </li>
<li>template
    <ul><li>a syntax template for migration file</li></ul>
    </li>
 <li>traits
    <ul><li>a singleton pattern</li></ul>
    </li>
 <li>Utility
    <ul><li>helper classes</li></ul>
    </li>

</ol>
