from temp import tprint_all, tread
from time import time, sleep

tprint_all(tread())
#temp.tlog()
print "\n"
#benchmarking do tempo necessario para ler o sensor
tempo = time()
tread()
tempo = time()-tempo
while True:
	now = time()
	tread()
	tempo = (time()-now+tempo)/2
	print tempo,
#	sleep(1)
