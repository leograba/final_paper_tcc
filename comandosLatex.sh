#bin/bash

pdflatex --interaction=nonstopmode --draftmode TCCmain.tex
bibtex TCCmain 
pdflatex --interaction=nonstopmode --draftmode TCCmain.tex
#pdflatex --interaction=nonstopmode --draftmode TCCmain.tex
pdflatex --interaction=nonstopmode -synctex=1 TCCmain.tex
rm TCCmain.pdf
evince TCCmain.pdf &
