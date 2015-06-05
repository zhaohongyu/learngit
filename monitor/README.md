# Monitor

1、部署的时候更改一下config.php中的$GLOBALS['LIB_ROOT']的路径,
是thrift的文件路径

2、更改一下monitor.php中$query的查询操作语句,随便找一条能查询数据的cql就可以

3、调用http://www.example.com/monitor/monitor.php?cassandra_host=192.168.1.115
