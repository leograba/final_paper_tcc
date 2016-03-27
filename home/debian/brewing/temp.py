#!/usr/bin/env python
# -*- coding: utf-8 -*-

#importa os módulos que serão usados no script
import time
import os.path
#import graficos_png as graph 
#import datetime
#import wiringpi2 as wpi 

def tread():
	"""lê temperatura do DS18B20 e retorna em celsius"""
	tfile = open("/sys/bus/w1/devices/28-000004ee8ded/w1_slave")
	tfile.readline()
	temp_raw = tfile.readline()
	tfile.close()
	temp_raw = temp_raw.split(" ")[9]
	temp_raw = float(temp_raw[2:])
	return temp_raw/1000
	
def tprint_all(tcelsius):
	"""imprime a temperatura em diversas escalas no formato HTML"""
	print "Temperatura:<br />",
	print "%.2f &degC<br />" % tcelsius,#celsius
	print "%.2f &degF<br />" % (tcelsius*1.8+32),#fahrenheit
	print "%.2f K<br />" % (tcelsius+273.15),#kelvin
	print "%.2f &degR<br />" % (tcelsius*1.8+32+459.67),#rankine

def tprint_all_terminal(tcelsius):
        """imprime a temperatura em diversas escalas formatado para o terminal"""
        print "Temperatura:"
        print "%.2f" % tcelsius, u"\u00B0C"#celsius
        print "%.2f" % (tcelsius*1.8+32), u"\u00B0F"#fahrenheit
        print "%.2f K" % (tcelsius+273.15)#kelvin
        print "%.2f" % (tcelsius*1.8+32+459.67), u"\u00B0R"#rankine

def tlog(file = "/var/www/datalog/default.csv"):
	"""salva temperatura e Unix Time em .csv """
	#arquivo padrão CSV com cabecalho


	#file = raw_input("digite o nome do arquivo e.g. log-data\n")
	#file += ".log"
	#tsample = float(raw_input("digite tempo de amostragem em segundos: "))


	#os comandos acima foram ignorados para poder usar o nohup (que nao recebe input)
	#tsample = 0.2202 #amostra a cada x segundos
	tsample = 1 #valor de amostragem para teste
	#amostras = 5000#numero de amostras a serem coletadas
	buff_temp = tread()#guarda o último valor lido
	exist = os.path.isfile(file)
	#buffer = open(file,"a")#mesma funcao da linha abaixo, porem menos recomendada
	with open(file, 'a', 1) as log:#desse jeito, o arquivo será fechado
	#mesmo que haja uma excessao, diferente de usar file.close()
	#o arg. 1 indica que o buffer antes de escrever no arquivo eh 1 linha
		if exist is False:#se vai criar o arquivo agora
			log.write("temperatura,data\n")
		temp_celsius = 25.00#somente para teste
		temp_sparge = 20.00#somente para teste
		flag1 = 0#somente para teste
		flag2 = 0
		while True: #loop infinito
		#while amostras > 0:#coleta o numero de amostras
			#amostras = amostras - 1#decrementa variavel de controle
			#temp_celsius = tread()
			if temp_celsius < 32.00 and flag1:#apos decrescer, volta a aumentar
				print "L1"
				flag2 = 1#impossibilita entrar aqui de novo
				temp_celsius += 0.2 #usando valores incrementais para teste
                                temp_sparge += 0.2
			elif temp_celsius > 36.00 and not flag2:#decresce um pouco para testar temperatura abaixo do setpoint
				print "L2"
				flag1 = 1#impossibilita entrar aqui de novo
				temp_celsius -= 0.2 #usando valores incrementais para teste
                                temp_sparge -= 0.2
			elif temp_celsius < 75.00:#somente para testes nao incrementa alem de 75
				print "L3"
				temp_celsius += 0.2 #usando valores incrementais para teste
				temp_sparge += 0.2
			epoch = time.time()#lê a data/hora do sistema
			#nowis = time.ctime(epoch)#converte para string
			if temp_celsius >= 0:#evita leitura errada
			#essa leitura errada é intermitente e causa desconhecida
				log.write("%f,%f\n" % (temp_celsius, epoch))
				tlog_instant(temp_celsius, epoch)#escreve arquivo com ultima leitura
				tlog_instant(temp_sparge, epoch, "/var/www/datalog/instant_bk.csv")#somente para teste por enquanto
				#graph.graph_gen()# atualiza gráfico da temperatura
				#escreve temperatura e data/hora no .txt
				time.sleep(tsample)#espera n segundos
				print "registrando temperatura!",temp_celsius


def tlog_instant(temperature, epoch, file = "/var/www/datalog/instant.csv"):
	"""salva última temperatura e Unix Time em arquivo .csv"""
	with open(file, 'w', 1) as log:#sobrescreve o arquivo toda vez
		#temperature = tread()#lê a temperatura
		#epoch = int(time.time())#lê o Unix Time do sistema
		log.write("temperatura,data\n")
		log.write("%f,%f\n" % (temperature, epoch))
