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
	tsample = 0.2202 #amostra a cada x segundos
	#amostras = 5000#numero de amostras a serem coletadas
	buff_temp = tread()#guarda o último valor lido
	exist = os.path.isfile(file)
	#buffer = open(file,"a")#mesma funcao da linha abaixo, porem menos recomendada
	with open(file, 'a', 1) as log:#desse jeito, o arquivo será fechado
	#mesmo que haja uma excessao, diferente de usar file.close()
	#o arg. 1 indica que o buffer antes de escrever no arquivo eh 1 linha
		if exist is False:#se vai criar o arquivo agora
			log.write("temperatura,data\n")
		while True: #loop infinito
		#while amostras > 0:#coleta o numero de amostras
			#amostras = amostras - 1#decrementa variavel de controle
			temp_celsius = tread()
			epoch = time.time()#lê a data/hora do sistema
			#nowis = time.ctime(epoch)#converte para string
			if temp_celsius >= 0:#evita leitura errada
			#essa leitura errada é intermitente e causa desconhecida
				log.write("%f,%f\n" % (temp_celsius, epoch))
				tlog_instant(temp_celsius, epoch)#escreve arquivo com ultima leitura
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

def tlog_test(file = "/var/www/datalog/default.csv"):
	"""salva temperatura e Unix Time em .csv """
	#arquivo padrão CSV com cabecalho
	tsample = 1 #valor de amostragem para teste
	exist = os.path.isfile(file)
	with open(file, 'a', 1) as log:#desse jeito, o arquivo será fechado
		if exist is False:#se vai criar o arquivo agora
			log.write("temperatura,data\n")
		temp_celsius = 25.00#somente para teste
		temp_sparge = 20.00#somente para teste
		ramp_section = 0#sequencia de funcoes que gera as rampas/degraus
		while True: #loop infinito
			if ramp_section == 0:#aquece medio ate 29 graus
				temp_celsius += 0.2
				if temp_celsius >= 29:
					ramp_section = 1
			elif ramp_section == 1:#aquece devagar ate 30 graus
                                temp_celsius += 0.1
                                if temp_celsius >= 30:
                                        ramp_section = 2
			elif ramp_section == 2:#espera usuario adicionar maltes
				time.sleep(15)#30 segundos
				ramp_section = 3
			elif ramp_section == 3:#aquece medio ate 35 graus
                                temp_celsius += 0.2
                                if temp_celsius >= 35:
                                        ramp_section = 4
			elif ramp_section == 4:#retrai aquecimento ate 33 graus
                                temp_celsius -= 0.2
                                if temp_celsius <= 33:
                                        ramp_section = 5
                        elif ramp_section == 5:#degrau 1, aquece sparge durante 1 minuto
				temp_sparge += 0.5
                                if temp_sparge >= 50:
                                        ramp_section = 6
			elif ramp_section == 6:#aquece rapido ate 42 graus
				temp_celsius += 0.3
				if temp_celsius >= 42:
					ramp_section = 7
			elif ramp_section == 7:#aquece medio ate 59 graus
                                temp_celsius += 0.2
                                if temp_celsius >= 59:
                                        ramp_section = 8
			elif ramp_section == 8:#aquece devagar ate 60 graus
                                temp_celsius += 0.1
                                if temp_celsius >= 60:
                                        ramp_section = 9
			else:#temperatura de fervura
                                if temp_sparge <= 98.5:
					temp_sparge += 0.3
			epoch = time.time()#lê a data/hora do sistema
			log.write("%f,%f\n" % (temp_celsius, epoch))
			tlog_instant(temp_celsius, epoch)#escreve arquivo com ultima leitura
			tlog_instant(temp_sparge, epoch, "/var/www/datalog/instant_bk.csv")#somente para teste por enquanto
			#escreve temperatura e data/hora no .txt
			time.sleep(tsample)#espera n segundos
			print "registrando temperatura!",temp_celsius,temp_sparge
