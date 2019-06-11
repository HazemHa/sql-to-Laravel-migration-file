# Migration Files based on SQL CODE
Do you have a sql code  and you want to make a Laravel migration files to backup it or it's easy to modify?
<br>

#NOTE
if you need use this for something else it's easy because a sql code will split into objects(model and column),so a Filter Unit will do the task without you re-write it and also you can easy to modify.

## How does it work ?
1- break down a  sql code into models => Table and Column
    * FilterUnit Class
2- build a text from tables and column
    * handler Class
3- write a text info file
    * Process File Class

### parts of Project
1- Core
   *  filter SQL CODE to PHP Column Models by REGEX
   *  use to build syntax of table from Filter Unit
2- databases
   * get  migrations files here
3- File
   * unit to handle file by create it or modify it.
4- Models
   * TABLE
   * COLUMN
5- template
   * a syntax template for migration file
6- traits
   * a singleton pattern
7- Utility
   * helper classes