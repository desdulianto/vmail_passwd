#!/usr/bin/env python
import fileinput
import sys

a = fileinput.input()
user = a.readline().strip()
domain = a.readline().strip()
alg = a.readline().strip()
path = a.readline().strip()
curpass = a.readline().strip()
password = a.readline().strip()

a.close()

oldrec = '%s:%s' % (user, curpass)
newrec = '%s:%s' % (user, password)

b = fileinput.input('%s/passwd' % path, inplace=True, backup='.bak')

ret = 1
for line in b:
	if line.strip() == oldrec:
		print newrec
		ret = 0
	else:
		print line.strip()

sys.exit(ret)
