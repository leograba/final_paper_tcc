#bin/bash

rm TCCmain.pdf
pdflatex --interaction=nonstopmode --draftmode TCCmain.tex
bibtex TCCmain 
pdflatex --interaction=nonstopmode --draftmode TCCmain.tex
#pdflatex --interaction=nonstopmode --draftmode TCCmain.tex
pdflatex --interaction=nonstopmode -synctex=1 TCCmain.tex
evince TCCmain.pdf &
