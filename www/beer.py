import time
import os
import sys

pid = str(os.getpid())
pidfile = "/tmp/beer.pid"

if os.path.isfile(pidfile):
	print "%s ja existe!" %pidfile
	sys.exit()
else:
	file(pidfile, 'w').write(pid) 

for x in range (0, 10):
	print "here!"
	time.sleep(5)

os.unlink(pidfile)
