<?php 
$entities = array();
/* REDBEAN
RedBeanPHP generates a sane and readable database schema for you. Here are the schema conventions used by RedBeanPHP:
Field names:	Lowercase a-z, 0-9 and underscore (_)
Table name:	Should match bean type, a-z, 0-9
Primary key:	Each table should have a primary key named 'id' (int, auto-incr)
Foreign key:	Format: <TYPE>_id
Link table:	Format: <TYPE1>_<TYPE2> sorted alphabetically
*/

include('entities/client.inc.php');
include('entities/group.inc.php');
include('entities/group_rate.inc.php');
include('entities/rate.inc.php');
include('entities/memo.inc.php');
include('entities/anamnesis.inc.php');
include('entities/performance.inc.php');
include('entities/paymentstate.inc.php');
include('entities/paymentformula.inc.php');
include('entities/paymentform.inc.php');
include('entities/performancelocation.inc.php');
include('entities/performancetype.inc.php');
include('entities/performance_performancetype.inc.php');
include('entities/payment.inc.php');
include('entities/bill.inc.php');
include('entities/billrow.inc.php');