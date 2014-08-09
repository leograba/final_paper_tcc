# final_paper_tcc
This repo contains some codes used in my final work for the undergraduation

The *Develop* branch is the default branch for development purposes, so it holds the most up-to-date files, but there is
no certainty wether there is something not working or unfinished.

## About the node modules installed

    /var/www/node_modules


**Module**           | **Status/Info**
---------------------|-------------
body-parser          | middleware used within Express
bonescript           | deprecated --> use octalbonescript
debug                | no need since Express has it
ds18b20              | currently not in use
express              | in use
express-generator    | not sure
node-pru-extended    | in use
octalbonescript      | in use
socket.io            | currently not in use (big potential)
update               | not sure

    /var/www/controle/node_modules

**Module**           | **Status/Info**
---------------------|-------------
body-parser          | middleware used within Express
cookie-parser        | not sure
debug                | not sure
express              | not sure
jade                 | not sure
morgan               | not sure
serve-favicon        | not sure / maybe

The web application relevant file structure is as follows (notice there may be more relevant files, since
I didn`t tested these are the only relevant ones):

	|-buttons.css
	|-config.css
	|-controle.html
	|-controle.js
	|-develop.html
	|-dyn_graph2.html
	|-figuras/
	| |
	| |----beer2.png
	| |----beer_compiler.png
	|-form.css
	|-header.php
	|-index2.php
	|-index2.html
	|-index.html
	|-restricted/
	| |
	| |----deleterecipe.php
	| |----listareceita.php
	| |----listrecipe.js
	| |----menu_receitas.html
	| |----newrecipe.js
	| |----newrecipe.php
	| |----novareceita.php
	| |----previewrecipe.php
	| |----recipes/
	| |----teste.php
	|-startrecipe.html
	
