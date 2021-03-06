#exemplo traduzido e adaptado de
#matplotlib.org/examples/pylab_examples/multipage_pdf.html
import time
import matplotlib
matplotlib.use('AGG')#muda a output para um renderer ao inves de backend
import numpy as np
import matplotlib.pyplot as plt
from scipy import interpolate
#from matplotlib.backends.backend_pdf import PdfPages

def graph_gen(file = '/var/www/datalog/default.csv', graph = '/var/www/img/tplot2.png'):
	#le arquivo com as temperaturas armazenadas
	#file = '/var/www/default.csv'
	tvalues=[]#declara lista das temperaturas
	ttimes=[]#declara lista dos tempos
	x_scale=[]#declara lista do eixo-x
	#legenda=[]#declara lista de legendas
	title=''
	last_line=['', '']
	control=0#variavel de controle do loop
	with open(file,'r') as tfile:
		for line in tfile:#le linha por linha o log da temperatura
			if control != 0:#se esta lendo a primeira linha
				tvalues.append(line.split(',')[0])#separa o valor da temperatura
				ttimes.append(line.split(',')[1])#separa o epoch
			last_line[0] = last_line[1]#pultima linha salva
			last_line[1] = line.split(',')[1]#ultima linha lida-data/hora
			if control == 1:#pega a data/hora da primeira linha
				title = line.split(',')[1]#esta em epoch time
			control += 1
			#ttimes.append(line[23:31])#separa a hora da leitura
		title = time.ctime(float(title))# de epoch para string formatada
		title += ' - '#separa tinicio e tfim
		title += time.ctime(float(last_line[1]))#pega a data/hora da ultima linha
		#nao da pra fazer slice do tipo [n:], porque nao exclui o \n do fim da linha
	
	#plota o grafico da temperatura
	fig = plt.figure(figsize=(9, 4))#, dpi=300)
	ax = fig.add_subplot(111)#subplot p/ mudar cor de fundo
	ax.set_axis_bgcolor('#BEC5C2')#muda cor do fundo do plot
	plt.grid()#plota o grafico com grid ligado
	#se tratando de salvar a figura, o dpi so faz diferenca no savefig

	#ajusta a escala do tempo baseado no tempo total de amostragem dos dados
	size_x = int(float(ttimes[len(ttimes)-1]))-float(ttimes[0])#tempo final menos tempo inicial
	if size_x < 7200:#ate duas horas, escala em minutos
		for tms in ttimes:#itera pelo array do eixo-x
			x_scale.append((float(tms)-float(ttimes[0]))/60)#transforma em minutos
		plt.xlabel('tempo(minutos)')#imprime label indicando minutos
	elif size_x < 129600:#ate um dia e meio, escala em horas
		for tms in ttimes:#itera pelo array do eixo-x
                        x_scale.append((float(tms)-float(ttimes[0]))/(60*60))#transforma em horas
		plt.xlabel('tempo(horas)')#imprime label indicando horas
	elif size_x < 7776000:#ate tres meses, escala em dias
                for tms in ttimes:#itera pelo array do eixo-x
                        x_scale.append((float(tms)-float(ttimes[0]))/(60*60*24))#transforma em horas
                plt.xlabel('tempo(dias)')#imprime label indicando dias
	else:#caso contrario, escala em meses
                for tms in ttimes:#itera pelo array do eixo-x
                        x_scale.append((float(tms)-float(ttimes[0]))/(60*60*24*30))#transforma em horas
                plt.xlabel(u'tempo(meses -> 30 dias por m\u00EAs)')#imprime label indicando meses
	
	#gerando interpolacao
	#tvalues = np.array(tvalues)
	#x_smooth = np.linspace(x_scale.min(), x_scale.max(), 10)
	#y_smooth = spline(x_scale, tvalues, x_smooth)
	#tck = interpolate.splrep(x_scale, tvalues)
	#print tck
	
	#plotando os graficos
	plt.plot(x_scale, tvalues, '-', color='b')
	#plt.plot(ttimes, tvalues, '-', color='b')

	#trabalha as legendas
#	tiks = ax.get_xticks().tolist()#le o eixo das legendas
#	size_tks = len(tiks)#ve quantas posicoes tem pra legenda
#	for lgd in tiks:#itera essas posicoes
#		legenda.append(x_scale(size_tks))
#	tiks[:]=['bla','ble','bli','blo','blu','bal','bel','bil','bol','bul']
#	print tiks[1]
#	ax.set_xticklabels(tiks)	
	#ax.axis_date()
	#plt.tight_layout(pad=4.0, w_pad=0.5, h_pad=1.0)
	#plt.plot(x_smooth, y_smooth, '-', color='r')
	plt.title(title)
	plt.ylabel(u'temperatura(\u00B0C)')
	plt.savefig(graph, dpi=150, facecolor='none')#='#97D9CC')
	plt.close()

while True:
	delta_t = time.time()#guarda tempo inicial
	graph_gen()
	delta_t = time.time() - delta_t#calcula tempo total
	print 'tempo para gerar grafico (s) = %.3f' %delta_t
	time.sleep(300)
