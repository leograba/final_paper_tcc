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
	return "";#retorna string vazia ao inves de "None"

def tprint_all_terminal(tcelsius):
        """imprime a temperatura em diversas escalas formatado para o terminal"""
        print "Temperatura:"
        print "%.2f" % tcelsius, u"\u00B0C"#celsius
        print "%.2f" % (tcelsius*1.8+32), u"\u00B0F"#fahrenheit
        print "%.2f K" % (tcelsius+273.15)#kelvin
        print "%.2f" % (tcelsius*1.8+32+459.67), u"\u00B0R"#rankine

def tprint_all_wordpress(tcelsius):
        """imprime a temperatura em diversas escalas formatado para o wordpress"""
        print "Temperatura:"
        print "%.2f" % tcelsius, "ºC"#celsius
        print "%.2f" % (tcelsius*1.8+32), "ºF"#fahrenheit
        print "%.2f K" % (tcelsius+273.15)#kelvin
        print "%.2f" % (tcelsius*1.8+32+459.67), "ºR"#rankine
	return "";#retorna string vazia ao inves de "None"

def tlog(file = "/var/www/default.csv"):
	"""salva temperatura/data/hora em .csv """
	#arquivo padrão CSV com cabecalho


	#file = raw_input("digite o nome do arquivo e.g. log-data\n")
	#file += ".log"
	#tsample = float(raw_input("digite tempo de amostragem em segundos: "))


	#os comandos acima foram ignorados para poder usar o nohup (que nao recebe input)
	tsample = 30#amostra a cada x segundos
	exist = os.path.isfile(file)
	#buffer = open(file,"a")#mesma funcao da linha abaixo, porem menos recomendada
	with open(file, 'a', 1) as log:#desse jeito, o arquivo será fechado
	#mesmo que haja uma excessao, diferente de usar file.close()
	#o arg. 1 indica que o buffer antes de escrever no arquivo eh 1 linha
		if exist is False:#se vai criar o arquivo agora
			log.write("temperatura,data\n")
		while True: #loop infinito
			temp_celsius = tread()
			nowis = time.ctime()#lê data/hora do sistema
			log.write("%f,%s\n" % (temp_celsius,nowis))
			#graph.graph_gen()# atualiza gráfico da temperatura
			#escreve temperatura e data/hora no .txt
			time.sleep(tsample)#espera n segundos
			print "registrando temperatura!",temp_celsius

