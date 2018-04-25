#!/bin/env python
# -*- coding: utf-8 -*-
#by dongzh
#import data from nginx api into redis
##data model##
#> hgetall yewuout:etcp.com.cn
# 1) "isout"
# 2) "0"
# 3) "ipnum"
# 4) "2"
# 5) "yewuling"
# 6) ""
# 7) "ip1"
# 8) "10.103.22.75"
# 9) "ip2"
#10) "10.103.22.76"
import redis
import commands
import json
import os

r = redis.StrictRedis(host='10.86.10.134',port=6379,db=0)
status,output=commands.getstatusoutput('curl -s http://v7.ops.etcp.cn/ups_ip')
if status == 0:
    if output != "":
        hname = output
    else:
        print("cannot gain data from ups_ip")
        os._exit(1)

out = json.loads(hname)
for k,v in out.iteritems():
    kn = k.encode("utf-8")
    numofv = len(v)
    if r.sismember('yewuoutset',kn):
        print(kn+" is already in redis , just update as need")
        hashkey = 'yewuout' + ":" + kn
        r.hset(hashkey,"ipnum",numofv)
        ipfrom = 1
        for eachv in v:
            ipaddr=eachv['addr'].split(':')[0]
            r.hset(hashkey,"ip"+str(ipfrom),ipaddr)
            ipfrom = ipfrom+1
        
    else:
        print(kn+" is newadd")
        r.sadd('yewuoutset',kn)
        hashkey = 'yewuout' + ":" + kn
        r.hset(hashkey,"isout","0")
        r.hset(hashkey,"ipnum",numofv)
        r.hset(hashkey,"yewuling","")
        ipfrom = 1
        for eachv in v:
            ipaddr=eachv['addr'].split(':')[0]
            r.hset(hashkey,"ip"+str(ipfrom),ipaddr)
            ipfrom = ipfrom+1
    

